<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserExtInfoModel;
use App\Models\AddressModel;
use App\Models\AttendanceModel;
use App\Models\EventModel;
use App\Models\BulletinModel;
use App\Controllers\ProfileController;
use App\Libraries\BarangayHelper;
// Note: NotificationSettingsModel and PrivacySettingsModel are optional and not required for basic settings

class KKController extends BaseController
{
    public function dashboard()
    {
        $session = session();
        $userId = $session->get('user_id');
        $username = $session->get('username');
        $userType = $session->get('user_type') ?: 'KK';
        // Prefer kk_barangay for KK; fallback to sk_barangay only if kk is not set
    $kk = $session->get('kk_barangay');
    $sk = $session->get('sk_barangay');
    $gen = $session->get('barangay_id');
    $kk = ($kk === '' || $kk === '0' || $kk === 0) ? null : $kk;
    $sk = ($sk === '' || $sk === '0' || $sk === 0) ? null : $sk;
    $gen = ($gen === '' || $gen === '0' || $gen === 0) ? null : $gen;
        $barangayId = $kk ?? $gen ?? $sk;
        // If still missing, try to resolve from user's address and persist in session
        if (empty($barangayId)) {
            try {
                $permanentUserId = $session->get('user_id');
                if ($permanentUserId) {
                    $um = new UserModel();
                    $me = $um->where('user_id', $permanentUserId)->first();
                    if ($me && !empty($me['id'])) {
                        $addrModel = new AddressModel();
                        $addr = $addrModel->where('user_id', $me['id'])->first();
                        if ($addr && !empty($addr['barangay'])) {
                            $barangayId = $addr['barangay'];
                            $session->set('barangay_id', $barangayId);
                        }
                    }
                }
            } catch (\Throwable $t) {
                log_message('error', 'KK dashboard barangay resolve fallback error: ' . $t->getMessage());
            }
        }

        // Gather bulletin data for embedding
        $bulletin = [
            'posts' => [],
            'featured_posts' => [],
            'urgent_posts' => [],
            'categories' => [],
            'recent_events' => [],
            'recent_documents' => [],
            'user_type' => $userType,
            'user_id' => $userId,
            'barangay_id' => $barangayId,
            'barangay_name' => $barangayId ? \App\Libraries\BarangayHelper::getBarangayName($barangayId) : null,
        ];

        try {
            $bm = new BulletinModel();
            $role = strtolower((string)$userType);
            $bulletin['posts'] = $bm->getVisiblePosts($role, $barangayId, 10, 0);
            $bulletin['featured_posts'] = $bm->getFeaturedPosts(3, $role, $barangayId);
            $bulletin['urgent_posts'] = $bm->getUrgentPosts(3, $role, $barangayId);
            $bulletin['categories'] = $bm->getCategoriesWithCounts($role, $barangayId);
            $recentEvents = $bm->getRecentEvents(5, $role, $barangayId);
            if (empty($recentEvents)) {
                $recentEvents = $bm->getRecentEventsAnyDate(5, $role, $barangayId);
            }
            $bulletin['recent_events'] = $recentEvents;
            $bulletin['recent_documents'] = $bm->getRecentDocuments(5, $role, $barangayId);
        } catch (\Throwable $t) {
            log_message('error', 'KK Dashboard bulletin embed error: ' . $t->getMessage());
        }

        $data = [
            'user_id' => $userId,
            'username' => $username,
            'bulletinData' => $bulletin,
        ];

        return
            $this->loadView('K-NECT/KK/template/header') .
            $this->loadView('K-NECT/KK/template/sidebar') .
            $this->loadView('K-NECT/KK/dashboard', $data) .
            $this->loadView('K-NECT/KK/template/footer');
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

        // Get attendance statistics
        $attendanceModel = new AttendanceModel();
        $attendanceStats = [
            'total_events' => 0,
            'attended_events' => 0,
            'attendance_percentage' => 0,
            'recent_events' => []
        ];
        
        // Get user attendance records
        $attendanceRecords = $attendanceModel->getUserAttendanceHistory($userId);
        $totalEvents = count($attendanceRecords);
        $attendedEvents = 0;
        
        // Process attendance data
        foreach ($attendanceRecords as $record) {
            if (
                ($record['time-in_am'] && $record['time-in_am'] !== '00:00:00') || 
                ($record['time-out_am'] && $record['time-out_am'] !== '00:00:00') ||
                ($record['time-in_pm'] && $record['time-in_pm'] !== '00:00:00') || 
                ($record['time-out_pm'] && $record['time-out_pm'] !== '00:00:00')
            ) {
                $attendedEvents++;
            }
        }
        
        $attendanceStats['total_events'] = $totalEvents;
        $attendanceStats['attended_events'] = $attendedEvents;
        $attendanceStats['attendance_percentage'] = $totalEvents > 0 ? round(($attendedEvents / $totalEvents) * 100) : 0;
        
        // Get 3 most recent events
        $attendanceStats['recent_events'] = array_slice($attendanceRecords, 0, 3);

        // Merge all data for the view (using ProfileController structure)
        // Compute address barangay name for the view
        $addressBarangayName = '';
        if (!empty($profileData['address']['barangay'])) {
            $addressBarangayName = BarangayHelper::getBarangayName($profileData['address']['barangay']);
        }

        $data = array_merge($profileData, [
            'username' => $session->get('username'),
            'attendance_stats' => $attendanceStats,
            'field_mappings' => $profileController->getFieldMappings(),
            'address_barangay_name' => $addressBarangayName,
        ]);

        return 
            $this->loadView('K-NECT/KK/template/header') .
            $this->loadView('K-NECT/KK/template/sidebar') .
            $this->loadView('K-NECT/KK/profile', $data) .
            $this->loadView('K-NECT/KK/template/footer');
    }

    public function attendance()
    {
        $session = session();
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return redirect()->to('login')->with('error', 'Please login to view attendance.');
        }

        $attendanceModel = new AttendanceModel();
        
        // Get all attendance records for this user with event details
        $userAttendance = $attendanceModel->getUserAttendanceHistory($userId);
        
        // Process attendance data to determine status for each event
        $attendanceData = [];
        foreach ($userAttendance as $record) {
            $attendedSessions = [];
            $totalSessions = 0;
            
            // Check AM session
            if (($record['time-in_am'] && $record['time-in_am'] !== '00:00:00') || 
                ($record['time-out_am'] && $record['time-out_am'] !== '00:00:00')) {
                $attendedSessions[] = 'Morning';
                $totalSessions++;
            }
            
            // Check PM session  
            if (($record['time-in_pm'] && $record['time-in_pm'] !== '00:00:00') || 
                ($record['time-out_pm'] && $record['time-out_pm'] !== '00:00:00')) {
                $attendedSessions[] = 'Afternoon';
                $totalSessions++;
            }
            
            // If no sessions attended but record exists, count as attempted
            if (empty($attendedSessions)) {
                $totalSessions = 1; // At least tried to attend
            }
            
            $attendanceData[] = [
                'event_title' => $record['title'],
                'event_description' => $record['description'],
                'event_date' => date('M d, Y', strtotime($record['start_datetime'])),
                'event_time' => date('h:i A', strtotime($record['start_datetime'])) . ' - ' . date('h:i A', strtotime($record['end_datetime'])),
                'event_location' => $record['location'],
                'event_banner' => $record['event_banner'],
                'time_in_am' => ($record['time-in_am'] && $record['time-in_am'] !== '00:00:00') ? date('h:i A', strtotime($record['time-in_am'])) : null,
                'time_out_am' => ($record['time-out_am'] && $record['time-out_am'] !== '00:00:00') ? date('h:i A', strtotime($record['time-out_am'])) : null,
                'time_in_pm' => ($record['time-in_pm'] && $record['time-in_pm'] !== '00:00:00') ? date('h:i A', strtotime($record['time-in_pm'])) : null,
                'time_out_pm' => ($record['time-out_pm'] && $record['time-out_pm'] !== '00:00:00') ? date('h:i A', strtotime($record['time-out_pm'])) : null,
                'status_am' => $record['status_am'],
                'status_pm' => $record['status_pm'],
                'attended_sessions' => $attendedSessions,
                'session_count' => $totalSessions,
                'overall_status' => !empty($attendedSessions) ? 'Attended' : 'Registered'
            ];
        }
        
        $data = [
            'username' => $session->get('username'),
            'attendance_records' => $attendanceData,
            'total_events_attended' => count($attendanceData)
        ];

        return 
            $this->loadView('K-NECT/KK/template/header') .
            $this->loadView('K-NECT/KK/template/sidebar') .
            $this->loadView('K-NECT/KK/attendance', $data) .
            $this->loadView('K-NECT/KK/template/footer');
    }

    /**
     * Display the settings page
     */
    public function settings()
    {
        $session = session();
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return redirect()->to('login')->with('error', 'Please login to access settings.');
        }

        // Use shared ProfileController for common functionality
        $profileController = new ProfileController();
        $profileData = $profileController->getUserProfileData($userId);
        
        if (!$profileData) {
            return redirect()->to('login')->with('error', 'User profile not found.');
        }

        // Compute address barangay name for the view
        $addressBarangayName = '';
        if (!empty($profileData['address']['barangay'])) {
            $addressBarangayName = BarangayHelper::getBarangayName($profileData['address']['barangay']);
        }

        // Prepare data for the view
        $data = array_merge($profileData, [
            'username' => $session->get('username'),
            'address_barangay_name' => $addressBarangayName,
            // Notification and privacy settings are optional; omit when models are not present
        ]);

        return 
            $this->loadView('K-NECT/KK/template/header') .
            $this->loadView('K-NECT/KK/template/sidebar') .
            $this->loadView('K-NECT/KK/settings', $data) .
            $this->loadView('K-NECT/KK/template/footer');
    }

    /**
     * Update profile information
     */
    public function updateProfile()
    {
        $session = session();
        $permanentUserId = $session->get('user_id');
        
        if (!$permanentUserId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please login to update profile.']);
        }

        // Handle form submission
        $userModel = new UserModel();
        $userExtModel = new UserExtInfoModel();
        $addressModel = new AddressModel();

        // Resolve DB user id from permanent user_id
        $userRow = $userModel->where('user_id', $permanentUserId)->first();
        if (!$userRow) {
            return redirect()->to('kk/settings')->with('error', 'User not found.');
        }
        $dbUserId = $userRow['id'];

        // Begin transaction
        $db = \Config\Database::connect();
        $db->transBegin();

    try {
            // Update basic user info (only changed fields to avoid empty update error)
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

            // Upsert address info (map to actual columns)
            $postedAddress = [
                'zone_purok'   => $this->request->getPost('street'), // UI uses 'street' input for zone/purok
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

            // Handle profile picture upload if provided
            $file = $this->request->getFile('profile_picture');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!in_array($file->getClientMimeType(), $validTypes)) {
                    throw new \RuntimeException('Invalid file type. Please upload a JPEG, PNG, or GIF image.');
                }
                if ($file->getSize() > 2 * 1024 * 1024) {
                    throw new \RuntimeException('File size exceeds 2MB limit.');
                }

                // Get current profile picture for deletion after update
                $currentExt = $userExtModel->where('user_id', $dbUserId)->first();
                $oldPath = $currentExt['profile_picture'] ?? null;

                $targetDir = FCPATH . 'uploads/profile_pictures/';
                if (!is_dir($targetDir)) {
                    @mkdir($targetDir, 0775, true);
                }
                // Match profiling naming convention: profilepic_<uniqid>.<ext>
                $newName = 'profilepic_' . uniqid() . '.' . $file->getClientExtension();
                $file->move($targetDir, $newName);
                $profilePicturePath = 'uploads/profile_pictures/' . $newName;

                $userExtModel->where('user_id', $dbUserId)->set([
                    'profile_picture' => $profilePicturePath
                ])->update();

                // Delete old file if different
                if (!empty($oldPath) && $oldPath !== $profilePicturePath) {
                    $candidates = [];
                    if (strpos($oldPath, '/') !== false) {
                        $candidates[] = ROOTPATH . 'public/' . ltrim($oldPath, '/');
                    } else {
                        // old value stored as bare filename
                        $candidates[] = FCPATH . 'uploads/profile_pictures/' . $oldPath;
                        $candidates[] = FCPATH . 'uploads/profile/' . $oldPath; // legacy location
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
            return redirect()->to('kk/settings')->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to('kk/settings')->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    /**
     * Update account settings
     */
    public function updateAccount()
    {
        $session = session();
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return redirect()->to('login')->with('error', 'Please login to update account settings.');
        }

        // Handle account settings update
        $userModel = new UserModel();
        
        try {
            $userModel->update($userId, [
                'language' => $this->request->getPost('language'),
                'timezone' => $this->request->getPost('timezone')
            ]);
            
            return redirect()->to('kk/settings')->with('success', 'Account settings updated successfully.');
        } catch (\Exception $e) {
            return redirect()->to('kk/settings')->with('error', 'Failed to update account settings: ' . $e->getMessage());
        }
    }

    /**
     * Update notification preferences
     */
    public function updateNotifications()
    {
        $session = session();
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return redirect()->to('login')->with('error', 'Please login to update notification settings.');
        }

        // Handle notification settings update
        // TODO: Implement NotificationSettingsModel when needed
        /*
        $notificationModel = new NotificationSettingsModel();
        
        $data = [
            'user_id' => $userId,
            'email_events' => $this->request->getPost('email_events') ? 1 : 0,
            'email_announcements' => $this->request->getPost('email_announcements') ? 1 : 0,
            'email_reminders' => $this->request->getPost('email_reminders') ? 1 : 0,
            'email_newsletter' => $this->request->getPost('email_newsletter') ? 1 : 0,
            'sms_events' => $this->request->getPost('sms_events') ? 1 : 0,
            'sms_emergency' => $this->request->getPost('sms_emergency') ? 1 : 0,
            'app_all' => $this->request->getPost('app_all') ? 1 : 0
        ];

        try {
            // Check if settings already exist
            $existingSettings = $notificationModel->where('user_id', $userId)->first();
            if ($existingSettings) {
                $notificationModel->update($existingSettings['id'], $data);
            } else {
                $notificationModel->insert($data);
            }
            
            return redirect()->to('kk/settings')->with('success', 'Notification preferences updated successfully.');
        } catch (\Exception $e) {
            return redirect()->to('kk/settings')->with('error', 'Failed to update notification preferences: ' . $e->getMessage());
        }
        */
        
        // Temporary implementation - just return success message
        return redirect()->to('kk/settings')->with('success', 'Notification preferences will be implemented soon.');
    }

    /**
     * Update password
     */
    public function updatePassword()
    {
        $session = session();
        $permanentUserId = $session->get('user_id'); // persistent user_id, not DB PK
        
        if (!$permanentUserId) {
            return redirect()->to('login')->with('error', 'Please login to change your password.');
        }

        // Handle password change
        $userModel = new UserModel();
        // Resolve DB primary id from permanent user_id
        $userRow = $userModel->where('user_id', $permanentUserId)->first();
        if (!$userRow) {
            return redirect()->to('kk/settings')->with('error', 'User not found.');
        }
        $dbUserId = $userRow['id'];
        
        // Verify current password
        $currentPassword = (string)$this->request->getPost('current_password');
        if ($currentPassword === '') {
            return redirect()->to('kk/settings')->with('error', 'Please enter your current password.');
        }
        $storedHash = (string)($userRow['password'] ?? '');
        if ($storedHash === '' || !password_verify($currentPassword, $storedHash)) {
            return redirect()->to('kk/settings')->with('error', 'Current password is incorrect.');
        }
        
        // Validate new password (strong requirements)
        $newPassword = (string)$this->request->getPost('new_password');
        $confirmPassword = (string)$this->request->getPost('confirm_password');
        
        if ($newPassword !== $confirmPassword) {
            return redirect()->to('kk/settings')->with('error', 'New passwords do not match.');
        }
        
        $errors = [];
        if (strlen($newPassword) < 8) {
            $errors[] = 'at least 8 characters';
        }
        if (!preg_match('/[A-Z]/', $newPassword)) {
            $errors[] = 'one uppercase letter';
        }
        if (!preg_match('/[a-z]/', $newPassword)) {
            $errors[] = 'one lowercase letter';
        }
        if (!preg_match('/\d/', $newPassword)) {
            $errors[] = 'one number';
        }
        if (!preg_match('/[!@#$%^&*()_+\-={}\[\]\\|:;"\'<>,.?\/]/', $newPassword)) {
            $errors[] = 'one special character';
        }
        if (!empty($errors)) {
            return redirect()->to('kk/settings')->with('error', 'Password must contain: ' . implode(', ', $errors) . '.');
        }
        
        // Update password
        try {
            $userModel->update($dbUserId, [
                'password' => password_hash($newPassword, PASSWORD_DEFAULT)
            ]);
            
            return redirect()->to('kk/settings')->with('success', 'Password updated successfully.');
        } catch (\Exception $e) {
            return redirect()->to('kk/settings')->with('error', 'Failed to update password: ' . $e->getMessage());
        }
    }

    /**
     * Update privacy settings
     */
    public function updatePrivacy()
    {
        $session = session();
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return redirect()->to('login')->with('error', 'Please login to update privacy settings.');
        }

        // Handle privacy settings update
        // TODO: Implement PrivacySettingsModel when needed
        /*
        $privacyModel = new PrivacySettingsModel();
        
        $data = [
            'user_id' => $userId,
            'profile_visibility' => $this->request->getPost('profile_visibility'),
            'show_email' => $this->request->getPost('show_email') ? 1 : 0,
            'show_phone' => $this->request->getPost('show_phone') ? 1 : 0,
            'show_attendance' => $this->request->getPost('show_attendance') ? 1 : 0,
            'show_events' => $this->request->getPost('show_events') ? 1 : 0
        ];

        try {
            // Check if settings already exist
            $existingSettings = $privacyModel->where('user_id', $userId)->first();
            if ($existingSettings) {
                $privacyModel->update($existingSettings['id'], $data);
            } else {
                $privacyModel->insert($data);
            }
            
            return redirect()->to('kk/settings')->with('success', 'Privacy settings updated successfully.');
        } catch (\Exception $e) {
            return redirect()->to('kk/settings')->with('error', 'Failed to update privacy settings: ' . $e->getMessage());
        }
        */
        
        // Temporary implementation - just return success message
        return redirect()->to('kk/settings')->with('success', 'Privacy settings will be implemented soon.');
    }

    /**
     * Handle profile photo upload
     */
    public function uploadProfilePhoto()
    {
        $session = session();
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please login to upload a profile photo.']);
        }

    // Check if file is uploaded
    $file = $this->request->getFile('profile_picture');
    if (!$file->isValid() || $file->hasMoved()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid file upload.']);
        }

        // Validate file type
        $validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!in_array($file->getClientMimeType(), $validTypes)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid file type. Please upload a JPEG, PNG, or GIF image.']);
        }

        // Validate file size (max 2MB)
        if ($file->getSize() > 2 * 1024 * 1024) {
            return $this->response->setJSON(['success' => false, 'message' => 'File size exceeds 2MB limit.']);
        }

        try {
            // Resolve DB primary id from permanent user_id
            $userModel = new UserModel();
            $userRow = $userModel->where('user_id', $userId)->first();
            if (!$userRow) {
                return $this->response->setJSON(['success' => false, 'message' => 'User not found.']);
            }
            $dbUserId = $userRow['id'];

            // Fetch current profile picture to delete later
            $userExtModel = new UserExtInfoModel();
            $current = $userExtModel->where('user_id', $dbUserId)->first();
            $oldPath = $current['profile_picture'] ?? null;

            // Ensure destination directory exists
            $destDir = FCPATH . 'uploads/profile_pictures/';
            if (!is_dir($destDir)) {
                @mkdir($destDir, 0775, true);
            }

            // Match profiling naming convention: profilepic_<uniqid>.<ext>
            $newName = 'profilepic_' . uniqid() . '.' . $file->getClientExtension();
            $file->move($destDir, $newName);

            // Update user profile with new image path
            $profilePicturePath = 'uploads/profile_pictures/' . $newName;
            $userExtModel->where('user_id', $dbUserId)->set([
                'profile_picture' => $profilePicturePath
            ])->update();

            // Delete old profile picture if different
            if (!empty($oldPath) && $oldPath !== $profilePicturePath) {
                $candidates = [];
                if (str_contains($oldPath, '/')) {
                    $candidates[] = ROOTPATH . 'public/' . ltrim($oldPath, '/');
                } else {
                    $candidates[] = FCPATH . 'uploads/profile_pictures/' . $oldPath;
                    $candidates[] = FCPATH . 'uploads/profile/' . $oldPath; // legacy
                }
                foreach ($candidates as $abs) {
                    if (is_file($abs)) {
                        @unlink($abs);
                        break;
                    }
                }
            }
            
            // Update session data if needed
            $userData = session()->get('user_data');
            if ($userData) {
                $userData['profile_picture'] = $profilePicturePath;
                session()->set('user_data', $userData);
            }
            
            return redirect()->to('kk/settings')->with('success', 'Profile picture updated successfully.');
        } catch (\Exception $e) {
            return redirect()->to('kk/settings')->with('error', 'Failed to upload profile picture: ' . $e->getMessage());
        }
    }

    /**
     * Get user type text for display
     */
    private function getUserTypeText($userType)
    {
        switch($userType) {
            case 1:
                return 'KK Member';
            case 2:
                return 'SK Official';
            case 3:
                return 'Pederasyon Official';
            default:
                return 'Member';
        }
    }

    /**
     * Get position text for display
     */
    private function getPositionText($position)
    {
        $positions = [
            1 => 'SK Chairperson',
            2 => 'SK Kagawad', 
            3 => 'SK Secretary',
            4 => 'SK Treasurer',
            5 => 'Pederasyon President',
            6 => 'Pederasyon Vice President',
            7 => 'Pederasyon Secretary', 
            8 => 'Pederasyon Treasurer',
            9 => 'Pederasyon Auditor',
            10 => 'Pederasyon Board Member'
        ];
        return $positions[$position] ?? '';
    }
}