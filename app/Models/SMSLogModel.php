<?php

namespace App\Models;

use CodeIgniter\Model;

class SMSLogModel extends Model
{
    protected $table      = 'sms_logs';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields = [
        'phone_number', 'message', 'status', 'response', 
        'event_id', 'sent_by', 'sent_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    protected $validationRules    = [
        'phone_number' => 'required|min_length[10]|max_length[20]',
        'message'      => 'required',
        'status'       => 'required|in_list[sent,failed,delivered]',
    ];
    
    protected $validationMessages = [
        'phone_number' => [
            'required'   => 'Phone number is required',
            'min_length' => 'Phone number must be at least 10 characters',
            'max_length' => 'Phone number cannot exceed 20 characters'
        ],
        'message' => [
            'required' => 'Message content is required'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list'  => 'Status must be one of: sent, failed, delivered'
        ]
    ];
    
    /**
     * Log SMS sent for an event
     */
    public function logEventSMS($eventId, $phoneNumber, $message, $status, $response = null, $sentBy = null)
    {
        return $this->insert([
            'event_id'     => $eventId,
            'phone_number' => $phoneNumber,
            'message'      => $message,
            'status'       => $status,
            'response'     => $response,
            'sent_by'      => $sentBy ?: session('user_id'),
            'sent_at'      => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Get SMS logs for a specific event
     */
    public function getEventSMSLogs($eventId)
    {
        return $this->where('event_id', $eventId)
                   ->orderBy('sent_at', 'DESC')
                   ->findAll();
    }
    
    /**
     * Get SMS statistics for an event
     */
    public function getEventSMSStats($eventId)
    {
        $builder = $this->builder();
        $builder->select('status, COUNT(*) as count')
                ->where('event_id', $eventId)
                ->groupBy('status');
        
        $results = $builder->get()->getResultArray();
        
        $stats = [
            'sent' => 0,
            'failed' => 0,
            'delivered' => 0,
            'total' => 0
        ];
        
        foreach ($results as $result) {
            $stats[$result['status']] = (int)$result['count'];
            $stats['total'] += (int)$result['count'];
        }
        
        return $stats;
    }
    
    /**
     * Get recent SMS activity
     */
    public function getRecentActivity($limit = 50)
    {
        return $this->select('sms_logs.*, event.title as event_title')
                   ->join('event', 'event.event_id = sms_logs.event_id', 'left')
                   ->orderBy('sms_logs.sent_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
}