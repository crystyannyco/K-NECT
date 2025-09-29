<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserExtInfoModel;
use App\Models\AddressModel;
use App\Libraries\BarangayHelper;

use App\Libraries\DemographicsHelper;

class ProfileController extends BaseController
{
    protected $userModel;
    protected $userExtInfoModel; 
    protected $addressModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->userExtInfoModel = new UserExtInfoModel();
        $this->addressModel = new AddressModel();
    }

    /**
     * Get user profile data for any user type
     * @param string|null $userId - The permanent user_id from session
     * @return array|null - Array containing user data or null if not found
     */
    public function getUserProfileData($userId = null)
    {
        if (!$userId) {
            $session = session();
            $userId = $session->get('user_id');
        }

        if (!$userId) {
            return null;
        }

        // Get user by permanent user_id field
        $user = $this->userModel->where('user_id', $userId)->first();
        
        if (!$user) {
            return null;
        }

        // Get extended info using the database id
        $userExtInfo = $this->userExtInfoModel->where('user_id', $user['id'])->first();
        $address = $this->addressModel->where('user_id', $user['id'])->first();

        // Calculate age
        $age = '';
        if ($user['birthdate']) {
            $birthDate = new \DateTime($user['birthdate']);
            $today = new \DateTime();
            $age = $today->diff($birthDate)->y;
        }

        return [
            'user' => $user,
            'userExtInfo' => $userExtInfo,
            'address' => $address,
            'age' => $age,
            'user_id' => $userId
        ];
    }

    /**
     * Get field mappings for profile data display
     * @return array - Array of field mappings
     */
    public function getFieldMappings()
    {
        return [
            'civil_status' => DemographicsHelper::civilStatusMap(),
            'youth_classification' => DemographicsHelper::youthClassificationMap(),
            'age_group' => DemographicsHelper::youthAgeGroupMap(),
            'work_status' => DemographicsHelper::workStatusMap(),
            'educational_background' => DemographicsHelper::educationalBackgroundMap(),
            'how_many_times' => DemographicsHelper::howManyTimesMap(),
            'no_why' => DemographicsHelper::noWhyMap(),
            'sk_position' => [
                1 => 'SK Chairperson',
                2 => 'SK Kagawad', 
                3 => 'SK Secretary',
                4 => 'SK Treasurer'
            ],
            'pederasyon_position' => [
                5 => 'Pederasyon President',
                6 => 'Pederasyon Vice President',
                7 => 'Pederasyon Secretary', 
                8 => 'Pederasyon Treasurer',
                9 => 'Pederasyon Auditor',
                10 => 'Pederasyon Board Member'
            ]
        ];
    }

    /**
     * Get status information for display
     * @param int $status - Status code
     * @return array - Array containing status text and CSS class
     */
    public function getStatusInfo($status)
    {
        switch($status) {
            case 2:
                return [
                    'text' => 'Approved',
                    'class' => 'bg-green-100 text-green-800'
                ];
            case 3:
                return [
                    'text' => 'Rejected', 
                    'class' => 'bg-red-100 text-red-800'
                ];
            default:
                return [
                    'text' => 'Pending',
                    'class' => 'bg-yellow-100 text-yellow-800'
                ];
        }
    }

    /**
     * Format user's full name
     * @param array $user - User data array
     * @return string - Formatted full name
     */
    public function getFormattedName($user)
    {
        $name = esc($user['first_name']);
        if (!empty($user['middle_name'])) {
            $name .= ' ' . esc($user['middle_name']);
        }
        $name .= ' ' . esc($user['last_name']);
        if (!empty($user['suffix'])) {
            $name .= ', ' . esc($user['suffix']);
        }
        return $name;
    }

    /**
     * Get sex display text
     * @param string $sex - Sex code
     * @return string - Sex display text
     */
    public function getSexText($sex)
    {
        return $sex == '1' ? 'Male' : ($sex == '2' ? 'Female' : 'Not specified');
    }

    /**
     * Format date for display
     * @param string $date - Date string
     * @param string $format - Display format (default: 'F j, Y')
     * @return string - Formatted date or 'Not specified'
     */
    public function formatDate($date, $format = 'F j, Y')
    {
        return $date ? date($format, strtotime($date)) : 'Not specified';
    }

    /**
     * Format datetime for display
     * @param string $datetime - Datetime string
     * @return string - Formatted datetime or 'Not specified'
     */
    public function formatDateTime($datetime)
    {
        return $datetime ? date('F j, Y g:i A', strtotime($datetime)) : 'Not specified';
    }

    /**
     * Get all users with their extended information for member listing
     * @param string|null $barangayFilter - Filter by specific barangay
     * @param int|null $statusFilter - Filter by status (default: 2 for approved)
     * @return array - Array of users with calculated data
     */
    public function getAllUsersWithExtendedInfo($barangayFilter = null, $statusFilter = 2)
    {
        $query = $this->userModel
            ->select('
                user.id, user.status, user.last_name, user.first_name, user.middle_name, user.suffix, user.email, user.sex, user.birthdate, user.user_type, user.position, user.ped_position, user.sk_username, user.sk_password, user.ped_username, user.ped_password, user.user_id, address.barangay, address.municipality, address.province, address.region, address.zone_purok, user_ext_info.civil_status, user_ext_info.youth_classification, user_ext_info.age_group, user_ext_info.work_status, user_ext_info.educational_background, user_ext_info.sk_voter, user_ext_info.sk_election, user_ext_info.national_voter, user_ext_info.kk_assembly, user_ext_info.how_many_times, user_ext_info.no_why, user_ext_info.birth_certificate, user_ext_info.upload_id, `user_ext_info`.`upload_id-back` AS `upload_id-back`
            ')
            ->join('address', 'address.user_id = user.id', 'left')
            ->join('user_ext_info', 'user_ext_info.user_id = user.id', 'left');

        if ($barangayFilter) {
            $query->where('address.barangay', $barangayFilter);
        }

        $users = $query->findAll();

        // Process each user
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
            $u['sex_text'] = $this->getSexText($u['sex']);
            
            // Format barangay name
            $u['barangay_name'] = BarangayHelper::getBarangayName($u['barangay']);
            
            // Format zone/purok
            $u['zone_display'] = isset($u['zone_purok']) && !empty($u['zone_purok']) ? esc($u['zone_purok']) : '-';
        }
        unset($u);

        return $users;
    }

    /**
     * Process user data for member listing with position and status information
     * @param array $users - Array of user data
     * @param string $userType - Type of user (sk, pederasyon, kk)
     * @return array - Processed user array
     */
    public function processUsersForMemberListing($users, $userType = 'sk')
    {
        $fieldMappings = $this->getFieldMappings();
        
        foreach ($users as &$u) {
            // Process position information
            $position = isset($u['position']) ? (int)$u['position'] : 5;
            $u['position_int'] = $position;
            
            // Set position text based on user type
            if ($userType === 'sk') {
                $u['is_chairperson'] = ($position == 1);
                switch($position) {
                    case 1:
                        $u['position_text'] = 'SK Chairperson';
                        break;
                    case 2:
                        $u['position_text'] = 'SK Kagawad';
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
                
                // Set checkbox attributes for SK
                $u['checkbox_disabled'] = $u['is_chairperson'] ? 'disabled' : '';
                $u['checkbox_title'] = $u['is_chairperson'] ? 'title="SK Chairperson position cannot be changed"' : '';
                
            } elseif ($userType === 'pederasyon') {
                switch($position) {
                    case 5:
                        $u['position_text'] = 'Pederasyon President';
                        break;
                    case 6:
                        $u['position_text'] = 'Pederasyon Vice President';
                        break;
                    case 7:
                        $u['position_text'] = 'Pederasyon Secretary';
                        break;
                    case 8:
                        $u['position_text'] = 'Pederasyon Treasurer';
                        break;
                    case 9:
                        $u['position_text'] = 'Pederasyon Auditor';
                        break;
                    case 10:
                        $u['position_text'] = 'Pederasyon Board Member';
                        break;
                    default:
                        $u['position_text'] = 'Pederasyon Officer | SK Chairperson';
                }
            } else {
                $u['position_text'] = 'KK Member';
            }
            
            // User JSON for modals
            $u['user_json'] = htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8');
        }
        unset($u);

        return $users;
    }

    /**
     * Get profile picture for a specific user
     * @param string $userId - The permanent user_id 
     * @return array - Array containing profile picture info
     */
    public function getProfilePicture($userId = null)
    {
        if (!$userId) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false, 
                'message' => 'User ID is required'
            ]);
        }

        // Get user by permanent user_id field
        $user = $this->userModel->where('user_id', $userId)->first();
        
        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false, 
                'message' => 'User not found'
            ]);
        }

        // Get extended info using the database id
        $userExtInfo = $this->userExtInfoModel->where('user_id', $user['id'])->first();
        
        $profilePicture = null;
        $hasProfilePicture = false;
        
        if ($userExtInfo && !empty($userExtInfo['profile_picture'])) {
            $stored = $userExtInfo['profile_picture'];
            if (str_contains($stored, '/')) {
                // stored as relative path already
                $profilePicture = $stored;
                $profilePicturePath = FCPATH . ltrim($stored, '/');
            } else {
                // legacy filename; check new and legacy directories
                if (is_file(FCPATH . 'uploads/profile/' . $stored)) {
                    $profilePicture = 'uploads/profile/' . $stored;
                    $profilePicturePath = FCPATH . $profilePicture;
                } else {
                    $profilePicture = $stored;
                    $profilePicturePath = FCPATH . 'uploads/profile_pictures/' . $stored;
                }
            }
            $hasProfilePicture = file_exists($profilePicturePath);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'user_id' => $userId,
                'profile_picture' => $profilePicture,
                'has_profile_picture' => $hasProfilePicture,
                'profile_picture_url' => $hasProfilePicture ? base_url(ltrim($profilePicture, '/')) : null,
                'full_name' => trim($user['first_name'] . ' ' . $user['last_name'])
            ]
        ]);
    }
}
