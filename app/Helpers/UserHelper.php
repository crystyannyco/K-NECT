<?php

namespace App\Libraries;

use App\Models\UserModel;
use App\Models\UserExtInfoModel;
use App\Models\AddressModel;
use App\Libraries\BarangayHelper;

class UserHelper
{
    public static function generateUnique6DigitId()
    {
        $userModel = new UserModel();
        do {
            $id = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $exists = $userModel->where('user_id', $id)->first();
        } while ($exists);
        return $id;
    }

    public static function getCurrentUserProfile()
    {
        $session = session();
        $userId = $session->get('user_id');
        $username = $session->get('username');
        $sessionUserType = $session->get('user_type'); // Get user type from session
        
        if (!$userId || !$username) {
            return null;
        }
        
        $userModel = new UserModel();
        $userExtInfoModel = new UserExtInfoModel();
        $addressModel = new AddressModel();
        
        // Find user by user_id (permanent ID) first
        $user = $userModel->where('user_id', $userId)->first();
        
        if (!$user) {
            return null;
        }
        
        // Determine user type based on session data first, then fall back to username matching
        $userType = $sessionUserType ?? 'kk'; // Default to KK member
        $userTypeText = 'KK Member';
        $actualUserType = 1; // Default user_type value
        
        if ($userType === 'kk' || $user['username'] === $username) {
            $userType = 'kk';
            $userTypeText = 'KK Member';
            $actualUserType = 1;
        } elseif ($userType === 'sk' || $user['sk_username'] === $username) {
            $userType = 'sk';
            $userTypeText = 'SK Official';
            $actualUserType = 2;
        } elseif ($userType === 'pederasyon' || $user['ped_username'] === $username) {
            $userType = 'pederasyon';
            $userTypeText = 'Pederasyon Officer';
            $actualUserType = 3;
        }
        
        // Get extended info and address
        $extInfo = $userExtInfoModel->where('user_id', $user['id'])->first();
        $address = $addressModel->where('user_id', $user['id'])->first();
        
        // Build full name with middle initial
        $middleInitial = !empty($user['middle_name']) ? strtoupper(substr($user['middle_name'], 0, 1)) . '.' : '';
        $fullName = trim($user['first_name'] . ' ' . $middleInitial . ' ' . $user['last_name']);
        if (!empty($user['suffix'])) {
            $fullName .= ' ' . $user['suffix'];
        }
        
        // Determine position text based on user type
        $positionText = '';
        if ($userType === 'sk') {
            // SK Official positions
            $positions = [
                1 => 'Chairman', 
                2 => 'Secretary',
                3 => 'Treasurer'
            ];
            $positionText = $positions[$user['position']] ?? 'Member';
        } elseif ($userType === 'pederasyon') {
            // Pederasyon Officer positions
            $pedPositions = [
                1 => 'President',
                2 => 'Vice President', 
                3 => 'Secretary',
                4 => 'Treasurer',
                5 => 'Auditor',
                6 => 'Public Information Officer',
                7 => 'Sergeant at Arms'
            ];
            
            // Check if user has a pederasyon position assigned
            if (!empty($user['ped_position']) && isset($pedPositions[$user['ped_position']])) {
                $positionText = $pedPositions[$user['ped_position']];
            } else {
                $positionText = '';
            }
        }
        // KK Members don't have position text
        
        // Profile picture URL (robust across storage variants) with default fallback
        $defaultAvatar = base_url('assets/images/default-avatar.svg');
        $profilePicture = $defaultAvatar;
        $stored = $extInfo['profile_picture'] ?? '';
        if (!empty($stored)) {
            // If already an absolute URL, use as-is
            if (str_starts_with($stored, 'http://') || str_starts_with($stored, 'https://')) {
                $profilePicture = $stored;
            } else {
                // Normalize stored path or filename
                $pathCandidate = null;
                // If stored contains a slash, treat as relative path under public (FCPATH)
                if (strpos($stored, '/') !== false) {
                    $relative = ltrim($stored, '/');
                    $fsPath = FCPATH . $relative;
                    if (is_file($fsPath)) {
                        $pathCandidate = $relative;
                    }
                } else {
                    // Try legacy and current directories
                    $candidates = [
                        'uploads/profile/' . $stored,           // legacy path
                        'uploads/profile_pictures/' . $stored,   // current path
                    ];
                    foreach ($candidates as $rel) {
                        if (is_file(FCPATH . $rel)) {
                            $pathCandidate = $rel;
                            break;
                        }
                    }
                }

                if ($pathCandidate !== null) {
                    $profilePicture = base_url($pathCandidate);
                }
            }
        }
        
        // Get barangay name
        $barangayCode = $address['barangay'] ?? '';
        $barangayName = '';
        if (!empty($barangayCode)) {
            $barangayName = BarangayHelper::getBarangayName($barangayCode);
        }
        
        return [
            'id' => $user['id'],
            'user_id' => $user['user_id'],
            'full_name' => $fullName,
            'first_name' => $user['first_name'],
            'middle_name' => $user['middle_name'],
            'last_name' => $user['last_name'],
            'suffix' => $user['suffix'],
            'email' => $user['email'],
            'user_type' => $actualUserType,
            'user_type_text' => $userTypeText,
            'user_role' => $userType, // 'kk', 'sk', or 'pederasyon'
            'position' => $user['position'],
            'ped_position' => $user['ped_position'] ?? null,
            'position_text' => $positionText,
            'profile_picture' => $profilePicture,
            'username' => $username,
            'barangay' => $barangayCode,
            'barangay_name' => $barangayName,
        ];
    }

    public static function generateSKUsername($firstName, $lastName)
    {
        return 'SK_' . ucfirst(str_replace(' ', '', $firstName)) . ucfirst(str_replace(' ', '', $lastName));
    }
    
    public static function generateSecretaryUsername($firstName, $lastName)
    {
        return 'SEC_' . ucfirst(str_replace(' ', '', $firstName)) . ucfirst(str_replace(' ', '', $lastName));
    }
    
    public static function generatePEDUsername($firstName, $lastName)
    {
        return 'PED_' . ucfirst(str_replace(' ', '', $firstName)) . ucfirst(str_replace(' ', '', $lastName));
    }

    public static function generatePassword($length = 8)
    {
        return bin2hex(random_bytes($length/2));
    }

    public static function generateUniqueUsername($prefix = 'user')
    {
        return $prefix . '_' . bin2hex(random_bytes(3)) . time();
    }
}
