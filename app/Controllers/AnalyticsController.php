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
     * Helper method to standardize barangay filtering logic
     * @param string $barangayId The barangay filter value from request
     * @return array ['type' => 'all'|'city-wide'|'specific', 'id' => null|0|barangay_id]
     */
    private function parseBarangayFilter($barangayId)
    {
        if ($barangayId === 'city-wide') {
            return ['type' => 'city-wide', 'id' => 0];
        } elseif ($barangayId === 'all' || !$barangayId) {
            return ['type' => 'all', 'id' => null];
        } else {
            return ['type' => 'specific', 'id' => (int)$barangayId];
        }
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
            } else {
                $data = $this->analyticsModel->getGenderDistributionCitywide();
            }
        } else {
            // SK view - barangay specific
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getGenderDistributionPerBarangay($skBarangay);
        }
        
        // Initialize with both genders to ensure complete data structure
        $genderCounts = [
            'Male' => 0,
            'Female' => 0
        ];
        
        // Populate actual counts from database
        foreach ($data as $item) {
            $genderCounts[$item['gender']] = (int)$item['total'];
        }
        
        // Transform to chart format
        $chartData = [];
        foreach ($genderCounts as $gender => $count) {
            $chartData[] = [
                'name' => $gender,
                'y' => $count
            ];
        }
        
        // If one gender has zero count, filter it out to prevent empty slices
        $chartData = array_filter($chartData, function($item) {
            return $item['y'] > 0;
        });
        
        // Re-index array to ensure proper JSON formatting
        $chartData = array_values($chartData);
        
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
            $filterBarangay = ($barangayId && $barangayId !== 'all' && $barangayId !== 'city-wide') ? $barangayId : null;
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
     * Get top active SK officials
     */
    public function getTopActiveSKOfficials()
    {
        $request = $this->request;
        $barangayId = $request->getGet('barangay_id');
        $viewType = $request->getGet('view_type');
        
        if ($viewType === 'citywide') {
            $filterBarangay = ($barangayId && $barangayId !== 'all') ? $barangayId : null;
            $data = $this->analyticsModel->getTopActiveSKOfficials($filterBarangay, 5);
        } else {
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getTopActiveSKOfficials($skBarangay, 5);
        }
        
        return $this->response->setJSON($data);
    }

    /**
     * Get top active KK members
     */
    public function getTopActiveKKMembers()
    {
        $request = $this->request;
        $barangayId = $request->getGet('barangay_id');
        $viewType = $request->getGet('view_type');
        
        if ($viewType === 'citywide') {
            $filterBarangay = ($barangayId && $barangayId !== 'all') ? $barangayId : null;
            $data = $this->analyticsModel->getTopActiveKKMembers($filterBarangay, 5);
        } else {
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getTopActiveKKMembers($skBarangay, 5);
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
            'document_summary' => $this->analyticsModel->getDocumentSummary(),
            'barangays' => $this->analyticsModel->getBarangays()
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
            'document_summary' => $this->analyticsModel->getDocumentSummary($barangayId)
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
        $request = $this->request;
        $barangayId = $request->getGet('barangay_id');
        $viewType = $request->getGet('view_type');
        
        if ($viewType === 'citywide') {
            $filterBarangay = ($barangayId && $barangayId !== 'all' && $barangayId !== 'city-wide') ? $barangayId : null;
            $data = $this->analyticsModel->getMostAccessedDocumentCategories($filterBarangay);
        } else {
            // SK view - barangay specific
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getMostAccessedDocumentCategories($skBarangay);
        }
        
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
        $request = $this->request;
        $barangayId = $request->getGet('barangay_id');
        $viewType = $request->getGet('view_type');
        
        if ($viewType === 'citywide') {
            $filterBarangay = ($barangayId && $barangayId !== 'all' && $barangayId !== 'city-wide') ? $barangayId : null;
            $data = $this->analyticsModel->getDocumentApprovalTime($filterBarangay);
        } else {
            // SK view - barangay specific
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getDocumentApprovalTime($skBarangay);
        }
        
        // Transform data for histogram
        $groupedData = [];
        
        foreach ($data as $item) {
            $days = (int)$item['approval_days'];
            $dayRange = $days == 0 ? 'Same day' : 
                       ($days == 1 ? '1 day' : 
                       ($days <= 3 ? '2-3 days' : 
                       ($days <= 7 ? '4-7 days' : 
                       ($days <= 14 ? '1-2 weeks' : '2+ weeks'))));
            
            if (!isset($groupedData[$dayRange])) {
                $groupedData[$dayRange] = 0;
            }
            $groupedData[$dayRange] += (int)$item['document_count'];
        }
        
        return $this->response->setJSON([
            'categories' => array_keys($groupedData),
            'series' => array_values($groupedData)
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
        try {
            $request = $this->request;
            $barangayId = $request->getGet('barangay_id');
            $viewType = $request->getGet('view_type');
            
            if ($viewType === 'citywide') {
                $filterBarangay = ($barangayId && $barangayId !== 'all') ? $barangayId : null;
                $data = $this->analyticsModel->getBarangayPerformanceScore($filterBarangay, 'citywide');
            } else {
                $session = session();
                $skBarangay = $session->get('sk_barangay');
                $data = $this->analyticsModel->getBarangayPerformanceScore($skBarangay, 'sk');
            }
            
            // Clean up the data - remove debug columns
            $cleanData = [];
            foreach ($data as $row) {
                $cleanData[] = [
                    'barangay' => $row['barangay'],
                    'event_participation_score' => $row['event_participation_score'],
                    'document_activity_score' => $row['document_activity_score'],
                    'attendance_consistency_score' => $row['attendance_consistency_score']
                ];
            }
            
            // Log the number of barangays returned
            log_message('info', "Returning performance data for " . count($cleanData) . " barangays (view: {$viewType})");
            
            return $this->response->setJSON($cleanData);
        } catch (\Exception $e) {
            log_message('error', 'Error in getBarangayPerformanceScore: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to load performance data']);
        }
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

    /**
     * Get filtered demographics summary data
     */
    public function getFilteredDemographicsSummary()
    {
        $request = $this->request;
        $barangayId = $request->getGet('barangay_id');
        $viewType = $request->getGet('view_type');
        
        if ($viewType === 'citywide') {
            $filterBarangay = ($barangayId && $barangayId !== 'all' && $barangayId !== 'city-wide') ? $barangayId : null;
            $data = $this->analyticsModel->getDemographicsSummary($filterBarangay);
        } else {
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getDemographicsSummary($skBarangay);
        }
        
        return $this->response->setJSON($data);
    }

    /**
     * Get filtered event summary data
     */
    public function getFilteredEventSummary()
    {
        $request = $this->request;
        $barangayId = $request->getGet('barangay_id');
        $viewType = $request->getGet('view_type');
        
        if ($viewType === 'citywide') {
            if ($barangayId === 'city-wide') {
                // Show only city-wide/superadmin events (barangay_id = 0)
                $data = $this->analyticsModel->getEventSummary(0);
            } elseif ($barangayId === 'all') {
                // Show all barangays except city-wide (barangay_id > 0)
                $data = $this->analyticsModel->getEventSummaryAllBarangays();
            } elseif ($barangayId && $barangayId !== 'all' && $barangayId !== 'city-wide') {
                // Show specific barangay
                $data = $this->analyticsModel->getEventSummary($barangayId);
            } else {
                // Default to all barangays (excluding city-wide)
                $data = $this->analyticsModel->getEventSummaryAllBarangays();
            }
        } else {
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getEventSummary($skBarangay);
        }
        
        return $this->response->setJSON($data);
    }

    /**
     * Get filtered document summary data
     */
    public function getFilteredDocumentSummary()
    {
        $request = $this->request;
        $barangayId = $request->getGet('barangay_id');
        $viewType = $request->getGet('view_type');
        
        if ($viewType === 'citywide') {
            if ($barangayId === 'city-wide') {
                // Show only city-wide/superadmin documents (barangay_id = 0)
                $data = $this->analyticsModel->getDocumentSummary(0);
            } elseif ($barangayId === 'all') {
                // Show all barangays except city-wide (barangay_id > 0)
                $data = $this->analyticsModel->getDocumentSummaryAllBarangays();
            } elseif ($barangayId && $barangayId !== 'all' && $barangayId !== 'city-wide') {
                // Show specific barangay
                $data = $this->analyticsModel->getDocumentSummary($barangayId);
            } else {
                // Default to all barangays (excluding city-wide)
                $data = $this->analyticsModel->getDocumentSummaryAllBarangays();
            }
        } else {
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getDocumentSummary($skBarangay);
        }
        
        return $this->response->setJSON($data);
    }

    /**
     * AJAX endpoint to get gender identity distribution data
     */
    public function getGenderIdentityDistribution()
    {
        $request = $this->request;
        $barangayId = $request->getGet('barangay_id');
        $viewType = $request->getGet('view_type');
        
        if ($viewType === 'citywide') {
            if ($barangayId && $barangayId !== 'all') {
                $data = $this->analyticsModel->getGenderIdentityDistributionPerBarangay($barangayId);
            } else {
                $data = $this->analyticsModel->getGenderIdentityDistributionCitywide();
            }
        } else {
            // SK view - barangay specific
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getGenderIdentityDistributionPerBarangay($skBarangay);
        }
        
        return $this->response->setJSON($data);
    }

    /**
     * AJAX endpoint to get combined sex and gender analytics
     */
    public function getCombinedGenderAnalytics()
    {
        $request = $this->request;
        $barangayId = $request->getGet('barangay_id');
        $viewType = $request->getGet('view_type');
        
        if ($viewType === 'citywide') {
            if ($barangayId && $barangayId !== 'all') {
                $data = $this->analyticsModel->getCombinedGenderAnalytics($barangayId);
            } else {
                $data = $this->analyticsModel->getCombinedGenderAnalytics();
            }
        } else {
            // SK view - barangay specific
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getCombinedGenderAnalytics($skBarangay);
        }
        
        return $this->response->setJSON($data);
    }

    /**
     * AJAX endpoint to get participation by gender identity per event
     */
    public function getParticipationByGenderIdentity()
    {
        $request = $this->request;
        $barangayId = $request->getGet('barangay_id');
        $viewType = $request->getGet('view_type');
        $limit = $request->getGet('limit') ?: 10;
        
        if ($viewType === 'citywide') {
            if ($barangayId && $barangayId !== 'all') {
                $data = $this->analyticsModel->getParticipationByGenderIdentityPerEvent($barangayId, $limit);
            } else {
                $data = $this->analyticsModel->getParticipationByGenderIdentityPerEvent(null, $limit);
            }
        } else {
            // SK view - barangay specific
            $session = session();
            $skBarangay = $session->get('sk_barangay');
            $data = $this->analyticsModel->getParticipationByGenderIdentityPerEvent($skBarangay, $limit);
        }
        
        return $this->response->setJSON($data);
    }
}