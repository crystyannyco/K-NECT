<?php

namespace App\Models;

use CodeIgniter\Model;

class EventAttendanceModel extends Model
{
    protected $table = 'event_attendance';
    protected $primaryKey = 'event_attendance_id';
    protected $allowedFields = [
        'event_attendance_id',
        'event_id',
        'start_attendance_am',
        'end_attendance_am',
        'start_attendance_pm',
        'end_attendance_pm',
        'created_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';

    /**
     * Get attendance settings for a specific event
     */
    public function getEventAttendanceSettings($eventId)
    {
        return $this->where('event_id', $eventId)->first();
    }

    /**
     * Save or update attendance settings for an event
     */
    public function saveAttendanceSettings($eventId, $settings)
    {
        $existing = $this->getEventAttendanceSettings($eventId);
        
        $data = [
            'event_id' => $eventId,
            'start_attendance_am' => $settings['start_attendance_am'] ?? null,
            'end_attendance_am' => $settings['end_attendance_am'] ?? null,
            'start_attendance_pm' => $settings['start_attendance_pm'] ?? null,
            'end_attendance_pm' => $settings['end_attendance_pm'] ?? null
        ];

        if ($existing) {
            return $this->update($existing['event_attendance_id'], $data);
        } else {
            return $this->insert($data);
        }
    }
}
