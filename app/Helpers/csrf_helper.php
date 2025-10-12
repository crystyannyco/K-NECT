<?php

/**
 * CSRF Helper
 * 
 * Helper functions for CSRF token management in forms and AJAX requests
 */

if (!function_exists('csrf_field')) {
    /**
     * Generate CSRF hidden input field for forms
     * 
     * @return string HTML hidden input with CSRF token
     */
    function csrf_field()
    {
        return '<input type="hidden" name="' . csrf_token() . '" value="' . csrf_hash() . '" />';
    }
}

if (!function_exists('csrf_meta')) {
    /**
     * Generate CSRF meta tag for AJAX requests
     * 
     * @return string HTML meta tag with CSRF token
     */
    function csrf_meta()
    {
        return '<meta name="' . csrf_token() . '" content="' . csrf_hash() . '">';
    }
}

if (!function_exists('csrf_token_js')) {
    /**
     * Generate JavaScript object with CSRF token data
     * Useful for AJAX requests
     * 
     * @return string JavaScript code
     */
    function csrf_token_js()
    {
        $tokenName = csrf_token();
        $tokenHash = csrf_hash();
        
        return "<script>
        // CSRF Token for AJAX requests
        window.csrf = {
            token: '{$tokenName}',
            hash: '{$tokenHash}',
            header: '" . config('Security')->headerName . "'
        };
        </script>";
    }
}
