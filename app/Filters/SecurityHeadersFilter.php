<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class SecurityHeadersFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // No action required before
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Remove X-Powered-By header to prevent information disclosure
        try {
            $response->removeHeader('X-Powered-By');
            $response->removeHeader('Server');
        } catch (\Throwable $e) {
            // ignore
        }
        
        // Set generic server name
        $response->setHeader('Server', 'Web Server');

        // Prevent clickjacking - SAMEORIGIN allows iframes from same domain (needed for document preview)
        $response->setHeader('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME sniffing - CRITICAL for security
        $response->setHeader('X-Content-Type-Options', 'nosniff');

        // Legacy XSS protection (deprecated but still helps older browsers)
        $response->setHeader('X-XSS-Protection', '1; mode=block');

        // Referrer policy - stricter policy
        $response->setHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Cross-domain policies - prevent Adobe Flash and PDF from making cross-domain requests
        $response->setHeader('X-Permitted-Cross-Domain-Policies', 'none');

        // Permissions policy - disable unnecessary features (only valid/recognized features)
        // Note: Some features are browser-specific or deprecated, only including widely supported ones
        $response->setHeader('Permissions-Policy', 
            'accelerometer=(), autoplay=(), camera=(), display-capture=(), encrypted-media=(), ' .
            'fullscreen=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), ' .
            'midi=(), payment=(), picture-in-picture=(), publickey-credentials-get=(), ' .
            'screen-wake-lock=(), sync-xhr=(), usb=(), web-share=(), xr-spatial-tracking=()'
        );

        // Generate CSP nonce for this request
        $nonce = base64_encode(random_bytes(16));
        $response->setHeader('X-CSP-Nonce', $nonce);

        // Enhanced Content Security Policy - STRICT configuration
        // Note: In development, we allow unsafe-inline/unsafe-eval for rapid development
        // In production, these should be removed and nonces should be used
        $isProduction = (ENVIRONMENT === 'production');
        
        $cspParts = [
            "default-src 'self'",
            // Script sources - removed wildcards, specific CDNs only
            $isProduction 
                ? "script-src 'self' 'nonce-{$nonce}' https://code.jquery.com https://cdn.datatables.net https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://unpkg.com https://apis.google.com https://accounts.google.com"
                : "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://code.jquery.com https://cdn.datatables.net https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://unpkg.com https://apis.google.com https://accounts.google.com",
            // Style sources - specific CDNs only
            $isProduction
                ? "style-src 'self' 'nonce-{$nonce}' https://fonts.googleapis.com https://cdn.datatables.net https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://accounts.google.com"
                : "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.datatables.net https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://accounts.google.com",
            // Image sources - allow data URIs and specific domains (including sample image sources for profiling)
            "img-src 'self' data: blob: https://lh3.googleusercontent.com https://www.google.com https://i.pinimg.com https://philsys.gov.ph",
            // Font sources
            "font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com",
            // Connect sources - removed wildcards, specific APIs only
            "connect-src 'self' https://accounts.google.com https://www.googleapis.com https://apis.google.com https://oauth2.googleapis.com wss://localhost:* ws://localhost:* https://cdnjs.cloudflare.com https://cdn.jsdelivr.net",
            // Frame sources for Google OAuth, Calendar, and document preview
            "frame-src 'self' https://accounts.google.com https://www.google.com https://calendar.google.com",
            // Child sources (fallback for workers/frames)
            "child-src 'self' https://accounts.google.com",
            // Worker sources
            "worker-src 'self' blob:",
            // Manifest sources
            "manifest-src 'self'",
            // Media sources
            "media-src 'self'",
            // Object sources - block all plugins
            "object-src 'none'",
            // Base URI restriction
            "base-uri 'self'",
            // Form action restriction
            "form-action 'self'",
            // Frame ancestors - allow self for document preview iframes
            "frame-ancestors 'self'"
        ];
        
        // Only add upgrade-insecure-requests in production with HTTPS
        if ($request->isSecure() && $isProduction) {
            $cspParts[] = "upgrade-insecure-requests";
        }
        
        $response->setHeader('Content-Security-Policy', implode('; ', $cspParts));

        // Enhanced cache control for all sensitive pages
        $path = $request->getUri()->getPath();
        $sensitivePatterns = ['login', 'dashboard', 'profile', 'settings', 'admin', 'sk/', 'kk/', 'pederasyon/', 'forgot-password', 'reset-password', 'verify-otp', 'change-password'];
        $isSensitive = false;
        
        foreach ($sensitivePatterns as $pattern) {
            if (str_contains($path, $pattern)) {
                $isSensitive = true;
                break;
            }
        }
        
        if ($isSensitive) {
            // Strict cache control for sensitive pages - prevent caching completely
            $response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, private, no-transform');
            $response->setHeader('Pragma', 'no-cache');
            $response->setHeader('Expires', '0');
            $response->setHeader('Clear-Site-Data', '"cache", "storage"');
        } else {
            // For public content, allow caching but revalidate and set proper cache duration
            $response->setHeader('Cache-Control', 'public, max-age=3600, must-revalidate, no-transform');
            $response->setHeader('Expires', gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');
        }

        // HSTS - Force HTTPS (set to 2 years for production)
        // CRITICAL: Always set HSTS in production for maximum security
        // Set even on HTTP requests so browsers remember for next HTTPS visit
        if ($isProduction) {
            // 2 years = 63072000 seconds
            $response->setHeader('Strict-Transport-Security', 'max-age=63072000; includeSubDomains; preload');
        } elseif ($request->isSecure()) {
            // In development with HTTPS, use shorter HSTS for testing
            $response->setHeader('Strict-Transport-Security', 'max-age=300');
        }
        
        // Prevent redirect information leakage
        // Remove query parameters from redirect URLs if they contain sensitive data
        $location = $response->getHeaderLine('Location');
        if ($location && str_contains($location, '?')) {
            // Check if this is a redirect that might leak sensitive info
            $sensitiveParams = ['token', 'password', 'reset', 'verify', 'otp', 'session'];
            foreach ($sensitiveParams as $param) {
                if (str_contains(strtolower($location), strtolower($param))) {
                    // Log warning about potential sensitive redirect
                    log_message('warning', 'Redirect with potential sensitive parameter detected: ' . $location);
                    break;
                }
            }
        }

        return $response;
    }
}
