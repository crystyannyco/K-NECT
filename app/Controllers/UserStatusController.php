<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\AddressModel;
use App\Models\UserExtInfoModel;
use App\Libraries\BarangayHelper;

class UserStatusController extends BaseController
{
    protected $userModel;
    protected $addressModel;
    protected $userExtInfoModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->addressModel = new AddressModel();
        $this->userExtInfoModel = new UserExtInfoModel();
    }

    /**
     * Get inactive users (overage 31+ years old and 1+ year inactive)
     */
    public function getInactiveUsers()
    {
        try {
            $session = session();
            $userType = $session->get('user_type');
            $skBarangay = $session->get('sk_barangay'); // Get barangay directly from session like youthProfile
            
            $query = $this->userModel
                ->select('
                    user.id, user.user_id, user.first_name, user.last_name, user.middle_name, 
                    user.suffix, user.email, user.phone_number, user.birthdate, user.sex, 
                    user.is_active, user.last_login, user.created_at, user.position,
                    address.barangay, address.zone_purok, address.municipality, address.province, address.region
                ')
                ->join('address', 'address.user_id = user.id', 'left')
                ->whereIn('user.is_active', [2, 3]); // 2 = overage (31+), 3 = inactive (1+ year)

            // Filter by barangay for SK officials - only show users from the same barangay
            if ($userType === 'sk' && $skBarangay) {
                $query->where('address.barangay', $skBarangay);
            }

            $inactiveUsers = $query->findAll();

            // Process the data
            $today = new \DateTime();
            foreach ($inactiveUsers as &$user) {
                // Calculate age
                if (!empty($user['birthdate'])) {
                    $birthDate = new \DateTime($user['birthdate']);
                    $user['age'] = $today->diff($birthDate)->y;
                } else {
                    $user['age'] = 0;
                }

                // Format last login
                if (!empty($user['last_login'])) {
                    $user['last_login_formatted'] = date('M j, Y g:i A', strtotime($user['last_login']));
                    $lastLogin = new \DateTime($user['last_login']);
                    $user['days_since_login'] = $today->diff($lastLogin)->days;
                } else {
                    $user['last_login_formatted'] = 'Never';
                    $user['days_since_login'] = null;
                }

                // Format created date
                if (!empty($user['created_at'])) {
                    $user['created_at_formatted'] = date('M j, Y g:i A', strtotime($user['created_at']));
                }

                // Get barangay name
                $user['barangay_name'] = BarangayHelper::getBarangayName($user['barangay']);

                // Determine inactive reason
                if ($user['is_active'] == 2) {
                    $user['inactive_reason'] = 'Overage (31+ Years)';
                    $user['inactive_class'] = 'bg-orange-100 text-orange-800';
                } elseif ($user['is_active'] == 3) {
                    $user['inactive_reason'] = 'Inactive (1+ Year)';
                    $user['inactive_class'] = 'bg-red-100 text-red-800';
                } else {
                    $user['inactive_reason'] = 'Unknown';
                    $user['inactive_class'] = 'bg-gray-100 text-gray-800';
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'users' => $inactiveUsers
            ]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Error fetching inactive users: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Manually run the user status check
     */
    public function runStatusCheck()
    {
        try {
            $aged31Count = 0;
            $inactiveCount = 0;
            $specialCaseCount = 0;
            $processedCount = 0;

            // First, check for users where both active status and birthday-related fields have value 4
            $specialCaseUsers = $this->userModel
                ->select('user.*, user_ext_info.age_group, user_ext_info.youth_classification')
                ->join('user_ext_info', 'user_ext_info.user_id = user.id', 'left')
                ->where('user.is_active', 4)
                ->where('user_ext_info.age_group', 4)
                ->findAll();

            foreach ($specialCaseUsers as $user) {
                // Update these users to inactive status (is_active = 3)
                $this->userModel->update($user['id'], ['is_active' => 3]);
                $specialCaseCount++;
                
                log_message('info', "Special case user updated: {$user['first_name']} {$user['last_name']} (ID: {$user['user_id']}) - active=4, age_group=4");
            }


            // Get all active users (is_active = 1), skip reactivated users (is_active = 5)
            $activeUsers = $this->userModel->where('is_active', 1)->findAll();

            foreach ($activeUsers as $user) {
                // Skip users who have been reactivated (is_active = 5)
                if ($user['is_active'] == 5) {
                    continue;
                }
                $updated = false;

                // Check age (31+ years old)
                if (!empty($user['birthdate'])) {
                    $birthDate = new \DateTime($user['birthdate']);
                    $today = new \DateTime();
                    $age = $today->diff($birthDate)->y;

                    if ($age >= 31) {
                        $this->userModel->update($user['id'], ['is_active' => 2]);
                        $aged31Count++;
                        $updated = true;
                    }
                }

                // Check inactivity (1+ year without login) - only if not already marked as aged out
                if (!$updated && !empty($user['last_login'])) {
                    $lastLogin = new \DateTime($user['last_login']);
                    $today = new \DateTime();
                    $daysSinceLogin = $today->diff($lastLogin)->days;

                    if ($daysSinceLogin >= 365) {
                        $this->userModel->update($user['id'], ['is_active' => 3]);
                        $inactiveCount++;
                        $updated = true;
                    }
                }

                // Check for users who never logged in (created more than 1 year ago)
                if (!$updated && empty($user['last_login'])) {
                    $createdAt = new \DateTime($user['created_at']);
                    $today = new \DateTime();
                    $daysSinceCreation = $today->diff($createdAt)->days;

                    if ($daysSinceCreation >= 365) {
                        $this->userModel->update($user['id'], ['is_active' => 3]);
                        $inactiveCount++;
                        $updated = true;
                    }
                }

                if ($updated) {
                    $processedCount++;
                }
            }

            $totalUpdated = $specialCaseCount + $processedCount;

            // Log the results
            log_message('info', "Manual User Status Check completed. Special cases (active=4, age_group=4): {$specialCaseCount}, Aged out: {$aged31Count}, Inactive: {$inactiveCount}, Total: {$totalUpdated}");

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status check completed successfully',
                'results' => [
                    'total_checked' => count($activeUsers) + count($specialCaseUsers),
                    'special_cases' => $specialCaseCount,
                    'aged_out' => $aged31Count,
                    'inactive' => $inactiveCount,
                    'total_updated' => $totalUpdated
                ]
            ]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Error running status check: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Reactivate a user (set is_active back to 1)
     */
    /**
     * Reactivate a user (set is_active back to 1)
     */
    public function reactivateUser()
    {
        try {
            $userId = $this->request->getPost('user_id');

            if (!$userId) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'User ID is required'
                ]);
            }

            $user = $this->userModel->find($userId);
            if (!$user) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }

            // Update user status back to active (is_active = 1)
            $result = $this->userModel->update($userId, ['is_active' => 1]);

            if ($result) {
                log_message('info', "User reactivated: {$user['first_name']} {$user['last_name']} (ID: {$user['user_id']})");
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'User reactivated successfully'
                ]);
            } else {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Failed to reactivate user'
                ]);
            }

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Error reactivating user: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get reactivated users (recently reactivated from inactive status)
     */
    /**
     * Get deactivated users (is_active = 4)
     */
    public function getDeactivatedUsers()
    {
        try {
            $session = session();
            $userType = $session->get('user_type');
            $skBarangay = $session->get('sk_barangay'); // Get barangay directly from session like youthProfile
            
            // Get users who are deactivated (is_active = 4)
            $query = $this->userModel
                ->select('
                    user.id, user.user_id, user.first_name, user.last_name, user.middle_name, 
                    user.suffix, user.email, user.phone_number, user.birthdate, user.sex, 
                    user.is_active, user.last_login, user.created_at, user.updated_at, user.position,
                    address.barangay, address.zone_purok, address.municipality, address.province, address.region
                ')
                ->join('address', 'address.user_id = user.id', 'left')
                ->where('user.is_active', 4); // Only deactivated users

            // Filter by barangay for SK officials - only show users from the same barangay
            if ($userType === 'sk' && $skBarangay) {
                $query->where('address.barangay', $skBarangay);
            }

            // Apply filters from request
            $zoneFilter = $this->request->getGet('zone');
            $reasonFilter = $this->request->getGet('reason');
            
            if (!empty($zoneFilter)) {
                $query->like('address.zone_purok', $zoneFilter);
            }

            $deactivatedUsers = $query->findAll();

            // Process the data
            $today = new \DateTime();
            foreach ($deactivatedUsers as &$user) {
                // Calculate age
                if (!empty($user['birthdate'])) {
                    $birthDate = new \DateTime($user['birthdate']);
                    $user['age'] = $today->diff($birthDate)->y;
                } else {
                    $user['age'] = 0;
                }

                // Format last login
                if (!empty($user['last_login'])) {
                    $user['last_login_formatted'] = date('M j, Y g:i A', strtotime($user['last_login']));
                    $lastLogin = new \DateTime($user['last_login']);
                    $user['days_since_login'] = $today->diff($lastLogin)->days;
                } else {
                    $user['last_login_formatted'] = 'Never';
                    $user['days_since_login'] = null;
                }

                // Format deactivated date
                if (!empty($user['updated_at'])) {
                    $user['deactivated_date'] = date('M j, Y g:i A', strtotime($user['updated_at']));
                }

                // Get barangay name
                $user['barangay_name'] = BarangayHelper::getBarangayName($user['barangay']);

                // Set deactivation reason - for now all are manual deactivation
                $user['deactivation_reason'] = 'Manual Deactivation';
            }

            // Get available zones for filtering (distinct zones from current data)
            $availableZones = [];
            foreach ($deactivatedUsers as $user) {
                if (!empty($user['zone_purok']) && !in_array($user['zone_purok'], $availableZones)) {
                    $availableZones[] = $user['zone_purok'];
                }
            }
            sort($availableZones);

            return $this->response->setJSON([
                'success' => true,
                'users' => $deactivatedUsers,
                'available_zones' => $availableZones
            ]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Error fetching deactivated users: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get all verified users (active users with status = 2, is_active = 1 or 5)
     */
    public function getVerifiedUsers()
    {
        try {
            $session = session();
            $userType = $session->get('user_type');
            $skBarangay = $session->get('sk_barangay'); // Get barangay directly from session like youthProfile
            
            // Get all verified users (status = 2) who are active (is_active = 1)
            $query = $this->userModel
                ->select('
                    user.id, user.user_id, user.first_name, user.last_name, user.middle_name, 
                    user.suffix, user.email, user.phone_number, user.birthdate, user.sex, 
                    user.is_active, user.last_login, user.created_at, user.status, user.user_type, user.position,
                    address.barangay, address.zone_purok, address.municipality, address.province, address.region
                ')
                ->join('address', 'address.user_id = user.id', 'left')
                ->where('user.status', 2)  // Verified/approved users
                ->where('user.is_active', 1)  // Active users only
                ->groupBy('user.id'); // Prevent duplicates from JOIN

            // Filter by barangay for SK officials - only show users from the same barangay
            if ($userType === 'sk' && $skBarangay) {
                $query->where('address.barangay', $skBarangay);
            }

            // Apply filters from request
            $zoneFilter = $this->request->getGet('zone');
            $positionFilter = $this->request->getGet('user_type'); // This is actually position filter from frontend
            
            if (!empty($zoneFilter)) {
                $query->where('address.zone_purok', $zoneFilter);
            }
            
            if (!empty($positionFilter)) {
                // Filter by position (SK Chairman, Secretary, etc.)
                $query->where('user.position', $positionFilter);
            }

            $verifiedUsers = $query->findAll();

            // Debug information
            log_message('info', 'UserStatusController::getVerifiedUsers - Query executed');
            log_message('info', 'User type: ' . $userType);
            log_message('info', 'SK barangay: ' . ($skBarangay ?? 'null'));
            log_message('info', 'Zone filter: ' . ($zoneFilter ?? 'null'));
            log_message('info', 'Position filter: ' . ($positionFilter ?? 'null'));
            log_message('info', 'Total verified users found: ' . count($verifiedUsers));
            
            // Check for duplicates by user ID
            $userIds = array_column($verifiedUsers, 'id');
            $duplicates = array_diff_assoc($userIds, array_unique($userIds));
            if (!empty($duplicates)) {
                log_message('warning', 'Duplicate users found in result: ' . implode(', ', array_unique($duplicates)));
            }

            // Process the data
            foreach ($verifiedUsers as &$user) {
                // Calculate age
                if (!empty($user['birthdate'])) {
                    $birthDate = new \DateTime($user['birthdate']);
                    $today = new \DateTime();
                    $user['age'] = $today->diff($birthDate)->y;
                } else {
                    $user['age'] = 0;
                }

                // Format last login
                if (!empty($user['last_login'])) {
                    $user['last_login_formatted'] = date('M j, Y g:i A', strtotime($user['last_login']));
                    $lastLogin = new \DateTime($user['last_login']);
                    $today = new \DateTime();
                    $user['days_since_login'] = $today->diff($lastLogin)->days;
                } else {
                    $user['last_login_formatted'] = 'Never';
                    $user['days_since_login'] = null;
                }

                // Format created date
                if (!empty($user['created_at'])) {
                    $user['created_at_formatted'] = date('M j, Y g:i A', strtotime($user['created_at']));
                }

                // Get barangay name
                $user['barangay_name'] = BarangayHelper::getBarangayName($user['barangay']);

                // Get user type text
                $userTypes = [
                    1 => 'KK Member',
                    2 => 'SK Official', 
                    3 => 'Pederasyon Officer'
                ];
                $user['user_type_text'] = $userTypes[$user['user_type']] ?? 'Unknown';

                // Get position text for SK/Pederasyon users
                if ($user['user_type'] == 2 && !empty($user['position'])) {
                    $skPositions = [
                        1 => 'Chairman',
                        2 => 'Vice Chairman',
                        3 => 'Secretary',
                        4 => 'Treasurer',
                        5 => 'Member'
                    ];
                    $user['position_text'] = $skPositions[$user['position']] ?? 'Member';
                } else {
                    $user['position_text'] = '';
                }

                // Set status info
                if ($user['is_active'] == 1) {
                    $user['status_reason'] = 'Active';
                    $user['status_class'] = 'bg-green-100 text-green-800';
                } elseif ($user['is_active'] == 5) {
                    $user['status_reason'] = 'Reactivated';
                    $user['status_class'] = 'bg-blue-100 text-blue-800';
                } else {
                    $user['status_reason'] = 'Unknown';
                    $user['status_class'] = 'bg-gray-100 text-gray-800';
                }
            }

            // Get available zones for filtering (distinct zones from current data)
            $availableZones = [];
            foreach ($verifiedUsers as $user) {
                if (!empty($user['zone_purok']) && !in_array($user['zone_purok'], $availableZones)) {
                    $availableZones[] = $user['zone_purok'];
                }
            }
            sort($availableZones);

            return $this->response->setJSON([
                'success' => true,
                'users' => $verifiedUsers,
                'available_zones' => $availableZones
            ]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Error fetching verified users: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Deactivate or flag a user with a specific reason.
     * - aged_out => is_active = 2
     * - inactive_long => is_active = 3
     * - manual_deactivation (default) => is_active = 4
     */
    public function deactivateUser()
    {
        try {
            $userId = $this->request->getPost('user_id');
            $reason = $this->request->getPost('reason');

            if (!$userId || !$reason) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'User ID and reason are required'
                ]);
            }

            $user = $this->userModel->find($userId);
            if (!$user) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }

            // Map reason to is_active value
            switch ($reason) {
                case 'aged_out':
                    $isActiveValue = 2; // Overage (31+)
                    break;
                case 'inactive_long':
                    $isActiveValue = 3; // Inactive 1+ year
                    break;
                case 'manual_deactivation':
                default:
                    $isActiveValue = 4; // Manual deactivation
                    break;
            }

            // Update user status
            $result = $this->userModel->update($userId, ['is_active' => $isActiveValue]);

            if ($result) {
                log_message('info', "User deactivated: {$user['first_name']} {$user['last_name']} (ID: {$user['user_id']}) - Reason: {$reason}, is_active: {$isActiveValue}");
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'User status updated successfully'
                ]);
            } else {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Failed to deactivate user'
                ]);
            }

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Error deactivating user: ' . $e->getMessage()
            ]);
        }
    }

    public function userManagement()
    {
        $session = session();
        
        $data = [
            'title' => 'User Management',
            'user_id' => $session->get('user_id'),
            'username' => $session->get('username'),
            'sk_barangay' => $session->get('sk_barangay')
        ];
        
        return 
            $this->loadView('K-NECT/SK/template/header') .
            $this->loadView('K-NECT/SK/template/sidebar') .
            $this->loadView('K-NECT/SK/user_management', $data);
    }
}
