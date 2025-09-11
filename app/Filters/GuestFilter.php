<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class GuestFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // If user is already logged in, redirect to appropriate dashboard
        if ($session->has('user_id') && $session->has('username') && $session->has('user_type')) {
            $userType = $session->get('user_type');
            
            switch ($userType) {
                case 'kk':
                    return redirect()->to('/kk/dashboard');
                case 'sk':
                    return redirect()->to('/sk/dashboard');
                case 'pederasyon':
                    return redirect()->to('/pederasyon/dashboard');
                default:
                    // If user type is unknown, destroy session and allow access to login
                    $session->destroy();
                    break;
            }
        }
        
        // User is not logged in, allow access to login page
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after
    }
}
