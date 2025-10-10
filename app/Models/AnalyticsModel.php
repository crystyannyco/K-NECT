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
        $whereClause = "WHERE u.is_active = 1 AND u.status = 2 AND uei.youth_classification IS NOT NULL";
        
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND a.barangay = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                CASE 
                    WHEN uei.youth_classification = 1 THEN 'In School Youth'
                    WHEN uei.youth_classification = 2 THEN 'Out-of-School Youth'
                    WHEN uei.youth_classification = 3 THEN 'Working Youth'
                    WHEN uei.youth_classification = 4 THEN 'Youth with Specific Needs'
                    WHEN uei.youth_classification = 5 THEN 'Person with Disability'
                    WHEN uei.youth_classification = 6 THEN 'Children in Conflict with the Law'
                    WHEN uei.youth_classification = 7 THEN 'Indigenous People'
                    ELSE 'Not Specified'
                END AS youth_classification,
                COUNT(*) AS total
            FROM user u
            JOIN user_ext_info uei ON u.id = uei.user_id
            {$joinClause}
            {$whereClause}
            GROUP BY youth_classification
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
                    WHEN uei.civil_status = 4 THEN 'Divorced'
                    WHEN uei.civil_status = 5 THEN 'Separated'
                    WHEN uei.civil_status = 6 THEN 'Annulled'
                    WHEN uei.civil_status = 7 THEN 'Live-In'
                    WHEN uei.civil_status = 8 THEN 'Unknown'
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
                    WHEN uei.work_status = 3 THEN 'Currently looking for a Job'
                    WHEN uei.work_status = 4 THEN 'Not Interested in finding Job'
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
                    WHEN uei.educational_background = 1 THEN 'Elementary Level'
                    WHEN uei.educational_background = 2 THEN 'Elementary Graduate'
                    WHEN uei.educational_background = 3 THEN 'High School Level'
                    WHEN uei.educational_background = 4 THEN 'High School Graduate'
                    WHEN uei.educational_background = 5 THEN 'Vocational Level'
                    WHEN uei.educational_background = 6 THEN 'College Level'
                    WHEN uei.educational_background = 7 THEN 'College Graduate'
                    WHEN uei.educational_background = 8 THEN 'Master Level'
                    WHEN uei.educational_background = 9 THEN 'Master Graduate'
                    WHEN uei.educational_background = 10 THEN 'Doctorate Level'
                    WHEN uei.educational_background = 11 THEN 'Doctorate Graduate'
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
                    WHEN 'Elementary Level' THEN 1
                    WHEN 'Elementary Graduate' THEN 2
                    WHEN 'High School Level' THEN 3
                    WHEN 'High School Graduate' THEN 4
                    WHEN 'Vocational Level' THEN 5
                    WHEN 'College Level' THEN 6
                    WHEN 'College Graduate' THEN 7
                    WHEN 'Master Level' THEN 8
                    WHEN 'Master Graduate' THEN 9
                    WHEN 'Doctorate Level' THEN 10
                    WHEN 'Doctorate Graduate' THEN 11
                    ELSE 12
                END
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get SK voter distribution
     */
    public function getSKVoterDistribution($barangayId = null)
    {
        $joinClause = "LEFT JOIN address a ON u.id = a.user_id";
        $whereClause = "WHERE u.is_active = 1 AND u.status = 2 AND uei.sk_voter IS NOT NULL";
        
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND a.barangay = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                CASE 
                    WHEN uei.sk_voter = 1 THEN 'Yes'
                    WHEN uei.sk_voter = 0 THEN 'No'
                    ELSE 'Not Specified'
                END AS sk_voter,
                COUNT(*) AS total
            FROM user u
            JOIN user_ext_info uei ON u.id = uei.user_id
            {$joinClause}
            {$whereClause}
            GROUP BY sk_voter
            ORDER BY total DESC
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get SK election participation distribution
     */
    public function getSKElectionDistribution($barangayId = null)
    {
        $joinClause = "LEFT JOIN address a ON u.id = a.user_id";
        $whereClause = "WHERE u.is_active = 1 AND u.status = 2 AND uei.sk_election IS NOT NULL";
        
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND a.barangay = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                CASE 
                    WHEN uei.sk_election = 1 THEN 'Yes'
                    WHEN uei.sk_election = 0 THEN 'No'
                    ELSE 'Not Specified'
                END AS sk_election,
                COUNT(*) AS total
            FROM user u
            JOIN user_ext_info uei ON u.id = uei.user_id
            {$joinClause}
            {$whereClause}
            GROUP BY sk_election
            ORDER BY total DESC
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get national voter distribution
     */
    public function getNationalVoterDistribution($barangayId = null)
    {
        $joinClause = "LEFT JOIN address a ON u.id = a.user_id";
        $whereClause = "WHERE u.is_active = 1 AND u.status = 2 AND uei.national_voter IS NOT NULL";
        
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND a.barangay = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                CASE 
                    WHEN uei.national_voter = 1 THEN 'Yes'
                    WHEN uei.national_voter = 0 THEN 'No'
                    ELSE 'Not Specified'
                END AS national_voter,
                COUNT(*) AS total
            FROM user u
            JOIN user_ext_info uei ON u.id = uei.user_id
            {$joinClause}
            {$whereClause}
            GROUP BY national_voter
            ORDER BY total DESC
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get KK assembly attendance distribution
     */
    public function getKKAssemblyDistribution($barangayId = null)
    {
        $joinClause = "LEFT JOIN address a ON u.id = a.user_id";
        $whereClause = "WHERE u.is_active = 1 AND u.status = 2 AND uei.kk_assembly IS NOT NULL";
        
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND a.barangay = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                CASE 
                    WHEN uei.kk_assembly = 1 THEN 'Yes'
                    WHEN uei.kk_assembly = 0 THEN 'No'
                    ELSE 'Not Specified'
                END AS kk_assembly,
                COUNT(*) AS total
            FROM user u
            JOIN user_ext_info uei ON u.id = uei.user_id
            {$joinClause}
            {$whereClause}
            GROUP BY kk_assembly
            ORDER BY total DESC
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get KK assembly attendance frequency (how many times)
     */
    public function getKKAssemblyFrequencyDistribution($barangayId = null)
    {
        $joinClause = "LEFT JOIN address a ON u.id = a.user_id";
        $whereClause = "WHERE u.is_active = 1 AND u.status = 2 AND uei.kk_assembly = 1 AND uei.how_many_times IS NOT NULL";
        
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND a.barangay = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                CASE 
                    WHEN uei.how_many_times = 1 THEN '1-2 times'
                    WHEN uei.how_many_times = 2 THEN '3-4 times'
                    WHEN uei.how_many_times = 3 THEN '5 or more times'
                    ELSE 'Not Specified'
                END AS frequency,
                COUNT(*) AS total
            FROM user u
            JOIN user_ext_info uei ON u.id = uei.user_id
            {$joinClause}
            {$whereClause}
            GROUP BY frequency
            ORDER BY 
                CASE frequency
                    WHEN '1-2 times' THEN 1
                    WHEN '3-4 times' THEN 2
                    WHEN '5 or more times' THEN 3
                    ELSE 4
                END
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get voter classification distribution (combined SK & National)
     */
    public function getVoterClassificationDistribution($barangayId = null)
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
     * Get age group distribution
     * Based on the actual age groups in the profiling form
     */
    public function getYouthAgeGroupDistribution($barangayId = null)
    {
        $joinClause = "LEFT JOIN address a ON u.id = a.user_id";
        $whereClause = "WHERE u.is_active = 1 AND u.status = 2 AND uei.age_group IS NOT NULL";
        
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND a.barangay = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                CASE 
                    WHEN uei.age_group = 1 THEN 'Child Youth (15-17 yrs old)'
                    WHEN uei.age_group = 2 THEN 'Core Youth (18-24 yrs old)'
                    WHEN uei.age_group = 3 THEN 'Young Adult (25-30 yrs old)'
                    ELSE 'Not Specified'
                END AS age_group,
                COUNT(*) AS total
            FROM user u
            JOIN user_ext_info uei ON u.id = uei.user_id
            {$joinClause}
            {$whereClause}
            GROUP BY age_group
            ORDER BY 
                CASE age_group
                    WHEN 'Child Youth (15-17 yrs old)' THEN 1
                    WHEN 'Core Youth (18-24 yrs old)' THEN 2
                    WHEN 'Young Adult (25-30 yrs old)' THEN 3
                    ELSE 4
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
        $genderIdentityData = $barangayId ? $this->getGenderIdentityDistributionPerBarangay($barangayId) : $this->getGenderIdentityDistributionCitywide();
        $ageData = $this->getAgeGroupDistribution($barangayId);
        
        // Calculate sex-based percentages (assigned at birth)
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
        
        // Calculate gender identity distribution
        $genderIdentityCounts = [];
        $genderIdentityPercentages = [];
        
        if ($barangayId) {
            // For barangay-specific data, sum up the totals by gender identity
            $genderTotals = [];
            foreach ($genderIdentityData as $item) {
                $identity = $item['gender_identity'];
                if (!isset($genderTotals[$identity])) {
                    $genderTotals[$identity] = 0;
                }
                $genderTotals[$identity] += $item['total'];
            }
            $genderIdentityCounts = $genderTotals;
        } else {
            // For city-wide data
            foreach ($genderIdentityData as $item) {
                $identity = $item['gender_identity'];
                $genderIdentityCounts[$identity] = $item['total'];
            }
        }
        
        // Calculate percentages for gender identities
        foreach ($genderIdentityCounts as $identity => $count) {
            $genderIdentityPercentages[$identity] = $totalUsers > 0 ? round(($count / $totalUsers) * 100, 1) : 0;
        }
        
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
            // Sex-based data (assigned at birth)
            'male_count' => $maleCount,
            'female_count' => $femaleCount,
            'male_percentage' => $malePercentage,
            'female_percentage' => $femalePercentage,
            // Gender identity data
            'gender_identity_counts' => $genderIdentityCounts,
            'gender_identity_percentages' => $genderIdentityPercentages,
            // Age data
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
                        WHEN u.position = 1 THEN 'Chairperson'
                        WHEN u.position = 2 THEN 'Secretary'
                        WHEN u.position = 3 THEN 'Treasurer'
                        WHEN u.position = 4 THEN 'SK Councilor'
                        ELSE 'Member'
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
     * 
     * Consistency Rate Formula:
     * (Total Attendances / Total Possible Attendances) × 100
     * 
     * Where:
     * - Total Attendances = Count of all actual attendance records (status_am or status_pm = 'Present' or 'Late')
     * - Total Possible Attendances = Total Participants × Total Events
     * 
     * Example: If 50 participants attended 10 events, and there were 400 actual attendances:
     * Consistency Rate = (400 / (50 × 10)) × 100 = (400 / 500) × 100 = 80%
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
                COUNT(a.attendance_id) as total_attendances,
                CASE 
                    WHEN COUNT(DISTINCT a.user_id) > 0 AND COUNT(DISTINCT e.event_id) > 0 THEN
                        LEAST(100, ROUND(
                            (COUNT(a.attendance_id) / 
                             (COUNT(DISTINCT a.user_id) * COUNT(DISTINCT e.event_id))
                            ) * 100, 2
                        ))
                    ELSE 0
                END as consistency_rate
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
                    -- Event participation score (0-100) - average participation rate based on target_participants
                    COALESCE(ROUND(
                        CASE 
                            WHEN COUNT(DISTINCT CASE WHEN e.target_participants > 0 THEN e.event_id END) > 0 THEN
                                AVG(CASE 
                                    WHEN e.target_participants > 0 THEN 
                                        LEAST(100, (event_attendee_count.attendee_count * 100.0 / e.target_participants))
                                    ELSE NULL 
                                END)
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
                    
                    -- Attendance consistency score (0-100)
                    -- Formula: (Total Attendances / Total Possible Attendances) × 100
                    -- Total Possible Attendances = Total Unique Participants × Total Events
                    COALESCE(ROUND(
                        CASE 
                            WHEN COUNT(DISTINCT e.event_id) > 0 AND COUNT(DISTINCT a.user_id) > 0 THEN
                                LEAST(100, (COUNT(a.attendance_id) * 100.0 / 
                                           (COUNT(DISTINCT a.user_id) * COUNT(DISTINCT e.event_id))))
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
                LEFT JOIN (
                    SELECT event_id, COUNT(DISTINCT user_id) as attendee_count
                    FROM attendance
                    GROUP BY event_id
                ) event_attendee_count ON e.event_id = event_attendee_count.event_id
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
        
        $summary = $query->getRowArray();
        
        // Calculate average participation rate
        $rateQuery = $this->db->query("
            SELECT 
                ROUND(AVG(participation_rate), 2) as avg_participation_rate
            FROM (
                SELECT 
                    CASE 
                        WHEN e.target_participants IS NOT NULL AND e.target_participants > 0 
                        THEN (COUNT(DISTINCT a.user_id) / e.target_participants) * 100
                        ELSE NULL
                    END as participation_rate
                FROM event e
                LEFT JOIN attendance a ON e.event_id = a.event_id
                {$whereClause}
                GROUP BY e.event_id, e.target_participants
                HAVING participation_rate IS NOT NULL
            ) as rates
        ");
        
        $rateData = $rateQuery->getRowArray();
        $summary['avg_participation_rate'] = $rateData['avg_participation_rate'] ?? 0;
        
        return $summary;
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
        
        $summary = $query->getRowArray();
        
        // Calculate average participation rate
        $rateQuery = $this->db->query("
            SELECT 
                ROUND(AVG(participation_rate), 2) as avg_participation_rate
            FROM (
                SELECT 
                    CASE 
                        WHEN e.target_participants IS NOT NULL AND e.target_participants > 0 
                        THEN (COUNT(DISTINCT a.user_id) / e.target_participants) * 100
                        ELSE NULL
                    END as participation_rate
                FROM event e
                LEFT JOIN attendance a ON e.event_id = a.event_id
                WHERE e.barangay_id > 0
                GROUP BY e.event_id, e.target_participants
                HAVING participation_rate IS NOT NULL
            ) as rates
        ");
        
        $rateData = $rateQuery->getRowArray();
        $summary['avg_participation_rate'] = $rateData['avg_participation_rate'] ?? 0;
        
        return $summary;
    }

    /**
     * Get event participation rate details
     * Returns individual events with their participation rates
     */
    public function getEventParticipationRates($barangayId = null, $limit = null)
    {
        $whereClause = "WHERE e.status = 'Published' AND e.target_participants IS NOT NULL AND e.target_participants > 0";
        
        if ($barangayId !== null && $barangayId >= 0) {
            $whereClause .= " AND e.barangay_id = " . (int)$barangayId;
        }
        
        $limitClause = "";
        if ($limit !== null && $limit > 0) {
            $limitClause = "LIMIT " . (int)$limit;
        }
        
        $query = $this->db->query("
            SELECT 
                e.event_id,
                e.title,
                e.start_datetime,
                e.end_datetime,
                e.target_participants,
                COUNT(DISTINCT a.user_id) as actual_attendees,
                CASE 
                    WHEN e.target_participants IS NOT NULL AND e.target_participants > 0 
                    THEN ROUND((COUNT(DISTINCT a.user_id) / e.target_participants) * 100, 2)
                    ELSE 0
                END as participation_rate_percent
            FROM event e
            LEFT JOIN attendance a ON e.event_id = a.event_id
            {$whereClause}
            GROUP BY e.event_id, e.title, e.start_datetime, e.end_datetime, e.target_participants
            ORDER BY e.start_datetime DESC
            {$limitClause}
        ");
        
        return $query->getResultArray();
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
                COUNT(DISTINCT d.id) as total_uploads,
                COUNT(al.id) as total_downloads
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
                COUNT(DISTINCT d.id) as total_uploads,
                COUNT(al.id) as total_downloads
            FROM documents d
            JOIN user u ON (d.uploaded_by = u.sk_username OR d.uploaded_by = u.ped_username)
            JOIN address a ON u.id = a.user_id
            LEFT JOIN audit_logs al ON d.id = al.document_id AND al.action = 'download'
            WHERE a.barangay > 0 AND u.is_active = 1 AND u.status = 2
        ");
        
        return $query->getRowArray();
    }

    /**
     * Get gender identity distribution city-wide
     */
    public function getGenderIdentityDistributionCitywide()
    {
        $query = $this->db->query("
            SELECT 
                CASE 
                    WHEN gender IS NULL OR gender = '' THEN 'Not Specified'
                    WHEN gender = '1' THEN 'Man'
                    WHEN gender = '2' THEN 'Woman'
                    WHEN gender = '3' THEN 'Non-binary'
                    WHEN gender = '4' THEN 'Prefer not to say'
                    WHEN gender = '5' THEN 'Other'
                    ELSE 'Not Specified'
                END AS gender_identity,
                COUNT(*) AS total
            FROM user
            WHERE is_active = 1 AND status = 2
            GROUP BY 
                CASE 
                    WHEN gender IS NULL OR gender = '' THEN 'Not Specified'
                    WHEN gender = '1' THEN 'Man'
                    WHEN gender = '2' THEN 'Woman'
                    WHEN gender = '3' THEN 'Non-binary'
                    WHEN gender = '4' THEN 'Prefer not to say'
                    WHEN gender = '5' THEN 'Other'
                    ELSE 'Not Specified'
                END
            ORDER BY total DESC
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get gender identity distribution per barangay
     */
    public function getGenderIdentityDistributionPerBarangay($barangayId = null)
    {
        $whereClause = "WHERE u.is_active = 1 AND u.status = 2";
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND a.barangay = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                b.name AS barangay,
                CASE 
                    WHEN u.gender IS NULL OR u.gender = '' THEN 'Not Specified'
                    WHEN u.gender = '1' THEN 'Man'
                    WHEN u.gender = '2' THEN 'Woman'
                    WHEN u.gender = '3' THEN 'Non-binary'
                    WHEN u.gender = '4' THEN 'Prefer not to say'
                    WHEN u.gender = '5' THEN 'Other'
                    ELSE 'Not Specified'
                END AS gender_identity,
                COUNT(*) AS total
            FROM user u
            JOIN address a ON u.id = a.user_id
            JOIN barangay b ON a.barangay = b.barangay_id
            {$whereClause}
            GROUP BY b.name, 
                CASE 
                    WHEN u.gender IS NULL OR u.gender = '' THEN 'Not Specified'
                    WHEN u.gender = '1' THEN 'Man'
                    WHEN u.gender = '2' THEN 'Woman'
                    WHEN u.gender = '3' THEN 'Non-binary'
                    WHEN u.gender = '4' THEN 'Prefer not to say'
                    WHEN u.gender = '5' THEN 'Other'
                    ELSE 'Not Specified'
                END
            ORDER BY b.name, total DESC
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get combined sex and gender analytics for comprehensive demographics
     */
    public function getCombinedGenderAnalytics($barangayId = null)
    {
        $whereClause = "WHERE u.is_active = 1 AND u.status = 2";
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND a.barangay = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                CASE 
                    WHEN u.sex = 1 THEN 'Male' 
                    WHEN u.sex = 0 THEN 'Female'
                    ELSE 'Female' 
                END AS sex_assigned,
                CASE 
                    WHEN u.gender IS NULL OR u.gender = '' THEN 'Not Specified'
                    WHEN u.gender = '1' THEN 'Man'
                    WHEN u.gender = '2' THEN 'Woman'
                    WHEN u.gender = '3' THEN 'Non-binary'
                    WHEN u.gender = '4' THEN 'Prefer not to say'
                    WHEN u.gender = '5' THEN 'Other'
                    ELSE 'Not Specified'
                END AS gender_identity,
                COUNT(*) AS total
            FROM user u
            LEFT JOIN address a ON u.id = a.user_id
            {$whereClause}
            GROUP BY sex_assigned, gender_identity
            ORDER BY sex_assigned, total DESC
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get participation by gender identity per event
     */
    public function getParticipationByGenderIdentityPerEvent($barangayId = null, $limit = 10)
    {
        $whereClause = "WHERE u.is_active = 1 AND u.status = 2";
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND a.barangay = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                e.event_id,
                e.title,
                e.category,
                CASE 
                    WHEN u.gender IS NULL OR u.gender = '' THEN 'Not Specified'
                    WHEN u.gender = '1' THEN 'Man'
                    WHEN u.gender = '2' THEN 'Woman'
                    WHEN u.gender = '3' THEN 'Non-binary'
                    WHEN u.gender = '4' THEN 'Prefer not to say'
                    WHEN u.gender = '5' THEN 'Other'
                    ELSE 'Not Specified'
                END as gender_identity,
                COUNT(DISTINCT att.user_id) as participant_count
            FROM events e
            JOIN attendance att ON e.event_id = att.event_id
            JOIN user u ON att.user_id = u.user_id
            LEFT JOIN address a ON u.id = a.user_id
            {$whereClause}
            GROUP BY e.event_id, e.title, e.category, gender_identity
            ORDER BY e.event_id DESC, participant_count DESC
            LIMIT {$limit}
        ");
        
        return $query->getResultArray();
    }

    /**
     * Get top performing barangays based on events, participants, and posts within a time window.
     * Scoring weights (can be tuned): events*3 + participants*0.5 + posts*2
     * @param int $limit Number of barangays to return
     * @param int $days  Lookback window in days
     * @return array
     */
    public function getTopPerformingBarangays(int $limit = 3, int $days = 30, bool $excludeAggregates = true): array
    {
        try {
            $cutoff = new \DateTime("-{$days} days", new \DateTimeZone('Asia/Manila'));
        } catch (\Throwable $e) {
            $cutoff = new \DateTime("-{$days} days");
        }
        $cutoffStr = $cutoff->format('Y-m-d H:i:s');

        // Using subqueries for portability (avoids CTE requirement)
                $limitClause = $limit > 0 ? "LIMIT {$limit}" : ""; // allow unlimited when $limit <= 0

                $sql = "SELECT 
                b.barangay_id,
                b.name,
                -- Events count
                     (SELECT COUNT(*) FROM event e 
                         WHERE (e.status='Published' OR e.status='published') 
                     AND e.barangay_id = b.barangay_id 
                     AND e.start_datetime >= ?) AS events_count,
                -- Participants (distinct attendees across events)
                                (SELECT COUNT(DISTINCT a.user_id) 
                                     FROM event e 
                   LEFT JOIN attendance a ON a.event_id = e.event_id 
                                    WHERE (e.status='Published' OR e.status='published') 
                    AND e.barangay_id = b.barangay_id 
                    AND e.start_datetime >= ? 
                    AND a.user_id IS NOT NULL) AS participants_count,
                -- Posts published
                                (SELECT COUNT(*) FROM bulletin_posts bp 
                                    WHERE bp.status='published' 
                    AND bp.barangay_id = b.barangay_id 
                    AND bp.published_at >= ?) AS posts_count
            FROM barangay b
            HAVING events_count > 0 OR posts_count > 0 OR participants_count > 0
            ORDER BY (events_count*3 + participants_count*0.5 + posts_count*2) DESC
                        {$limitClause}";

        $query = $this->db->query($sql, [$cutoffStr, $cutoffStr, $cutoffStr]);
        $rows = $query->getResultArray();

        if ($excludeAggregates) {
            $rows = array_values(array_filter($rows, function($r){
                $name = strtolower($r['name'] ?? '');
                // Exclude generic aggregate/grouping rows that might exist in seed data
                return !in_array($name, ['city-wide','citywide','all','overall']);
            }));
        }

        // Compute score & normalize for progress bars
        $maxScore = 0;
        foreach ($rows as &$r) {
            $r['events_count'] = (int)$r['events_count'];
            $r['participants_count'] = (int)$r['participants_count'];
            $r['posts_count'] = (int)$r['posts_count'];
            $r['score'] = $r['events_count']*3 + $r['participants_count']*0.5 + $r['posts_count']*2;
            if ($r['score'] > $maxScore) { $maxScore = $r['score']; }
        }
        unset($r);
        if ($maxScore > 0) {
            foreach ($rows as &$r) {
                $r['score_percent'] = round(($r['score'] / $maxScore) * 100, 2);
            }
        } else {
            foreach ($rows as &$r) { $r['score_percent'] = 0; }
        }
        return $rows;
    }

    /**
     * Get participation rate trend by month
     * @param int|null $barangayId Barangay filter
     * @param int $months Number of months to look back
     * @return array
     */
    public function getParticipationRateTrendByMonth($barangayId = null, $months = 12): array
    {
        $whereClause = "WHERE e.status = 'Published' AND e.target_participants IS NOT NULL AND e.target_participants > 0";
        
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND e.barangay_id = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                DATE_FORMAT(e.start_datetime, '%Y-%m') as month,
                DATE_FORMAT(e.start_datetime, '%b %Y') as month_name,
                e.event_id,
                e.target_participants,
                COUNT(DISTINCT a.user_id) as actual_participants,
                ROUND((COUNT(DISTINCT a.user_id) / NULLIF(e.target_participants, 0)) * 100, 1) as participation_rate
            FROM event e
            LEFT JOIN attendance a ON e.event_id = a.event_id
            {$whereClause}
            AND e.start_datetime >= DATE_SUB(NOW(), INTERVAL {$months} MONTH)
            GROUP BY e.event_id, month, month_name, e.target_participants
            ORDER BY month ASC
        ");
        
        $results = $query->getResultArray();
        
        // Group by month and calculate average across all events in that month
        $monthlyData = [];
        foreach ($results as $row) {
            $month = $row['month'];
            if (!isset($monthlyData[$month])) {
                $monthlyData[$month] = [
                    'month' => $month,
                    'month_name' => $row['month_name'],
                    'rates' => []
                ];
            }
            $monthlyData[$month]['rates'][] = (float)$row['participation_rate'];
        }
        
        // Calculate average rate per month
        $finalResults = [];
        foreach ($monthlyData as $data) {
            $finalResults[] = [
                'month' => $data['month'],
                'month_name' => $data['month_name'],
                'avg_participation_rate' => count($data['rates']) > 0 
                    ? round(array_sum($data['rates']) / count($data['rates']), 1)
                    : 0
            ];
        }
        
        return $finalResults;
    }

    /**
     * Get categories by average participation rate
     * @param int|null $barangayId Barangay filter
     * @return array
     */
    public function getCategoriesByParticipationRate($barangayId = null): array
    {
        $whereClause = "WHERE e.status = 'Published' AND e.target_participants IS NOT NULL AND e.target_participants > 0";
        
        if ($barangayId !== null && $barangayId > 0) {
            $whereClause .= " AND e.barangay_id = " . (int)$barangayId;
        }
        
        $query = $this->db->query("
            SELECT 
                e.event_id,
                e.category,
                e.target_participants,
                COUNT(DISTINCT a.user_id) as actual_participants,
                ROUND((COUNT(DISTINCT a.user_id) / NULLIF(e.target_participants, 0)) * 100, 1) as participation_rate
            FROM event e
            LEFT JOIN attendance a ON e.event_id = a.event_id
            {$whereClause}
            GROUP BY e.event_id, e.category, e.target_participants
            ORDER BY e.category
        ");
        
        $results = $query->getResultArray();
        
        // Group by category and calculate average
        $categoryData = [];
        foreach ($results as $row) {
            $category = $row['category'];
            if (!isset($categoryData[$category])) {
                $categoryData[$category] = [
                    'category' => $category,
                    'rates' => []
                ];
            }
            $categoryData[$category]['rates'][] = (float)$row['participation_rate'];
        }
        
        // Calculate final average per category
        $finalResults = [];
        foreach ($categoryData as $data) {
            $finalResults[] = [
                'category' => $data['category'],
                'avg_participation_rate' => count($data['rates']) > 0 
                    ? round(array_sum($data['rates']) / count($data['rates']), 1)
                    : 0
            ];
        }
        
        // Sort by participation rate descending
        usort($finalResults, function($a, $b) {
            return $b['avg_participation_rate'] <=> $a['avg_participation_rate'];
        });
        
        return $finalResults;
    }

    /**
     * Get top barangays by average participation rate
     * @return array
     */
    public function getTopBarangaysByParticipationRate(): array
    {
        // First, get participation rate for each event
        $query = $this->db->query("
            SELECT 
                e.event_id,
                b.barangay_id,
                b.name as barangay,
                e.target_participants,
                COUNT(DISTINCT a.user_id) as actual_attendees,
                (COUNT(DISTINCT a.user_id) / NULLIF(e.target_participants, 0)) * 100 as participation_rate
            FROM event e
            JOIN barangay b ON e.barangay_id = b.barangay_id
            LEFT JOIN attendance a ON e.event_id = a.event_id
            WHERE e.status = 'Published' 
            AND e.target_participants IS NOT NULL 
            AND e.target_participants > 0
            AND e.barangay_id > 0
            GROUP BY e.event_id, b.barangay_id, b.name, e.target_participants
        ");
        
        $results = $query->getResultArray();
        
        // Group by barangay and calculate average
        $barangayData = [];
        foreach ($results as $row) {
            $barangay = $row['barangay'];
            if (!isset($barangayData[$barangay])) {
                $barangayData[$barangay] = [
                    'barangay' => $barangay,
                    'rates' => [],
                    'event_count' => 0
                ];
            }
            $barangayData[$barangay]['rates'][] = (float)$row['participation_rate'];
            $barangayData[$barangay]['event_count']++;
        }
        
        // Calculate final average per barangay
        $finalResults = [];
        foreach ($barangayData as $data) {
            $finalResults[] = [
                'barangay' => $data['barangay'],
                'event_count' => $data['event_count'],
                'avg_participation_rate' => count($data['rates']) > 0 
                    ? round(array_sum($data['rates']) / count($data['rates']), 1)
                    : 0
            ];
        }
        
        // Sort by participation rate descending
        usort($finalResults, function($a, $b) {
            return $b['avg_participation_rate'] <=> $a['avg_participation_rate'];
        });
        
        return $finalResults;
    }
}
