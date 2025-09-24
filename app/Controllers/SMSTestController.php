<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SMSLogModel;
use App\Models\UserModel;

helper('sms');

class SMSTestController extends BaseController
{
    public function index()
    {
        // Allow both super admin and admin to access SMS testing
        $userRole = session('role');
        if (!in_array($userRole, ['super_admin', 'admin'])) {
            return redirect()->to('/')->with('error', 'Access denied.');
        }
        
        $smsLogModel = new SMSLogModel();
        $userModel = new UserModel();
        
        // Get SMS logs with pagination
        $perPage = 50;
        $page = (int)($this->request->getGet('page') ?? 1);
        
        // Build query based on user role
        if ($userRole === 'admin') {
            // Admin can only see SMS logs for their barangay
            $barangayId = session('barangay_id');
            $data['recentSMS'] = $smsLogModel->select('sms_logs.*, event.title as event_title, event.barangay_id as event_barangay_id')
                                               ->join('event', 'event.event_id = sms_logs.event_id', 'left')
                                               ->where('event.barangay_id', $barangayId)
                                               ->orWhere('event.barangay_id', null) // Include test SMS without events
                                               ->orderBy('sms_logs.sent_at', 'DESC')
                                               ->paginate($perPage);
        } else {
            // Super admin can see all SMS logs
            $data['recentSMS'] = $smsLogModel->select('sms_logs.*, event.title as event_title, event.barangay_id as event_barangay_id, barangay.name as barangay_name')
                                               ->join('event', 'event.event_id = sms_logs.event_id', 'left')
                                               ->join('barangay', 'barangay.barangay_id = event.barangay_id', 'left')
                                               ->orderBy('sms_logs.sent_at', 'DESC')
                                               ->paginate($perPage);
        }
        
        // Get SMS statistics
        if ($userRole === 'admin') {
            $barangayId = session('barangay_id');
            $data['smsStats'] = $this->getSMSStatsByBarangay($barangayId);
        } else {
            $data['smsStats'] = $this->getSMSStatsOverall();
        }
        
        // Get recent events with SMS enabled
        $data['recentEventsWithSMS'] = $this->getRecentEventsWithSMS($userRole);
        
        // Get user information
        $data['userRole'] = $userRole;
        $data['userName'] = session('first_name') . ' ' . session('last_name');
        $data['userBarangay'] = $userRole === 'admin' ? session('barangay_id') : null;
        
        // Get pagination info
        $data['pager'] = $smsLogModel->pager;
        
        return view('sms_dashboard', $data);
    }
    
    public function sendTest()
    {
        // Only allow super admin to access SMS testing
        if (session('role') !== 'super_admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied.'
            ]);
        }
        
        $phoneNumber = $this->request->getPost('phone_number');
        $message = $this->request->getPost('message');
        
        if (empty($phoneNumber) || empty($message)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Phone number and message are required.'
            ]);
        }
        
        try {
            $result = send_sms($phoneNumber, $message);
            
            if ($result && !isset($result['error'])) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'SMS sent successfully!',
                    'response' => $result
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to send SMS: ' . (isset($result['error']) ? $result['error'] : 'Unknown error'),
                    'response' => $result
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ]);
        }
    }
    
    public function testRecipients()
    {
        // Only allow super admin to access SMS testing
        if (session('role') !== 'super_admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied.'
            ]);
        }
        
        $userModel = new UserModel();
        
        // Test recipient query similar to what events use
        $recipients = $userModel->select('user.*, address.barangay as barangay_id, barangay.name as barangay_name')
                                ->join('address', 'address.user_id = user.id', 'left')
                                ->join('barangay', 'barangay.barangay_id = address.barangay', 'left')
                                ->where('user.phone_number IS NOT NULL')
                                ->where('user.phone_number !=', '')
                                ->where('user.is_active', 1)
                                ->findAll();
        
        $recipientData = [];
        foreach ($recipients as $recipient) {
            $recipientData[] = [
                'name' => $recipient['first_name'] . ' ' . $recipient['last_name'],
                'phone' => $recipient['phone_number'],
                'user_type' => $recipient['user_type'],
                'position' => $recipient['position'],
                'ped_position' => $recipient['ped_position'],
                'barangay' => $recipient['barangay_name'] ?? 'N/A'
            ];
        }
        
        return $this->response->setJSON([
            'success' => true,
            'recipients' => $recipientData,
            'count' => count($recipientData)
        ]);
    }
    
    /**
     * Get SMS statistics by barangay (for admin users)
     */
    private function getSMSStatsByBarangay($barangayId)
    {
        $smsLogModel = new SMSLogModel();
        $db = \Config\Database::connect();
        
        $query = $db->table('sms_logs')
                   ->select('status, COUNT(*) as count')
                   ->join('event', 'event.event_id = sms_logs.event_id', 'left')
                   ->where('event.barangay_id', $barangayId)
                   ->orWhere('event.barangay_id', null)
                   ->groupBy('status');
        
        $results = $query->get()->getResultArray();
        
        $stats = [
            'sent' => 0,
            'failed' => 0,
            'delivered' => 0,
            'total' => 0,
            'today' => 0,
            'this_week' => 0,
            'this_month' => 0
        ];
        
        foreach ($results as $result) {
            $stats[$result['status']] = (int)$result['count'];
            $stats['total'] += (int)$result['count'];
        }
        
        // Get time-based stats
        $today = date('Y-m-d');
        $weekAgo = date('Y-m-d', strtotime('-7 days'));
        $monthAgo = date('Y-m-d', strtotime('-30 days'));
        
        $stats['today'] = $db->table('sms_logs')
                            ->join('event', 'event.event_id = sms_logs.event_id', 'left')
                            ->where('event.barangay_id', $barangayId)
                            ->where('DATE(sms_logs.sent_at)', $today)
                            ->countAllResults();
        
        $stats['this_week'] = $db->table('sms_logs')
                                ->join('event', 'event.event_id = sms_logs.event_id', 'left')
                                ->where('event.barangay_id', $barangayId)
                                ->where('DATE(sms_logs.sent_at) >=', $weekAgo)
                                ->countAllResults();
        
        $stats['this_month'] = $db->table('sms_logs')
                                 ->join('event', 'event.event_id = sms_logs.event_id', 'left')
                                 ->where('event.barangay_id', $barangayId)
                                 ->where('DATE(sms_logs.sent_at) >=', $monthAgo)
                                 ->countAllResults();
        
        return $stats;
    }
    
    /**
     * Get overall SMS statistics (for super admin users)
     */
    private function getSMSStatsOverall()
    {
        $smsLogModel = new SMSLogModel();
        $db = \Config\Database::connect();
        
        $query = $db->table('sms_logs')
                   ->select('status, COUNT(*) as count')
                   ->groupBy('status');
        
        $results = $query->get()->getResultArray();
        
        $stats = [
            'sent' => 0,
            'failed' => 0,
            'delivered' => 0,
            'total' => 0,
            'today' => 0,
            'this_week' => 0,
            'this_month' => 0
        ];
        
        foreach ($results as $result) {
            $stats[$result['status']] = (int)$result['count'];
            $stats['total'] += (int)$result['count'];
        }
        
        // Get time-based stats
        $today = date('Y-m-d');
        $weekAgo = date('Y-m-d', strtotime('-7 days'));
        $monthAgo = date('Y-m-d', strtotime('-30 days'));
        
        $stats['today'] = $db->table('sms_logs')
                            ->where('DATE(sent_at)', $today)
                            ->countAllResults();
        
        $stats['this_week'] = $db->table('sms_logs')
                                ->where('DATE(sent_at) >=', $weekAgo)
                                ->countAllResults();
        
        $stats['this_month'] = $db->table('sms_logs')
                                 ->where('DATE(sent_at) >=', $monthAgo)
                                 ->countAllResults();
        
        return $stats;
    }
    
    /**
     * Get recent events with SMS enabled
     */
    private function getRecentEventsWithSMS($userRole)
    {
        $db = \Config\Database::connect();
        
        $query = $db->table('event')
                   ->select('event.*, barangay.name as barangay_name')
                   ->join('barangay', 'barangay.barangay_id = event.barangay_id', 'left')
                   ->where('sms_notification_enabled', 1)
                   ->orderBy('created_at', 'DESC')
                   ->limit(10);
        
        if ($userRole === 'admin') {
            $barangayId = session('barangay_id');
            $query->where('event.barangay_id', $barangayId);
        }
        
        return $query->get()->getResultArray();
    }
    
    /**
     * Get SMS logs with filters (AJAX endpoint)
     */
    public function getSMSLogs()
    {
        $userRole = session('role');
        if (!in_array($userRole, ['super_admin', 'admin'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied.'
            ]);
        }
        
        $smsLogModel = new SMSLogModel();
        $db = \Config\Database::connect();
        
        // Get filter parameters
        $status = $this->request->getGet('status');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');
        $eventId = $this->request->getGet('event_id');
        $page = (int)($this->request->getGet('page') ?? 1);
        $perPage = 25;
        
        // Build query
        $query = $db->table('sms_logs')
                   ->select('sms_logs.*, event.title as event_title, event.barangay_id as event_barangay_id, barangay.name as barangay_name')
                   ->join('event', 'event.event_id = sms_logs.event_id', 'left')
                   ->join('barangay', 'barangay.barangay_id = event.barangay_id', 'left')
                   ->orderBy('sms_logs.sent_at', 'DESC');
        
        // Apply role-based filtering
        if ($userRole === 'admin') {
            $barangayId = session('barangay_id');
            $query->where('event.barangay_id', $barangayId)
                  ->orWhere('event.barangay_id', null);
        }
        
        // Apply filters
        if ($status && $status !== 'all') {
            $query->where('sms_logs.status', $status);
        }
        
        if ($dateFrom) {
            $query->where('DATE(sms_logs.sent_at) >=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->where('DATE(sms_logs.sent_at) <=', $dateTo);
        }
        
        if ($eventId) {
            $query->where('sms_logs.event_id', $eventId);
        }
        
        // Get total count
        $totalQuery = clone $query;
        $total = $totalQuery->countAllResults(false);
        
        // Apply pagination
        $offset = ($page - 1) * $perPage;
        $results = $query->limit($perPage, $offset)->get()->getResultArray();
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $results,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => ceil($total / $perPage)
            ]
        ]);
    }
}