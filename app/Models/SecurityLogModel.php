<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Security Log Model
 * 
 * Tracks security-related events including:
 * - Login attempts (successful/failed)
 * - CSRF violations
 * - Unauthorized access attempts
 * - Permission denials
 * - Suspicious activities
 * 
 * @package K-NECT
 * @version 1.0.0
 * @date October 13, 2025
 */
class SecurityLogModel extends Model
{
    protected $table            = 'security_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'event_type',
        'severity',
        'ip_address',
        'user_agent',
        'request_uri',
        'request_method',
        'details',
        'created_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Event type constants
     */
    const EVENT_LOGIN_SUCCESS = 'login_success';
    const EVENT_LOGIN_FAILED = 'login_failed';
    const EVENT_LOGOUT = 'logout';
    const EVENT_CSRF_VIOLATION = 'csrf_violation';
    const EVENT_UNAUTHORIZED_ACCESS = 'unauthorized_access';
    const EVENT_PERMISSION_DENIED = 'permission_denied';
    const EVENT_SUSPICIOUS_ACTIVITY = 'suspicious_activity';
    const EVENT_PASSWORD_CHANGE = 'password_change';
    const EVENT_ACCOUNT_LOCKED = 'account_locked';
    const EVENT_XSS_ATTEMPT = 'xss_attempt';
    const EVENT_SQL_INJECTION_ATTEMPT = 'sql_injection_attempt';
    const EVENT_FILE_UPLOAD = 'file_upload';
    const EVENT_FILE_DOWNLOAD = 'file_download';
    const EVENT_DATA_EXPORT = 'data_export';

    /**
     * Severity level constants
     */
    const SEVERITY_INFO = 'info';
    const SEVERITY_WARNING = 'warning';
    const SEVERITY_ERROR = 'error';
    const SEVERITY_CRITICAL = 'critical';

    /**
     * Log a security event
     * 
     * @param string $eventType Event type constant
     * @param string $severity Severity level constant
     * @param int|null $userId User ID if authenticated
     * @param array $details Additional event details
     * @return int|bool Insert ID or false on failure
     */
    public function logEvent(string $eventType, string $severity, ?int $userId = null, array $details = [])
    {
        $request = service('request');
        
        $data = [
            'user_id'        => $userId,
            'event_type'     => $eventType,
            'severity'       => $severity,
            'ip_address'     => $request->getIPAddress(),
            'user_agent'     => $request->getUserAgent()->getAgentString(),
            'request_uri'    => $request->getUri()->getPath(),
            'request_method' => $request->getMethod(),
            'details'        => json_encode($details),
        ];

        return $this->insert($data);
    }

    /**
     * Log a login attempt
     * 
     * @param bool $success Whether login was successful
     * @param int|null $userId User ID (null if failed)
     * @param string $username Attempted username
     * @return int|bool
     */
    public function logLogin(bool $success, ?int $userId, string $username): int|bool
    {
        $eventType = $success ? self::EVENT_LOGIN_SUCCESS : self::EVENT_LOGIN_FAILED;
        $severity = $success ? self::SEVERITY_INFO : self::SEVERITY_WARNING;
        
        $details = [
            'username' => $username,
            'success' => $success,
        ];

        return $this->logEvent($eventType, $severity, $userId, $details);
    }

    /**
     * Log a CSRF violation
     * 
     * @param int|null $userId User ID if authenticated
     * @return int|bool
     */
    public function logCSRFViolation(?int $userId = null): int|bool
    {
        return $this->logEvent(
            self::EVENT_CSRF_VIOLATION,
            self::SEVERITY_ERROR,
            $userId,
            ['message' => 'CSRF token validation failed']
        );
    }

    /**
     * Log unauthorized access attempt
     * 
     * @param int|null $userId User ID if authenticated
     * @param string $resource Resource attempting to access
     * @return int|bool
     */
    public function logUnauthorizedAccess(?int $userId, string $resource): int|bool
    {
        return $this->logEvent(
            self::EVENT_UNAUTHORIZED_ACCESS,
            self::SEVERITY_WARNING,
            $userId,
            ['resource' => $resource]
        );
    }

    /**
     * Log permission denied
     * 
     * @param int $userId User ID
     * @param string $action Action attempted
     * @param string $resource Resource name
     * @return int|bool
     */
    public function logPermissionDenied(int $userId, string $action, string $resource): int|bool
    {
        return $this->logEvent(
            self::EVENT_PERMISSION_DENIED,
            self::SEVERITY_WARNING,
            $userId,
            [
                'action' => $action,
                'resource' => $resource
            ]
        );
    }

    /**
     * Get recent security events
     * 
     * @param int $limit Number of records to retrieve
     * @param string|null $severity Filter by severity
     * @param string|null $eventType Filter by event type
     * @return array
     */
    public function getRecentEvents(int $limit = 100, ?string $severity = null, ?string $eventType = null): array
    {
        $builder = $this->builder();
        
        if ($severity) {
            $builder->where('severity', $severity);
        }
        
        if ($eventType) {
            $builder->where('event_type', $eventType);
        }
        
        return $builder
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Get events by user
     * 
     * @param int $userId User ID
     * @param int $limit Number of records
     * @return array
     */
    public function getEventsByUser(int $userId, int $limit = 50): array
    {
        return $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get failed login attempts for an IP address
     * 
     * @param string $ipAddress IP address
     * @param int $minutes Time window in minutes
     * @return int Count of failed attempts
     */
    public function getFailedLoginCount(string $ipAddress, int $minutes = 30): int
    {
        $timeAgo = date('Y-m-d H:i:s', strtotime("-{$minutes} minutes"));
        
        return $this->where('event_type', self::EVENT_LOGIN_FAILED)
            ->where('ip_address', $ipAddress)
            ->where('created_at >=', $timeAgo)
            ->countAllResults();
    }

    /**
     * Get security statistics
     * 
     * @param string $startDate Start date (Y-m-d)
     * @param string $endDate End date (Y-m-d)
     * @return array
     */
    public function getStatistics(string $startDate, string $endDate): array
    {
        $db = $this->db;
        
        // Total events by type
        $eventsByType = $db->table($this->table)
            ->select('event_type, COUNT(*) as count')
            ->where('created_at >=', $startDate)
            ->where('created_at <=', $endDate . ' 23:59:59')
            ->groupBy('event_type')
            ->get()
            ->getResultArray();
        
        // Total events by severity
        $eventsBySeverity = $db->table($this->table)
            ->select('severity, COUNT(*) as count')
            ->where('created_at >=', $startDate)
            ->where('created_at <=', $endDate . ' 23:59:59')
            ->groupBy('severity')
            ->get()
            ->getResultArray();
        
        // Top IP addresses
        $topIPs = $db->table($this->table)
            ->select('ip_address, COUNT(*) as count')
            ->where('created_at >=', $startDate)
            ->where('created_at <=', $endDate . ' 23:59:59')
            ->where('severity !=', self::SEVERITY_INFO)
            ->groupBy('ip_address')
            ->orderBy('count', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();
        
        return [
            'events_by_type' => $eventsByType,
            'events_by_severity' => $eventsBySeverity,
            'top_suspicious_ips' => $topIPs,
        ];
    }

    /**
     * Clean old security logs
     * 
     * @param int $days Keep logs for this many days
     * @return int Number of deleted records
     */
    public function cleanOldLogs(int $days = 90): int
    {
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        
        return $this->where('created_at <', $cutoffDate)
            ->where('severity', self::SEVERITY_INFO) // Only delete info logs
            ->delete();
    }
}
