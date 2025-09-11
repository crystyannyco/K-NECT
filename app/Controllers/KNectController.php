<?php

namespace App\Controllers;

use App\Models\SystemLogoModel;

class KNectController extends BaseController
{
    public function login(): string
    {
        // Add security headers to prevent caching
        $this->response->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
        $this->response->setHeader('Pragma', 'no-cache');
        $this->response->setHeader('Expires', '0');
        
        // Double check: if user is somehow still logged in despite the filter, redirect them
        $session = session();
        if ($session->has('user_id') && $session->has('username') && $session->has('user_type')) {
            $userType = $session->get('user_type');
            
            switch ($userType) {
                case 'kk':
                    return redirect()->to('/kk/dashboard')->send();
                case 'sk':
                    return redirect()->to('/sk/dashboard')->send();
                case 'pederasyon':
                    return redirect()->to('/pederasyon/dashboard')->send();
                default:
                    // If user type is unknown, destroy session and continue to login
                    $session->destroy();
                    break;
            }
        }
        
        // Load logos for the login page
        $logos = $this->getLogosForLogin();
        
        return view('K-NECT/login', ['logos' => $logos]);
    }
    
    /**
     * Get logos for the login page
     */
    private function getLogosForLogin()
    {
        $systemLogoModel = new SystemLogoModel();
        $logos = [];
        
        // Get SK Pederasyon logo
        $pederasyonLogo = $systemLogoModel->where('logo_type', 'pederasyon')
                                         ->where('is_active', true)
                                         ->orderBy('created_at', 'DESC')
                                         ->first();
        if ($pederasyonLogo) {
            $logos['pederasyon'] = $pederasyonLogo;
        }
        
        // Get Iriga City logo
        $irigaLogo = $systemLogoModel->where('logo_type', 'iriga_city')
                                    ->where('is_active', true)
                                    ->orderBy('created_at', 'DESC')
                                    ->first();
        if ($irigaLogo) {
            $logos['iriga_city'] = $irigaLogo;
        }
        
        return $logos;
    }
}
