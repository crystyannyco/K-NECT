<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserExtInfoModel;
use App\Models\AddressModel;
use App\Models\SystemLogoModel;
use App\Models\BarangayModel;
use App\Models\EventModel;
use App\Models\AttendanceModel;
use App\Models\EventAttendanceModel;
use App\Libraries\UserHelper;
use App\Libraries\BarangayHelper;
use App\Libraries\ZoneHelper;
use App\Libraries\DemographicsHelper;

class SKController extends BaseController
{

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

        $skBarangay = $session->get('sk_barangay');
        $barangayName = BarangayHelper::getBarangayName($skBarangay);
        $addressBarangayName = '';
        if (!empty($profileData['address']['barangay'])) {
            $addressBarangayName = BarangayHelper::getBarangayName($profileData['address']['barangay']);
        }

        $data = array_merge($profileData, [
            'username' => $session->get('username'),
            'sk_barangay' => $skBarangay,
            'barangay_name' => $barangayName,
            'address_barangay_name' => $addressBarangayName,
        ]);

        return
            $this->loadView('K-NECT/SK/template/header') .
            $this->loadView('K-NECT/SK/template/sidebar') .
            $this->loadView('K-NECT/SK/account_settings', $data);
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
            return redirect()->to('sk/account-settings#security')->with('error', 'User not found.');
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
                        if (is_file($abs)) {
                            @unlink($abs);
                            break;
                        }
                    }
                }
            }

            $db->transCommit();
            return redirect()->to('sk/account-settings')->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to('sk/account-settings')->with('error', 'Failed to update profile: ' . $e->getMessage());
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
            return redirect()->to('sk/account-settings')->with('error', 'User not found.');
        }
        $dbUserId = $userRow['id'];

        $currentPassword = (string)$this->request->getPost('current_password');
        if ($currentPassword === '') {
            return redirect()->to('sk/account-settings#security')->with('error', 'Please enter your current password.');
        }
        // Verify current password against SK password (hashed or plaintext),
        // fallback to generic password if SK password is not set
        $storedSk = (string)($userRow['sk_password'] ?? '');
        $validCurrent = false;
        if ($storedSk !== '') {
            $isHashed = (strlen($storedSk) === 60 && preg_match('/^\$2y\$/', $storedSk));
            $validCurrent = $isHashed ? password_verify($currentPassword, $storedSk) : ($currentPassword === $storedSk);
        } else {
            $storedGeneric = (string)($userRow['password'] ?? '');
            if ($storedGeneric !== '') {
                $validCurrent = password_verify($currentPassword, $storedGeneric);
            }
        }
        if (!$validCurrent) {
            return redirect()->to('sk/account-settings#security')->with('error', 'Current password is incorrect.');
        }

    $newPassword = (string)$this->request->getPost('sk_password');
        $confirmPassword = (string)$this->request->getPost('confirm_password');
        if ($newPassword !== $confirmPassword) {
            return redirect()->to('sk/account-settings#security')->with('error', 'New passwords do not match.');
        }

        $errors = [];
        if (strlen($newPassword) < 8) { $errors[] = 'at least 8 characters'; }
        if (!preg_match('/[A-Z]/', $newPassword)) { $errors[] = 'one uppercase letter'; }
        if (!preg_match('/[a-z]/', $newPassword)) { $errors[] = 'one lowercase letter'; }
        if (!preg_match('/\d/', $newPassword)) { $errors[] = 'one number'; }
        if (!preg_match('/[!@#$%^&*()_+\-={}\[\]\\|;:"\'<>.,?\/]/', $newPassword)) { $errors[] = 'one special character'; }
        if (!empty($errors)) {
            return redirect()->to('sk/account-settings#security')->with('error', 'Password must contain: ' . implode(', ', $errors) . '.');
        }

        try {
            $userModel->update($dbUserId, [ 'sk_password' => password_hash($newPassword, PASSWORD_DEFAULT) ]);
            return redirect()->to('sk/account-settings#security')->with('success', 'Password updated successfully.');
        } catch (\Exception $e) {
            return redirect()->to('sk/account-settings#security')->with('error', 'Failed to update password: ' . $e->getMessage());
        }
    }
    public function dashboard()
    {
        $session = session();
        $skBarangay = $session->get('sk_barangay');
        $barangayName = BarangayHelper::getBarangayName($skBarangay);
        $username = $session->get('username');
        
        // Get document statistics for the current user
        $db = \Config\Database::connect();
        
        // Total documents uploaded by this user
        $totalDocuments = $db->query("SELECT COUNT(*) as count FROM documents WHERE uploaded_by = ?", [$username])->getRowArray()['count'];
        
        // Documents pending approval
        $pendingApproval = $db->query("SELECT COUNT(*) as count FROM documents WHERE uploaded_by = ? AND approval_status = 'pending'", [$username])->getRowArray()['count'];
        
        // Approved documents
        $approvedDocuments = $db->query("SELECT COUNT(*) as count FROM documents WHERE uploaded_by = ? AND approval_status = 'approved'", [$username])->getRowArray()['count'];
        
        // Shared documents (documents shared with this user)
        $sharedDocuments = $db->query("
            SELECT COUNT(DISTINCT ds.document_id) as count 
            FROM document_shares ds 
            JOIN user u ON ds.shared_with_user_id = u.id 
            WHERE u.username = ?
        ", [$username])->getRowArray()['count'];
        
        $data = [
            'user_id' => $session->get('user_id'),
            'username' => $username,
            'sk_barangay' => $skBarangay,
            'barangay_name' => $barangayName,
            'totalDocuments' => $totalDocuments,
            'pendingApproval' => $pendingApproval,
            'approvedDocuments' => $approvedDocuments,
            'sharedDocuments' => $sharedDocuments
        ];

        return 
            $this->loadView('K-NECT/SK/template/header') .
            $this->loadView('K-NECT/SK/template/sidebar') .
            $this->loadView('K-NECT/SK/dashboard', $data);
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

        $skBarangay = $session->get('sk_barangay');
        $barangayName = BarangayHelper::getBarangayName($skBarangay);
        // Compute address barangay name from profile data
        $addressBarangayName = '';
        if (!empty($profileData['address']['barangay'])) {
            $addressBarangayName = BarangayHelper::getBarangayName($profileData['address']['barangay']);
        }

        // Merge with session and SK-specific data
        $data = array_merge($profileData, [
            'username' => $session->get('username'),
            'sk_barangay' => $skBarangay,
            'barangay_name' => $barangayName,
            'address_barangay_name' => $addressBarangayName,
            'field_mappings' => $profileController->getFieldMappings(),
        ]);

        return 
            $this->loadView('K-NECT/SK/template/header') .
            $this->loadView('K-NECT/SK/template/sidebar') .
            $this->loadView('K-NECT/SK/profile', $data);
    }

    public function youthProfile()
    {
        $session = session();
        $skBarangay = $session->get('sk_barangay');
        $barangayName = BarangayHelper::getBarangayName($skBarangay);
        
        $userModel = new UserModel();
        $addressModel = new AddressModel();
        $userExtInfoModel = new UserExtInfoModel();

        $query = $userModel
            ->select('user.id, user.user_id, user.status, user.position, user.rfid_code, 
                     address.barangay, address.zone_purok, 
                     user.last_name, user.first_name, user.middle_name, user.suffix, user.birthdate, user.sex, user.email, user.phone_number,
                     user_ext_info.civil_status, user_ext_info.youth_classification, user_ext_info.age_group, 
                     user_ext_info.work_status, user_ext_info.educational_background,
                     user_ext_info.sk_voter, user_ext_info.sk_election, user_ext_info.national_voter, user_ext_info.kk_assembly, user_ext_info.how_many_times, user_ext_info.no_why,
                     user_ext_info.profile_picture, user_ext_info.birth_certificate, user_ext_info.upload_id, `user_ext_info`.`upload_id-back` AS upload_id_back')
            ->join('address', 'address.user_id = user.id', 'left')
            ->join('user_ext_info', 'user_ext_info.user_id = user.id', 'left');
        
        // Filter by SK's barangay if available
        if ($skBarangay) {
            $query->where('address.barangay', $skBarangay);
        }
        
        $users = $query->findAll();

        // Process user data with all backend logic
        foreach ($users as &$u) {
            // Calculate age
            $u['age'] = $u['birthdate'] ? (date_diff(date_create($u['birthdate']), date_create('today'))->y) : '';
            
            // Format full name
            $fullName = esc($u['last_name']);
            if (!empty($u['first_name'])) {
                $fullName .= ', ' . esc($u['first_name']);
            }
            if (!empty($u['middle_name'])) {
                $fullName .= ' ' . esc($u['middle_name']);
            }
            $u['full_name'] = $fullName;
            
            // Format sex
            $u['sex_text'] = $u['sex'] == '1' ? 'Male' : ($u['sex'] == '2' ? 'Female' : '');
            
            // Format barangay name
            $u['barangay_name'] = BarangayHelper::getBarangayName($u['barangay']);
            
            // Format zone/purok
            $u['zone_display'] = isset($u['zone_purok']) && !empty($u['zone_purok']) ? esc($u['zone_purok']) : '-';
            
            // Process status
            $status = isset($u['status']) ? (int)$u['status'] : 1;
            $u['status_value'] = $status;
            
            switch($status) {
                case 2:
                    $u['status_class'] = 'bg-green-100 text-green-800';
                    $u['status_text'] = 'Accepted';
                    break;
                case 3:
                    $u['status_class'] = 'bg-red-100 text-red-800';
                    $u['status_text'] = 'Rejected';
                    break;
                default:
                    $u['status_class'] = 'bg-yellow-100 text-yellow-800';
                    $u['status_text'] = 'Pending';
            }
            
            // Button logic
            $u['has_documents'] = !empty($u['birth_certificate']) || !empty($u['upload_id']);
            $u['can_verify'] = $u['has_documents'] && $status === 1;
            $u['user_json'] = htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8');
        }
        unset($u);

        // Check if zone data exists
        $hasZoneData = false;
        foreach ($users as $user) {
            if (isset($user['zone_purok']) && !empty($user['zone_purok'])) {
                $hasZoneData = true;
                break;
            }
        }

        // Get status counts from database
        $statusCounts = [
            'all' => 0,
            'pending' => 0,
            'accepted' => 0,
            'rejected' => 0
        ];
        
        // Create separate queries for each count to avoid stacking conditions
        $allQuery = $userModel->join('address', 'address.user_id = user.id', 'left');
        if ($skBarangay) {
            $allQuery->where('address.barangay', $skBarangay);
        }
        $statusCounts['all'] = $allQuery->countAllResults();
        
        $pendingQuery = $userModel->join('address', 'address.user_id = user.id', 'left');
        if ($skBarangay) {
            $pendingQuery->where('address.barangay', $skBarangay);
        }
        $statusCounts['pending'] = $pendingQuery->where('user.status', 1)->countAllResults();
        
        $acceptedQuery = $userModel->join('address', 'address.user_id = user.id', 'left');
        if ($skBarangay) {
            $acceptedQuery->where('address.barangay', $skBarangay);
        }
        $statusCounts['accepted'] = $acceptedQuery->where('user.status', 2)->countAllResults();
        
        $rejectedQuery = $userModel->join('address', 'address.user_id = user.id', 'left');
        if ($skBarangay) {
            $rejectedQuery->where('address.barangay', $skBarangay);
        }
        $statusCounts['rejected'] = $rejectedQuery->where('user.status', 3)->countAllResults();

        $data['user_list'] = $users;
        $data['sk_barangay'] = $skBarangay;
        $data['barangay_name'] = $barangayName;
        $data['status_counts'] = $statusCounts;
        $data['has_zone_data'] = $hasZoneData;
        $data['sample_user_data'] = isset($users[0]) ? $users[0] : [];
        
        // Add helper maps for JavaScript
        $data['barangay_map'] = BarangayHelper::getBarangayMap();
        $data['zone_map'] = ZoneHelper::getZoneMap();
    // Centralized demographic maps for consistent labels in JS
    $data['field_mappings'] = DemographicsHelper::allMapsForJs();

        return 
            $this->loadView('K-NECT/SK/template/header') .
            $this->loadView('K-NECT/SK/template/sidebar') .
            $this->loadView('K-NECT/SK/youth_profile', $data) .
            $this->loadView('K-NECT/SK/template/footer');
    }

    public function rfidAssignment()
    {
        $session = session();
        $skBarangay = $session->get('sk_barangay');
        $barangayName = BarangayHelper::getBarangayName($skBarangay);
        
        $userModel = new UserModel();
        $addressModel = new AddressModel();
        $userExtInfoModel = new UserExtInfoModel();

        $query = $userModel
            ->select('user.id, user.user_id, user.rfid_code, address.barangay, address.zone_purok, user.last_name, user.first_name, user.middle_name, user.birthdate, user.sex, user_ext_info.profile_picture')
            ->join('address', 'address.user_id = user.id', 'left')
            ->join('user_ext_info', 'user_ext_info.user_id = user.id', 'left')
            ->where('user.status', 2); // Only verified users
        
        // Filter by SK's barangay if available
        if ($skBarangay) {
            $query->where('address.barangay', $skBarangay);
        }
        
        $users = $query->findAll();

        // Process user data
        foreach ($users as &$u) {
            // Calculate age
            $u['age'] = $u['birthdate'] ? (date_diff(date_create($u['birthdate']), date_create('today'))->y) : '';
            
            // Format full name
            $fullName = esc($u['last_name']);
            if (!empty($u['first_name'])) {
                $fullName .= ', ' . esc($u['first_name']);
            }
            if (!empty($u['middle_name'])) {
                $fullName .= ' ' . esc($u['middle_name']);
            }
            $u['full_name'] = $fullName;
            
            // Format sex
            $u['sex_text'] = $u['sex'] == '1' ? 'Male' : ($u['sex'] == '2' ? 'Female' : '');
            
            // Format barangay name
            $u['barangay_name'] = BarangayHelper::getBarangayName($u['barangay']);
            
            // Format zone/purok
            $u['zone_display'] = isset($u['zone_purok']) && !empty($u['zone_purok']) ? esc($u['zone_purok']) : '-';
            
            // RFID status
            $u['has_rfid'] = !empty($u['rfid_code']);
            $u['rfid_status'] = $u['has_rfid'] ? 'Assigned' : 'Not Assigned';
            $u['rfid_status_class'] = $u['has_rfid'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
            
            $u['user_json'] = htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8');
        }
        unset($u);

        $data['user_list'] = $users;
        $data['sk_barangay'] = $skBarangay;
        $data['barangay_name'] = $barangayName;
        $data['total_users'] = count($users);
        $data['assigned_count'] = count(array_filter($users, function($u) { return $u['has_rfid']; }));
        $data['unassigned_count'] = $data['total_users'] - $data['assigned_count'];

        return 
            $this->loadView('K-NECT/SK/template/header') .
            $this->loadView('K-NECT/SK/template/sidebar') .
            $this->loadView('K-NECT/SK/rfid_assignment', $data) .
            $this->loadView('K-NECT/SK/template/footer');
    }


    // Modal-based verify user (accept)
    public function approved($userId)
    {
        try {
            if (!$userId || !is_numeric($userId)) {
                return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Missing or invalid user_id']);
            }

            $userModel = new UserModel();
            $user = $userModel->find($userId);
            if (!$user) {
                return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'User not found']);
            }

            // If already accepted, make it idempotent
            if (isset($user['status']) && (int)$user['status'] === 2) {
                return $this->response->setJSON(['success' => true, 'message' => 'User already accepted']);
            }

            $db = \Config\Database::connect();
            $db->transStart();

            $updateData = ['status' => 2];

            // Ensure we have a unique user_id if missing
            if (empty($user['user_id'])) {
                $attempts = 0;
                $newId = null;
                do {
                    $newId = UserHelper::generateYearPrefixedUserId();
                    $exists = $userModel->where('user_id', $newId)->first();
                    $attempts++;
                } while ($exists && $attempts < 5);
                $updateData['user_id'] = $newId;
            }

            $result = $userModel->update($userId, $updateData);

            $db->transComplete();

            if ($db->transStatus() === false || $result === false) {
                $errorMsg = 'Failed to accept user';
                $errors = method_exists($userModel, 'errors') ? $userModel->errors() : [];
                $dbError = $db->error();
                if (!empty($errors)) {
                    $errorMsg .= ': ' . implode('; ', array_values($errors));
                } elseif (!empty($dbError) && !empty($dbError['message'])) {
                    $errorMsg .= ': ' . $dbError['message'];
                }
                log_message('error', 'Approve user failed for ID ' . $userId . ' - ' . $errorMsg);
                return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => $errorMsg]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'User accepted successfully',
                'user_id' => $updateData['user_id'] ?? $user['user_id'] ?? null,
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Approve user exception for ID ' . $userId . ': ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to accept user: ' . $e->getMessage(),
            ]);
        }
    }

    public function reject($userId)
    {
        $reason = $this->request->getPost('reason');
        if (!$userId || empty($reason)) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Missing user_id or reason']);
        }
        
        $userModel = new UserModel();
        $userExtInfoModel = new UserExtInfoModel();
        
        $db = \Config\Database::connect();
        $db->transStart();
        
        $userModel->update($userId, ['status' => 3]);
        $userExtInfoModel->update($userId, ['reason' => $reason]);
        
        $db->transComplete();
        
        if ($db->transStatus() === false) {
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Failed to reject user']);
        }
        
        return $this->response->setJSON(['success' => true, 'message' => 'User rejected successfully']);
    }

    public function reverify($userId)
    {
        if (!$userId) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Missing user_id']);
        }
        
        $userModel = new UserModel();
        $user = $userModel->find($userId);
        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'User not found']);
        }

        // Change status back to pending (1)
        $result = $userModel->update($userId, ['status' => 1]);
        
        if ($result) {
            return $this->response->setJSON(['success' => true, 'message' => 'User set for re-verification successfully']);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Failed to set user for re-verification']);
        }
    }

    public function skOfficial()
    {
        $session = session();
        $skBarangay = $session->get('sk_barangay');
        $barangayName = BarangayHelper::getBarangayName($skBarangay);
        
        $userModel = new UserModel();
        $addressModel = new AddressModel();
        $userExtInfoModel = new UserExtInfoModel();

        $query = $userModel
            ->select('
                user.id, user.status, user.last_name, user.first_name, user.middle_name, user.suffix, user.email, user.sex, user.birthdate, user.user_type, user.position, user.sk_username, user.sk_password, user.ped_username, user.ped_password, address.barangay, address.municipality, address.province, address.region, address.zone_purok, user_ext_info.civil_status, user_ext_info.youth_classification, user_ext_info.age_group, user_ext_info.work_status, user_ext_info.educational_background, user_ext_info.sk_voter, user_ext_info.sk_election, user_ext_info.national_voter, user_ext_info.kk_assembly, user_ext_info.how_many_times, user_ext_info.no_why, user_ext_info.birth_certificate, user_ext_info.upload_id
            ')
            ->join('address', 'address.user_id = user.id', 'left')
            ->join('user_ext_info', 'user_ext_info.user_id = user.id', 'left')
            ->where('user.status', 2); // Only show accepted users
            
        // Filter by SK's barangay if available
        if ($skBarangay) {
            $query->where('address.barangay', $skBarangay);
        }
        
        $users = $query->findAll();

        // Calculate age for each user and process additional fields
        foreach ($users as &$u) {
            // Calculate age
            $u['age'] = $u['birthdate'] ? (date_diff(date_create($u['birthdate']), date_create('today'))->y) : '';
            
            // Process position information
            $position = isset($u['position']) ? (int)$u['position'] : 5;
            $u['position_int'] = $position;
            $u['is_chairperson'] = ($position == 1);
            
            // Set position text
            switch($position) {
                case 1:
                    $u['position_text'] = 'SK Chairperson';
                    break;
                case 2:
                    $u['position_text'] = 'SK Councilor';
                    break;
                case 3:
                    $u['position_text'] = 'Secretary';
                    break;
                case 4:
                    $u['position_text'] = 'Treasurer';
                    break;
                default:
                    $u['position_text'] = 'KK Member';
            }
            
            // Process sex
            $u['sex_text'] = $u['sex'] == '1' ? 'Male' : ($u['sex'] == '2' ? 'Female' : '');
            
            // Process barangay name
            $u['barangay_name'] = BarangayHelper::getBarangayName($u['barangay']);
            
            // Format full name
            $u['full_name'] = $u['last_name'] . ', ' . $u['first_name'] . ' ' . $u['middle_name'];
            
            // Set checkbox attributes
            $u['checkbox_disabled'] = $u['is_chairperson'] ? 'disabled' : '';
            $u['checkbox_title'] = $u['is_chairperson'] ? 'title="SK Chairperson position cannot be changed"' : '';
        }
        unset($u);
        
        $data['user_list'] = $users;
        $data['sk_barangay'] = $skBarangay;
        $data['barangay_name'] = $barangayName;
        
        // Get current user's database ID from their permanent user_id
        $currentUserPermanentId = $session->get('user_id');
        $currentUser = $userModel->where('user_id', $currentUserPermanentId)->first();
        $data['current_user_id'] = $currentUser ? $currentUser['id'] : null; // Add current user database ID
        
        // Add mappings for JavaScript
        $data['barangay_map'] = BarangayHelper::getBarangayMap();
        $data['zone_map'] = ZoneHelper::getZoneMap();
        
    // Centralized demographic maps for consistent labels in JS
    $data['field_mappings'] = DemographicsHelper::allMapsForJs();
        
        return 
            $this->loadView('K-NECT/SK/template/header') .
            $this->loadView('K-NECT/SK/template/sidebar') .
            $this->loadView('K-NECT/SK/sk_official', $data);
    }

    public function getKKListData()
    {
        try {
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            
            // Get filter parameters
            // Force only verified (Accepted) users for download list
            $statusFilter = 'Accepted';
            $zoneFilter = $this->request->getGet('zone') ?? '';
            
            $userModel = new UserModel();
            
            // Build query with comprehensive joins
            $query = $userModel
                ->select('
                    user.id, 
                    user.user_id, 
                    user.status, 
                    user.position, 
                    user.last_name, 
                    user.first_name, 
                    user.middle_name, 
                    user.suffix,
                    user.birthdate, 
                    user.sex,
                    user.email,
                    user.phone_number,
                    address.barangay, 
                    address.zone_purok,
                    user_ext_info.civil_status,
                    user_ext_info.youth_classification,
                    user_ext_info.age_group,
                    user_ext_info.work_status,
                    user_ext_info.educational_background,
                    user_ext_info.sk_voter,
                    user_ext_info.sk_election,
                    user_ext_info.national_voter,
                    user_ext_info.kk_assembly,
                    user_ext_info.how_many_times
                ')
                ->join('address', 'address.user_id = user.id', 'left')
                ->join('user_ext_info', 'user_ext_info.user_id = user.id', 'left');
            
            // Filter by SK's barangay
            if ($skBarangay) {
                $query->where('address.barangay', $skBarangay);
            }
            
            // Apply status filter: only Accepted
            $query->where('user.status', 2);
            
            // Apply zone filter
            if (!empty($zoneFilter)) {
                $query->where('address.zone_purok', $zoneFilter);
            }
            
            $users = $query->findAll();

            // Process user data
            foreach ($users as &$u) {
                // Calculate age
                if ($u['birthdate']) {
                    $u['age'] = date_diff(date_create($u['birthdate']), date_create('today'))->y;
                } else {
                    $u['age'] = '';
                }
                
                // Format full name
                $fullName = esc($u['last_name'] ?? '');
                if (!empty($u['first_name'])) {
                    $fullName .= ', ' . esc($u['first_name']);
                }
                if (!empty($u['middle_name'])) {
                    $fullName .= ' ' . esc($u['middle_name']);
                }
                if (!empty($u['suffix'])) {
                    $fullName .= ', ' . esc($u['suffix']);
                }
                $u['full_name'] = $fullName;
                
                // Format barangay name
                $u['barangay_name'] = BarangayHelper::getBarangayName($u['barangay'] ?? '');
                
                // Format birthdate for display
                if ($u['birthdate']) {
                    $u['birthdate'] = date('m/d/Y', strtotime($u['birthdate']));
                }
                
                // Ensure all fields have default values to prevent undefined errors
                $u['civil_status'] = $u['civil_status'] ?? '';
                $u['youth_classification'] = $u['youth_classification'] ?? '';
                $u['age_group'] = $u['age_group'] ?? '';
                $u['work_status'] = $u['work_status'] ?? '';
                $u['educational_background'] = $u['educational_background'] ?? '';
                $u['sk_voter'] = $u['sk_voter'] ?? 0;
                $u['sk_election'] = $u['sk_election'] ?? 0;
                $u['kk_assembly'] = $u['kk_assembly'] ?? 0;
                $u['how_many_times'] = $u['how_many_times'] ?? '';
                $u['email'] = $u['email'] ?? '';
                $u['phone_number'] = $u['phone_number'] ?? '';
                $u['house_number'] = '';  // Set to empty since column doesn't exist
                $u['street'] = '';        // Set to empty since column doesn't exist  
                $u['subdivision'] = '';   // Set to empty since column doesn't exist
                $u['zone_purok'] = $u['zone_purok'] ?? '';
            }
            unset($u);

            // Extract secretary and chairperson names
            $secretaryName = '';
            $chairpersonName = '';
            foreach ($users as $user) {
                if (isset($user['position']) && (int)$user['position'] === 3) {
                    $secretaryName = trim($user['first_name'] . ' ' . $user['middle_name'] . ' ' . $user['last_name']);
                }
                if (isset($user['position']) && (int)$user['position'] === 1) {
                    $chairpersonName = trim($user['first_name'] . ' ' . $user['middle_name'] . ' ' . $user['last_name']);
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'users' => $users,
                'count' => count($users),
                'secretary_name' => $secretaryName,
                'chairman_name' => $chairpersonName,
                'filters' => [
                    'status' => $statusFilter,
                    'zone' => $zoneFilter,
                    'barangay' => $skBarangay
                ]
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in getKKListData: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'An error occurred while fetching data: ' . $e->getMessage()
            ]);
        }
    }

    public function settings()
    {
        $session = session();
        $skBarangay = $session->get('sk_barangay');
        $barangayName = BarangayHelper::getBarangayName($skBarangay);
        
        $data = [
            'user_id' => $session->get('user_id'),
            'username' => $session->get('username'),
            'sk_barangay' => $skBarangay,
            'barangay_name' => $barangayName,
            'user_type' => $session->get('user_type') // Add user type for logo access control
        ];

        return
            $this->loadView('K-NECT/SK/template/header') .
            $this->loadView('K-NECT/SK/template/sidebar') .
            $this->loadView('K-NECT/SK/settings', $data);
    }

    public function userManagement()
    {
        $session = session();
        $skBarangay = $session->get('sk_barangay');
        $barangayName = BarangayHelper::getBarangayName($skBarangay);
        
        $userModel = new UserModel();
        $addressModel = new AddressModel();
        $userExtInfoModel = new UserExtInfoModel();

    // Note: Automatic DB updates for overage/inactive are disabled.
    // We'll compute overage/inactive dynamically for display only.

    // Get all verified users (status = 2, is_active = 1) - same as youthProfile pattern
        $query = $userModel
            ->select('
                user.id, user.user_id, user.first_name, user.last_name, user.middle_name, 
                user.suffix, user.email, user.phone_number, user.birthdate, user.sex, 
                user.is_active, user.last_login, user.created_at, user.status, user.user_type, user.position,
                address.barangay, address.zone_purok, address.municipality, address.province, address.region
            ')
            ->join('address', 'address.user_id = user.id', 'left')
            ->where('user.status', 2)  // Verified/approved users
            ->whereIn('user.is_active', [1, 5]) // Active or Reactivated users
            ->groupBy('user.id'); // Prevent duplicates from JOIN
        
        // Filter by SK's barangay if available - same as youthProfile
        if ($skBarangay) {
            $query->where('address.barangay', $skBarangay);
        }
        
    $users = $query->findAll();

        // Process user data with all backend logic - same as youthProfile pattern
        foreach ($users as &$u) {
            // Calculate age
            $u['age'] = $u['birthdate'] ? (date_diff(date_create($u['birthdate']), date_create('today'))->y) : '';
            
            // Format full name
            $fullName = esc($u['last_name']);
            if (!empty($u['first_name'])) {
                $fullName .= ', ' . esc($u['first_name']);
            }
            if (!empty($u['middle_name'])) {
                $fullName .= ' ' . esc($u['middle_name']);
            }
            $u['full_name'] = $fullName;
            
            // Format sex
            $u['sex_text'] = $u['sex'] == '1' ? 'Male' : ($u['sex'] == '2' ? 'Female' : '');
            
            // Format barangay name
            $u['barangay_name'] = BarangayHelper::getBarangayName($u['barangay']);
            
            // Format zone/purok
            $u['zone_display'] = isset($u['zone_purok']) && !empty($u['zone_purok']) ? esc($u['zone_purok']) : '-';
            
            // Format last login
            if (!empty($u['last_login'])) {
                $u['last_login_formatted'] = date('M j, Y g:i A', strtotime($u['last_login']));
                $lastLogin = new \DateTime($u['last_login']);
                $today = new \DateTime();
                $u['days_since_login'] = $today->diff($lastLogin)->days;
            } else {
                $u['last_login_formatted'] = 'Never';
                $u['days_since_login'] = null;
            }

            // Format created date
            if (!empty($u['created_at'])) {
                $u['created_at_formatted'] = date('M j, Y g:i A', strtotime($u['created_at']));
            }

            // Get user type text
            $userTypes = [
                1 => 'KK Member',
                2 => 'SK Official', 
                3 => 'Pederasyon Officer | SK Chairperson'
            ];
            $u['user_type_text'] = $userTypes[$u['user_type']] ?? 'Unknown';

            // Get position text for SK/Pederasyon users
            if ($u['user_type'] == 2 && !empty($u['position'])) {
                $skPositions = [
                    1 => 'Chairperson',
                    2 => 'Secretary',
                    3 => 'Treasurer',
                    4 => 'Member'
                ];
                $u['position_text'] = $skPositions[$u['position']] ?? 'Member';
            } else {
                $u['position_text'] = 'KK Member';
            }

            // Set status info
            if ($u['is_active'] == 1) {
                $u['status_reason'] = 'Active';
                $u['status_class'] = 'bg-green-100 text-green-800';
            } elseif ($u['is_active'] == 5) {
                $u['status_reason'] = 'Reactivated';
                $u['status_class'] = 'bg-blue-100 text-blue-800';
            } else {
                $u['status_reason'] = 'Unknown';
                $u['status_class'] = 'bg-gray-100 text-gray-800';
            }
            
            $u['user_json'] = htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8');
        }
        unset($u);

        // Build Overage & Inactive list dynamically (no DB writes)
        // Start from verified, not manually deactivated users
        $inactiveBaseQuery = $userModel
            ->select('
                user.id, user.user_id, user.first_name, user.last_name, user.middle_name,
                user.suffix, user.email, user.phone_number, user.birthdate, user.sex,
                user.is_active, user.last_login, user.created_at, user.status, user.user_type, user.position,
                address.barangay, address.zone_purok, address.municipality, address.province, address.region
            ')
            ->join('address', 'address.user_id = user.id', 'left')
            ->where('user.status', 2)
            ->whereIn('user.is_active', [1, 5]); // only active or reactivated candidates
        if ($skBarangay) {
            $inactiveBaseQuery->where('address.barangay', $skBarangay);
        }
        $inactiveCandidates = $inactiveBaseQuery->findAll();

        $inactiveUsers = [];
        $today = new \DateTime();
        foreach ($inactiveCandidates as $row) {
            $row['age'] = $row['birthdate'] ? (date_diff(date_create($row['birthdate']), date_create('today'))->y) : '';
            $fullName = esc($row['last_name']);
            if (!empty($row['first_name'])) { $fullName .= ', ' . esc($row['first_name']); }
            if (!empty($row['middle_name'])) { $fullName .= ' ' . esc($row['middle_name']); }
            $row['full_name'] = $fullName;
            $row['barangay_name'] = BarangayHelper::getBarangayName($row['barangay']);
            $row['zone_display'] = isset($row['zone_purok']) && !empty($row['zone_purok']) ? esc($row['zone_purok']) : '-';

            $reason = null;
            $reasonClass = 'bg-gray-100 text-gray-800';
            // Overage check
            if (!empty($row['age']) && (int)$row['age'] >= 31) {
                $reason = 'Overage (31+)';
                $reasonClass = 'bg-orange-100 text-orange-800';
            } else {
                // Inactivity check
                if (!empty($row['last_login'])) {
                    $lastLogin = new \DateTime($row['last_login']);
                    $days = $today->diff($lastLogin)->days;
                    if ($days >= 365) {
                        $reason = 'Inactive 1+ Year';
                        $reasonClass = 'bg-red-100 text-red-800';
                    }
                } elseif (!empty($row['created_at'])) {
                    $createdAt = new \DateTime($row['created_at']);
                    $days = $today->diff($createdAt)->days;
                    if ($days >= 365) {
                        $reason = 'Inactive 1+ Year';
                        $reasonClass = 'bg-red-100 text-red-800';
                    }
                }
            }

            if ($reason !== null) {
                $row['inactive_reason'] = $reason;
                $row['inactive_class'] = $reasonClass;
                $inactiveUsers[] = $row;
            }
        }

        // Build Deactivated list (is_active = 4)
        $deactivatedQuery = $userModel
            ->select('
                user.id, user.user_id, user.first_name, user.last_name, user.middle_name,
                user.suffix, user.email, user.phone_number, user.birthdate, user.sex,
                user.is_active, user.last_login, user.created_at, user.updated_at, user.status, user.user_type, user.position,
                address.barangay, address.zone_purok, address.municipality, address.province, address.region
            ')
            ->join('address', 'address.user_id = user.id', 'left')
            ->whereIn('user.is_active', [2, 3, 4]);
        if ($skBarangay) {
            $deactivatedQuery->where('address.barangay', $skBarangay);
        }
        $deactivatedUsers = $deactivatedQuery->findAll();

        foreach ($deactivatedUsers as &$u) {
            $u['age'] = $u['birthdate'] ? (date_diff(date_create($u['birthdate']), date_create('today'))->y) : '';
            $fullName = esc($u['last_name']);
            if (!empty($u['first_name'])) { $fullName .= ', ' . esc($u['first_name']); }
            if (!empty($u['middle_name'])) { $fullName .= ' ' . esc($u['middle_name']); }
            $u['full_name'] = $fullName;
            $u['barangay_name'] = BarangayHelper::getBarangayName($u['barangay']);
            $u['zone_display'] = isset($u['zone_purok']) && !empty($u['zone_purok']) ? esc($u['zone_purok']) : '-';
            // Map reason based on is_active
            if ((int)$u['is_active'] === 2) {
                $u['deactivation_reason'] = 'Overage (31+)';
            } elseif ((int)$u['is_active'] === 3) {
                $u['deactivation_reason'] = 'Inactive 1+ Year';
            } else {
                $u['deactivation_reason'] = 'Manual Deactivation';
            }
            $u['deactivated_date'] = !empty($u['updated_at']) ? date('M j, Y g:i A', strtotime($u['updated_at'])) : '';
        }
        unset($u);

        // Check if zone data exists - same as youthProfile
        $hasZoneData = false;
        foreach ($users as $user) {
            if (isset($user['zone_purok']) && !empty($user['zone_purok'])) {
                $hasZoneData = true;
                break;
            }
        }

        $data = [
            'user_id' => $session->get('user_id'),
            'username' => $session->get('username'),
            'sk_barangay' => $skBarangay,
            'barangay_name' => $barangayName,
            'user_list' => $users,
            'inactive_list' => $inactiveUsers,
            'deactivated_list' => $deactivatedUsers,
            'has_zone_data' => $hasZoneData,
            'total_users' => count($users),
            'verified_count' => count($users),
            'inactive_count' => count($inactiveUsers),
            'deactivated_count' => count($deactivatedUsers),
            // Add helper maps for JavaScript - same as youthProfile
            'barangay_map' => BarangayHelper::getBarangayName($skBarangay),
            'zone_map' => ZoneHelper::getZoneMap(),
            // Centralized demographic maps for consistent labels in JS
            'field_mappings' => DemographicsHelper::allMapsForJs()
        ];

        return
            $this->loadView('K-NECT/SK/template/header') .
            $this->loadView('K-NECT/SK/template/sidebar') .
            $this->loadView('K-NECT/SK/user_management', $data);
    }

    /**
     * Automatically update user is_active based on age/inactivity.
     * - Age >= 31 -> is_active = 2 (Overage)
     * - Inactive >= 365 days (by last_login or created_at if never logged in) -> is_active = 3
     * Skips users with is_active in (4=Manual Deactivated, 5=Reactivated)
     * Optionally scope by barangay.
     */
    private function autoUpdateUserActiveStatuses($barangayId = null): void
    {
        try {
            $userModel = new UserModel();

            // Active, Verified users only
            $query = $userModel
                ->select('user.*')
                ->join('address', 'address.user_id = user.id', 'left')
                ->where('user.status', 2)
                ->where('user.is_active', 1);

            if (!empty($barangayId)) {
                $query->where('address.barangay', $barangayId);
            }

            $users = $query->findAll();

            $today = new \DateTime();
            $updates = [];

            foreach ($users as $u) {
                $newActive = null;

                // Age-based check
                if (!empty($u['birthdate'])) {
                    $birth = new \DateTime($u['birthdate']);
                    $age = $today->diff($birth)->y;
                    if ($age >= 31) {
                        $newActive = 2; // Overage
                    }
                }

                // Inactivity-based check (only if not already flagged by age)
                if ($newActive === null) {
                    if (!empty($u['last_login'])) {
                        $lastLogin = new \DateTime($u['last_login']);
                        $days = $today->diff($lastLogin)->days;
                        if ($days >= 365) {
                            $newActive = 3; // Inactive 1+ year
                        }
                    } else if (!empty($u['created_at'])) {
                        $createdAt = new \DateTime($u['created_at']);
                        $days = $today->diff($createdAt)->days;
                        if ($days >= 365) {
                            $newActive = 3; // Never logged in for 1+ year
                        }
                    }
                }

                if ($newActive !== null) {
                    $updates[] = [
                        'id' => $u['id'],
                        'is_active' => $newActive
                    ];
                }
            }

            if (!empty($updates)) {
                // Batch update
                $userModel->updateBatch($updates, 'id');
                log_message('info', 'autoUpdateUserActiveStatuses: Updated ' . count($updates) . ' users.');
            }
        } catch (\Throwable $e) {
            log_message('error', 'autoUpdateUserActiveStatuses error: ' . $e->getMessage());
        }
    }

    public function liveAttendance($eventId)
    {
        $session = session();
        $skBarangay = $session->get('sk_barangay');
        $barangayName = BarangayHelper::getBarangayName($skBarangay);
        
        // Get event details
        $eventModel = new EventModel();
        $event = $eventModel->find($eventId);
        
        if (!$event) {
            return redirect()->to('sk/attendance')->with('error', 'Event not found');
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
            'sk_barangay' => $skBarangay,
            'barangay_name' => $barangayName,
            'event' => $event,
            'attendance_settings' => $attendanceSettings,
            'barangay_logo' => $barangayLogo,
            'sk_logo' => $skLogo
        ];

        return view('K-NECT/SK/live_attendance', $data);
    }

    public function generateKKListPDF()
    {
        try {
            log_message('info', 'Starting KK List PDF generation...');
            
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $barangayName = BarangayHelper::getBarangayName($skBarangay);
            
            if (!$skBarangay) {
                return $this->response->setJSON(['success' => false, 'message' => 'Barangay not found']);
            }

            $userModel = new UserModel();
            $userExtInfoModel = new UserExtInfoModel();

            // Get all users from the barangay with complete information
            $users = $userModel
                ->select('user.id, user.user_id, user.status, user.position, 
                         user.last_name, user.first_name, user.middle_name, user.suffix,
                         user.birthdate, user.sex, user.email, user.phone_number,
                         address.barangay, address.zone_purok,
                         user_ext_info.civil_status, user_ext_info.youth_classification, user_ext_info.age_group,
                         user_ext_info.work_status, user_ext_info.educational_background, user_ext_info.sk_voter, 
                         user_ext_info.sk_election, user_ext_info.kk_assembly, user_ext_info.how_many_times')
                ->join('address', 'address.user_id = user.id', 'left')
                ->join('user_ext_info', 'user_ext_info.user_id = user.id', 'left')
                ->where('address.barangay', $skBarangay)
                ->where('user.status', 2) // Only accepted users
                ->orderBy('user.last_name', 'ASC')
                ->findAll();

            if (empty($users)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No accepted KK members found for this barangay']);
            }

            // Process user data to ensure all fields are available
            foreach ($users as &$user) {
                $user['house_number'] = '';
                $user['street'] = '';
                $user['subdivision'] = '';
                $user['zone_purok'] = $user['zone_purok'] ?? '';
            }
            unset($user);

            // Get logos for the PDF
            $logos = $this->getLogosForDocument();

            // Generate PDF document using DomPDF
            $outputPdfFile = $this->generateKKListPDFDocument($users, $barangayName, $logos);
            
            if ($outputPdfFile && file_exists($outputPdfFile)) {
                // Return the PDF file for download
                $fileName = basename($outputPdfFile);
                log_message('info', 'KK List PDF document ready for download: ' . $fileName);
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'KK List PDF document generated successfully',
                    'download_url' => base_url('uploads/generated/' . $fileName),
                    'user_count' => count($users)
                ]);
            } else {
                log_message('error', 'PDF document file not created or does not exist');
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Error generating PDF document - file not created'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in generateKKListPDF: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    public function generateKKListWord()
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
            log_message('info', 'Starting KK List Word generation...');
            
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $barangayName = BarangayHelper::getBarangayName($skBarangay);
            
            if (!$skBarangay) {
                return $this->response->setJSON(['success' => false, 'message' => 'Barangay not found']);
            }

            $userModel = new UserModel();
            $userExtInfoModel = new UserExtInfoModel();

            // Get all users from the barangay with complete information
            $users = $userModel
                ->select('user.id, user.user_id, user.status, user.position, 
                         user.last_name, user.first_name, user.middle_name, user.suffix,
                         user.birthdate, user.sex, user.email, user.phone_number,
                         address.barangay, address.zone_purok,
                         user_ext_info.civil_status, user_ext_info.youth_classification, user_ext_info.age_group,
                         user_ext_info.work_status, user_ext_info.educational_background, user_ext_info.sk_voter, 
                         user_ext_info.sk_election, user_ext_info.kk_assembly, user_ext_info.how_many_times')
                ->join('address', 'address.user_id = user.id', 'left')
                ->join('user_ext_info', 'user_ext_info.user_id = user.id', 'left')
                ->where('address.barangay', $skBarangay)
                ->where('user.status', 2) // Only accepted users
                ->orderBy('user.last_name', 'ASC')
                ->findAll();

            if (empty($users)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No accepted KK members found for this barangay']);
            }

            // Process user data to ensure all fields are available
            foreach ($users as &$user) {
                $user['house_number'] = '';
                $user['street'] = '';
                $user['subdivision'] = '';
                $user['zone_purok'] = $user['zone_purok'] ?? '';
            }
            unset($user);

            // Get logos for the Word document
            $logos = $this->getLogosForDocument();

            // Generate Word document
            $outputWordFile = $this->generateKKListWordDocument($users, $barangayName, $logos);
            
            if ($outputWordFile && file_exists($outputWordFile)) {
                // Return the Word file for download
                $fileName = basename($outputWordFile);
                log_message('info', 'KK List Word document ready for download: ' . $fileName);
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'KK List Word document generated successfully',
                    'download_url' => base_url('uploads/generated/' . $fileName),
                    'user_count' => count($users)
                ]);
            } else {
                log_message('error', 'Word document file not created or does not exist');
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Error generating Word document - file not created'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in generateKKListWord: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    public function generateKKListExcel()
    {
        try {
            log_message('info', 'Starting KK List Excel generation...');
            
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $barangayName = BarangayHelper::getBarangayName($skBarangay);
            
            if (!$skBarangay) {
                return $this->response->setJSON(['success' => false, 'message' => 'Barangay not found']);
            }

            $userModel = new UserModel();
            $userExtInfoModel = new UserExtInfoModel();

            // Get all users from the barangay with complete information
            $users = $userModel
                ->select('user.id, user.user_id, user.status, user.position, 
                         user.last_name, user.first_name, user.middle_name, user.suffix,
                         user.birthdate, user.sex, user.email, user.phone_number,
                         address.barangay, address.zone_purok,
                         user_ext_info.civil_status, user_ext_info.youth_classification, user_ext_info.age_group,
                         user_ext_info.work_status, user_ext_info.educational_background, user_ext_info.sk_voter, 
                         user_ext_info.sk_election, user_ext_info.kk_assembly, user_ext_info.how_many_times')
                ->join('address', 'address.user_id = user.id', 'left')
                ->join('user_ext_info', 'user_ext_info.user_id = user.id', 'left')
                ->where('address.barangay', $skBarangay)
                ->where('user.status', 2) // Only accepted users
                ->orderBy('user.last_name', 'ASC')
                ->findAll();

            if (empty($users)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No accepted KK members found for this barangay']);
            }

            // Process user data to ensure all fields are available
            foreach ($users as &$user) {
                $user['house_number'] = '';
                $user['street'] = '';
                $user['subdivision'] = '';
                $user['zone_purok'] = $user['zone_purok'] ?? '';
            }
            unset($user);

            // Generate Excel document
            $outputExcelFile = $this->generateKKListExcelDocument($users, $barangayName);
            
            if ($outputExcelFile && file_exists($outputExcelFile)) {
                // Return the Excel file for download
                $fileName = basename($outputExcelFile);
                log_message('info', 'KK List Excel document ready for download: ' . $fileName);
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'KK List Excel document generated successfully',
                    'download_url' => base_url('uploads/generated/' . $fileName),
                    'user_count' => count($users)
                ]);
            } else {
                log_message('error', 'Excel document file not created or does not exist');
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Error generating Excel document - file not created'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in generateKKListExcel: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    private function getLogoBase64($logoPath)
    {
        if (!$logoPath) {
            return null;
        }
        
        $fullPath = FCPATH . $logoPath;
        if (!file_exists($fullPath)) {
            return null;
        }
        
        $imageData = file_get_contents($fullPath);
        if ($imageData === false) {
            return null;
        }
        
        $imageInfo = getimagesize($fullPath);
        if ($imageInfo === false) {
            return null;
        }
        
        $mimeType = $imageInfo['mime'];
        $base64 = base64_encode($imageData);
        
        return "data:{$mimeType};base64,{$base64}";
    }

    private function generateKKListHTML($users, $barangayName, $logos = [])
    {
        // Extract secretary and chairperson names
        $secretaryName = '';
        $chairpersonName = '';
        foreach ($users as $user) {
            if (isset($user['position']) && (int)$user['position'] === 3) {
                $secretaryName = esc($user['first_name'] . ' ' . $user['middle_name'] . ' ' . $user['last_name']);
            }
            if (isset($user['position']) && (int)$user['position'] === 1) {
                $chairpersonName = esc($user['first_name'] . ' ' . $user['middle_name'] . ' ' . $user['last_name']);
            }
        }
        
        // Process logos for HTML embedding
        $barangayLogoHtml = '';
        $irigaLogoHtml = '';
        
        if (isset($logos['barangay']) || isset($logos['sk'])) {
            $logoData = $logos['barangay'] ?? $logos['sk'];
            $logoBase64 = $this->getLogoBase64($logoData['file_path']);
            if ($logoBase64) {
                $barangayLogoHtml = '<img src="' . $logoBase64 . '" style="width: 43.2px; height: 43.2px; object-fit: contain;">';
            } else {
                $barangayLogoHtml = '<div style="width: 43.2px; height: 43.2px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 8px;">BRGY LOGO</div>';
            }
        } else {
            $barangayLogoHtml = '<div style="width: 43.2px; height: 43.2px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 8px;">BRGY LOGO</div>';
        }
        
        if (isset($logos['iriga_city'])) {
            $logoBase64 = $this->getLogoBase64($logos['iriga_city']['file_path']);
            if ($logoBase64) {
                $irigaLogoHtml = '<img src="' . $logoBase64 . '" style="width: 43.2px; height: 43.2px; object-fit: contain;">';
            } else {
                $irigaLogoHtml = '<div style="width: 43.2px; height: 43.2px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 8px;">IRIGA LOGO</div>';
            }
        } else {
            $irigaLogoHtml = '<div style="width: 43.2px; height: 43.2px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 8px;">IRIGA LOGO</div>';
        }
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Katipunan ng Kabataan Youth Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 8pt;
            line-height: 1.2;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .header-logos {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        
        .logo {
            width: 43.2px;
            height: 43.2px;
            margin: 0 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .header h2, .header h3, .header h4 {
            margin: 2px 0;
            font-weight: normal;
        }
        
        .header h3.city {
            font-weight: bold;
        }
        
        .title {
            font-weight: bold;
            font-size: 10pt;
            margin: 16px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 6pt;
        }
        
        th, td {
            border: 1px solid black;
            padding: 2px;
            text-align: center;
            vertical-align: middle;
        }
        
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        .name-column {
            text-align: left;
            padding-left: 4px;
        }
        
        .address-column {
            text-align: left;
            padding-left: 4px;
        }
        
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: center;
            width: 100%;
        }
        
        .signatures {
            display: flex;
            justify-content: center;
            gap: 120px;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .signature-box {
            text-align: center;
        }
        
        .signature-line {
            border-bottom: 1px solid black;
            width: 200px;
            margin: 48px auto 8px auto;
        }
        
        .signature-text {
            font-size: 9pt;
            margin: 2px 0;
        }
        
        .signature-title {
            font-weight: bold;
        }
        
        @page {
            size: A4 landscape;
            margin: 15mm 10mm;
        }
        
        @media print {
            body { 
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            table { 
                page-break-inside: auto;
            }
            tr { 
                page-break-inside: avoid; 
                page-break-after: auto;
            }
            thead { 
                display: table-header-group;
            }
            tfoot { 
                display: table-footer-group;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-logos">
            <div class="logo">' . $barangayLogoHtml . '</div>
            <div>
                <h2>Republic of the Philippines</h2>
                <h3>Province of Camarines Sur</h3>
                <h3 class="city">CITY OF IRIGA</h3>
                <h4>SANGGUNIANG KABATAAN NG BARANGAY</h4>
                <h4>' . strtoupper(esc($barangayName)) . '</h4>
            </div>
            <div class="logo">' . $irigaLogoHtml . '</div>
        </div>
        <hr>
        <div class="title">KATIPUNAN NG KABATAAN YOUTH PROFILE</div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 3%">REGION</th>
                <th style="width: 4%">PROVINCE</th>
                <th style="width: 6%">CITY</th>
                <th style="width: 6%">BARANGAY</th>
                <th style="width: 8%">NAME</th>
                <th style="width: 3%">AGE</th>
                <th style="width: 6%">BIRTHDAY</th>
                <th style="width: 4%">SEX<br>M/F</th>
                <th style="width: 6%">CIVIL<br>STATUS</th>
                <th style="width: 6%">YOUTH<br>CLASSIFICATION/<br>IN/OUT/KATIPUNAN</th>
                <th style="width: 6%">YOUTH<br>AGE<br>GROUP</th>
                <th style="width: 5%">EMAIL<br>ADDRESS</th>
                <th style="width: 5%">CONTACT<br>NUMBER</th>
                <th style="width: 6%">HOME ADDRESS</th>
                <th style="width: 6%">HIGHEST<br>EDUCATIONAL<br>ATTAINMENT</th>
                <th style="width: 5%">WORK<br>STATUS</th>
                <th style="width: 6%">Registered<br>SK<br>Voter</th>
                <th style="width: 6%">Voted Last<br>SK<br>Election?</th>
                <th style="width: 6%">Attended a KK<br>assembly? Y/N</th>
                <th style="width: 5%">If yes, how<br>many<br>times?</th>
            </tr>
        </thead>
        <tbody>';
        
        foreach ($users as $user) {
            // Calculate age
            $age = $user['birthdate'] ? (date_diff(date_create($user['birthdate']), date_create('today'))->y) : '';
            
            // Format name
            $fullName = esc($user['last_name']);
            if (!empty($user['first_name'])) {
                $fullName .= ', ' . esc($user['first_name']);
            }
            if (!empty($user['middle_name'])) {
                $fullName .= ' ' . esc($user['middle_name']);
            }
            if (!empty($user['suffix'])) {
                $fullName .= ', ' . esc($user['suffix']);
            }
            
            // Format other fields
            $sex = $user['sex'] == '1' ? 'M' : ($user['sex'] == '2' ? 'F' : '');
            $birthday = $user['birthdate'] ? date('m/d/Y', strtotime($user['birthdate'])) : '';
            $civilStatus = $this->formatCivilStatus($user['civil_status']);
            $education = $this->formatEducation($user['educational_background']);
            $workStatus = $this->formatWorkStatus($user['work_status']);
            $youthClassification = $this->formatYouthClassification($user['youth_classification']);
            $youthAgeGroup = $this->formatYouthAgeGroup($user['age_group']);
            $skVoter = $user['sk_voter'] == '1' ? 'Yes' : 'No';
            $skElection = $user['sk_election'] == '1' ? 'Yes' : 'No';
            $assemblyAttendance = $user['kk_assembly'] == '1' ? 'Y' : 'N';
            $assemblyTimes = $user['kk_assembly'] == '1' ? $this->formatHowManyTimes($user['how_many_times'] ?? null) : '';
            
            // Format home address
            $homeAddress = '';
            if (!empty($user['house_number'] ?? '')) {
                $homeAddress .= $user['house_number'] . ' ';
            }
            if (!empty($user['street'] ?? '')) {
                $homeAddress .= $user['street'] . ' ';
            }
            if (!empty($user['subdivision'] ?? '')) {
                $homeAddress .= $user['subdivision'] . ' ';
            }
            if (!empty($user['zone_purok'])) {
                $homeAddress .= $user['zone_purok'];
            }
            $homeAddress = trim($homeAddress);
            
            $html .= '<tr>
                <td>V</td>
                <td>Camarines Sur</td>
                <td>Iriga City</td>
                <td>' . esc($barangayName) . '</td>
                <td class="name-column">' . $fullName . '</td>
                <td>' . $age . '</td>
                <td>' . $birthday . '</td>
                <td>' . $sex . '</td>
                <td>' . $civilStatus . '</td>
                <td>' . $youthClassification . '</td>
                <td>' . $youthAgeGroup . '</td>
                <td>' . esc($user['email']) . '</td>
                <td>' . esc($user['phone_number']) . '</td>
                <td class="address-column">' . esc($homeAddress) . '</td>
                <td>' . $education . '</td>
                <td>' . $workStatus . '</td>
                <td>' . $skVoter . '</td>
                <td>' . $skElection . '</td>
                <td>' . $assemblyAttendance . '</td>
                <td>' . $assemblyTimes . '</td>
            </tr>';
        }
        
        $html .= '</tbody>
    </table>
    
    <div class="signature-section">
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-text">Prepared by:</div>
                <div class="signature-line"></div>
                <div class="signature-text">' . ($secretaryName ?: '________________') . '</div>
                <div class="signature-text signature-title">SK Secretary</div>
            </div>
            <div class="signature-box">
                <div class="signature-text">Approved by:</div>
                <div class="signature-line"></div>
                <div class="signature-text">' . ($chairpersonName ?: '________________') . '</div>
                <div class="signature-text signature-title">SK Chairperson</div>
            </div>
        </div>
    </div>
</body>
</html>';
        
        return $html;
    }

    private function generateKKListWordDocument($users, $barangayName, $logos = [])
    {
        try {
            log_message('info', 'Starting Word document creation...');
            
            // Ensure Composer autoloader is available (mirrors Excel/PDF methods)
            if (!class_exists('PhpOffice\\PhpWord\\PhpWord')) {
                $autoload = FCPATH . '../vendor/autoload.php';
                if (is_file($autoload)) {
                    require_once $autoload;
                }
            }
            
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            log_message('info', 'PHPWord instance created successfully');
            // Remove default space after paragraphs in Word
            $phpWord->setDefaultParagraphStyle([
                'spaceAfter' => 0,
                'spacing' => 0,
            ]);
        
        // Set document properties
        $properties = $phpWord->getDocInfo();
        $properties->setCreator('K-NECT System');
        $properties->setCompany('Sangguniang Kabataan ng ' . $barangayName);
        $properties->setTitle('Katipunan ng Kabataan Youth Profile');
        $properties->setDescription('Youth profile list generated from K-NECT System');
        $properties->setCategory('Government Document');
        $properties->setSubject('KK Youth Profile');
        
        // Add section - Legal landscape with narrow 0.5in margins
        $section = $phpWord->addSection([
            'orientation' => 'landscape',
            'pageSizeW' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(14.0),
            'pageSizeH' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(8.5),
            'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.5),
            'marginRight' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.5),
            'marginTop' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.5),
            'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.5),
        ]);
        
        // Header styles
        $headerStyle = ['name' => 'Arial', 'size' => 10, 'bold' => false];
        $titleStyle = ['name' => 'Arial', 'size' => 11, 'bold' => true];
        $tableHeaderStyle = ['name' => 'Arial', 'size' => 6, 'bold' => true];
        $tableCellStyle = ['name' => 'Arial', 'size' => 6];
        
        // Create header section with logos
        $headerTable = $section->addTable([
            'borderSize' => 0,
            'borderColor' => 'FFFFFF',
            'width' => 100 * 50,
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
        ]);
        $headerTable->addRow();
        
        // Left logo cell
        $leftCell = $headerTable->addCell(2000, ['valign' => 'center']);
        if (isset($logos['barangay']) || isset($logos['sk'])) {
            $logoData = $logos['barangay'] ?? $logos['sk'];
            $logoType = isset($logos['barangay']) ? 'barangay' : 'sk';
            $logoPath = FCPATH . $logoData['file_path'];
            log_message('info', "Attempting to add {$logoType} logo: {$logoPath}");
            
            if (file_exists($logoPath)) {
                try {
                    $leftCell->addImage($logoPath, [
                        'width' => 50.4,  // 0.70 inches = 50.4 points
                        'height' => 50.4,
                        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
                    ]);
                    log_message('info', "Successfully added {$logoType} logo to Word document");
                } catch (\Exception $e) {
                    log_message('error', "Failed to add {$logoType} logo to Word document: " . $e->getMessage());
                    $leftCell->addText('BRGY LOGO', $headerStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                }
            } else {
                log_message('warning', "Logo file does not exist: {$logoPath}");
                $leftCell->addText('BRGY LOGO', $headerStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            }
        } else {
            log_message('info', 'No barangay or SK logo available, using placeholder');
            $leftCell->addText('BRGY LOGO', $headerStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        }
        
        // Center text cell
        $centerCell = $headerTable->addCell(6000, ['valign' => 'center']);
        $centerCell->addText('Republic of the Philippines', $headerStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
        $centerCell->addText('Province of Camarines Sur', $headerStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
        $centerCell->addText('CITY OF IRIGA', ['name' => 'Arial', 'size' => 10, 'bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
    $centerCell->addText('SANGGUNIANG KABATAAN NG', ['name' => 'Arial', 'size' => 10, 'bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
    $centerCell->addText('BARANGAY ' . strtoupper($barangayName), ['name' => 'Arial', 'size' => 10, 'bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
        
        // Right logo cell
        $rightCell = $headerTable->addCell(2000, ['valign' => 'center']);
        if (isset($logos['iriga_city'])) {
            $logoPath = FCPATH . $logos['iriga_city']['file_path'];
            log_message('info', "Attempting to add Iriga City logo: {$logoPath}");
            
            if (file_exists($logoPath)) {
                try {
                    $rightCell->addImage($logoPath, [
                        'width' => 50.4,  // 0.70 inches = 50.4 points
                        'height' => 50.4,
                        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
                    ]);
                    log_message('info', "Successfully added Iriga City logo to Word document");
                } catch (\Exception $e) {
                    log_message('error', "Failed to add Iriga City logo to Word document: " . $e->getMessage());
                    $rightCell->addText('IRIGA LOGO', $headerStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                }
            } else {
                log_message('warning', "Iriga City logo file does not exist: {$logoPath}");
                $rightCell->addText('IRIGA LOGO', $headerStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            }
        } else {
            log_message('info', 'No Iriga City logo available, using placeholder');
            $rightCell->addText('IRIGA LOGO', $headerStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        }
        
        // Add horizontal line
        $section->addTextBreak();
        
        // Add title
        $section->addText('KATIPUNAN NG KABATAAN YOUTH PROFILE', $titleStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
        $section->addTextBreak();
        
    // Create data table with center alignment
        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 20,
            'width' => 100 * 50,
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
        ]);
    // Common styles
    $cellVAlignCenter = ['valign' => 'center'];
    $paraCenter = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER];
        
        // Add table header
    $table->addRow();
    $table->addCell(600, $cellVAlignCenter)->addText('REGION', $tableHeaderStyle, $paraCenter);
    $table->addCell(800, $cellVAlignCenter)->addText('PROVINCE', $tableHeaderStyle, $paraCenter);
    $table->addCell(1000, $cellVAlignCenter)->addText('CITY', $tableHeaderStyle, $paraCenter);
    $table->addCell(800, $cellVAlignCenter)->addText('BARANGAY', $tableHeaderStyle, $paraCenter);
    $table->addCell(1500, $cellVAlignCenter)->addText('FAMILY NAME, FIRST NAME, MIDDLE NAME', $tableHeaderStyle, $paraCenter);
    $table->addCell(400, $cellVAlignCenter)->addText('AGE', $tableHeaderStyle, $paraCenter);
    $table->addCell(800, $cellVAlignCenter)->addText('BIRTHDAY', $tableHeaderStyle, $paraCenter);
    $table->addCell(400, $cellVAlignCenter)->addText('SEX', $tableHeaderStyle, $paraCenter);
    $table->addCell(800, $cellVAlignCenter)->addText('CIVIL STATUS', $tableHeaderStyle, $paraCenter);
    $table->addCell(1000, $cellVAlignCenter)->addText('YOUTH CLASSIFICATION', $tableHeaderStyle, $paraCenter);
    $table->addCell(800, $cellVAlignCenter)->addText('AGE GROUP', $tableHeaderStyle, $paraCenter);
    $table->addCell(1200, $cellVAlignCenter)->addText('EMAIL ADDRESS', $tableHeaderStyle, $paraCenter);
    $table->addCell(800, $cellVAlignCenter)->addText('CONTACT NO.', $tableHeaderStyle, $paraCenter);
    $table->addCell(1200, $cellVAlignCenter)->addText('HOME ADDRESS', $tableHeaderStyle, $paraCenter);
    $table->addCell(800, $cellVAlignCenter)->addText('WORK STATUS', $tableHeaderStyle, $paraCenter);
    $table->addCell(1000, $cellVAlignCenter)->addText('EDUCATIONAL BACKGROUND', $tableHeaderStyle, $paraCenter);
    $table->addCell(600, $cellVAlignCenter)->addText('SK VOTER', $tableHeaderStyle, $paraCenter);
    $table->addCell(800, $cellVAlignCenter)->addText('SK ELECTION', $tableHeaderStyle, $paraCenter);
    $table->addCell(800, $cellVAlignCenter)->addText('KK ASSEMBLY', $tableHeaderStyle, $paraCenter);
    $table->addCell(800, $cellVAlignCenter)->addText('HOW MANY TIMES', $tableHeaderStyle, $paraCenter);
        
        // Add data rows
        foreach ($users as $user) {
            // Format data similar to PDF version
            $age = $user['birthdate'] ? (date_diff(date_create($user['birthdate']), date_create('today'))->y) : '';
            $birthday = $user['birthdate'] ? date('M d, Y', strtotime($user['birthdate'])) : '';
            $sex = $user['sex'] == '1' ? 'Male' : ($user['sex'] == '2' ? 'Female' : '');
            
            $fullName = esc($user['last_name']);
            if (!empty($user['first_name'])) {
                $fullName .= ', ' . esc($user['first_name']);
            }
            if (!empty($user['middle_name'])) {
                $fullName .= ' ' . esc($user['middle_name']);
            }
            
            $civilStatus = $this->formatCivilStatus($user['civil_status']);
            $youthClassification = $this->formatYouthClassification($user['youth_classification']);
            $ageGroup = $this->formatYouthAgeGroup($user['age_group']);
            $workStatus = $this->formatWorkStatus($user['work_status']);
            $education = $this->formatEducation($user['educational_background']);
            $skVoter = $user['sk_voter'] == '1' ? 'Yes' : 'No';
            $skElection = $user['sk_election'] == '1' ? 'Yes' : 'No';
            $kkAssembly = $user['kk_assembly'] == '1' ? 'Yes' : 'No';
            $howManyTimes = $user['kk_assembly'] == '1' ? $this->formatHowManyTimes($user['how_many_times'] ?? null) : '';
            
            $homeAddress = '';
            if (!empty($user['house_number'] ?? '')) {
                $homeAddress .= esc($user['house_number']) . ' ';
            }
            if (!empty($user['street'] ?? '')) {
                $homeAddress .= esc($user['street']) . ' ';
            }
            if (!empty($user['subdivision'] ?? '')) {
                $homeAddress .= esc($user['subdivision']) . ' ';
            }
            if (!empty($user['zone_purok'])) {
                $homeAddress .= esc($user['zone_purok']);
            }
            $homeAddress = trim($homeAddress);
            
            $table->addRow();
            $table->addCell(600, $cellVAlignCenter)->addText('V', $tableCellStyle, $paraCenter);
            $table->addCell(800, $cellVAlignCenter)->addText('Camarines Sur', $tableCellStyle, $paraCenter);
            $table->addCell(1000, $cellVAlignCenter)->addText('Iriga City', $tableCellStyle, $paraCenter);
            $table->addCell(800, $cellVAlignCenter)->addText($barangayName, $tableCellStyle, $paraCenter);
            $table->addCell(1500, $cellVAlignCenter)->addText($fullName, $tableCellStyle, $paraCenter);
            $table->addCell(400, $cellVAlignCenter)->addText($age, $tableCellStyle, $paraCenter);
            $table->addCell(800, $cellVAlignCenter)->addText($birthday, $tableCellStyle, $paraCenter);
            $table->addCell(400, $cellVAlignCenter)->addText($sex, $tableCellStyle, $paraCenter);
            $table->addCell(800, $cellVAlignCenter)->addText($civilStatus, $tableCellStyle, $paraCenter);
            $table->addCell(1000, $cellVAlignCenter)->addText($youthClassification, $tableCellStyle, $paraCenter);
            $table->addCell(800, $cellVAlignCenter)->addText($ageGroup, $tableCellStyle, $paraCenter);
            $table->addCell(1200, $cellVAlignCenter)->addText($user['email'] ?? '', $tableCellStyle, $paraCenter);
            $table->addCell(800, $cellVAlignCenter)->addText($user['phone_number'] ?? '', $tableCellStyle, $paraCenter);
            $table->addCell(1200, $cellVAlignCenter)->addText($homeAddress, $tableCellStyle, $paraCenter);
            $table->addCell(800, $cellVAlignCenter)->addText($workStatus, $tableCellStyle, $paraCenter);
            $table->addCell(1000, $cellVAlignCenter)->addText($education, $tableCellStyle, $paraCenter);
            $table->addCell(600, $cellVAlignCenter)->addText($skVoter, $tableCellStyle, $paraCenter);
            $table->addCell(800, $cellVAlignCenter)->addText($skElection, $tableCellStyle, $paraCenter);
            $table->addCell(800, $cellVAlignCenter)->addText($kkAssembly, $tableCellStyle, $paraCenter);
            $table->addCell(800, $cellVAlignCenter)->addText($howManyTimes, $tableCellStyle, $paraCenter);
        }
        
        // Add signature section
        $secretaryName = '';
        $chairpersonName = '';
        foreach ($users as $user) {
            if (isset($user['position']) && (int)$user['position'] === 3) {
                $secretaryName = $user['first_name'] . ' ' . $user['middle_name'] . ' ' . $user['last_name'];
            }
            if (isset($user['position']) && (int)$user['position'] === 1) {
                $chairpersonName = $user['first_name'] . ' ' . $user['middle_name'] . ' ' . $user['last_name'];
            }
        }
        $section->addTextBreak(2);
        // Add a table for signatures with no border and extra space between cells
        $table = $section->addTable([
            'borderSize' => 0,
            'borderColor' => 'FFFFFF',
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            'cellMargin' => 80, // add more margin for spacing
        ]);
        // Increase the gap between the two signature boxes by adding an empty cell in between
        $table->addRow(null, ['tblHeader' => false, 'cantSplit' => true, 'height' => 800]);
        $cell1 = $table->addCell(4000, ['borderSize' => 0, 'borderColor' => 'FFFFFF', 'valign' => 'top', 'marginRight' => 400]);
        $table->addCell(1000, ['borderSize' => 0, 'borderColor' => 'FFFFFF']); // Spacer cell for more space between
        $cell2 = $table->addCell(4000, ['borderSize' => 0, 'borderColor' => 'FFFFFF', 'valign' => 'top', 'marginLeft' => 400]);
        $cell1->addText('Prepared by:', ['bold' => true, 'size' => 8], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
        $cell1->addText('', [], ['space' => array('after' => 200)]); // Extra space after label
        $cell2->addText('Approved by:', ['bold' => true, 'size' => 8], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
        $cell2->addText('', [], ['space' => array('after' => 200)]); // Extra space after label
        $cell1->addText('_________________________', ['size' => 8], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
        $cell2->addText('_________________________', ['size' => 8], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
        $cell1->addText($secretaryName ?: '________________', ['bold' => true, 'size' => 8], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
        $cell2->addText($chairpersonName ?: '________________', ['bold' => true, 'size' => 8], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
        $cell1->addText('SK Secretary', ['size' => 8], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
        $cell2->addText('SK Chairperson', ['size' => 8], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);

        // Save the document
        $outputDir = FCPATH . 'uploads/generated/';
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }
        
        $fileName = 'KK_List_' . str_replace(' ', '_', $barangayName) . '_' . date('Y_m_d_H_i_s') . '.docx';
        $outputFile = $outputDir . $fileName;
        
    $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    $writer->save($outputFile);
        
    log_message('info', 'Word document saved successfully: ' . $outputFile);
    return $outputFile;
        
        } catch (\Exception $e) {
            log_message('error', 'Error in generateKKListWordDocument: ' . $e->getMessage());
            throw $e;
        }
    }

    private function generateKKListExcelDocument($users, $barangayName, $logos = [])
    {
        try {
            // Ensure Composer autoloader is available for PhpSpreadsheet
            if (!class_exists('PhpOffice\\PhpSpreadsheet\\Spreadsheet')) {
                $autoload = FCPATH . '../vendor/autoload.php';
                if (is_file($autoload)) {
                    require_once $autoload;
                }
            }

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // Page setup: Legal landscape, narrow margins
            $pageSetup = $sheet->getPageSetup();
            $pageSetup->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL);
            $pageSetup->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $margins = $sheet->getPageMargins();
            $margins->setTop(0.5);
            $margins->setBottom(0.5);
            $margins->setLeft(0.5);
            $margins->setRight(0.5);

            // Keep Legal landscape as configured above; no override here

            // Add logos if available
            $currentRow = 1;
            if (!empty($logos)) {
                // Add DILG logo
                if (isset($logos['dilg']) && is_string($logos['dilg']) && file_exists($logos['dilg'])) {
                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setName('DILG Logo');
                    $drawing->setDescription('DILG Logo');
                    $drawing->setPath($logos['dilg']);
                    $drawing->setHeight(50); // 0.70 inches in points
                    $drawing->setCoordinates('A1');
                    $drawing->setWorksheet($sheet);
                }

                // Add Barangay logo (center)
                if (isset($logos['barangay']) && is_string($logos['barangay']) && file_exists($logos['barangay'])) {
                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setName('Barangay Logo');
                    $drawing->setDescription('Barangay Logo');
                    $drawing->setPath($logos['barangay']);
                    $drawing->setHeight(50);
                    $drawing->setCoordinates('I1');
                    $drawing->setWorksheet($sheet);
                }

                // Add SK logo
                if (isset($logos['sk']) && is_string($logos['sk']) && file_exists($logos['sk'])) {
                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setName('SK Logo');
                    $drawing->setDescription('SK Logo');
                    $drawing->setPath($logos['sk']);
                    $drawing->setHeight(50);
                    $drawing->setCoordinates('Q1');
                    $drawing->setWorksheet($sheet);
                }

                $currentRow = 4; // Leave space for logos
            }

            // Header text
            $sheet->setCellValue('A' . $currentRow, 'KATIPUNAN NG KABATAAN YOUTH PROFILE');
            $sheet->mergeCells('A' . $currentRow . ':T' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(16);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $currentRow++;

            $sheet->setCellValue('A' . $currentRow, "BARANGAY $barangayName");
            $sheet->mergeCells('A' . $currentRow . ':T' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $currentRow++;

            $currentRow++; // Empty row

            // Table headers (as provided by user)
            $headers = [
                'A' => 'REGION',
                'B' => 'PROVINCE',
                'C' => 'CITY',
                'D' => 'BARANGAY',
                'E' => 'NAME',
                'F' => 'AGE',
                'G' => 'BIRTHDAY',
                'H' => 'SEX M/F',
                'I' => 'CIVIL STATUS',
                'J' => 'YOUTH CLASSIFICATION/IN/OUT/KATIPUNAN',
                'K' => 'YOUTH AGE GROUP',
                'L' => 'EMAIL ADDRESS',
                'M' => 'CONTACT NUMBER',
                'N' => 'HOME ADDRESS',
                'O' => 'HIGHEST EDUCATIONAL ATTAINMENT',
                'P' => 'WORK STATUS',
                'Q' => 'Registered SK Voter',
                'R' => 'Voted Last SK Election?',
                'S' => 'Attended a KK assembly? Y/N',
                'T' => 'If yes, how many times?'
            ];

            foreach ($headers as $col => $header) {
                $sheet->setCellValue($col . $currentRow, $header);
                $sheet->getStyle($col . $currentRow)->getFont()->setBold(true);
                $sheet->getStyle($col . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }

            // Style the header row
            $sheet->getStyle('A' . $currentRow . ':T' . $currentRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $currentRow++;

            // Add user data
            foreach ($users as $user) {
                // Set static values for region, province, city
                $region = 'V';
                $province = 'Camarines Sur';
                $city = 'Iriga City';
                $barangay = $barangayName;
                $fullName = trim($user['last_name'] . ', ' . $user['first_name'] . ' '
                    . ($user['middle_name'] ? strtoupper(substr($user['middle_name'], 0, 1)) . '.' : '')
                    . ($user['suffix'] ? ' ' . $user['suffix'] : ''));
                $age = $user['birthdate'] ? (int)((time() - strtotime($user['birthdate'])) / (365.25 * 24 * 3600)) : '';
                $birthday = $user['birthdate'] ? date('m/d/Y', strtotime($user['birthdate'])) : '';
                $sex = $user['sex'] == '1' ? 'M' : ($user['sex'] == '2' ? 'F' : '');
                $civilStatus = $this->formatCivilStatus($user['civil_status']);
                $youthClassification = $this->formatYouthClassification($user['youth_classification']);
                $youthAgeGroup = $this->formatYouthAgeGroup($user['age_group']);
                $email = isset($user['email']) ? $user['email'] : '';
                $contactNumber = isset($user['phone_number']) ? $user['phone_number'] : '';
                $address = trim((($user['house_number'] ?? '') ?: '') . ' '
                    . (($user['street'] ?? '') ?: '') . ' '
                    . (($user['subdivision'] ?? '') ?: '') . ', '
                    . ($user['zone_purok'] ?: 'Zone/Purok not specified'));
                $education = $this->formatEducation($user['educational_background']);
                $workStatus = $this->formatWorkStatus($user['work_status']);
                $skVoter = $user['sk_voter'] == '1' ? 'Yes' : 'No';
                $skElection = $user['sk_election'] == '1' ? 'Yes' : 'No';
                $assemblyAttendance = $user['kk_assembly'] == '1' ? 'Y' : 'N';
                $assemblyTimes = $user['kk_assembly'] == '1' ? $this->formatHowManyTimes($user['how_many_times'] ?? null) : '';

                $sheet->setCellValue('A' . $currentRow, $region);
                $sheet->setCellValue('B' . $currentRow, $province);
                $sheet->setCellValue('C' . $currentRow, $city);
                $sheet->setCellValue('D' . $currentRow, $barangay);
                $sheet->setCellValue('E' . $currentRow, $fullName);
                $sheet->setCellValue('F' . $currentRow, $age);
                $sheet->setCellValue('G' . $currentRow, $birthday);
                $sheet->setCellValue('H' . $currentRow, $sex);
                $sheet->setCellValue('I' . $currentRow, $civilStatus);
                $sheet->setCellValue('J' . $currentRow, $youthClassification);
                $sheet->setCellValue('K' . $currentRow, $youthAgeGroup);
                $sheet->setCellValue('L' . $currentRow, $email);
                $sheet->setCellValue('M' . $currentRow, $contactNumber);
                $sheet->setCellValue('N' . $currentRow, $address);
                $sheet->setCellValue('O' . $currentRow, $education);
                $sheet->setCellValue('P' . $currentRow, $workStatus);
                $sheet->setCellValue('Q' . $currentRow, $skVoter);
                $sheet->setCellValue('R' . $currentRow, $skElection);
                $sheet->setCellValue('S' . $currentRow, $assemblyAttendance);
                $sheet->setCellValue('T' . $currentRow, $assemblyTimes);

                // Add borders to data rows
                $sheet->getStyle('A' . $currentRow . ':T' . $currentRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $currentRow++;
            }

            // Auto-size columns
            foreach (range('A', 'T') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }


            // Add signature section (secretary and chairperson close together)
            $currentRow += 2;
            $secretaryName = '';
            $chairpersonName = '';
            foreach ($users as $user) {
                if (isset($user['position']) && (int)$user['position'] === 3) {
                    $secretaryName = $user['first_name'] . ' ' . $user['middle_name'] . ' ' . $user['last_name'];
                }
                if (isset($user['position']) && (int)$user['position'] === 1) {
                    $chairpersonName = $user['first_name'] . ' ' . $user['middle_name'] . ' ' . $user['last_name'];
                }
            }

            // Secretary signature at K14, Chairman at M (currentRow)
            $sheet->setCellValue('K14', 'Prepared by:');
            $sheet->getStyle('K14')->getFont()->setBold(true);
            $sheet->setCellValue('K15', $secretaryName ?: '_______________________');
            $sheet->getStyle('K15')->getFont()->setBold(true);
            $sheet->setCellValue('K16', 'SK Secretary');
            $sheet->getStyle('K16')->getFont()->setBold(true);

            // Chairperson signature at M (currentRow)
            $sheet->setCellValue('M' . $currentRow, 'Noted by:');
            $sheet->getStyle('M' . $currentRow)->getFont()->setBold(true);
            $currentRow++;
            $sheet->setCellValue('M' . $currentRow, $chairpersonName ?: '_______________________');
            $sheet->getStyle('M' . $currentRow)->getFont()->setBold(true);
            $currentRow++;
            $sheet->setCellValue('M' . $currentRow, 'SK Chairperson');
            $sheet->getStyle('M' . $currentRow)->getFont()->setBold(true);

            // Generate filename and save
            $filename = 'KK_List_' . str_replace(' ', '_', $barangayName) . '_' . date('Y-m-d_H-i-s') . '.xlsx';
            $outputPath = FCPATH . 'uploads/generated/' . $filename;

            // Ensure the directory exists
            $dir = dirname($outputPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($outputPath);

            log_message('info', 'Excel document saved to: ' . $outputPath);
            return $outputPath;

        } catch (\Exception $e) {
            log_message('error', 'Error in generateKKListExcelDocument: ' . $e->getMessage());
            throw $e;
        }
    }

    private function generateKKListPDFDocument($users, $barangayName, $logos = [])
    {
        try {
            // Ensure Composer autoloader is available for Dompdf
            if (!class_exists('Dompdf\\Dompdf')) {
                $autoload = FCPATH . '../vendor/autoload.php';
                if (is_file($autoload)) {
                    require_once $autoload;
                }
            }
            
            // Create a DomPDF instance with proper Options
            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $options->set('fontHeightRatio', 1.1);
            $options->set('fontSubsetting', false);
            $options->set('isJavascriptEnabled', false);
            $options->set('chroot', FCPATH); // Allow access to project files for images
            $dompdf = new \Dompdf\Dompdf($options);

            // Build the HTML content
            $html = '<html><head><style>
                @page { size: legal landscape; margin: 0.5in; }
                body { font-family: Arial, sans-serif; font-size: 10px; margin: 0; padding: 0; }
                .header { text-align: center; margin-bottom: 20px; }
                .logos { display: inline-block; vertical-align: middle; }
                .logo { width: 60px; height: 60px; margin: 0 10px; }
                .title { font-size: 16px; font-weight: bold; margin: 10px 0; }
                .subtitle { font-size: 12px; margin: 5px 0; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th, td { border: 0.5px solid #000; padding: 3px; text-align: center; font-size: 8px; }
                th { background-color: #ffffff; font-weight: bold; }
                .signatures { margin-top: 40px; display: table; width: 100%; }
                .signature-box { display: table-cell; text-align: center; width: 45%; padding: 0 20px; }
                .signature-line { border-bottom: 0.5px solid #000; margin-bottom: 5px; padding-bottom: 15px; }
                </style></head><body>';

            // Header with logos
            $html .= '<div class="header">';
            
            // Add logos if available (embed as data URIs)
            if (!empty($logos['iriga_city'])) {
                $path = FCPATH . $logos['iriga_city']['file_path'];
                if (file_exists($path)) {
                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                    $mime = ($ext === 'png') ? 'image/png' : (($ext === 'gif') ? 'image/gif' : (($ext === 'webp') ? 'image/webp' : 'image/jpeg'));
                    $data = base64_encode(file_get_contents($path));
                    $html .= '<div class="logos"><img src="data:' . $mime . ';base64,' . $data . '" class="logo" /></div>';
                }
            }
            if (!empty($logos['sk'])) {
                $path = FCPATH . $logos['sk']['file_path'];
                if (file_exists($path)) {
                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                    $mime = ($ext === 'png') ? 'image/png' : (($ext === 'gif') ? 'image/gif' : (($ext === 'webp') ? 'image/webp' : 'image/jpeg'));
                    $data = base64_encode(file_get_contents($path));
                    $html .= '<div class="logos"><img src="data:' . $mime . ';base64,' . $data . '" class="logo" /></div>';
                }
            }
            
            $html .= '<div class="title">KATIPUNAN NG KABATAAN YOUTH PROFILE</div>';
            $html .= '<div class="subtitle">SANGGUNIANG KABATAAN NG</div>';
            $html .= '<div class="subtitle">BARANGAY ' . htmlspecialchars(strtoupper($barangayName)) . '</div>';
            $html .= '</div>';

            // Table
            $html .= '<table>
                <thead>
                    <tr>
                        <th style="width: 3%;">No.</th>
                        <th style="width: 12%;">Full Name</th>
                        <th style="width: 4%;">Age</th>
                        <th style="width: 8%;">Birthday</th>
                        <th style="width: 5%;">Sex</th>
                        <th style="width: 7%;">Civil Status</th>
                        <th style="width: 8%;">Youth Classification</th>
                        <th style="width: 6%;">Age Group</th>
                        <th style="width: 10%;">Email</th>
                        <th style="width: 8%;">Contact</th>
                        <th style="width: 12%;">Address</th>
                        <th style="width: 8%;">Education</th>
                        <th style="width: 6%;">Work Status</th>
                        <th style="width: 3%;">SK Voter</th>
                    </tr>
                </thead>
                <tbody>';

            $counter = 1;
            foreach ($users as $user) {
                // Format data (align keys with the selected fields in generateKKListPDF())
                $fullName = trim(($user['first_name'] ?? '') . ' ' . ($user['middle_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
                $age = !empty($user['birthdate']) ? (date_diff(date_create($user['birthdate']), date_create('today'))->y) : '';
                $birthday = !empty($user['birthdate']) ? date('M d, Y', strtotime($user['birthdate'])) : '';
                $sex = ($user['sex'] ?? '') == '1' ? 'M' : ((($user['sex'] ?? '') == '2') ? 'F' : '');
                $civilStatus = $this->formatCivilStatus($user['civil_status'] ?? '');
                $youthClassification = $this->formatYouthClassification($user['youth_classification'] ?? '');
                $youthAgeGroup = $this->formatYouthAgeGroup($user['age_group'] ?? '');
                $education = $this->formatEducation($user['educational_background'] ?? '');
                $workStatus = $this->formatWorkStatus($user['work_status'] ?? '');
                $skVoter = ($user['sk_voter'] ?? '') == '1' ? 'Yes' : 'No';

                // Build address from parts
                $addrParts = [];
                if (!empty($user['house_number'] ?? '')) { $addrParts[] = $user['house_number']; }
                if (!empty($user['street'] ?? '')) { $addrParts[] = $user['street']; }
                if (!empty($user['subdivision'] ?? '')) { $addrParts[] = $user['subdivision']; }
                if (!empty($user['zone_purok'] ?? '')) { $addrParts[] = $user['zone_purok']; }
                $address = trim(implode(' ', $addrParts));
                
                $html .= '<tr>
                    <td>' . $counter . '</td>
                    <td>' . htmlspecialchars($fullName) . '</td>
                    <td>' . $age . '</td>
                    <td>' . $birthday . '</td>
                    <td>' . $sex . '</td>
                    <td>' . $civilStatus . '</td>
                    <td>' . $youthClassification . '</td>
                    <td>' . $youthAgeGroup . '</td>
                    <td>' . htmlspecialchars($user['email'] ?? '') . '</td>
                    <td>' . htmlspecialchars($user['phone_number'] ?? '') . '</td>
                    <td>' . htmlspecialchars($address) . '</td>
                    <td>' . $education . '</td>
                    <td>' . $workStatus . '</td>
                    <td>' . $skVoter . '</td>
                </tr>';
                $counter++;
            }

            $html .= '</tbody></table>';

            // Signatures
            $secretaryName = '';
            $chairpersonName = '';
            foreach ($users as $user) {
                if (isset($user['position']) && (int)$user['position'] === 3) {
                    $secretaryName = trim($user['first_name'] . ' ' . $user['middle_name'] . ' ' . $user['last_name']);
                }
                if (isset($user['position']) && (int)$user['position'] === 1) {
                    $chairpersonName = trim($user['first_name'] . ' ' . $user['middle_name'] . ' ' . $user['last_name']);
                }
            }

            $html .= '<div class="signatures">
                <div class="signature-box">
                    <div style="margin-bottom: 5px; font-weight: bold;">Prepared by:</div>
                    <div class="signature-line"></div>
                    <div style="font-weight: bold;">' . ($secretaryName ?: '_______________________') . '</div>
                    <div>SK Secretary</div>
                </div>
                <div class="signature-box">
                    <div style="margin-bottom: 5px; font-weight: bold;">Approved by:</div>
                    <div class="signature-line"></div>
                    <div style="font-weight: bold;">' . ($chairpersonName ?: '_______________________') . '</div>
                    <div>SK Chairperson</div>
                </div>
            </div>';

            $html .= '</body></html>';

            // Load HTML and render PDF
            $dompdf->loadHtml($html);
            $dompdf->setPaper('legal', 'landscape');
            $dompdf->render();

            // Save the document
            $outputDir = FCPATH . 'uploads/generated/';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0777, true);
            }
            
            $fileName = 'KK_List_' . str_replace(' ', '_', $barangayName) . '_' . date('Y_m_d_H_i_s') . '.pdf';
            $outputFile = $outputDir . $fileName;
            
            file_put_contents($outputFile, $dompdf->output());
            
            log_message('info', 'PDF document saved successfully: ' . $outputFile);
            return $outputFile;
            
        } catch (\Exception $e) {
            log_message('error', 'Error in generateKKListPDFDocument: ' . $e->getMessage());
            throw $e;
        }
    }

    private function formatCivilStatus($status)
    {
        $statusMap = [
            '1' => 'Single',
            '2' => 'Married',
            '3' => 'Widowed',
            '4' => 'Divorced',
            '5' => 'Separated',
            '6' => 'Annulled',
            '7' => 'Live-In',
            '8' => 'Unknown'
        ];
        return $statusMap[$status] ?? '';
    }

    private function formatEducation($education)
    {
        $educationMap = [
            '1' => 'Elementary Level',
            '2' => 'Elementary Graduate',
            '3' => 'High School Level',
            '4' => 'High School Graduate',
            '5' => 'Vocational Level',
            '6' => 'College Level',
            '7' => 'College Graduate',
            '8' => 'Master Level',
            '9' => 'Master Graduate',
            '10' => 'Doctorate Level',
            '11' => 'Doctorate Graduate'
        ];
        return $educationMap[$education] ?? '';
    }

    private function formatWorkStatus($status)
    {
        $statusMap = [
            '1' => 'Employed',
            '2' => 'Unemployed',
            '3' => 'Currently looking for a Job',
            '4' => 'Not Interested in finding Job'
        ];
        return $statusMap[$status] ?? '';
    }

    private function formatYouthClassification($classification)
    {
        $classificationMap = [
            '1' => 'In School Youth',
            '2' => 'Out-of-School Youth',
            '3' => 'Working Youth',
            '4' => 'Youth with Special Needs',
            '5' => 'Person with Disability',
            '6' => 'Children in Conflict with the Law',
            '7' => 'Indigenous People'
        ];
        return $classificationMap[$classification] ?? '';
    }

    public function saveRFID()
    {
        $userId = $this->request->getPost('user_id');
        $rfidNumber = $this->request->getPost('rfid_number');

        if (!$userId || !$rfidNumber) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false, 
                'message' => 'Missing user ID or RFID number'
            ]);
        }

        $userModel = new UserModel();
        
        // Check if RFID already exists for another user
        $existingUser = $userModel->where('rfid_code', $rfidNumber)
                                  ->where('id !=', $userId)
                                  ->first();
        
        if ($existingUser) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'RFID code already assigned to another user'
            ]);
        }

        // Update the user's RFID
        $result = $userModel->update($userId, ['rfid_code' => $rfidNumber]);
        
        if ($result) {
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'RFID assigned successfully'
            ]);
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false, 
                'message' => 'Failed to assign RFID'
            ]);
        }
    }

    public function checkRFIDDuplicate()
    {
        $rfidNumber = $this->request->getPost('rfid_number');
        $currentUserId = $this->request->getPost('current_user_id');

        if (!$rfidNumber) {
            return $this->response->setJSON([
                'duplicate' => false
            ]);
        }

        $userModel = new UserModel();
        $query = $userModel->where('rfid_code', $rfidNumber);
        
        // Exclude current user if provided
        if ($currentUserId) {
            $query->where('id !=', $currentUserId);
        }
        
        $existingUser = $query->first();
        
        if ($existingUser) {
            $fullName = trim($existingUser['first_name'] . ' ' . $existingUser['middle_name'] . ' ' . $existingUser['last_name']);
            return $this->response->setJSON([
                'duplicate' => true,
                'assigned_to' => $fullName
            ]);
        }

        return $this->response->setJSON([
            'duplicate' => false
        ]);
    }

    private function formatYouthAgeGroup($ageGroup)
    {
        $ageGroupMap = [
            '1' => 'Child Youth (15-17 yrs old)',
            '2' => 'Core Youth (18-24 yrs old)',
            '3' => 'Young Adult (25-30 yrs old)'
        ];
        return $ageGroupMap[$ageGroup] ?? '';
    }

    private function formatHowManyTimes($value)
    {
        $map = [
            '1' => '1-2 times',
            '2' => '3-4 times',
            '3' => '5 or more times',
        ];
        // Accept ints or strings
        if ($value === null || $value === '' || $value === 0 || $value === '0') {
            return '';
        }
        $key = (string)$value;
        return $map[$key] ?? '';
    }

    // Attendance Report Generation Methods (following ped-officers format)
    
    public function generateAttendanceExcel()
    {
        try {
            log_message('info', 'Starting Attendance Report Excel generation...');
            
            $eventId = $this->request->getPost('event_id');
            if (!$eventId) {
                return $this->response->setJSON(['success' => false, 'message' => 'Event ID is required']);
            }

            // Get event and attendance data
            $eventModel = new EventModel();
            $eventAttendanceModel = new EventAttendanceModel();
            $attendanceModel = new AttendanceModel();
            
            $event = $eventModel->find($eventId);
            if (!$event) {
                return $this->response->setJSON(['success' => false, 'message' => 'Event not found']);
            }

            // Get attendance data with simplified format
            $attendanceData = $attendanceModel->getEventAttendanceWithUserDetails($eventId);

            if (empty($attendanceData)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No attendance data found for this event']);
            }

            // Generate Excel document
            $outputExcelFile = $this->generateAttendanceExcelDocument($event, $attendanceData);
            
            if ($outputExcelFile && file_exists($outputExcelFile)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Attendance Report Excel generated successfully',
                    'download_url' => base_url('uploads/temp/' . basename($outputExcelFile)),
                    'filename' => basename($outputExcelFile)
                ]);
            } else {
                log_message('error', 'Attendance Excel file not created or does not exist');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error generating attendance Excel - file not created'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in generateAttendanceExcel: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    private function generateAttendanceExcelDocument($event, $attendanceData)
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

            // Header text (following ped-officers format)
            $sheet->setCellValue('A' . $currentRow, 'REPUBLIC OF THE PHILIPPINES');
            $sheet->mergeCells('A' . $currentRow . ':G' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $currentRow++;

            $sheet->setCellValue('A' . $currentRow, 'PROVINCE OF CAMARINES SUR');
            $sheet->mergeCells('A' . $currentRow . ':G' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $currentRow++;

            $sheet->setCellValue('A' . $currentRow, 'CITY OF IRIGA');
            $sheet->mergeCells('A' . $currentRow . ':G' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $currentRow++;

            $sheet->setCellValue('A' . $currentRow, 'SANGGUNIANG KABATAAN');
            $sheet->mergeCells('A' . $currentRow . ':G' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(false)->setSize(10);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $currentRow++;

            $currentRow++; // Empty row

            // Title
            $sheet->setCellValue('A' . $currentRow, 'ATTENDANCE REPORT');
            $sheet->mergeCells('A' . $currentRow . ':G' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $currentRow++;

            // Event details
            $sheet->setCellValue('A' . $currentRow, 'Event: ' . $event['title']);
            $sheet->mergeCells('A' . $currentRow . ':G' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(false)->setSize(10);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $currentRow++;

            $sheet->setCellValue('A' . $currentRow, 'Date: ' . date('F j, Y', strtotime($event['start_datetime'])));
            $sheet->mergeCells('A' . $currentRow . ':G' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(false)->setSize(10);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $currentRow++;

            $currentRow++; // Empty row

            // Simplified table headers (7 columns like ped-officers)
            $headers = [
                'A' => 'No.',
                'B' => 'KK Number',
                'C' => 'Name', 
                'D' => 'Zone',
                'E' => 'Time In',
                'F' => 'Time Out',
                'G' => 'Status'
            ];

            // Add and style headers
            $headerRowNum = $currentRow;
            foreach ($headers as $col => $header) {
                $sheet->setCellValue($col . $currentRow, $header);
                $sheet->getStyle($col . $currentRow)->getFont()->setBold(true)->setSize(10);
                $sheet->getStyle($col . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle($col . $currentRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $sheet->getStyle($col . $currentRow)->getFill()->getStartColor()->setRGB('E8E8E8');
                $sheet->getStyle($col . $currentRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            }
            $currentRow++;

            // Add attendance data rows
            $rowNum = 1;
            foreach ($attendanceData as $attendance) {
                // Format full name
                $fullName = trim($attendance['last_name'] . ', ' . $attendance['first_name']);
                if (!empty($attendance['middle_name'])) {
                    $fullName .= ' ' . $attendance['middle_name'];
                }

                // Format status and time fields
                $timeIn = '-';
                $timeOut = '-';
                $status = 'Absent';
                
                // Check time-in (prioritize AM, then PM)
                if (!empty($attendance['time-in_am'])) {
                    $timeIn = date('h:i A', strtotime($attendance['time-in_am']));
                } elseif (!empty($attendance['time-in_pm'])) {
                    $timeIn = date('h:i A', strtotime($attendance['time-in_pm']));
                }
                
                // Check time-out (prioritize PM, then AM)  
                if (!empty($attendance['time-out_pm'])) {
                    $timeOut = date('h:i A', strtotime($attendance['time-out_pm']));
                } elseif (!empty($attendance['time-out_am'])) {
                    $timeOut = date('h:i A', strtotime($attendance['time-out_am']));
                }
                
                // Determine status
                if (!empty($attendance['time-in_am']) || !empty($attendance['time-in_pm'])) {
                    $status = 'Present';
                    if ((!empty($attendance['time-in_am']) && !empty($attendance['time-out_am'])) || 
                        (!empty($attendance['time-in_pm']) && !empty($attendance['time-out_pm']))) {
                        $status = 'Complete';
                    }
                }

                // Add data to Excel
                $sheet->setCellValue('A' . $currentRow, $rowNum);
                $sheet->setCellValue('B' . $currentRow, $attendance['user_id'] ?: '');
                $sheet->setCellValue('C' . $currentRow, $fullName);
                $sheet->setCellValue('D' . $currentRow, ZoneHelper::getZoneName($attendance['zone_purok']) ?: 'N/A');
                $sheet->setCellValue('E' . $currentRow, $timeIn);
                $sheet->setCellValue('F' . $currentRow, $timeOut);
                $sheet->setCellValue('G' . $currentRow, $status);

                // Style data cells
                foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G'] as $col) {
                    $sheet->getStyle($col . $currentRow)->getFont()->setSize(9);
                    $sheet->getStyle($col . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle($col . $currentRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                }

                $sheet->getRowDimension($currentRow)->setRowHeight(20);
                $currentRow++;
                $rowNum++;
            }

            // Add signatures section (following ped-officers format)
            $currentRow += 2;

            // Prepared by and Approved by section
            $sheet->mergeCells('A' . $currentRow . ':C' . $currentRow);
            $sheet->setCellValue('A' . $currentRow, 'Prepared by:');
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            $sheet->mergeCells('E' . $currentRow . ':G' . $currentRow);
            $sheet->setCellValue('E' . $currentRow, 'Approved by:');
            $sheet->getStyle('E' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            $currentRow += 3;
            
            $sheet->mergeCells('A' . $currentRow . ':C' . $currentRow);
            $sheet->setCellValue('A' . $currentRow, '________________');
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            $sheet->mergeCells('E' . $currentRow . ':G' . $currentRow);
            $sheet->setCellValue('E' . $currentRow, '________________');
            $sheet->getStyle('E' . $currentRow)->getFont()->setBold(true);
            $sheet->getStyle('E' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            $currentRow++;
            
            $sheet->mergeCells('A' . $currentRow . ':C' . $currentRow);
            $sheet->setCellValue('A' . $currentRow, 'SK Secretary');
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            $sheet->mergeCells('E' . $currentRow . ':G' . $currentRow);
            $sheet->setCellValue('E' . $currentRow, 'SK Chairperson');
            $sheet->getStyle('E' . $currentRow)->getFont()->setBold(true);
            $sheet->getStyle('E' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Set optimal column widths
            $sheet->getColumnDimension('A')->setWidth(6);
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(25);
            $sheet->getColumnDimension('D')->setWidth(12);
            $sheet->getColumnDimension('E')->setWidth(12);
            $sheet->getColumnDimension('F')->setWidth(12);
            $sheet->getColumnDimension('G')->setWidth(12);

            // Save the document
            $outputDir = WRITEPATH . 'temp/';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $fileName = 'Attendance_Report_' . date('Y-m-d_H-i-s') . '.xlsx';
            $outputPath = $outputDir . $fileName;
            
            $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $objWriter->save($outputPath);
            
            log_message('info', 'Attendance Excel document saved to: ' . $outputPath);
            return $outputPath;
            
        } catch (\Exception $e) {
            log_message('error', 'Error in generateAttendanceExcelDocument: ' . $e->getMessage());
            throw $e;
        }
    }

    public function generateAttendancePDF()
    {
        try {
            log_message('info', 'Starting Attendance Report PDF generation...');
            
            $eventId = $this->request->getPost('event_id');
            if (!$eventId) {
                return $this->response->setJSON(['success' => false, 'message' => 'Event ID is required']);
            }

            // Get event and attendance data
            $eventModel = new EventModel();
            $eventAttendanceModel = new EventAttendanceModel();
            $attendanceModel = new AttendanceModel();
            
            $event = $eventModel->find($eventId);
            if (!$event) {
                return $this->response->setJSON(['success' => false, 'message' => 'Event not found']);
            }

            $attendanceData = $attendanceModel->getEventAttendanceWithUserDetails($eventId);

            if (empty($attendanceData)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No attendance data found for this event']);
            }

            // Get logos for PDF
            $logos = $this->getLogosForDocument();

            // Generate PDF document
            $outputPdfFile = $this->generateAttendancePDFDocument($event, $attendanceData, $logos);
            
            if ($outputPdfFile && file_exists($outputPdfFile)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Attendance Report PDF generated successfully',
                    'download_url' => base_url('uploads/temp/' . basename($outputPdfFile)),
                    'filename' => basename($outputPdfFile)
                ]);
            } else {
                log_message('error', 'Attendance PDF file not created or does not exist');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error generating attendance PDF - file not created'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in generateAttendancePDF: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    private function generateAttendancePDFDocument($event, $attendanceData, $logos = [])
    {
        try {
            log_message('info', 'Starting Attendance PDF document creation...');
            
            // Create HTML content for PDF (following ped-officers format)
            $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; font-size: 12px; }
        .header-table { width: 100%; margin-bottom: 20px; }
        .header-table td { vertical-align: middle; text-align: center; }
        .logo { width: 60px; height: 60px; }
        .header-text { font-weight: bold; margin: 2px 0; }
        .subheader-text { margin: 2px 0; }
        .title { font-size: 16px; font-weight: bold; text-align: center; margin: 20px 0; }
        .event-info { text-align: center; margin: 10px 0; }
        .attendance-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .attendance-table th, .attendance-table td { 
            border: 1px solid #000; 
            padding: 8px; 
            text-align: center; 
            font-size: 10px; 
        }
        .attendance-table th { background-color: #f0f0f0; font-weight: bold; }
        .signatures { margin-top: 40px; }
        .signature-section { width: 50%; float: left; text-align: center; }
        .signature-line { margin: 30px 0 5px 0; }
    </style>
</head>
<body>';

            // Header with logos
            $html .= '<table class="header-table">
                <tr>
                    <td width="20%">';
            
            if (isset($logos['sk']) && file_exists(FCPATH . $logos['sk']['file_path'])) {
                $logoBase64 = base64_encode(file_get_contents(FCPATH . $logos['sk']['file_path']));
                $logoMimeType = mime_content_type(FCPATH . $logos['sk']['file_path']);
                $html .= '<img src="data:' . $logoMimeType . ';base64,' . $logoBase64 . '" class="logo">';
            } else {
                $html .= '<div style="width: 60px; height: 60px; border: 1px solid #000; display: inline-block;">SK LOGO</div>';
            }

            $html .= '</td>
                    <td width="60%">
                        <div class="header-text">REPUBLIC OF THE PHILIPPINES</div>
                        <div class="header-text">PROVINCE OF CAMARINES SUR</div>
                        <div class="header-text">CITY OF IRIGA</div>
                        <div class="subheader-text">SANGGUNIANG KABATAAN</div>
                    </td>
                    <td width="20%">';

            if (isset($logos['iriga_city']) && file_exists(FCPATH . $logos['iriga_city']['file_path'])) {
                $logoBase64 = base64_encode(file_get_contents(FCPATH . $logos['iriga_city']['file_path']));
                $logoMimeType = mime_content_type(FCPATH . $logos['iriga_city']['file_path']);
                $html .= '<img src="data:' . $logoMimeType . ';base64,' . $logoBase64 . '" class="logo">';
            } else {
                $html .= '<div style="width: 60px; height: 60px; border: 1px solid #000; display: inline-block;">IRIGA LOGO</div>';
            }

            $html .= '</td>
                </tr>
            </table>';

            // Title and event info
            $html .= '<div class="title">ATTENDANCE REPORT</div>';
            $html .= '<div class="event-info">Event: ' . htmlspecialchars($event['title']) . '</div>';
            $html .= '<div class="event-info">Date: ' . date('F j, Y', strtotime($event['start_datetime'])) . '</div>';

            // Attendance table (simplified 7 columns)
            $html .= '<table class="attendance-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>KK Number</th>
                        <th>Name</th>
                        <th>Zone</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>';

            $rowNum = 1;
            foreach ($attendanceData as $attendance) {
                $fullName = trim($attendance['last_name'] . ', ' . $attendance['first_name']);
                if (!empty($attendance['middle_name'])) {
                    $fullName .= ' ' . $attendance['middle_name'];
                }

                // Format status and time fields
                $timeIn = '-';
                $timeOut = '-';
                $status = 'Absent';
                
                // Check time-in (prioritize AM, then PM)
                if (!empty($attendance['time-in_am'])) {
                    $timeIn = date('h:i A', strtotime($attendance['time-in_am']));
                } elseif (!empty($attendance['time-in_pm'])) {
                    $timeIn = date('h:i A', strtotime($attendance['time-in_pm']));
                }
                
                // Check time-out (prioritize PM, then AM)
                if (!empty($attendance['time-out_pm'])) {
                    $timeOut = date('h:i A', strtotime($attendance['time-out_pm']));
                } elseif (!empty($attendance['time-out_am'])) {
                    $timeOut = date('h:i A', strtotime($attendance['time-out_am']));
                }
                
                // Determine status
                if (!empty($attendance['time-in_am']) || !empty($attendance['time-in_pm'])) {
                    $status = 'Present';
                    if ((!empty($attendance['time-in_am']) && !empty($attendance['time-out_am'])) || 
                        (!empty($attendance['time-in_pm']) && !empty($attendance['time-out_pm']))) {
                        $status = 'Complete';
                    }
                }

                $html .= '<tr>
                    <td>' . $rowNum . '</td>
                    <td>' . htmlspecialchars($attendance['user_id'] ?: '') . '</td>
                    <td>' . htmlspecialchars($fullName) . '</td>
                    <td>' . htmlspecialchars(ZoneHelper::getZoneName($attendance['zone_purok']) ?: 'N/A') . '</td>
                    <td>' . $timeIn . '</td>
                    <td>' . $timeOut . '</td>
                    <td>' . $status . '</td>
                </tr>';
                $rowNum++;
            }

            $html .= '</tbody></table>';

            // Signatures section
            $html .= '<div class="signatures">
                <div class="signature-section">
                    <div>Prepared by:</div>
                    <div class="signature-line">________________________</div>
                    <div><strong>SK Secretary</strong></div>
                </div>
                <div class="signature-section">
                    <div>Approved by:</div>
                    <div class="signature-line">________________________</div>
                    <div><strong>SK Chairperson</strong></div>
                </div>
                <div style="clear: both;"></div>
            </div>';

            $html .= '</body></html>';

            // Use DomPDF to generate PDF
            require_once FCPATH . '../vendor/autoload.php';
            
            $dompdf = new \Dompdf\Dompdf([
                'enable_font_subsetting' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Arial'
            ]);
            
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            // Save PDF file
            $outputDir = WRITEPATH . 'temp/';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $fileName = 'Attendance_Report_' . date('Y-m-d_H-i-s') . '.pdf';
            $outputPath = $outputDir . $fileName;
            
            file_put_contents($outputPath, $dompdf->output());
            
            log_message('info', 'Attendance PDF document saved to: ' . $outputPath);
            return $outputPath;
            
        } catch (\Exception $e) {
            log_message('error', 'Error in generateAttendancePDFDocument: ' . $e->getMessage());
            throw $e;
        }
    }

    public function generateAttendanceWord()
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
            log_message('info', 'Starting Attendance Report Word generation...');
            
            $eventId = $this->request->getPost('event_id');
            if (!$eventId) {
                return $this->response->setJSON(['success' => false, 'message' => 'Event ID is required']);
            }

            // Get event and attendance data
            $eventModel = new EventModel();
            $eventAttendanceModel = new EventAttendanceModel();
            $attendanceModel = new AttendanceModel();
            
            $event = $eventModel->find($eventId);
            if (!$event) {
                return $this->response->setJSON(['success' => false, 'message' => 'Event not found']);
            }

            $attendanceData = $attendanceModel->getEventAttendanceWithUserDetails($eventId);

            if (empty($attendanceData)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No attendance data found for this event']);
            }

            // Get logos for Word document
            $logos = $this->getLogosForDocument();

            // Generate Word document
            $outputWordFile = $this->generateAttendanceWordDocument($event, $attendanceData, $logos);
            
            if ($outputWordFile && file_exists($outputWordFile)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Attendance Report Word generated successfully',
                    'download_url' => base_url('uploads/temp/' . basename($outputWordFile)),
                    'filename' => basename($outputWordFile)
                ]);
            } else {
                log_message('error', 'Attendance Word file not created or does not exist');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error generating attendance Word document - file not created'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in generateAttendanceWord: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    private function generateAttendanceWordDocument($event, $attendanceData, $logos = [])
    {
        try {
            log_message('info', 'Starting Attendance Word document creation...');
            
            require_once FCPATH . '../vendor/autoload.php';
            
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            log_message('info', 'PHPWord instance created successfully');
        
            // Set document properties
            $properties = $phpWord->getDocInfo();
            $properties->setCreator('K-NECT System');
            $properties->setCompany('Sangguniang Kabataan');
            $properties->setTitle('Attendance Report');
            $properties->setDescription('Attendance report generated from K-NECT System');
            $properties->setCategory('Government Document');
            $properties->setSubject('Attendance Report');
            
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
            $titleStyle = ['name' => 'Arial', 'size' => 14, 'bold' => true];
            $tableHeaderStyle = ['name' => 'Arial', 'size' => 9, 'bold' => true];
            $tableCellStyle = ['name' => 'Arial', 'size' => 8];
            
            // Create header section with logos (following ped-officers format)
            $headerTable = $section->addTable([
                'borderSize' => 0,
                'borderColor' => 'FFFFFF',
                'width' => 100 * 50,
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
            ]);
            $headerTable->addRow();
            
            // Left logo cell (SK)
            $leftCell = $headerTable->addCell(2000, ['valign' => 'center']);
            if (isset($logos['sk']) && file_exists(FCPATH . $logos['sk']['file_path'])) {
                try {
                    $leftCell->addImage(FCPATH . $logos['sk']['file_path'], [
                        'width' => 50.4,
                        'height' => 50.4,
                        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
                    ]);
                } catch (\Exception $e) {
                    $leftCell->addText('SK LOGO', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                }
            } else {
                $leftCell->addText('SK LOGO', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            }
            
            // Center text cell
            $centerCell = $headerTable->addCell(6000, ['valign' => 'center']);
            $centerCell->addText('REPUBLIC OF THE PHILIPPINES', $headerStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $centerCell->addText('PROVINCE OF CAMARINES SUR', $headerStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $centerCell->addText('CITY OF IRIGA', $headerStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $centerCell->addText('SANGGUNIANG KABATAAN', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            
            // Right logo cell (Iriga City)
            $rightCell = $headerTable->addCell(2000, ['valign' => 'center']);
            if (isset($logos['iriga_city']) && file_exists(FCPATH . $logos['iriga_city']['file_path'])) {
                try {
                    $rightCell->addImage(FCPATH . $logos['iriga_city']['file_path'], [
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
            
            // Add title and event info
            $section->addTextBreak();
            $section->addText('ATTENDANCE REPORT', $titleStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $section->addTextBreak();
            $section->addText('Event: ' . $event['title'], $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $section->addText('Date: ' . date('F j, Y', strtotime($event['start_datetime'])), $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $section->addTextBreak();
            
            // Create data table (simplified 7 columns)
            $table = $section->addTable([
                'borderSize' => 4,
                'borderColor' => '000000',
                'cellMargin' => 20,
                'width' => 100 * 50,
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
            ]);
            
            // Add table header
            $table->addRow();
            $table->addCell(800)->addText('No.', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell(1500)->addText('KK Number', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell(2500)->addText('Name', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell(1000)->addText('Zone', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell(1000)->addText('Time In', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell(1000)->addText('Time Out', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $table->addCell(1200)->addText('Status', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            
            // Add attendance data rows
            $rowNum = 1;
            foreach ($attendanceData as $attendance) {
                $fullName = trim($attendance['last_name'] . ', ' . $attendance['first_name']);
                if (!empty($attendance['middle_name'])) {
                    $fullName .= ' ' . $attendance['middle_name'];
                }

                // Format status and time fields
                $timeIn = '-';
                $timeOut = '-';
                $status = 'Absent';
                
                // Check time-in (prioritize AM, then PM)
                if (!empty($attendance['time-in_am'])) {
                    $timeIn = date('h:i A', strtotime($attendance['time-in_am']));
                } elseif (!empty($attendance['time-in_pm'])) {
                    $timeIn = date('h:i A', strtotime($attendance['time-in_pm']));
                }
                
                // Check time-out (prioritize PM, then AM)
                if (!empty($attendance['time-out_pm'])) {
                    $timeOut = date('h:i A', strtotime($attendance['time-out_pm']));
                } elseif (!empty($attendance['time-out_am'])) {
                    $timeOut = date('h:i A', strtotime($attendance['time-out_am']));
                }
                
                // Determine status
                if (!empty($attendance['time-in_am']) || !empty($attendance['time-in_pm'])) {
                    $status = 'Present';
                    if ((!empty($attendance['time-in_am']) && !empty($attendance['time-out_am'])) || 
                        (!empty($attendance['time-in_pm']) && !empty($attendance['time-out_pm']))) {
                        $status = 'Complete';
                    }
                }

                $table->addRow();
                $table->addCell(800)->addText($rowNum, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $table->addCell(1500)->addText($attendance['user_id'] ?: '', $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $table->addCell(2500)->addText($fullName, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $table->addCell(1000)->addText(ZoneHelper::getZoneName($attendance['zone_purok']) ?: 'N/A', $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $table->addCell(1000)->addText($timeIn, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $table->addCell(1000)->addText($timeOut, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                $table->addCell(1200)->addText($status, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
                
                $rowNum++;
            }
            
            // Add signature section
            $section->addTextBreak(2);
            $signatureTable = $section->addTable([
                'borderSize' => 0,
                'borderColor' => 'FFFFFF',
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
            ]);
            $signatureTable->addRow();
            
            // Prepared by
            $preparedCell = $signatureTable->addCell(4000, ['valign' => 'center']);
            $preparedCell->addText('Prepared by:', $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 800]);
            $preparedCell->addText('_________________________', $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 100]);
            $preparedCell->addText('SK Secretary', ['name' => 'Arial', 'size' => 9, 'bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            
            // Approved by
            $approvedCell = $signatureTable->addCell(4000, ['valign' => 'center']);
            $approvedCell->addText('Approved by:', $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 800]);
            $approvedCell->addText('_________________________', $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 100]);
            $approvedCell->addText('SK Chairperson', ['name' => 'Arial', 'size' => 9, 'bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            
            // Save the document
            $outputDir = WRITEPATH . 'temp/';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $fileName = 'Attendance_Report_' . date('Y-m-d_H-i-s') . '.docx';
            $outputPath = $outputDir . $fileName;
            
            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save($outputPath);
            
            log_message('info', 'Attendance Word document saved to: ' . $outputPath);
            return $outputPath;
            
        } catch (\Exception $e) {
            log_message('error', 'Error in generateAttendanceWordDocument: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    private function getLogosForDocument()
    {
        try {
            $systemLogoModel = new SystemLogoModel();
            $logos = [];
            
            // Get SK logo (try barangay-specific first, then any active SK logo)
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            
            $skLogo = null;
            if ($skBarangay) {
                // Try to get barangay-specific SK logo first
                $skLogo = $systemLogoModel->where('logo_type', 'sk')
                                         ->where('barangay_id', $skBarangay)
                                         ->where('is_active', true)
                                         ->orderBy('created_at', 'DESC')
                                         ->first();
                
                if ($skLogo) {
                    log_message('info', 'Barangay-specific SK logo found for barangay: ' . $skBarangay);
                }
            }
            
            // If no barangay-specific logo, get any active SK logo
            if (!$skLogo) {
                $skLogo = $systemLogoModel->where('logo_type', 'sk')
                                         ->where('is_active', true)
                                         ->orderBy('created_at', 'DESC')
                                         ->first();
                if ($skLogo) {
                    log_message('info', 'General SK logo found (fallback)');
                }
            }
            
            if ($skLogo && file_exists(FCPATH . $skLogo['file_path'])) {
                $logos['sk'] = $skLogo;
                log_message('info', 'SK logo added: ' . $skLogo['file_path']);
            } else {
                log_message('warning', 'No SK logo found or file does not exist');
            }
            
            // Get Barangay logo (prioritize barangay-specific, then any active)
            $barangayLogo = null;
            if ($skBarangay) {
                // Try to get barangay-specific logo first
                $barangayLogo = $systemLogoModel->where('logo_type', 'barangay')
                                               ->where('barangay_id', $skBarangay)
                                               ->where('is_active', true)
                                               ->orderBy('created_at', 'DESC')
                                               ->first();
            }
            
            // If no barangay-specific logo, get any active barangay logo as fallback
            if (!$barangayLogo) {
                $barangayLogo = $systemLogoModel->where('logo_type', 'barangay')
                                               ->where('is_active', true)
                                               ->orderBy('created_at', 'DESC')
                                               ->first();
            }
            
            if ($barangayLogo && file_exists(FCPATH . $barangayLogo['file_path'])) {
                $logos['barangay'] = $barangayLogo;
                log_message('info', 'Barangay logo added: ' . $barangayLogo['file_path']);
            } else {
                log_message('warning', 'No barangay logo found or file does not exist');
            }
            
            // Get Iriga City logo (should be global)
            $irigaLogo = $systemLogoModel->where('logo_type', 'iriga_city')
                                        ->where('is_active', true)
                                        ->orderBy('created_at', 'DESC')
                                        ->first();
            if ($irigaLogo && file_exists(FCPATH . $irigaLogo['file_path'])) {
                $logos['iriga_city'] = $irigaLogo;
                log_message('info', 'Iriga City logo added: ' . $irigaLogo['file_path']);
            } else {
                log_message('warning', 'No Iriga City logo found or file does not exist');
            }
            
            log_message('info', 'Total logos found for document: ' . count($logos));
            return $logos;
        } catch (\Exception $e) {
            log_message('error', 'Error fetching logos: ' . $e->getMessage());
            return [];
        }
    }
   
}
