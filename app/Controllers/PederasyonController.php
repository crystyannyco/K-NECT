<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserExtInfoModel;
use App\Models\AddressModel;
use App\Models\EventModel;
use App\Models\EventAttendanceModel;
use App\Models\AttendanceModel;
use App\Models\SystemLogoModel;
use App\Models\BarangayModel;
use App\Libraries\BarangayHelper;
use App\Libraries\DemographicsHelper;
use CodeIgniter\HTTP\ResponseInterface;

class PederasyonController extends BaseController
{
    public function checkEmail()
    {
        $email = trim((string)$this->request->getGet('email'));
        $isValid = filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
        if (!$isValid) {
            return $this->response->setJSON(['available' => false, 'reason' => 'invalid']);
        }
        $session = session();
        $permanentUserId = $session->get('user_id');
        $currentId = null;
        if ($permanentUserId) {
            $um = new \App\Models\UserModel();
            $me = $um->where('user_id', $permanentUserId)->first();
            $currentId = $me['id'] ?? null;
        }
        $um = isset($um) ? $um : new \App\Models\UserModel();
        $row = $um->where('email', $email)->first();
        $available = !$row || ((int)($row['id'] ?? 0) === (int)($currentId ?? -1));
        return $this->response->setJSON(['available' => (bool)$available]);
    }

    public function dashboard()
    {
        $session = session();
        $data = [
            'user_id' => $session->get('user_id'),
            'username' => $session->get('username'),
        ];

        return 
            $this->loadView('K-NECT/Pederasyon/template/header') .
            $this->loadView('K-NECT/Pederasyon/template/sidebar') .
            $this->loadView('K-NECT/Pederasyon/dashboard', $data);
    }

    public function profile()
    {
        $session = session();
        $userId = $session->get('user_id'); // This is the permanent user_id
        
        if (!$userId) {
            return redirect()->to('login')->with('error', 'Please login to view your profile.');
        }

        // Use shared ProfileController for common functionality
        $profileController = new ProfileController();
        $profileData = $profileController->getUserProfileData($userId);
        
        if (!$profileData) {
            return redirect()->to('login')->with('error', 'User profile not found.');
        }

        // Merge with session data
        $data = array_merge($profileData, [
            'username' => $session->get('username'),
        ]);

        // Resolve profile picture URL here (supports absolute URL, relative path, or filename)
        $profilePictureUrl = '';
        $defaultAvatar = base_url('assets/images/default-avatar.svg');
        $pp = (string)($data['userExtInfo']['profile_picture'] ?? '');
        if ($pp !== '') {
            if (preg_match('~^(https?:)?//~i', $pp) || str_starts_with($pp, 'data:')) {
                $profilePictureUrl = $pp; // absolute or data URL
            } elseif (strpos($pp, '/') !== false) {
                $profilePictureUrl = base_url(ltrim($pp, '/'));
            } else {
                $profilePictureUrl = base_url('uploads/profile_pictures/' . $pp);
            }
        }
    $data['profile_picture_url'] = $profilePictureUrl; // empty string if none
    $data['default_avatar_url'] = $defaultAvatar;

    // Provide demographic maps to the view (move from view to controller)
    $data['civilStatusMap'] = DemographicsHelper::civilStatusMap();
    $data['youthClassificationMap'] = DemographicsHelper::youthClassificationMap();
    $data['workStatusMap'] = DemographicsHelper::workStatusMap();
    $data['educationalBackgroundMap'] = DemographicsHelper::educationalBackgroundMap();
    $data['howManyTimesMap'] = DemographicsHelper::howManyTimesMap();

        // Provide resolved barangay name to the view
        if (!empty($data['address']) && is_array($data['address'])) {
            $barangayId = $data['address']['barangay'] ?? null;
            $data['address']['barangay_name'] = $barangayId !== null
                ? (BarangayHelper::getBarangayName($barangayId) ?: '')
                : '';
        }

        return 
            $this->loadView('K-NECT/Pederasyon/template/header') .
            $this->loadView('K-NECT/Pederasyon/template/sidebar') .
            $this->loadView('K-NECT/Pederasyon/profile', $data);
    }

    public function youthlist()
    {
        // Use shared ProfileController for common functionality
        $profileController = new ProfileController();
        $users = $profileController->getAllUsersWithExtendedInfo();
        $users = $profileController->processUsersForMemberListing($users, 'pederasyon');
        
    $data['user_list'] = $users;
    // Provide centralized maps for JS in view
    $data['field_mappings'] = DemographicsHelper::allMapsForJs();
        return 
            $this->loadView('K-NECT/Pederasyon/template/header') .
            $this->loadView('K-NECT/Pederasyon/template/sidebar') .
            $this->loadView('K-NECT/Pederasyon/youthlist', $data);
    }

    public function pedOfficers()
    {
        // Use shared ProfileController for common functionality
        $profileController = new ProfileController();
        $users = $profileController->getAllUsersWithExtendedInfo();
        $users = $profileController->processUsersForMemberListing($users, 'pederasyon');
        
        // Filter only Pederasyon Officers (user_type = 3)
        $pedOfficers = array_filter($users, function($user) {
            return isset($user['user_type']) && (int)$user['user_type'] === 3;
        });

        // Prepare barangay map and computed barangay names for the view (move helper usage to backend)
        $barangayMap = BarangayHelper::getBarangayMap();
        $pedOfficers = array_map(function ($user) use ($barangayMap) {
            $barangayId = $user['barangay'] ?? null;
            $user['barangay_name'] = $barangayId !== null && isset($barangayMap[$barangayId])
                ? $barangayMap[$barangayId]
                : ($barangayId ?? '');
            return $user;
        }, $pedOfficers);

        $data['ped_officers'] = $pedOfficers;
        // Provide centralized maps for JS in view
        $data['field_mappings'] = DemographicsHelper::allMapsForJs();
        // Explicitly provide barangay_map to avoid helper calls in the view
        $data['barangay_map'] = $barangayMap;
        return 
            $this->loadView('K-NECT/Pederasyon/template/header') .
            $this->loadView('K-NECT/Pederasyon/template/sidebar') .
            $this->loadView('K-NECT/Pederasyon/ped-officers', $data);
    }

    public function settings()
    {
        $session = session();
        $data = [
            'user_id' => $session->get('user_id'),
            'username' => $session->get('username'),
            'user_type' => 'pederasyon' // Set user type for access control
        ];

        return 
            $this->loadView('K-NECT/Pederasyon/template/header') .
            $this->loadView('K-NECT/Pederasyon/template/sidebar') .
            $this->loadView('K-NECT/Pederasyon/settings', $data);
    }

    public function accountSettings()
    {
        $session = session();
        $permanentUserId = $session->get('user_id');
        if (!$permanentUserId) {
            return redirect()->to('login')->with('error', 'Please login to view settings.');
        }

        $profileController = new ProfileController();
        $profileData = $profileController->getUserProfileData($permanentUserId);
        if (!$profileData) {
            return redirect()->to('login')->with('error', 'User profile not found.');
        }

        $data = array_merge($profileData, [
            'username' => $session->get('username'),
        ]);

        return 
            $this->loadView('K-NECT/Pederasyon/template/header') .
            $this->loadView('K-NECT/Pederasyon/template/sidebar') .
            $this->loadView('K-NECT/Pederasyon/account_settings', $data);
    }

    public function updateProfile()
    {
        $session = session();
        $permanentUserId = $session->get('user_id');
        if (!$permanentUserId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please login to update profile.']);
        }

        $userModel = new UserModel();
        $userExtModel = new UserExtInfoModel();
        $addressModel = new AddressModel();

        $userRow = $userModel->where('user_id', $permanentUserId)->first();
        if (!$userRow) {
            return redirect()->to('pederasyon/account-settings#security')->with('error', 'User not found.');
        }
        $dbUserId = $userRow['id'];

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $postedUser = [
                'first_name'   => $this->request->getPost('first_name'),
                'last_name'    => $this->request->getPost('last_name'),
                'email'        => $this->request->getPost('email'),
                'phone_number' => $this->request->getPost('phone'),
                'birthdate'    => $this->request->getPost('birthdate'),
                'sex'          => $this->request->getPost('gender'),
            ];
            $userUpdate = [];
            foreach ($postedUser as $k => $v) {
                if ((string)($userRow[$k] ?? '') !== (string)$v && $v !== null) {
                    $userUpdate[$k] = $v;
                }
            }
            if (!empty($userUpdate)) {
                $userModel->update($dbUserId, $userUpdate);
            }

            $postedAddress = [
                'zone_purok'   => $this->request->getPost('street'),
                'barangay'     => $this->request->getPost('barangay'),
                'municipality' => $this->request->getPost('city'),
                'province'     => $this->request->getPost('province'),
                'zip_code'     => $this->request->getPost('postal_code'),
            ];
            $addressRow = $addressModel->where('user_id', $dbUserId)->first();
            if ($addressRow) {
                $addrUpdate = [];
                foreach ($postedAddress as $k => $v) {
                    if ((string)($addressRow[$k] ?? '') !== (string)$v && $v !== null) {
                        $addrUpdate[$k] = $v;
                    }
                }
                if (!empty($addrUpdate)) {
                    $addressModel->where('user_id', $dbUserId)->set($addrUpdate)->update();
                }
            } else {
                $addressModel->insert(array_merge(['user_id' => $dbUserId], $postedAddress));
            }

            $file = $this->request->getFile('profile_picture');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!in_array($file->getClientMimeType(), $validTypes)) {
                    throw new \RuntimeException('Invalid file type. Please upload a JPEG, PNG, or GIF image.');
                }
                if ($file->getSize() > 2 * 1024 * 1024) {
                    throw new \RuntimeException('File size exceeds 2MB limit.');
                }

                $currentExt = $userExtModel->where('user_id', $dbUserId)->first();
                $oldPath = $currentExt['profile_picture'] ?? null;

                $targetDir = FCPATH . 'uploads/profile_pictures/';
                if (!is_dir($targetDir)) {
                    @mkdir($targetDir, 0775, true);
                }
                $newName = 'profilepic_' . uniqid() . '.' . $file->getClientExtension();
                $file->move($targetDir, $newName);
                $profilePicturePath = 'uploads/profile_pictures/' . $newName;

                $userExtModel->where('user_id', $dbUserId)->set([
                    'profile_picture' => $profilePicturePath
                ])->update();

                if (!empty($oldPath) && $oldPath !== $profilePicturePath) {
                    $candidates = [];
                    if (strpos($oldPath, '/') !== false) {
                        $candidates[] = ROOTPATH . 'public/' . ltrim($oldPath, '/');
                    } else {
                        $candidates[] = FCPATH . 'uploads/profile_pictures/' . $oldPath;
                        $candidates[] = FCPATH . 'uploads/profile/' . $oldPath;
                    }
                    foreach ($candidates as $abs) {
                        if (is_file($abs)) { @unlink($abs); break; }
                    }
                }
            }

            $db->transCommit();
            return redirect()->to('pederasyon/account-settings')->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to('pederasyon/account-settings')->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    public function updatePassword()
    {
        $session = session();
        $permanentUserId = $session->get('user_id');
        if (!$permanentUserId) {
            return redirect()->to('login')->with('error', 'Please login to change your password.');
        }

        $userModel = new UserModel();
        $userRow = $userModel->where('user_id', $permanentUserId)->first();
        if (!$userRow) {
            return redirect()->to('pederasyon/account-settings')->with('error', 'User not found.');
        }
        $dbUserId = $userRow['id'];

        $currentPassword = (string)$this->request->getPost('current_password');
        if ($currentPassword === '') {
            return redirect()->to('pederasyon/account-settings#security')->with('error', 'Please enter your current password.');
        }
        // Verify current password against Pederasyon password (hashed or plaintext),
        // fallback to generic password if Pederasyon password is not set
        $storedPed = (string)($userRow['ped_password'] ?? '');
        $validCurrent = false;
        if ($storedPed !== '') {
            $isHashed = (strlen($storedPed) === 60 && preg_match('/^\$2y\$/', $storedPed));
            $validCurrent = $isHashed ? password_verify($currentPassword, $storedPed) : ($currentPassword === $storedPed);
        } else {
            $storedGeneric = (string)($userRow['password'] ?? '');
            if ($storedGeneric !== '') {
                $validCurrent = password_verify($currentPassword, $storedGeneric);
            }
        }
        if (!$validCurrent) {
            return redirect()->to('pederasyon/account-settings#security')->with('error', 'Current password is incorrect.');
        }

    $newPassword = (string)$this->request->getPost('ped_password');
        $confirmPassword = (string)$this->request->getPost('confirm_password');
        if ($newPassword !== $confirmPassword) {
            return redirect()->to('pederasyon/account-settings#security')->with('error', 'New passwords do not match.');
        }

        $errors = [];
        if (strlen($newPassword) < 8) { $errors[] = 'at least 8 characters'; }
        if (!preg_match('/[A-Z]/', $newPassword)) { $errors[] = 'one uppercase letter'; }
        if (!preg_match('/[a-z]/', $newPassword)) { $errors[] = 'one lowercase letter'; }
        if (!preg_match('/\d/', $newPassword)) { $errors[] = 'one number'; }
        if (!preg_match('/[!@#$%^&*()_+\-={}\[\]\\|;:"\'<>.,?\/]/', $newPassword)) { $errors[] = 'one special character'; }
        if (!empty($errors)) {
            return redirect()->to('pederasyon/account-settings#security')->with('error', 'Password must contain: ' . implode(', ', $errors) . '.');
        }

        try {
            $userModel->update($dbUserId, [ 'ped_password' => password_hash($newPassword, PASSWORD_DEFAULT) ]);
            return redirect()->to('pederasyon/account-settings#security')->with('success', 'Password updated successfully.');
        } catch (\Exception $e) {
            return redirect()->to('pederasyon/account-settings#security')->with('error', 'Failed to update password: ' . $e->getMessage());
        }
    }

    public function liveAttendance($eventId)
    {
        $session = session();
        
        // Get event details
        $eventModel = new EventModel();
        $event = $eventModel->find($eventId);
        
        if (!$event) {
            return redirect()->to('pederasyon/attendance')->with('error', 'Event not found');
        }
        
        // Get attendance settings
        $eventAttendanceModel = new EventAttendanceModel();
        $attendanceSettings = $eventAttendanceModel->getEventAttendanceSettings($eventId);
        
        // Get barangay and SK logos
        $systemLogoModel = new SystemLogoModel();
        $barangayLogo = $systemLogoModel->getActiveLogoByType('barangay');
        $skLogo = $systemLogoModel->getActiveLogoByType('sk');
        
        $data = [
            'user_id' => $session->get('user_id'),
            'username' => $session->get('username'),
            'event' => $event,
            'attendance_settings' => $attendanceSettings,
            'barangay_logo' => $barangayLogo,
            'sk_logo' => $skLogo
        ];

        return view('K-NECT/Pederasyon/live_attendance', $data);
    }

    public function updateOfficerPosition()
    {
        $request = $this->request;
        
        if (!$request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $userId = $request->getPost('user_id');
        $pedPosition = $request->getPost('ped_position');

        if (empty($userId) || !is_numeric($pedPosition)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid data']);
        }

        $userModel = new UserModel();
        
        // Update the ped_position in the users table
        $updated = $userModel->update($userId, ['ped_position' => (int)$pedPosition]);
        
        if ($updated) {
            return $this->response->setJSON(['success' => true, 'message' => 'Officer position updated successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update officer position']);
        }
    }

    public function bulkUpdateOfficerPosition()
    {
        $request = $this->request;
        
        if (!$request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $officerIds = $request->getPost('officer_ids');
        $pedPosition = $request->getPost('ped_position');

        if (empty($officerIds) || !is_array($officerIds) || !is_numeric($pedPosition)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid data']);
        }

        $userModel = new UserModel();
        $updated = 0;
        
        foreach ($officerIds as $officerId) {
            if (is_numeric($officerId)) {
                $result = $userModel->update((int)$officerId, ['ped_position' => (int)$pedPosition]);
                if ($result) {
                    $updated++;
                }
            }
        }
        
        if ($updated > 0) {
            return $this->response->setJSON(['success' => true, 'message' => "Updated {$updated} officer positions successfully"]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update officer positions']);
        }
    }

    public function generateOfficialListWord()
    {
        // Preflight: Zip is required for PhpWord (DOCX)
        if (!class_exists('ZipArchive') || !extension_loaded('zip')) {
            $ini = function_exists('php_ini_loaded_file') ? (php_ini_loaded_file() ?: 'php.ini') : 'php.ini';
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Missing PHP zip extension. Enable extension=zip in ' . $ini . ' and restart the server to generate Word documents.'
            ]);
        }
        try {
            log_message('info', 'Starting Pederasyon Official List Word generation...');
            
            // Use shared ProfileController for common functionality
            $profileController = new ProfileController();
            $users = $profileController->getAllUsersWithExtendedInfo();
            $users = $profileController->processUsersForMemberListing($users, 'pederasyon');
            
            // Filter only officials (SK Chairperson and Pederasyon Officers with Accepted status)
            $officials = array_filter($users, function($user) {
                $userType = isset($user['user_type']) ? (int)$user['user_type'] : 1;
                $status = isset($user['status']) ? (int)$user['status'] : 1;
                return ($userType === 2 || $userType === 3) && $status === 2; // SK Chairperson or Pederasyon Officer, Accepted
            });

            if (empty($officials)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No officials found for the official list']);
            }

            // Get logos for the Word document
            $logos = $this->getLogosForDocument();

            // Generate Word document
            $outputWordFile = $this->generateOfficialListWordDocument($officials, $logos);
            
            if ($outputWordFile && file_exists($outputWordFile)) {
                // Return the Word file for download
                $fileName = basename($outputWordFile);
                log_message('info', 'Word document ready for download: ' . $fileName);
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Official List Word document generated successfully',
                    'download_url' => base_url('uploads/generated/' . $fileName),
                    'official_count' => count($officials)
                ]);
            } else {
                log_message('error', 'Word document file not created or does not exist');
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Error generating Word document - file not created'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in generateOfficialListWord: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    private function generateOfficialListWordDocument($officials, $logos = [])
    {
        try {
            log_message('info', 'Starting Official List Word document creation...');
            
            // Ensure composer autoloader is loaded
            if (!class_exists('\PhpOffice\PhpWord\PhpWord')) {
                require_once ROOTPATH . 'vendor/autoload.php';
            }
            
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            log_message('info', 'PHPWord instance created successfully');
        
            // Set document properties
            $properties = $phpWord->getDocInfo();
            $properties->setCreator('K-NECT System');
            $properties->setCompany('Panlungsod na Pederasyon ng mga Sangguniang Kabataan ng Iriga');
            $properties->setTitle('Pederasyon Official List');
            $properties->setDescription('Official list generated from K-NECT System');
            $properties->setCategory('Government Document');
            $properties->setSubject('Pederasyon Official List');
            
            // Add section with landscape orientation
            $section = $phpWord->addSection([
                'orientation' => 'landscape',
                'marginLeft' => 720,
                'marginRight' => 720,
                'marginTop' => 720,
                'marginBottom' => 720
            ]);
            
            // Header styles
            $headerStyle = ['name' => 'Arial', 'size' => 12, 'bold' => true];
            $subHeaderStyle = ['name' => 'Arial', 'size' => 10, 'bold' => false];
            $titleStyle = ['name' => 'Arial', 'size' => 12, 'bold' => true];
            $tableHeaderStyle = ['name' => 'Arial', 'size' => 8, 'bold' => true];
            $tableCellStyle = ['name' => 'Arial', 'size' => 8];
            
            // Create header section with logos
            $headerTable = $section->addTable([
                'borderSize' => 0,
                'borderColor' => 'FFFFFF',
                'width' => 100 * 50,
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
            ]);
            $headerTable->addRow();
            
            // Left logo cell (Pederasyon)
            $leftCell = $headerTable->addCell(2000, ['valign' => 'center']);
            if (isset($logos['pederasyon'])) {
                $logoPath = FCPATH . $logos['pederasyon']['file_path'];
                if (file_exists($logoPath)) {
                    try {
                        $leftCell->addImage($logoPath, [
                            'width' => 50.4,
                            'height' => 50.4,
                            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
                        ]);
                    } catch (\Exception $e) {
                        $leftCell->addText('PEDERASYON LOGO', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                    }
                } else {
                    $leftCell->addText('PEDERASYON LOGO', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                }
            } else {
                $leftCell->addText('PEDERASYON LOGO', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            }
            
            // Center text cell
            $centerCell = $headerTable->addCell(6000, ['valign' => 'center']);
            $centerCell->addText('REPUBLIC OF THE PHILIPPINES', $headerStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $centerCell->addText('PROVINCE OF CAMARINES SUR', $headerStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $centerCell->addText('CITY OF IRIGA', $headerStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $centerCell->addText('PANLUNGSOD NA PEDERASYON NG MGA', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $centerCell->addText('SANGGUNIANG KABATAAN NG IRIGA', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            
            // Right logo cell (Iriga City)
            $rightCell = $headerTable->addCell(2000, ['valign' => 'center']);
            if (isset($logos['iriga_city'])) {
                $logoPath = FCPATH . $logos['iriga_city']['file_path'];
                if (file_exists($logoPath)) {
                    try {
                        $rightCell->addImage($logoPath, [
                            'width' => 50.4,
                            'height' => 50.4,
                            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
                        ]);
                    } catch (\Exception $e) {
                        $rightCell->addText('IRIGA LOGO', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                    }
                } else {
                    $rightCell->addText('IRIGA LOGO', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                }
            } else {
                $rightCell->addText('IRIGA LOGO', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            }
            
            // Add horizontal line and title
            $section->addTextBreak();
            $section->addText('PANLUNGSOD NA PEDERASYON NG MGA KABATAAN', $titleStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $section->addText('OFFICIAL LIST', $titleStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $section->addTextBreak();
            
            // Create data table
            $table = $section->addTable([
                'borderSize' => 4,
                'borderColor' => '000000',
                'cellMargin' => 20,
                'width' => 100 * 50,
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
            ]);
            
            // Add table header
            $table->addRow();
            $table->addCell(1200)->addText('User ID', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell(1800)->addText('Barangay', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell(2500)->addText('Name', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell(800)->addText('Age', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell(1200)->addText('Birthday', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell(800)->addText('Sex', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell(1700)->addText('Position', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            
            // Process officials and add to table
            foreach ($officials as $official) {
                $userType = isset($official['user_type']) ? (int)$official['user_type'] : 1;
                $status = isset($official['status']) ? (int)$official['status'] : 1;
                
                if (($userType === 2 || $userType === 3) && $status === 2) {
                    // Format data
                    $userId = $official['user_id'] ?: '';
                    $barangay = BarangayHelper::getBarangayName($official['barangay']);
                    
                    $fullName = esc($official['last_name']);
                    if (!empty($official['first_name'])) {
                        $fullName .= ', ' . esc($official['first_name']);
                    }
                    if (!empty($official['middle_name'])) {
                        $fullName .= ' ' . esc($official['middle_name']);
                    }
                    
                    $age = $official['age'] ?: '';
                    $birthday = $official['birthdate'] ? date('m/d/Y', strtotime($official['birthdate'])) : '';
                    $sex = $official['sex'] == '1' ? 'Male' : ($official['sex'] == '2' ? 'Female' : '');
                    
                    // Determine specific position
                    $position = '';
                    if ($userType === 3) { // Pederasyon Officer
                        $pedPosition = isset($official['ped_position']) ? (int)$official['ped_position'] : 0;
                        switch($pedPosition) {
                            case 1: $position = 'Pederasyon President'; break;
                            case 2: $position = 'Pederasyon Vice President'; break;
                            case 3: $position = 'Pederasyon Secretary'; break;
                            case 4: $position = 'Pederasyon Treasurer'; break;
                            case 5: $position = 'Pederasyon Auditor'; break;
                            case 6: $position = 'Pederasyon Public Information Officer'; break;
                            case 7: $position = 'Pederasyon Sergeant at Arms'; break;
                            default: $position = 'Pederasyon Officer'; break;
                        }
                    } else if ($userType === 2) { // SK Chairperson
                        $userPosition = isset($official['position']) ? (int)$official['position'] : 0;
                        switch($userPosition) {
                            case 1: $position = 'SK Chairperson'; break;
                            case 2: $position = 'SK Councilor'; break;
                            case 3: $position = 'SK Secretary'; break;
                            case 4: $position = 'SK Treasurer'; break;
                            default: $position = 'SK Official'; break;
                        }
                    }
                    
                    // Add row to table
                    $table->addRow();
                    $table->addCell()->addText($userId, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                    $table->addCell()->addText($barangay, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                    $table->addCell()->addText($fullName, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                    $table->addCell()->addText($age, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                    $table->addCell()->addText($birthday, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                    $table->addCell()->addText($sex, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                    $table->addCell()->addText($position, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                }
            }
            
            // Add signature section
            $section->addTextBreak(2);
            $signatureTable = $section->addTable([
                'borderSize' => 0,
                'borderColor' => 'FFFFFF',
                'width' => 100 * 50,
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
            ]);
            $signatureTable->addRow();
            
            // Prepared by
            $preparedCell = $signatureTable->addCell(4000, ['valign' => 'center']);
            $preparedCell->addText('Prepared by:', $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 800]);
            $preparedCell->addText('_________________________', $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 100]);
            
            // Find secretary name from officials
            $secretaryName = '';
            foreach ($officials as $official) {
                if (isset($official['ped_position']) && (int)$official['ped_position'] === 3) {
                    $secretaryName = esc($official['first_name']) . ' ';
                    if (!empty($official['middle_name'])) {
                        $secretaryName .= esc($official['middle_name']) . ' ';
                    }
                    $secretaryName .= esc($official['last_name']);
                    break;
                }
            }
            $preparedCell->addText($secretaryName ?: '________________', ['name' => 'Arial', 'size' => 8, 'bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $preparedCell->addText('Pederasyon Secretary', ['name' => 'Arial', 'size' => 8, 'bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            
            // Approved by
            $approvedCell = $signatureTable->addCell(4000, ['valign' => 'center']);
            $approvedCell->addText('Approved by:', $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 800]);
            $approvedCell->addText('_________________________', $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 100]);
            
            // Find president name from officials
            $presidentName = '';
            foreach ($officials as $official) {
                if (isset($official['ped_position']) && (int)$official['ped_position'] === 1) {
                    $presidentName = esc($official['first_name']) . ' ';
                    if (!empty($official['middle_name'])) {
                        $presidentName .= esc($official['middle_name']) . ' ';
                    }
                    $presidentName .= esc($official['last_name']);
                    break;
                }
            }
            $approvedCell->addText($presidentName ?: '________________', ['name' => 'Arial', 'size' => 8, 'bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $approvedCell->addText('Pederasyon President', ['name' => 'Arial', 'size' => 8, 'bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            
            // Save the document
            $outputDir = FCPATH . 'uploads/generated/';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $fileName = 'PEDERASYON_Official_List_' . date('Y-m-d_H-i-s') . '.docx';
            $outputPath = $outputDir . $fileName;
            
            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save($outputPath);
            
            log_message('info', 'Word document saved to: ' . $outputPath);
            return $outputPath;
            
        } catch (\Exception $e) {
            log_message('error', 'Error in generateOfficialListWordDocument: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    private function getLogosForDocument()
    {
        try {
            $systemLogoModel = new \App\Models\SystemLogoModel();
            $logos = [];
            
            // Get Pederasyon logo (should be global or null barangay_id)
            $pederasyonLogo = $systemLogoModel->where('logo_type', 'pederasyon')
                                             ->where('is_active', true)
                                             ->orderBy('created_at', 'DESC')
                                             ->first();
            if ($pederasyonLogo) {
                $logos['pederasyon'] = $pederasyonLogo;
                log_message('info', 'Pederasyon logo found: ' . $pederasyonLogo['file_path']);
            } else {
                // Fallback to check direct files
                $logoFiles = glob(FCPATH . 'uploads/logos/pederasyon_logo_*');
                if (!empty($logoFiles)) {
                    $latestFile = end($logoFiles);
                    $logos['pederasyon'] = ['file_path' => str_replace(FCPATH, '', $latestFile)];
                    log_message('info', 'Pederasyon logo found via fallback: ' . $logos['pederasyon']['file_path']);
                } else {
                    log_message('warning', 'Pederasyon logo not found');
                }
            }
            
            // Get Iriga City logo (should be global)
            $irigaLogo = $systemLogoModel->where('logo_type', 'iriga_city')
                                        ->where('is_active', true)
                                        ->orderBy('created_at', 'DESC')
                                        ->first();
            if ($irigaLogo) {
                $logos['iriga_city'] = $irigaLogo;
                log_message('info', 'Iriga City logo found: ' . $irigaLogo['file_path']);
            } else {
                // Fallback to check direct files
                $logoFiles = glob(FCPATH . 'uploads/logos/iriga_city_logo_*');
                if (!empty($logoFiles)) {
                    $latestFile = end($logoFiles);
                    $logos['iriga_city'] = ['file_path' => str_replace(FCPATH, '', $latestFile)];
                    log_message('info', 'Iriga City logo found via fallback: ' . $logos['iriga_city']['file_path']);
                } else {
                    log_message('warning', 'Iriga City logo not found');
                }
            }
            
            log_message('info', 'Total logos found: ' . count($logos));
            return $logos;
        } catch (\Exception $e) {
            log_message('error', 'Error fetching logos: ' . $e->getMessage());
            
            // Emergency fallback - try to find logos directly
            $logos = [];
            $logoFiles = glob(FCPATH . 'uploads/logos/pederasyon_logo_*');
            if (!empty($logoFiles)) {
                $latestFile = end($logoFiles);
                $logos['pederasyon'] = ['file_path' => str_replace(FCPATH, '', $latestFile)];
            }
            
            $logoFiles = glob(FCPATH . 'uploads/logos/iriga_city_logo_*');
            if (!empty($logoFiles)) {
                $latestFile = end($logoFiles);
                $logos['iriga_city'] = ['file_path' => str_replace(FCPATH, '', $latestFile)];
            }
            
            return $logos;
        }
    }

    public function generateOfficialListExcel()
    {
        // Preflight: Zip is required for PhpSpreadsheet (XLSX)
        if (!class_exists('ZipArchive') || !extension_loaded('zip')) {
            $ini = function_exists('php_ini_loaded_file') ? (php_ini_loaded_file() ?: 'php.ini') : 'php.ini';
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Missing PHP zip extension. Enable extension=zip in ' . $ini . ' and restart the server to generate Excel files.'
            ]);
        }
        try {
            log_message('info', 'Starting Pederasyon Official List Excel generation...');
            
            // Use shared ProfileController for common functionality
            $profileController = new ProfileController();
            $users = $profileController->getAllUsersWithExtendedInfo();
            $users = $profileController->processUsersForMemberListing($users, 'pederasyon');
            
            // Filter only officials (SK Chairman and Pederasyon Officers with Accepted status)
            $officials = array_filter($users, function($user) {
                $userType = isset($user['user_type']) ? (int)$user['user_type'] : 1;
                $status = isset($user['status']) ? (int)$user['status'] : 1;
                return ($userType === 2 || $userType === 3) && $status === 2; // SK Chairman or Pederasyon Officer, Accepted
            });

            if (empty($officials)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No officials found for the official list']);
            }

            // Generate Excel document (no logos)
            $outputExcelFile = $this->generateOfficialListExcelDocument($officials);
            
            if ($outputExcelFile && file_exists($outputExcelFile)) {
                // Return the Excel file for download
                $fileName = basename($outputExcelFile);
                log_message('info', 'Excel document ready for download: ' . $fileName);
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Official List Excel document generated successfully',
                    'download_url' => base_url('uploads/generated/' . $fileName),
                    'official_count' => count($officials)
                ]);
            } else {
                log_message('error', 'Excel document file not created or does not exist');
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Error generating Excel document - file not created'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in generateOfficialListExcel: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    private function generateOfficialListExcelDocument($officials)
    {
        try {
            // Ensure PhpSpreadsheet is loaded
            if (!class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
                require_once ROOTPATH . 'vendor/autoload.php';
            }

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set page orientation to landscape
            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            $sheet->getPageSetup()->setFitToPage(true);
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            // Start content from row 1 (no logos)
            $currentRow = 1;

            // Header text
            $sheet->setCellValue('A' . $currentRow, 'REPUBLIC OF THE PHILIPPINES');
            $sheet->mergeCells('A' . $currentRow . ':G' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $currentRow++;

            $sheet->setCellValue('A' . $currentRow, 'PROVINCE OF CAMARINES SUR');
            $sheet->mergeCells('A' . $currentRow . ':G' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $currentRow++;

            $sheet->setCellValue('A' . $currentRow, 'CITY OF IRIGA');
            $sheet->mergeCells('A' . $currentRow . ':G' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $currentRow++;

            $sheet->setCellValue('A' . $currentRow, 'PANLUNGSOD NA PEDERASYON NG MGA');
            $sheet->mergeCells('A' . $currentRow . ':G' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(false)->setSize(10);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $currentRow++;

            $sheet->setCellValue('A' . $currentRow, 'SANGGUNIANG KABATAAN NG IRIGA');
            $sheet->mergeCells('A' . $currentRow . ':G' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(false)->setSize(10);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $currentRow++;

            $currentRow++; // Empty row

            // Title
            $sheet->setCellValue('A' . $currentRow, 'PANLUNGSOD NA PEDERASYON NG MGA KABATAAN');
            $sheet->mergeCells('A' . $currentRow . ':G' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $currentRow++;

            $sheet->setCellValue('A' . $currentRow, 'OFFICIAL LIST');
            $sheet->mergeCells('A' . $currentRow . ':G' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $currentRow++;

            $currentRow++; // Empty row

            // Table headers
            $headers = [
                'A' => 'User ID',
                'B' => 'Barangay',
                'C' => 'Name',
                'D' => 'Age',
                'E' => 'Birthday',
                'F' => 'Sex',
                'G' => 'Position'
            ];

            // Add and style headers
            $headerRowNum = $currentRow;
            foreach ($headers as $col => $header) {
                $sheet->setCellValue($col . $currentRow, $header);
                $sheet->getStyle($col . $currentRow)->getFont()->setBold(true)->setSize(10);
                $sheet->getStyle($col . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle($col . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $sheet->getStyle($col . $currentRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $sheet->getStyle($col . $currentRow)->getFill()->getStartColor()->setRGB('E8E8E8'); // Light gray background
                $sheet->getStyle($col . $currentRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            }
            $currentRow++;

            // Add data rows with proper formatting
            $dataStartRow = $currentRow;
            foreach ($officials as $official) {
                $userType = isset($official['user_type']) ? (int)$official['user_type'] : 1;
                $status = isset($official['status']) ? (int)$official['status'] : 1;
                
                if (($userType === 2 || $userType === 3) && $status === 2) {
                    // Format data
                    $userId = $official['user_id'] ?: '';
                    $barangay = BarangayHelper::getBarangayName($official['barangay']);
                    
                    $fullName = esc($official['last_name']);
                    if (!empty($official['first_name'])) {
                        $fullName .= ', ' . esc($official['first_name']);
                    }
                    if (!empty($official['middle_name'])) {
                        $fullName .= ' ' . esc($official['middle_name']);
                    }
                    
                    $age = $official['age'] ?: '';
                    $birthday = $official['birthdate'] ? date('m/d/Y', strtotime($official['birthdate'])) : '';
                    $sex = $official['sex'] == '1' ? 'Male' : ($official['sex'] == '2' ? 'Female' : '');
                    
                    // Determine specific position
                    $position = '';
                    if ($userType === 3) { // Pederasyon Officer
                        $pedPosition = isset($official['ped_position']) ? (int)$official['ped_position'] : 0;
                        switch($pedPosition) {
                            case 1: $position = 'Pederasyon President'; break;
                            case 2: $position = 'Pederasyon Vice President'; break;
                            case 3: $position = 'Pederasyon Secretary'; break;
                            case 4: $position = 'Pederasyon Treasurer'; break;
                            case 5: $position = 'Pederasyon Auditor'; break;
                            case 6: $position = 'Pederasyon Public Information Officer'; break;
                            case 7: $position = 'Pederasyon Sergeant at Arms'; break;
                            default: $position = 'Pederasyon Officer'; break;
                        }
                    } else if ($userType === 2) { // SK Chairperson
                        $userPosition = isset($official['position']) ? (int)$official['position'] : 0;
                        switch($userPosition) {
                            case 1: $position = 'SK Chairperson'; break;
                            case 2: $position = 'SK Councilor'; break;
                            case 3: $position = 'SK Secretary'; break;
                            case 4: $position = 'SK Treasurer'; break;
                            default: $position = 'SK Official'; break;
                        }
                    }
                    
                    // Add data to Excel with proper formatting
                    $sheet->setCellValue('A' . $currentRow, $userId);
                    $sheet->setCellValue('B' . $currentRow, $barangay);
                    $sheet->setCellValue('C' . $currentRow, $fullName);
                    $sheet->setCellValue('D' . $currentRow, $age);
                    $sheet->setCellValue('E' . $currentRow, $birthday);
                    $sheet->setCellValue('F' . $currentRow, $sex);
                    $sheet->setCellValue('G' . $currentRow, $position);
                    
                    // Style data cells
                    foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G'] as $col) {
                        $sheet->getStyle($col . $currentRow)->getFont()->setSize(9);
                        $sheet->getStyle($col . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle($col . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                        $sheet->getStyle($col . $currentRow)->getAlignment()->setWrapText(true);
                        $sheet->getStyle($col . $currentRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    }
                    
                    // Set row height for better readability
                    $sheet->getRowDimension($currentRow)->setRowHeight(20);
                    
                    $currentRow++;
                }
            }

            // Add spacing before signatures
            $currentRow += 2;

            // Find signature names
            $secretaryName = '';
            $presidentName = '';
            foreach ($officials as $official) {
                if (isset($official['ped_position'])) {
                    $pedPosition = (int)$official['ped_position'];
                    if ($pedPosition === 3) { // Secretary
                        $secretaryName = esc($official['first_name']) . ' ';
                        if (!empty($official['middle_name'])) {
                            $secretaryName .= esc($official['middle_name']) . ' ';
                        }
                        $secretaryName .= esc($official['last_name']);
                    } elseif ($pedPosition === 1) { // President
                        $presidentName = esc($official['first_name']) . ' ';
                        if (!empty($official['middle_name'])) {
                            $presidentName .= esc($official['middle_name']) . ' ';
                        }
                        $presidentName .= esc($official['last_name']);
                    }
                }
            }

            // Signature section with proper spacing and formatting
            $signatureStartRow = $currentRow;
            
            // Prepared by section
            $sheet->mergeCells('A' . $currentRow . ':C' . $currentRow);
            $sheet->setCellValue('A' . $currentRow, 'Prepared by:');
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(false)->setSize(10);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            // Approved by section
            $sheet->mergeCells('E' . $currentRow . ':G' . $currentRow);
            $sheet->setCellValue('E' . $currentRow, 'Approved by:');
            $sheet->getStyle('E' . $currentRow)->getFont()->setBold(false)->setSize(10);
            $sheet->getStyle('E' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            $currentRow += 3; // Space for signature lines
            
            // Secretary name and title
            $sheet->mergeCells('A' . $currentRow . ':C' . $currentRow);
            $sheet->setCellValue('A' . $currentRow, $secretaryName ?: '________________');
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(10);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            // President name
            $sheet->mergeCells('E' . $currentRow . ':G' . $currentRow);
            $sheet->setCellValue('E' . $currentRow, $presidentName ?: '________________');
            $sheet->getStyle('E' . $currentRow)->getFont()->setBold(true)->setSize(10);
            $sheet->getStyle('E' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            $currentRow++;
            
            // Titles
            $sheet->mergeCells('A' . $currentRow . ':C' . $currentRow);
            $sheet->setCellValue('A' . $currentRow, 'Pederasyon Secretary');
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(9);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            $sheet->mergeCells('E' . $currentRow . ':G' . $currentRow);
            $sheet->setCellValue('E' . $currentRow, 'Pederasyon President');
            $sheet->getStyle('E' . $currentRow)->getFont()->setBold(true)->setSize(9);
            $sheet->getStyle('E' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Set optimal column widths
            $sheet->getColumnDimension('A')->setWidth(12); // User ID
            $sheet->getColumnDimension('B')->setWidth(18); // Barangay
            $sheet->getColumnDimension('C')->setWidth(28); // Name
            $sheet->getColumnDimension('D')->setWidth(6);  // Age
            $sheet->getColumnDimension('E')->setWidth(12); // Birthday
            $sheet->getColumnDimension('F')->setWidth(8);  // Sex
            $sheet->getColumnDimension('G')->setWidth(32); // Position

            // Set row heights for headers
            $sheet->getRowDimension($headerRowNum)->setRowHeight(25);

            // Auto-fit page margins
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setBottom(0.5);
            $sheet->getPageMargins()->setLeft(0.5);
            $sheet->getPageMargins()->setRight(0.5);

            // Save the document
            $outputDir = FCPATH . 'uploads/generated/';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $fileName = 'PEDERASYON_Official_List_' . date('Y-m-d_H-i-s') . '.xlsx';
            $outputPath = $outputDir . $fileName;
            
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($outputPath);
            
            log_message('info', 'Excel document saved to: ' . $outputPath);
            return $outputPath;
            
        } catch (\Exception $e) {
            log_message('error', 'Error in generateOfficialListExcelDocument: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    public function generateCredentials()
    {
        // Preflight: Zip is required for PhpSpreadsheet (XLSX)
        if (!class_exists('ZipArchive') || !extension_loaded('zip')) {
            $ini = function_exists('php_ini_loaded_file') ? (php_ini_loaded_file() ?: 'php.ini') : 'php.ini';
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Missing PHP zip extension. Enable extension=zip in ' . $ini . ' and restart the server to generate credentials.'
            ]);
        }
        try {
            log_message('info', 'Starting Pederasyon Credentials generation...');
            
            // Use shared ProfileController for common functionality
            $profileController = new ProfileController();
            $users = $profileController->getAllUsersWithExtendedInfo();
            $users = $profileController->processUsersForMemberListing($users, 'pederasyon');
            
            // Filter only officials (SK Chairman and Pederasyon Officers with Accepted status)
            $officials = array_filter($users, function($user) {
                $userType = isset($user['user_type']) ? (int)$user['user_type'] : 1;
                $status = isset($user['status']) ? (int)$user['status'] : 1;
                return ($userType === 2 || $userType === 3) && $status === 2; // SK Chairman or Pederasyon Officer, Accepted
            });

            if (empty($officials)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No officials found for credentials generation']);
            }

            // Generate credentials document
            $outputCredentialsFile = $this->generateCredentialsDocument($officials);
            
            if ($outputCredentialsFile && file_exists($outputCredentialsFile)) {
                // Return the credentials file for download
                $fileName = basename($outputCredentialsFile);
                log_message('info', 'Credentials document ready for download: ' . $fileName);
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Credentials document generated successfully',
                    'download_url' => base_url('uploads/generated/' . $fileName),
                    'official_count' => count($officials)
                ]);
            } else {
                log_message('error', 'Credentials document file not created or does not exist');
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Error generating credentials document - file not created'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in generateCredentials: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    private function generateCredentialsDocument($officials)
    {
        try {
            // Ensure autoloader is available
            if (!class_exists('PhpOffice\\PhpSpreadsheet\\Spreadsheet')) {
                require_once ROOTPATH . 'vendor/autoload.php';
            }

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set page orientation to landscape
            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            $sheet->getPageSetup()->setFitToPage(true);
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            // Start content from row 1
            $currentRow = 1;

            // Header text
            $sheet->setCellValue('A' . $currentRow, 'REPUBLIC OF THE PHILIPPINES');
            $sheet->mergeCells('A' . $currentRow . ':H' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $currentRow++;

            $sheet->setCellValue('A' . $currentRow, 'PROVINCE OF CAMARINES SUR');
            $sheet->mergeCells('A' . $currentRow . ':H' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $currentRow++;

            $sheet->setCellValue('A' . $currentRow, 'CITY OF IRIGA');
            $sheet->mergeCells('A' . $currentRow . ':H' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $currentRow++;

            $sheet->setCellValue('A' . $currentRow, 'PANLUNGSOD NA PEDERASYON NG MGA');
            $sheet->mergeCells('A' . $currentRow . ':H' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(false)->setSize(10);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $currentRow++;

            $sheet->setCellValue('A' . $currentRow, 'SANGGUNIANG KABATAAN NG IRIGA');
            $sheet->mergeCells('A' . $currentRow . ':H' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(false)->setSize(10);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $currentRow++;

            $currentRow++; // Empty row

            // Title
            $sheet->setCellValue('A' . $currentRow, 'PANLUNGSOD NA PEDERASYON NG MGA KABATAAN');
            $sheet->mergeCells('A' . $currentRow . ':H' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $currentRow++;

            $sheet->setCellValue('A' . $currentRow, 'OFFICIALS CREDENTIALS');
            $sheet->mergeCells('A' . $currentRow . ':H' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $currentRow++;

            $currentRow++; // Empty row

            // Table headers
            $headers = [
                'A' => 'User ID',
                'B' => 'Full Name',
                'C' => 'Position',
                'D' => 'Barangay',
                'E' => 'Email',
                'F' => 'Phone',
                'G' => 'Status',
                'H' => 'Date Appointed'
            ];

            // Add and style headers
            $headerRowNum = $currentRow;
            foreach ($headers as $col => $header) {
                $sheet->setCellValue($col . $currentRow, $header);
                $sheet->getStyle($col . $currentRow)->getFont()->setBold(true)->setSize(10);
                $sheet->getStyle($col . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle($col . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $sheet->getStyle($col . $currentRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $sheet->getStyle($col . $currentRow)->getFill()->getStartColor()->setRGB('E8E8E8'); // Light gray background
                $sheet->getStyle($col . $currentRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            }
            $currentRow++;

            // Add data rows with proper formatting
            $dataStartRow = $currentRow;
            foreach ($officials as $official) {
                $userType = isset($official['user_type']) ? (int)$official['user_type'] : 1;
                $status = isset($official['status']) ? (int)$official['status'] : 1;
                
                if (($userType === 2 || $userType === 3) && $status === 2) {
                    // Format data
                    $userId = $official['user_id'] ?: '';
                    $barangay = BarangayHelper::getBarangayName($official['barangay']);
                    
                    // Full name format: First Middle Last
                    $fullName = '';
                    if (!empty($official['first_name'])) {
                        $fullName .= esc($official['first_name']);
                    }
                    if (!empty($official['middle_name'])) {
                        $fullName .= ' ' . esc($official['middle_name']);
                    }
                    if (!empty($official['last_name'])) {
                        $fullName .= ' ' . esc($official['last_name']);
                    }
                    $fullName = trim($fullName);
                    
                    $email = isset($official['email']) ? $official['email'] : 'N/A';
                    $phone = isset($official['phone_number']) ? $official['phone_number'] : 'N/A';
                    $dateAppointed = isset($official['created_at']) ? date('m/d/Y', strtotime($official['created_at'])) : 'N/A';
                    
                    // Determine specific position
                    $position = '';
                    if ($userType === 3) { // Pederasyon Officer
                        $pedPosition = isset($official['ped_position']) ? (int)$official['ped_position'] : 0;
                        switch($pedPosition) {
                            case 1: $position = 'Pederasyon President'; break;
                            case 2: $position = 'Pederasyon Vice President'; break;
                            case 3: $position = 'Pederasyon Secretary'; break;
                            case 4: $position = 'Pederasyon Treasurer'; break;
                            case 5: $position = 'Pederasyon Auditor'; break;
                            case 6: $position = 'Pederasyon Public Information Officer'; break;
                            case 7: $position = 'Pederasyon Sergeant at Arms'; break;
                            default: $position = 'Pederasyon Officer'; break;
                        }
                    } else if ($userType === 2) { // SK Chairperson
                        $userPosition = isset($official['position']) ? (int)$official['position'] : 0;
                        switch($userPosition) {
                            case 1: $position = 'SK Chairperson'; break;
                            case 2: $position = 'SK Councilor'; break;
                            case 3: $position = 'SK Secretary'; break;
                            case 4: $position = 'SK Treasurer'; break;
                            default: $position = 'SK Official'; break;
                        }
                    }
                    
                    // Add data to Excel with proper formatting
                    $sheet->setCellValue('A' . $currentRow, $userId);
                    $sheet->setCellValue('B' . $currentRow, $fullName);
                    $sheet->setCellValue('C' . $currentRow, $position);
                    $sheet->setCellValue('D' . $currentRow, $barangay);
                    $sheet->setCellValue('E' . $currentRow, $email);
                    $sheet->setCellValue('F' . $currentRow, $phone);
                    $sheet->setCellValue('G' . $currentRow, 'Active');
                    $sheet->setCellValue('H' . $currentRow, $dateAppointed);
                    
                    // Style data cells
                    foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'] as $col) {
                        $sheet->getStyle($col . $currentRow)->getFont()->setSize(9);
                        $sheet->getStyle($col . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle($col . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                        $sheet->getStyle($col . $currentRow)->getAlignment()->setWrapText(true);
                        $sheet->getStyle($col . $currentRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    }
                    
                    // Set row height for better readability
                    $sheet->getRowDimension($currentRow)->setRowHeight(25);
                    
                    $currentRow++;
                }
            }

            // Set optimal column widths
            $sheet->getColumnDimension('A')->setWidth(12); // User ID
            $sheet->getColumnDimension('B')->setWidth(25); // Full Name
            $sheet->getColumnDimension('C')->setWidth(30); // Position
            $sheet->getColumnDimension('D')->setWidth(18); // Barangay
            $sheet->getColumnDimension('E')->setWidth(25); // Email
            $sheet->getColumnDimension('F')->setWidth(15); // Phone
            $sheet->getColumnDimension('G')->setWidth(10); // Status
            $sheet->getColumnDimension('H')->setWidth(12); // Date Appointed

            // Set row heights for headers
            $sheet->getRowDimension($headerRowNum)->setRowHeight(25);

            // Auto-fit page margins
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setBottom(0.5);
            $sheet->getPageMargins()->setLeft(0.5);
            $sheet->getPageMargins()->setRight(0.5);

            // Save the document
            $outputDir = FCPATH . 'uploads/generated/';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $fileName = 'PEDERASYON_Officials_Credentials_' . date('Y-m-d_H-i-s') . '.xlsx';
            $outputPath = $outputDir . $fileName;
            
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($outputPath);
            
            log_message('info', 'Credentials document saved to: ' . $outputPath);
            return $outputPath;
            
        } catch (\Exception $e) {
            log_message('error', 'Error in generateCredentialsDocument: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    public function getCredentialsData()
    {
        try {
            log_message('info', 'Getting credentials data...');
            
            // Use shared ProfileController for common functionality
            $profileController = new ProfileController();
            $users = $profileController->getAllUsersWithExtendedInfo();
            $users = $profileController->processUsersForMemberListing($users, 'pederasyon');
            
            // Filter only officials (SK Chairman and Pederasyon Officers with Accepted status)
            $officials = array_filter($users, function($user) {
                $userType = isset($user['user_type']) ? (int)$user['user_type'] : 1;
                $status = isset($user['status']) ? (int)$user['status'] : 1;
                return ($userType === 2 || $userType === 3) && $status === 2; // SK Chairman or Pederasyon Officer, Accepted
            });

            $skCredentials = [];
            $pederasyonCredentials = [];

            foreach ($officials as $official) {
                $userType = (int)$official['user_type'];
                $userId = $official['user_id'] ?: '';
                $barangay = \App\Libraries\BarangayHelper::getBarangayName($official['barangay']);
                
                $fullName = esc($official['last_name']);
                if (!empty($official['first_name'])) {
                    $fullName .= ', ' . esc($official['first_name']);
                }
                if (!empty($official['middle_name'])) {
                    $fullName .= ' ' . esc($official['middle_name']);
                }

                // SK Credentials (both SK Chairman and Pederasyon Officers get SK credentials)
                $skUsername = $official['sk_username'] ?? '';
                $skPassword = $official['sk_password'] ?? '';
                
                if ($skUsername && $skPassword) {
                    $skCredentials[] = [
                        'userId' => $userId,
                        'name' => $fullName,
                        'barangay' => $barangay,
                        'position' => 'SK Chairperson',
                        'username' => $skUsername,
                        'password' => $skPassword
                    ];
                }

                // Pederasyon Credentials (only for Pederasyon Officers)
                if ($userType === 3) {
                    $pedUsername = $official['ped_username'] ?? '';
                    $pedPassword = $official['ped_password'] ?? '';
                    
                    if ($pedUsername && $pedPassword) {
                        // Determine specific position
                        $position = 'Pederasyon Officer';
                        $pedPosition = isset($official['ped_position']) ? (int)$official['ped_position'] : 0;
                        switch($pedPosition) {
                            case 1: $position = 'Pederasyon President'; break;
                            case 2: $position = 'Pederasyon Vice President'; break;
                            case 3: $position = 'Pederasyon Secretary'; break;
                            case 4: $position = 'Pederasyon Treasurer'; break;
                            case 5: $position = 'Pederasyon Auditor'; break;
                            case 6: $position = 'Pederasyon Public Information Officer'; break;
                            case 7: $position = 'Pederasyon Sergeant at Arms'; break;
                        }

                        $pederasyonCredentials[] = [
                            'userId' => $userId,
                            'name' => $fullName,
                            'barangay' => $barangay,
                            'position' => $position,
                            'username' => $pedUsername,
                            'password' => $pedPassword
                        ];
                    }
                }
            }

            log_message('info', 'Found ' . count($skCredentials) . ' SK credentials and ' . count($pederasyonCredentials) . ' Pederasyon credentials');

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'sk' => $skCredentials,
                    'pederasyon' => $pederasyonCredentials
                ],
                'counts' => [
                    'sk' => count($skCredentials),
                    'pederasyon' => count($pederasyonCredentials),
                    'total' => count($skCredentials) + count($pederasyonCredentials)
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error in getCredentialsData: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    public function generateCredentialsPDF()
    {
        try {
            log_message('info', 'Starting Pederasyon Credentials PDF generation...');
            
            // Use shared ProfileController for common functionality
            $profileController = new ProfileController();
            $users = $profileController->getAllUsersWithExtendedInfo();
            $users = $profileController->processUsersForMemberListing($users, 'pederasyon');
            
            // Filter only officials (SK Chairman and Pederasyon Officers with Accepted status)
            $officials = array_filter($users, function($user) {
                $userType = isset($user['user_type']) ? (int)$user['user_type'] : 1;
                $status = isset($user['status']) ? (int)$user['status'] : 1;
                return ($userType === 2 || $userType === 3) && $status === 2; // SK Chairman or Pederasyon Officer, Accepted
            });

            if (empty($officials)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No officials found for credentials PDF generation']);
            }

            // Generate credentials PDF document
            $outputPdfFile = $this->generateCredentialsPDFDocument($officials);
            
            if ($outputPdfFile && file_exists($outputPdfFile)) {
                // Return the credentials PDF file for download
                $fileName = basename($outputPdfFile);
                log_message('info', 'Credentials PDF document ready for download: ' . $fileName);
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Credentials PDF document generated successfully',
                    'download_url' => base_url('uploads/generated/' . $fileName),
                    'official_count' => count($officials)
                ]);
            } else {
                log_message('error', 'Credentials PDF document file not created or does not exist');
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Error generating credentials PDF document - file not created'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in generateCredentialsPDF: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    private function generateCredentialsPDFDocument($officials)
    {
        try {
            log_message('info', 'Starting Credentials PDF document creation...');
            
            // Get logos for the PDF document
            $logos = $this->getLogosForDocument();
            
            // Create HTML content similar to official list format
            $html = '<!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <style>
                    body { 
                        font-family: Arial, sans-serif; 
                        margin: 0; 
                        padding: 20px;
                    }
                    .header-section {
                        text-align: center;
                        margin-bottom: 30px;
                    }
                    .header-table {
                        width: 100%;
                        margin-bottom: 20px;
                    }
                    .header-table td {
                        vertical-align: middle;
                        text-align: center;
                    }
                    .logo-cell {
                        width: 80px;
                    }
                    .header-text {
                        font-size: 12px;
                        font-weight: bold;
                        line-height: 1.2;
                        margin: 0;
                    }
                    .sub-header-text {
                        font-size: 10px;
                        font-weight: normal;
                        line-height: 1.2;
                        margin: 0;
                    }
                    .title-text {
                        font-size: 12px;
                        font-weight: bold;
                        margin: 10px 0;
                    }
                    .section-title {
                        font-size: 11px;
                        font-weight: bold;
                        margin: 20px 0 10px 0;
                        padding: 5px;
                        background-color: #f0f0f0;
                        border-left: 4px solid #0066cc;
                        text-align: left;
                    }
                    .credentials-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                        font-size: 8px;
                    }
                    .credentials-table th,
                    .credentials-table td {
                        border: 1px solid #000;
                        padding: 4px;
                        text-align: center;
                        vertical-align: middle;
                    }
                    .credentials-table th {
                        background-color: #f0f0f0;
                        font-weight: bold;
                    }
                    .monospace {
                        font-family: Arial, sans-serif;
                    }
                </style>
            </head>
            <body>';
            
            // Header with logos (similar to official list)
            $html .= '<div class="header-section">
                <table class="header-table">
                    <tr>';
            
            // Left logo (Pederasyon)
            $html .= '<td class="logo-cell">';
            if (isset($logos['pederasyon'])) {
                $logoPath = FCPATH . $logos['pederasyon']['file_path'];
                if (file_exists($logoPath)) {
                    $logoData = base64_encode(file_get_contents($logoPath));
                    
                    // Get MIME type by file extension
                    $extension = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
                    switch($extension) {
                        case 'jpg':
                        case 'jpeg':
                            $logoMime = 'image/jpeg';
                            break;
                        case 'png':
                            $logoMime = 'image/png';
                            break;
                        case 'gif':
                            $logoMime = 'image/gif';
                            break;
                        case 'webp':
                            $logoMime = 'image/webp';
                            break;
                        default:
                            $logoMime = 'image/jpeg';
                            break;
                    }
                    
                    $html .= '<img src="data:' . $logoMime . ';base64,' . $logoData . '" style="width: 60px; height: auto;">';
                } else {
                    $html .= '<div style="width: 60px; height: 60px; border: 1px solid #ccc; display: flex; align-items: center; justify-content: center; font-size: 8px;">LOGO</div>';
                }
            } else {
                $html .= '<div style="width: 60px; height: 60px; border: 1px solid #ccc; display: flex; align-items: center; justify-content: center; font-size: 8px;">LOGO</div>';
            }
            $html .= '</td>';
            
            // Center text
            $html .= '<td>
                <p class="header-text">REPUBLIC OF THE PHILIPPINES</p>
                <p class="header-text">PROVINCE OF CAMARINES SUR</p>
                <p class="header-text">CITY OF IRIGA</p>
                <p class="sub-header-text">PANLUNGSOD NA PEDERASYON NG MGA</p>
                <p class="sub-header-text">SANGGUNIANG KABATAAN NG IRIGA</p>
            </td>';
            
            // Right logo (Iriga City)
            $html .= '<td class="logo-cell">';
            if (isset($logos['iriga_city'])) {
                $logoPath = FCPATH . $logos['iriga_city']['file_path'];
                if (file_exists($logoPath)) {
                    $logoData = base64_encode(file_get_contents($logoPath));
                    
                    // Get MIME type by file extension
                    $extension = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
                    switch($extension) {
                        case 'jpg':
                        case 'jpeg':
                            $logoMime = 'image/jpeg';
                            break;
                        case 'png':
                            $logoMime = 'image/png';
                            break;
                        case 'gif':
                            $logoMime = 'image/gif';
                            break;
                        case 'webp':
                            $logoMime = 'image/webp';
                            break;
                        default:
                            $logoMime = 'image/jpeg';
                            break;
                    }
                    
                    $html .= '<img src="data:' . $logoMime . ';base64,' . $logoData . '" style="width: 60px; height: auto;">';
                } else {
                    $html .= '<div style="width: 60px; height: 60px; border: 1px solid #ccc; display: flex; align-items: center; justify-content: center; font-size: 8px;">LOGO</div>';
                }
            } else {
                $html .= '<div style="width: 60px; height: 60px; border: 1px solid #ccc; display: flex; align-items: center; justify-content: center; font-size: 8px;">LOGO</div>';
            }
            $html .= '</td>';
            
            $html .= '</tr></table>
                <hr style="border: 1px solid #000; margin: 10px 0;">
                <p class="title-text">PANLUNGSOD NA PEDERASYON NG MGA KABATAAN</p>
                <p class="title-text">OFFICIALS CREDENTIALS</p>
            </div>';
            
            // Separate SK and Pederasyon officials
            // Rule: Type 3 (Pederasyon) also appears under SK; Type 2 appears only under SK
            $skOfficials = [];
            $pederasyonOfficials = [];
            
            foreach ($officials as $official) {
                $userType = isset($official['user_type']) ? (int)$official['user_type'] : 1;
                if ($userType === 2) { // SK
                    $skOfficials[] = $official;
                } else if ($userType === 3) { // Pederasyon
                    // Include in both SK and Pederasyon lists per rule (3 = 3 and 2)
                    $skOfficials[] = $official;
                    $pederasyonOfficials[] = $official;
                }
            }
            
            // SK Officials Section
            if (!empty($skOfficials)) {
                $html .= '<div class="section-title">SANGGUNIANG KABATAAN OFFICIALS LOGIN CREDENTIALS</div>
                    <table class="credentials-table">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Full Name</th>
                                <th>Barangay</th>
                                <th>Position</th>
                                <th>SK Username</th>
                                <th>SK Password</th>
                            </tr>
                        </thead>
                        <tbody>';
                
                foreach ($skOfficials as $official) {
                    $fullName = trim(($official['first_name'] ?? '') . ' ' . ($official['middle_name'] ?? '') . ' ' . ($official['last_name'] ?? ''));
                    $barangay = BarangayHelper::getBarangayName($official['barangay']);
                    
                    // Check if password is hashed and mask it
                    $skPassword = $official['sk_password'] ?? 'N/A';
                    if ($skPassword !== 'N/A' && (
                        strpos($skPassword, '$2y$') === 0 || 
                        strpos($skPassword, '$2b$') === 0 ||
                        strlen($skPassword) > 20
                    )) {
                        $skPassword = '********';
                    }
                    
                    $html .= '<tr>
                        <td>' . esc($official['user_id'] ?? '') . '</td>
                        <td>' . esc($fullName) . '</td>
                        <td>' . esc($barangay) . '</td>
                        <td>SK Chairperson</td>
                        <td class="monospace">' . esc($official['sk_username'] ?? 'N/A') . '</td>
                        <td class="monospace">' . esc($skPassword) . '</td>
                    </tr>';
                }
                
                $html .= '</tbody></table>';
            }
            
            // Pederasyon Officials Section
            if (!empty($pederasyonOfficials)) {
                $html .= '<div class="section-title">PEDERASYON OFFICIALS LOGIN CREDENTIALS</div>
                    <table class="credentials-table">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Full Name</th>
                                <th>Barangay</th>
                                <th>Position</th>
                                <th>Ped Username</th>
                                <th>Ped Password</th>
                            </tr>
                        </thead>
                        <tbody>';
                
                foreach ($pederasyonOfficials as $official) {
                    $fullName = trim(($official['first_name'] ?? '') . ' ' . ($official['middle_name'] ?? '') . ' ' . ($official['last_name'] ?? ''));
                    $barangay = BarangayHelper::getBarangayName($official['barangay']);
                    
                    $pedPosition = isset($official['ped_position']) ? (int)$official['ped_position'] : 0;
                    $position = 'Pederasyon Officer';
                    switch($pedPosition) {
                        case 1: $position = 'Pederasyon President'; break;
                        case 2: $position = 'Pederasyon Vice President'; break;
                        case 3: $position = 'Pederasyon Secretary'; break;
                        case 4: $position = 'Pederasyon Treasurer'; break;
                        case 5: $position = 'Pederasyon Auditor'; break;
                        case 6: $position = 'Pederasyon Public Information Officer'; break;
                        case 7: $position = 'Pederasyon Sergeant at Arms'; break;
                    }
                    
                    // Check if password is hashed and mask it
                    $pedPassword = $official['ped_password'] ?? 'N/A';
                    if ($pedPassword !== 'N/A' && (
                        strpos($pedPassword, '$2y$') === 0 || 
                        strpos($pedPassword, '$2b$') === 0 ||
                        strlen($pedPassword) > 20
                    )) {
                        $pedPassword = '********';
                    }
                    
                    $html .= '<tr>
                        <td>' . esc($official['user_id'] ?? '') . '</td>
                        <td>' . esc($fullName) . '</td>
                        <td>' . esc($barangay) . '</td>
                        <td>' . esc($position) . '</td>
                        <td class="monospace">' . esc($official['ped_username'] ?? 'N/A') . '</td>
                        <td class="monospace">' . esc($pedPassword) . '</td>
                    </tr>';
                }
                
                $html .= '</tbody></table>';
            }
            
            $html .= '</body></html>';
            
            // Use DomPDF to generate PDF from HTML
            require_once FCPATH . '../vendor/autoload.php';
            
            $dompdf = new \Dompdf\Dompdf([
                'isPhpEnabled' => true
            ]);
            
            // Set paper size and orientation
            $dompdf->setPaper('A4', 'landscape');
            
            // Load HTML content
            $dompdf->loadHtml($html);
            
            // Render the HTML as PDF
            $dompdf->render();
            
            // Save the document
            $outputDir = FCPATH . 'uploads/generated/';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $fileName = 'PEDERASYON_Officials_Credentials_' . date('Y-m-d_H-i-s') . '.pdf';
            $outputPath = $outputDir . $fileName;
            
            // Save PDF to file
            file_put_contents($outputPath, $dompdf->output());
            
            log_message('info', 'Credentials PDF document saved to: ' . $outputPath);
            return $outputPath;
            
        } catch (\Exception $e) {
            log_message('error', 'Error in generateCredentialsPDFDocument: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    public function generateCredentialsWord()
    {
        // Preflight: Zip is required for PhpWord (DOCX)
        if (!class_exists('ZipArchive') || !extension_loaded('zip')) {
            $ini = function_exists('php_ini_loaded_file') ? (php_ini_loaded_file() ?: 'php.ini') : 'php.ini';
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Missing PHP zip extension. Enable extension=zip in ' . $ini . ' and restart the server to generate Word credentials.'
            ]);
        }
        try {
            log_message('info', 'Starting Pederasyon Credentials Word generation...');
            
            // Get the active tab from request
            $requestData = json_decode($this->request->getBody(), true);
            $activeTab = $requestData['activeTab'] ?? 'sk';
            
            // Use shared ProfileController for common functionality
            $profileController = new ProfileController();
            $users = $profileController->getAllUsersWithExtendedInfo();
            $users = $profileController->processUsersForMemberListing($users, 'pederasyon');
            
        // Filter only officials based on active tab
        $officials = array_filter($users, function($user) use ($activeTab) {
                $userType = isset($user['user_type']) ? (int)$user['user_type'] : 1;
                $status = isset($user['status']) ? (int)$user['status'] : 1;
                
                if ($status !== 2) return false; // Must be Accepted
                
                if ($activeTab === 'sk') {
            // Show SK credentials for both SK (2) and Pederasyon (3)
            return ($userType === 2 || $userType === 3);
                } elseif ($activeTab === 'pederasyon') {
                    return $userType === 3; // Only Pederasyon Officers
                }
                
                return ($userType === 2 || $userType === 3); // Fallback: both types
            });

            if (empty($officials)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No officials found for credentials Word generation']);
            }

            // Generate credentials Word document
            $outputWordFile = $this->generateCredentialsWordDocument($officials, $activeTab);
            
            if ($outputWordFile && file_exists($outputWordFile)) {
                // Return the credentials Word file for download
                $fileName = basename($outputWordFile);
                log_message('info', 'Credentials Word document ready for download: ' . $fileName);
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Credentials Word document generated successfully',
                    'download_url' => base_url('uploads/generated/' . $fileName),
                    'official_count' => count($officials)
                ]);
            } else {
                log_message('error', 'Credentials Word document file not created or does not exist');
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Error generating credentials Word document - file not created'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in generateCredentialsWord: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    private function generateCredentialsWordDocument($officials, $activeTab = 'sk')
    {
        try {
            log_message('info', 'Starting Credentials Word document creation...');
            
            require_once FCPATH . '../vendor/autoload.php';
            
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            log_message('info', 'PHPWord instance created successfully');
        
            // Set document properties
            $properties = $phpWord->getDocInfo();
            $properties->setCreator('K-NECT System');
            $properties->setCompany('Panlungsod na Pederasyon ng mga Sangguniang Kabataan ng Iriga');
            $properties->setTitle('Pederasyon Officials Credentials');
            $properties->setDescription('Login credentials for Pederasyon officials generated from K-NECT System');
            $properties->setCategory('Government Document');
            $properties->setSubject('Officials Credentials');
            
            // Add section with landscape orientation
            $section = $phpWord->addSection([
                'orientation' => 'landscape',
                'marginLeft' => 720,
                'marginRight' => 720,
                'marginTop' => 720,
                'marginBottom' => 720
            ]);
            
            // Header styles
            $headerStyle = ['name' => 'Arial', 'size' => 12, 'bold' => true];
            $subHeaderStyle = ['name' => 'Arial', 'size' => 10, 'bold' => false];
            $titleStyle = ['name' => 'Arial', 'size' => 12, 'bold' => true];
            $tableHeaderStyle = ['name' => 'Arial', 'size' => 8, 'bold' => true];
            $tableCellStyle = ['name' => 'Arial', 'size' => 8];
            
            // Get logos for the Word document
            $logos = $this->getLogosForDocument();
            
            // Create header section with logos
            $headerTable = $section->addTable([
                'borderSize' => 0,
                'borderColor' => 'FFFFFF',
                'width' => 100 * 50,
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
            ]);
            $headerTable->addRow();
            
            // Left logo cell (Pederasyon)
            $leftCell = $headerTable->addCell(2000, ['valign' => 'center']);
            if (isset($logos['pederasyon'])) {
                $logoPath = FCPATH . $logos['pederasyon']['file_path'];
                if (file_exists($logoPath)) {
                    try {
                        $leftCell->addImage($logoPath, [
                            'width' => 60,
                            'height' => 60,
                            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
                        ]);
                    } catch (\Exception $e) {
                        $leftCell->addText('PEDERASYON LOGO', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                    }
                } else {
                    $leftCell->addText('PEDERASYON LOGO', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                }
            } else {
                $leftCell->addText('PEDERASYON LOGO', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            }
            
            // Center text cell
            $centerCell = $headerTable->addCell(6000, ['valign' => 'center']);
            $centerCell->addText('REPUBLIC OF THE PHILIPPINES', $headerStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $centerCell->addText('PROVINCE OF CAMARINES SUR', $headerStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $centerCell->addText('CITY OF IRIGA', $headerStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $centerCell->addText('PANLUNGSOD NA PEDERASYON NG MGA', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $centerCell->addText('SANGGUNIANG KABATAAN NG IRIGA', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            
            // Right logo cell (Iriga City)
            $rightCell = $headerTable->addCell(2000, ['valign' => 'center']);
            if (isset($logos['iriga_city'])) {
                $logoPath = FCPATH . $logos['iriga_city']['file_path'];
                if (file_exists($logoPath)) {
                    try {
                        $rightCell->addImage($logoPath, [
                            'width' => 60,
                            'height' => 60,
                            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
                        ]);
                    } catch (\Exception $e) {
                        $rightCell->addText('IRIGA LOGO', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                    }
                } else {
                    $rightCell->addText('IRIGA LOGO', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                }
            } else {
                $rightCell->addText('IRIGA LOGO', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            }
            
            // Add horizontal line and title
            $section->addTextBreak();
            $section->addText('PANLUNGSOD NA PEDERASYON NG MGA KABATAAN', $titleStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $section->addText('OFFICIALS CREDENTIALS', $titleStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $section->addTextBreak();

            // Separate SK and Pederasyon officials (3 appears in both lists)
            $skOfficials = [];
            $pederasyonOfficials = [];
            
            foreach ($officials as $official) {
                $userType = isset($official['user_type']) ? (int)$official['user_type'] : 1;
                if ($userType === 2) { // SK
                    $skOfficials[] = $official;
                } else if ($userType === 3) { // Pederasyon
                    $skOfficials[] = $official;
                    $pederasyonOfficials[] = $official;
                }
            }

            // SK Officials Section - only show if SK tab is active
            if ($activeTab === 'sk' && !empty($skOfficials)) {
                $section->addText('SANGGUNIANG KABATAAN OFFICIALS LOGIN CREDENTIALS', $titleStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $section->addTextBreak();
                
                // Create SK credentials table
                $skTable = $section->addTable([
                    'borderSize' => 4,
                    'borderColor' => '000000',
                    'cellMargin' => 20,
                    'width' => 100 * 50,
                    'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
                ]);
                
                // Add SK table header
                $skTable->addRow();
                $skTable->addCell(1000)->addText('User ID', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $skTable->addCell(2200)->addText('Full Name', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $skTable->addCell(1500)->addText('Barangay', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $skTable->addCell(1300)->addText('Position', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $skTable->addCell(1500)->addText('SK Username', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $skTable->addCell(1500)->addText('SK Password', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                
                foreach ($skOfficials as $official) {
                    $fullName = trim(($official['first_name'] ?? '') . ' ' . ($official['middle_name'] ?? '') . ' ' . ($official['last_name'] ?? ''));
                    $barangay = BarangayHelper::getBarangayName($official['barangay']);
                    
                    // Check if password is hashed and mask it
                    $skPassword = $official['sk_password'] ?? 'N/A';
                    if ($skPassword !== 'N/A' && (
                        strpos($skPassword, '$2y$') === 0 || 
                        strpos($skPassword, '$2b$') === 0 ||
                        strlen($skPassword) > 20
                    )) {
                        $skPassword = '********';
                    }
                    
                    $skTable->addRow();
                    $skTable->addCell()->addText(esc($official['user_id'] ?? ''), $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                    $skTable->addCell()->addText(esc($fullName), $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                    $skTable->addCell()->addText(esc($barangay), $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                    $skTable->addCell()->addText('SK Chairperson', $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                    $skTable->addCell()->addText(esc($official['sk_username'] ?? 'N/A'), $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                    $skTable->addCell()->addText(esc($skPassword), $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                }
                
                $section->addTextBreak(2);
            }

            // Pederasyon Officials Section - only show if Pederasyon tab is active
            if ($activeTab === 'pederasyon' && !empty($pederasyonOfficials)) {
                $section->addText('PEDERASYON OFFICIALS LOGIN CREDENTIALS', $titleStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $section->addTextBreak();
                
                // Create Pederasyon credentials table
                $pedTable = $section->addTable([
                    'borderSize' => 4,
                    'borderColor' => '000000',
                    'cellMargin' => 20,
                    'width' => 100 * 50,
                    'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
                ]);
                
                // Add Pederasyon table header
                $pedTable->addRow();
                $pedTable->addCell(1000)->addText('User ID', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $pedTable->addCell(2000)->addText('Full Name', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $pedTable->addCell(1200)->addText('Barangay', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $pedTable->addCell(1800)->addText('Position', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $pedTable->addCell(1500)->addText('Ped Username', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $pedTable->addCell(1500)->addText('Ped Password', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                
                foreach ($pederasyonOfficials as $official) {
                    $fullName = trim(($official['first_name'] ?? '') . ' ' . ($official['middle_name'] ?? '') . ' ' . ($official['last_name'] ?? ''));
                    $barangay = BarangayHelper::getBarangayName($official['barangay']);
                    
                    $pedPosition = isset($official['ped_position']) ? (int)$official['ped_position'] : 0;
                    $position = 'Pederasyon Officer';
                    switch($pedPosition) {
                        case 1: $position = 'Pederasyon President'; break;
                        case 2: $position = 'Pederasyon Vice President'; break;
                        case 3: $position = 'Pederasyon Secretary'; break;
                        case 4: $position = 'Pederasyon Treasurer'; break;
                        case 5: $position = 'Pederasyon Auditor'; break;
                        case 6: $position = 'Pederasyon Public Information Officer'; break;
                        case 7: $position = 'Pederasyon Sergeant at Arms'; break;
                    }
                    
                    // Check if password is hashed and mask it
                    $pedPassword = $official['ped_password'] ?? 'N/A';
                    if ($pedPassword !== 'N/A' && (
                        strpos($pedPassword, '$2y$') === 0 || 
                        strpos($pedPassword, '$2b$') === 0 ||
                        strlen($pedPassword) > 20
                    )) {
                        $pedPassword = '********';
                    }
                    
                    $pedTable->addRow();
                    $pedTable->addCell()->addText(esc($official['user_id'] ?? ''), $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                    $pedTable->addCell()->addText(esc($fullName), $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                    $pedTable->addCell()->addText(esc($barangay), $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                    $pedTable->addCell()->addText(esc($position), $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                    $pedTable->addCell()->addText(esc($official['ped_username'] ?? 'N/A'), $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                    $pedTable->addCell()->addText(esc($pedPassword), $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                }
            }

            // Save the document
            $outputDir = FCPATH . 'uploads/generated/';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $fileName = 'PEDERASYON_Officials_Credentials_' . date('Y-m-d_H-i-s') . '.docx';
            $outputPath = $outputDir . $fileName;
            
            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save($outputPath);
            
            log_message('info', 'Credentials Word document saved to: ' . $outputPath);
            return $outputPath;
            
        } catch (\Exception $e) {
            log_message('error', 'Error in generateCredentialsWordDocument: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    public function generateCredentialsExcel()
    {
        try {
            log_message('info', 'Starting Pederasyon Credentials Excel generation...');
            
            // Get the active tab from request
            $requestData = json_decode($this->request->getBody(), true);
            $activeTab = $requestData['activeTab'] ?? 'sk';
            
            // Use shared ProfileController for common functionality
            $profileController = new ProfileController();
            $users = $profileController->getAllUsersWithExtendedInfo();
            $users = $profileController->processUsersForMemberListing($users, 'pederasyon');
            
        // Filter only officials based on active tab
        $officials = array_filter($users, function($user) use ($activeTab) {
                $userType = isset($user['user_type']) ? (int)$user['user_type'] : 1;
                $status = isset($user['status']) ? (int)$user['status'] : 1;
                
                if ($status !== 2) return false; // Must be Accepted
                
                if ($activeTab === 'sk') {
            return ($userType === 2 || $userType === 3);
                } elseif ($activeTab === 'pederasyon') {
                    return $userType === 3; // Only Pederasyon Officers
                }
                
                return ($userType === 2 || $userType === 3); // Fallback: both types
            });

            if (empty($officials)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No officials found for credentials Excel generation']);
            }

            // Generate Excel document
            $outputExcelFile = $this->generateCredentialsExcelDocument($officials, $activeTab);
            
            if ($outputExcelFile && file_exists($outputExcelFile)) {
                // Return the Excel file for download
                $fileName = basename($outputExcelFile);
                log_message('info', 'Credentials Excel document ready for download: ' . $fileName);
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Credentials Excel document generated successfully',
                    'download_url' => base_url('uploads/generated/' . $fileName),
                    'official_count' => count($officials)
                ]);
            } else {
                log_message('error', 'Credentials Excel document file not created or does not exist');
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Error generating credentials Excel document - file not created'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in generateCredentialsExcel: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    private function generateCredentialsExcelDocument($officials, $activeTab = 'sk')
    {
        try {
            require_once FCPATH . '../vendor/autoload.php';

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set page orientation to landscape
            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            $sheet->getPageSetup()->setFitToPage(true);
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            // Start content from row 1
            $currentRow = 1;

            // Header text (same format as official list)
            $sheet->setCellValue('A' . $currentRow, 'REPUBLIC OF THE PHILIPPINES');
            $sheet->mergeCells('A' . $currentRow . ':F' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $currentRow++;

            $sheet->setCellValue('A' . $currentRow, 'PROVINCE OF CAMARINES SUR');
            $sheet->mergeCells('A' . $currentRow . ':F' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $currentRow++;

            $sheet->setCellValue('A' . $currentRow, 'CITY OF IRIGA');
            $sheet->mergeCells('A' . $currentRow . ':F' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $currentRow++;

            $sheet->setCellValue('A' . $currentRow, 'PANLUNGSOD NA PEDERASYON NG MGA');
            $sheet->mergeCells('A' . $currentRow . ':F' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(false)->setSize(10);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $currentRow++;

            $sheet->setCellValue('A' . $currentRow, 'SANGGUNIANG KABATAAN NG IRIGA');
            $sheet->mergeCells('A' . $currentRow . ':F' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(false)->setSize(10);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $currentRow++;

            $currentRow++; // Empty row

            // Title
            $sheet->setCellValue('A' . $currentRow, 'PANLUNGSOD NA PEDERASYON NG MGA KABATAAN');
            $sheet->mergeCells('A' . $currentRow . ':F' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $currentRow++;

            $sheet->setCellValue('A' . $currentRow, 'OFFICIALS CREDENTIALS');
            $sheet->mergeCells('A' . $currentRow . ':F' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $currentRow++;

            $currentRow++; // Empty row

            // Separate SK and Pederasyon officials (3 appears in both lists)
            $skOfficials = [];
            $pederasyonOfficials = [];
            
            foreach ($officials as $official) {
                $userType = isset($official['user_type']) ? (int)$official['user_type'] : 1;
                if ($userType === 2) { // SK
                    $skOfficials[] = $official;
                } else if ($userType === 3) { // Pederasyon
                    $skOfficials[] = $official;
                    $pederasyonOfficials[] = $official;
                }
            }

            // SK Officials Section - only show if SK tab is active
            if ($activeTab === 'sk' && !empty($skOfficials)) {
                $sheet->setCellValue('A' . $currentRow, 'SANGGUNIANG KABATAAN OFFICIALS LOGIN CREDENTIALS');
                $sheet->mergeCells('A' . $currentRow . ':F' . $currentRow);
                $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(11);
                $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A' . $currentRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $sheet->getStyle('A' . $currentRow)->getFill()->getStartColor()->setARGB('FFE8E8E8');
                $currentRow++;

                // SK table headers
                $headers = ['User ID', 'Full Name', 'Barangay', 'Position', 'SK Username', 'SK Password'];
                $headerRowNum = $currentRow;
                
                foreach ($headers as $col => $header) {
                    $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
                    $sheet->setCellValue($columnLetter . $currentRow, $header);
                    $sheet->getStyle($columnLetter . $currentRow)->getFont()->setBold(true)->setSize(9);
                    $sheet->getStyle($columnLetter . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle($columnLetter . $currentRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $sheet->getStyle($columnLetter . $currentRow)->getFill()->getStartColor()->setARGB('FFF0F0F0');
                    $sheet->getStyle($columnLetter . $currentRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                }
                $currentRow++;

                // Add SK officials data
                $dataStartRow = $currentRow;
                foreach ($skOfficials as $official) {
                    $fullName = trim(($official['first_name'] ?? '') . ' ' . ($official['middle_name'] ?? '') . ' ' . ($official['last_name'] ?? ''));
                    $barangay = BarangayHelper::getBarangayName($official['barangay']);
                    
                    // Check if password is hashed and mask it
                    $skPassword = $official['sk_password'] ?? 'N/A';
                    if ($skPassword !== 'N/A' && (
                        strpos($skPassword, '$2y$') === 0 || 
                        strpos($skPassword, '$2b$') === 0 ||
                        strlen($skPassword) > 20
                    )) {
                        $skPassword = '********';
                    }

                    $rowData = [
                        $official['user_id'] ?? '',
                        $fullName,
                        $barangay,
                        'SK Chairperson',
                        $official['sk_username'] ?? 'N/A',
                        $skPassword
                    ];

                    foreach ($rowData as $col => $value) {
                        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
                        $sheet->setCellValue($columnLetter . $currentRow, $value);
                        $sheet->getStyle($columnLetter . $currentRow)->getFont()->setSize(8);
                        $sheet->getStyle($columnLetter . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle($columnLetter . $currentRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        
                        // Use Arial font for all columns including usernames and passwords
                        $sheet->getStyle($columnLetter . $currentRow)->getFont()->setName('Arial');
                    }
                    $currentRow++;
                }
                
                $currentRow += 2; // Add spacing
            }

            // Pederasyon Officials Section - only show if Pederasyon tab is active
            if ($activeTab === 'pederasyon' && !empty($pederasyonOfficials)) {
                $sheet->setCellValue('A' . $currentRow, 'PEDERASYON OFFICIALS LOGIN CREDENTIALS');
                $sheet->mergeCells('A' . $currentRow . ':F' . $currentRow);
                $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(11);
                $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A' . $currentRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $sheet->getStyle('A' . $currentRow)->getFill()->getStartColor()->setARGB('FFE8E8E8');
                $currentRow++;

                // Pederasyon table headers
                $headers = ['User ID', 'Full Name', 'Barangay', 'Position', 'Ped Username', 'Ped Password'];
                $headerRowNum = $currentRow;
                
                foreach ($headers as $col => $header) {
                    $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
                    $sheet->setCellValue($columnLetter . $currentRow, $header);
                    $sheet->getStyle($columnLetter . $currentRow)->getFont()->setBold(true)->setSize(9);
                    $sheet->getStyle($columnLetter . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle($columnLetter . $currentRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $sheet->getStyle($columnLetter . $currentRow)->getFill()->getStartColor()->setARGB('FFF0F0F0');
                    $sheet->getStyle($columnLetter . $currentRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                }
                $currentRow++;

                // Add Pederasyon officials data
                $dataStartRow = $currentRow;
                foreach ($pederasyonOfficials as $official) {
                    $fullName = trim(($official['first_name'] ?? '') . ' ' . ($official['middle_name'] ?? '') . ' ' . ($official['last_name'] ?? ''));
                    $barangay = BarangayHelper::getBarangayName($official['barangay']);
                    
                    $pedPosition = isset($official['ped_position']) ? (int)$official['ped_position'] : 0;
                    $position = 'Pederasyon Officer';
                    switch($pedPosition) {
                        case 1: $position = 'Pederasyon President'; break;
                        case 2: $position = 'Pederasyon Vice President'; break;
                        case 3: $position = 'Pederasyon Secretary'; break;
                        case 4: $position = 'Pederasyon Treasurer'; break;
                        case 5: $position = 'Pederasyon Auditor'; break;
                        case 6: $position = 'Pederasyon Public Information Officer'; break;
                        case 7: $position = 'Pederasyon Sergeant at Arms'; break;
                    }
                    
                    // Check if password is hashed and mask it
                    $pedPassword = $official['ped_password'] ?? 'N/A';
                    if ($pedPassword !== 'N/A' && (
                        strpos($pedPassword, '$2y$') === 0 || 
                        strpos($pedPassword, '$2b$') === 0 ||
                        strlen($pedPassword) > 20
                    )) {
                        $pedPassword = '********';
                    }

                    $rowData = [
                        $official['user_id'] ?? '',
                        $fullName,
                        $barangay,
                        $position,
                        $official['ped_username'] ?? 'N/A',
                        $pedPassword
                    ];

                    foreach ($rowData as $col => $value) {
                        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
                        $sheet->setCellValue($columnLetter . $currentRow, $value);
                        $sheet->getStyle($columnLetter . $currentRow)->getFont()->setSize(8);
                        $sheet->getStyle($columnLetter . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle($columnLetter . $currentRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        
                        // Use Arial font for all columns including usernames and passwords
                        $sheet->getStyle($columnLetter . $currentRow)->getFont()->setName('Arial');
                    }
                    $currentRow++;
                }
            }

            // Set optimal column widths
            $sheet->getColumnDimension('A')->setWidth(12); // User ID
            $sheet->getColumnDimension('B')->setWidth(25); // Full Name
            $sheet->getColumnDimension('C')->setWidth(18); // Barangay
            $sheet->getColumnDimension('D')->setWidth(25); // Position
            $sheet->getColumnDimension('E')->setWidth(15); // Username
            $sheet->getColumnDimension('F')->setWidth(15); // Password

            // Auto-fit page margins
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setBottom(0.5);
            $sheet->getPageMargins()->setLeft(0.5);
            $sheet->getPageMargins()->setRight(0.5);

            // Save the document
            $outputDir = FCPATH . 'uploads/generated/';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $fileName = 'PEDERASYON_Officials_Credentials_' . date('Y-m-d_H-i-s') . '.xlsx';
            $outputPath = $outputDir . $fileName;
            
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($outputPath);
            
            log_message('info', 'Credentials Excel document saved to: ' . $outputPath);
            return $outputPath;
            
        } catch (\Exception $e) {
            log_message('error', 'Error in generateCredentialsExcelDocument: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * Generate Attendance Report Excel
     */
    public function generateAttendanceReportExcel($eventId)
    {
        try {
            log_message('info', 'Starting Pederasyon Attendance Report Excel generation for event: ' . $eventId);
            
            // Get event and attendance data
            $eventModel = new \App\Models\EventModel();
            $event = $eventModel->find($eventId);
            
            if (!$event) {
                return $this->response->setJSON(['success' => false, 'message' => 'Event not found']);
            }
            
            // Get attendance records for this event (similar to SK implementation)
            $attendanceData = $this->getAttendanceDataForEvent($eventId);
            $attendanceRecords = $attendanceData['records'];
            $barangayName = $attendanceData['barangay_name'];
            
            if (empty($attendanceRecords)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No attendance records found for this event']);
            }
            
            // Generate Excel document
            $outputFile = $this->generateAttendanceExcelDocument($event, $attendanceRecords, $barangayName);
            
            if ($outputFile && file_exists($outputFile)) {
                $fileName = basename($outputFile);
                log_message('info', 'Pederasyon Excel document ready for download: ' . $fileName);
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Attendance report Excel document generated successfully',
                    'download_url' => base_url('uploads/generated/' . $fileName),
                    'record_count' => count($attendanceRecords)
                ]);
            } else {
                log_message('error', 'Excel document file not created or does not exist');
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Error generating Excel document - file not created'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in Pederasyon generateAttendanceReportExcel: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Generate Attendance Report Word
     */
    public function generateAttendanceReportWord($eventId)
    {
        try {
            log_message('info', 'Starting Pederasyon Attendance Report Word generation for event: ' . $eventId);
            
            // Get event and attendance data
            $eventModel = new \App\Models\EventModel();
            $event = $eventModel->find($eventId);
            
            if (!$event) {
                return $this->response->setJSON(['success' => false, 'message' => 'Event not found']);
            }
            
            // Get attendance records for this event
            $attendanceData = $this->getAttendanceDataForEvent($eventId);
            $attendanceRecords = $attendanceData['records'];
            $barangayName = $attendanceData['barangay_name'];
            
            if (empty($attendanceRecords)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No attendance records found for this event']);
            }
            
            // Get logos for the Word document
            $logos = $this->getLogosForDocument();
            
            // Generate Word document
            $outputFile = $this->generateAttendanceWordDocument($event, $attendanceRecords, $logos, $barangayName);
            
            if ($outputFile && file_exists($outputFile)) {
                $fileName = basename($outputFile);
                log_message('info', 'Pederasyon Word document ready for download: ' . $fileName);
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Attendance report Word document generated successfully',
                    'download_url' => base_url('uploads/generated/' . $fileName),
                    'record_count' => count($attendanceRecords)
                ]);
            } else {
                log_message('error', 'Word document file not created or does not exist');
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Error generating Word document - file not created'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in Pederasyon generateAttendanceReportWord: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get attendance data for event (similar to SK implementation)
     */
    private function getAttendanceDataForEvent($eventId)
    {
        try {
            $attendanceModel = new \App\Models\AttendanceModel();
            $userModel = new \App\Models\UserModel();
            $addressModel = new \App\Models\AddressModel();
            
            // Get attendance records for this event
            $attendanceRecords = $attendanceModel->where('event_id', $eventId)->findAll();
            
            $barangayName = null;
            
            // Enhance records with user information
            foreach ($attendanceRecords as &$record) {
                // Get user information
                if (!empty($record['user_id'])) {
                    $user = $userModel->where('user_id', $record['user_id'])->first();
                    if ($user) {
                        $record['permanent_user_id'] = $user['user_id'];
                        $record['user_name'] = trim($user['first_name'] . ' ' . ($user['middle_name'] ? $user['middle_name'] . ' ' : '') . $user['last_name']);
                        
                        // Get address information for zone and barangay
                        $address = $addressModel->where('user_id', $user['id'])->first();
                        if ($address) {
                            $record['zone_purok'] = $address['zone_purok'];
                            // Store the first barangay name found for document header
                            if (!$barangayName && !empty($address['barangay'])) {
                                // Try to get barangay name using helper if available
                                if (class_exists('\App\Libraries\BarangayHelper')) {
                                    $barangayName = BarangayHelper::getBarangayName($address['barangay']);
                                } else {
                                    // Fallback - use the address barangay field directly
                                    $barangayName = $address['barangay'];
                                }
                            }
                            // Also store barangay name in record for table display
                            if (class_exists('\App\Libraries\BarangayHelper')) {
                                $record['barangay_name'] = BarangayHelper::getBarangayName($address['barangay']);
                            } else {
                                $record['barangay_name'] = $address['barangay'];
                            }
                        }
                    }
                }
            }
            
            return [
                'records' => $attendanceRecords,
                'barangay_name' => $barangayName
            ];
            
        } catch (\Exception $e) {
            log_message('error', 'Error in getAttendanceDataForEvent: ' . $e->getMessage());
            return [
                'records' => [],
                'barangay_name' => null
            ];
        }
    }
    
    /**
     * Generate Excel document for attendance report
     */
    private function generateAttendanceExcelDocument($event, $attendanceRecords, $barangayName = null)
    {
        try {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set document properties
            $spreadsheet->getProperties()
                ->setCreator('K-NECT System - Pederasyon')
                ->setLastModifiedBy('K-NECT System')
                ->setTitle('Pederasyon Attendance Report - ' . $event['title'])
                ->setSubject('Event Attendance Report')
                ->setDescription('Generated attendance report for event: ' . $event['title']);
            
            // Get logos
            $logos = $this->getLogosForDocument();
            
            $row = 1;
            $logoRowHeight = 25;
            
            // Header section with logos
            if (!empty($logos)) {
                $sheet->getRowDimension($row)->setRowHeight($logoRowHeight);
                
                // Left logo (Pederasyon)
                if (isset($logos['pederasyon']) && isset($logos['pederasyon']['file_path'])) {
                    $logoPath = FCPATH . $logos['pederasyon']['file_path'];
                    if (file_exists($logoPath)) {
                        $drawing1 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                        $drawing1->setName('Pederasyon Logo');
                        $drawing1->setDescription('Pederasyon Logo');
                        $drawing1->setPath($logoPath);
                        $drawing1->setHeight(80);
                        $drawing1->setCoordinates('A' . $row);
                        $drawing1->setWorksheet($sheet);
                    }
                }
                
                // Right logo (Iriga City)
                if (isset($logos['iriga_city']) && isset($logos['iriga_city']['file_path'])) {
                    $logoPath = FCPATH . $logos['iriga_city']['file_path'];
                    if (file_exists($logoPath)) {
                        $drawing2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                        $drawing2->setName('Iriga City Logo');
                        $drawing2->setDescription('Iriga City Logo');
                        $drawing2->setPath($logoPath);
                        $drawing2->setHeight(80);
                        $drawing2->setCoordinates('I' . $row);
                        $drawing2->setWorksheet($sheet);
                    }
                }
            }
            
            // Header text (centered between logos)
            $row += 2;
            $sheet->setCellValue('D' . $row, 'REPUBLIC OF THE PHILIPPINES');
            $sheet->getStyle('D' . $row)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            $row++;
            $sheet->setCellValue('D' . $row, 'PROVINCE OF CAMARINES SUR');
            $sheet->getStyle('D' . $row)->getFont()->setBold(true)->setSize(11);
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            $row++;
            $sheet->setCellValue('D' . $row, 'CITY OF IRIGA');
            $sheet->getStyle('D' . $row)->getFont()->setBold(true)->setSize(11);
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            $row++;
            $sheet->setCellValue('D' . $row, 'SANGGUNIANG KABATAAN PEDERASYON');
            $sheet->getStyle('D' . $row)->getFont()->setBold(false)->setSize(10);
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            if ($barangayName) {
                $row++;
                $sheet->setCellValue('D' . $row, 'NG BARANGAY ' . strtoupper($barangayName));
                $sheet->getStyle('D' . $row)->getFont()->setBold(false)->setSize(10);
                $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            
            // Title
            $row += 2;
            $sheet->setCellValue('D' . $row, 'ATTENDANCE REPORT');
            $sheet->getStyle('D' . $row)->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            // Event details
            $row += 2;
            $sheet->setCellValue('A' . $row, 'Event: ' . $event['title']);
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            
            $row++;
            $sheet->setCellValue('A' . $row, 'Date: ' . date('F j, Y', strtotime($event['start_datetime'])));
            
            $row++;
            $sheet->setCellValue('A' . $row, 'Time: ' . date('g:i A', strtotime($event['start_datetime'])) . ' - ' . date('g:i A', strtotime($event['end_datetime'])));
            
            if (!empty($event['location'])) {
                $row++;
                $sheet->setCellValue('A' . $row, 'Location: ' . $event['location']);
            }
            
            // Table headers
            $row += 2;
            $headers = ['No.', 'KK Number', 'Name', 'Zone', 'AM Time-In', 'AM Time-Out', 'AM Status', 'PM Time-In', 'PM Time-Out', 'PM Status'];
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . $row, $header);
                $sheet->getStyle($col . $row)->getFont()->setBold(true);
                $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle($col . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('E5E7EB');
                $col++;
            }
            
            // Data rows
            $dataStartRow = $row + 1;
            foreach ($attendanceRecords as $index => $record) {
                $row++;
                
                // Format name as Lastname, Firstname Middlename
                $userName = $record['user_name'] ?? 'N/A';
                if ($userName !== 'N/A') {
                    $nameParts = explode(' ', trim($userName));
                    if (count($nameParts) >= 2) {
                        $firstName = $nameParts[0];
                        $lastName = end($nameParts);
                        $middleName = count($nameParts) > 2 ? implode(' ', array_slice($nameParts, 1, -1)) : '';
                        $userName = $lastName . ', ' . $firstName . ($middleName ? ' ' . $middleName : '');
                    }
                }
                
                // Calculate statuses
                $amStatus = 'Absent';
                if (!empty($record['time-in_am'])) {
                    if (!empty($record['status_am']) && strtolower($record['status_am']) === 'late') {
                        $amStatus = 'Late';
                    } elseif (!empty($record['time-out_am'])) {
                        $amStatus = 'Complete';
                    } else {
                        $amStatus = 'Present';
                    }
                }
                
                $pmStatus = 'Absent';
                if (!empty($record['time-in_pm'])) {
                    if (!empty($record['status_pm']) && strtolower($record['status_pm']) === 'late') {
                        $pmStatus = 'Late';
                    } elseif (!empty($record['time-out_pm'])) {
                        $pmStatus = 'Complete';
                    } else {
                        $pmStatus = 'Present';
                    }
                }
                
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $record['permanent_user_id'] ?? 'N/A');
                $sheet->setCellValue('C' . $row, $userName);
                $sheet->setCellValue('D' . $row, $record['zone_purok'] ?? 'N/A');
                $sheet->setCellValue('E' . $row, !empty($record['time-in_am']) ? date('h:i A', strtotime($record['time-in_am'])) : '-');
                $sheet->setCellValue('F' . $row, !empty($record['time-out_am']) ? date('h:i A', strtotime($record['time-out_am'])) : '-');
                $sheet->setCellValue('G' . $row, $amStatus);
                $sheet->setCellValue('H' . $row, !empty($record['time-in_pm']) ? date('h:i A', strtotime($record['time-in_pm'])) : '-');
                $sheet->setCellValue('I' . $row, !empty($record['time-out_pm']) ? date('h:i A', strtotime($record['time-out_pm'])) : '-');
                $sheet->setCellValue('J' . $row, $pmStatus);
                
                // Center align all data
                $sheet->getStyle('A' . $row . ':J' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            
            // Apply borders to the table
            $tableRange = 'A' . ($dataStartRow - 1) . ':J' . $row;
            $sheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            
            // Set column widths
            $sheet->getColumnDimension('A')->setWidth(8);  // No.
            $sheet->getColumnDimension('B')->setWidth(15); // KK Number
            $sheet->getColumnDimension('C')->setWidth(30); // Name
            $sheet->getColumnDimension('D')->setWidth(12); // Zone
            $sheet->getColumnDimension('E')->setWidth(15); // AM Time-In
            $sheet->getColumnDimension('F')->setWidth(15); // AM Time-Out
            $sheet->getColumnDimension('G')->setWidth(12); // AM Status
            $sheet->getColumnDimension('H')->setWidth(15); // PM Time-In
            $sheet->getColumnDimension('I')->setWidth(15); // PM Time-Out
            $sheet->getColumnDimension('J')->setWidth(12); // PM Status
            
            // Save the document
            $outputDir = FCPATH . 'uploads/generated/';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $fileName = 'Pederasyon_Attendance_Report_' . date('Y-m-d_H-i-s', strtotime($event['start_datetime'])) . '.xlsx';
            $outputPath = $outputDir . $fileName;
            
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($outputPath);
            
            log_message('info', 'Pederasyon Attendance Excel document saved to: ' . $outputPath);
            return $outputPath;
            
        } catch (\Exception $e) {
            log_message('error', 'Error in generateAttendanceExcelDocument: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Generate Word document for attendance report
     */
    private function generateAttendanceWordDocument($event, $attendanceRecords, $logos = [], $barangayName = null)
    {
        try {
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            
            // Set document properties
            $properties = $phpWord->getDocInfo();
            $properties->setCreator('K-NECT System - Pederasyon');
            $properties->setLastModifiedBy('K-NECT System');
            $properties->setTitle('Pederasyon Attendance Report - ' . $event['title']);
            $properties->setSubject('Event Attendance Report');
            $properties->setDescription('Generated attendance report for event: ' . $event['title']);
            
            // Create section
            $section = $phpWord->addSection([
                'marginTop' => 720,    // 0.5 inch
                'marginBottom' => 720, // 0.5 inch
                'marginLeft' => 720,   // 0.5 inch
                'marginRight' => 720,  // 0.5 inch
                'orientation' => 'landscape'
            ]);
            
            // Header with logos
            $headerTable = $section->addTable([
                'borderSize' => 0,
                'cellMargin' => 80,
                'width' => 100 * 50, // 100% width
                'unit' => \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT
            ]);
            
            $headerRow = $headerTable->addRow();
            
            // Left logo cell (Pederasyon)
            $leftCell = $headerRow->addCell(2000, ['valign' => 'center']);
            if (isset($logos['pederasyon']) && isset($logos['pederasyon']['file_path'])) {
                $logoPath = FCPATH . $logos['pederasyon']['file_path'];
                if (file_exists($logoPath)) {
                    $leftCell->addImage($logoPath, [
                        'width' => 80,
                        'height' => 80,
                        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
                    ]);
                }
            }
            
            // Center text cell
            $centerCell = $headerRow->addCell(6000, ['valign' => 'center']);
            $centerCell->addText('REPUBLIC OF THE PHILIPPINES', [
                'name' => 'Arial',
                'size' => 12,
                'bold' => true
            ], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            
            $centerCell->addText('PROVINCE OF CAMARINES SUR', [
                'name' => 'Arial',
                'size' => 11,
                'bold' => true
            ], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            
            $centerCell->addText('CITY OF IRIGA', [
                'name' => 'Arial',
                'size' => 11,
                'bold' => true
            ], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            
            $centerCell->addText('SANGGUNIANG KABATAAN PEDERASYON', [
                'name' => 'Arial',
                'size' => 10,
                'bold' => false
            ], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            
            if ($barangayName) {
                $centerCell->addText('NG BARANGAY ' . strtoupper($barangayName), [
                    'name' => 'Arial',
                    'size' => 10,
                    'bold' => false
                ], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            }
            
            // Right logo cell (Iriga City)
            $rightCell = $headerRow->addCell(2000, ['valign' => 'center']);
            if (isset($logos['iriga_city']) && isset($logos['iriga_city']['file_path'])) {
                $logoPath = FCPATH . $logos['iriga_city']['file_path'];
                if (file_exists($logoPath)) {
                    $rightCell->addImage($logoPath, [
                        'width' => 80,
                        'height' => 80,
                        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
                    ]);
                }
            }
            
            // Title
            $section->addTextBreak(2);
            $section->addText('ATTENDANCE REPORT', [
                'name' => 'Arial',
                'size' => 14,
                'bold' => true
            ], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 200]);
            
            // Event details
            $section->addText('Event: ' . $event['title'], [
                'name' => 'Arial',
                'size' => 10,
                'bold' => true
            ], ['spaceAfter' => 100]);
            
            $section->addText('Date: ' . date('F j, Y', strtotime($event['start_datetime'])), [
                'name' => 'Arial',
                'size' => 10
            ], ['spaceAfter' => 100]);
            
            $section->addText('Time: ' . date('g:i A', strtotime($event['start_datetime'])) . ' - ' . date('g:i A', strtotime($event['end_datetime'])), [
                'name' => 'Arial',
                'size' => 10
            ], ['spaceAfter' => 100]);
            
            if (!empty($event['location'])) {
                $section->addText('Location: ' . $event['location'], [
                    'name' => 'Arial',
                    'size' => 10
                ], ['spaceAfter' => 100]);
            }
            
            // Attendance table
            $section->addTextBreak(1);
            
            $tableStyle = [
                'borderSize' => 6,
                'borderColor' => '000000',
                'cellMargin' => 50,
                'width' => 100 * 50,
                'unit' => \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT
            ];
            
            $table = $section->addTable($tableStyle);
            
            // Header row
            $table->addRow(400);
            $headerRow = $table->getRows()[0];
            
            $headers = ['No.', 'KK Number', 'Name', 'Zone', 'AM Time-In', 'AM Time-Out', 'AM Status', 'PM Time-In', 'PM Time-Out', 'PM Status'];
            foreach ($headers as $header) {
                $cell = $headerRow->addCell(1000, [
                    'bgColor' => 'F3F4F6',
                    'valign' => 'center'
                ]);
                $cell->addText($header, [
                    'name' => 'Arial',
                    'size' => 9,
                    'bold' => true
                ], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            }
            
            // Data rows
            foreach ($attendanceRecords as $index => $record) {
                $table->addRow(300);
                $dataRow = $table->getRows()[count($table->getRows()) - 1];
                
                // Format name
                $userName = $record['user_name'] ?? 'N/A';
                if ($userName !== 'N/A') {
                    $nameParts = explode(' ', trim($userName));
                    if (count($nameParts) >= 2) {
                        $firstName = $nameParts[0];
                        $lastName = end($nameParts);
                        $middleName = count($nameParts) > 2 ? implode(' ', array_slice($nameParts, 1, -1)) : '';
                        $userName = $lastName . ', ' . $firstName . ($middleName ? ' ' . $middleName : '');
                    }
                }
                
                // Calculate statuses
                $amStatus = 'Absent';
                if (!empty($record['time-in_am'])) {
                    if (!empty($record['status_am']) && strtolower($record['status_am']) === 'late') {
                        $amStatus = 'Late';
                    } elseif (!empty($record['time-out_am'])) {
                        $amStatus = 'Complete';
                    } else {
                        $amStatus = 'Present';
                    }
                }
                
                $pmStatus = 'Absent';
                if (!empty($record['time-in_pm'])) {
                    if (!empty($record['status_pm']) && strtolower($record['status_pm']) === 'late') {
                        $pmStatus = 'Late';
                    } elseif (!empty($record['time-out_pm'])) {
                        $pmStatus = 'Complete';
                    } else {
                        $pmStatus = 'Present';
                    }
                }
                
                $cellData = [
                    $index + 1,
                    $record['permanent_user_id'] ?? 'N/A',
                    $userName,
                    $record['zone_purok'] ?? 'N/A',
                    !empty($record['time-in_am']) ? date('h:i A', strtotime($record['time-in_am'])) : '-',
                    !empty($record['time-out_am']) ? date('h:i A', strtotime($record['time-out_am'])) : '-',
                    $amStatus,
                    !empty($record['time-in_pm']) ? date('h:i A', strtotime($record['time-in_pm'])) : '-',
                    !empty($record['time-out_pm']) ? date('h:i A', strtotime($record['time-out_pm'])) : '-',
                    $pmStatus
                ];
                
                foreach ($cellData as $cellValue) {
                    $cell = $dataRow->addCell(1000, ['valign' => 'center']);
                    $cell->addText($cellValue, [
                        'name' => 'Arial',
                        'size' => 8
                    ], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                }
            }
            
            // Save the document
            $outputDir = FCPATH . 'uploads/generated/';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $fileName = 'Pederasyon_Attendance_Report_' . date('Y-m-d_H-i-s', strtotime($event['start_datetime'])) . '.docx';
            $outputPath = $outputDir . $fileName;
            
            $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save($outputPath);
            
            log_message('info', 'Pederasyon Attendance Word document saved to: ' . $outputPath);
            return $outputPath;
            
        } catch (\Exception $e) {
            log_message('error', 'Error in generateAttendanceWordDocument: ' . $e->getMessage());
            throw $e;
        }
    }

}