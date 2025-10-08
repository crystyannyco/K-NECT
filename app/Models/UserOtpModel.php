<?php

namespace App\Models;

use CodeIgniter\Model;

class UserOtpModel extends Model
{
    protected $table = 'user_otp';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id',
        'otp_code',
        'otp_expires_at',
        'otp_type',
        'otp_verified',
        'otp_attempts',
        'otp_last_request',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'user_id' => 'required|integer',
        'otp_type' => 'permit_empty|in_list[sms,email]',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get the latest OTP record for a user
     * @param int $userId
     * @return array|null
     */
    public function getLatestOtpForUser(int $userId): ?array
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->first();
    }

    /**
     * Create or update OTP for a user
     * @param int $userId
     * @param array $otpData
     * @return bool|int
     */
    public function upsertOtp(int $userId, array $otpData)
    {
        $existing = $this->getLatestOtpForUser($userId);
        
        $otpData['user_id'] = $userId;
        
        if ($existing) {
            // Update existing record
            return $this->update($existing['id'], $otpData);
        } else {
            // Insert new record
            return $this->insert($otpData);
        }
    }

    /**
     * Clear OTP data for a user
     * @param int $userId
     * @return bool
     */
    public function clearOtp(int $userId): bool
    {
        return $this->where('user_id', $userId)->delete();
    }
}
