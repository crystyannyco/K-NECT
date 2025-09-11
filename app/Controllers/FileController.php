<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class FileController extends BaseController
{
    /**
     * Securely serve files under uploads/ via router.
     * Expected route: $routes->get('uploads/(.*)', 'FileController::serve/$1');
     */
    public function serve(?string $path = null)
    {
        if ($path === null || trim($path) === '') {
            return $this->response->setStatusCode(400)->setBody('Missing file path');
        }

        // Normalize and block traversal
        $original = $path;
        $path = str_replace(['\\', '..'], ['/', ''], $path);
        $path = ltrim($path, '/');

        // Candidate roots to look for files
        $candidates = [
            rtrim(FCPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $path,
            rtrim(WRITEPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $path,
        ];

        $filePath = null;
        foreach ($candidates as $candidate) {
            $real = realpath($candidate);
            if ($real && is_file($real)) {
                // Must reside under either public/uploads or writable/uploads
                $allowedRoots = [
                    realpath(FCPATH . 'uploads'),
                    realpath(WRITEPATH . 'uploads'),
                ];
                foreach ($allowedRoots as $root) {
                    if ($root && strpos($real, $root) === 0) {
                        $filePath = $real;
                        break 2;
                    }
                }
            }
        }

        if (!$filePath) {
            // Fallbacks similar to legacy controller behavior when file is missing
            $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
            if ($ext === 'pdf') {
                $fallbackPdf = FCPATH . 'images/file-not-found.pdf';
                if (is_file($fallbackPdf)) {
                    return $this->response
                        ->setHeader('Content-Type', 'application/pdf')
                        ->setHeader('Content-Disposition', 'inline; filename="file-not-found.pdf"')
                        ->setBody(@file_get_contents($fallbackPdf));
                }
            }
            $fallbackPng = FCPATH . 'images/file-not-found.png';
            if (is_file($fallbackPng)) {
                return $this->response
                    ->setHeader('Content-Type', 'image/png')
                    ->setHeader('Content-Disposition', 'inline; filename="file-not-found.png"')
                    ->setBody(@file_get_contents($fallbackPng));
            }
            return $this->response->setStatusCode(404)->setBody('File not found');
        }

        // Derive mime type and headers
        $mime = mime_content_type($filePath) ?: 'application/octet-stream';
        $basename = basename($filePath);
        $inlineTypes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
            'application/pdf', 'text/plain', 'text/css', 'text/javascript', 'application/json'
        ];
        $disposition = in_array($mime, $inlineTypes, true) ? 'inline' : 'attachment';

        if ($disposition === 'inline') {
            $this->response->setHeader('Cache-Control', 'public, max-age=86400, immutable');
        } else {
            $this->response->setHeader('Cache-Control', 'no-cache');
        }

        $this->response->setHeader('Content-Type', $mime);
        $this->response->setHeader('Content-Length', (string) filesize($filePath));
        $this->response->setHeader('Content-Disposition', $disposition . '; filename="' . $basename . '"');

        return $this->response->setBody(@file_get_contents($filePath));
    }
}
