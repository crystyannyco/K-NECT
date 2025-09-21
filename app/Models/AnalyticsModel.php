<?php

namespace App\Models;

use CodeIgniter\Model;

class AnalyticsModel extends Model
{
    protected $db;
    
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    /**
     * Get gender distribution city-wide
     */
    public function getGenderDistributionCitywide()
    {
        $query = $this->db->query("
            SELECT 
                CASE 
                    WHEN sex = 1 THEN 'Male' 
                    WHEN sex = 0 THEN 'Female'
                    ELSE 'Female' 
                END AS gender,
                COUNT(*) AS total
            FROM user
            WHERE is_active = 1 AND status = 2
            GROUP BY 
                CASE 
                    WHEN sex = 1 THEN 'Male' 
                    WHEN sex = 0 THEN 'Female'
                    ELSE 'Female' 
                END
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get gender distribution per barangay
     */
    public function getGenderDistributionPerBarangay($barangayId = null)
    {
        $whereClause = "WHERE u.is_active = 1 AND u.status = 2";
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND a.barangay = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                b.name AS barangay,
                CASE 
                    WHEN u.sex = 1 THEN 'Male' 
                    WHEN u.sex = 0 THEN 'Female'
                    ELSE 'Female' 
                END AS gender,
                COUNT(*) AS total
            FROM user u
            JOIN address a ON u.id = a.user_id
            JOIN barangay b ON a.barangay = b.barangay_id
            {$whereClause}
            GROUP BY b.name, 
                CASE 
                    WHEN u.sex = 1 THEN 'Male' 
                    WHEN u.sex = 0 THEN 'Female'
                    ELSE 'Female' 
                END
            ORDER BY b.name, gender
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get age group distribution
     */
    public function getAgeGroupDistribution($barangayId = null)
    {
        $whereClause = "WHERE u.is_active = 1 AND u.status = 2";
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND a.barangay = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                CASE 
                    WHEN TIMESTAMPDIFF(YEAR, u.birthdate, CURDATE()) BETWEEN 15 AND 17 THEN '15-17'
                    WHEN TIMESTAMPDIFF(YEAR, u.birthdate, CURDATE()) BETWEEN 18 AND 21 THEN '18-21'
                    WHEN TIMESTAMPDIFF(YEAR, u.birthdate, CURDATE()) BETWEEN 22 AND 24 THEN '22-24'
                    ELSE '25+' 
                END AS age_group,
                COUNT(*) AS total
            FROM user u
            LEFT JOIN address a ON u.id = a.user_id
            {$whereClause}
            GROUP BY age_group
            ORDER BY 
                CASE age_group 
                    WHEN '15-17' THEN 1
                    WHEN '18-21' THEN 2
                    WHEN '22-24' THEN 3
                    WHEN '25+' THEN 4
                END
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get youth classification distribution
     */
    public function getYouthClassificationDistribution($barangayId = null)
    {
        $joinClause = "LEFT JOIN address a ON u.id = a.user_id";
        $whereClause = "WHERE u.is_active = 1 AND u.status = 2 AND uei.sk_voter IS NOT NULL";
        
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND a.barangay = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                CASE 
                    WHEN uei.sk_voter = 1 AND uei.national_voter = 1 THEN 'Both SK & National Voter'
                    WHEN uei.sk_voter = 1 AND uei.national_voter = 0 THEN 'SK Voter Only'
                    WHEN uei.sk_voter = 0 AND uei.national_voter = 1 THEN 'National Voter Only'
                    ELSE 'Non-Voter'
                END AS voter_classification,
                COUNT(*) AS total
            FROM user u
            JOIN user_ext_info uei ON u.id = uei.user_id
            {$joinClause}
            {$whereClause}
            GROUP BY voter_classification
            ORDER BY total DESC
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get total users count
     */
    public function getTotalUsersCount($barangayId = null)
    {
        $whereClause = "WHERE u.is_active = 1 AND u.status = 2";
        $joinClause = "";
        
        if ($barangayId !== null && $barangayId > 0) {
            $joinClause = "JOIN address a ON u.id = a.user_id";
            $whereClause .= " AND a.barangay = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT COUNT(*) as total
            FROM user u
            {$joinClause}
            {$whereClause}
        ");
        
        $result = $query->getRow();
        return $result ? $result->total : 0;
    }

    /**
     * Get barangay list
     */
    public function getBarangays()
    {
        $query = $this->db->query("
            SELECT barangay_id, name
            FROM barangay
            WHERE barangay_id > 0
            ORDER BY name
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get barangay name by ID
     */
    public function getBarangayName($barangayId)
    {
        $query = $this->db->query("
            SELECT name
            FROM barangay
            WHERE barangay_id = ?
        ", [$barangayId]);
        
        $result = $query->getRow();
        return $result ? $result->name : 'Unknown';
    }

    /**
     * Get civil status distribution
     */
    public function getCivilStatusDistribution($barangayId = null)
    {
        $joinClause = "LEFT JOIN address a ON u.id = a.user_id";
        $whereClause = "WHERE u.is_active = 1 AND u.status = 2 AND uei.civil_status IS NOT NULL";
        
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND a.barangay = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                CASE 
                    WHEN uei.civil_status = 1 THEN 'Single'
                    WHEN uei.civil_status = 2 THEN 'Married'
                    WHEN uei.civil_status = 3 THEN 'Widowed'
                    WHEN uei.civil_status = 4 THEN 'Separated'
                    WHEN uei.civil_status = 5 THEN 'Divorced'
                    ELSE 'Not Specified'
                END AS civil_status,
                COUNT(*) AS total
            FROM user u
            JOIN user_ext_info uei ON u.id = uei.user_id
            {$joinClause}
            {$whereClause}
            GROUP BY civil_status
            ORDER BY total DESC
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get work status distribution
     */
    public function getWorkStatusDistribution($barangayId = null)
    {
        $joinClause = "LEFT JOIN address a ON u.id = a.user_id";
        $whereClause = "WHERE u.is_active = 1 AND u.status = 2 AND uei.work_status IS NOT NULL";
        
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND a.barangay = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                CASE 
                    WHEN uei.work_status = 1 THEN 'Employed'
                    WHEN uei.work_status = 2 THEN 'Unemployed'
                    WHEN uei.work_status = 3 THEN 'Student'
                    WHEN uei.work_status = 4 THEN 'Self-Employed'
                    WHEN uei.work_status = 5 THEN 'Out of School Youth'
                    ELSE 'Not Specified'
                END AS work_status,
                COUNT(*) AS total
            FROM user u
            JOIN user_ext_info uei ON u.id = uei.user_id
            {$joinClause}
            {$whereClause}
            GROUP BY work_status
            ORDER BY total DESC
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get educational background distribution
     */
    public function getEducationalBackgroundDistribution($barangayId = null)
    {
        $joinClause = "LEFT JOIN address a ON u.id = a.user_id";
        $whereClause = "WHERE u.is_active = 1 AND u.status = 2 AND uei.educational_background IS NOT NULL";
        
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND a.barangay = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                CASE 
                    WHEN uei.educational_background = 1 THEN 'Elementary'
                    WHEN uei.educational_background = 2 THEN 'High School'
                    WHEN uei.educational_background = 3 THEN 'Senior High School'
                    WHEN uei.educational_background = 4 THEN 'College'
                    WHEN uei.educational_background = 5 THEN 'Vocational'
                    WHEN uei.educational_background = 6 THEN 'Post Graduate'
                    ELSE 'Not Specified'
                END AS educational_background,
                COUNT(*) AS total
            FROM user u
            JOIN user_ext_info uei ON u.id = uei.user_id
            {$joinClause}
            {$whereClause}
            GROUP BY educational_background
            ORDER BY 
                CASE educational_background
                    WHEN 'Elementary' THEN 1
                    WHEN 'High School' THEN 2
                    WHEN 'Senior High School' THEN 3
                    WHEN 'Vocational' THEN 4
                    WHEN 'College' THEN 5
                    WHEN 'Post Graduate' THEN 6
                    ELSE 7
                END
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get demographics summary for dashboard cards
     */
    public function getDemographicsSummary($barangayId = null)
    {
        $totalUsers = $this->getTotalUsersCount($barangayId);
        $genderData = $barangayId ? $this->getGenderDistributionPerBarangay($barangayId) : $this->getGenderDistributionCitywide();
        $ageData = $this->getAgeGroupDistribution($barangayId);
        
        // Calculate gender percentages
        $maleCount = 0;
        $femaleCount = 0;
        
        if ($barangayId) {
            // For barangay-specific data, sum up the totals
            foreach ($genderData as $item) {
                if ($item['gender'] == 'Male') {
                    $maleCount += $item['total'];
                } else {
                    $femaleCount += $item['total'];
                }
            }
        } else {
            // For city-wide data
            foreach ($genderData as $item) {
                if ($item['gender'] == 'Male') {
                    $maleCount = $item['total'];
                } else {
                    $femaleCount = $item['total'];
                }
            }
        }
        
        $malePercentage = $totalUsers > 0 ? round(($maleCount / $totalUsers) * 100, 1) : 0;
        $femalePercentage = $totalUsers > 0 ? round(($femaleCount / $totalUsers) * 100, 1) : 0;
        
        // Get most populous age group
        $largestAgeGroup = '';
        $largestCount = 0;
        foreach ($ageData as $item) {
            if ($item['total'] > $largestCount) {
                $largestCount = $item['total'];
                $largestAgeGroup = $item['age_group'];
            }
        }
        
        return [
            'total_users' => $totalUsers,
            'male_count' => $maleCount,
            'female_count' => $femaleCount,
            'male_percentage' => $malePercentage,
            'female_percentage' => $femalePercentage,
            'largest_age_group' => $largestAgeGroup,
            'largest_age_group_count' => $largestCount
        ];
    }

    // ============= EVENT ANALYTICS METHODS ============= //

    /**
     * Get event participation trend (monthly)
     */
    public function getEventParticipationTrend($barangayId = null, $months = 12)
    {
        $whereClause = "WHERE a.event_id IS NOT NULL";
        $joinClause = "LEFT JOIN event e ON a.event_id = e.event_id";
        
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND e.barangay_id = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                DATE_FORMAT(COALESCE(a.`time-in_am`, a.`time-in_pm`, e.start_datetime), '%Y-%m') as month,
                DATE_FORMAT(COALESCE(a.`time-in_am`, a.`time-in_pm`, e.start_datetime), '%M %Y') as month_name,
                COUNT(DISTINCT a.user_id) as total_participants
            FROM attendance a
            {$joinClause}
            {$whereClause}
            AND COALESCE(a.`time-in_am`, a.`time-in_pm`, e.start_datetime) >= DATE_SUB(NOW(), INTERVAL {$months} MONTH)
            GROUP BY month, month_name
            ORDER BY month ASC
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get top engaged barangays
     */
    public function getTopEngagedBarangays($limit = 10)
    {
        $query = $this->db->query("
            SELECT 
                b.name as barangay,
                COUNT(DISTINCT a.user_id) as total_participants,
                COUNT(a.attendance_id) as total_attendances,
                COUNT(DISTINCT a.event_id) as events_participated
            FROM attendance a
            JOIN event e ON a.event_id = e.event_id
            JOIN barangay b ON e.barangay_id = b.barangay_id
            WHERE e.barangay_id > 0
            GROUP BY b.barangay_id, b.name
            ORDER BY total_participants DESC
            LIMIT {$limit}
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get top active members (leaderboard)
     */
    public function getTopActiveMembers($barangayId = null, $limit = 20)
    {
        $whereClause = "WHERE a.event_id IS NOT NULL";
        $joinClause = "LEFT JOIN event e ON a.event_id = e.event_id";
        
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND e.barangay_id = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                CONCAT(u.first_name, ' ', u.last_name) as name,
                b.name as barangay,
                COUNT(DISTINCT a.event_id) as events_attended,
                COUNT(a.attendance_id) as total_attendances
            FROM attendance a
            JOIN user u ON a.user_id = u.user_id
            LEFT JOIN address addr ON u.id = addr.user_id
            LEFT JOIN barangay b ON addr.barangay = b.barangay_id
            {$joinClause}
            {$whereClause}
            AND u.is_active = 1 AND u.status = 2
            GROUP BY u.id, u.first_name, u.last_name, b.name
            ORDER BY events_attended DESC, total_attendances DESC
            LIMIT {$limit}
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get top active SK officials (leaderboard)
     */
    public function getTopActiveSKOfficials($barangayId = null, $limit = 20)
    {
        $whereClause = "WHERE a.event_id IS NOT NULL";
        $joinClause = "LEFT JOIN event e ON a.event_id = e.event_id";
        
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND e.barangay_id = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                CONCAT(u.first_name, ' ', u.last_name) as name,
                b.name as barangay,
                COALESCE(p.name, 
                    CASE 
                        WHEN u.position = 1 THEN 'SK Chairman'
                        WHEN u.position = 2 THEN 'SK Kagawad'
                        WHEN u.position = 3 THEN 'Secretary'
                        WHEN u.position = 4 THEN 'Treasurer'
                        WHEN u.position = 5 THEN 'KK Member'
                        ELSE 'SK Kagawad'
                    END
                ) as position,
                COUNT(DISTINCT a.event_id) as events_attended,
                COUNT(a.attendance_id) as total_attendances
            FROM attendance a
            JOIN user u ON a.user_id = u.user_id
            LEFT JOIN address addr ON u.id = addr.user_id
            LEFT JOIN barangay b ON addr.barangay = b.barangay_id
            LEFT JOIN position p ON u.position = p.position_id
            {$joinClause}
            {$whereClause}
            AND u.is_active = 1 AND u.status = 2 AND u.user_type = 2
            GROUP BY u.id, u.first_name, u.last_name, b.name, position
            ORDER BY events_attended DESC, total_attendances DESC
            LIMIT {$limit}
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get top active KK members (leaderboard)
     */
    public function getTopActiveKKMembers($barangayId = null, $limit = 20)
    {
        $whereClause = "WHERE a.event_id IS NOT NULL";
        $joinClause = "LEFT JOIN event e ON a.event_id = e.event_id";
        
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND e.barangay_id = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                CONCAT(u.first_name, ' ', u.last_name) as name,
                b.name as barangay,
                COUNT(DISTINCT a.event_id) as events_attended,
                COUNT(a.attendance_id) as total_attendances
            FROM attendance a
            JOIN user u ON a.user_id = u.user_id
            LEFT JOIN address addr ON u.id = addr.user_id
            LEFT JOIN barangay b ON addr.barangay = b.barangay_id
            {$joinClause}
            {$whereClause}
            AND u.is_active = 1 AND u.status = 2 AND u.user_type = 1
            GROUP BY u.id, u.first_name, u.last_name, b.name
            ORDER BY events_attended DESC, total_attendances DESC
            LIMIT {$limit}
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get attendance consistency by barangay
     */
    public function getAttendanceConsistency($barangayId = null)
    {
        $whereClause = "WHERE e.status = 'Published'";
        
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND e.barangay_id = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                b.name as barangay,
                COUNT(DISTINCT e.event_id) as total_events,
                COUNT(DISTINCT a.user_id) as total_attendees,
                ROUND((COUNT(DISTINCT a.user_id) / NULLIF(COUNT(DISTINCT e.event_id), 0)) * 100, 2) as consistency_rate
            FROM event e
            JOIN barangay b ON e.barangay_id = b.barangay_id
            LEFT JOIN attendance a ON e.event_id = a.event_id
            {$whereClause}
            AND e.barangay_id > 0
            GROUP BY b.barangay_id, b.name
            ORDER BY consistency_rate DESC
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get most popular event categories
     */
    public function getMostPopularEventCategories($barangayId = null)
    {
        $whereClause = "WHERE e.status = 'Published'";
        
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND e.barangay_id = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                e.category,
                COUNT(DISTINCT e.event_id) as total_events,
                COUNT(DISTINCT a.user_id) as total_participants,
                COUNT(a.attendance_id) as total_attendances
            FROM event e
            LEFT JOIN attendance a ON e.event_id = a.event_id
            {$whereClause}
            GROUP BY e.category
            ORDER BY total_participants DESC
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get event reach (heatmap data)
     */
    public function getEventReach()
    {
        $query = $this->db->query("
            SELECT 
                b.name as barangay,
                e.category,
                COUNT(DISTINCT a.user_id) as participants
            FROM event e
            JOIN barangay b ON e.barangay_id = b.barangay_id
            LEFT JOIN attendance a ON e.event_id = a.event_id
            WHERE e.status = 'Published' AND e.barangay_id > 0
            GROUP BY b.barangay_id, b.name, e.category
            ORDER BY b.name, e.category
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get participation by gender per event
     */
    public function getParticipationByGenderPerEvent($barangayId = null, $limit = 10)
    {
        $whereClause = "WHERE e.status = 'Published'";
        
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND e.barangay_id = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                e.title as event_title,
                e.category,
                CASE WHEN u.sex = 1 THEN 'Male' ELSE 'Female' END as gender,
                COUNT(DISTINCT a.user_id) as participants
            FROM event e
            LEFT JOIN attendance a ON e.event_id = a.event_id
            LEFT JOIN user u ON a.user_id = u.user_id
            {$whereClause}
            AND a.user_id IS NOT NULL AND u.is_active = 1 AND u.status = 2
            GROUP BY e.event_id, e.title, e.category, gender
            ORDER BY e.start_datetime DESC
            LIMIT {$limit}
        ");
        
        return $query->getResultArray();
    }

    // ============= DOCUMENT ANALYTICS METHODS ============= //

    /**
     * Get most accessed document categories
     */
    public function getMostAccessedDocumentCategories($barangayId = null)
    {
        $whereClause = "WHERE d.approval_status = 'approved'";
        $joinClause = "";
        
        if ($barangayId !== null && $barangayId > 0) {
            $joinClause = "JOIN user u ON (d.uploaded_by = u.sk_username OR d.uploaded_by = u.ped_username) JOIN address a ON u.id = a.user_id";
            $whereClause .= " AND a.barangay = " . (int)$barangayId . " AND u.is_active = 1 AND u.status = 2";
        }
        
        $query = $this->db->query("
            SELECT 
                c.name as category,
                COUNT(al.id) as total_downloads,
                COUNT(DISTINCT d.id) as total_documents
            FROM categories c
            JOIN document_category dc ON c.id = dc.category_id
            JOIN documents d ON dc.document_id = d.id
            {$joinClause}
            LEFT JOIN audit_logs al ON d.id = al.document_id AND al.action = 'download'
            {$whereClause}
            GROUP BY c.id, c.name
            ORDER BY total_downloads DESC
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get document approval time
     */
    public function getDocumentApprovalTime($barangayId = null)
    {
        $whereClause = "WHERE d.approval_status = 'approved' AND d.approval_at IS NOT NULL AND d.uploaded_at IS NOT NULL";
        $joinClause = "";
        
        if ($barangayId !== null && $barangayId > 0) {
            $joinClause = "JOIN user u ON (d.uploaded_by = u.sk_username OR d.uploaded_by = u.ped_username) JOIN address a ON u.id = a.user_id";
            $whereClause .= " AND a.barangay = " . (int)$barangayId . " AND u.is_active = 1 AND u.status = 2";
        }
        
        $query = $this->db->query("
            SELECT 
                DATEDIFF(d.approval_at, d.uploaded_at) as approval_days,
                COUNT(*) as document_count
            FROM documents d
            {$joinClause}
            {$whereClause}
            GROUP BY approval_days
            ORDER BY approval_days ASC
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get top downloaded documents
     */
    public function getTopDownloadedDocuments($limit = 20)
    {
        $query = $this->db->query("
            SELECT 
                d.title,
                d.filename,
                c.name as category,
                COUNT(al.id) as download_count,
                d.uploaded_at
            FROM documents d
            LEFT JOIN audit_logs al ON d.id = al.document_id AND al.action = 'download'
            LEFT JOIN document_category dc ON d.id = dc.document_id
            LEFT JOIN categories c ON dc.category_id = c.id
            WHERE d.approval_status = 'approved'
            GROUP BY d.id, d.title, d.filename, c.name, d.uploaded_at
            ORDER BY download_count DESC
            LIMIT {$limit}
        ");
        
        return $query->getResultArray();
    }

    // ============= PERFORMANCE ANALYTICS METHODS ============= //

    /**
     * Get barangay performance score
     */
    public function getBarangayPerformanceScore($barangayId = null, $viewType = 'sk')
    {
        try {
            // Build WHERE clause for specific barangay or all barangays
            $whereClause = $barangayId ? "AND b.barangay_id = " . (int)$barangayId : "";
            
            // For citywide view, show all barangays. For SK view, only show barangays with data
            $havingClause = ($viewType === 'citywide') ? "" : 
                "HAVING (COUNT(DISTINCT e.event_id) > 0 OR total_documents > 0)";
            
            $query = $this->db->query("
                SELECT 
                    b.name as barangay,
                    -- Event participation score (0-100) - percentage of published events that have attendance
                    COALESCE(ROUND(
                        CASE 
                            WHEN COUNT(DISTINCT e.event_id) > 0 THEN
                                (COUNT(DISTINCT CASE WHEN a.attendance_id IS NOT NULL THEN e.event_id END) * 100.0 / COUNT(DISTINCT e.event_id))
                            ELSE 0
                        END, 2
                    ), 0) as event_participation_score,
                    
                    -- Document activity score (0-100) - documents uploaded in past year, capped at 100
                    COALESCE(LEAST(100, ROUND(
                        (SELECT COUNT(DISTINCT d.id) 
                         FROM documents d 
                         JOIN user u ON d.uploaded_by = u.id 
                         JOIN address addr ON u.id = addr.user_id 
                         WHERE addr.barangay = b.barangay_id 
                         AND d.uploaded_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)) * 8.33, 2
                    )), 0) as document_activity_score,
                    
                    -- Attendance consistency score (0-100) - average attendees per event
                    COALESCE(ROUND(
                        CASE 
                            WHEN COUNT(DISTINCT e.event_id) > 0 THEN
                                LEAST(100, (COUNT(a.attendance_id) * 10.0 / COUNT(DISTINCT e.event_id)))
                            ELSE 0
                        END, 2
                    ), 0) as attendance_consistency_score,
                    
                    -- Debug counts
                    COUNT(DISTINCT e.event_id) as total_events,
                    COUNT(a.attendance_id) as total_attendances,
                    (SELECT COUNT(DISTINCT d.id) 
                     FROM documents d 
                     JOIN user u ON d.uploaded_by = u.id 
                     JOIN address addr ON u.id = addr.user_id 
                     WHERE addr.barangay = b.barangay_id 
                     AND d.uploaded_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)) as total_documents
                     
                FROM barangay b
                LEFT JOIN event e ON b.barangay_id = e.barangay_id 
                    AND e.status = 'Published' 
                    AND e.start_datetime >= DATE_SUB(NOW(), INTERVAL 1 YEAR)
                LEFT JOIN attendance a ON e.event_id = a.event_id
                WHERE b.name != 'City-wide' AND b.name IS NOT NULL {$whereClause}
                GROUP BY b.barangay_id, b.name
                {$havingClause}
                ORDER BY b.name
            ");
            
            $result = $query->getResultArray();
            
            // Log the query result for debugging
            log_message('info', "Performance score query returned: " . count($result) . " barangays for view type: {$viewType}");
            foreach ($result as $row) {
                log_message('info', "Barangay: {$row['barangay']}, Events: {$row['total_events']}, Attendances: {$row['total_attendances']}, Documents: {$row['total_documents']}");
            }
            
            return $result;
            
        } catch (\Exception $e) {
            log_message('error', 'Error in getBarangayPerformanceScore query: ' . $e->getMessage());
            log_message('error', 'SQL Error: ' . $this->db->error());
            return [];
        }
    }

    /**
     * Get inactive members
     */
    public function getInactiveMembers($barangayId = null, $inactiveDays = 90)
    {
        $whereClause = "WHERE u.is_active = 1 AND u.status = 2";
        $joinClause = "";
        
        if ($barangayId !== null && $barangayId > 0) {
            $joinClause = "JOIN address addr ON u.id = addr.user_id";
            $whereClause .= " AND addr.barangay = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                CONCAT(u.first_name, ' ', u.last_name) as name,
                b.name as barangay,
                COALESCE(MAX(COALESCE(a.`time-in_am`, a.`time-in_pm`)), 'Never attended') as last_event_date,
                CASE 
                    WHEN MAX(COALESCE(a.`time-in_am`, a.`time-in_pm`)) IS NULL THEN 'Never attended any event'
                    ELSE CONCAT(DATEDIFF(NOW(), MAX(COALESCE(a.`time-in_am`, a.`time-in_pm`))), ' days ago')
                END as last_activity
            FROM user u
            {$joinClause}
            LEFT JOIN attendance a ON u.user_id = a.user_id
            LEFT JOIN barangay b ON addr.barangay = b.barangay_id
            {$whereClause}
            GROUP BY u.id, u.first_name, u.last_name, b.name
            HAVING 
                MAX(COALESCE(a.`time-in_am`, a.`time-in_pm`)) IS NULL OR 
                MAX(COALESCE(a.`time-in_am`, a.`time-in_pm`)) < DATE_SUB(NOW(), INTERVAL {$inactiveDays} DAY)
            ORDER BY last_event_date DESC
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get event summary statistics
     */
    public function getEventSummary($barangayId = null)
    {
        $whereClause = "WHERE 1=1";
        
        if ($barangayId !== null && $barangayId >= 0) {
            $whereClause .= " AND e.barangay_id = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                COUNT(DISTINCT CASE WHEN e.status = 'Published' THEN e.event_id END) as total_published_events,
                COUNT(DISTINCT CASE WHEN e.status = 'Draft' THEN e.event_id END) as total_draft_events,
                COUNT(DISTINCT CASE WHEN e.status = 'Scheduled' THEN e.event_id END) as total_scheduled_events,
                COUNT(DISTINCT a.user_id) as total_unique_participants,
                COUNT(a.attendance_id) as total_attendances,
                ROUND(AVG(
                    CASE WHEN a.`time-out_am` IS NOT NULL 
                    THEN TIMESTAMPDIFF(MINUTE, a.`time-in_am`, a.`time-out_am`) 
                    WHEN a.`time-out_pm` IS NOT NULL 
                    THEN TIMESTAMPDIFF(MINUTE, a.`time-in_pm`, a.`time-out_pm`)
                    END
                ), 2) as avg_attendance_duration
            FROM event e
            LEFT JOIN attendance a ON e.event_id = a.event_id
            {$whereClause}
        ");
        
        return $query->getRowArray();
    }

    /**
     * Get event summary statistics for all barangays (excluding city-wide/superadmin)
     */
    public function getEventSummaryAllBarangays()
    {
        $query = $this->db->query("
            SELECT 
                COUNT(DISTINCT CASE WHEN e.status = 'Published' THEN e.event_id END) as total_published_events,
                COUNT(DISTINCT CASE WHEN e.status = 'Draft' THEN e.event_id END) as total_draft_events,
                COUNT(DISTINCT CASE WHEN e.status = 'Scheduled' THEN e.event_id END) as total_scheduled_events,
                COUNT(DISTINCT a.user_id) as total_unique_participants,
                COUNT(a.attendance_id) as total_attendances,
                ROUND(AVG(
                    CASE WHEN a.`time-out_am` IS NOT NULL 
                    THEN TIMESTAMPDIFF(MINUTE, a.`time-in_am`, a.`time-out_am`) 
                    WHEN a.`time-out_pm` IS NOT NULL 
                    THEN TIMESTAMPDIFF(MINUTE, a.`time-in_pm`, a.`time-out_pm`)
                    END
                ), 2) as avg_attendance_duration
            FROM event e
            LEFT JOIN attendance a ON e.event_id = a.event_id
            WHERE e.barangay_id > 0
        ");
        
        return $query->getRowArray();
    }

    /**
     * Get document summary statistics
     */
    public function getDocumentSummary($barangayId = null)
    {
        $whereClause = "WHERE 1=1";
        $joinClause = "";
        
        if ($barangayId !== null && $barangayId >= 0) {
            $joinClause = "JOIN user u ON (d.uploaded_by = u.sk_username OR d.uploaded_by = u.ped_username) JOIN address a ON u.id = a.user_id";
            $whereClause .= " AND a.barangay = " . (int)$barangayId . " AND u.is_active = 1 AND u.status = 2";
        }
        
        $query = $this->db->query("
            SELECT 
                COUNT(CASE WHEN d.approval_status = 'approved' THEN 1 END) as total_approved_documents,
                COUNT(CASE WHEN d.approval_status = 'pending' THEN 1 END) as total_pending_documents,
                COUNT(CASE WHEN d.approval_status = 'rejected' THEN 1 END) as total_rejected_documents,
                COUNT(DISTINCT al.document_id) as total_downloaded_documents,
                COUNT(al.id) as total_downloads,
                ROUND(AVG(DATEDIFF(d.approval_at, d.uploaded_at)), 2) as avg_approval_time_days
            FROM documents d
            {$joinClause}
            LEFT JOIN audit_logs al ON d.id = al.document_id AND al.action = 'download'
            {$whereClause}
        ");
        
        return $query->getRowArray();
    }

    /**
     * Get document summary statistics for all barangays (excluding city-wide/superadmin)
     */
    public function getDocumentSummaryAllBarangays()
    {
        $query = $this->db->query("
            SELECT 
                COUNT(CASE WHEN d.approval_status = 'approved' THEN 1 END) as total_approved_documents,
                COUNT(CASE WHEN d.approval_status = 'pending' THEN 1 END) as total_pending_documents,
                COUNT(CASE WHEN d.approval_status = 'rejected' THEN 1 END) as total_rejected_documents,
                COUNT(DISTINCT al.document_id) as total_downloaded_documents,
                COUNT(al.id) as total_downloads,
                ROUND(AVG(DATEDIFF(d.approval_at, d.uploaded_at)), 2) as avg_approval_time_days
            FROM documents d
            JOIN user u ON (d.uploaded_by = u.sk_username OR d.uploaded_by = u.ped_username)
            JOIN address a ON u.id = a.user_id
            LEFT JOIN audit_logs al ON d.id = al.document_id AND al.action = 'download'
            WHERE a.barangay > 0 AND u.is_active = 1 AND u.status = 2
        ");
        
        return $query->getRowArray();
    }
}
