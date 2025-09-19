<?php

namespace App\Controllers;

use App\Models\UserAnalyticsModel;
use App\Libraries\BarangayHelper;

class UserAnalyticsController extends BaseController
{
    protected $userAnalyticsModel;
    
    public function __construct()
    {
        $this->userAnalyticsModel = new UserAnalyticsModel();
    }

    /**
     * User Analytics Dashboard
     */
    public function dashboard()
    {
        $session = session();
        $userId = $session->get('user_id');
        $username = $session->get('username');
        
        if (!$userId) {
            return redirect()->to('/login');
        }

        // Get all analytics data for the user
        $data = [
            'user_id' => $userId,
            'username' => $username,
            'page_title' => 'My Analytics Dashboard',
            'user_summary' => $this->userAnalyticsModel->getUserSummary($userId),
            'event_stats' => $this->userAnalyticsModel->getUserEventStats($userId),
            'profile_completeness' => $this->userAnalyticsModel->getUserProfileCompleteness($userId),
            'recent_attendance' => $this->userAnalyticsModel->getUserRecentAttendance($userId, 8),
            'favorite_categories' => $this->userAnalyticsModel->getUserFavoriteEventCategories($userId),
            'achievements' => $this->userAnalyticsModel->getUserAchievements($userId),
            'barangay_comparison' => $this->userAnalyticsModel->getUserBarangayComparison($userId),
            'recent_activity' => $this->userAnalyticsModel->getUserRecentActivity($userId, 10)
        ];

        return 
            $this->loadView('K-NECT/KK/template/header', $data) .
            $this->loadView('K-NECT/KK/template/sidebar') .
            $this->loadView('K-NECT/KK/analytics', $data) .
            $this->loadView('K-NECT/KK/template/footer');
    }

    /**
     * AJAX endpoint to get user's attendance trend data
     */
    public function getAttendanceTrend()
    {
        $session = session();
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $months = $this->request->getGet('months') ?? 12;
        $data = $this->userAnalyticsModel->getUserAttendanceTrend($userId, $months);
        
        // Transform data for chart
        $categories = [];
        $attendedSeries = [];
        $availableSeries = [];
        $rateSeries = [];
        
        foreach ($data as $item) {
            $categories[] = $item['month_name'];
            $attendedSeries[] = (int)$item['events_attended'];
            $availableSeries[] = (int)$item['events_available'];
            $rateSeries[] = (float)$item['attendance_rate'];
        }
        
        return $this->response->setJSON([
            'categories' => $categories,
            'series' => [
                [
                    'name' => 'Events Attended',
                    'type' => 'column',
                    'data' => $attendedSeries,
                    'color' => '#4F46E5'
                ],
                [
                    'name' => 'Events Available',
                    'type' => 'column',
                    'data' => $availableSeries,
                    'color' => '#E5E7EB'
                ],
                [
                    'name' => 'Attendance Rate (%)',
                    'type' => 'line',
                    'yAxis' => 1,
                    'data' => $rateSeries,
                    'color' => '#10B981'
                ]
            ]
        ]);
    }

    /**
     * AJAX endpoint to get user's favorite event categories
     */
    public function getFavoriteCategories()
    {
        $session = session();
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $data = $this->userAnalyticsModel->getUserFavoriteEventCategories($userId);
        
        // Transform data for pie chart
        $chartData = [];
        foreach ($data as $item) {
            $chartData[] = [
                'name' => ucwords(str_replace('_', ' ', $item['category'])),
                'y' => (int)$item['attendance_count'],
                'percentage' => (float)$item['percentage']
            ];
        }
        
        return $this->response->setJSON($chartData);
    }

    /**
     * AJAX endpoint to refresh profile completeness
     */
    public function getProfileCompleteness()
    {
        $session = session();
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $data = $this->userAnalyticsModel->getUserProfileCompleteness($userId);
        
        return $this->response->setJSON($data);
    }

    /**
     * AJAX endpoint to get fresh user summary stats
     */
    public function getUserSummaryStats()
    {
        $session = session();
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $userSummary = $this->userAnalyticsModel->getUserSummary($userId);
        $eventStats = $this->userAnalyticsModel->getUserEventStats($userId);
        $achievements = $this->userAnalyticsModel->getUserAchievements($userId);
        $barangayComparison = $this->userAnalyticsModel->getUserBarangayComparison($userId);
        
        return $this->response->setJSON([
            'user_summary' => $userSummary,
            'event_stats' => $eventStats,
            'achievements' => $achievements,
            'barangay_comparison' => $barangayComparison
        ]);
    }

    /**
     * AJAX endpoint to get recent activity feed
     */
    public function getRecentActivity()
    {
        $session = session();
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $limit = $this->request->getGet('limit') ?? 15;
        $data = $this->userAnalyticsModel->getUserRecentActivity($userId, $limit);
        
        return $this->response->setJSON($data);
    }

    /**
     * AJAX endpoint to get recent attendance details
     */
    public function getRecentAttendance()
    {
        $session = session();
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $limit = $this->request->getGet('limit') ?? 10;
        $data = $this->userAnalyticsModel->getUserRecentAttendance($userId, $limit);
        
        return $this->response->setJSON($data);
    }
}
