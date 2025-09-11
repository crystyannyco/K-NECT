<?php
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

define('SMS_ENABLED', false); // Set to true to enable, false to disable

if (!function_exists('check_sms_delivery_status')) {
    function check_sms_delivery_status($messageId) {
        $baseUrl = 'https://rp9mm1.api.infobip.com';
        $apiKey = '714c7c24647c5a373276e7793fa104ad-f176a2b1-080f-486a-8509-ec713a96a284';
        $client = new Client([
            'base_uri' => $baseUrl,
            'headers' => [
                'Authorization' => 'App ' . $apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ]);
        try {
            $response = $client->get('/sms/2/reports', [
                'query' => [
                    'messageId' => $messageId
                ]
            ]);
            $result = $response->getBody()->getContents();
            log_message('debug', 'Infobip delivery report for messageId ' . $messageId . ': ' . $result);
            return $result;
        } catch (RequestException $e) {
            log_message('error', 'Infobip delivery report error for messageId ' . $messageId . ': ' . $e->getMessage());
            if ($e->hasResponse()) {
                log_message('error', 'Infobip delivery report response: ' . $e->getResponse()->getBody()->getContents());
            }
            return false;
        }
    }
}

if (!function_exists('send_sms')) {
    function send_sms($to, $message) {
        if (!SMS_ENABLED) {
            log_message('debug', 'SMS sending is currently disabled.');
            return false;
        }
        // Accept $to as array or comma-separated string
        if (is_array($to)) {
            $to = implode(',', $to);
        }
        $apiToken = '541cca40527b1e22517c725738925deff2b4c69a';
        $url = 'https://sms.iprogtech.com/api/v1/sms_messages/send_bulk';
        $client = new Client();
        $body = [
            'api_token' => $apiToken,
            'phone_number' => $to,
            'message' => $message
        ];
        try {
            $response = $client->post($url, [
                'form_params' => $body,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Accept' => 'application/json',
                ]
            ]);
            $result = $response->getBody()->getContents();
            log_message('debug', 'IPROG SMS API response: ' . $result);
            return $result;
        } catch (RequestException $e) {
            log_message('error', 'IPROG SMS API error: ' . $e->getMessage());
            if ($e->hasResponse()) {
                log_message('error', 'IPROG SMS API response: ' . $e->getResponse()->getBody()->getContents());
            }
            return false;
        }
    }
} 