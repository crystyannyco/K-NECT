<?php

namespace App\Controllers;

use App\Models\AnalyticsModel;
use App\Libraries\BarangayHelper;

class AnalyticsController extends BaseController
{
    protected $analyticsModel;
    
    public function __construct()
    {
        $this->analyticsModel = new AnalyticsModel();
    }

    /**
     * Pederasyon (City-wide) Analytics Dashboard
     */
    public function pederasyonDashboard()
    {
        $session = session();
        
        // Get analytics data for city-wide view
        $data = [
            'user_id' => $session->get('user_id'),
            'username' => $session->get('username'),
            'view_type' => 'citywide',
            'page_title' => 'City-wide Demographics Analytics',
            'summary' => $this->analyticsModel->getDemographicsSummary(),
            'barangays' => $this->analyticsModel->getBarangays()
        ];

        return 
            $this->loadView('K-NECT/Pederasyon/template/header', $data) .
            $this->loadView('K-NECT/Pederasyon/template/sidebar') .
            $this->loadView('K-NECT/analytics/demographics', $data);
    }

    /**
     * SK (Barangay-specific) Analytics Dashboard
     */
    public function skDashboard()
    {
        $session = session();
        $barangayId = $session->get('sk_barangay');
        $barangayName = BarangayHelper::getBarangayName($barangayId);
        
        // Get analytics data for specific barangay
        $data = [
            'user_id' => $session->get('user_id'),
            'username' => $session->get('username'),
            'sk_barangay' => $barangayId,
            'barangay_name' => $barangayName,
            'view_type' => 'barangay',
            'page_title' => $barangayName . ' Demographics Analytics',
            'summary' => $this->analyticsModel->getDemographicsSummary($barangayId)
        ];

        return 
            $this->loadView('K-NECT/SK/Template/Header', $data) .
            $this->loadView('K-NECT/SK/Template/Sidebar') .
            $this->loadView('K-NECT/analytics/demographics', $data);
    }

    /**
     * AJAX endpoint to get gender distribution data
     */
    public function getGenderDistribution()
    {
        $request = $this->request;
        $barangayId = $request->getGet('barangay_id');
        $viewType = $request->getGet('view_type');
        
        if ($viewType === 'citywide') {
            if ($barangayId && $barangayId !== 'all') {
                $data = $this->analyticsModel->getGenderDistributionPerBarangay($barangayId);
                
                // Transform barangay-specific data for pie chart
                $chartData = [];
                foreach ($data as $item) {
                    $chartData[] = [
                        'name' => $item['gender'],
                        'y' => (int)$item['total']
                    ];
                }
            } else {
                $data = $this->analyticsModel->getGenderDistributionCitywide();
                
                // Transform city-wide data for pie chart
                $chartData = [];
                foreach ($data as $item) {
                    $chartData[] = [
                        'name' => $item['gender'],
                        'y' => (int)$item['total']
                    ];
                }
            }
        } else {
            // SK view - barangay specific
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getGenderDistributionPerBarangay($skBarangay);
            
            // Transform data for pie chart
            $chartData = [];
            foreach ($data as $item) {
                $chartData[] = [
                    'name' => $item['gender'],
                    'y' => (int)$item['total']
                ];
            }
        }
        
        return $this->response->setJSON($chartData);
    }

    /**
     * AJAX endpoint to get age group distribution data
     */
    public function getAgeGroupDistribution()
    {
        $request = $this->request;
        $barangayId = $request->getGet('barangay_id');
        $viewType = $request->getGet('view_type');
        
        if ($viewType === 'citywide') {
            $filterBarangay = ($barangayId && $barangayId !== 'all') ? $barangayId : null;
            $data = $this->analyticsModel->getAgeGroupDistribution($filterBarangay);
        } else {
            // SK view - barangay specific
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getAgeGroupDistribution($skBarangay);
        }
        
        // Transform data for bar chart
        $categories = [];
        $series = [];
        
        foreach ($data as $item) {
            $categories[] = $item['age_group'];
            $series[] = (int)$item['total'];
        }
        
        return $this->response->setJSON([
            'categories' => $categories,
            'series' => $series
        ]);
    }

    /**
     * AJAX endpoint to get youth classification distribution data
     */
    public function getYouthClassificationDistribution()
    {
        $request = $this->request;
        $barangayId = $request->getGet('barangay_id');
        $viewType = $request->getGet('view_type');
        
        if ($viewType === 'citywide') {
            $filterBarangay = ($barangayId && $barangayId !== 'all') ? $barangayId : null;
            $data = $this->analyticsModel->getYouthClassificationDistribution($filterBarangay);
        } else {
            // SK view - barangay specific
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getYouthClassificationDistribution($skBarangay);
        }
        
        // Transform data for donut chart
        $chartData = [];
        foreach ($data as $item) {
            $chartData[] = [
                'name' => $item['voter_classification'],
                'y' => (int)$item['total']
            ];
        }
        
        return $this->response->setJSON($chartData);
    }

    /**
     * AJAX endpoint to get civil status distribution data
     */
    public function getCivilStatusDistribution()
    {
        $request = $this->request;
        $barangayId = $request->getGet('barangay_id');
        $viewType = $request->getGet('view_type');
        
        if ($viewType === 'citywide') {
            $filterBarangay = ($barangayId && $barangayId !== 'all') ? $barangayId : null;
            $data = $this->analyticsModel->getCivilStatusDistribution($filterBarangay);
        } else {
            // SK view - barangay specific
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getCivilStatusDistribution($skBarangay);
        }
        
        // Transform data for pie chart
        $chartData = [];
        foreach ($data as $item) {
            $chartData[] = [
                'name' => $item['civil_status'],
                'y' => (int)$item['total']
            ];
        }
        
        return $this->response->setJSON($chartData);
    }

    /**
     * AJAX endpoint to get work status distribution data
     */
    public function getWorkStatusDistribution()
    {
        $request = $this->request;
        $barangayId = $request->getGet('barangay_id');
        $viewType = $request->getGet('view_type');
        
        if ($viewType === 'citywide') {
            $filterBarangay = ($barangayId && $barangayId !== 'all') ? $barangayId : null;
            $data = $this->analyticsModel->getWorkStatusDistribution($filterBarangay);
        } else {
            // SK view - barangay specific
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getWorkStatusDistribution($skBarangay);
        }
        
        // Transform data for bar chart
        $categories = [];
        $series = [];
        
        foreach ($data as $item) {
            $categories[] = $item['work_status'];
            $series[] = (int)$item['total'];
        }
        
        return $this->response->setJSON([
            'categories' => $categories,
            'series' => $series
        ]);
    }

    /**
     * AJAX endpoint to get educational background distribution data
     */
    public function getEducationalBackgroundDistribution()
    {
        $request = $this->request;
        $barangayId = $request->getGet('barangay_id');
        $viewType = $request->getGet('view_type');
        
        if ($viewType === 'citywide') {
            $filterBarangay = ($barangayId && $barangayId !== 'all') ? $barangayId : null;
            $data = $this->analyticsModel->getEducationalBackgroundDistribution($filterBarangay);
        } else {
            // SK view - barangay specific
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getEducationalBackgroundDistribution($skBarangay);
        }
        
        // Transform data for horizontal bar chart
        $categories = [];
        $series = [];
        
        foreach ($data as $item) {
            $categories[] = $item['educational_background'];
            $series[] = (int)$item['total'];
        }
        
        return $this->response->setJSON([
            'categories' => $categories,
            'series' => $series
        ]);
    }

    /**
     * AJAX endpoint to get gender distribution per barangay for city-wide view
     */
    public function getGenderByBarangay()
    {
        $data = $this->analyticsModel->getGenderDistributionPerBarangay();
        
        // Transform data for stacked bar chart
        $barangays = [];
        $maleData = [];
        $femaleData = [];
        
        // Group by barangay
        $groupedData = [];
        foreach ($data as $item) {
            $barangay = $item['barangay'];
            $gender = $item['gender'];
            $total = (int)$item['total'];
            
            if (!isset($groupedData[$barangay])) {
                $groupedData[$barangay] = ['Male' => 0, 'Female' => 0];
            }
            $groupedData[$barangay][$gender] = $total;
        }
        
        // Convert to chart format
        foreach ($groupedData as $barangay => $genderCounts) {
            $barangays[] = $barangay;
            $maleData[] = $genderCounts['Male'];
            $femaleData[] = $genderCounts['Female'];
        }
        
        return $this->response->setJSON([
            'categories' => $barangays,
            'series' => [
                [
                    'name' => 'Male',
                    'data' => $maleData,
                    'color' => '#4A90E2'
                ],
                [
                    'name' => 'Female', 
                    'data' => $femaleData,
                    'color' => '#E24A90'
                ]
            ]
        ]);
    }

    // ============= EVENT ANALYTICS ENDPOINTS ============= //

    /**
     * Event Analytics Dashboard
     */
    public function eventAnalytics()
    {
        $session = session();
        $userType = $session->get('user_type');
        
        if ($userType == 3) { // Pederasyon
            return $this->pederasyonEventAnalytics();
        } else { // SK
            return $this->skEventAnalytics();
        }
    }

    /**
     * Pederasyon Event Analytics Dashboard
     */
    public function pederasyonEventAnalytics()
    {
        $session = session();
        
        $data = [
            'user_id' => $session->get('user_id'),
            'username' => $session->get('username'),
            'view_type' => 'citywide',
            'page_title' => 'Event Analytics Dashboard',
            'event_summary' => $this->analyticsModel->getEventSummary(),
            'barangays' => $this->analyticsModel->getBarangays()
        ];

        return 
            $this->loadView('K-NECT/Pederasyon/template/header', $data) .
            $this->loadView('K-NECT/Pederasyon/template/sidebar') .
            $this->loadView('K-NECT/analytics/events', $data);
    }

    /**
     * SK Event Analytics Dashboard
     */
    public function skEventAnalytics()
    {
        $session = session();
        $barangayId = $session->get('sk_barangay');
        $barangayName = BarangayHelper::getBarangayName($barangayId);
        
        $data = [
            'user_id' => $session->get('user_id'),
            'username' => $session->get('username'),
            'sk_barangay' => $barangayId,
            'barangay_name' => $barangayName,
            'view_type' => 'barangay',
            'page_title' => $barangayName . ' Event Analytics',
            'event_summary' => $this->analyticsModel->getEventSummary($barangayId)
        ];

        return 
            $this->loadView('K-NECT/SK/Template/Header', $data) .
            $this->loadView('K-NECT/SK/Template/Sidebar') .
            $this->loadView('K-NECT/analytics/events', $data);
    }

    /**
     * Get event participation trend data
     */
    public function getEventParticipationTrend()
    {
        $request = $this->request;
        $barangayId = $request->getGet('barangay_id');
        $viewType = $request->getGet('view_type');
        $months = $request->getGet('months') ?? 12;
        
        if ($viewType === 'citywide') {
            $filterBarangay = ($barangayId && $barangayId !== 'all') ? $barangayId : null;
            $data = $this->analyticsModel->getEventParticipationTrend($filterBarangay, $months);
        } else {
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getEventParticipationTrend($skBarangay, $months);
        }
        
        // Transform data for line chart
        $categories = [];
        $series = [];
        
        foreach ($data as $item) {
            $categories[] = $item['month_name'];
            $series[] = (int)$item['total_participants'];
        }
        
        return $this->response->setJSON([
            'categories' => $categories,
            'series' => [$series]
        ]);
    }

    /**
     * Get top engaged barangays
     */
    public function getTopEngagedBarangays()
    {
        $data = $this->analyticsModel->getTopEngagedBarangays();
        
        // Transform data for horizontal bar chart
        $categories = [];
        $series = [];
        
        foreach ($data as $item) {
            $categories[] = $item['barangay'];
            $series[] = (int)$item['total_participants'];
        }
        
        return $this->response->setJSON([
            'categories' => $categories,
            'series' => $series
        ]);
    }

    /**
     * Get top active members
     */
    public function getTopActiveMembers()
    {
        $request = $this->request;
        $barangayId = $request->getGet('barangay_id');
        $viewType = $request->getGet('view_type');
        
        if ($viewType === 'citywide') {
            $filterBarangay = ($barangayId && $barangayId !== 'all') ? $barangayId : null;
            $data = $this->analyticsModel->getTopActiveMembers($filterBarangay);
        } else {
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getTopActiveMembers($skBarangay);
        }
        
        return $this->response->setJSON($data);
    }

    /**
     * Get attendance consistency
     */
    public function getAttendanceConsistency()
    {
        $request = $this->request;
        $barangayId = $request->getGet('barangay_id');
        $viewType = $request->getGet('view_type');
        
        if ($viewType === 'citywide') {
            $filterBarangay = ($barangayId && $barangayId !== 'all') ? $barangayId : null;
            $data = $this->analyticsModel->getAttendanceConsistency($filterBarangay);
        } else {
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getAttendanceConsistency($skBarangay);
        }
        
        return $this->response->setJSON($data);
    }

    /**
     * Get most popular event categories
     */
    public function getMostPopularEventCategories()
    {
        $request = $this->request;
        $barangayId = $request->getGet('barangay_id');
        $viewType = $request->getGet('view_type');
        
        if ($viewType === 'citywide') {
            $filterBarangay = ($barangayId && $barangayId !== 'all') ? $barangayId : null;
            $data = $this->analyticsModel->getMostPopularEventCategories($filterBarangay);
        } else {
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getMostPopularEventCategories($skBarangay);
        }
        
        // Transform data for bar chart
        $categories = [];
        $series = [];
        
        foreach ($data as $item) {
            $categories[] = ucwords(str_replace('_', ' ', $item['category']));
            $series[] = (int)$item['total_participants'];
        }
        
        return $this->response->setJSON([
            'categories' => $categories,
            'series' => $series
        ]);
    }

    /**
     * Get event reach heatmap data
     */
    public function getEventReach()
    {
        $data = $this->analyticsModel->getEventReach();
        
        // Transform data for heatmap
        $barangays = [];
        $categories = [];
        $heatmapData = [];
        
        // Get unique barangays and categories
        foreach ($data as $item) {
            if (!in_array($item['barangay'], $barangays)) {
                $barangays[] = $item['barangay'];
            }
            if (!in_array($item['category'], $categories)) {
                $categories[] = $item['category'];
            }
        }
        
        // Create heatmap data array
        foreach ($data as $item) {
            $barangayIndex = array_search($item['barangay'], $barangays);
            $categoryIndex = array_search($item['category'], $categories);
            $heatmapData[] = [$categoryIndex, $barangayIndex, (int)$item['participants']];
        }
        
        return $this->response->setJSON([
            'barangays' => $barangays,
            'categories' => array_map(function($cat) { 
                return ucwords(str_replace('_', ' ', $cat)); 
            }, $categories),
            'data' => $heatmapData
        ]);
    }

    /**
     * Get participation by gender per event
     */
    public function getParticipationByGenderPerEvent()
    {
        $request = $this->request;
        $barangayId = $request->getGet('barangay_id');
        $viewType = $request->getGet('view_type');
        
        if ($viewType === 'citywide') {
            $filterBarangay = ($barangayId && $barangayId !== 'all') ? $barangayId : null;
            $data = $this->analyticsModel->getParticipationByGenderPerEvent($filterBarangay);
        } else {
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getParticipationByGenderPerEvent($skBarangay);
        }
        
        // Transform data for stacked bar chart
        $events = [];
        $maleData = [];
        $femaleData = [];
        $eventTitles = [];
        
        // Group by event
        $groupedData = [];
        foreach ($data as $item) {
            $eventTitle = $item['event_title'];
            if (!isset($groupedData[$eventTitle])) {
                $groupedData[$eventTitle] = ['Male' => 0, 'Female' => 0];
                $eventTitles[] = $eventTitle;
            }
            $groupedData[$eventTitle][$item['gender']] = (int)$item['participants'];
        }
        
        // Convert to chart format
        foreach ($eventTitles as $title) {
            $maleData[] = $groupedData[$title]['Male'];
            $femaleData[] = $groupedData[$title]['Female'];
        }
        
        return $this->response->setJSON([
            'categories' => $eventTitles,
            'series' => [
                [
                    'name' => 'Male',
                    'data' => $maleData,
                    'color' => '#4A90E2'
                ],
                [
                    'name' => 'Female',
                    'data' => $femaleData,
                    'color' => '#E24A90'
                ]
            ]
        ]);
    }

    // ============= DOCUMENT ANALYTICS ENDPOINTS ============= //

    /**
     * Document Analytics Dashboard
     */
    public function documentAnalytics()
    {
        $session = session();
        $userType = $session->get('user_type');
        
        if ($userType == 3) { // Pederasyon
            return $this->pederasyonDocumentAnalytics();
        } else { // SK
            return $this->skDocumentAnalytics();
        }
    }

    /**
     * Pederasyon Document Analytics Dashboard
     */
    public function pederasyonDocumentAnalytics()
    {
        $session = session();
        
        $data = [
            'user_id' => $session->get('user_id'),
            'username' => $session->get('username'),
            'view_type' => 'citywide',
            'page_title' => 'Document Analytics Dashboard',
            'document_summary' => $this->analyticsModel->getDocumentSummary()
        ];

        return 
            $this->loadView('K-NECT/Pederasyon/template/header', $data) .
            $this->loadView('K-NECT/Pederasyon/template/sidebar') .
            $this->loadView('K-NECT/analytics/documents', $data);
    }

    /**
     * SK Document Analytics Dashboard
     */
    public function skDocumentAnalytics()
    {
        $session = session();
        $barangayId = $session->get('sk_barangay');
        $barangayName = BarangayHelper::getBarangayName($barangayId);
        
        $data = [
            'user_id' => $session->get('user_id'),
            'username' => $session->get('username'),
            'sk_barangay' => $barangayId,
            'barangay_name' => $barangayName,
            'view_type' => 'barangay',
            'page_title' => $barangayName . ' Document Analytics',
            'document_summary' => $this->analyticsModel->getDocumentSummary()
        ];

        return 
            $this->loadView('K-NECT/SK/Template/Header', $data) .
            $this->loadView('K-NECT/SK/Template/Sidebar') .
            $this->loadView('K-NECT/analytics/documents', $data);
    }

    /**
     * Get most accessed document categories
     */
    public function getMostAccessedDocumentCategories()
    {
        $data = $this->analyticsModel->getMostAccessedDocumentCategories();
        
        // Transform data for bar chart
        $categories = [];
        $series = [];
        
        foreach ($data as $item) {
            $categories[] = $item['category'];
            $series[] = (int)$item['total_downloads'];
        }
        
        return $this->response->setJSON([
            'categories' => $categories,
            'series' => $series
        ]);
    }

    /**
     * Get document approval time
     */
    public function getDocumentApprovalTime()
    {
        $data = $this->analyticsModel->getDocumentApprovalTime();
        
        // Transform data for histogram
        $categories = [];
        $series = [];
        
        foreach ($data as $item) {
            $days = (int)$item['approval_days'];
            $dayRange = $days == 0 ? 'Same day' : 
                       ($days == 1 ? '1 day' : 
                       ($days <= 3 ? '2-3 days' : 
                       ($days <= 7 ? '4-7 days' : 
                       ($days <= 14 ? '1-2 weeks' : '2+ weeks'))));
            
            if (!isset($categories[$dayRange])) {
                $categories[$dayRange] = 0;
            }
            $categories[$dayRange] += (int)$item['document_count'];
        }
        
        return $this->response->setJSON([
            'categories' => array_keys($categories),
            'series' => array_values($categories)
        ]);
    }

    /**
     * Get top downloaded documents
     */
    public function getTopDownloadedDocuments()
    {
        $data = $this->analyticsModel->getTopDownloadedDocuments();
        return $this->response->setJSON($data);
    }

    // ============= PERFORMANCE ANALYTICS ENDPOINTS ============= //

    /**
     * Performance Analytics Dashboard
     */
    public function performanceAnalytics()
    {
        $session = session();
        $userType = $session->get('user_type');
        
        if ($userType == 3) { // Pederasyon
            return $this->pederasyonPerformanceAnalytics();
        } else { // SK
            return $this->skPerformanceAnalytics();
        }
    }

    /**
     * Pederasyon Performance Analytics Dashboard
     */
    public function pederasyonPerformanceAnalytics()
    {
        $session = session();
        
        $data = [
            'user_id' => $session->get('user_id'),
            'username' => $session->get('username'),
            'view_type' => 'citywide',
            'page_title' => 'Performance Analytics Dashboard',
            'barangays' => $this->analyticsModel->getBarangays()
        ];

        return 
            $this->loadView('K-NECT/Pederasyon/template/header', $data) .
            $this->loadView('K-NECT/Pederasyon/template/sidebar') .
            $this->loadView('K-NECT/analytics/performance', $data);
    }

    /**
     * SK Performance Analytics Dashboard
     */
    public function skPerformanceAnalytics()
    {
        $session = session();
        $barangayId = $session->get('sk_barangay');
        $barangayName = BarangayHelper::getBarangayName($barangayId);
        
        $data = [
            'user_id' => $session->get('user_id'),
            'username' => $session->get('username'),
            'sk_barangay' => $barangayId,
            'barangay_name' => $barangayName,
            'view_type' => 'barangay',
            'page_title' => $barangayName . ' Performance Analytics'
        ];

        return 
            $this->loadView('K-NECT/SK/Template/Header', $data) .
            $this->loadView('K-NECT/SK/Template/Sidebar') .
            $this->loadView('K-NECT/analytics/performance', $data);
    }

    /**
     * Get barangay performance score
     */
    public function getBarangayPerformanceScore()
    {
        $request = $this->request;
        $barangayId = $request->getGet('barangay_id');
        $viewType = $request->getGet('view_type');
        
        if ($viewType === 'citywide') {
            $filterBarangay = ($barangayId && $barangayId !== 'all') ? $barangayId : null;
            $data = $this->analyticsModel->getBarangayPerformanceScore($filterBarangay);
        } else {
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getBarangayPerformanceScore($skBarangay);
        }
        
        return $this->response->setJSON($data);
    }

    /**
     * Get inactive members
     */
    public function getInactiveMembers()
    {
        $request = $this->request;
        $barangayId = $request->getGet('barangay_id');
        $viewType = $request->getGet('view_type');
        $inactiveDays = $request->getGet('inactive_days') ?? 90;
        
        if ($viewType === 'citywide') {
            $filterBarangay = ($barangayId && $barangayId !== 'all') ? $barangayId : null;
            $data = $this->analyticsModel->getInactiveMembers($filterBarangay, $inactiveDays);
        } else {
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getInactiveMembers($skBarangay, $inactiveDays);
        }
        
        return $this->response->setJSON($data);
    }
}