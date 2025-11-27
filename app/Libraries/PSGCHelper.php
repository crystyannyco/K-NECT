<?php

namespace App\Libraries;

use CodeIgniter\Cache\CacheInterface;
use Config\Services;

/**
 * PSGC Helper - Resolves Philippine Standard Geographic Codes to readable names
 */
class PSGCHelper
{
    private static ?CacheInterface $cacheInstance = null;

    /**
     * Resolve a PSGC code to its display name
     */
    public static function getLocationName(?string $code, ?string $type = null): string
    {
        $code = trim((string) $code);

        if ($code === '') {
            return '';
        }

        if (!ctype_digit($code)) {
            // Already a readable value
            return $code;
        }

        $type = $type ?? self::detectType($code);

        if ($type === null) {
            log_message('warning', 'PSGCHelper: Unable to detect type for code ' . $code);
            return $code;
        }

        $cacheKey = 'psgc_' . md5($type . '_' . $code);
        $cache = self::cache();

        $cached = $cache->get($cacheKey);
        if (!empty($cached)) {
            return $cached;
        }

        $resolved = self::fetchLocationName($code, $type);
        $cache->save($cacheKey, $resolved, 86400); // 24 hours

        return $resolved;
    }

    /**
     * Resolve multiple PSGC codes at once
     */
    public static function getLocationNames(array $locations): array
    {
        $result = [];

        foreach (['region', 'province', 'municipality', 'barangay'] as $type) {
            if (!empty($locations[$type])) {
                $result[$type] = self::getLocationName($locations[$type], $type);
            }
        }

        return $result;
    }

    /**
     * Clear cached PSGC lookups
     */
    public static function clearCache(): bool
    {
        return self::cache()->clean();
    }

    private static function cache(): CacheInterface
    {
        if (self::$cacheInstance === null) {
            self::$cacheInstance = Services::cache();
        }

        return self::$cacheInstance;
    }

    /**
     * Guess the PSGC type based on the code format
     */
    private static function detectType(string $code): ?string
    {
        if (preg_match('/^\d{2}0{7}$/', $code)) {
            return 'region';
        }

        if (preg_match('/^\d{4}0{5}$/', $code)) {
            return 'province';
        }

        if (preg_match('/^\d{6}0{3}$/', $code) || preg_match('/^\d{7}0{2}$/', $code)) {
            return 'municipality';
        }

        if (strlen($code) === 9) {
            return 'barangay';
        }

        return null;
    }

    private static function fetchLocationName(string $code, string $type): string
    {
        // Try the official PSGC API first with a simple direct call
        $baseUrl = 'https://psgc.gitlab.io/api';
        $endpointMap = [
            'region' => '/regions/',
            'province' => '/provinces/',
            'city' => '/cities-municipalities/',
            'municipality' => '/cities-municipalities/',
            'barangay' => '/barangays/',
        ];

        if (!isset($endpointMap[$type])) {
            log_message('error', 'PSGCHelper: Unsupported type "' . $type . '" for code ' . $code);
            return $code;
        }

        // Add trailing slash to avoid redirect
        $url = $baseUrl . $endpointMap[$type] . $code . '/';
        
        // Use cURL if available for better control
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            if ($ch !== false) {
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 10,
                    CURLOPT_FOLLOWLOCATION => false, // Don't follow redirects to avoid auth loops
                    CURLOPT_SSL_VERIFYPEER => false, // Disable SSL verification for PSGC API
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_HTTPHEADER => [
                        'Accept: application/json',
                        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                    ],
                ]);

                $response = curl_exec($ch);
                $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $error = curl_error($ch);
                curl_close($ch);

                if ($response !== false && $statusCode === 200) {
                    $decoded = json_decode($response, true);
                    if ($decoded && isset($decoded['name'])) {
                        log_message('debug', 'PSGCHelper: Resolved ' . $type . ' code ' . $code . ' to ' . $decoded['name']);
                        return $decoded['name'];
                    }
                }
                
                if ($error) {
                    log_message('error', 'PSGCHelper: cURL error for ' . $url . ' - ' . $error);
                }
            }
        }

        log_message('warning', 'PSGCHelper: Unable to resolve ' . $type . ' code ' . $code . ' from PSGC API, returning code');
        return $code;
    }

    private static function requestJson(string $url, int $redirectDepth = 0): ?array
    {
        if ($redirectDepth > 5) {
            log_message('error', 'PSGCHelper: Too many redirects while requesting ' . $url);
            return null;
        }

        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            if ($ch === false) {
                log_message('error', 'PSGCHelper: Failed to initialize cURL for ' . $url);
                return null;
            }

            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTPHEADER => [
                    'Accept: application/json',
                    'User-Agent: K-NECT-PSGC-Helper/1.0 (+https://psgc.gitlab.io)'
                ],
            ]);

            $response = curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($response === false) {
                log_message('error', 'PSGCHelper: cURL error for ' . $url . ' - ' . curl_error($ch));
                curl_close($ch);
                return null;
            }

            curl_close($ch);

            if ($statusCode < 200 || $statusCode >= 300) {
                log_message('error', 'PSGCHelper: HTTP ' . $statusCode . ' for ' . $url);
                return null;
            }

            return self::decodeJson($response, $url);
        }

        $context = self::getStreamContext();
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            $error = error_get_last();
            log_message('error', 'PSGCHelper: file_get_contents error for ' . $url . ' - ' . ($error['message'] ?? 'unknown error'));
            return null;
        }

        $headers = $http_response_header ?? [];
        $statusCode = self::extractStatusCode($headers);

        if ($statusCode !== null && in_array($statusCode, [301, 302, 303, 307, 308], true)) {
            $location = self::extractLocation($headers);
            if ($location) {
                $nextUrl = self::resolveRedirectUrl($url, $location);
                log_message('debug', 'PSGCHelper: Following redirect (' . $statusCode . ') to ' . $nextUrl . ' from ' . $url);
                return self::requestJson($nextUrl, $redirectDepth + 1);
            }

            log_message('error', 'PSGCHelper: Received redirect (' . $statusCode . ') without Location header for ' . $url);
            return null;
        }

        if ($statusCode !== null && ($statusCode < 200 || $statusCode >= 300)) {
            log_message('error', 'PSGCHelper: HTTP ' . $statusCode . ' for ' . $url . ' (stream context)');
            return null;
        }

        return self::decodeJson($response, $url);
    }

    private static function decodeJson(string $response, string $url): ?array
    {
        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $snippet = substr($response, 0, 200);
            log_message('debug', 'PSGCHelper: Raw response from ' . $url . ' - Length: ' . strlen($response) . ' - First 200 chars: ' . $snippet);
            log_message('error', 'PSGCHelper: JSON decode error for ' . $url . ' - ' . json_last_error_msg());
            return null;
        }

        return $decoded;
    }

    private static function extractStatusCode(array $headers): ?int
    {
        if (empty($headers)) {
            return null;
        }

        $statusLine = $headers[0];
        if (preg_match('/\s(\d{3})\s/', $statusLine, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    private static function getStreamContext()
    {
        return stream_context_create([
            'http' => [
                'timeout' => 15,
                'ignore_errors' => true,
                'header' => [
                    'Accept: application/json',
                    'User-Agent: K-NECT-PSGC-Helper/1.0 (+https://psgc.gitlab.io)'
                ]
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true
            ]
        ]);
    }

    private static function extractLocation(array $headers): ?string
    {
        foreach ($headers as $header) {
            if (stripos($header, 'Location:') === 0) {
                return trim(substr($header, 9));
            }
        }

        return null;
    }

    private static function resolveRedirectUrl(string $currentUrl, string $location): string
    {
        // If location is already absolute, return as-is
        if (preg_match('#^https?://#i', $location)) {
            return $location;
        }

        $parts = parse_url($currentUrl);
        $scheme = $parts['scheme'] ?? 'https';
        $host = $parts['host'] ?? '';
        $port = isset($parts['port']) ? ':' . $parts['port'] : '';

        // If location starts with //, it's protocol-relative
        if (strpos($location, '//') === 0) {
            return $scheme . ':' . $location;
        }

        // If location starts with /, it's absolute path
        if (strpos($location, '/') === 0) {
            return $scheme . '://' . $host . $port . $location;
        }

        // Otherwise, it's relative to current path
        $basePath = isset($parts['path']) ? rtrim(dirname($parts['path']), '/\\') : '';
        return $scheme . '://' . $host . $port . $basePath . '/' . $location;
    }
}
