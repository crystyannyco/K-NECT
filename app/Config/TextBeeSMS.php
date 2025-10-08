<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class TextBeeSMS extends BaseConfig
{
    /**
     * TextBee SMS API Configuration
     * 
     * Device ID for TextBee SMS API
     * This is the unique identifier for your device in TextBee system
     */
    public $deviceId = '68d1bf3ab8c77d7feb0ac0a4';

    /**
     * API Base URL for TextBee SMS
     */
    public $apiUrl = 'https://api.textbee.dev/api/v1/gateway/devices/';

    /**
     * Enable/Disable SMS sending
     * Set to false during development or testing
     */
    public $enabled = true;

    /**
     * Default sender name/number
     * This will appear as the sender on recipient's phone
     */
    public $senderName = 'K-NECT';

    /**
     * Timeout for API requests (in seconds)
     */
    public $timeout = 30;

    /**
     * Debug mode - set to true to log all SMS requests
     */
    public $debug = false;
}