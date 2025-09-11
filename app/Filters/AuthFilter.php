<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\Services;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Check if user has all required session data
        if (!$session->has('user_id') || !$session->has('username') || !$session->has('user_type') || !$session->has('role')) {
            // Clear any partial session data
            $session->destroy();
            
            // Redirect to login with message if this was an authenticated request
            return redirect()->to('/login')->with('error', 'Please log in to access this page.');
        }
        
        // Validate that session data is not empty
        $userId = $session->get('user_id');
        $username = $session->get('username');
        $userType = $session->get('user_type');
        $role = $session->get('role');
        
        if (empty($userId) || empty($username) || empty($userType) || empty($role)) {
            // Clear corrupted session data
            $session->destroy();
            
            return redirect()->to('/login')->with('error', 'Session expired. Please log in again.');
        }
        
        // Additional validation: check if user_type is valid
        $validUserTypes = ['kk', 'sk', 'pederasyon'];
        if (!in_array($userType, $validUserTypes)) {
            // Clear invalid session data
            $session->destroy();
            
            return redirect()->to('/login')->with('error', 'Invalid session. Please log in again.');
        }

        // Additional validation: check if role is valid
        $validRoles = ['user', 'admin', 'super_admin'];
        if (!in_array($role, $validRoles)) {
            // Clear invalid session data
            $session->destroy();
            
            return redirect()->to('/login')->with('error', 'Invalid session. Please log in again.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Add security headers to prevent caching of authenticated pages
        $response->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->setHeader('Pragma', 'no-cache');
        $response->setHeader('Expires', '0');
    }
} 