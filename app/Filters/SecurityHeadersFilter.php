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
        // Prevent clickjacking
        $response->setHeader('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME sniffing
        $response->setHeader('X-Content-Type-Options', 'nosniff');

        // Legacy XSS protection
        $response->setHeader('X-XSS-Protection', '1; mode=block');

        // Referrer policy
        $response->setHeader('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions policy
        $response->setHeader('Permissions-Policy', 'geolocation=(), microphone=(), camera=(), payment=()');

        // Content Security Policy
        $cspParts = [
            "default-src 'self' https:",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://code.jquery.com https://cdn.datatables.net https://cdnjs.cloudflare.com https://cdn.jsdelivr.net",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.datatables.net https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
            "img-src 'self' data: https:",
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com",
            "connect-src 'self' https: wss:",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ];
        $response->setHeader('Content-Security-Policy', implode('; ', $cspParts));

        // Remove server information
        try {
            $response->removeHeader('X-Powered-By');
        } catch (\Throwable $e) {
            // ignore
        }
        $response->setHeader('Server', 'K-NECT');

        // Cache control for sensitive paths
        $path = $request->getUri()->getPath();
        if (str_contains($path, 'login') || str_contains($path, 'dashboard') || str_contains($path, 'profile')) {
            $response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->setHeader('Pragma', 'no-cache');
            $response->setHeader('Expires', '0');
        }

        // HSTS when request is secure
        try {
            if ($request->isSecure()) {
                $response->setHeader('Strict-Transport-Security', 'max-age=63072000; includeSubDomains; preload');
            }
        } catch (\Throwable $e) {
            // ignore
        }

        return $response;
    }
}
