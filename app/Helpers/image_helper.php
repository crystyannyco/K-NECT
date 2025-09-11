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
            $src = base_url(ltrim($image_path, '/'));
        } else {
            // Assume it's just a filename in uploads directory
            $src = base_url('uploads/' . $image_path);
        }
        
        return [
            'src' => $src,
            'fallback' => $fallback
        ];
    }
}
