<?php

if (!function_exists('get_default_image')) {
    /**
     * Get the appropriate default image based on file type or context
     * 
     * @param string $type The type of default image needed
     * @param string $filename Optional filename to determine type from extension
     * @return string URL to the default image
     */
    function get_default_image($type = 'generic', $filename = null)
    {
        $base_url = base_url('assets/images/');
        
        // If filename is provided, try to determine type from extension
        if ($filename) {
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            switch ($ext) {
                case 'pdf':
                    return $base_url . 'default-pdf.svg';
                case 'doc':
                case 'docx':
                    return $base_url . 'default-word.svg';
                case 'xls':
                case 'xlsx':
                case 'csv':
                    return $base_url . 'default-excel.svg';
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'webp':
                case 'svg':
                case 'bmp':
                    return $base_url . 'default-image.svg';
                default:
                    return $base_url . 'default-document.svg';
            }
        }
        
        // Otherwise use the specified type
        switch ($type) {
            case 'avatar':
            case 'profile':
                return $base_url . 'default-avatar.svg';
            case 'event':
            case 'banner':
                return $base_url . 'default-event-banner.svg';
            case 'pdf':
                return $base_url . 'default-pdf.svg';
            case 'word':
            case 'doc':
            case 'docx':
                return $base_url . 'default-word.svg';
            case 'excel':
            case 'xls':
            case 'xlsx':
                return $base_url . 'default-excel.svg';
            case 'image':
            case 'photo':
                return $base_url . 'default-image.svg';
            case 'document':
            case 'file':
            default:
                return $base_url . 'default-document.svg';
        }
    }
}

if (!function_exists('get_file_type_from_mimetype')) {
    /**
     * Get file type category from MIME type
     * 
     * @param string $mimetype
     * @return string
     */
    function get_file_type_from_mimetype($mimetype)
    {
        if (strpos($mimetype, 'image/') === 0) {
            return 'image';
        }
        
        if (strpos($mimetype, 'application/pdf') === 0) {
            return 'pdf';
        }
        
        if (in_array($mimetype, [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ])) {
            return 'word';
        }
        
        if (in_array($mimetype, [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/csv'
        ])) {
            return 'excel';
        }
        
        return 'document';
    }
}

if (!function_exists('safe_image_url')) {
    /**
     * Generate a safe image URL with fallback
     * 
     * @param string $image_path The image path/URL
     * @param string $fallback_type The type of fallback image
     * @param string $filename Optional filename for type detection
     * @return array Contains 'src' and 'fallback' URLs
     */
    function safe_image_url($image_path, $fallback_type = 'generic', $filename = null)
    {
        $fallback = get_default_image($fallback_type, $filename);
        
        if (empty($image_path)) {
            return [
                'src' => $fallback,
                'fallback' => $fallback
            ];
        }
        
        // Handle different path formats
        if (strpos($image_path, 'http') === 0 || strpos($image_path, 'data:') === 0) {
            $src = $image_path;
        } elseif (strpos($image_path, '/') !== false) {
            // Convert to proper URL first, then fix if needed
            $src = base_url(ltrim($image_path, '/'));
            $src = fix_image_url($src);
        } else {
            // Assume it's just a filename - determine type based on fallback_type
            if ($fallback_type === 'avatar' || $fallback_type === 'profile') {
                $src = base_url("/previewDocument/profile_pictures/{$image_path}");
            } else {
                $src = base_url('uploads/' . $image_path);
                $src = fix_image_url($src);
            }
        }
        
        return [
            'src' => $src,
            'fallback' => $fallback
        ];
    }
}

if (!function_exists('fix_image_url')) {
    /**
     * Convert direct file system URLs to proper preview routes for Railway hosting
     * 
     * @param string $url The original image URL
     * @return string The fixed URL using preview route
     */
    function fix_image_url($url)
    {
        if (empty($url)) {
            return $url;
        }
        
        // If it's already a proper URL (http/https) or data URL, return as-is
        if (strpos($url, 'http') === 0 || strpos($url, 'data:') === 0) {
            return $url;
        }
        
        // If it already uses previewDocument route, return as-is
        if (strpos($url, '/previewDocument/') !== false) {
            return $url;
        }
        
        // Extract base URL and path
        $baseUrl = base_url();
        $path = str_replace($baseUrl, '', $url);
        $path = ltrim($path, '/');
        
        // Map different upload directories to preview routes
        $mappings = [
            'uploads/profile_pictures/' => '/previewDocument/profile_pictures/',
            'uploads/profile/' => '/previewDocument/profile_pictures/', // legacy path
            'uploads/bulletin/' => '/previewDocument/bulletin/',
            'uploads/event/' => '/previewDocument/event/',
            'uploads/logos/' => '/previewDocument/logos/',
            'uploads/certificate/' => '/previewDocument/certificate/',
            'uploads/id/' => '/previewDocument/id/',
        ];
        
        foreach ($mappings as $oldPath => $newRoute) {
            if (strpos($path, $oldPath) === 0) {
                $filename = str_replace($oldPath, '', $path);
                return base_url($newRoute . $filename);
            }
        }
        
        // If no mapping found, return original
        return $url;
    }
}
