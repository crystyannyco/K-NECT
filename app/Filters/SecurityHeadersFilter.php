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

        // Prevent clickjacking - DENY is more secure than SAMEORIGIN
        $response->setHeader('X-Frame-Options', 'DENY');

        // Prevent MIME sniffing
        $response->setHeader('X-Content-Type-Options', 'nosniff');

        // Legacy XSS protection (deprecated but still helps older browsers)
        $response->setHeader('X-XSS-Protection', '1; mode=block');

        // Referrer policy - stricter policy
        $response->setHeader('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions policy - disable unnecessary features (only valid/recognized features)
        // Note: Some features are browser-specific or deprecated, only including widely supported ones
        $response->setHeader('Permissions-Policy', 
            'accelerometer=(), autoplay=(), camera=(), display-capture=(), encrypted-media=(), ' .
            'fullscreen=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), ' .
            'midi=(), payment=(), picture-in-picture=(), publickey-credentials-get=(), ' .
            'screen-wake-lock=(), sync-xhr=(), usb=(), web-share=(), xr-spatial-tracking=()'
        );

        // Enhanced Content Security Policy with Google Calendar support
        $cspParts = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://code.jquery.com https://cdn.datatables.net https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://unpkg.com https://apis.google.com https://accounts.google.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.datatables.net https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://accounts.google.com",
            "img-src 'self' data: https: blob:",
            "font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com",
            "connect-src 'self' https://accounts.google.com https://www.googleapis.com https://apis.google.com wss: https:",
            "frame-src 'self' https://accounts.google.com https://www.google.com https://calendar.google.com",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
            "upgrade-insecure-requests"
        ];
        $response->setHeader('Content-Security-Policy', implode('; ', $cspParts));

        // Enhanced cache control for all sensitive pages
        $path = $request->getUri()->getPath();
        $sensitivePatterns = ['login', 'dashboard', 'profile', 'settings', 'admin', 'sk/', 'kk/', 'pederasyon/'];
        $isSensitive = false;
        
        foreach ($sensitivePatterns as $pattern) {
            if (str_contains($path, $pattern)) {
                $isSensitive = true;
                break;
            }
        }
        
        if ($isSensitive) {
            $response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, private');
            $response->setHeader('Pragma', 'no-cache');
            $response->setHeader('Expires', '0');
        } else {
            // For public content, allow caching but revalidate
            $response->setHeader('Cache-Control', 'public, max-age=3600, must-revalidate');
        }

        // HSTS - Force HTTPS (set to 2 years for production)
        // Always set HSTS in production, even on HTTP (browser will remember for HTTPS)
        $isProduction = ENVIRONMENT === 'production';
        
        try {
            if ($request->isSecure() || $isProduction) {
                // 2 years = 63072000 seconds
                $response->setHeader('Strict-Transport-Security', 'max-age=63072000; includeSubDomains; preload');
            }
        } catch (\Throwable $e) {
            // ignore
        }

        return $response;
    }
}
