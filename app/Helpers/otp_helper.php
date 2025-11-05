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
        $message .= "This code will expire in 5 minutes. Do not share it with anyone.\n\n";
        $message .= "If you did not request a password reset, please ignore this message.";
        
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

if (!function_exists('send_account_approved_notification')) {
    /**
     * Send account approval notification via Email and SMS
     * 
     * @param array $user User data array
     * @param array $address Address data array
     * @return array ['email' => bool, 'sms' => bool] Success status for each channel
     */
    function send_account_approved_notification($user, $address = null) {
        $result = ['email' => false, 'sms' => false];
        
        // Prepare user information
        $userName = trim($user['first_name'] . ' ' . $user['last_name']);
        $userId = $user['user_id'] ?? '';
        $username = $user['username'] ?? '';
        $email = $user['email'] ?? '';
        $phoneNumber = $user['phone_number'] ?? '';
        
        // All users in approval process are KK Members
        $accountTypeLabel = 'Katipunan ng Kabataan (KK) Member';
        
        // Get barangay name if available
        $barangayName = '';
        if ($address && isset($address['barangay'])) {
            $barangayHelper = new \App\Libraries\BarangayHelper();
            $barangayName = $barangayHelper->getBarangayName($address['barangay']);
        }
        
        // Send Email Notification
        try {
            $emailService = \Config\Services::email();
            $emailService->setTo($email);
            $emailService->setSubject('Account Approved - Welcome to K-NECT!');
            
            $message = view('emails/account_approved', [
                'userName' => $userName,
                'userId' => $userId,
                'username' => $username,
                'accountTypeLabel' => $accountTypeLabel,
                'barangayName' => $barangayName
            ]);
            
            $emailService->setMessage($message);
            
            if ($emailService->send()) {
                $result['email'] = true;
                log_message('info', "Account approval email sent successfully to {$email}");
            } else {
                log_message('error', "Failed to send account approval email to {$email}: " . $emailService->printDebugger(['headers']));
            }
        } catch (\Exception $e) {
            log_message('error', "Account approval email exception for {$email}: " . $e->getMessage());
        }
        
        // Send SMS Notification
        if (!empty($phoneNumber)) {
            try {
                // Use newlines to improve readability on mobile devices
                $smsMessage = "Congratulations {$userName}!\n";
                $smsMessage .= "Your account has been APPROVED.\n";
                $smsMessage .= "User ID: {$userId}\n\n";
                $smsMessage .= "Login to access K-NECT. Welcome to K-NECT!";

                $smsResult = send_sms($phoneNumber, $smsMessage);

                if ($smsResult && !isset($smsResult['error'])) {
                    $result['sms'] = true;
                    log_message('info', "Account approval SMS sent successfully to {$phoneNumber}");
                } else {
                    log_message('error', "Failed to send account approval SMS to {$phoneNumber}");
                }
            } catch (\Exception $e) {
                log_message('error', "Account approval SMS exception for {$phoneNumber}: " . $e->getMessage());
            }
        }
        
        return $result;
    }
}

if (!function_exists('send_sk_chairperson_approved_notification')) {
    /**
     * Send SK Chairperson approval notification via Email and SMS
     * 
     * @param array $user User data array
     * @param array $address Address data array
     * @param string $skUsername SK Username credential
     * @param string $skPassword SK Password credential
     * @return array ['email' => bool, 'sms' => bool] Success status for each channel
     */
    function send_sk_chairperson_approved_notification($user, $address = null, $skUsername = '', $skPassword = '') {
        $result = ['email' => false, 'sms' => false];
        
        // Prepare user information
        $userName = trim($user['first_name'] . ' ' . $user['last_name']);
        $userId = $user['user_id'] ?? '';
        $email = $user['email'] ?? '';
        $phoneNumber = $user['phone_number'] ?? '';
        
        // Account type label
        $accountTypeLabel = 'SK Chairperson';
        
        // Get barangay name if available
        $barangayName = '';
        if ($address && isset($address['barangay'])) {
            $barangayHelper = new \App\Libraries\BarangayHelper();
            $barangayName = $barangayHelper->getBarangayName($address['barangay']);
        }
        
        // Send Email Notification
        try {
            $emailService = \Config\Services::email();
            $emailService->setTo($email);
            $emailService->setSubject('SK Chairperson Appointment - K-NECT Account Activated');
            
            $message = view('emails/sk_chairperson_approved', [
                'userName' => $userName,
                'userId' => $userId,
                'skUsername' => $skUsername,
                'skPassword' => $skPassword,
                'accountTypeLabel' => $accountTypeLabel,
                'barangayName' => $barangayName
            ]);
            
            $emailService->setMessage($message);
            
            if ($emailService->send()) {
                $result['email'] = true;
                log_message('info', "SK Chairperson approval email sent successfully to {$email}");
            } else {
                log_message('error', "Failed to send SK Chairperson approval email to {$email}: " . $emailService->printDebugger(['headers']));
            }
        } catch (\Exception $e) {
            log_message('error', "SK Chairperson approval email exception for {$email}: " . $e->getMessage());
        }
        
        // Send SMS Notification
        if (!empty($phoneNumber)) {
            try {
                $smsMessage = "Congratulations {$userName}!\n";
                $smsMessage .= "You are now an SK CHAIRPERSON";
                if ($barangayName) {
                    $smsMessage .= " for Barangay {$barangayName}";
                }
                $smsMessage .= ".\n\n";
                $smsMessage .= "SK Login Credentials:\n";
                $smsMessage .= "Username: {$skUsername}\n";
                $smsMessage .= "Password: {$skPassword}\n\n";
                $smsMessage .= "Login at K-NECT to access SK features. Welcome!";

                $smsResult = send_sms($phoneNumber, $smsMessage);

                if ($smsResult && !isset($smsResult['error'])) {
                    $result['sms'] = true;
                    log_message('info', "SK Chairperson approval SMS sent successfully to {$phoneNumber}");
                } else {
                    log_message('error', "Failed to send SK Chairperson approval SMS to {$phoneNumber}");
                }
            } catch (\Exception $e) {
                log_message('error', "SK Chairperson approval SMS exception for {$phoneNumber}: " . $e->getMessage());
            }
        }
        
        return $result;
    }
}

if (!function_exists('send_account_rejected_notification')) {
    /**
     * Send account rejection notification via Email and SMS
     * 
     * @param array $user User data array
     * @param string $reason Rejection reason
     * @return array ['email' => bool, 'sms' => bool] Success status for each channel
     */
    function send_account_rejected_notification($user, $reason) {
        $result = ['email' => false, 'sms' => false];
        
        // Prepare user information
        $userName = trim($user['first_name'] . ' ' . $user['last_name']);
        $email = $user['email'] ?? '';
        $phoneNumber = $user['phone_number'] ?? '';
        $id = $user['id'] ?? ''; // Use database ID, not user_id
        
        // All users in approval process are KK Members
        $accountTypeLabel = 'Katipunan ng Kabataan (KK) Member';
        
        // Send Email Notification
        try {
            $emailService = \Config\Services::email();
            $emailService->setTo($email);
            $emailService->setSubject('Account Registration Update - K-NECT');
            
            $message = view('emails/account_rejected', [
                'userName' => $userName,
                'accountTypeLabel' => $accountTypeLabel,
                'reason' => $reason,
                'id' => $id
            ]);
            
            $emailService->setMessage($message);
            
            if ($emailService->send()) {
                $result['email'] = true;
                log_message('info', "Account rejection email sent successfully to {$email}");
            } else {
                log_message('error', "Failed to send account rejection email to {$email}: " . $emailService->printDebugger(['headers']));
            }
        } catch (\Exception $e) {
            log_message('error', "Account rejection email exception for {$email}: " . $e->getMessage());
        }
        
        // Send SMS Notification
        if (!empty($phoneNumber)) {
            try {
                // Add newlines so the message is easier to read on phones
                $smsMessage = "Dear {$userName},\n";
                $smsMessage .= "Your account registration could not be approved.\n";
                $smsMessage .= "Reason: " . substr($reason, 0, 100) . (strlen($reason) > 100 ? '...' : '') . "\n\n";
                $smsMessage .= "Please login and profile again your documents. Check your email for full details.";

                $smsResult = send_sms($phoneNumber, $smsMessage);

                if ($smsResult && !isset($smsResult['error'])) {
                    $result['sms'] = true;
                    log_message('info', "Account rejection SMS sent successfully to {$phoneNumber}");
                } else {
                    log_message('error', "Failed to send account rejection SMS to {$phoneNumber}");
                }
            } catch (\Exception $e) {
                log_message('error', "Account rejection SMS exception for {$phoneNumber}: " . $e->getMessage());
            }
        }
        
        return $result;
    }
}

if (!function_exists('send_account_deactivated_notification')) {
    /**
     * Send account deactivation notification via Email and SMS
     * 
     * @param array $user User data array
     * @param string $reason Deactivation reason
     * @return array ['email' => bool, 'sms' => bool] Success status for each channel
     */
    function send_account_deactivated_notification($user, $reason) {
        $result = ['email' => false, 'sms' => false];
        
        // Prepare user information
        $userName = trim($user['first_name'] . ' ' . $user['last_name']);
        $email = $user['email'] ?? '';
        $phoneNumber = $user['phone_number'] ?? '';
        $userId = $user['user_id'] ?? '';
        
        // Map is_active value to readable reason if not provided
        if (empty($reason) && isset($user['is_active'])) {
            switch ($user['is_active']) {
                case 2:
                    $reason = 'Your account has been deactivated because you are 31 years old or above.';
                    break;
                case 3:
                    $reason = 'Your account has been deactivated due to inactivity for more than 1 year.';
                    break;
                case 4:
                    $reason = $user['deactivation_reason'] ?? 'Your account has been manually deactivated.';
                    break;
                default:
                    $reason = 'Your account has been deactivated.';
            }
        }
        
        // Send Email Notification
        try {
            $emailService = \Config\Services::email();
            $emailService->setTo($email);
            $emailService->setSubject('Account Deactivation Notice - K-NECT');
            
            $message = view('emails/account_deactivated', [
                'userName' => $userName,
                'userId' => $userId,
                'reason' => $reason
            ]);
            
            $emailService->setMessage($message);
            
            if ($emailService->send()) {
                $result['email'] = true;
                log_message('info', "Account deactivation email sent successfully to {$email}");
            } else {
                log_message('error', "Failed to send account deactivation email to {$email}: " . $emailService->printDebugger(['headers']));
            }
        } catch (\Exception $e) {
            log_message('error', "Account deactivation email exception for {$email}: " . $e->getMessage());
        }
        
        // Send SMS Notification
        if (!empty($phoneNumber)) {
            try {
                $smsMessage = "Dear {$userName},\n";
                $smsMessage .= "Your K-NECT account has been deactivated.\n";
                $smsMessage .= "Reason: " . substr($reason, 0, 100) . (strlen($reason) > 100 ? '...' : '') . "\n\n";
                $smsMessage .= "Contact your SK administrator for more information.";

                $smsResult = send_sms($phoneNumber, $smsMessage);

                if ($smsResult && !isset($smsResult['error'])) {
                    $result['sms'] = true;
                    log_message('info', "Account deactivation SMS sent successfully to {$phoneNumber}");
                } else {
                    log_message('error', "Failed to send account deactivation SMS to {$phoneNumber}");
                }
            } catch (\Exception $e) {
                log_message('error', "Account deactivation SMS exception for {$phoneNumber}: " . $e->getMessage());
            }
        }
        
        return $result;
    }
}

if (!function_exists('send_account_reactivated_notification')) {
    /**
     * Send account reactivation notification via Email and SMS
     * 
     * @param array $user User data array containing email, phone_number, name, etc.
     * @return array Result array with 'email' and 'sms' boolean keys
     */
    function send_account_reactivated_notification($user) {
        $result = ['email' => false, 'sms' => false];
        
        // Prepare user information
        $firstName = $user['first_name'] ?? '';
        $lastName = $user['last_name'] ?? '';
        $userName = trim($firstName . ' ' . $lastName);
        $email = $user['email'] ?? '';
        $phoneNumber = $user['phone_number'] ?? '';
        $userId = $user['user_id'] ?? '';
        
        // Send Email Notification
        if (!empty($email)) {
            try {
                $emailService = \Config\Services::email();
                $emailService->setTo($email);
                $emailService->setSubject('Account Reactivated - K-NECT');
                
                $message = view('emails/account_reactivated', [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'user_id' => $userId,
                    'email' => $email
                ]);
                
                $emailService->setMessage($message);
                
                if ($emailService->send()) {
                    $result['email'] = true;
                    log_message('info', "Account reactivation email sent successfully to {$email}");
                } else {
                    log_message('error', "Failed to send account reactivation email to {$email}: " . $emailService->printDebugger(['headers']));
                }
            } catch (\Exception $e) {
                log_message('error', "Account reactivation email exception for {$email}: " . $e->getMessage());
            }
        }
        
        // Send SMS Notification
        if (!empty($phoneNumber)) {
            try {
                $smsMessage = "Good news, {$userName}!\n\n";
                $smsMessage .= "Your K-NECT account has been REACTIVATED.\n\n";
                $smsMessage .= "You can now log in and access all platform features.\n\n";
                $smsMessage .= "Login at: " . base_url('login');

                $smsResult = send_sms($phoneNumber, $smsMessage);

                if ($smsResult && !isset($smsResult['error'])) {
                    $result['sms'] = true;
                    log_message('info', "Account reactivation SMS sent successfully to {$phoneNumber}");
                } else {
                    log_message('error', "Failed to send account reactivation SMS to {$phoneNumber}");
                }
            } catch (\Exception $e) {
                log_message('error', "Account reactivation SMS exception for {$phoneNumber}: " . $e->getMessage());
            }
        }
        
        return $result;
    }
}

if (!function_exists('send_profiling_completed_notification')) {
    /**
     * Send profiling completion notification via Email and SMS
     * 
     * @param array $user User data array
     * @param array $address Address data array
     * @return array ['email' => bool, 'sms' => bool] Success status for each channel
     */
    function send_profiling_completed_notification($user, $address = null) {
        $result = ['email' => false, 'sms' => false];
        
        // Prepare user information
        $userName = trim($user['first_name'] . ' ' . $user['last_name']);
        $userId = $user['user_id'] ?? '';
        $username = $user['username'] ?? '';
        $email = $user['email'] ?? '';
        $phoneNumber = $user['phone_number'] ?? '';
        
        // Get barangay name if available
        $barangayName = '';
        if ($address && isset($address['barangay'])) {
            $barangayHelper = new \App\Libraries\BarangayHelper();
            $barangayName = $barangayHelper->getBarangayName($address['barangay']);
        }
        
        // Send Email Notification
        try {
            $emailService = \Config\Services::email();
            $emailService->setTo($email);
            $emailService->setSubject('Profiling Completed - K-NECT Account Pending Approval');
            
            $message = view('emails/profiling_completed', [
                'userName' => $userName,
                'userId' => $userId,
                'username' => $username,
                'barangayName' => $barangayName
            ]);
            
            $emailService->setMessage($message);
            
            if ($emailService->send()) {
                $result['email'] = true;
                log_message('info', "Profiling completed email sent successfully to {$email}");
            } else {
                log_message('error', "Failed to send profiling completed email to {$email}: " . $emailService->printDebugger(['headers']));
            }
        } catch (\Exception $e) {
            log_message('error', "Profiling completed email exception for {$email}: " . $e->getMessage());
        }
        
        // Send SMS Notification
        if (!empty($phoneNumber)) {
            try {
                $smsMessage = "Thank you, {$userName}!\n\n";
                $smsMessage .= "Your K-NECT profiling has been completed successfully.\n\n";
                $smsMessage .= "Username: {$username}\n\n";
                $smsMessage .= "Your account is now pending approval by SK Officials. You will be notified once approved.\n\n";
                $smsMessage .= "Thank you for registering!";

                $smsResult = send_sms($phoneNumber, $smsMessage);

                if ($smsResult && !isset($smsResult['error'])) {
                    $result['sms'] = true;
                    log_message('info', "Profiling completed SMS sent successfully to {$phoneNumber}");
                } else {
                    log_message('error', "Failed to send profiling completed SMS to {$phoneNumber}");
                }
            } catch (\Exception $e) {
                log_message('error', "Profiling completed SMS exception for {$phoneNumber}: " . $e->getMessage());
            }
        }
        
        return $result;
    }
}
