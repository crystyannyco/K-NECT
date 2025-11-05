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
use App\Libraries\UserHelper;
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
        $userId = $session->get('user_id');
        $username = $session->get('username');

        // Database connection
        $db = \Config\Database::connect();

        // Document statistics (per current logged-in PED officer)
        $totalDocuments = $db->query(
            "SELECT COUNT(*) AS count FROM documents WHERE LOWER(TRIM(uploaded_by)) = LOWER(TRIM(?))",
            [$username]
        )->getRowArray()['count'] ?? 0;

        $pederasyonDocuments = $db->query(
            "SELECT COUNT(*) AS count FROM documents WHERE LOWER(TRIM(uploaded_by)) = LOWER(TRIM(?)) AND visibility = 'pederasyon'",
            [$username]
        )->getRowArray()['count'] ?? 0;

        $skDocuments = $db->query(
            "SELECT COUNT(*) AS count FROM documents WHERE LOWER(TRIM(uploaded_by)) = LOWER(TRIM(?)) AND visibility = 'sk'",
            [$username]
        )->getRowArray()['count'] ?? 0;

        // Bulletin data (city-wide visibility scope for federation role)
        $bulletinModel = new \App\Models\BulletinModel();
        $featuredPosts = $bulletinModel->getFeaturedPosts(5, 'pederasyon');
        $urgentPosts = $bulletinModel->getUrgentPosts(3, 'pederasyon');
        $recentPosts = $bulletinModel->getVisiblePosts('pederasyon', null, 6) ?? [];

        // Upcoming events (next 6 city-wide)
        $eventModel = new \App\Models\EventModel();
        $upcomingEvents = $eventModel
            ->where('status', 'Published')
            ->where('start_datetime >=', date('Y-m-d H:i:s'))
            ->orderBy('start_datetime', 'ASC')
            ->limit(6)
            ->find();

        // Recent documents uploaded by this user (limit 8)
        $recentDocuments = $db->query(
            "SELECT id, filename, filepath AS file_path, uploaded_at AS created_at, visibility 
             FROM documents WHERE LOWER(TRIM(uploaded_by)) = LOWER(TRIM(?)) ORDER BY uploaded_at DESC LIMIT 8",
            [$username]
        )->getResultArray();

        $data = [
            'user_id' => $userId,
            'username' => $username,
            // Document stats
            'totalDocuments' => $totalDocuments,
            'pederasyonDocuments' => $pederasyonDocuments,
            'skDocuments' => $skDocuments,
            'totalDocuments' => $totalDocuments,
            'pederasyonDocuments' => $pederasyonDocuments,
            'skDocuments' => $skDocuments,
            // Bulletin/events/documents overview
            'featuredPosts' => $featuredPosts,
            'urgentPosts' => $urgentPosts,
            'recentPosts' => $recentPosts,
            'upcomingEvents' => $upcomingEvents,
            'recentDocuments' => $recentDocuments,
        ];

        return 
            $this->loadView('K-NECT/Pederasyon/template/header', $data) .
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
        // Pass null as statusFilter to show ALL users regardless of approval status (including unverified)
        $users = $profileController->getAllUsersWithExtendedInfo(null, null);
        $users = $profileController->processUsersForMemberListing($users, 'pederasyon');
        
        // Filter to show KK Members (user_type = 1), SK Chairpersons (user_type = 2), and Pederasyon Officers (user_type = 3)
        $filteredUsers = array_filter($users, function($user) {
            $userType = isset($user['user_type']) ? (int)$user['user_type'] : 0;
            return in_array($userType, [1, 2, 3]); // KK Members, SK Chairpersons, and Pederasyon Officers
        });
        
        $data['user_list'] = array_values($filteredUsers); // Re-index array
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
        
        // Filter SK Chairpersons (user_type = 2) and Pederasyon Officers (user_type = 3)
        $pedOfficers = array_filter($users, function($user) {
            $userType = isset($user['user_type']) ? (int)$user['user_type'] : 0;
            return in_array($userType, [2, 3]); // SK Chairperson or Pederasyon Officer
        });

        // Prepare barangay map and computed barangay names for the view (move helper usage to backend)
        $barangayMap = BarangayHelper::getBarangayMap();
        $pedOfficers = array_map(function ($user) use ($barangayMap) {
            $barangayId = $user['barangay'] ?? null;
            $user['barangay_name'] = $barangayId !== null && isset($barangayMap[$barangayId])
                ? $barangayMap[$barangayId]
                : ($barangayId ?? '');

            $pedPosition = isset($user['ped_position']) ? (int)$user['ped_position'] : null;
            $user['position_display'] = $this->getPedPositionLabel($pedPosition);
            if (isset($user['user_type']) && (int)$user['user_type'] === 2 && $pedPosition === null) {
                $user['position_display'] = 'Member';
            }

            return $user;
        }, $pedOfficers);

        $data['ped_officers'] = $pedOfficers;
        // Provide centralized maps for JS in view
        $data['field_mappings'] = DemographicsHelper::allMapsForJs();
        // Explicitly provide barangay_map to avoid helper calls in the view
        $data['barangay_map'] = $barangayMap;
        
        // Check if there are officers with credentials and pass to view
        $officersCheck = $this->checkPederasyonOfficersWithCredentials();
        $data['has_officers_with_credentials'] = $officersCheck['hasOfficers'];
        $data['officers_with_credentials'] = $officersCheck['officers'];
        
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

        // Get barangay name from ID
        $barangayName = '';
        if (!empty($profileData['address']['barangay'])) {
            $barangayName = BarangayHelper::getBarangayName($profileData['address']['barangay']);
        }

        $data = array_merge($profileData, [
            'username' => $session->get('username'),
            'barangay_name' => $barangayName,
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

                $targetDir = ROOTPATH . 'uploads/profile_pictures/';
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

        if (empty($userId) || ($pedPosition !== null && $pedPosition !== 'NULL' && !is_numeric($pedPosition))) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid data']);
        }

        // Convert NULL string to actual null
        if ($pedPosition === 'NULL') {
            $pedPosition = null;
        } else {
            $pedPosition = (int)$pedPosition;
        }

        $userModel = new UserModel();
        
        // Get current user data
        $user = $userModel->find($userId);
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
        }
        
        // Check position limits - ensure only 1 person per position (except null - Member)
        if ($pedPosition !== null) {
            $existingUserWithPosition = $userModel->where('ped_position', $pedPosition)
                                                   ->where('id !=', $userId)
                                                   ->first();
            
            if ($existingUserWithPosition) {
                $positionNames = $this->getPedPositionMap();
                $positionName = $positionNames[$pedPosition] ?? 'Unknown Position';
                $currentHolderName = $existingUserWithPosition['first_name'] . ' ' . $existingUserWithPosition['last_name'];
                
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => "Position '{$positionName}' is already occupied by {$currentHolderName}. Only one person can hold this position at a time."
                ]);
            }
        }
        
        // Prepare update data
        $updateData = ['ped_position' => $pedPosition];
        
        // If assigning a position (not null), upgrade to Pederasyon Officer and generate credentials
        if ($pedPosition !== null) {
            // Change user_type to 3 (Pederasyon Officer)
            $updateData['user_type'] = 3;
            
            // Generate Pederasyon credentials if they don't exist
            if (empty($user['ped_username']) || empty($user['ped_password'])) {
                // Generate unique username: PED_FirstNameLastName (no spaces, no underscores between names)
                $firstName = preg_replace('/[^a-zA-Z0-9]/', '', $user['first_name'] ?? '');
                $lastName = preg_replace('/[^a-zA-Z0-9]/', '', $user['last_name'] ?? '');
                $baseUsername = 'PED_' . ucfirst(strtolower($firstName)) . ucfirst(strtolower($lastName));
                
                // Ensure uniqueness
                $username = $baseUsername;
                $counter = 1;
                while ($userModel->where('ped_username', $username)->first()) {
                    $username = $baseUsername . $counter;
                    $counter++;
                }
                
                // Generate temporary password (8 characters: mix of uppercase, lowercase, and numbers)
                $password = $this->generateTemporaryPassword();
                
                $updateData['ped_username'] = $username;
                // Store as plain text temporarily so it shows in credentials list
                $updateData['ped_password'] = $password;
                
                // Return credentials in response
                $newCredentials = [
                    'username' => $username,
                    'password' => $password,
                    'user_id' => $user['user_id']
                ];
            }
        } else {
            // If setting to null (regular member), clear Pederasyon credentials
            // This prevents unauthorized login
            $updateData['ped_username'] = null;
            $updateData['ped_password'] = null;
            // Keep user_type = 3 for SK Chairpersons who are Pederasyon members
        }
        
        // Update the user
        $updated = $userModel->update($userId, $updateData);
        
        if ($updated) {
            $response = ['success' => true, 'message' => 'Officer position updated successfully'];
            
            // If new credentials were generated, include them in the response
            if (isset($newCredentials)) {
                $response['newCredentials'] = $newCredentials;
                $response['showCredentialsModal'] = true;
            }
            
            // Check if there are any Pederasyon officers with credentials remaining
            $officersCheck = $this->checkPederasyonOfficersWithCredentials();
            if (!$officersCheck['hasOfficers']) {
                $response['warning'] = true;
                $response['warningMessage'] = 'No Pederasyon officers with login credentials remain. At least one officer is required to manage the system.';
                $response['showOfficerWarning'] = true;
                $response['remainingOfficers'] = [];
            } else {
                $response['remainingOfficers'] = $officersCheck['officers'];
            }
            
            return $this->response->setJSON($response);
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

        if (empty($officerIds) || !is_array($officerIds) || ($pedPosition !== null && $pedPosition !== 'NULL' && !is_numeric($pedPosition))) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid data']);
        }

        // Convert NULL string to actual null
        if ($pedPosition === 'NULL') {
            $pedPosition = null;
        } else {
            $pedPosition = (int)$pedPosition;
        }

        $userModel = new UserModel();
        
        // Check position limits - ensure only 1 person per position (except null - Member)
        if ($pedPosition !== null) {
            $existingUserWithPosition = $userModel->where('ped_position', $pedPosition)
                                                   ->whereNotIn('id', $officerIds)
                                                   ->first();
            
            if ($existingUserWithPosition) {
                $positionNames = $this->getPedPositionMap();
                $positionName = $positionNames[$pedPosition] ?? 'Unknown Position';
                $currentHolderName = $existingUserWithPosition['first_name'] . ' ' . $existingUserWithPosition['last_name'];
                
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => "Position '{$positionName}' is already occupied by {$currentHolderName}. Only one person can hold this position at a time. Please remove them from the position first or select multiple users including the current holder."
                ]);
            }
            
            // Also check if more than 1 user is selected for positions 1-7
            if (count($officerIds) > 1) {
                $positionNames = $this->getPedPositionMap();
                $positionName = $positionNames[$pedPosition] ?? 'Unknown Position';
                
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => "Cannot assign multiple users to '{$positionName}' position. Only one person can hold this position at a time."
                ]);
            }
        }

        $updated = 0;
        $newCredentials = [];
        
        foreach ($officerIds as $officerId) {
            if (is_numeric($officerId)) {
                $user = $userModel->find((int)$officerId);
                if (!$user) continue;
                
                // Prepare update data
                $updateData = ['ped_position' => $pedPosition];
                
                // If assigning a position (not null), upgrade to Pederasyon Officer and generate credentials
                if ($pedPosition !== null) {
                    // Change user_type to 3 (Pederasyon Officer)
                    $updateData['user_type'] = 3;
                    
                    // Generate Pederasyon credentials if they don't exist
                    if (empty($user['ped_username']) || empty($user['ped_password'])) {
                        // Generate unique username: PED_FirstNameLastName
                        $firstName = preg_replace('/[^a-zA-Z0-9]/', '', $user['first_name'] ?? '');
                        $lastName = preg_replace('/[^a-zA-Z0-9]/', '', $user['last_name'] ?? '');
                        $baseUsername = 'PED_' . ucfirst(strtolower($firstName)) . ucfirst(strtolower($lastName));
                        
                        // Ensure uniqueness
                        $username = $baseUsername;
                        $counter = 1;
                        while ($userModel->where('ped_username', $username)->first()) {
                            $username = $baseUsername . $counter;
                            $counter++;
                        }
                        
                        // Generate temporary password
                        $password = $this->generateTemporaryPassword();
                        
                        $updateData['ped_username'] = $username;
                        // Store as plain text temporarily
                        $updateData['ped_password'] = $password;
                        
                        // Store credentials for response
                        $newCredentials[] = [
                            'user_id' => $user['user_id'],
                            'name' => $user['first_name'] . ' ' . $user['last_name'],
                            'username' => $username,
                            'password' => $password
                        ];
                    }
                } else {
                    // If setting to null, clear credentials
                    $updateData['ped_username'] = null;
                    $updateData['ped_password'] = null;
                }
                
                $result = $userModel->update((int)$officerId, $updateData);
                if ($result) {
                    $updated++;
                }
            }
        }
        
        if ($updated > 0) {
            $response = ['success' => true, 'message' => "Updated {$updated} officer position(s) successfully"];
            
            // If new credentials were generated, include them in the response
            if (!empty($newCredentials)) {
                $response['newCredentials'] = $newCredentials;
                $response['showCredentialsModal'] = true;
            }
            
            // Check if there are any Pederasyon officers with credentials remaining
            $officersCheck = $this->checkPederasyonOfficersWithCredentials();
            if (!$officersCheck['hasOfficers']) {
                $response['warning'] = true;
                $response['warningMessage'] = 'No Pederasyon officers with login credentials remain. At least one officer is required to manage the system.';
                $response['showOfficerWarning'] = true;
                $response['remainingOfficers'] = [];
            } else {
                $response['remainingOfficers'] = $officersCheck['officers'];
            }
            
            return $this->response->setJSON($response);
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

            $positionMap = $this->getPedPositionMap();

            // Limit to accepted Pederasyon officers with defined positions (1-7)
            $officials = array_values(array_filter($users, function($user) use ($positionMap) {
                $userType = isset($user['user_type']) ? (int)$user['user_type'] : 0;
                $status = isset($user['status']) ? (int)$user['status'] : 0;
                $pedPosition = isset($user['ped_position']) ? (int)$user['ped_position'] : 0;

                return $userType === 3 && $status === 2 && isset($positionMap[$pedPosition]);
            }));

            if (empty($officials)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No officials found for the official list']);
            }

            // Get logos for the Word document
            $logos = $this->getLogosForDocument();

            // Generate Word document and stream directly to user
            $fileName = 'Pederasyon_Officials_List_' . date('Y-m-d_His') . '.docx';
            $phpWord = $this->generateOfficialListWordDocument($officials, $logos);
            
            // Stream the file directly to the user
            $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            
            // Set headers for file download
            $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');
            $this->response->setHeader('Cache-Control', 'max-age=0');
            
            // Write to output buffer
            ob_start();
            $writer->save('php://output');
            $wordOutput = ob_get_clean();
            
            log_message('info', 'Word document streamed successfully: ' . $fileName);
            return $this->response->setBody($wordOutput);
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

            $positionMap = $this->getPedPositionMap();
        
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
            
            // Use a repeating section header so it appears on every page
            $header = $section->addHeader();

            $headerTable = $header->addTable([
                'borderSize' => 0,
                'borderColor' => 'FFFFFF',
                'width' => 100 * 50,
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
            ]);
            $headerTable->addRow();

            // Left logo cell (Pederasyon)
            $leftCell = $headerTable->addCell(2000, ['valign' => 'center']);
            if (isset($logos['pederasyon'])) {
                $logoPath = ROOTPATH . $logos['pederasyon']['file_path'];
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
                $logoPath = ROOTPATH . $logos['iriga_city']['file_path'];
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

            $header->addTextBreak();
            $header->addText('PANLUNGSOD NA PEDERASYON NG MGA SANGGUNIANG KABATAAN', $titleStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $header->addText('OFFICIAL LIST', $titleStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);

            // Provide spacing between the header and table content
            $section->addTextBreak(2);
            
            // Create data table
            $table = $section->addTable([
                'borderSize' => 4,
                'borderColor' => '000000',
                'cellMargin' => 20,
                'width' => 100 * 50,
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
            ]);
            
            // Add table header
            $table->addRow(null, ['tblHeader' => true]);
            $table->addCell(1200)->addText('User ID', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell(2500)->addText('Full Name', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell(1800)->addText('Barangay', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell(800)->addText('Gender', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell(800)->addText('Age', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell(1200)->addText('Birthdate', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell(1700)->addText('Position', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            
            // Process officials and add to table
            foreach ($officials as $official) {
                $pedPosition = isset($official['ped_position']) ? (int)$official['ped_position'] : 0;
                if (!isset($positionMap[$pedPosition])) {
                    continue;
                }

                $userId = $official['user_id'] ?? '';
                $barangay = BarangayHelper::getBarangayName($official['barangay'] ?? '');

                $fullName = esc($official['last_name'] ?? '');
                if (!empty($official['first_name'])) {
                    $fullName .= ', ' . esc($official['first_name']);
                }
                if (!empty($official['middle_name'])) {
                    $fullName .= ' ' . esc($official['middle_name']);
                }

                $age = $official['age'] ?? '';
                $birthday = !empty($official['birthdate']) ? date('m/d/Y', strtotime($official['birthdate'])) : '';
                $sexValue = $official['sex'] ?? null;
                $sex = $sexValue == '1' ? 'Male' : ($sexValue == '2' ? 'Female' : '');

                $position = $positionMap[$pedPosition];

                $table->addRow();
                $table->addCell()->addText($userId, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $table->addCell()->addText($fullName, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $table->addCell()->addText($barangay, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $table->addCell()->addText($sex, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $table->addCell()->addText($age, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $table->addCell()->addText($birthday, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $table->addCell()->addText($position, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
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
            // $outputDir = FCPATH . 'uploads/generated/';
            // if (!is_dir($outputDir)) {
            //     mkdir($outputDir, 0755, true);
            // }
            
            $fileName = 'PEDERASYON_Official_List_' . date('Y-m-d') . '.docx';
            $outputPath = $fileName;
            
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
            $systemLogoModel = new SystemLogoModel();
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
                $logoFiles = glob(ROOTPATH . 'uploads/logos/pederasyon_logo_*');
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
                $logoFiles = glob(ROOTPATH . 'uploads/logos/iriga_city_logo_*');
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
            $logoFiles = glob(ROOTPATH . 'uploads/logos/pederasyon_logo_*');
            if (!empty($logoFiles)) {
                $latestFile = end($logoFiles);
                $logos['pederasyon'] = ['file_path' => str_replace(FCPATH, '', $latestFile)];
            }
            
            $logoFiles = glob(ROOTPATH . 'uploads/logos/iriga_city_logo_*');
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

            $positionMap = $this->getPedPositionMap();

            // Limit to accepted Pederasyon officers with defined positions (1-7)
            $officials = array_values(array_filter($users, function($user) use ($positionMap) {
                $userType = isset($user['user_type']) ? (int)$user['user_type'] : 0;
                $status = isset($user['status']) ? (int)$user['status'] : 0;
                $pedPosition = isset($user['ped_position']) ? (int)$user['ped_position'] : 0;

                return $userType === 3 && $status === 2 && isset($positionMap[$pedPosition]);
            }));

            if (empty($officials)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No officials found for the official list']);
            }

            // Generate Excel document and stream directly to user
            $fileName = 'Pederasyon_Officials_List_' . date('Y-m-d_His') . '.xlsx';
            $spreadsheet = $this->generateOfficialListExcelDocument($officials);
            
            // Clear any previous output
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            // Set headers for file download
            $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');
            $this->response->setHeader('Cache-Control', 'max-age=0');
            $this->response->setHeader('Pragma', 'public');
            
            // Write to output buffer
            ob_start();
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            $excelOutput = ob_get_clean();
            
            log_message('info', 'Excel document streamed successfully: ' . $fileName);
            return $this->response->setBody($excelOutput);
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

            $positionMap = $this->getPedPositionMap();

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
            $sheet->setCellValue('A' . $currentRow, 'PANLUNGSOD NA PEDERASYON NG MGA SANGGUNIANG KABATAAN');
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
                'B' => 'Full Name',
                'C' => 'Barangay',
                'D' => 'Gender',
                'E' => 'Age',
                'F' => 'Birthdate',
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
                $pedPosition = isset($official['ped_position']) ? (int)$official['ped_position'] : 0;
                if (!isset($positionMap[$pedPosition])) {
                    continue;
                }

                $userId = $official['user_id'] ?? '';
                $barangay = BarangayHelper::getBarangayName($official['barangay'] ?? '');

                $fullName = esc($official['last_name'] ?? '');
                if (!empty($official['first_name'])) {
                    $fullName .= ', ' . esc($official['first_name']);
                }
                if (!empty($official['middle_name'])) {
                    $fullName .= ' ' . esc($official['middle_name']);
                }

                $age = $official['age'] ?? '';
                $birthday = !empty($official['birthdate']) ? date('m/d/Y', strtotime($official['birthdate'])) : '';
                $sexValue = $official['sex'] ?? null;
                $sex = $sexValue == '1' ? 'Male' : ($sexValue == '2' ? 'Female' : '');

                $position = $positionMap[$pedPosition];

                $sheet->setCellValue('A' . $currentRow, $userId);
                $sheet->setCellValue('B' . $currentRow, $fullName);
                $sheet->setCellValue('C' . $currentRow, $barangay);
                $sheet->setCellValue('D' . $currentRow, $sex);
                $sheet->setCellValue('E' . $currentRow, $age);
                $sheet->setCellValue('F' . $currentRow, $birthday);
                $sheet->setCellValue('G' . $currentRow, $position);

                foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G'] as $col) {
                    $sheet->getStyle($col . $currentRow)->getFont()->setSize(9);
                    $sheet->getStyle($col . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle($col . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $sheet->getStyle($col . $currentRow)->getAlignment()->setWrapText(true);
                    $sheet->getStyle($col . $currentRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                }

                $sheet->getRowDimension($currentRow)->setRowHeight(20);

                $currentRow++;
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
            $sheet->getColumnDimension('B')->setWidth(30); // Full Name
            $sheet->getColumnDimension('C')->setWidth(20); // Barangay
            $sheet->getColumnDimension('D')->setWidth(10); // Gender
            $sheet->getColumnDimension('E')->setWidth(8);  // Age
            $sheet->getColumnDimension('F')->setWidth(15); // Birthdate
            $sheet->getColumnDimension('G')->setWidth(20); // Position

            // Set row heights for headers
            $sheet->getRowDimension($headerRowNum)->setRowHeight(25);

            // Auto-fit page margins
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setBottom(0.5);
            $sheet->getPageMargins()->setLeft(0.5);
            $sheet->getPageMargins()->setRight(0.5);

            // // Save the document
            // $outputDir = FCPATH . 'uploads/generated/';
            // if (!is_dir($outputDir)) {
            //     mkdir($outputDir, 0755, true);
            // }
            
            $fileName = 'PEDERASYON_Official_List_' . date('Y-m-d') . '.xlsx';
            $outputPath = $fileName;
            
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
            
            // Filter only SK Chairpersons (user_type=2 AND position=1 with Accepted status)
            $officials = array_filter($users, function($user) {
                $userType = isset($user['user_type']) ? (int)$user['user_type'] : 1;
                $position = isset($user['position']) ? (int)$user['position'] : 0;
                $status = isset($user['status']) ? (int)$user['status'] : 1;
                return $userType === 2 && $position === 1 && $status === 2; // Only SK Chairpersons, Accepted
            });

            if (empty($officials)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No SK Chairpersons found for credentials generation']);
            }

            // Generate credentials document and stream directly to user
            $fileName = 'PEDERASYON_Officials_Credentials_' . date('Y-m-d_His') . '.xlsx';
            $spreadsheet = $this->generateCredentialsDocument($officials);
            
            // Clear any previous output
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            // Set headers for file download
            $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');
            $this->response->setHeader('Cache-Control', 'max-age=0');
            $this->response->setHeader('Pragma', 'public');
            
            // Write to output buffer
            ob_start();
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            $excelOutput = ob_get_clean();
            
            log_message('info', 'Credentials Excel streamed successfully: ' . $fileName);
            return $this->response->setBody($excelOutput);
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
                    
                    $pedPosition = ($userType === 3)
                        ? (isset($official['ped_position']) ? (int)$official['ped_position'] : null)
                        : null;
                    $position = $this->getPedPositionLabel($pedPosition);
                    
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
            $sheet->getColumnDimension('B')->setWidth(30); // Full Name
            $sheet->getColumnDimension('C')->setWidth(20); // Position
            $sheet->getColumnDimension('D')->setWidth(20); // Barangay
            $sheet->getColumnDimension('E')->setWidth(30); // Email
            $sheet->getColumnDimension('F')->setWidth(18); // Phone
            $sheet->getColumnDimension('G')->setWidth(12); // Status
            $sheet->getColumnDimension('H')->setWidth(15); // Date Appointed

            // Set row heights for headers
            $sheet->getRowDimension($headerRowNum)->setRowHeight(25);

            // Auto-fit page margins
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setBottom(0.5);
            $sheet->getPageMargins()->setLeft(0.5);
            $sheet->getPageMargins()->setRight(0.5);

            // Save the document
            // $outputDir = FCPATH . 'uploads/generated/';
            // if (!is_dir($outputDir)) {
            //     mkdir($outputDir, 0755, true);
            // }
            
            $fileName = 'PEDERASYON_Officials_Credentials_' . date('Y-m-d') . '.xlsx';
            $outputPath = $fileName;
            
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
            log_message('info', 'Getting credentials data for Youth List (SK Chairpersons only) ...');

            $profileController = new ProfileController();
            $users = $profileController->getAllUsersWithExtendedInfo();
            $users = $profileController->processUsersForMemberListing($users, 'pederasyon');

            log_message('info', 'Total users fetched: ' . count($users));

            $skCredentials = [];
            $debugCount = 0;
            $statusFiltered = 0;
            $userTypeFiltered = 0;
            $credentialsFiltered = 0;

            foreach ($users as $u) {
                $debugCount++;
                $status = isset($u['status']) ? (int)$u['status'] : 0;
                $userType = isset($u['user_type']) ? (int)$u['user_type'] : 0;
                $position = isset($u['position']) ? (int)$u['position'] : 0;
                $skPosition = isset($u['sk_position']) ? (int)$u['sk_position'] : 0;
                $pedPosition = isset($u['ped_position']) ? (int)$u['ped_position'] : 0;
                $skUsername = $u['sk_username'] ?? '';
                $skPassword = $u['sk_password'] ?? '';
                
                // Debug logging for first 5 users, SK Chairpersons, or Pederasyon Officers
                if ($debugCount <= 5 || $userType === 2 || $userType === 3) {
                    log_message('info', sprintf(
                        'User #%d: user_id=%s, user_type=%d, sk_position=%d, ped_position=%d, status=%d, sk_username=%s, sk_password=%s',
                        $debugCount,
                        $u['user_id'] ?? 'NULL',
                        $userType,
                        $skPosition,
                        $pedPosition,
                        $status,
                        $skUsername ? 'SET' : 'EMPTY',
                        $skPassword ? 'SET' : 'EMPTY'
                    ));
                }
                
                if ($status !== 2) {
                    $statusFiltered++;
                    continue; // Only accepted users eligible for credential listing
                }

                $skPosition = isset($u['sk_position']) ? (int)$u['sk_position'] : 0;
                
                // Include users who are SK Chairpersons:
                // 1. user_type = 2 (SK Chairperson) AND sk_position = 1 (Chairperson position)
                // 2. user_type = 3 (Pederasyon Officer - they are also SK Chairpersons from their barangay)
                $isSkChairperson = ($userType === 2 && $skPosition === 1) || ($userType === 3);
                
                if ($isSkChairperson) {
                    $userId = $u['user_id'] ?? '';
                    $barangay = \App\Libraries\BarangayHelper::getBarangayName($u['barangay'] ?? '');
                    
                    // Consistent Full Name: Last, First Middle
                    $fullName = esc($u['last_name'] ?? '');
                    if (!empty($u['first_name'])) {
                        $fullName .= ', ' . esc($u['first_name']);
                    }
                    if (!empty($u['middle_name'])) {
                        $fullName .= ' ' . esc($u['middle_name']);
                    }

                    // Determine position label
                    $positionLabel = 'SK Chairperson';

                    // Include ALL SK Chairpersons, even without credentials
                    // Frontend will handle display of empty/hashed passwords
                    $skCredentials[] = [
                        'userId'   => $userId,
                        'name'     => $fullName,
                        'barangay' => $barangay,
                        'position' => $positionLabel,
                        'username' => $skUsername ?: 'Not Set',
                        'password' => $skPassword ?: 'Not Set',
                        'hasCredentials' => ($skUsername && $skPassword) ? true : false,
                    ];
                    
                    if ($skUsername && $skPassword) {
                        log_message('info', 'Added SK Chairperson with credentials: ' . $userId . ' - ' . $fullName . ' (' . $positionLabel . ')');
                    } else {
                        $credentialsFiltered++;
                        log_message('info', 'Added SK Chairperson without credentials: ' . $userId . ' - ' . $fullName . ' (' . $positionLabel . ')');
                    }
                } else {
                    if ($status === 2) {
                        $userTypeFiltered++;
                    }
                }
            }

            log_message('info', 'Filter summary: status_filtered=' . $statusFiltered . ', user_type_filtered=' . $userTypeFiltered . ', credentials_filtered=' . $credentialsFiltered);
            log_message('info', 'SK Chairperson credentials count: ' . count($skCredentials));
            
            // Additional debug info
            if (count($skCredentials) === 0) {
                log_message('warning', 'No SK Chairpersons found! Possible reasons:');
                log_message('warning', '  - No users with (user_type=2 AND sk_position=1) for SK Chairpersons');
                log_message('warning', '  - No users with user_type=3 for Pederasyon Officers (who are also SK Chairpersons)');
                log_message('warning', '  - No users with status=2 (Accepted)');
                log_message('warning', '  - Check the users table for correct user_type, sk_position, and status values');
                log_message('info', 'Total users processed: ' . $debugCount);
                log_message('info', 'Users filtered by status: ' . $statusFiltered);
                log_message('info', 'Users filtered by user_type: ' . $userTypeFiltered);
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'sk' => $skCredentials,
                ],
                'counts' => [
                    'sk' => count($skCredentials),
                    'total' => count($skCredentials)
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

    // New method for Pederasyon Officers credentials (to be called from ped-officers page)
    public function getPedOfficersCredentialsData()
    {
        try {
            log_message('info', 'Getting Pederasyon Officers credentials data ...');

            $profileController = new ProfileController();
            $users = $profileController->getAllUsersWithExtendedInfo();
            $users = $profileController->processUsersForMemberListing($users, 'pederasyon');

            $pedCredentials = [];

            $pedPositionMap = $this->getPedPositionMap();

            foreach ($users as $u) {
                $status = isset($u['status']) ? (int)$u['status'] : 0;
                if ($status !== 2) {
                    continue; // Only accepted users
                }

                $userType = isset($u['user_type']) ? (int)$u['user_type'] : 0;
                
                // Include SK Chairpersons (user_type = 2) and Pederasyon Officers (user_type = 3)
                if ($userType === 2 || $userType === 3) {
                    $userId = $u['user_id'] ?? '';
                    $barangay = \App\Libraries\BarangayHelper::getBarangayName($u['barangay'] ?? '');
                    $fullName = esc($u['last_name'] ?? '');
                    if (!empty($u['first_name'])) {
                        $fullName .= ', ' . esc($u['first_name']);
                    }
                    if (!empty($u['middle_name'])) {
                        $fullName .= ' ' . esc($u['middle_name']);
                    }

                    // For SK Chairpersons (user_type = 2), use their Pederasyon credentials if available
                    $pedUsername = $u['ped_username'] ?? '';
                    $pedPassword = $u['ped_password'] ?? '';
                    
                    // Determine position label and code
                    $positionLabel = '';
                    $pedPositionCode = null;
                    $pedPosCode = isset($u['ped_position']) ? (int)$u['ped_position'] : 0;
                    $pedPositionCode = ($userType === 3 && $pedPosCode > 0) ? $pedPosCode : null;
                    $positionLabel = $pedPositionCode !== null && isset($pedPositionMap[$pedPositionCode])
                        ? $pedPositionMap[$pedPositionCode]
                        : 'Member';
                    
                    if ($pedUsername && $pedPassword) {
                        $pedCredentials[] = [
                            'user_id'      => $userId,
                            'first_name'   => $u['first_name'] ?? '',
                            'middle_name'  => $u['middle_name'] ?? '',
                            'last_name'    => $u['last_name'] ?? '',
                            'barangay'     => $u['barangay'] ?? '',
                            'position'     => $positionLabel,
                            'ped_position' => $pedPositionCode,
                            'ped_username' => $pedUsername,
                            'ped_password' => $pedPassword,
                        ];
                    }
                }
            }

            log_message('info', 'Pederasyon Officers credentials count: ' . count($pedCredentials));

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'ped' => $pedCredentials,
                ],
                'counts' => [
                    'ped' => count($pedCredentials),
                    'total' => count($pedCredentials)
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in getPedOfficersCredentialsData: ' . $e->getMessage());
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
            
            // Filter for credentials: Include user_type=2 (SK Chairpersons) and user_type=3 (Pederasyon) with Accepted status
            $officials = array_filter($users, function($user) {
                $userType = isset($user['user_type']) ? (int)$user['user_type'] : 1;
                $status = isset($user['status']) ? (int)$user['status'] : 1;
                // Include both SK Chairpersons (type 2) and Pederasyon (type 3), both Accepted
                return ($userType === 2 || $userType === 3) && $status === 2;
            });

            if (empty($officials)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No officials found for credentials PDF generation']);
            }

            // Generate credentials PDF document and stream directly to user
            $fileName = 'PEDERASYON_Officials_Credentials_' . date('Y-m-d_His') . '.pdf';
            $pdfContent = $this->generateCredentialsPDFDocument($officials);
            
            // Set headers for file download
            $this->response->setHeader('Content-Type', 'application/pdf');
            $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');
            $this->response->setHeader('Cache-Control', 'max-age=0');
            
            log_message('info', 'Credentials PDF streamed successfully: ' . $fileName);
            return $this->response->setBody($pdfContent);
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
                    p {
                        margin: 0;
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
                        margin: 0;
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
                $logoPath = ROOTPATH . $logos['pederasyon']['file_path'];
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
                $logoPath = ROOTPATH . $logos['iriga_city']['file_path'];
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
                    
                    $pedPosition = isset($official['ped_position']) ? (int)$official['ped_position'] : null;
                    $position = $this->getPedPositionLabel($pedPosition);
                    
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
            require_once ROOTPATH . '../vendor/autoload.php';
            
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
            // $outputDir = FCPATH . 'uploads/generated/';
            // if (!is_dir($outputDir)) {
            //     mkdir($outputDir, 0755, true);
            // }
            
            $fileName = 'PEDERASYON_Officials_Credentials_' . date('Y-m-d') . '.pdf';
            $outputPath = $fileName;
            
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
            
            // Filter for credentials: Include user_type=2 (SK Chairpersons) and user_type=3 (Pederasyon) with Accepted status
            $officials = array_filter($users, function($user) {
                $userType = isset($user['user_type']) ? (int)$user['user_type'] : 1;
                $status = isset($user['status']) ? (int)$user['status'] : 1;
                // Include both SK Chairpersons (type 2) and Pederasyon (type 3), both Accepted
                return ($userType === 2 || $userType === 3) && $status === 2;
            });

            if (empty($officials)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No officials found for credentials Word generation']);
            }

            // Generate credentials Word document and stream directly to user
            $tabName = ($activeTab === 'pederasyon') ? 'Pederasyon' : 'SK';
            $fileName = $tabName . '_Officials_Credentials_' . date('Y-m-d_His') . '.docx';
            $phpWord = $this->generateCredentialsWordDocument($officials, $activeTab);
            
            // Stream the file directly to the user
            $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            
            // Set headers for file download
            $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');
            $this->response->setHeader('Cache-Control', 'max-age=0');
            
            // Write to output buffer
            ob_start();
            $writer->save('php://output');
            $wordOutput = ob_get_clean();
            
            log_message('info', 'Credentials Word streamed successfully: ' . $fileName);
            return $this->response->setBody($wordOutput);
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
            
            require_once ROOTPATH . '../vendor/autoload.php';
            
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

            // Move the logo banner into a repeating section header
            $header = $section->addHeader();

            $headerTable = $header->addTable([
                'borderSize' => 0,
                'borderColor' => 'FFFFFF',
                'width' => 100 * 50,
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
            ]);
            $headerTable->addRow();

            // Left logo cell (Pederasyon)
            $leftCell = $headerTable->addCell(2000, ['valign' => 'center']);
            if (isset($logos['pederasyon'])) {
                $logoPath = ROOTPATH . $logos['pederasyon']['file_path'];
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
                $logoPath = ROOTPATH . $logos['iriga_city']['file_path'];
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

            $header->addTextBreak();
            $header->addText('PANLUNGSOD NA PEDERASYON NG MGA KABATAAN', $titleStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $header->addText('OFFICIALS LOGIN CREDENTIALS', $titleStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);

            $section->addTextBreak(2);

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
                $skTable->addRow(null, ['tblHeader' => true]);
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
                $section->addText('PANLUNGSOD NA PEDERASYON NG MGA KABATAAN', $titleStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $section->addText('OFFICIALS LOGIN CREDENTIALS', $titleStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
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
                $pedTable->addRow(null, ['tblHeader' => true]);
                $pedTable->addCell(1000)->addText('User ID', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $pedTable->addCell(2000)->addText('Full Name', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $pedTable->addCell(1200)->addText('Barangay', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $pedTable->addCell(1800)->addText('Position', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $pedTable->addCell(1500)->addText('Ped Username', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $pedTable->addCell(1500)->addText('Ped Password', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                
                foreach ($pederasyonOfficials as $official) {
                    $fullName = trim(($official['first_name'] ?? '') . ' ' . ($official['middle_name'] ?? '') . ' ' . ($official['last_name'] ?? ''));
                    $barangay = BarangayHelper::getBarangayName($official['barangay']);
                    
                    $pedPosition = isset($official['ped_position']) ? (int)$official['ped_position'] : null;
                    $position = $this->getPedPositionLabel($pedPosition);
                    
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
            // $outputDir = FCPATH . 'uploads/generated/';
            // if (!is_dir($outputDir)) {
            //     mkdir($outputDir, 0755, true);
            // }
            
            $fileName = 'PEDERASYON_Officials_Credentials_' . date('Y-m-d') . '.docx';
            $outputPath = $fileName;
            
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
            
            // Filter for credentials: Include user_type=2 (SK Chairpersons) and user_type=3 (Pederasyon) with Accepted status
            $officials = array_filter($users, function($user) {
                $userType = isset($user['user_type']) ? (int)$user['user_type'] : 1;
                $status = isset($user['status']) ? (int)$user['status'] : 1;
                // Include both SK Chairpersons (type 2) and Pederasyon (type 3), both Accepted
                return ($userType === 2 || $userType === 3) && $status === 2;
            });

            if (empty($officials)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No officials found for credentials Excel generation']);
            }

            // Generate Excel document and stream directly to user
            $tabName = ($activeTab === 'pederasyon') ? 'Pederasyon' : 'SK';
            $fileName = $tabName . '_Officials_Credentials_' . date('Y-m-d_His') . '.xlsx';
            $spreadsheet = $this->generateCredentialsExcelDocument($officials, $activeTab);
            
            // Clear any previous output
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            // Set headers for file download
            $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');
            $this->response->setHeader('Cache-Control', 'max-age=0');
            $this->response->setHeader('Pragma', 'public');
            
            // Write to output buffer
            ob_start();
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            $excelOutput = ob_get_clean();
            
            log_message('info', 'Credentials Excel streamed successfully: ' . $fileName);
            return $this->response->setBody($excelOutput);
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
            require_once ROOTPATH . '../vendor/autoload.php';

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
                // Two-line title for Pederasyon (matching Word document format)
                $sheet->setCellValue('A' . $currentRow, 'PANLUNGSOD NA PEDERASYON NG MGA KABATAAN');
                $sheet->mergeCells('A' . $currentRow . ':F' . $currentRow);
                $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(11);
                $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A' . $currentRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $sheet->getStyle('A' . $currentRow)->getFill()->getStartColor()->setARGB('FFE8E8E8');
                $currentRow++;
                
                $sheet->setCellValue('A' . $currentRow, 'OFFICIALS LOGIN CREDENTIALS');
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
                    
                    $pedPosition = isset($official['ped_position']) ? (int)$official['ped_position'] : null;
                    $position = $this->getPedPositionLabel($pedPosition);
                    
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

            // // Save the document
            // $outputDir = FCPATH . 'uploads/generated/';
            // if (!is_dir($outputDir)) {
            //     mkdir($outputDir, 0755, true);
            // }
            
            $fileName = 'PEDERASYON_Officials_Credentials_' . date('Y-m-d') . '.xlsx';
            $outputPath = $fileName;
            
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
        // Preflight: Zip is required for PhpSpreadsheet (XLSX)
        if (!class_exists('ZipArchive') || !extension_loaded('zip')) {
            log_message('error', 'ZipArchive class or zip extension not available for Excel generation');
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Server error: ZIP extension required for Excel document generation is not available'
            ]);
        }
        
        try {
            log_message('info', 'Starting Pederasyon Attendance Report Excel generation for event: ' . $eventId);
            
            // Get event and attendance data
            $eventModel = new EventModel();
            $event = $eventModel->find($eventId);
            
            if (!$event) {
                log_message('error', 'Event not found for ID: ' . $eventId);
                return $this->response->setJSON(['success' => false, 'message' => 'Event not found']);
            }
            
            // Get attendance records for this event (similar to SK implementation)
            $attendanceData = $this->getAttendanceDataForEvent($eventId);
            $attendanceRecords = $attendanceData['records'];
            $barangayName = $attendanceData['barangay_name'];
            
            if (empty($attendanceRecords)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No attendance records found for this event']);
            }
            
            // Generate Excel document and stream directly to user
            $eventTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $event['title']);
            $eventDate = date('Y-m-d', strtotime($event['start_datetime']));
            $fileName = 'Pederasyon_' . $eventTitle . '_Attendance_' . $eventDate . '.xlsx';
            $spreadsheet = $this->generateAttendanceExcelDocument($event, $attendanceRecords, $barangayName);
            
            // Clear any previous output
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            // Set headers for file download
            $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');
            $this->response->setHeader('Cache-Control', 'max-age=0');
            $this->response->setHeader('Pragma', 'public');
            
            // Write to output buffer
            ob_start();
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            $excelOutput = ob_get_clean();
            
            log_message('info', 'Pederasyon Attendance Excel streamed successfully: ' . $fileName);
            return $this->response->setBody($excelOutput);
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
        // Preflight: Zip is required for PhpWord (DOCX)
        if (!class_exists('ZipArchive') || !extension_loaded('zip')) {
            log_message('error', 'ZipArchive class or zip extension not available for Word generation');
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Server error: ZIP extension required for Word document generation is not available'
            ]);
        }
        
        try {
            log_message('info', 'Starting Pederasyon Attendance Report Word generation for event: ' . $eventId);
            
            // Get event and attendance data
            $eventModel = new EventModel();
            $event = $eventModel->find($eventId);
            
            if (!$event) {
                log_message('error', 'Event not found for ID: ' . $eventId);
                return $this->response->setJSON(['success' => false, 'message' => 'Event not found']);
            }
            
            // Get attendance records for this event
            $attendanceData = $this->getAttendanceDataForEvent($eventId);
            $attendanceRecords = $attendanceData['records'];
            $barangayName = $attendanceData['barangay_name'];
            
            log_message('info', 'Found ' . count($attendanceRecords) . ' attendance records for event ' . $eventId);
            
            if (empty($attendanceRecords)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No attendance records found for this event']);
            }
            
            // Get logos for the Word document
            $logos = $this->getLogosForDocument();
            log_message('info', 'Retrieved ' . count($logos) . ' logos for document');
            
            // Generate Word document and stream directly to user
            $eventTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $event['title']);
            $eventDate = date('Y-m-d', strtotime($event['start_datetime']));
            $fileName = 'Pederasyon_' . $eventTitle . '_Attendance_' . $eventDate . '.docx';
            $phpWord = $this->generateAttendanceWordDocument($event, $attendanceRecords, $logos, $barangayName);
            
            // Stream the file directly to the user
            $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            
            // Set headers for file download
            $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');
            $this->response->setHeader('Cache-Control', 'max-age=0');
            
            // Write to output buffer
            ob_start();
            $writer->save('php://output');
            $wordOutput = ob_get_clean();
            
            log_message('info', 'Pederasyon Attendance Word streamed successfully: ' . $fileName);
            return $this->response->setBody($wordOutput);
        } catch (\Exception $e) {
            log_message('error', 'Error in Pederasyon generateAttendanceReportWord: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
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
            $attendanceModel = new AttendanceModel();
            $userModel = new UserModel();
            $addressModel = new AddressModel();
            
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
                                try {
                                    $barangayName = BarangayHelper::getBarangayName($address['barangay']);
                                } catch (\Exception $e) {
                                    // Fallback - use the address barangay field directly
                                    $barangayName = $address['barangay'];
                                    log_message('warning', 'BarangayHelper error, using fallback: ' . $e->getMessage());
                                }
                            }
                            // Also store barangay name in record for table display
                            try {
                                $record['barangay_name'] = BarangayHelper::getBarangayName($address['barangay']);
                            } catch (\Exception $e) {
                                $record['barangay_name'] = $address['barangay'];
                                log_message('warning', 'BarangayHelper error for record, using fallback: ' . $e->getMessage());
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
                    $logoPath = ROOTPATH . $logos['pederasyon']['file_path'];
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
            $sheet->setCellValue('D' . $row, 'PANLUNGSOD NA PEDERASYON NG MGA');
            $sheet->getStyle('D' . $row)->getFont()->setBold(false)->setSize(10);
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            $row++;
            $sheet->setCellValue('D' . $row, 'SANGGUNIANG KABATAAN');
            $sheet->getStyle('D' . $row)->getFont()->setBold(false)->setSize(10);
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            // Removed barangay line for Pederasyon documents - header intentionally shows city + Panlungsod Pederasyon only
            
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

            // Set page setup for 13 x 8.5 inch landscape with 0.5 inch margins
            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL); // Closest to 13x8.5
            $sheet->getPageMargins()->setTop(0.5)->setRight(0.5)->setBottom(0.5)->setLeft(0.5);
            $sheet->getPageSetup()->setFitToPage(false);
            
            // Save the document
            // $outputDir = FCPATH . 'uploads/generated/';
            // if (!is_dir($outputDir)) {
            //     mkdir($outputDir, 0755, true);
            // }
            
            $eventTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $event['title']);
            $fileName = $eventTitle . '_Attendance_Report_' . date('Y-m-d') . '.xlsx';
            $outputPath = $fileName;
            
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
            log_message('info', 'Starting Word document generation with ' . count($attendanceRecords) . ' records');
            
            // Check if PhpWord is available
            if (!class_exists('\PhpOffice\PhpWord\PhpWord')) {
                throw new \Exception('PhpWord library not found');
            }
            
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            
            // Set document properties
            $properties = $phpWord->getDocInfo();
            $properties->setCreator('K-NECT System');
            $properties->setCompany('Sangguniang Kabataan Pederasyon');
            $properties->setTitle('Attendance Report - ' . $event['title']);
            $properties->setDescription('Attendance report generated from K-NECT System');
            $properties->setSubject('Attendance Report');
            
            // Add section with landscape orientation and custom 13x8.5in size
            $section = $phpWord->addSection([
                'orientation' => 'landscape',
                'pageSizeW' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(13.0),
                'pageSizeH' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(8.5),
                'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.5),
                'marginRight' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.5),
                'marginTop' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.5),
                'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.5)
            ]);
            
            // Header styles
            $headerStyle = ['name' => 'Arial', 'size' => 12, 'bold' => true];
            $subHeaderStyle = ['name' => 'Arial', 'size' => 10, 'bold' => false];
            $titleStyle = ['name' => 'Arial', 'size' => 14, 'bold' => true];
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
                $logoPath = ROOTPATH . $logos['pederasyon']['file_path'];
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
            $centerCell->addText('SANGGUNIANG KABATAAN', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            
            // Right logo cell (Iriga City)
            $rightCell = $headerTable->addCell(2000, ['valign' => 'center']);
            if (isset($logos['iriga_city'])) {
                $logoPath = ROOTPATH . $logos['iriga_city']['file_path'];
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
            
            // Add title and event details (no extra space after paragraphs)
            $section->addTextBreak();
            $section->addText('ATTENDANCE REPORT', $titleStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $section->addTextBreak();
            
            // Event information - FIXED: Remove space after paragraphs
            $section->addText('Event: ' . $event['title'], $subHeaderStyle, ['spaceAfter' => 0]);
            $section->addText('Date: ' . date('F j, Y', strtotime($event['start_datetime'])), $subHeaderStyle, ['spaceAfter' => 0]);
            $section->addText('Time: ' . date('g:i A', strtotime($event['start_datetime'])) . ' - ' . date('g:i A', strtotime($event['end_datetime'])), $subHeaderStyle, ['spaceAfter' => 0]);
            if (!empty($event['location'])) {
                $section->addText('Location: ' . $event['location'], $subHeaderStyle, ['spaceAfter' => 0]);
            }
            $section->addTextBreak();
            
            // Create attendance table and compute column widths to exactly fill printable area
            $table = $section->addTable([
                'borderSize' => 4,
                'borderColor' => '000000',
                'cellMargin' => 20,
                // width will be set by cell widths; keep table centered
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
            ]);

            // Printable width in twips: page width (13in) minus left/right margins (0.5in each) = 12in
            $printableWidth = \PhpOffice\PhpWord\Shared\Converter::inchToTwip(12.0);
            // Use the previous relative column units to distribute widths proportionally
            $colRel = [1000, 1500, 3500, 1000, 1200, 1200, 1200, 1200, 1200, 1200];
            $totalRel = array_sum($colRel);
            $colWidths = array_map(function($r) use ($printableWidth, $totalRel) {
                return (int) floor(($r / $totalRel) * $printableWidth);
            }, $colRel);

            // Add table header with computed column widths (spaceAfter=0 to remove extra paragraph spacing)
            $table->addRow();
            $table->addCell($colWidths[0])->addText('No.', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell($colWidths[1])->addText('KK Number', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell($colWidths[2])->addText('Name', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell($colWidths[3])->addText('Zone', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell($colWidths[4])->addText('AM Time-In', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell($colWidths[5])->addText('AM Time-Out', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell($colWidths[6])->addText('AM Status', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell($colWidths[7])->addText('PM Time-In', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell($colWidths[8])->addText('PM Time-Out', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell($colWidths[9])->addText('PM Status', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            
            // Add data rows
            foreach ($attendanceRecords as $index => $record) {
                // AM Time-In
                $amTimeIn = '-';
                if (!empty($record['time-in_am'])) {
                    $amTimeIn = date('h:i A', strtotime($record['time-in_am']));
                }
                
                // AM Time-Out
                $amTimeOut = '-';
                if (!empty($record['time-out_am'])) {
                    $amTimeOut = date('h:i A', strtotime($record['time-out_am']));
                }
                
                // PM Time-In
                $pmTimeIn = '-';
                if (!empty($record['time-in_pm'])) {
                    $pmTimeIn = date('h:i A', strtotime($record['time-in_pm']));
                }
                
                // PM Time-Out
                $pmTimeOut = '-';
                if (!empty($record['time-out_pm'])) {
                    $pmTimeOut = date('h:i A', strtotime($record['time-out_pm']));
                }
                
                // AM Status
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
                
                // PM Status
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
                
                // Format name as Lastname, Firstname Middlename
                $userName = $record['user_name'] ?? 'N/A';
                $nameParts = explode(' ', trim($userName));
                if (count($nameParts) >= 2) {
                    $firstName = $nameParts[0];
                    $lastName = end($nameParts);
                    $middleName = count($nameParts) > 2 ? implode(' ', array_slice($nameParts, 1, -1)) : '';
                    $formattedName = $lastName . ', ' . $firstName . ($middleName ? ' ' . $middleName : '');
                } else {
                    $formattedName = $userName;
                }
                
                $table->addRow();
                $table->addCell($colWidths[0])->addText($index + 1, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $table->addCell($colWidths[1])->addText($record['permanent_user_id'] ?? 'N/A', $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $table->addCell($colWidths[2])->addText($formattedName, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT, 'spaceAfter' => 0]);
                $table->addCell($colWidths[3])->addText($record['zone_purok'] ?? 'N/A', $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $table->addCell($colWidths[4])->addText($amTimeIn, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $table->addCell($colWidths[5])->addText($amTimeOut, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $table->addCell($colWidths[6])->addText($amStatus, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $table->addCell($colWidths[7])->addText($pmTimeIn, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $table->addCell($colWidths[8])->addText($pmTimeOut, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $table->addCell($colWidths[9])->addText($pmStatus, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            }
            
            // Save the document
            // $outputDir = FCPATH . 'uploads/generated/';
            // if (!is_dir($outputDir)) {
            //     if (!mkdir($outputDir, 0755, true)) {
            //         throw new \Exception('Failed to create output directory: ' . $outputDir);
            //     }
            //     log_message('info', 'Created output directory: ' . $outputDir);
            // }
            
            $eventTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $event['title']);
            $fileName = 'Pederasyon_Attendance_Report_' . $eventTitle . '_' . date('Y-m-d') . '.docx';
            $outputPath = $fileName;
            
            log_message('info', 'Attempting to save Word document to: ' . $outputPath);
            
            $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save($outputPath);
            
            if (!file_exists($outputPath)) {
                throw new \Exception('Word document was not created at expected path: ' . $outputPath);
            }
            
            log_message('info', 'Pederasyon Attendance Word document saved successfully to: ' . $outputPath);
            return $outputPath;
            
        } catch (\Exception $e) {
            log_message('error', 'Error in generateAttendanceWordDocument: ' . $e->getMessage());
            throw $e;
        }
    }

    public function bulkUpdateUserType()
    {
        try {
            $userIds = $this->request->getPost('user_ids');
            $newUserType = (int)$this->request->getPost('user_type');

            if (empty($userIds) || !is_array($userIds) || !in_array($newUserType, [1, 2, 3])) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid input data']);
            }

            $userModel = new UserModel();
            $addressModel = new AddressModel();
            $db = \Config\Database::connect();
            
            $db->transStart();

            $successCount = 0;
            $errors = [];

            // System validation: must keep at least one Pederasyon overall
            $userModelForCount = new UserModel();
            $remainingPed = (int) $userModelForCount->where('user_type', 3)->countAllResults();

            foreach ($userIds as $userId) {
                try {
                    if (!is_numeric($userId)) {
                        $errors[] = "Invalid user ID: $userId";
                        continue;
                    }

                    $user = $userModel->find($userId);
                    if (!$user) {
                        $errors[] = "User not found: ID $userId";
                        continue;
                    }

                    // Initialize update data for this user
                    $updateData = [
                        'user_type' => $newUserType
                    ];

                    // Guard: If converting a Pederasyon to a non-Pederasyon and that would remove the last one, block
                    $currentType = (int)($user['user_type'] ?? 1);
                    if ($currentType === 3 && $newUserType !== 3) {
                        if ($remainingPed <= 1) {
                            $errors[] = "Cannot remove the last Pederasyon user (ID $userId). The system must always have at least one.";
                            continue;
                        }
                    }

                    // Additional validation for SK Chairperson (user_type = 2)
                    if ($newUserType === 2) {
                        // Get user's barangay
                        $userAddress = $addressModel->where('user_id', $userId)->first();
                        
                        if ($userAddress) {
                            // Check if there's already an SK Chairperson in this barangay (excluding current user)
                            $existingChairperson = $userModel
                                ->select('user.id')
                                ->join('address', 'address.user_id = user.id', 'left')
                                ->where('user.user_type', 2) // SK Chairperson
                                ->where('user.status', 2) // Approved users only
                                ->where('address.barangay', $userAddress['barangay'])
                                ->where('user.id !=', $userId) // Exclude current user
                                ->first();
                                
                            if ($existingChairperson) {
                                $errors[] = "Cannot assign SK Chairperson to user ID $userId: This barangay already has an SK Chairperson";
                                continue;
                            }
                        }
                        
                        // Auto-verify when changing to SK Chairperson
                        $updateData['status'] = 2;
                        
                        // Generate USER_ID if missing
                        if (empty($user['user_id'])) {
                            $attempts = 0;
                            $newId = null;
                            do {
                                $newId = UserHelper::generateYearPrefixedUserId();
                                $exists = $userModel->where('user_id', $newId)->first();
                                $attempts++;
                            } while ($exists && $attempts < 5);
                            
                            if (!$newId) {
                                $errors[] = "Failed to generate unique user_id for user ID $userId";
                                continue;
                            }
                            $updateData['user_id'] = $newId;
                        }
                        
                        // Generate SK credentials if missing
                        if (empty($user['sk_username']) || empty($user['sk_password'])) {
                            $updateData['sk_username'] = UserHelper::generateSKUsername($user['first_name'], $user['last_name']);
                            $updateData['sk_password'] = UserHelper::generatePassword(8);
                        }
                        
                        // Set sk_position to 1 (SK Chairperson position)
                        $updateData['sk_position'] = 1;
                        
                    } elseif ($newUserType === 3 && (int)($user['status'] ?? 0) === 1) {
                        // Auto-verify for Pederasyon promotions
                        $updateData['status'] = 2;
                        
                        if (empty($user['user_id'])) {
                            $attempts = 0;
                            $newId = null;
                            do {
                                $newId = UserHelper::generateYearPrefixedUserId();
                                $exists = $userModel->where('user_id', $newId)->first();
                                $attempts++;
                            } while ($exists && $attempts < 5);
                            
                            if (!$newId) {
                                $errors[] = "Failed to generate unique user_id for user ID $userId";
                                continue;
                            }
                            $updateData['user_id'] = $newId;
                        }
                    }

                    // Handle credential generation based on user type
                    if ($newUserType === 2) { // SK Chairperson
                        // SK credentials already handled above
                        
                        // If downgrading from PED -> SK, clear PED credentials
                        if ($currentType === 3) {
                            $updateData['ped_username'] = null;
                            $updateData['ped_password'] = null;
                            $updateData['ped_position'] = null;
                        }
                        
                    } elseif ($newUserType === 3) { // Pederasyon Officer
                        $wasSK = ($currentType === 2);

                        // Ensure PED credentials exist
                        if (empty($user['ped_username']) || empty($user['ped_password'])) {
                            $updateData['ped_username'] = UserHelper::generatePEDUsername($user['first_name'], $user['last_name']);
                            $updateData['ped_password'] = UserHelper::generatePassword(8);
                        }

                        // If the user was SK before, keep SK credentials; otherwise generate them
                        if (!$wasSK) {
                            if (empty($user['sk_username']) || empty($user['sk_password'])) {
                                $updateData['sk_username'] = UserHelper::generateSKUsername($user['first_name'], $user['last_name']);
                                $updateData['sk_password'] = UserHelper::generatePassword(8);
                            }
                            // Set sk_position to 1 (SK Chairperson) when promoting to Pederasyon
                            $updateData['sk_position'] = 1;
                        }
                        
                        // Set ped_position to null by default (Member)
                        if (!isset($updateData['ped_position'])) {
                            $updateData['ped_position'] = null;
                        }
                        
                    } elseif ($newUserType === 1) { // KK Member
                        // If downgrading to KK, clear credentials accordingly
                        if ($currentType === 3) { // PED -> KK
                            $updateData['ped_username'] = null;
                            $updateData['ped_password'] = null;
                            $updateData['ped_position'] = null;
                            $updateData['sk_username'] = null;
                            $updateData['sk_password'] = null;
                            $updateData['sk_position'] = null;
                        } elseif ($currentType === 2) { // SK -> KK
                            $updateData['sk_username'] = null;
                            $updateData['sk_password'] = null;
                            $updateData['sk_position'] = null;
                        }
                    }

                    // Update user table
                    $result = $userModel->update($userId, $updateData);
                    if (!$result) {
                        $errors[] = "Failed to update user ID $userId";
                        continue;
                    }

                    if ($currentType === 3 && $newUserType !== 3) {
                        $remainingPed = max(0, $remainingPed - 1);
                    }

                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "Error updating user ID $userId: " . $e->getMessage();
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON(['success' => false, 'message' => 'Database transaction failed']);
            }

            if ($successCount === 0) {
                return $this->response->setJSON(['success' => false, 'message' => 'No users were updated. Errors: ' . implode('; ', $errors)]);
            }

            $message = "$successCount user(s) updated successfully.";
            if (!empty($errors)) {
                $message .= " Errors: " . implode('; ', $errors);
            }

            return $this->response->setJSON(['success' => true, 'message' => $message]);

        } catch (\Exception $e) {
            log_message('error', 'Error in bulkUpdateUserType: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while updating users']);
        }
    }

    public function checkSKChairpersonByBarangay()
    {
        try {
            $barangayId = $this->request->getPost('barangay_id');
            $currentUserId = $this->request->getPost('current_user_id'); // Optional: exclude current user from check

            if (!$barangayId) {
                return $this->response->setJSON(['success' => false, 'message' => 'Barangay ID is required']);
            }

            $userModel = new UserModel();
            $addressModel = new AddressModel();

            // Query to find existing SK Chairperson in the barangay
            $query = $userModel
                ->select('user.id, user.user_id, user.first_name, user.last_name, user.status')
                ->join('address', 'address.user_id = user.id', 'left')
                ->where('user.user_type', 2) // SK Chairperson
                ->where('user.status', 2) // Approved users only
                ->where('address.barangay', $barangayId);

            // Exclude current user if provided (for editing existing user)
            if ($currentUserId) {
                $query->where('user.id !=', $currentUserId);
            }

            $existingChairperson = $query->first();

            if ($existingChairperson) {
                return $this->response->setJSON([
                    'success' => true,
                    'hasChairperson' => true,
                    'chairperson' => [
                        'id' => $existingChairperson['id'],
                        'user_id' => $existingChairperson['user_id'],
                        'name' => $existingChairperson['first_name'] . ' ' . $existingChairperson['last_name']
                    ]
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => true,
                    'hasChairperson' => false
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error in checkSKChairpersonByBarangay: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while checking SK Chairperson']);
        }
    }

    /**
     * Check if there is at least one Pederasyon officer with login credentials
    * Positions 1-7 have credentials, NULL (Member) does not
     * Returns array with 'hasOfficers' boolean and 'officers' array
     */
    private function checkPederasyonOfficersWithCredentials()
    {
        $userModel = new UserModel();
        
        // Get all users with Pederasyon positions (1-7)
        $officers = $userModel->whereIn('ped_position', [1, 2, 3, 4, 5, 6, 7])
                              ->where('user_type', 3) // Pederasyon Officers
                              ->findAll();
        
        $hasOfficers = !empty($officers);
        
        // Format officers data for response
        $officersData = [];
        if ($hasOfficers) {
            $positionNames = $this->getPedPositionMap();
            
            foreach ($officers as $officer) {
                $officersData[] = [
                    'name' => trim(($officer['first_name'] ?? '') . ' ' . ($officer['middle_name'] ?? '') . ' ' . ($officer['last_name'] ?? '')),
                    'position' => $positionNames[$officer['ped_position']] ?? 'Unknown Position',
                    'username' => $officer['ped_username'] ?? 'N/A'
                ];
            }
        }
        
        return [
            'hasOfficers' => $hasOfficers,
            'officers' => $officersData,
            'count' => count($officers)
        ];
    }

    /**
     * Generate a temporary password (8 characters: uppercase, lowercase, and numbers)
     * This password will be stored as plain text so it can be shown to the user once
     * User should change it on first login
     */
    private function generateTemporaryPassword()
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        
        // Ensure at least one of each type
        $password = '';
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        
        // Fill the rest randomly
        $allChars = $uppercase . $lowercase . $numbers;
        for ($i = 3; $i < 8; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }
        
        // Shuffle the password
        return str_shuffle($password);
    }

    /**
     * Provide a consistent label map for Pederasyon officer positions.
     */
    private function getPedPositionMap(): array
    {
        return [
            1 => 'President',
            2 => 'Vice President',
            3 => 'Secretary',
            4 => 'Treasurer',
            5 => 'Auditor',
            6 => 'Public Information Officer',
            7 => 'Sergeant at Arms',
        ];
    }

    /**
     * Resolve a ped_position value into a display label.
     */
    private function getPedPositionLabel(?int $pedPosition): string
    {
        $map = $this->getPedPositionMap();
        return $map[$pedPosition] ?? 'Member';
    }

    /**
     * Generate Pederasyon Officers Credentials Word Document
     */
    public function generatePedCredentialsWord()
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
            log_message('info', 'Starting Pederasyon Officers Credentials Word generation...');
            
            // Use shared ProfileController for common functionality
            $profileController = new ProfileController();
            $users = $profileController->getAllUsersWithExtendedInfo();
            $users = $profileController->processUsersForMemberListing($users, 'pederasyon');
            
            // Filter Pederasyon Officers (user_type = 3, accepted)
            $officials = array_filter($users, function($user) {
                $userType = isset($user['user_type']) ? (int)$user['user_type'] : 1;
                $status = isset($user['status']) ? (int)$user['status'] : 1;
                return $userType === 3 && $status === 2; // Pederasyon Officers, Accepted
            });

            if (empty($officials)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No Pederasyon officers found for credentials']);
            }

            // Get logos for the Word document
            $logos = $this->getLogosForDocument();

            // Generate Word document and stream directly to user
            $fileName = 'Pederasyon_Officers_Credentials_' . date('Y-m-d_His') . '.docx';
            $phpWord = $this->generatePedCredentialsWordDocument($officials, $logos);
            
            // Stream the file directly to the user
            $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            
            // Set headers for file download
            $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');
            $this->response->setHeader('Cache-Control', 'max-age=0');
            
            // Write to output buffer
            ob_start();
            $writer->save('php://output');
            $wordOutput = ob_get_clean();

            log_message('info', 'Pederasyon credentials Word streamed successfully: ' . $fileName);
            return $this->response->setBody($wordOutput);
        } catch (\Exception $e) {
            log_message('error', 'Error in generatePedCredentialsWord: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    private function generatePedCredentialsWordDocument($officials, $logos = [])
    {
        try {
            require_once FCPATH . '../vendor/autoload.php';
            
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
            // Set document properties
            $properties = $phpWord->getDocInfo();
            $properties->setCreator('K-NECT System');
            $properties->setCompany('Panlungsod na Pederasyon ng mga Sangguniang Kabataan ng Iriga');
            $properties->setTitle('Pederasyon Officers Credentials');
            $properties->setDescription('Login credentials for Pederasyon officers generated from K-NECT System');
            $properties->setCategory('Government Document');
            $properties->setSubject('Officers Credentials');
            
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
                            'width' => 60,
                            'height' => 60,
                            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
                        ]);
                    } catch (\Exception $e) {
                        $leftCell->addText('PEDERASYON LOGO', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                    }
                }
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
                }
            }
            
            // Add horizontal line and title
            $section->addTextBreak();
            $section->addText('PANLUNGSOD NA PEDERASYON NG MGA SANGGUNIANG KABATAAN', $titleStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $section->addText('OFFICIALS LOGIN CREDENTIALS', $titleStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $section->addTextBreak();
            
            // Create Pederasyon credentials table
            // For landscape A4: ~15840 twips width, minus margins = ~13500 usable width
            $pedTable = $section->addTable([
                'borderSize' => 4,
                'borderColor' => '000000',
                'cellMargin' => 20,
                'width' => 13500,
                'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::TWIP,
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
            ]);
            
            // Add Pederasyon table header - proportional widths totaling 13500 twips
            $pedTable->addRow();
            $pedTable->addCell(1100, ['valign' => 'center'])->addText('User ID', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $pedTable->addCell(3400, ['valign' => 'center'])->addText('Full Name', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $pedTable->addCell(2100, ['valign' => 'center'])->addText('Barangay', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $pedTable->addCell(2500, ['valign' => 'center'])->addText('Position', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $pedTable->addCell(2700, ['valign' => 'center'])->addText('Username', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $pedTable->addCell(1700, ['valign' => 'center'])->addText('Password', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            
            // Find President and Secretary for signatures
            $presidentName = '';
            $secretaryName = '';
            
            foreach ($officials as $official) {
                $fullName = trim(($official['first_name'] ?? '') . ' ' . ($official['middle_name'] ?? '') . ' ' . ($official['last_name'] ?? ''));
                $barangay = BarangayHelper::getBarangayName($official['barangay']);
                
                $pedPosition = isset($official['ped_position']) ? (int)$official['ped_position'] : null;
                $position = $this->getPedPositionLabel($pedPosition);
                if ($pedPosition === 1) {
                    $presidentName = $fullName;
                } elseif ($pedPosition === 3) {
                    $secretaryName = $fullName;
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
                $pedTable->addCell(1100, ['valign' => 'center'])->addText(esc($official['user_id'] ?? ''), $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $pedTable->addCell(3400, ['valign' => 'center'])->addText(esc($fullName), $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $pedTable->addCell(2100, ['valign' => 'center'])->addText(esc($barangay), $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $pedTable->addCell(2500, ['valign' => 'center'])->addText(esc($position), $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $pedTable->addCell(2700, ['valign' => 'center'])->addText(esc($official['ped_username'] ?? 'N/A'), $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $pedTable->addCell(1700, ['valign' => 'center'])->addText(esc($pedPassword), $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            }

            // Add signature section
            $section->addTextBreak(2);
            $signatureTable = $section->addTable([
                'borderSize' => 0,
                'borderColor' => 'FFFFFF',
                'width' => 13500,
                'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::TWIP,
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
            ]);
            $signatureTable->addRow();
            
            // Left signature (Prepared by - Secretary)
            $leftSigCell = $signatureTable->addCell(6750, ['valign' => 'top']);
            $leftSigCell->addText('Prepared by:', $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $leftSigCell->addTextBreak(2);
            $leftSigCell->addText('_________________________', $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $leftSigCell->addText($secretaryName ?: '_________________________', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $leftSigCell->addText('Secretary', $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            
            // Right signature (Approved by - President)
            $rightSigCell = $signatureTable->addCell(6750, ['valign' => 'top']);
            $rightSigCell->addText('Approved by:', $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $rightSigCell->addTextBreak(2);
            $rightSigCell->addText('_________________________', $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $rightSigCell->addText($presidentName ?: '_________________________', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $rightSigCell->addText('President', $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);

            return $phpWord;
            
        } catch (\Exception $e) {
            log_message('error', 'Error in generatePedCredentialsWordDocument: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate Pederasyon Officers Credentials Excel Document
     */
    public function generatePedCredentialsExcel()
    {
        // Preflight: Zip is required for PhpSpreadsheet (XLSX)
        if (!class_exists('ZipArchive') || !extension_loaded('zip')) {
            $ini = function_exists('php_ini_loaded_file') ? (php_ini_loaded_file() ?: 'php.ini') : 'php.ini';
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Missing PHP zip extension. Enable extension=zip in ' . $ini . ' and restart the server to generate Excel documents.'
            ]);
        }
        try {
            log_message('info', 'Starting Pederasyon Officers Credentials Excel generation...');
            
            // Use shared ProfileController for common functionality
            $profileController = new ProfileController();
            $users = $profileController->getAllUsersWithExtendedInfo();
            $users = $profileController->processUsersForMemberListing($users, 'pederasyon');
            
            // Filter Pederasyon Officers (user_type = 3, accepted)
            $officials = array_filter($users, function($user) {
                $userType = isset($user['user_type']) ? (int)$user['user_type'] : 1;
                $status = isset($user['status']) ? (int)$user['status'] : 1;
                return $userType === 3 && $status === 2; // Pederasyon Officers, Accepted
            });

            if (empty($officials)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No Pederasyon officers found for credentials']);
            }

            // Generate Excel document and stream directly to user
            $fileName = 'Pederasyon_Officers_Credentials_' . date('Y-m-d_His') . '.xlsx';
            $spreadsheet = $this->generatePedCredentialsExcelDocument($officials);
            
            // Clear any previous output
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            // Set headers for file download
            $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');
            $this->response->setHeader('Cache-Control', 'max-age=0');
            $this->response->setHeader('Pragma', 'public');
            
            // Write to output buffer
            ob_start();
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            $excelOutput = ob_get_clean();
            
            log_message('info', 'Pederasyon credentials Excel streamed successfully: ' . $fileName);
            return $this->response->setBody($excelOutput);
        } catch (\Exception $e) {
            log_message('error', 'Error in generatePedCredentialsExcel: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    private function generatePedCredentialsExcelDocument($officials)
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
            $sheet->setCellValue('A' . $currentRow, 'PANLUNGSOD NA PEDERASYON NG MGA SANGGUNIANG KABATAAN');
            $sheet->mergeCells('A' . $currentRow . ':F' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $currentRow++;

            $sheet->setCellValue('A' . $currentRow, 'OFFICIALS LOGIN CREDENTIALS');
            $sheet->mergeCells('A' . $currentRow . ':F' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $currentRow++;

            $currentRow++; // Empty row

            // Table header
            $headerRow = $currentRow;
            $headers = ['User ID', 'Full Name', 'Barangay', 'Position', 'Username', 'Password'];
            $column = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($column . $headerRow, $header);
                $sheet->getStyle($column . $headerRow)->getFont()->setBold(true)->setSize(10);
                $sheet->getStyle($column . $headerRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle($column . $headerRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('DDDDDD');
                $sheet->getStyle($column . $headerRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $column++;
            }
            $currentRow++;

            // Find President and Secretary for signatures
            $presidentName = '';
            $secretaryName = '';

            // Populate table data
            foreach ($officials as $official) {
                $fullName = trim(($official['first_name'] ?? '') . ' ' . ($official['middle_name'] ?? '') . ' ' . ($official['last_name'] ?? ''));
                $barangay = BarangayHelper::getBarangayName($official['barangay']);
                
                $pedPosition = isset($official['ped_position']) ? (int)$official['ped_position'] : null;
                $position = $this->getPedPositionLabel($pedPosition);
                if ($pedPosition === 1) {
                    $presidentName = $fullName;
                } elseif ($pedPosition === 3) {
                    $secretaryName = $fullName;
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
                
                $sheet->setCellValue('A' . $currentRow, $official['user_id'] ?? '');
                $sheet->setCellValue('B' . $currentRow, $fullName);
                $sheet->setCellValue('C' . $currentRow, $barangay);
                $sheet->setCellValue('D' . $currentRow, $position);
                $sheet->setCellValue('E' . $currentRow, $official['ped_username'] ?? 'N/A');
                $sheet->setCellValue('F' . $currentRow, $pedPassword);
                
                // Apply styling to data rows
                $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                
                $currentRow++;
            }

            // Add signature section
            $currentRow += 2;
            $sheet->setCellValue('B' . $currentRow, 'Prepared by:');
            $sheet->setCellValue('E' . $currentRow, 'Approved by:');
            $sheet->getStyle('B' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            $currentRow += 3;
            $sheet->setCellValue('B' . $currentRow, '_________________________');
            $sheet->setCellValue('E' . $currentRow, '_________________________');
            $sheet->getStyle('B' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            $currentRow++;
            $sheet->setCellValue('B' . $currentRow, $secretaryName ?: '_________________________');
            $sheet->setCellValue('E' . $currentRow, $presidentName ?: '_________________________');
            $sheet->getStyle('B' . $currentRow)->getFont()->setBold(true);
            $sheet->getStyle('E' . $currentRow)->getFont()->setBold(true);
            $sheet->getStyle('B' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            $currentRow++;
            $sheet->setCellValue('B' . $currentRow, 'Secretary');
            $sheet->setCellValue('E' . $currentRow, 'President');
            $sheet->getStyle('B' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Auto-size columns
            foreach (range('A', 'F') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            return $spreadsheet;
            
        } catch (\Exception $e) {
            log_message('error', 'Error in generatePedCredentialsExcelDocument: ' . $e->getMessage());
            throw $e;
        }
    }

}