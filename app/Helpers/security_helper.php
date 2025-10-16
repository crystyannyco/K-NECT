<?php

/**
 * Security Helper Functions
 * 
 * Helper functions for implementing security features across the application
 */

if (!function_exists('get_csp_nonce')) {
    /**
     * Get the CSP nonce for the current request
     * 
     * Use this in your views to add nonces to inline scripts and styles
     * 
     * @return string The CSP nonce value
     * 
     * @example
     * <script nonce="<?= get_csp_nonce() ?>">
     *   // Inline JavaScript here
     * </script>
     */
    function get_csp_nonce(): string
    {
        $response = service('response');
        $nonce = $response->getHeaderLine('X-CSP-Nonce');
        
        // If no nonce exists (shouldn't happen), return empty string
        // In development, nonce might not be required due to unsafe-inline
        return $nonce ?: '';
    }
}

if (!function_exists('csp_script_tag')) {
    /**
     * Generate a script tag with CSP nonce
     * 
     * @param string $content The JavaScript content
     * @param array $attributes Additional attributes for the script tag
     * @return string The complete script tag with nonce
     * 
     * @example
     * <?= csp_script_tag("console.log('Hello');") ?>
     */
    function csp_script_tag(string $content, array $attributes = []): string
    {
        $nonce = get_csp_nonce();
        $attrs = '';
        
        foreach ($attributes as $key => $value) {
            $attrs .= ' ' . esc($key) . '="' . esc($value) . '"';
        }
        
        return sprintf(
            '<script nonce="%s"%s>%s</script>',
            esc($nonce),
            $attrs,
            $content // Don't escape content - it's JavaScript
        );
    }
}

if (!function_exists('csp_style_tag')) {
    /**
     * Generate a style tag with CSP nonce
     * 
     * @param string $content The CSS content
     * @param array $attributes Additional attributes for the style tag
     * @return string The complete style tag with nonce
     * 
     * @example
     * <?= csp_style_tag(".custom { color: red; }") ?>
     */
    function csp_style_tag(string $content, array $attributes = []): string
    {
        $nonce = get_csp_nonce();
        $attrs = '';
        
        foreach ($attributes as $key => $value) {
            $attrs .= ' ' . esc($key) . '="' . esc($value) . '"';
        }
        
        return sprintf(
            '<style nonce="%s"%s>%s</style>',
            esc($nonce),
            $attrs,
            $content // Don't escape content - it's CSS
        );
    }
}

if (!function_exists('sanitize_redirect_url')) {
    /**
     * Sanitize a redirect URL to prevent open redirect vulnerabilities
     * 
     * @param string $url The URL to sanitize
     * @param string $default Default URL if the provided URL is invalid
     * @return string Safe redirect URL
     */
    function sanitize_redirect_url(string $url, string $default = '/'): string
    {
        // If URL is empty, return default
        if (empty($url)) {
            return $default;
        }
        
        // Parse the URL
        $parsed = parse_url($url);
        
        // If URL has a host (external URL), reject it unless whitelisted
        if (isset($parsed['host'])) {
            $allowedHosts = [
                'localhost',
                'accounts.google.com',
                'www.google.com',
            ];
            
            // Add your production domain
            if (ENVIRONMENT === 'production') {
                $allowedHosts[] = env('app.baseURL') ? parse_url(env('app.baseURL'), PHP_URL_HOST) : '';
            }
            
            if (!in_array($parsed['host'], $allowedHosts, true)) {
                log_message('warning', 'Blocked redirect to external URL: ' . $url);
                return $default;
            }
        }
        
        // Return the sanitized URL
        return $url;
    }
}

if (!function_exists('format_date_safe')) {
    /**
     * Format a date without exposing Unix timestamp
     * 
     * Use this instead of exposing raw timestamps in views
     * 
     * @param mixed $date Date string, DateTime object, or Unix timestamp
     * @param string $format Date format string
     * @return string Formatted date
     */
    function format_date_safe($date, string $format = 'Y-m-d H:i:s'): string
    {
        if ($date instanceof \DateTime) {
            return $date->format($format);
        }
        
        if (is_numeric($date)) {
            // Unix timestamp
            return date($format, (int)$date);
        }
        
        if (is_string($date)) {
            $timestamp = strtotime($date);
            if ($timestamp !== false) {
                return date($format, $timestamp);
            }
        }
        
        return '';
    }
}

if (!function_exists('generate_secure_token')) {
    /**
     * Generate a cryptographically secure token
     * 
     * @param int $length Token length in bytes (will be base64 encoded, so output is longer)
     * @return string Secure random token
     */
    function generate_secure_token(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }
}

if (!function_exists('is_production')) {
    /**
     * Check if running in production environment
     * 
     * @return bool True if production
     */
    function is_production(): bool
    {
        return ENVIRONMENT === 'production';
    }
}

if (!function_exists('mask_sensitive_data')) {
    /**
     * Mask sensitive data for logging/display
     * 
     * @param string $data The sensitive data
     * @param int $visibleChars Number of characters to show at end
     * @return string Masked data
     * 
     * @example
     * mask_sensitive_data('1234567890', 4) // Returns: ******7890
     */
    function mask_sensitive_data(string $data, int $visibleChars = 4): string
    {
        $length = strlen($data);
        
        if ($length <= $visibleChars) {
            return str_repeat('*', $length);
        }
        
        $masked = str_repeat('*', $length - $visibleChars);
        $visible = substr($data, -$visibleChars);
        
        return $masked . $visible;
    }
}

if (!function_exists('remove_sensitive_params')) {
    /**
     * Remove sensitive parameters from URL
     * 
     * @param string $url URL to clean
     * @param array $sensitiveParams Parameters to remove
     * @return string Cleaned URL
     */
    function remove_sensitive_params(string $url, array $sensitiveParams = []): string
    {
        if (empty($sensitiveParams)) {
            $sensitiveParams = ['token', 'password', 'reset', 'verify', 'otp', 'session', 'api_key'];
        }
        
        $parsed = parse_url($url);
        
        if (!isset($parsed['query'])) {
            return $url;
        }
        
        parse_str($parsed['query'], $params);
        
        foreach ($sensitiveParams as $param) {
            unset($params[$param]);
        }
        
        $parsed['query'] = http_build_query($params);
        
        // Rebuild URL
        $scheme = isset($parsed['scheme']) ? $parsed['scheme'] . '://' : '';
        $host = $parsed['host'] ?? '';
        $port = isset($parsed['port']) ? ':' . $parsed['port'] : '';
        $path = $parsed['path'] ?? '';
        $query = !empty($parsed['query']) ? '?' . $parsed['query'] : '';
        $fragment = isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '';
        
        return $scheme . $host . $port . $path . $query . $fragment;
    }
}
