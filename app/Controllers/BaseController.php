<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Libraries\UserHelper;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;
    protected $data = [];

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Set timezone to Manila for all controllers
        date_default_timezone_set('Asia/Manila');

        // Preload any models, libraries, etc, here.
        // E.g.: $this->session = service('session');
        
        // Load current user data for all views
        $this->data['currentUser'] = UserHelper::getCurrentUserProfile();
        
        // Load event counts for sidebar
        $this->loadEventCounts();
    }
    
    /**
     * Load event counts for sidebar navigation
     */
    protected function loadEventCounts()
    {
        $session = session();
        $userType = $session->get('user_type');
        $barangayId = $session->get('barangay_id') ?: $session->get('sk_barangay');
        
        if ($userType && in_array($userType, ['sk', 'kk', 'pederasyon'])) {
            $eventModel = new \App\Models\EventModel();
            
            if (($userType === 'sk' || $userType === 'kk') && $barangayId) {
                // SK Officials and KK members see their barangay events
                if ($userType === 'kk') {
                    // KK members only see published events
                    $eventCount = $eventModel->where('barangay_id', $barangayId)
                                           ->where('status', 'Published')
                                           ->countAllResults();
                } else {
                    // SK Officials see all events in their barangay
                    $eventCount = $eventModel->where('barangay_id', $barangayId)->countAllResults();
                }
            } else if ($userType === 'pederasyon') {
                // Pederasyon sees all events
                $eventCount = $eventModel->countAllResults();
            } else {
                $eventCount = 0;
            }
            
            $this->data['eventCount'] = $eventCount;
        } else {
            $this->data['eventCount'] = 0;
        }
    }
    
    /**
     * Load view with shared data
     */
    protected function loadView($view, $data = [])
    {
        return view($view, array_merge($this->data, $data));
    }
}
