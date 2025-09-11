<?php
namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table = 'event';
    protected $primaryKey = 'event_id';
    protected $allowedFields = [
        'event_id',
        'title',
        'description',
        'start_datetime',
        'end_datetime',
        'location',
        'created_by',
        'barangay_id',
        'google_event_id',
        'created_at',
        'updated_at',
        'event_banner',
        'category',
        'status',
        'publish_date',
        'scheduling_enabled',
        'scheduled_publish_datetime',
        'sms_notification_enabled',
        'sms_recipient_scope',
        'sms_recipient_barangays',
        'sms_recipient_roles'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
} 