<?php

namespace App\Validation;

/**
 * Custom Validation Rules for K-NECT System
 * 
 * Provides specialized validation rules for:
 * - Philippine phone numbers
 * - Youth age validation
 * - RFID card numbers
 * - SQL injection prevention
 * - XSS attack prevention
 * 
 * @package K-NECT
 * @version 1.0.0
 * @date October 13, 2025
 */
class CustomRules
{
    /**
     * Validate Philippine mobile phone numbers
     * 
     * Accepts formats:
     * - 09123456789
     * - +639123456789
     * - 639123456789
     * 
     * @param string|null $value Phone number to validate
     * @param string|null $params Not used
     * @param array $data Full data array
     * @return bool
     */
    public function valid_ph_phone(?string $value, ?string $params, array $data): bool
    {
        if (empty($value)) {
            return false;
        }

        // Remove spaces and dashes
        $cleaned = preg_replace('/[\s\-]/', '', $value);

        // Pattern for Philippine mobile numbers
        // Accepts: 09XX, +639XX, 639XX where XX is valid prefix
        $pattern = '/^(\+?63|0)(9\d{9})$/';

        return preg_match($pattern, $cleaned) === 1;
    }

    /**
     * Validate youth age (15-30 years old for SK/KK members)
     * 
     * @param string|null $value Birthdate in Y-m-d format
     * @param string|null $params Not used
     * @param array $data Full data array
     * @return bool
     */
    public function valid_youth_age(?string $value, ?string $params, array $data): bool
    {
        if (empty($value)) {
            return false;
        }

        try {
            $birthdate = new \DateTime($value);
            $today = new \DateTime();
            $age = $today->diff($birthdate)->y;

            // SK/KK members must be 15-30 years old
            return $age >= 15 && $age <= 30;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Validate RFID card number format
     * 
     * RFID cards typically have:
     * - 8-16 alphanumeric characters
     * - May include dashes or colons
     * 
     * @param string|null $value RFID card number
     * @param string|null $params Not used
     * @param array $data Full data array
     * @return bool
     */
    public function valid_rfid(?string $value, ?string $params, array $data): bool
    {
        if (empty($value)) {
            return false;
        }

        // RFID pattern: 8-16 alphanumeric chars, optional dashes/colons
        $pattern = '/^[A-Fa-f0-9:\-]{8,16}$/';

        return preg_match($pattern, $value) === 1;
    }

    /**
     * Check for potential SQL injection patterns
     * 
    * Detects common SQL injection attempts including:
    * - SQL keywords (SELECT, UNION, DROP, etc.)
    * - Comment markers (e.g., '--' or block comments)
    * - Quote manipulation
     * 
     * @param string|null $value Input value to check
     * @param string|null $params Not used
     * @param array $data Full data array
     * @return bool True if safe, false if suspicious
     */
    public function no_sql_injection(?string $value, ?string $params, array $data): bool
    {
        if (empty($value)) {
            return true; // Empty is safe
        }

        // Patterns that indicate SQL injection attempts
        $dangerous_patterns = [
            '/(\bSELECT\b|\bUNION\b|\bDROP\b|\bINSERT\b|\bUPDATE\b|\bDELETE\b)/i',
            '/(\bEXEC\b|\bEXECUTE\b|\bCREATE\b|\bALTER\b)/i',
            '/(--|\/\*|\*\/|;)/',  // SQL comments and statement terminators
            '/(\bOR\b\s+\d+\s*=\s*\d+|\bAND\b\s+\d+\s*=\s*\d+)/i', // OR 1=1, AND 1=1
            '/(\'\s*OR\s*\'|\'\s*AND\s*\')/i', // ' OR ', ' AND '
            '/(\bxp_cmdshell\b)/i', // SQL Server command execution
        ];

        foreach ($dangerous_patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check for potential XSS (Cross-Site Scripting) patterns
     * 
     * Detects common XSS attempts including:
     * - Script tags
     * - Event handlers (onclick, onerror, etc.)
     * - Javascript protocol
     * - Data URIs with scripts
     * 
     * @param string|null $value Input value to check
     * @param string|null $params Not used
     * @param array $data Full data array
     * @return bool True if safe, false if suspicious
     */
    public function no_xss(?string $value, ?string $params, array $data): bool
    {
        if (empty($value)) {
            return true; // Empty is safe
        }

        // Patterns that indicate XSS attempts
        $dangerous_patterns = [
            '/<script[^>]*>.*?<\/script>/is', // Script tags
            '/<iframe[^>]*>.*?<\/iframe>/is', // Iframes
            '/javascript:/i', // Javascript protocol
            '/on\w+\s*=\s*["\']?[^"\']*["\']?/i', // Event handlers (onclick, onerror, etc.)
            '/<embed[^>]*>/i', // Embed tags
            '/<object[^>]*>/i', // Object tags
            '/data:text\/html/i', // Data URIs with HTML
            '/<svg[^>]*>.*?<\/svg>/is', // SVG tags (can contain scripts)
            '/expression\s*\(/i', // CSS expression
            '/vbscript:/i', // VBScript protocol
        ];

        foreach ($dangerous_patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validate barangay format (for K-NECT system)
     * 
     * Barangay names should:
     * - Start with a letter
     * - Contain only letters, numbers, spaces, and hyphens
     * - Be 3-100 characters long
     * 
     * @param string|null $value Barangay name
     * @param string|null $params Not used
     * @param array $data Full data array
     * @return bool
     */
    public function valid_barangay(?string $value, ?string $params, array $data): bool
    {
        if (empty($value)) {
            return false;
        }

        // Barangay pattern: starts with letter, 3-100 chars, letters/numbers/spaces/hyphens
        $pattern = '/^[A-Za-z][A-Za-z0-9\s\-]{2,99}$/';

        return preg_match($pattern, $value) === 1;
    }

    /**
     * Validate strong password
     * 
     * Requirements:
     * - At least 8 characters
     * - Contains uppercase letter
     * - Contains lowercase letter
     * - Contains number
     * - Contains special character
     * 
     * @param string|null $value Password to validate
     * @param string|null $params Not used
     * @param array $data Full data array
     * @return bool
     */
    public function strong_password(?string $value, ?string $params, array $data): bool
    {
        if (empty($value)) {
            return false;
        }

        // Check minimum length
        if (strlen($value) < 8) {
            return false;
        }

        // Check for uppercase letter
        if (!preg_match('/[A-Z]/', $value)) {
            return false;
        }

        // Check for lowercase letter
        if (!preg_match('/[a-z]/', $value)) {
            return false;
        }

        // Check for number
        if (!preg_match('/[0-9]/', $value)) {
            return false;
        }

        // Check for special character
        if (!preg_match('/[^A-Za-z0-9]/', $value)) {
            return false;
        }

        return true;
    }

    /**
     * Error messages for custom validation rules
     * 
     * @return array
     */
    public function getErrorMessages(): array
    {
        return [
            'valid_ph_phone' => 'The {field} must be a valid Philippine mobile number (e.g., 09123456789)',
            'valid_youth_age' => 'The {field} must indicate an age between 15-30 years old for SK/KK members',
            'valid_rfid' => 'The {field} must be a valid RFID card number (8-16 alphanumeric characters)',
            'no_sql_injection' => 'The {field} contains suspicious content that may be a security risk',
            'no_xss' => 'The {field} contains potentially harmful content (HTML/JavaScript)',
            'valid_barangay' => 'The {field} must be a valid barangay name (3-100 characters, letters, numbers, spaces, hyphens)',
            'strong_password' => 'The {field} must be at least 8 characters and contain uppercase, lowercase, number, and special character',
        ];
    }
}
