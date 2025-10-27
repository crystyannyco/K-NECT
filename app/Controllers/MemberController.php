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
                user_ext_info.birth_certificate, user_ext_info.upload_id, `user_ext_info`.`upload_id-back` AS `upload_id-back`, user_ext_info.profile_picture
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
        // Accept either numeric DB ID or permanent user_id string
        $user = $userModel->find($userId);
        if (!$user) {
            $user = $userModel->where('user.user_id', $userId)->first();
            // If found by user_id, switch to DB id for updates
            if ($user) {
                $userId = $user['id'];
            }
        }
        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'User not found']);
        }

        // System validation: must always keep at least one Pederasyon (user_type = 3)
        $newTypeInt = (int) $userType;
        $currentTypeInt = (int) ($user['user_type'] ?? 1);
        if ($currentTypeInt === 3 && $newTypeInt !== 3) {
            $pedCount = (int) $userModel->where('user_type', 3)->countAllResults();
            if ($pedCount <= 1) {
                return $this->response->setStatusCode(409)->setJSON([
                    'success' => false,
                    'message' => 'Cannot remove the last Pederasyon user. The system must always have at least one Pederasyon user.'
                ]);
            }
        }

        $updateData = ['user_type' => (int)$userType];

        // Position handling rules based on user type transitions
        $oldType = (int) ($user['user_type'] ?? 1);
        $newType = (int) $userType;
        
        if ($oldType === 3 && $newType === 2) {
            // From Pederasyon → SK: ped_position must be updated to NULL
            $updateData['ped_position'] = null;
        } elseif (($oldType === 3 || $oldType === 2) && $newType === 1) {
            // From Pederasyon or SK → KK: Both position and ped_position must be updated to NULL
            $updateData['position'] = null;
            $updateData['ped_position'] = null;
        } elseif ($oldType === 1 && $newType === 2) {
            // From KK → SK: position must be updated to 1
            $updateData['position'] = 1;
        } elseif ($oldType === 1 && $newType === 3) {
            // From KK → Pederasyon: position must be updated to 1
            $updateData['position'] = 1;
        }

        // Enforce single SK Chairperson per barangay when setting to SK (type 2)
        if ((int)$userType === 2) {
            $addressModel = new AddressModel();
            $address = $addressModel->where('user_id', $user['id'])->first();
            $barangay = $address['barangay'] ?? null;
            if ($barangay !== null && $barangay !== '') {
                $existingChair = $userModel
                    ->select('user.id')
                    ->join('address', 'address.user_id = user.id', 'left')
                    ->where('user.status', 2) // approved
                    ->where('user.user_type', 2) // SK Chairperson
                    ->where('address.barangay', $barangay)
                    ->where('user.id !=', $user['id'])
                    ->first();
                if ($existingChair) {
                    return $this->response->setStatusCode(409)->setJSON([
                        'success' => false,
                        'message' => 'This barangay already has an SK Chairperson. Only one is allowed per barangay.'
                    ]);
                }
            }
            // Ensure position aligns to Chairperson (position = 1)
            $updateData['position'] = 1;
        }

        // When changing to SK Chairperson (user_type = 2), automatically verify and generate credentials
        if ($newType === 2) {
            // Auto-verify the user (set status to 2 - Accepted)
            $updateData['status'] = 2;
            
            // Generate USER_ID if missing
            if (empty($user['user_id'])) {
                $tries = 0;
                do {
                    $newId = UserHelper::generateYearPrefixedUserId();
                    $exists = $userModel->where('user_id', $newId)->first();
                    $tries++;
                } while ($exists && $tries < 5);
                if ($exists) {
                    return $this->response->setStatusCode(500)->setJSON([
                        'success' => false,
                        'message' => 'Failed to generate a unique USER ID. Please try again.'
                    ]);
                }
                $updateData['user_id'] = $newId;
            }
            
            // Generate SK credentials if missing
            if (empty($user['sk_username']) || empty($user['sk_password'])) {
                $updateData['sk_username'] = UserHelper::generateSKUsername($user['first_name'], $user['last_name']);
                $updateData['sk_password'] = UserHelper::generatePassword(8);
            }
            
            // If downgrading from PED -> SK, clear PED credentials
            if ($oldType === 3) {
                $updateData['ped_username'] = null;
                $updateData['ped_password'] = null;
            }
        } elseif ($newType === 3) { // Pederasyon Officer
            // Auto-verify for Pederasyon promotions
            if ((int)($user['status'] ?? 0) === 1) {
                $updateData['status'] = 2;
                if (empty($user['user_id'])) {
                    $tries = 0;
                    do {
                        $newId = UserHelper::generateYearPrefixedUserId();
                        $exists = $userModel->where('user_id', $newId)->first();
                        $tries++;
                    } while ($exists && $tries < 5);
                    if ($exists) {
                        return $this->response->setStatusCode(500)->setJSON([
                            'success' => false,
                            'message' => 'Failed to generate a unique USER ID. Please try again.'
                        ]);
                    }
                    $updateData['user_id'] = $newId;
                }
            }
            
            $wasSK = $oldType === 2;

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
        } elseif ($newType === 1) { // KK Member
            // Downgrades to KK should nullify credentials accordingly
            if ($oldType === 3) { // PED -> KK: clear both SK and PED creds
                $updateData['ped_username'] = null;
                $updateData['ped_password'] = null;
                $updateData['sk_username'] = null;
                $updateData['sk_password'] = null;
            } elseif ($oldType === 2) { // SK -> KK: clear SK creds
                $updateData['sk_username'] = null;
                $updateData['sk_password'] = null;
            }
        }

        $result = $userModel->update($userId, $updateData);
        if ($result) {
            return $this->response->setJSON(['success' => true, 'message' => 'User type updated successfully']);
        } else {
            $errors = method_exists($userModel, 'errors') ? $userModel->errors() : [];
            $msg = 'Failed to update user type';
            if (!empty($errors)) {
                $msg .= ': ' . implode('; ', array_filter(array_values($errors)));
            }
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => $msg, 'errors' => $errors]);
        }
    }

    public function updateUserPosition()
    {
        $userId = $this->request->getPost('user_id');
        $position = $this->request->getPost('position');
        
        if (!$userId || !$position) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Missing user_id or position']);
        }
        
        // Validate position value
        if (!in_array($position, ['1', '2', '3', '4', '5'])) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Invalid position value']);
        }
        
        $userModel = new UserModel();
        
        try {
            // Get current user's database ID from session
            $session = session();
            $currentUserPermanentId = $session->get('user_id');
            $currentUser = $userModel->where('user_id', $currentUserPermanentId)->first();
            $currentUserId = $currentUser ? $currentUser['id'] : null;
            
            $user = $userModel->find($userId);
            
            if (!$user) {
                return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'User not found']);
            }

            // Check restrictions: current user or SK Chairperson (position 1)
            $isCurrentUser = ($user['id'] == $currentUserId);
            $isChairperson = ($user['position'] == 1);
            
            if ($isCurrentUser) {
                return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You cannot change your own position']);
            }
            
            if ($isChairperson) {
                return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'SK Chairperson position cannot be changed']);
            }

            // Check position limits per barangay - ensure only 1 person per position per barangay (except positions 4 and 5)
            // Position 4 (SK Councilor) allows up to 7 people per barangay
            // Position 5 (KK Member) allows unlimited people per barangay
            if ($position != 5 && $position != 4) {
                // Get user's barangay
                $addressModel = new AddressModel();
                $userAddress = $addressModel->where('user_id', $user['id'])->first();
                $userBarangay = $userAddress['barangay'] ?? null;
                
                if ($userBarangay !== null) {
                    // Check if position already exists in the same barangay
                    $existingUserWithPosition = $userModel
                        ->select('user.id, user.first_name, user.last_name, user.position')
                        ->join('address', 'address.user_id = user.id', 'inner')
                        ->where('user.position', $position)
                        ->where('address.barangay', $userBarangay)
                        ->where('user.id !=', $userId)
                        ->where('user.status', 2) // Only check approved users
                        ->first();
                    
                    if ($existingUserWithPosition) {
                        $positionNames = [
                            1 => 'SK Chairperson',
                            2 => 'SK Secretary', 
                            3 => 'SK Treasurer',
                            4 => 'SK Councilor',
                            5 => 'KK Member'
                        ];
                        
                        $positionName = $positionNames[$position] ?? 'Unknown Position';
                        $currentHolderName = $existingUserWithPosition['first_name'] . ' ' . $existingUserWithPosition['last_name'];
                        
                        return $this->response->setStatusCode(400)->setJSON([
                            'success' => false, 
                            'message' => "Position '{$positionName}' is already occupied by {$currentHolderName} in this barangay. Only one person can hold this position per barangay."
                        ]);
                    }
                }
            } elseif ($position == 4) {
                // Special validation for SK Councilor - maximum 7 per barangay
                $addressModel = new AddressModel();
                $userAddress = $addressModel->where('user_id', $user['id'])->first();
                $userBarangay = $userAddress['barangay'] ?? null;
                
                if ($userBarangay !== null) {
                    $councilorsCount = $userModel
                        ->select('user.id')
                        ->join('address', 'address.user_id = user.id', 'inner')
                        ->where('user.position', 4) // SK Councilor
                        ->where('address.barangay', $userBarangay)
                        ->where('user.id !=', $userId)
                        ->where('user.status', 2) // Only check approved users
                        ->countAllResults();
                    
                    if ($councilorsCount >= 7) {
                        return $this->response->setStatusCode(400)->setJSON([
                            'success' => false, 
                            'message' => "Maximum of 7 SK Councilors allowed per barangay. This barangay already has {$councilorsCount} SK Councilors."
                        ]);
                    }
                }
            }

            $updateData = ['position' => (int)$position];

            // Update user_type based on position
            if ($position == 1) { // SK Chairperson
                $updateData['user_type'] = 2; // SK Chairperson is considered SK Official
                
                // Generate SK credentials if missing
                if (empty($user['sk_username']) || empty($user['sk_password'])) {
                    $updateData['sk_username'] = UserHelper::generateSKUsername($user['first_name'], $user['last_name']);
                    $updateData['sk_password'] = UserHelper::generatePassword(8);
                }
            } elseif ($position == 2) { // SK Secretary
                $updateData['user_type'] = 2;
                
                // Generate Secretary credentials with SEC_ prefix if missing
                if (empty($user['sk_username']) || empty($user['sk_password'])) {
                    $updateData['sk_username'] = UserHelper::generateSecretaryUsername($user['first_name'], $user['last_name']);
                    $updateData['sk_password'] = UserHelper::generatePassword(8);
                }
            } elseif ($position == 3) { // SK Treasurer
                $updateData['user_type'] = 2;
                
                // Generate SK credentials if missing
                if (empty($user['sk_username']) || empty($user['sk_password'])) {
                    $updateData['sk_username'] = UserHelper::generateSKUsername($user['first_name'], $user['last_name']);
                    $updateData['sk_password'] = UserHelper::generatePassword(8);
                }
            } elseif ($position == 4) { // SK Councilor
                $updateData['user_type'] = 2;
                
                // Generate SK credentials if missing
                if (empty($user['sk_username']) || empty($user['sk_password'])) {
                    $updateData['sk_username'] = UserHelper::generateSKUsername($user['first_name'], $user['last_name']);
                    $updateData['sk_password'] = UserHelper::generatePassword(8);
                }
            } elseif ($position == 5) { // KK Member
                $updateData['user_type'] = 1;
                // When selecting "KK Member", clear SK credentials
                $updateData['sk_username'] = null;
                $updateData['sk_password'] = null;
                $updateData['ped_position'] = null;
            }

            $result = $userModel->update($userId, $updateData);
            
            if ($result) {
                return $this->response->setJSON(['success' => true, 'message' => 'User position updated successfully']);
            } else {
                $errors = $userModel->errors();
                $errorMsg = 'Failed to update user position';
                if (!empty($errors)) {
                    $errorMsg .= ': ' . implode(', ', array_values($errors));
                }
                return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => $errorMsg]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error updating user position: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Database error occurred while updating position']);
        }
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
        $errors = [];

        // System validation guard for last Pederasyon in bulk: ensure at least one remains
        $newTypeInt = (int)$userType;
        $remainingPedCount = (int) $userModel->where('user_type', 3)->countAllResults();

        foreach ($userIds as $id) {
            $user = $userModel->find($id);
            if (!$user) { continue; }

            // If converting a Pederasyon to a non-Pederasyon and it would be the last one, skip and record error
            if ((int)($user['user_type'] ?? 1) === 3 && $newTypeInt !== 3) {
                if ($remainingPedCount <= 1) {
                    $errors[] = "Cannot remove the last Pederasyon user (ID {$id}). At least one must remain.";
                    continue;
                }
            }

            $updateData = ['user_type' => (int)$userType];

            // Apply position handling rules based on user type transitions
            $currentUserType = (int)($user['user_type'] ?? 1);
            
            if ($currentUserType == 3 && $newTypeInt == 2) {
                // From Pederasyon → SK: ped_position must be updated to NULL
                $updateData['ped_position'] = null;
            } elseif (($currentUserType == 3 || $currentUserType == 2) && $newTypeInt == 1) {
                // From Pederasyon or SK → KK: Both position and ped_position must be updated to NULL
                $updateData['position'] = null;
                $updateData['ped_position'] = null;
            } elseif ($currentUserType == 1 && $newTypeInt == 2) {
                // From KK → SK: position must be updated to 1
                $updateData['position'] = 1;
            } elseif ($currentUserType == 1 && $newTypeInt == 3) {
                // From KK → Pederasyon: position must be updated to 1
                $updateData['position'] = 1;
            }

            // Enforce single SK Chairperson per barangay
            if ((int)$userType === 2) {
                $addressModel = new AddressModel();
                $address = $addressModel->where('user_id', $user['id'])->first();
                $barangay = $address['barangay'] ?? null;
                if ($barangay !== null && $barangay !== '') {
                    $existingChair = $userModel
                        ->select('user.id')
                        ->join('address', 'address.user_id = user.id', 'left')
                        ->where('user.status', 2)
                        ->where('user.user_type', 2)
                        ->where('address.barangay', $barangay)
                        ->where('user.id !=', $user['id'])
                        ->first();
                    if ($existingChair) {
                        $errors[] = "Barangay already has SK Chairperson for user {$user['id']}";
                        continue;
                    }
                }
                // Position is handled by position transition rules above
            }

            // Auto-accept pending users and generate USER_ID when promoting to SK/PED
            $isPromotionType = ((int)$userType === 2 || (int)$userType === 3);
            if ($isPromotionType && (int)($user['status'] ?? 0) === 1) {
                $updateData['status'] = 2;
                if (empty($user['user_id'])) {
                    $tries = 0;
                    do {
                        $newId = UserHelper::generateYearPrefixedUserId();
                        $exists = $userModel->where('user_id', $newId)->first();
                        $tries++;
                    } while ($exists && $tries < 5);
                    if ($exists) {
                        $errors[] = "Failed to generate USER ID for user {$user['id']}";
                        continue;
                    }
                    $updateData['user_id'] = $newId;
                }
            }

            if ((int)$userType === 2) { // SK Chairperson
                // Only generate SK credentials if missing
                if (empty($user['sk_username']) || empty($user['sk_password'])) {
                    $updateData['sk_username'] = UserHelper::generateSKUsername($user['first_name'], $user['last_name']);
                    $updateData['sk_password'] = UserHelper::generatePassword(8);
                }
                // If downgrading from PED -> SK, clear PED credentials
                if (isset($user['user_type']) && (int)$user['user_type'] === 3) {
                    $updateData['ped_username'] = null;
                    $updateData['ped_password'] = null;
                }
            } elseif ((int)$userType === 3) { // Pederasyon Officer
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

            // Handle KK downgrades nullification
            if ((int)$userType === 1) {
                if (isset($user['user_type'])) {
                    if ((int)$user['user_type'] === 3) { // PED -> KK: clear both SK and PED creds
                        $updateData['ped_username'] = null;
                        $updateData['ped_password'] = null;
                        $updateData['sk_username'] = null;
                        $updateData['sk_password'] = null;
                    } elseif ((int)$user['user_type'] === 2) { // SK -> KK
                        $updateData['sk_username'] = null;
                        $updateData['sk_password'] = null;
                    }
                }
            }

            if ($userModel->update($id, $updateData)) {
                // Decrement remaining count if we converted a Pederasyon to non-Pederasyon
                if ((int)($user['user_type'] ?? 1) === 3 && $newTypeInt !== 3) {
                    $remainingPedCount = max(0, $remainingPedCount - 1);
                }
                $updated++;
            }
        }
        $message = $updated > 0 ? 'User types updated successfully' : 'No users updated';
        if (!empty($errors)) { $message .= '. Notes: ' . implode('; ', $errors); }
        $status = $updated > 0 ? 200 : 400;
        return $this->response->setStatusCode($status)->setJSON(['success' => $updated > 0, 'message' => $message]);
    }

    public function bulkUpdateUserPosition()
    {
        $userIds = $this->request->getPost('user_ids');
        $position = $this->request->getPost('position');
        
        if (!$userIds || !$position || !is_array($userIds)) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Missing user_ids or position']);
        }
        
        // Validate position value
        if (!in_array($position, ['2', '3', '4', '5'])) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Invalid position value']);
        }
        
        // Prevent bulk assignment of SK Chairperson position
        if ($position == 1) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'SK Chairperson position cannot be assigned in bulk']);
        }
        
        $userModel = new UserModel();
        
        try {
            // Check position limits per barangay - ensure only 1 person per position per barangay (except positions 4 and 5)
            if ($position != 5 && $position != 4) {
                $addressModel = new AddressModel();
                
                // Get barangays of selected users to validate per-barangay restrictions
                $selectedUsersData = $userModel
                    ->select('user.id, user.first_name, user.last_name, address.barangay')
                    ->join('address', 'address.user_id = user.id', 'inner')
                    ->whereIn('user.id', $userIds)
                    ->findAll();
                
                if (empty($selectedUsersData)) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'success' => false, 
                        'message' => 'No valid users found with address information.'
                    ]);
                }
                
                // Group users by barangay
                $usersByBarangay = [];
                foreach ($selectedUsersData as $userData) {
                    $barangay = $userData['barangay'];
                    if (!isset($usersByBarangay[$barangay])) {
                        $usersByBarangay[$barangay] = [];
                    }
                    $usersByBarangay[$barangay][] = $userData;
                }
                
                // Check each barangay for existing position holders and validate bulk assignments
                foreach ($usersByBarangay as $barangay => $users) {
                    // Check if more than 1 user from same barangay is selected for positions 2 or 3
                    if (count($users) > 1) {
                        $positionNames = [
                            2 => 'SK Secretary', 
                            3 => 'SK Treasurer'
                        ];
                        
                        $positionName = $positionNames[$position] ?? 'Unknown Position';
                        
                        return $this->response->setStatusCode(400)->setJSON([
                            'success' => false, 
                            'message' => "Cannot assign multiple users from the same barangay to '{$positionName}' position. Only one person per barangay can hold this position."
                        ]);
                    }
                    
                    // Check if position already exists in this barangay
                    $existingUserWithPosition = $userModel
                        ->select('user.id, user.first_name, user.last_name, user.position, address.barangay')
                        ->join('address', 'address.user_id = user.id', 'inner')
                        ->where('user.position', $position)
                        ->where('address.barangay', $barangay)
                        ->where('user.status', 2) // Only check approved users
                        ->whereNotIn('user.id', $userIds)
                        ->first();
                    
                    if ($existingUserWithPosition) {
                        $positionNames = [
                            2 => 'SK Secretary', 
                            3 => 'SK Treasurer'
                        ];
                        
                        $positionName = $positionNames[$position] ?? 'Unknown Position';
                        $currentHolderName = $existingUserWithPosition['first_name'] . ' ' . $existingUserWithPosition['last_name'];
                        
                        return $this->response->setStatusCode(400)->setJSON([
                            'success' => false, 
                            'message' => "Position '{$positionName}' is already occupied by {$currentHolderName} in this barangay. Only one person per barangay can hold this position."
                        ]);
                    }
                }
            } elseif ($position == 4) {
                // Special validation for SK Councilor bulk assignment - maximum 7 per barangay total
                $addressModel = new AddressModel();
                
                // Get barangays of selected users
                $selectedUsersData = $userModel
                    ->select('user.id, user.first_name, user.last_name, address.barangay')
                    ->join('address', 'address.user_id = user.id', 'inner')
                    ->whereIn('user.id', $userIds)
                    ->findAll();
                
                if (empty($selectedUsersData)) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'success' => false, 
                        'message' => 'No valid users found with address information.'
                    ]);
                }
                
                // Group users by barangay and validate SK Councilor limits
                $usersByBarangay = [];
                foreach ($selectedUsersData as $userData) {
                    $barangay = $userData['barangay'];
                    if (!isset($usersByBarangay[$barangay])) {
                        $usersByBarangay[$barangay] = [];
                    }
                    $usersByBarangay[$barangay][] = $userData;
                }
                
                foreach ($usersByBarangay as $barangay => $users) {
                    // Count existing SK Councilors in this barangay
                    $existingCouncilorsCount = $userModel
                        ->select('user.id')
                        ->join('address', 'address.user_id = user.id', 'inner')
                        ->where('user.position', 4) // SK Councilor
                        ->where('address.barangay', $barangay)
                        ->where('user.status', 2) // Only check approved users
                        ->whereNotIn('user.id', $userIds) // Exclude selected users
                        ->countAllResults();
                    
                    $newCouncilorsCount = count($users);
                    $totalAfterAssignment = $existingCouncilorsCount + $newCouncilorsCount;
                    
                    if ($totalAfterAssignment > 7) {
                        return $this->response->setStatusCode(400)->setJSON([
                            'success' => false, 
                            'message' => "Cannot assign {$newCouncilorsCount} SK Councilors to this barangay. It already has {$existingCouncilorsCount} SK Councilors. Maximum allowed is 7 per barangay."
                        ]);
                    }
                }
            }
            
            // Get current user's database ID from session
            $session = session();
            $currentUserPermanentId = $session->get('user_id');
            $currentUser = $userModel->where('user_id', $currentUserPermanentId)->first();
            $currentUserId = $currentUser ? $currentUser['id'] : null;
            
            $updated = 0;
            $restricted = [];
            $errors = [];
            
            foreach ($userIds as $id) {
                $user = $userModel->find($id);
                
                if (!$user) {
                    $errors[] = "User with ID {$id} not found";
                    continue;
                }
                
                // Check restrictions: current user or SK Chairperson (position 1)
                $isCurrentUser = ($user['id'] == $currentUserId);
                $isChairperson = ($user['position'] == 1);
                
                if ($isCurrentUser || $isChairperson) {
                    $reason = $isCurrentUser ? 'current user' : 'SK Chairperson';
                    $restricted[] = $user['first_name'] . ' ' . $user['last_name'] . ' (' . $reason . ')';
                    continue;
                }
                
                $updateData = ['position' => (int)$position];

                // Update user_type based on position
                if ($position == 2) { // SK Secretary
                    $updateData['user_type'] = 2;
                    
                    // Generate Secretary credentials with SEC_ prefix if missing
                    if (empty($user['sk_username']) || empty($user['sk_password'])) {
                        $updateData['sk_username'] = UserHelper::generateSecretaryUsername($user['first_name'], $user['last_name']);
                        $updateData['sk_password'] = UserHelper::generatePassword(8);
                    }
                } elseif ($position == 3) { // SK Treasurer
                    $updateData['user_type'] = 2;
                    
                    // Generate SK credentials if missing
                    if (empty($user['sk_username']) || empty($user['sk_password'])) {
                        $updateData['sk_username'] = UserHelper::generateSKUsername($user['first_name'], $user['last_name']);
                        $updateData['sk_password'] = UserHelper::generatePassword(8);
                    }
                } elseif ($position == 4) { // SK Councilor
                    $updateData['user_type'] = 2;
                    
                    // Generate SK credentials if missing
                    if (empty($user['sk_username']) || empty($user['sk_password'])) {
                        $updateData['sk_username'] = UserHelper::generateSKUsername($user['first_name'], $user['last_name']);
                        $updateData['sk_password'] = UserHelper::generatePassword(8);
                    }
                } elseif ($position == 5) { // KK Member
                    $updateData['user_type'] = 1;
                    // When selecting "KK Member", clear SK credentials
                    $updateData['sk_username'] = null;
                    $updateData['sk_password'] = null;
                    $updateData['ped_position'] = null;
                }

                $result = $userModel->update($id, $updateData);
                if ($result) {
                    $updated++;
                } else {
                    $modelErrors = $userModel->errors();
                    if (!empty($modelErrors)) {
                        $errors[] = "Failed to update " . $user['first_name'] . ' ' . $user['last_name'] . ': ' . implode(', ', array_values($modelErrors));
                    }
                }
            }
            
            // Prepare response message
            $message = '';
            if ($updated > 0) {
                $message = "Updated {$updated} user position(s) successfully";
            }
            
            if (!empty($restricted)) {
                $restrictedMessage = "Could not update " . implode(', ', $restricted);
                $message = $message ? $message . '. ' . $restrictedMessage : $restrictedMessage;
            }
            
            if (!empty($errors)) {
                $errorMessage = "Errors: " . implode('; ', $errors);
                $message = $message ? $message . '. ' . $errorMessage : $errorMessage;
            }
            
            if ($updated > 0) {
                return $this->response->setJSON(['success' => true, 'message' => $message]);
            } else {
                return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => $message ?: 'No users were updated']);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error in bulk update user position: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Database error occurred while updating positions']);
        }
    }

}