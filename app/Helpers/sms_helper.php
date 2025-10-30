<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

if (!defined('SMS_ENABLED')) {
    define('SMS_ENABLED', true);
}

if (!function_exists('format_phone_number')) {
    function format_phone_number($phoneNumber) {
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        if (strlen($phone) === 11 && substr($phone, 0, 1) === '0') {
            return '+63' . substr($phone, 1);
        } elseif (strlen($phone) === 10 && substr($phone, 0, 1) === '9') {
            return '+63' . $phone;
        } elseif (strlen($phone) === 12 && substr($phone, 0, 2) === '63') {
            return '+' . $phone;
        } elseif (strlen($phone) === 13 && substr($phone, 0, 3) === '+63') {
            return $phone;
        }
        
        return $phoneNumber;
    }
}

if (!function_exists('send_sms')) {
    function send_sms($to, $message) {
        log_message('info', '[SMS] send_sms() called with recipient(s): ' . (is_array($to) ? json_encode($to) : $to));
        
        if (!SMS_ENABLED) {
            log_message('debug', '[SMS] SMS sending is currently disabled (SMS_ENABLED = false).');
            return false;
        }
        
        // Get API credentials from .env file
        $apiKey = getenv('TEXTBEE_API_KEY');
        $deviceId = getenv('TEXTBEE_DEVICE_ID');
        
        // Fallback to hardcoded values if not set in .env (for backward compatibility)
        if (empty($apiKey)) {
            $apiKey = '8f9a7412-f462-4db1-bdc7-d1dd29bbd081';
            log_message('warning', '[SMS] TEXTBEE_API_KEY not found in .env, using hardcoded value');
        }
        
        if (empty($deviceId)) {
            $deviceId = '68d1bf3ab8c77d7feb0ac0a4';
            log_message('warning', '[SMS] TEXTBEE_DEVICE_ID not found in .env, using hardcoded value');
        }
        
        log_message('info', '[SMS] Using Device ID: ' . $deviceId);
        
        $url = "https://api.textbee.dev/api/v1/gateway/devices/{$deviceId}/send-sms";
        
        $recipients = is_array($to) ? $to : [$to];
        $results = [];
        
        foreach ($recipients as $phoneNumber) {
            $formattedPhone = format_phone_number($phoneNumber);
            
            log_message('info', '[SMS] Sending to: ' . $formattedPhone . ' (Original: ' . $phoneNumber . ')');
            
            $payload = [
                'recipients' => [$formattedPhone],
                'message' => $message,
                'simulateDelivery' => false,
                'prioritize' => false
            ];
            
            log_message('debug', '[SMS] Payload: ' . json_encode($payload));
            
            try {
                $client = new Client();
                $response = $client->post($url, [
                    'headers' => [
                        'x-api-key' => $apiKey,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json'
                    ],
                    'json' => $payload,
                    'timeout' => 30,
                    'verify' => false
                ]);
                
                $result = json_decode($response->getBody()->getContents(), true);
                $results[$formattedPhone] = $result;
                
                log_message('info', "[SMS] TextBee SMS sent successfully to {$formattedPhone}: " . json_encode($result));
                log_sms_to_database($formattedPhone, $message, 'sent', json_encode($result), null);
                
            } catch (RequestException $e) {
                $errorMessage = $e->getMessage();
                $results[$formattedPhone] = ['error' => $errorMessage];
                
                log_message('error', "[SMS] TextBee SMS error for {$formattedPhone}: " . $errorMessage);
                
                if ($e->hasResponse()) {
                    $errorResponse = $e->getResponse()->getBody()->getContents();
                    log_message('error', "[SMS] TextBee SMS error response: " . $errorResponse);
                    $results[$formattedPhone]['response'] = $errorResponse;
                }
                
                log_sms_to_database($formattedPhone, $message, 'failed', $errorMessage, null);
            }
        }
        
        return count($recipients) === 1 ? $results[array_key_first($results)] : $results;
    }
}

if (!function_exists('log_sms_to_database')) {
    function log_sms_to_database($phoneNumber, $message, $status, $response = null, $eventId = null) {
        try {
            $db = \Config\Database::connect();
            
            if (!$db->tableExists('sms_logs')) {
                log_message('warning', 'SMS logs table does not exist. SMS will not be logged to database.');
                return false;
            }
            
            $data = [
                'phone_number' => $phoneNumber,
                'message' => $message,
                'status' => $status,
                'response' => $response,
                'event_id' => $eventId,
                'sent_at' => date('Y-m-d H:i:s'),
                'created_by' => session('user_id') ?? null
            ];
            
            $db->table('sms_logs')->insert($data);
            return true;
            
        } catch (\Exception $e) {
            log_message('error', 'Failed to log SMS to database: ' . $e->getMessage());
            return false;
        }
    }
}
