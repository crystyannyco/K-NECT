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

    /**
     * Get the temporal status of an event (upcoming, ongoing, completed)
     * Based on current time vs event start/end times
     */
    public function getEventTemporalStatus($event)
    {
        // Only check temporal status for published events
        if ($event['status'] !== 'Published') {
            return null; // Return null for non-published events
        }

        $currentDateTime = new \DateTime('now', new \DateTimeZone('Asia/Manila'));
        $startDateTime = new \DateTime($event['start_datetime'], new \DateTimeZone('Asia/Manila'));
        $endDateTime = new \DateTime($event['end_datetime'], new \DateTimeZone('Asia/Manila'));

        if ($currentDateTime < $startDateTime) {
            return 'upcoming';
        } elseif ($currentDateTime >= $startDateTime && $currentDateTime <= $endDateTime) {
            return 'ongoing';
        } else {
            return 'completed';
        }
    }

    /**
     * Check if an event can be edited based on its temporal status
     */
    public function canEventBeEdited($event)
    {
        // Only published events have temporal restrictions
        if ($event['status'] !== 'Published') {
            return true; // Draft and Scheduled events can always be edited
        }

        $temporalStatus = $this->getEventTemporalStatus($event);
        
        // Completed events cannot be edited
        if ($temporalStatus === 'completed') {
            return false;
        }

        // Upcoming and ongoing events can be edited (with restrictions for ongoing)
        return true;
    }

    /**
     * Check if specific fields can be edited for an event
     */
    public function getEditableFields($event)
    {
        // If event can't be edited at all, return empty array
        if (!$this->canEventBeEdited($event)) {
            return [];
        }

        // Default fields that can be edited
        $editableFields = [
            'title',
            'description',
            'start_datetime',
            'end_datetime',
            'location',
            'category',
            'event_banner'
        ];

        // For ongoing published events, remove start_datetime from editable fields
        if ($event['status'] === 'Published') {
            $temporalStatus = $this->getEventTemporalStatus($event);
            if ($temporalStatus === 'ongoing') {
                $editableFields = array_diff($editableFields, ['start_datetime']);
            }
        }

        return $editableFields;
    }
} 