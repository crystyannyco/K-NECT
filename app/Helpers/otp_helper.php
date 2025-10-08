<?php

if (!function_exists('generate_otp')) {
    /**
     * Generate a secure 6-digit OTP code
     * 
     * @return string 6-digit OTP code
     */
    function generate_otp() {
        return str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('hash_otp')) {
    /**
     * Hash OTP code for secure storage
     * 
     * @param string $otp The OTP code to hash
     * @return string Hashed OTP
     */
    function hash_otp($otp) {
        return hash('sha256', $otp);
    }
}

if (!function_exists('verify_otp')) {
    /**
     * Verify OTP code against stored hash
     * 
     * @param string $inputOtp The OTP entered by user
     * @param string $storedHash The stored hash in database
     * @return bool True if OTP matches, false otherwise
     */
    function verify_otp($inputOtp, $storedHash) {
        return hash_equals($storedHash, hash_otp($inputOtp));
    }
}

if (!function_exists('mask_email')) {
    /**
     * Mask email address for display
     * Example: john.doe@gmail.com becomes joh***@gmail.com
     * 
     * @param string $email The email to mask
     * @return string Masked email
     */
    function mask_email($email) {
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }
        
        $parts = explode('@', $email);
        $localPart = $parts[0];
        $domain = $parts[1];
        
        $localLength = strlen($localPart);
        
        if ($localLength <= 3) {
            $maskedLocal = substr($localPart, 0, 1) . '***';
        } else {
            $visibleChars = min(3, floor($localLength / 3));
            $maskedLocal = substr($localPart, 0, $visibleChars) . '***';
        }
        
        return $maskedLocal . '@' . $domain;
    }
}

if (!function_exists('mask_phone_number')) {
    /**
     * Mask phone number for display
     * Example: 09171234567 becomes 09****4567
     * 
     * @param string $phone The phone number to mask
     * @return string Masked phone number
     */
    function mask_phone_number($phone) {
        if (empty($phone)) {
            return $phone;
        }
        
        // Remove non-numeric characters
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        $length = strlen($cleanPhone);
        
        if ($length <= 4) {
            return $phone; // Too short to mask effectively
        }
        
        // Show first 2 and last 4 digits for Philippine numbers (11 digits)
        if ($length === 11) {
            return substr($cleanPhone, 0, 2) . '****' . substr($cleanPhone, -4);
        }
        
        // Show first 2 and last 4 digits for international format (+63)
        if ($length === 12 || $length === 13) {
            $prefix = substr($cleanPhone, 0, 2);
            $suffix = substr($cleanPhone, -4);
            return $prefix . '****' . $suffix;
        }
        
        // Generic masking for other formats
        $visibleStart = min(2, floor($length / 4));
        $visibleEnd = min(4, floor($length / 4));
        return substr($cleanPhone, 0, $visibleStart) . '****' . substr($cleanPhone, -$visibleEnd);
    }
}

if (!function_exists('check_otp_rate_limit')) {
    /**
     * Check if user has exceeded OTP request rate limit
     * 
     * @param DateTime|null $lastRequest Last OTP request timestamp
    * @param int $cooldownSeconds Cooldown period in seconds (default: 60)
     * @return array ['allowed' => bool, 'remainingSeconds' => int]
     */
    function check_otp_rate_limit($lastRequest, $cooldownSeconds = 60) {
        if (empty($lastRequest)) {
            return ['allowed' => true, 'remainingSeconds' => 0];
        }
        
        date_default_timezone_set('Asia/Manila');
        $now = new DateTime();
        $lastRequestTime = new DateTime($lastRequest);
        $diff = $now->getTimestamp() - $lastRequestTime->getTimestamp();
        
        if ($diff < $cooldownSeconds) {
            return [
                'allowed' => false,
                'remainingSeconds' => $cooldownSeconds - $diff
            ];
        }
        
        return ['allowed' => true, 'remainingSeconds' => 0];
    }
}

if (!function_exists('is_otp_expired')) {
    /**
     * Check if OTP has expired
     * 
     * @param DateTime|string $expiresAt OTP expiration timestamp
     * @return bool True if expired, false otherwise
     */
    function is_otp_expired($expiresAt) {
        if (empty($expiresAt)) {
            return true;
        }
        
        date_default_timezone_set('Asia/Manila');
        $now = new DateTime();
        $expiryTime = new DateTime($expiresAt);
        
        return $now > $expiryTime;
    }
}

if (!function_exists('get_otp_expiry_time')) {
    /**
     * Get OTP expiry timestamp
     * 
     * @param int $minutes Minutes until expiry (default: 5)
     * @return string Expiry timestamp in Y-m-d H:i:s format
     */
    function get_otp_expiry_time($minutes = 5) {
        date_default_timezone_set('Asia/Manila');
        return date('Y-m-d H:i:s', time() + ($minutes * 60));
    }
}

if (!function_exists('send_otp_sms')) {
    /**
     * Send OTP via SMS using existing SMS functionality
     * 
     * @param string $phoneNumber Recipient phone number
     * @param string $otp OTP code
     * @return array Result of SMS send operation
     */
    function send_otp_sms($phoneNumber, $otp) {
        $message = "K-NECT Password Reset\n\n";
        $message .= "Your OTP code is: {$otp}\n\n";
        $message .= "This code will expire in 5 minutes.\n";
        $message .= "DO NOT share this code with anyone.\n\n";
        $message .= "If you did not request this, please ignore this message.";
        
        return send_sms($phoneNumber, $message);
    }
}

if (!function_exists('send_otp_email')) {
    /**
     * Send OTP via email
     * 
     * @param string $email Recipient email address
     * @param string $otp OTP code
     * @param string $userName User's name
     * @param string $accountTypeLabel Account type label
     * @return bool True if sent successfully, false otherwise
     */
    function send_otp_email($email, $otp, $userName, $accountTypeLabel) {
        $emailService = \Config\Services::email();
        $emailService->setTo($email);
        $emailService->setSubject('Password Reset OTP - K-NECT ' . $accountTypeLabel . ' Account');
        
        $message = view('emails/otp_email', [
            'otp' => $otp,
            'userName' => $userName,
            'accountTypeLabel' => $accountTypeLabel
        ]);
        
        $emailService->setMessage($message);
        
        try {
            if ($emailService->send()) {
                return true;
            } else {
                log_message('error', 'Failed to send OTP email: ' . $emailService->printDebugger(['headers']));
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'OTP email sending exception: ' . $e->getMessage());
            return false;
        }
    }
}
