<?php

namespace App\Models;

use CodeIgniter\Model;

class UserAnalyticsModel extends Model
{
    protected $db;
    
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    /**
     * Get user's personal summary statistics
     */
    public function getUserSummary($userId)
    {
        $query = $this->db->query("
            SELECT 
                u.user_id,
                u.username,
                u.first_name,
                u.last_name,
                u.birthdate,
                TIMESTAMPDIFF(YEAR, u.birthdate, CURDATE()) as age,
                CASE WHEN u.sex = 1 THEN 'Male' ELSE 'Female' END as gender,
                u.created_at as join_date,
                DATEDIFF(CURDATE(), u.created_at) as days_as_member,
                b.name as barangay_name,
                uei.civil_status,
                uei.educational_background,
                uei.work_status,
                uei.sk_voter,
                uei.national_voter
            FROM user u
            LEFT JOIN address a ON u.id = a.user_id
            LEFT JOIN barangay b ON a.barangay = b.barangay_id
            LEFT JOIN user_ext_info uei ON u.id = uei.user_id
            WHERE u.user_id = ?
        ", [$userId]);
        
        return $query->getRowArray();
    }

    /**
     * Get user's event attendance statistics
     */
    public function getUserEventStats($userId)
    {
        $query = $this->db->query("
            SELECT 
                COUNT(DISTINCT att.event_id) as events_attended,
                COUNT(DISTINCT e.event_id) as total_events_available,
                ROUND((COUNT(DISTINCT att.event_id) / NULLIF(COUNT(DISTINCT e.event_id), 0)) * 100, 1) as attendance_percentage,
                SUM(CASE WHEN att.status_am = 'Present' OR att.status_pm = 'Present' THEN 1 ELSE 0 END) as times_present,
                SUM(CASE WHEN att.status_am = 'Late' OR att.status_pm = 'Late' THEN 1 ELSE 0 END) as times_late,
                SUM(CASE WHEN att.status_am = 'Absent' OR att.status_pm = 'Absent' THEN 1 ELSE 0 END) as times_absent
            FROM user u
            LEFT JOIN address a ON u.id = a.user_id
            LEFT JOIN event e ON (e.barangay_id = a.barangay OR e.barangay_id = 0) 
                AND e.status = 'Published' 
                AND e.start_datetime <= NOW()
            LEFT JOIN attendance att ON e.event_id = att.event_id AND att.user_id = u.user_id
            WHERE u.user_id = ?
        ", [$userId]);
        
        return $query->getRowArray();
    }

    /**
     * Get user's recent event attendance history
     */
    public function getUserRecentAttendance($userId, $limit = 10)
    {
        $query = $this->db->query("
            SELECT 
                e.title,
                e.start_datetime,
                e.location,
                e.category,
                COALESCE(att.status_am, att.status_pm, 'Not Recorded') as attendance_status,
                att.`time-in_am`,
                att.`time-out_am`,
                att.`time-in_pm`,
                att.`time-out_pm`
            FROM event e
            LEFT JOIN attendance att ON e.event_id = att.event_id AND att.user_id = ?
            LEFT JOIN user u ON u.user_id = ?
            LEFT JOIN address a ON u.id = a.user_id
            WHERE (e.barangay_id = a.barangay OR e.barangay_id = 0) 
                AND e.status = 'Published' 
                AND e.start_datetime <= NOW()
            ORDER BY e.start_datetime DESC
            LIMIT ?
        ", [$userId, $userId, $limit]);
        
        return $query->getResultArray();
    }

    /**
     * Get user's favorite event categories based on attendance
     */
    public function getUserFavoriteEventCategories($userId)
    {
        $query = $this->db->query("
            SELECT 
                e.category,
                COUNT(*) as attendance_count,
                ROUND((COUNT(*) / (SELECT COUNT(*) FROM attendance att2 
                    JOIN event e2 ON att2.event_id = e2.event_id 
                    WHERE att2.user_id = ? AND e2.category IS NOT NULL)) * 100, 1) as percentage
            FROM attendance att
            JOIN event e ON att.event_id = e.event_id
            WHERE att.user_id = ? 
                AND e.category IS NOT NULL 
                AND e.category != ''
                AND (att.status_am = 'Present' OR att.status_pm = 'Present')
            GROUP BY e.category
            ORDER BY attendance_count DESC
            LIMIT 5
        ", [$userId, $userId]);
        
        return $query->getResultArray();
    }

    /**
     * Get user's monthly attendance trend (last 12 months)
     */
    public function getUserAttendanceTrend($userId, $months = 12)
    {
        $query = $this->db->query("
            SELECT 
                DATE_FORMAT(e.start_datetime, '%Y-%m') as month,
                DATE_FORMAT(e.start_datetime, '%M %Y') as month_name,
                COUNT(DISTINCT e.event_id) as events_available,
                COUNT(DISTINCT att.event_id) as events_attended,
                ROUND((COUNT(DISTINCT att.event_id) / NULLIF(COUNT(DISTINCT e.event_id), 0)) * 100, 1) as attendance_rate
            FROM event e
            LEFT JOIN attendance att ON e.event_id = att.event_id AND att.user_id = ?
            LEFT JOIN user u ON u.user_id = ?
            LEFT JOIN address a ON u.id = a.user_id
            WHERE (e.barangay_id = a.barangay OR e.barangay_id = 0) 
                AND e.status = 'Published'
                AND e.start_datetime >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
                AND e.start_datetime <= NOW()
            GROUP BY DATE_FORMAT(e.start_datetime, '%Y-%m')
            ORDER BY month ASC
        ", [$userId, $userId, $months]);
        
        return $query->getResultArray();
    }

    /**
     * Get user's profile completeness score
     */
    public function getUserProfileCompleteness($userId)
    {
        $query = $this->db->query("
            SELECT 
                u.user_id,
                CASE WHEN u.first_name IS NOT NULL AND u.first_name != '' THEN 10 ELSE 0 END +
                CASE WHEN u.last_name IS NOT NULL AND u.last_name != '' THEN 10 ELSE 0 END +
                CASE WHEN u.birthdate IS NOT NULL THEN 10 ELSE 0 END +
                CASE WHEN u.sex IS NOT NULL THEN 10 ELSE 0 END +
                CASE WHEN u.phone_number IS NOT NULL AND u.phone_number != '' THEN 10 ELSE 0 END +
                CASE WHEN u.email IS NOT NULL AND u.email != '' THEN 10 ELSE 0 END +
                CASE WHEN a.barangay IS NOT NULL THEN 10 ELSE 0 END +
                CASE WHEN uei.civil_status IS NOT NULL THEN 10 ELSE 0 END +
                CASE WHEN uei.educational_background IS NOT NULL THEN 10 ELSE 0 END +
                CASE WHEN uei.work_status IS NOT NULL THEN 10 ELSE 0 END
                as completeness_score,
                
                -- Individual field checks for recommendations
                CASE WHEN u.first_name IS NULL OR u.first_name = '' THEN 'first_name' ELSE NULL END as missing_firstname,
                CASE WHEN u.last_name IS NULL OR u.last_name = '' THEN 'last_name' ELSE NULL END as missing_lastname,
                CASE WHEN u.birthdate IS NULL THEN 'birthdate' ELSE NULL END as missing_birthdate,
                CASE WHEN u.sex IS NULL THEN 'sex' ELSE NULL END as missing_sex,
                CASE WHEN u.phone_number IS NULL OR u.phone_number = '' THEN 'phone_number' ELSE NULL END as missing_contact,
                CASE WHEN u.email IS NULL OR u.email = '' THEN 'email' ELSE NULL END as missing_email,
                CASE WHEN a.barangay IS NULL THEN 'barangay' ELSE NULL END as missing_barangay,
                CASE WHEN uei.civil_status IS NULL THEN 'civil_status' ELSE NULL END as missing_civil_status,
                CASE WHEN uei.educational_background IS NULL THEN 'educational_background' ELSE NULL END as missing_education,
                CASE WHEN uei.work_status IS NULL THEN 'work_status' ELSE NULL END as missing_work_status
                
            FROM user u
            LEFT JOIN address a ON u.id = a.user_id
            LEFT JOIN user_ext_info uei ON u.id = uei.user_id
            WHERE u.user_id = ?
        ", [$userId]);
        
        return $query->getRowArray();
    }

    /**
     * Get user's recent activity feed
     */
    public function getUserRecentActivity($userId, $limit = 15)
    {
        $query = $this->db->query("
            SELECT 
                'attendance' as activity_type,
                CONCAT('Attended event: ', e.title) as activity_description,
                GREATEST(
                    COALESCE(att.`time-in_am`, '1970-01-01 00:00:00'),
                    COALESCE(att.`time-out_am`, '1970-01-01 00:00:00'),
                    COALESCE(att.`time-in_pm`, '1970-01-01 00:00:00'),
                    COALESCE(att.`time-out_pm`, '1970-01-01 00:00:00')
                ) as activity_date,
                e.title as related_title,
                att.status_am as status_detail
            FROM attendance att
            JOIN event e ON att.event_id = e.event_id
            WHERE att.user_id = ?
            
            UNION ALL
            
            SELECT 
                'profile_update' as activity_type,
                'Profile information updated' as activity_description,
                u.updated_at as activity_date,
                'Profile Update' as related_title,
                NULL as status_detail
            FROM user u
            WHERE u.user_id = ?
                AND u.updated_at IS NOT NULL
                
            ORDER BY activity_date DESC
            LIMIT ?
        ", [$userId, $userId, $limit]);
        
        return $query->getResultArray();
    }

    /**
     * Get comparison with barangay average (anonymized)
     */
    public function getUserBarangayComparison($userId)
    {
        // First, get user's barangay
        $userBarangay = $this->db->query("
            SELECT a.barangay 
            FROM user u 
            JOIN address a ON u.id = a.user_id 
            WHERE u.user_id = ?
        ", [$userId])->getRowArray();
        
        if (!$userBarangay) {
            return [
                'user_events_attended' => 0,
                'user_attendance_percentage' => 0,
                'avg_events_attended' => 0,
                'avg_attendance_percentage' => 0,
                'total_members' => 0,
                'performance_indicator' => 'No Data'
            ];
        }
        
        // Get user's stats
        $userStats = $this->db->query("
            SELECT 
                COUNT(DISTINCT att.event_id) as events_attended,
                ROUND((COUNT(DISTINCT att.event_id) / NULLIF(COUNT(DISTINCT e.event_id), 0)) * 100, 1) as attendance_percentage
            FROM user u
            LEFT JOIN address a ON u.id = a.user_id
            LEFT JOIN event e ON (e.barangay_id = ? OR e.barangay_id = 0) 
                AND e.status = 'Published' 
                AND e.start_datetime <= NOW()
            LEFT JOIN attendance att ON e.event_id = att.event_id AND att.user_id = u.user_id
            WHERE u.user_id = ?
        ", [$userBarangay['barangay'], $userId])->getRowArray();
        
        // Get barangay averages
        $barangayStats = $this->db->query("
            SELECT 
                ROUND(AVG(member_stats.events_attended), 1) as avg_events_attended,
                ROUND(AVG(member_stats.attendance_percentage), 1) as avg_attendance_percentage,
                COUNT(*) as total_members
            FROM (
                SELECT 
                    u.user_id,
                    COUNT(DISTINCT att.event_id) as events_attended,
                    ROUND((COUNT(DISTINCT att.event_id) / NULLIF(COUNT(DISTINCT e.event_id), 0)) * 100, 1) as attendance_percentage
                FROM user u
                JOIN address a ON u.id = a.user_id
                LEFT JOIN event e ON (e.barangay_id = ? OR e.barangay_id = 0) 
                    AND e.status = 'Published' 
                    AND e.start_datetime <= NOW()
                LEFT JOIN attendance att ON e.event_id = att.event_id AND att.user_id = u.user_id
                WHERE a.barangay = ?
                    AND u.is_active = 1 
                    AND u.status = 2
                GROUP BY u.user_id
            ) as member_stats
        ", [$userBarangay['barangay'], $userBarangay['barangay']])->getRowArray();
        
        // Calculate performance indicator
        $performanceIndicator = 'No Data';
        if ($userStats['attendance_percentage'] !== null && $barangayStats['avg_attendance_percentage'] !== null) {
            if ($userStats['attendance_percentage'] > $barangayStats['avg_attendance_percentage']) {
                $performanceIndicator = 'Above Average';
            } elseif ($userStats['attendance_percentage'] == $barangayStats['avg_attendance_percentage']) {
                $performanceIndicator = 'Average';
            } else {
                $performanceIndicator = 'Below Average';
            }
        }
        
        return [
            'user_events_attended' => $userStats['events_attended'] ?? 0,
            'user_attendance_percentage' => $userStats['attendance_percentage'] ?? 0,
            'avg_events_attended' => $barangayStats['avg_events_attended'] ?? 0,
            'avg_attendance_percentage' => $barangayStats['avg_attendance_percentage'] ?? 0,
            'total_members' => $barangayStats['total_members'] ?? 0,
            'performance_indicator' => $performanceIndicator
        ];
    }

    /**
     * Get user's achievements/badges based on participation
     */
    public function getUserAchievements($userId)
    {
        $query = $this->db->query("
            SELECT 
                user_stats.*,
                
                -- Achievement calculations
                CASE WHEN user_stats.events_attended >= 1 THEN 1 ELSE 0 END as first_event_badge,
                CASE WHEN user_stats.events_attended >= 5 THEN 1 ELSE 0 END as regular_participant_badge,
                CASE WHEN user_stats.events_attended >= 10 THEN 1 ELSE 0 END as active_member_badge,
                CASE WHEN user_stats.events_attended >= 20 THEN 1 ELSE 0 END as super_active_badge,
                CASE WHEN user_stats.attendance_percentage >= 80 THEN 1 ELSE 0 END as consistent_attendee_badge,
                CASE WHEN user_stats.attendance_percentage = 100 AND user_stats.events_attended >= 3 THEN 1 ELSE 0 END as perfect_attendance_badge,
                CASE WHEN user_stats.days_as_member >= 365 THEN 1 ELSE 0 END as one_year_member_badge,
                CASE WHEN user_stats.completeness_score = 100 THEN 1 ELSE 0 END as complete_profile_badge
                
            FROM (
                SELECT 
                    COUNT(DISTINCT att.event_id) as events_attended,
                    ROUND((COUNT(DISTINCT att.event_id) / NULLIF(COUNT(DISTINCT e.event_id), 0)) * 100, 1) as attendance_percentage,
                    DATEDIFF(CURDATE(), u.created_at) as days_as_member,
                    -- Profile completeness calculation
                    CASE WHEN u.first_name IS NOT NULL AND u.first_name != '' THEN 10 ELSE 0 END +
                    CASE WHEN u.last_name IS NOT NULL AND u.last_name != '' THEN 10 ELSE 0 END +
                    CASE WHEN u.birthdate IS NOT NULL THEN 10 ELSE 0 END +
                    CASE WHEN u.sex IS NOT NULL THEN 10 ELSE 0 END +
                    CASE WHEN u.phone_number IS NOT NULL AND u.phone_number != '' THEN 10 ELSE 0 END +
                    CASE WHEN u.email IS NOT NULL AND u.email != '' THEN 10 ELSE 0 END +
                    CASE WHEN a.barangay IS NOT NULL THEN 10 ELSE 0 END +
                    CASE WHEN uei.civil_status IS NOT NULL THEN 10 ELSE 0 END +
                    CASE WHEN uei.educational_background IS NOT NULL THEN 10 ELSE 0 END +
                    CASE WHEN uei.work_status IS NOT NULL THEN 10 ELSE 0 END
                    as completeness_score
                FROM user u
                LEFT JOIN address a ON u.id = a.user_id
                LEFT JOIN user_ext_info uei ON u.id = uei.user_id
                LEFT JOIN event e ON (e.barangay_id = a.barangay OR e.barangay_id = 0) 
                    AND e.status = 'Published' 
                    AND e.start_datetime <= NOW()
                LEFT JOIN attendance att ON e.event_id = att.event_id AND att.user_id = u.user_id
                WHERE u.user_id = ?
            ) as user_stats
        ", [$userId]);
        
        return $query->getRowArray();
    }
}
