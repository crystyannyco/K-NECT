<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserExtInfoModel;
use App\Models\AddressModel;
use App\Libraries\UserHelper;

class MemberController extends BaseController
{
    public function getUserInfo()
    {
        $userId = $this->request->getPost('user_id');
        if (!$userId) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Missing user_id']);
        }
        $userModel = new UserModel();
        $user = $userModel
            ->select('
                user.id, user.user_id, user.status, user.last_name, user.first_name, user.middle_name, user.suffix, user.email, user.sex, user.birthdate, user.user_type, user.position, user.ped_position,
                address.barangay, address.municipality, address.province, address.region, address.zone_purok,
                user_ext_info.civil_status, user_ext_info.youth_classification, user_ext_info.age_group, user_ext_info.work_status, user_ext_info.educational_background,
                user_ext_info.sk_voter, user_ext_info.sk_election, user_ext_info.national_voter, user_ext_info.kk_assembly, user_ext_info.how_many_times, user_ext_info.no_why,
                user_ext_info.birth_certificate, user_ext_info.upload_id, user_ext_info.profile_picture
            ')
            ->join('address', 'address.user_id = user.id', 'left')
            ->join('user_ext_info', 'user_ext_info.user_id = user.id', 'left')
            ->where('user.id', $userId)
            ->first();

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'User not found']);
        }

        // Calculate age
        $user['age'] = $user['birthdate'] ? (date_diff(date_create($user['birthdate']), date_create('today'))->y) : '';

        return $this->response->setJSON(['success' => true, 'user' => $user]);
    }

    public function updateUserType()
    {
        $userId = $this->request->getPost('user_id');
        $userType = $this->request->getPost('user_type');
        if (!$userId || !$userType) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Missing user_id or user_type']);
        }
        $userModel = new UserModel();
        $user = $userModel->find($userId);
        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'User not found']);
        }

        $updateData = ['user_type' => $userType];

        if ($userType == 2) { // SK Chairperson
            // Only generate SK credentials if missing
            if (empty($user['sk_username']) || empty($user['sk_password'])) {
                $updateData['sk_username'] = UserHelper::generateSKUsername($user['first_name'], $user['last_name']);
                $updateData['sk_password'] = UserHelper::generatePassword(8);
            }
        } elseif ($userType == 3) { // Pederasyon Officer
            $wasSK = isset($user['user_type']) && (int)$user['user_type'] === 2;

            // Ensure PED credentials exist
            if (empty($user['ped_username']) || empty($user['ped_password'])) {
                $updateData['ped_username'] = UserHelper::generatePEDUsername($user['first_name'], $user['last_name']);
                $updateData['ped_password'] = UserHelper::generatePassword(8);
            }

            // If the user was not SK before, ensure SK credentials and set position = 1 (Chairperson)
            if (!$wasSK) {
                if (empty($user['sk_username']) || empty($user['sk_password'])) {
                    $updateData['sk_username'] = UserHelper::generateSKUsername($user['first_name'], $user['last_name']);
                    $updateData['sk_password'] = UserHelper::generatePassword(8);
                }
                $updateData['position'] = 1; // Promote to SK Chairperson to align with Pederasyon composition
            }
        }
        // If KK Member (userType == 1), do not change username/password

        $result = $userModel->update($userId, $updateData);
        if ($result) {
            return $this->response->setJSON(['success' => true, 'message' => 'User type updated successfully']);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Failed to update user type']);
        }
    }

    public function updateUserPosition()
    {
        $userId = $this->request->getPost('user_id');
        $position = $this->request->getPost('position');
        
        if (!$userId || !$position) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Missing user_id or position']);
        }
        
        // Get current user's database ID from session
        $session = session();
        $currentUserPermanentId = $session->get('user_id');
        $userModel = new UserModel();
        $currentUser = $userModel->where('user_id', $currentUserPermanentId)->first();
        $currentUserId = $currentUser ? $currentUser['id'] : null;
        
        $user = $userModel->find($userId);
        
        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'User not found']);
        }

        // Check restrictions: current user or SK Chairman (position 1)
        $isCurrentUser = ($user['id'] == $currentUserId);
        $isChairman = ($user['position'] == 1);
        
        if ($isCurrentUser) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You cannot change your own position']);
        }
        
        if ($isChairman) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'SK Chairman position cannot be changed']);
        }

        $updateData = ['position' => $position];

        // Update user_type based on position
        if ($position == 1) { // SK Chairman
            $updateData['user_type'] = 2; // SK Chairman is considered SK Official
            
            // Generate SK credentials if missing
            if (empty($user['sk_username']) || empty($user['sk_password'])) {
                $updateData['sk_username'] = UserHelper::generateSKUsername($user['first_name'], $user['last_name']);
                $updateData['sk_password'] = UserHelper::generatePassword(8);
            }
        } elseif ($position == 2) { // SK Kagawad
            $updateData['user_type'] = 2;
            
            // Generate SK credentials if missing
            if (empty($user['sk_username']) || empty($user['sk_password'])) {
                $updateData['sk_username'] = UserHelper::generateSKUsername($user['first_name'], $user['last_name']);
                $updateData['sk_password'] = UserHelper::generatePassword(8);
            }
        } elseif ($position == 3) { // Secretary
            $updateData['user_type'] = 2;
            
            // Generate Secretary credentials with SEC_ prefix if missing
            if (empty($user['sk_username']) || empty($user['sk_password'])) {
                $updateData['sk_username'] = UserHelper::generateSecretaryUsername($user['first_name'], $user['last_name']);
                $updateData['sk_password'] = UserHelper::generatePassword(8);
            }
        } elseif ($position == 4) { // Treasurer (Pederasyon officer)
            $updateData['user_type'] = 3;
            
            // Generate PED credentials if missing
            if (empty($user['ped_username']) || empty($user['ped_password'])) {
                $updateData['ped_username'] = UserHelper::generatePEDUsername($user['first_name'], $user['last_name']);
                $updateData['ped_password'] = UserHelper::generatePassword(8);
            }
        } elseif ($position == 5) { // KK Member
            $updateData['user_type'] = 1;
        }

        $result = $userModel->update($userId, $updateData);
        
        if ($result) {
            return $this->response->setJSON(['success' => true, 'message' => 'User position updated successfully']);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Failed to update user position']);
        }
    }

    private function generateUniqueUsername($prefix = 'user') {
        return $prefix . '_' . bin2hex(random_bytes(3)) . time();
    }
    
    private function generatePassword($length = 8) {
        return bin2hex(random_bytes($length/2));
    }

    private function generateSKUsername($firstName, $lastName) {
        return 'SK_' . ucfirst(str_replace(' ', '', $firstName)) . ucfirst(str_replace(' ', '', $lastName));
    }
    
    private function generatePEDUsername($firstName, $lastName) {
        return 'PED_' . ucfirst(str_replace(' ', '', $firstName)) . ucfirst(str_replace(' ', '', $lastName));
    }

    private function generateUnique6DigitId() {
        $userModel = new UserModel();
        do {
            $id = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $exists = $userModel->where('user_id', $id)->first();
        } while ($exists);
        return $id;
    }

    public function bulkUpdateUserType()
    {
        $userIds = $this->request->getPost('user_ids');
        $userType = $this->request->getPost('user_type');
        if (!$userIds || !$userType || !is_array($userIds)) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Missing user_ids or user_type']);
        }
        $userModel = new UserModel();
        $updated = 0;
        foreach ($userIds as $id) {
            $user = $userModel->find($id);
            if ($user && $user['status'] == 2) {
                $updateData = ['user_type' => $userType];

                if ($userType == 2) { // SK Chairperson
                    // Only generate SK credentials if missing
                    if (empty($user['sk_username']) || empty($user['sk_password'])) {
                        $updateData['sk_username'] = UserHelper::generateSKUsername($user['first_name'], $user['last_name']);
                        $updateData['sk_password'] = UserHelper::generatePassword(8);
                    }
                } elseif ($userType == 3) { // Pederasyon Officer
                    $wasSK = isset($user['user_type']) && (int)$user['user_type'] === 2;
                    // Ensure PED credentials exist
                    if (empty($user['ped_username']) || empty($user['ped_password'])) {
                        $updateData['ped_username'] = UserHelper::generatePEDUsername($user['first_name'], $user['last_name']);
                        $updateData['ped_password'] = UserHelper::generatePassword(8);
                    }
                    // If not SK previously, ensure SK creds and set position = 1
                    if (!$wasSK) {
                        if (empty($user['sk_username']) || empty($user['sk_password'])) {
                            $updateData['sk_username'] = UserHelper::generateSKUsername($user['first_name'], $user['last_name']);
                            $updateData['sk_password'] = UserHelper::generatePassword(8);
                        }
                        $updateData['position'] = 1;
                    }
                }
                // If KK Member (userType == 1), do not change username/password

                $userModel->update($id, $updateData);
                $updated++;
            }
        }
        if ($updated > 0) {
            return $this->response->setJSON(['success' => true, 'message' => 'User positions updated successfully']);
        } else {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'No users updated (only accepted users can be changed)']);
        }
    }

    public function bulkUpdateUserPosition()
    {
        $userIds = $this->request->getPost('user_ids');
        $position = $this->request->getPost('position');
        
        if (!$userIds || !$position || !is_array($userIds)) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Missing user_ids or position']);
        }
        
        // Prevent bulk assignment of SK Chairman position
        if ($position == 1) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'SK Chairman position cannot be assigned in bulk']);
        }
        
        // Get current user's database ID from session
        $session = session();
        $currentUserPermanentId = $session->get('user_id');
        $userModel = new UserModel();
        $currentUser = $userModel->where('user_id', $currentUserPermanentId)->first();
        $currentUserId = $currentUser ? $currentUser['id'] : null;
        
        $updated = 0;
        $restricted = [];
        
        foreach ($userIds as $id) {
            $user = $userModel->find($id);
            
            if ($user) {
                // Check restrictions: current user or SK Chairman (position 1)
                $isCurrentUser = ($user['id'] == $currentUserId);
                $isChairman = ($user['position'] == 1);
                
                if ($isCurrentUser || $isChairman) {
                    $reason = $isCurrentUser ? 'current user' : 'SK Chairman';
                    $restricted[] = $user['first_name'] . ' ' . $user['last_name'] . ' (' . $reason . ')';
                    continue;
                }
                
                $updateData = ['position' => $position];

                // Update user_type based on position
                if ($position == 2) { // SK Kagawad
                    $updateData['user_type'] = 2;
                    
                    // Generate SK credentials if missing
                    if (empty($user['sk_username']) || empty($user['sk_password'])) {
                        $updateData['sk_username'] = UserHelper::generateSKUsername($user['first_name'], $user['last_name']);
                        $updateData['sk_password'] = UserHelper::generatePassword(8);
                    }
                } elseif ($position == 3) { // Secretary
                    $updateData['user_type'] = 2;
                    
                    // Generate Secretary credentials with SEC_ prefix if missing
                    if (empty($user['sk_username']) || empty($user['sk_password'])) {
                        $updateData['sk_username'] = UserHelper::generateSecretaryUsername($user['first_name'], $user['last_name']);
                        $updateData['sk_password'] = UserHelper::generatePassword(8);
                    }
                } elseif ($position == 4) { // Treasurer (Pederasyon officer)
                    $updateData['user_type'] = 3;
                    
                    // Generate PED credentials if missing
                    if (empty($user['ped_username']) || empty($user['ped_password'])) {
                        $updateData['ped_username'] = UserHelper::generatePEDUsername($user['first_name'], $user['last_name']);
                        $updateData['ped_password'] = UserHelper::generatePassword(8);
                    }
                } elseif ($position == 5) { // KK Member
                    $updateData['user_type'] = 1;
                }

                $userModel->update($id, $updateData);
                $updated++;
            }
        }
        
        // Prepare response message
        $message = '';
        if ($updated > 0) {
            $message = "Updated {$updated} user positions successfully";
        }
        
        if (!empty($restricted)) {
            $restrictedMessage = "Could not update " . implode(', ', $restricted);
            $message = $message ? $message . '. ' . $restrictedMessage : $restrictedMessage;
        }
        
        if ($updated > 0) {
            return $this->response->setJSON(['success' => true, 'message' => $message]);
        } else {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => $message ?: 'No users found to update']);
        }
    }

}