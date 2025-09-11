<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $table = 'attendance';
    protected $primaryKey = 'attendance_id';
    protected $allowedFields = [
        'attendance_id',
        'event_id',
        'rfid_code',
        'user_id',
        'time-in_am',
        'time-out_am',
        'time-in_pm',
        'time-out_pm',
        'status_am',
        'status_pm'
    ];
    protected $useTimestamps = false;

    /**
     * Get attendance records for a specific event
     */
    public function getEventAttendance($eventId)
    {
        return $this->where('event_id', $eventId)->findAll();
    }

    /**
     * Get attendance records with user details for a specific event
     */
    public function getEventAttendanceWithUserDetails($eventId)
    {
        return $this->select('attendance.*, user.first_name, user.middle_name, user.last_name, user.user_id as permanent_user_id, user.birthdate, user.sex, user.user_type, address.zone_purok, address.barangay, user_ext_info.profile_picture')
                    ->join('user', 'user.user_id = attendance.user_id OR user.id = attendance.user_id')
                    ->join('address', 'address.user_id = user.id', 'left')
                    ->join('user_ext_info', 'user_ext_info.user_id = user.id', 'left')
                    ->where('attendance.event_id', $eventId)
                    ->orderBy('attendance.attendance_id', 'DESC')
                    ->findAll();
    }

    /**
     * Get attendance records that are within session timeframe
     */
    public function getActiveSessionAttendance($eventId, $session, $startTime, $endTime)
    {
        $timeField = ($session === 'morning') ? 'time-in_am' : 'time-in_pm';
        $dateToday = date('Y-m-d');
        
        return $this->select('attendance.*, user.first_name, user.middle_name, user.last_name, user.user_id as permanent_user_id, user.birthdate, user.sex, user.user_type, address.zone_purok, address.barangay, user_ext_info.profile_picture')
                    ->join('user', 'user.user_id = attendance.user_id OR user.id = attendance.user_id')
                    ->join('address', 'address.user_id = user.id', 'left')
                    ->join('user_ext_info', 'user_ext_info.user_id = user.id', 'left')
                    ->where('attendance.event_id', $eventId)
                    ->where($timeField . ' IS NOT NULL')
                    ->where("DATE({$timeField})", $dateToday)
                    ->where("TIME({$timeField}) >=", $startTime)
                    ->where("TIME({$timeField}) <=", $endTime)
                    ->orderBy('attendance.attendance_id', 'DESC')
                    ->findAll();
    }

    /**
     * Get attendance record for a specific user and event
     */
    public function getUserEventAttendance($userId, $eventId)
    {
        return $this->where(['user_id' => $userId, 'event_id' => $eventId])->first();
    }

    /**
     * Record time-in for AM session
     */
    public function recordTimeInAM($userId, $eventId, $rfidCode = null, $status = 'Present')
    {
        $data = [
            'user_id' => $userId,
            'event_id' => $eventId,
            'rfid_code' => $rfidCode,
            'time-in_am' => date('Y-m-d H:i:s'),
            'status_am' => $status
        ];

        // Check if record exists
        $existing = $this->getUserEventAttendance($userId, $eventId);
        if ($existing) {
            return $this->update($existing['attendance_id'], [
                'time-in_am' => $data['time-in_am'],
                'status_am' => $data['status_am'],
                'rfid_code' => $rfidCode
            ]);
        }

        return $this->insert($data);
    }

    /**
     * Record time-out for AM session
     */
    public function recordTimeOutAM($userId, $eventId)
    {
        $existing = $this->getUserEventAttendance($userId, $eventId);
        if ($existing) {
            return $this->update($existing['attendance_id'], ['time-out_am' => date('Y-m-d H:i:s')]);
        }
        return false;
    }

    /**
     * Record time-in for PM session
     */
    public function recordTimeInPM($userId, $eventId, $rfidCode = null, $status = 'Present')
    {
        $existing = $this->getUserEventAttendance($userId, $eventId);
        if ($existing) {
            return $this->update($existing['attendance_id'], [
                'time-in_pm' => date('Y-m-d H:i:s'),
                'status_pm' => $status,
                'rfid_code' => $rfidCode
            ]);
        }

        // Create new record if doesn't exist
        $data = [
            'user_id' => $userId,
            'event_id' => $eventId,
            'rfid_code' => $rfidCode,
            'time-in_pm' => date('Y-m-d H:i:s'),
            'status_pm' => $status
        ];
        return $this->insert($data);
    }

    /**
     * Record time-out for PM session
     */
    public function recordTimeOutPM($userId, $eventId)
    {
        $existing = $this->getUserEventAttendance($userId, $eventId);
        if ($existing) {
            return $this->update($existing['attendance_id'], ['time-out_pm' => date('Y-m-d H:i:s')]);
        }
        return false;
    }

    /**
     * Check if user has already checked in for a specific session
     */
    public function hasUserCheckedIn($userId, $eventId, $session)
    {
        $existing = $this->getUserEventAttendance($userId, $eventId);
        if (!$existing) {
            return false;
        }

        $timeField = ($session === 'morning') ? 'time-in_am' : 'time-in_pm';
        return !empty($existing[$timeField]);
    }

    /**
     * Check if user has already checked out for a specific session
     */
    public function hasUserCheckedOut($userId, $eventId, $session)
    {
        $existing = $this->getUserEventAttendance($userId, $eventId);
        if (!$existing) {
            return false;
        }

        $timeOutField = ($session === 'morning') ? 'time-out_am' : 'time-out_pm';
        return !empty($existing[$timeOutField]);
    }

    /**
     * Get the last check-in time for a user in a specific session
     */
    public function getLastCheckInTime($userId, $eventId, $session)
    {
        $existing = $this->getUserEventAttendance($userId, $eventId);
        if (!$existing) {
            return null;
        }

        $timeField = ($session === 'morning') ? 'time-in_am' : 'time-in_pm';
        return $existing[$timeField] ?? null;
    }

    /**
     * Auto-timeout all users who haven't checked out for a session
     */
    public function autoTimeoutUsersForSession($eventId, $session)
    {
        $timeInField = ($session === 'morning') ? 'time-in_am' : 'time-in_pm';
        $timeOutField = ($session === 'morning') ? 'time-out_am' : 'time-out_pm';
        
        $records = $this->where('event_id', $eventId)
                        ->where($timeInField . ' IS NOT NULL')
                        ->where($timeOutField . ' IS NULL')
                        ->findAll();
        
        $timeoutCount = 0;
        $currentDateTime = date('Y-m-d H:i:s');
        
        foreach ($records as $record) {
            $this->update($record['attendance_id'], [$timeOutField => $currentDateTime]);
            $timeoutCount++;
        }
        
        return $timeoutCount;
    }

    /**
     * Record attendance for a specific session (AM or PM)
     * This is a general method that handles both AM and PM sessions
     */
    public function recordAttendance($userId, $eventId, $session, $rfidCode = null, $status = 'Present')
    {
        if ($session === 'AM') {
            return $this->recordTimeInAM($userId, $eventId, $rfidCode, $status);
        } elseif ($session === 'PM') {
            return $this->recordTimeInPM($userId, $eventId, $rfidCode, $status);
        }
        
        return false;
    }

    /**
     * Auto-mark timeouts for all sessions in an event
     */
    public function autoMarkTimeouts($eventId)
    {
        $morningTimeouts = $this->autoTimeoutUsersForSession($eventId, 'morning');
        $afternoonTimeouts = $this->autoTimeoutUsersForSession($eventId, 'afternoon');
        
        return $morningTimeouts + $afternoonTimeouts;
    }

    /**
     * Get all attendance records for a specific user with event details
     */
    public function getUserAttendanceHistory($userId)
    {
        return $this->select('attendance.*, event.title, event.description, event.start_datetime, event.end_datetime, event.location, event.event_banner')
                    ->join('event', 'event.event_id = attendance.event_id')
                    ->where('attendance.user_id', $userId)
                    ->orderBy('event.start_datetime', 'DESC')
                    ->findAll();
    }
}
