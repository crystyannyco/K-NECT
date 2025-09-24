<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class SMS extends BaseConfig
{
    /**
     * General SMS Configuration
     * 
     * Enable/Disable SMS functionality
     */
    public $enabled = true;

    /**
     * SMS Service Provider
     * Currently using TextBee
     */
    public $provider = 'TextBee';

    /**
     * Default country code for phone numbers
     */
    public $defaultCountryCode = '+63';

    /**
     * Maximum message length
     */
    public $maxLength = 160;

    /**
     * Rate limiting - max messages per minute
     */
    public $rateLimit = 10;

    /**
     * Log SMS activities
     */
    public $logging = true;

    /**
     * Phone number validation pattern
     * Philippine mobile number pattern
     */
    public $phonePattern = '/^(09|\+639)\d{9}$/';
}