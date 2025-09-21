<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserExtInfoModel;
use App\Models\AddressModel;
use App\Models\SystemLogoModel;
use App\Models\BarangayModel;
use App\Models\EventModel;
use App\Models\AttendanceModel;
use App\Models\EventAttendanceModel;
use App\Libraries\UserHelper;
use App\Libraries\BarangayHelper;
use App\Libraries\ZoneHelper;

class AttendanceController extends BaseController
{
// ================== ATTENDANCE SYSTEM (START) ==================
    // All methods below are related to the SK attendance system (UI, data, processing, reports)
    public function attendance()
    {
        $session = session();
        $skBarangay = $session->get('sk_barangay');
        $barangayName = BarangayHelper::getBarangayName($skBarangay);
        
        $eventModel = new EventModel();
        
        // Get only published events for the SK's specific barangay
        $events = [];
        if ($skBarangay) {
            $events = $eventModel
                ->select('*')
                ->where('barangay_id', $skBarangay)
                ->where('status', 'published') // Only published events
                ->orderBy('start_datetime', 'DESC')
                ->findAll();
        }
        
        // Get unique categories from events
        $categories = array_unique(array_filter(array_column($events, 'category')));
        sort($categories);
        
        $data = [
            'user_id' => $session->get('user_id'),
            'username' => $session->get('username'),
            'sk_barangay' => $skBarangay,
            'barangay_name' => $barangayName,
            'events' => $events,
            'categories' => $categories
        ];

        return 
            $this->loadView('K-NECT/SK/template/header') .
            $this->loadView('K-NECT/SK/template/sidebar') .
            $this->loadView('K-NECT/SK/attendance', $data);
    }

    // ================== PEDERASYON ATTENDANCE SYSTEM ==================
    // Pederasyon attendance for city-wide events (barangay_id = 0) and all accepted users
    public function pederasyonAttendance()
    {
        $session = session();
        
        $eventModel = new EventModel();
        
        // Get only published city-wide events (barangay_id = 0) for Pederasyon
        $events = $eventModel
            ->select('*')
            ->where('barangay_id', 0) // City-wide events only
            ->where('status', 'published') // Only published events
            ->orderBy('start_datetime', 'DESC')
            ->findAll();
        
        // Get unique categories from events
        $categories = array_unique(array_filter(array_column($events, 'category')));
        sort($categories);
        
        $data = [
            'user_id' => $session->get('user_id'),
            'username' => $session->get('username'),
            'events' => $events,
            'categories' => $categories,
            'user_type' => 'pederasyon'
        ];

        return 
            $this->loadView('K-NECT/Pederasyon/template/header') .
            $this->loadView('K-NECT/Pederasyon/template/sidebar') .
            $this->loadView('K-NECT/Pederasyon/attendance', $data);
    }

    public function getEventAttendanceSettings()
    {
        $eventId = $this->request->getPost('event_id');
        $eventAttendanceModel = new EventAttendanceModel();
        
        $settings = $eventAttendanceModel->getEventAttendanceSettings($eventId);
        
        return $this->response->setJSON([
            'success' => true,
            'settings' => $settings
        ]);
    }

    public function saveEventAttendanceSettings()
    {
        $eventId = $this->request->getPost('event_id');
        $settings = [
            'start_attendance_am' => $this->request->getPost('start_attendance_am') ?: null,
            'end_attendance_am' => $this->request->getPost('end_attendance_am') ?: null,
            'start_attendance_pm' => $this->request->getPost('start_attendance_pm') ?: null,
            'end_attendance_pm' => $this->request->getPost('end_attendance_pm') ?: null
        ];
        
        $eventAttendanceModel = new EventAttendanceModel();
        $result = $eventAttendanceModel->saveAttendanceSettings($eventId, $settings);
        
        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Attendance settings saved successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to save attendance settings'
            ]);
        }
    }

    public function startAttendance($eventId)
    {
        $session = session();
        $skBarangay = $session->get('sk_barangay');
        $barangayName = BarangayHelper::getBarangayName($skBarangay);
        
        $eventModel = new EventModel();
        $eventAttendanceModel = new EventAttendanceModel();
        $attendanceModel = new AttendanceModel();
        
        $event = $eventModel->find($eventId);
        if (!$event) {
            return redirect()->to('sk/attendance')->with('error', 'Event not found');
        }
        
        $attendanceSettings = $eventAttendanceModel->getEventAttendanceSettings($eventId);
        $attendanceRecords = $attendanceModel->getEventAttendance($eventId);
        
        $data = [
            'user_id' => $session->get('user_id'),
            'username' => $session->get('username'),
            'sk_barangay' => $skBarangay,
            'barangay_name' => $barangayName,
            'event' => $event,
            'attendance_settings' => $attendanceSettings,
            'attendance_records' => $attendanceRecords
        ];

        return 
            $this->loadView('K-NECT/SK/template/header') .
            $this->loadView('K-NECT/SK/template/sidebar') .
            $this->loadView('K-NECT/SK/attendance_display', $data);
    }

    // Pederasyon-specific attendance start for city-wide events
    public function pederasyonStartAttendance($eventId)
    {
        $session = session();
        $cityName = "Quezon City"; // Default city name for Pederasyon
        
        $eventModel = new EventModel();
        $eventAttendanceModel = new EventAttendanceModel();
        $attendanceModel = new AttendanceModel();
        
        $event = $eventModel->find($eventId);
        if (!$event || $event['barangay_id'] != 0) {
            return redirect()->to('pederasyon/attendance')->with('error', 'City-wide event not found');
        }
        
        $attendanceSettings = $eventAttendanceModel->getEventAttendanceSettings($eventId);
        $attendanceRecords = $attendanceModel->getEventAttendance($eventId);
        
        $data = [
            'user_id' => $session->get('user_id'),
            'username' => $session->get('username'),
            'city_name' => $cityName,
            'event' => $event,
            'attendance_settings' => $attendanceSettings,
            'attendance_records' => $attendanceRecords
        ];

        return 
            $this->loadView('K-NECT/Pederasyon/template/header') .
            $this->loadView('K-NECT/Pederasyon/template/sidebar') .
            $this->loadView('K-NECT/Pederasyon/attendance_display', $data);
    }

    public function attendanceDisplay($eventId)
    {
        $session = session();
        $eventModel = new EventModel();
        $eventAttendanceModel = new EventAttendanceModel();
        $attendanceModel = new AttendanceModel();
        $systemLogoModel = new SystemLogoModel();
        $barangayModel = new BarangayModel();
        
        $event = $eventModel->find($eventId);
        if (!$event) {
            return redirect()->to('sk/attendance')->with('error', 'Event not found');
        }
        
        $attendanceSettings = $eventAttendanceModel->getEventAttendanceSettings($eventId);
        
        // Get attendance records with full user details
        $attendanceRecords = $attendanceModel->getEventAttendanceWithUserDetails($eventId);
        
        // Process attendance records to include formatted user data
        $processedRecords = [];
        foreach ($attendanceRecords as $record) {
            // Calculate age
            $age = '';
            if ($record['birthdate']) {
                $birthDate = new \DateTime($record['birthdate']);
                $today = new \DateTime('today');
                $age = $birthDate->diff($today)->y;
            }
            
            // Get barangay name
            $barangayName = '';
            if ($record['barangay']) {
                $barangayName = BarangayHelper::getBarangayName($record['barangay']);
            }
            
            // Format full name
            $fullName = trim($record['first_name'] . ' ' . ($record['middle_name'] ? $record['middle_name'] . ' ' : '') . $record['last_name']);
            
            $processedRecords[] = [
                'attendance_id' => $record['attendance_id'],
                'user_id' => $record['user_id'],
                'user_name' => $fullName,
                'first_name' => $record['first_name'],
                'middle_name' => $record['middle_name'],
                'last_name' => $record['last_name'],
                'permanent_user_id' => $record['permanent_user_id'],
                'rfid_code' => $record['rfid_code'],
                'time-in_am' => $record['time-in_am'],
                'time-out_am' => $record['time-out_am'],
                'time-in_pm' => $record['time-in_pm'],
                'time-out_pm' => $record['time-out_pm'],
                'status_am' => $record['status_am'],
                'status_pm' => $record['status_pm'],
                'age' => $age,
                'sex' => $record['sex'] == 1 ? 'Male' : 'Female',
                'user_type' => $record['user_type'],
                'barangay' => $barangayName,
                'zone_purok' => $record['zone_purok'] ?? '',
                'profile_picture' => $record['profile_picture']
            ];
        }
        
        // Get SK barangay info
        $skBarangay = $session->get('sk_barangay');
        $barangayName = BarangayHelper::getBarangayName($skBarangay);
        
        // Get barangay and SK logos
        $barangayLogo = $systemLogoModel->getActiveLogoByType('barangay');
        $skLogo = $systemLogoModel->getActiveLogoByType('sk');
        
        $data = [
            'event' => $event,
            'attendance_settings' => $attendanceSettings,
            'attendance_records' => $processedRecords,
            'sk_barangay' => $skBarangay,
            'barangay_name' => $barangayName,
            'barangay_logo' => $barangayLogo,
            'sk_logo' => $skLogo
        ];

        return view('K-NECT/SK/attendance_display', $data);
    }

    // Pederasyon-specific attendance display for city-wide events
    public function pederasyonAttendanceDisplay($eventId)
    {
        $session = session();
        $eventModel = new EventModel();
        $eventAttendanceModel = new EventAttendanceModel();
        $attendanceModel = new AttendanceModel();
        $systemLogoModel = new SystemLogoModel();
        
        $event = $eventModel->find($eventId);
        if (!$event || $event['barangay_id'] != 0) {
            return redirect()->to('pederasyon/attendance')->with('error', 'City-wide event not found');
        }
        
        $attendanceSettings = $eventAttendanceModel->getEventAttendanceSettings($eventId);
        
        // Get attendance records with full user details for all city users
        $attendanceRecords = $attendanceModel->getEventAttendanceWithUserDetails($eventId);
        
        // Process attendance records to include formatted user data
        $processedRecords = [];
        foreach ($attendanceRecords as $record) {
            // Calculate age
            $age = '';
            if ($record['birthdate']) {
                $birthDate = new \DateTime($record['birthdate']);
                $today = new \DateTime('today');
                $age = $birthDate->diff($today)->y;
            }
            
            // Get barangay name for display
            $barangayName = '';
            if ($record['barangay']) {
                $barangayName = BarangayHelper::getBarangayName($record['barangay']);
            }
            
            // Format full name
            $fullName = trim($record['first_name'] . ' ' . ($record['middle_name'] ? $record['middle_name'] . ' ' : '') . $record['last_name']);
            
            $processedRecords[] = [
                'attendance_id' => $record['attendance_id'],
                'user_id' => $record['user_id'],
                'user_name' => $fullName,
                'first_name' => $record['first_name'],
                'middle_name' => $record['middle_name'],
                'last_name' => $record['last_name'],
                'permanent_user_id' => $record['permanent_user_id'],
                'rfid_code' => $record['rfid_code'],
                'time-in_am' => $record['time-in_am'],
                'time-out_am' => $record['time-out_am'],
                'time-in_pm' => $record['time-in_pm'],
                'time-out_pm' => $record['time-out_pm'],
                'status_am' => $record['status_am'],
                'status_pm' => $record['status_pm'],
                'age' => $age,
                'sex' => $record['sex'] == 1 ? 'Male' : 'Female',
                'user_type' => $record['user_type'],
                'barangay' => $barangayName,
                'zone_purok' => $record['zone_purok'] ?? '',
                'profile_picture' => $record['profile_picture']
            ];
        }
        
        // Get city and Pederasyon logos
        $cityLogo = $systemLogoModel->getActiveLogoByType('city');
        $irigaLogo = $systemLogoModel->getActiveLogoByType('iriga_city') ?: $cityLogo; // Use iriga_city as logo type
        $pederasyonLogo = $systemLogoModel->getActiveLogoByType('pederasyon');
        
        $data = [
            'event' => $event,
            'attendance_settings' => $attendanceSettings,
            'attendance_records' => $processedRecords,
            'city_name' => 'Iriga City',
            'city_logo' => $cityLogo,
            'iriga_logo' => $irigaLogo,
            'pederasyon_logo' => $pederasyonLogo
        ];

        return view('K-NECT/Pederasyon/attendance_display', $data);
    }

    public function getAttendanceData()
    {
        $eventId = $this->request->getPost('event_id');
        $session = $this->request->getPost('session'); // Optional filter by session
        $attendanceModel = new AttendanceModel();
        $eventAttendanceModel = new EventAttendanceModel();
        
        // Ensure consistent timezone
        date_default_timezone_set('Asia/Manila');
        
        // Get attendance settings to determine active session
        $attendanceSettings = $eventAttendanceModel->getEventAttendanceSettings($eventId);
        $currentTime = date('H:i'); // Use exact HH:MM format
        
        // Determine which session is currently active using precise time comparison
        $activeSession = null;
        if ($attendanceSettings) {
            if ($attendanceSettings['start_attendance_am'] && $attendanceSettings['end_attendance_am']) {
                $currentMinutes = $this->timeStringToMinutes($currentTime);
                $startMinutesAM = $this->timeStringToMinutes($attendanceSettings['start_attendance_am']);
                $endMinutesAM = $this->timeStringToMinutes($attendanceSettings['end_attendance_am']);
                
                if ($currentMinutes >= $startMinutesAM && $currentMinutes < $endMinutesAM) {
                    $activeSession = 'morning';
                }
            }
            if ($attendanceSettings['start_attendance_pm'] && $attendanceSettings['end_attendance_pm'] && !$activeSession) {
                $currentMinutes = $this->timeStringToMinutes($currentTime);
                $startMinutesPM = $this->timeStringToMinutes($attendanceSettings['start_attendance_pm']);
                $endMinutesPM = $this->timeStringToMinutes($attendanceSettings['end_attendance_pm']);
                
                if ($currentMinutes >= $startMinutesPM && $currentMinutes < $endMinutesPM) {
                    $activeSession = 'afternoon';
                }
            }
        }
        
        // Get attendance records with user details
        $attendanceRecords = $attendanceModel->getEventAttendanceWithUserDetails($eventId);
        
        // Process attendance data and include user details
        $attendanceData = [];
        foreach ($attendanceRecords as $record) {
            // Calculate age
            $age = '';
            if ($record['birthdate']) {
                $birthDate = new \DateTime($record['birthdate']);
                $today = new \DateTime('today');
                $age = $birthDate->diff($today)->y;
            }
            
            // Get barangay name
            $barangayName = '';
            if ($record['barangay']) {
                $barangayName = BarangayHelper::getBarangayName($record['barangay']);
            }
            
            // Format full name
            $fullName = trim($record['first_name'] . ' ' . ($record['middle_name'] ? $record['middle_name'] . ' ' : '') . $record['last_name']);
            
            $userData = [
                'user_id' => $record['user_id'],
                'name' => $fullName,
                'first_name' => $record['first_name'],
                'last_name' => $record['last_name'],
                'middle_name' => $record['middle_name'] ?? '',
                'permanent_user_id' => $record['permanent_user_id'],
                'rfid_code' => $record['rfid_code'],
                'age' => $age,
                'sex' => $record['sex'] == 1 ? 'Male' : 'Female',
                'user_type' => $record['user_type'],
                'barangay' => $barangayName,
                'zone_purok' => $record['zone_purok'] ?? '',
                'profile_picture' => $record['profile_picture'],
                'attendance_id' => $record['attendance_id']
            ];
            
            // Add session-specific data - CREATE SEPARATE ENTRIES FOR TIME-IN AND TIME-OUT
            if ($record['time-in_am']) {
                // Create time-in entry for morning session
                $sessionData = $userData;
                $sessionData['session'] = 'morning';
                $sessionData['time'] = $record['time-in_am'];
                $sessionData['time_in'] = $record['time-in_am'];
                $sessionData['time_out'] = null;
                $sessionData['status'] = $record['status_am'] ?? 'Present';
                $sessionData['action'] = 'check_in';
                
                // Only include if within session timeframe when filtering by active session
                $includeRecord = true;
                if ($activeSession === 'morning' && $attendanceSettings) {
                    $recordTime = date('H:i', strtotime($record['time-in_am']));
                    $startTime = $attendanceSettings['start_attendance_am'];
                    $endTime = $attendanceSettings['end_attendance_am'];
                    
                    // Use minute-based comparison for accuracy
                    $recordMinutes = $this->timeStringToMinutes($recordTime);
                    $startMinutes = $this->timeStringToMinutes($startTime);
                    $endMinutes = $this->timeStringToMinutes($endTime);
                    $includeRecord = ($recordMinutes >= $startMinutes && $recordMinutes < $endMinutes);
                }
                
                if ($includeRecord && (!$session || $session === 'morning')) {
                    $attendanceData[] = $sessionData;
                }
                
                // Create time-out entry for morning session if it exists
                if ($record['time-out_am']) {
                    $timeOutData = $userData;
                    $timeOutData['session'] = 'morning';
                    $timeOutData['time'] = $record['time-out_am'];
                    $timeOutData['time_in'] = $record['time-in_am'];
                    $timeOutData['time_out'] = $record['time-out_am'];
                    $timeOutData['status'] = $record['status_am'] ?? 'Present';
                    $timeOutData['action'] = 'check_out';
                    
                    // Check if time-out is within session timeframe
                    $includeTimeOut = true;
                    if ($activeSession === 'morning' && $attendanceSettings) {
                        $recordTime = date('H:i', strtotime($record['time-out_am']));
                        $startTime = $attendanceSettings['start_attendance_am'];
                        $endTime = $attendanceSettings['end_attendance_am'];
                        
                        $recordMinutes = $this->timeStringToMinutes($recordTime);
                        $startMinutes = $this->timeStringToMinutes($startTime);
                        $endMinutes = $this->timeStringToMinutes($endTime);
                        $includeTimeOut = ($recordMinutes >= $startMinutes && $recordMinutes <= $endMinutes);
                    }
                    
                    if ($includeTimeOut && (!$session || $session === 'morning')) {
                        $attendanceData[] = $timeOutData;
                    }
                }
            }
            
            if ($record['time-in_pm']) {
                // Create time-in entry for afternoon session
                $sessionData = $userData;
                $sessionData['session'] = 'afternoon';
                $sessionData['time'] = $record['time-in_pm'];
                $sessionData['time_in'] = $record['time-in_pm'];
                $sessionData['time_out'] = null;
                $sessionData['status'] = $record['status_pm'] ?? 'Present';
                $sessionData['action'] = 'check_in';
                
                // Only include if within session timeframe when filtering by active session
                $includeRecord = true;
                if ($activeSession === 'afternoon' && $attendanceSettings) {
                    $recordTime = date('H:i', strtotime($record['time-in_pm']));
                    $startTime = $attendanceSettings['start_attendance_pm'];
                    $endTime = $attendanceSettings['end_attendance_pm'];
                    
                    // Use minute-based comparison for accuracy
                    $recordMinutes = $this->timeStringToMinutes($recordTime);
                    $startMinutes = $this->timeStringToMinutes($startTime);
                    $endMinutes = $this->timeStringToMinutes($endTime);
                    $includeRecord = ($recordMinutes >= $startMinutes && $recordMinutes < $endMinutes);
                }
                
                if ($includeRecord && (!$session || $session === 'afternoon')) {
                    $attendanceData[] = $sessionData;
                }
                
                // Create time-out entry for afternoon session if it exists
                if ($record['time-out_pm']) {
                    $timeOutData = $userData;
                    $timeOutData['session'] = 'afternoon';
                    $timeOutData['time'] = $record['time-out_pm'];
                    $timeOutData['time_in'] = $record['time-in_pm'];
                    $timeOutData['time_out'] = $record['time-out_pm'];
                    $timeOutData['status'] = $record['status_pm'] ?? 'Present';
                    $timeOutData['action'] = 'check_out';
                    
                    // Check if time-out is within session timeframe
                    $includeTimeOut = true;
                    if ($activeSession === 'afternoon' && $attendanceSettings) {
                        $recordTime = date('H:i', strtotime($record['time-out_pm']));
                        $startTime = $attendanceSettings['start_attendance_pm'];
                        $endTime = $attendanceSettings['end_attendance_pm'];
                        
                        $recordMinutes = $this->timeStringToMinutes($recordTime);
                        $startMinutes = $this->timeStringToMinutes($startTime);
                        $endMinutes = $this->timeStringToMinutes($endTime);
                        $includeTimeOut = ($recordMinutes >= $startMinutes && $recordMinutes <= $endMinutes);
                    }
                    
                    if ($includeTimeOut && (!$session || $session === 'afternoon')) {
                        $attendanceData[] = $timeOutData;
                    }
                }
            }
        }
        
        // Sort by most recent first
        usort($attendanceData, function($a, $b) {
            return strcmp($b['time'], $a['time']);
        });
        
        // Calculate counts
        $morningCount = count(array_filter($attendanceData, function($record) {
            return $record['session'] === 'morning';
        }));
        
        $afternoonCount = count(array_filter($attendanceData, function($record) {
            return $record['session'] === 'afternoon';
        }));
        
        $totalCount = count($attendanceData);
        
        $currentlyPresent = count(array_filter($attendanceData, function($record) {
            return empty($record['time_out']);
        }));
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $attendanceData,
            'counts' => [
                'morning' => $morningCount,
                'afternoon' => $afternoonCount,
                'total' => $totalCount,
                'currently_present' => $currentlyPresent
            ],
            'active_session' => $activeSession,
            'current_time' => $currentTime,
            'attendance_settings' => $attendanceSettings // Include for frontend sync
        ]);
    }

    public function processAttendance()
    {
        // Check if it's an AJAX request or has JSON content
        $isAjax = $this->request->isAJAX() || 
                  $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest' ||
                  $this->request->getMethod() === 'POST';
                  
        if (!$isAjax) {
            log_message('error', 'processAttendance: Not an AJAX request');
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $eventId = $this->request->getPost('event_id');
        $rfidCode = $this->request->getPost('rfid_code');
        $userId = $this->request->getPost('user_id');
        $session = $this->request->getPost('session'); // 'morning' or 'afternoon'
        
        if (!$eventId || !$session) {
            log_message('error', 'processAttendance: Missing required fields - Event ID: ' . $eventId . ', Session: ' . $session);
            return $this->response->setJSON(['success' => false, 'message' => 'Missing required fields']);
        }

        if (!$rfidCode && !$userId) {
            log_message('error', 'processAttendance: Either RFID code or User ID is required');
            return $this->response->setJSON(['success' => false, 'message' => 'Either RFID code or User ID is required']);
        }

        try {
            $userModel = new UserModel();
            $attendanceModel = new AttendanceModel();
            $eventAttendanceModel = new EventAttendanceModel();
            
            // Get attendance settings to check if session is active
            $attendanceSettings = $eventAttendanceModel->getEventAttendanceSettings($eventId);
            
            // Define current time and datetime for calculations with proper timezone
            date_default_timezone_set('Asia/Manila'); // Ensure consistent timezone
            $currentTime = date('H:i'); // Use exact HH:MM format without seconds
            $currentDateTime = date('Y-m-d H:i:s');
            
            // Determine session validity and times with enhanced logic
            $sessionActive = false;
            $startTime = null;
            $endTime = null;
            $sessionStatus = 'inactive';
            
            if ($attendanceSettings) {
                if ($session === 'morning') {
                    if ($attendanceSettings['start_attendance_am'] && $attendanceSettings['end_attendance_am']) {
                        $startTime = $attendanceSettings['start_attendance_am'];
                        $endTime = $attendanceSettings['end_attendance_am'];
                        
                        // Use minute-based comparison for exact timing
                        $currentMinutes = $this->timeStringToMinutes($currentTime);
                        $startMinutes = $this->timeStringToMinutes($startTime);
                        $endMinutes = $this->timeStringToMinutes($endTime);
                        
                        if ($currentMinutes < $startMinutes) {
                            $sessionStatus = 'pending'; // Before start time
                        } else if ($currentMinutes >= $startMinutes && $currentMinutes < $endMinutes) {
                            $sessionActive = true;
                            $sessionStatus = 'active';
                        } else {
                            $sessionStatus = 'ended'; // After end time
                        }
                    }
                } else if ($session === 'afternoon') {
                    if ($attendanceSettings['start_attendance_pm'] && $attendanceSettings['end_attendance_pm']) {
                        $startTime = $attendanceSettings['start_attendance_pm'];
                        $endTime = $attendanceSettings['end_attendance_pm'];
                        
                        // Use minute-based comparison for exact timing
                        $currentMinutes = $this->timeStringToMinutes($currentTime);
                        $startMinutes = $this->timeStringToMinutes($startTime);
                        $endMinutes = $this->timeStringToMinutes($endTime);
                        
                        if ($currentMinutes < $startMinutes) {
                            $sessionStatus = 'pending';
                        } else if ($currentMinutes >= $startMinutes && $currentMinutes < $endMinutes) {
                            $sessionActive = true;
                            $sessionStatus = 'active';
                        } else {
                            $sessionStatus = 'ended';
                        }
                    }
                }
            }
            
            // Allow session if no session times are set
            if (!$sessionActive && !$startTime) {
                $sessionActive = true;
                $sessionStatus = 'active';
                $startTime = ($session === 'morning') ? '08:00' : '13:00';
                $endTime = ($session === 'morning') ? '12:00' : '17:00';
            }
            
            if ($sessionStatus === 'pending') {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => "Session starts at {$startTime}. Please wait until then.",
                    'session_status' => 'pending',
                    'start_time' => $startTime,
                    'current_time' => $currentTime
                ]);
            } else if ($sessionStatus === 'ended') {
                
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => "Session ended at {$endTime}. Attendance is no longer allowed.",
                    'session_status' => 'ended',
                    'end_time' => $endTime,
                    'current_time' => $currentTime
                ]);
            } else if (!$sessionActive) {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Session is not configured or active',
                    'session_status' => 'inactive'
                ]);
            }
            
            // Find and validate user
            $user = null;
            
            if ($rfidCode && $userId) {
                // Both RFID and User ID provided - validate they match
                $user = $userModel->where(['rfid_code' => $rfidCode, 'user_id' => $userId])->first();
                if (!$user) {
                    // Try with 'id' field as well
                    $user = $userModel->where(['rfid_code' => $rfidCode, 'id' => $userId])->first();
                }
                if (!$user) {
                    log_message('error', "User not found with RFID: {$rfidCode} and User ID: {$userId}");
                    return $this->response->setJSON([
                        'success' => false, 
                        'message' => 'RFID code and User ID do not match any existing record'
                    ]);
                }
            } else if ($rfidCode) {
                // Only RFID provided
                $user = $userModel->where('rfid_code', $rfidCode)->first();
                if (!$user) {
                    log_message('error', "RFID code not found: {$rfidCode}");
                    return $this->response->setJSON(['success' => false, 'message' => 'RFID code not found']);
                }
            } else if ($userId) {
                // Only User ID provided - try both user_id and id fields
                $user = $userModel->where('user_id', $userId)->first();
                if (!$user) {
                    $user = $userModel->find($userId); // This uses the primary key 'id'
                }
                if (!$user) {
                    log_message('error', "User ID not found: {$userId}");
                    return $this->response->setJSON(['success' => false, 'message' => 'User ID not found']);
                }
            }
            
            // Validate user status - only accepted users can attend
            $userStatus = isset($user['status']) ? (int)$user['status'] : 1;
            if ($userStatus !== 2) {
                $statusText = $userStatus === 1 ? 'pending' : ($userStatus === 3 ? 'rejected' : 'invalid');
                log_message('error', "User attendance denied - Status: {$statusText} for User ID: {$userIdForAttendance}");
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "Attendance denied. User status is {$statusText}. Only accepted users can attend events."
                ]);
            }
            
            // For city-wide events (Pederasyon), ensure event has barangay_id = 0
            $eventModel = new EventModel();
            $event = $eventModel->find($eventId);
            if (!$event) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Event not found'
                ]);
            }
            
            // Additional validation based on event type
            if ($event['barangay_id'] == 0) {
                // City-wide event - any accepted user can attend
            } else {
                // Barangay-specific event - could add barangay validation here if needed
            }
            
            // Determine which user ID field to use
            $userIdForAttendance = $user['user_id'] ?? $user['id'];
            
            // Check existing attendance for duplicate entry handling
            $existingAttendance = $attendanceModel->where([
                'user_id' => $userIdForAttendance,
                'event_id' => $eventId
            ])->first();
            
            // DUPLICATE ENTRY HANDLING
            $timeField = ($session === 'morning') ? 'time-in_am' : 'time-in_pm';
            $timeOutField = ($session === 'morning') ? 'time-out_am' : 'time-out_pm';
            
            if ($existingAttendance && !empty($existingAttendance[$timeField])) {
                $lastScanTime = new \DateTime($existingAttendance[$timeField]);
                $currentTimeObj = new \DateTime($currentDateTime);
                $timeDifference = $currentTimeObj->diff($lastScanTime);
                $minutesDifference = ($timeDifference->h * 60) + $timeDifference->i;
                
                // Check if user is already checked out
                if (!empty($existingAttendance[$timeOutField])) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Duplicate entry - already scanned and checked out for this session',
                        'duplicate' => true
                    ]);
                }
                
                // TIMEOUT RULES - Allow manual check-out if 30+ minutes have passed
                if ($minutesDifference >= 30) {
                    // Check if it's before session end time
                    $sessionEndTime = new \DateTime(date('Y-m-d') . ' ' . $endTime);
                    if ($currentTimeObj >= $sessionEndTime) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Session has ended - cannot check out manually',
                            'duplicate' => true
                        ]);
                    }
                    
                    // Process check-out
                    if ($session === 'morning') {
                        $attendanceModel->recordTimeOutAM($userIdForAttendance, $eventId);
                    } else {
                        $attendanceModel->recordTimeOutPM($userIdForAttendance, $eventId);
                    }
                    
                    // Get additional user information for response
                    $userExtInfoModel = new UserExtInfoModel();
                    $addressModel = new AddressModel();
                    $userExtInfo = $userExtInfoModel->where('user_id', $user['id'])->first();
                    $userAddress = $addressModel->where('user_id', $user['id'])->first();
                    
                    // Calculate age
                    $age = '';
                    if ($user['birthdate']) {
                        $birthDate = new \DateTime($user['birthdate']);
                        $today = new \DateTime('today');
                        $age = $birthDate->diff($today)->y;
                    }
                    
                    // Get barangay name
                    $barangayName = '';
                    if ($userAddress && $userAddress['barangay']) {
                        $barangayName = BarangayHelper::getBarangayName($userAddress['barangay']);
                    }
                    
                    $statusField = ($session === 'morning') ? 'status_am' : 'status_pm';
                    $originalStatus = $existingAttendance[$statusField] ?? 'Present';
                    
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => ucfirst($session) . ' session check-out successful (30+ minutes timeout met)',
                        'data' => [
                            'user' => [
                                'name' => trim($user['first_name'] . ' ' . ($user['middle_name'] ? $user['middle_name'] . ' ' : '') . $user['last_name']),
                                'first_name' => $user['first_name'],
                                'last_name' => $user['last_name'],
                                'middle_name' => $user['middle_name'] ?? '',
                                'id' => $userIdForAttendance,
                                'user_id' => $user['user_id'] ?? '',
                                'rfid_code' => $rfidCode ?? $user['rfid_code'] ?? '',
                                'session' => $session,
                                'action' => 'check_out',
                                'status' => $originalStatus,
                                'attendanceStatus' => $originalStatus,
                                'time' => date('g:i A'),
                                'age' => $age,
                                'sex' => $user['sex'] == 1 ? 'Male' : 'Female',
                                'birthdate' => $user['birthdate'] ?? '',
                                'position' => $user['position'] ?? '',
                                'user_type' => $user['user_type'] ?? '',
                                'barangay' => $barangayName,
                                'zone_purok' => $userAddress['zone_purok'] ?? '',
                                'email' => $user['email'] ?? '',
                                'phone_number' => $user['phone_number'] ?? '',
                                'profile_picture' => $userExtInfo['profile_picture'] ?? null
                            ]
                        ]
                    ]);
                } else {
                    // Block duplicate entry - less than 30 minutes
                    $remainingMinutes = 30 - $minutesDifference;
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => "Duplicate entry - already scanned. Please wait {$remainingMinutes} more minutes to check out.",
                        'duplicate' => true,
                        'remaining_minutes' => $remainingMinutes
                    ]);
                }
            }
            
            // Get additional user information
            $userExtInfoModel = new UserExtInfoModel();
            $addressModel = new AddressModel();
            
            $userExtInfo = $userExtInfoModel->where('user_id', $user['id'])->first();
            $userAddress = $addressModel->where('user_id', $user['id'])->first();
            
            // Calculate age
            $age = '';
            if ($user['birthdate']) {
                $birthDate = new \DateTime($user['birthdate']);
                $today = new \DateTime('today');
                $age = $birthDate->diff($today)->y;
            }
            
            // Get barangay name
            $barangayName = '';
            if ($userAddress && $userAddress['barangay']) {
                $barangayName = BarangayHelper::getBarangayName($userAddress['barangay']);
            }
            
            // LATECOMER IDENTIFICATION
            // Calculate if user is late (30+ minutes after session start)
            $startTimeObj = new \DateTime(date('Y-m-d') . ' ' . $startTime);
            $currentTimeObj = new \DateTime($currentDateTime);
            $timeDiffFromStart = $currentTimeObj->diff($startTimeObj);
            $minutesFromStart = ($timeDiffFromStart->h * 60) + $timeDiffFromStart->i;
            
            // User is Late if they arrive 30+ minutes after session start
            $status = ($minutesFromStart >= 30) ? 'Late' : 'Present';
            
            // Record check-in for the session
            $message = '';
            if ($session === 'morning') {
                $attendanceModel->recordTimeInAM($userIdForAttendance, $eventId, $rfidCode, $status);
                $message = "Morning session check-in successful - Status: {$status}";
            } else if ($session === 'afternoon') {
                $attendanceModel->recordTimeInPM($userIdForAttendance, $eventId, $rfidCode, $status);
                $message = "Afternoon session check-in successful - Status: {$status}";
            }
            
            // Add debug information for troubleshooting
            $debugInfo = [
                'event_id' => $eventId,
                'user_lookup_method' => $rfidCode ? 'rfid' : 'user_id',
                'rfid_code' => $rfidCode,
                'user_id_param' => $userId,
                'user_found_id' => $userIdForAttendance,
                'session' => $session,
                'status' => $status,
                'minutes_from_start' => $minutesFromStart,
                'current_time' => $currentTime,
                'start_time' => $startTime,
                'end_time' => $endTime
            ];
            
            
            return $this->response->setJSON([
                'success' => true,
                'message' => $message,
                'data' => [
                    'user' => [
                        'name' => trim($user['first_name'] . ' ' . ($user['middle_name'] ? $user['middle_name'] . ' ' : '') . $user['last_name']),
                        'first_name' => $user['first_name'],
                        'last_name' => $user['last_name'],
                        'middle_name' => $user['middle_name'] ?? '',
                        'id' => $userIdForAttendance,
                        'user_id' => $user['user_id'] ?? '',
                        'rfid_code' => $rfidCode ?? $user['rfid_code'] ?? '',
                        'session' => $session,
                        'action' => 'check_in',
                        'status' => $status,
                        'attendanceStatus' => $status,
                        'time' => date('g:i A'),
                        'age' => $age,
                        'sex' => $user['sex'] == 1 ? 'Male' : 'Female',
                        'birthdate' => $user['birthdate'] ?? '',
                        'position' => $user['position'] ?? '',
                        'user_type' => $user['user_type'] ?? '',
                        'barangay' => $barangayName,
                        'zone_purok' => $userAddress['zone_purok'] ?? '',
                        'email' => $user['email'] ?? '',
                        'phone_number' => $user['phone_number'] ?? '',
                        'profile_picture' => $userExtInfo['profile_picture'] ?? null,
                        'minutes_from_start' => $minutesFromStart
                    ]
                ],
                'debug' => $debugInfo
            ]);
            
        } catch (Exception $e) {
            log_message('error', 'Error processing attendance: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Failed to process attendance: ' . $e->getMessage(),
                'debug' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ]);
        }
    }

    public function attendanceReport($eventId)
    {
        $session = session();
        $eventModel = new EventModel();
        $attendanceModel = new AttendanceModel();
        
        // Determine user type from current URI
        $request = \Config\Services::request();
        $uri = $request->getUri();
        $path = $uri->getPath();
        $userType = strpos($path, '/pederasyon/') !== false ? 'pederasyon' : 'sk';
        
        $event = $eventModel->find($eventId);
        if (!$event) {
            $redirectPath = $userType === 'pederasyon' ? 'pederasyon/attendance' : 'sk/attendance';
            return redirect()->to($redirectPath)->with('error', 'Event not found');
        }
        
        // Get all attendance records with full user details
        $attendanceRecords = $attendanceModel->getEventAttendanceWithUserDetails($eventId);
        
        // Process attendance records to include formatted user data
        $processedRecords = [];
        foreach ($attendanceRecords as $record) {
            // Calculate age
            $age = '';
            if ($record['birthdate']) {
                $birthDate = new \DateTime($record['birthdate']);
                $today = new \DateTime('today');
                $age = $birthDate->diff($today)->y;
            }
            
            // Get barangay name
            $barangayName = '';
            if ($record['barangay']) {
                $barangayName = BarangayHelper::getBarangayName($record['barangay']);
            }
            
            // Format full name
            $fullName = trim($record['first_name'] . ' ' . ($record['middle_name'] ? $record['middle_name'] . ' ' : '') . $record['last_name']);
            
            $processedRecords[] = [
                'attendance_id' => $record['attendance_id'],
                'user_id' => $record['user_id'],
                'user_name' => $fullName,
                'first_name' => $record['first_name'],
                'middle_name' => $record['middle_name'],
                'last_name' => $record['last_name'],
                'permanent_user_id' => $record['permanent_user_id'],
                'rfid_code' => $record['rfid_code'],
                'time-in_am' => $record['time-in_am'],
                'time-out_am' => $record['time-out_am'],
                'time-in_pm' => $record['time-in_pm'],
                'time-out_pm' => $record['time-out_pm'],
                'status_am' => $record['status_am'],
                'status_pm' => $record['status_pm'],
                'age' => $age,
                'sex' => $record['sex'] == 1 ? 'Male' : 'Female',
                'user_type' => $record['user_type'],
                'barangay' => $barangayName,
                'barangay_name' => $barangayName,  // FIXED: Added barangay_name field for consistency
                'zone_purok' => $record['zone_purok'] ?? '',
                'profile_picture' => $record['profile_picture']
            ];
        }
        
        $data = [
            'user_id' => $session->get('user_id'),
            'username' => $session->get('username'),
            'event' => $event,
            'attendance_records' => $processedRecords,
            'user_type' => $userType
        ];

        // Add SK-specific data if needed
        if ($userType === 'sk') {
            $skBarangay = $session->get('sk_barangay');
            $barangayName = BarangayHelper::getBarangayName($skBarangay);
            $data['sk_barangay'] = $skBarangay;
            $data['barangay_name'] = $barangayName;
        } elseif ($userType === 'pederasyon') {
            // FIXED: Add Pederasyon barangay support
            // Get barangay name from first processed record if available
            $barangayNameFromRecords = '';
            if (!empty($processedRecords)) {
                foreach ($processedRecords as $record) {
                    if (!empty($record['barangay'])) {
                        $barangayNameFromRecords = $record['barangay'];
                        break;
                    }
                }
            }
            $data['barangay_name'] = $barangayNameFromRecords;
        }

        // Return appropriate views based on user type
        if ($userType === 'pederasyon') {
            return 
                $this->loadView('K-NECT/Pederasyon/template/header') .
                $this->loadView('K-NECT/Pederasyon/template/sidebar') .
                $this->loadView('K-NECT/Pederasyon/attendance_report', $data);
        } else {
            return 
                $this->loadView('K-NECT/SK/template/header') .
                $this->loadView('K-NECT/SK/template/sidebar') .
                $this->loadView('K-NECT/SK/attendance_report', $data);
        }
    }

    // ================== ATTENDANCE REPORT DOWNLOADS ==================
    
    /**
     * Generate Excel attendance report
     */
    public function generateAttendanceReportExcel($eventId)
    {
        try {
            log_message('info', 'Starting Attendance Report Excel generation for event: ' . $eventId);
            
            // Get event and attendance data
            $eventModel = new \App\Models\EventModel();
            $event = $eventModel->find($eventId);
            
            if (!$event) {
                return $this->response->setJSON(['success' => false, 'message' => 'Event not found']);
            }
            
            // Get attendance records for this event
            $attendanceData = $this->getAttendanceDataForEvent($eventId);
            $attendanceRecords = $attendanceData['records'];
            $barangayName = $attendanceData['barangay_name'];
            
            if (empty($attendanceRecords)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No attendance records found for this event']);
            }
            
            // Generate Excel document
            $outputFile = $this->generateAttendanceExcelDocument($event, $attendanceRecords, $barangayName);
            
            if ($outputFile && file_exists($outputFile)) {
                $fileName = basename($outputFile);
                log_message('info', 'Excel document ready for download: ' . $fileName);
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Attendance report Excel document generated successfully',
                    'download_url' => base_url('uploads/generated/' . $fileName),
                    'record_count' => count($attendanceRecords)
                ]);
            } else {
                log_message('error', 'Excel document file not created or does not exist');
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Error generating Excel document - file not created'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in generateAttendanceReportExcel: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Generate Word attendance report
     */
    public function generateAttendanceReportWord($eventId)
    {
        // Preflight: Zip is required for PhpWord (DOCX)
        if (!class_exists('ZipArchive') || !extension_loaded('zip')) {
            $ini = function_exists('php_ini_loaded_file') ? (php_ini_loaded_file() ?: 'php.ini') : 'php.ini';
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Missing PHP zip extension. Enable extension=zip in ' . $ini . ' and restart the server to generate Word documents.'
            ]);
        }
        try {
            log_message('info', 'Starting Attendance Report Word generation for event: ' . $eventId);
            
            // Get event and attendance data
            $eventModel = new \App\Models\EventModel();
            $event = $eventModel->find($eventId);
            
            if (!$event) {
                return $this->response->setJSON(['success' => false, 'message' => 'Event not found']);
            }
            
            // Get attendance records for this event
            $attendanceData = $this->getAttendanceDataForEvent($eventId);
            $attendanceRecords = $attendanceData['records'];
            $barangayName = $attendanceData['barangay_name'];
            
            if (empty($attendanceRecords)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No attendance records found for this event']);
            }
            
            // Get logos for the Word document
            $logos = $this->getLogosForDocument();
            
            // Generate Word document
            $outputFile = $this->generateAttendanceWordDocument($event, $attendanceRecords, $logos, $barangayName);
            
            if ($outputFile && file_exists($outputFile)) {
                $fileName = basename($outputFile);
                log_message('info', 'Word document ready for download: ' . $fileName);
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Attendance report Word document generated successfully',
                    'download_url' => base_url('uploads/generated/' . $fileName),
                    'record_count' => count($attendanceRecords)
                ]);
            } else {
                log_message('error', 'Word document file not created or does not exist');
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Error generating Word document - file not created'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in generateAttendanceReportWord: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Generate Excel document for attendance report
     */
    private function generateAttendanceExcelDocument($event, $attendanceRecords, $barangayName = null)
    {
        try {
            require_once FCPATH . '../vendor/autoload.php';
            
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set page orientation to landscape
            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            $sheet->getPageSetup()->setFitToPage(true);
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);
            
            $currentRow = 1;
            
            // Header text - FIXED: Merge across all 10 columns for proper centering
            $sheet->setCellValue('A' . $currentRow, 'REPUBLIC OF THE PHILIPPINES');
            $sheet->mergeCells('A' . $currentRow . ':J' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $currentRow++;
            
            $sheet->setCellValue('A' . $currentRow, 'PROVINCE OF CAMARINES SUR');
            $sheet->mergeCells('A' . $currentRow . ':J' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $currentRow++;
            
            $sheet->setCellValue('A' . $currentRow, 'CITY OF IRIGA');
            $sheet->mergeCells('A' . $currentRow . ':J' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $currentRow++;
            
            $sheet->setCellValue('A' . $currentRow, 'SANGGUNIANG KABATAAN');
            $sheet->mergeCells('A' . $currentRow . ':J' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(false)->setSize(10);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $currentRow++;
            
            // Add barangay name if available
            if ($barangayName) {
                $sheet->setCellValue('A' . $currentRow, 'NG BARANGAY ' . strtoupper($barangayName));
                $sheet->mergeCells('A' . $currentRow . ':J' . $currentRow);
                $sheet->getStyle('A' . $currentRow)->getFont()->setBold(false)->setSize(10);
                $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $currentRow++;
            }
            $currentRow++;
            
            // Title
            $sheet->setCellValue('A' . $currentRow, 'ATTENDANCE REPORT');
            $sheet->mergeCells('A' . $currentRow . ':J' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $currentRow += 2;
            
            // Event details
            $sheet->setCellValue('A' . $currentRow, 'Event: ' . $event['title']);
            $sheet->mergeCells('A' . $currentRow . ':J' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(false)->setSize(10);
            $currentRow++;
            
            $sheet->setCellValue('A' . $currentRow, 'Date: ' . date('F j, Y', strtotime($event['start_datetime'])));
            $sheet->mergeCells('A' . $currentRow . ':J' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(false)->setSize(10);
            $currentRow++;
            
            $sheet->setCellValue('A' . $currentRow, 'Time: ' . date('g:i A', strtotime($event['start_datetime'])) . ' - ' . date('g:i A', strtotime($event['end_datetime'])));
            $sheet->mergeCells('A' . $currentRow . ':J' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(false)->setSize(10);
            $currentRow++;
            
            if (!empty($event['location'])) {
                $sheet->setCellValue('A' . $currentRow, 'Location: ' . $event['location']);
                $sheet->mergeCells('A' . $currentRow . ':J' . $currentRow);
                $sheet->getStyle('A' . $currentRow)->getFont()->setBold(false)->setSize(10);
                $currentRow++;
            }
            
            $currentRow += 2;
            
            // Table headers
            $headers = [
                'A' => 'No.',
                'B' => 'KK Number', 
                'C' => 'Name',
                'D' => 'Zone',
                'E' => 'AM Time-In',
                'F' => 'AM Time-Out',
                'G' => 'AM Status',
                'H' => 'PM Time-In',
                'I' => 'PM Time-Out',
                'J' => 'PM Status'
            ];
            
            // Add and style headers
            $headerRowNum = $currentRow;
            foreach ($headers as $col => $header) {
                $sheet->setCellValue($col . $currentRow, $header);
                $sheet->getStyle($col . $currentRow)->getFont()->setBold(true)->setSize(10);
                $sheet->getStyle($col . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle($col . $currentRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $sheet->getStyle($col . $currentRow)->getFill()->getStartColor()->setRGB('E8E8E8');
                $sheet->getStyle($col . $currentRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            }
            $currentRow++;
            
            // Add data rows
            foreach ($attendanceRecords as $index => $record) {
                // AM Time-In
                $amTimeIn = '-';
                if (!empty($record['time-in_am'])) {
                    $amTimeIn = date('h:i A', strtotime($record['time-in_am']));
                }
                
                // AM Time-Out
                $amTimeOut = '-';
                if (!empty($record['time-out_am'])) {
                    $amTimeOut = date('h:i A', strtotime($record['time-out_am']));
                }
                
                // PM Time-In
                $pmTimeIn = '-';
                if (!empty($record['time-in_pm'])) {
                    $pmTimeIn = date('h:i A', strtotime($record['time-in_pm']));
                }
                
                // PM Time-Out
                $pmTimeOut = '-';
                if (!empty($record['time-out_pm'])) {
                    $pmTimeOut = date('h:i A', strtotime($record['time-out_pm']));
                }
                
                // AM Status
                $amStatus = 'Absent';
                if (!empty($record['time-in_am'])) {
                    if (!empty($record['status_am']) && strtolower($record['status_am']) === 'late') {
                        $amStatus = 'Late';
                    } elseif (!empty($record['time-out_am'])) {
                        $amStatus = 'Complete';
                    } else {
                        $amStatus = 'Present';
                    }
                }
                
                // PM Status
                $pmStatus = 'Absent';
                if (!empty($record['time-in_pm'])) {
                    if (!empty($record['status_pm']) && strtolower($record['status_pm']) === 'late') {
                        $pmStatus = 'Late';
                    } elseif (!empty($record['time-out_pm'])) {
                        $pmStatus = 'Complete';
                    } else {
                        $pmStatus = 'Present';
                    }
                }
                
                // Add data to Excel
                $sheet->setCellValue('A' . $currentRow, $index + 1);
                $sheet->setCellValue('B' . $currentRow, $record['permanent_user_id'] ?? 'N/A');
                $sheet->setCellValue('C' . $currentRow, $record['user_name'] ?? 'N/A');
                $sheet->setCellValue('D' . $currentRow, $record['zone_purok'] ?? 'N/A');
                $sheet->setCellValue('E' . $currentRow, $amTimeIn);
                $sheet->setCellValue('F' . $currentRow, $amTimeOut);
                $sheet->setCellValue('G' . $currentRow, $amStatus);
                $sheet->setCellValue('H' . $currentRow, $pmTimeIn);
                $sheet->setCellValue('I' . $currentRow, $pmTimeOut);
                $sheet->setCellValue('J' . $currentRow, $pmStatus);
                
                // Style data cells
                foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'] as $col) {
                    $sheet->getStyle($col . $currentRow)->getFont()->setSize(9);
                    $sheet->getStyle($col . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle($col . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $sheet->getStyle($col . $currentRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                }
                
                $sheet->getRowDimension($currentRow)->setRowHeight(20);
                $currentRow++;
            }
            
            // Set optimal column widths
            $sheet->getColumnDimension('A')->setWidth(6);  // No.
            $sheet->getColumnDimension('B')->setWidth(12); // KK Number
            $sheet->getColumnDimension('C')->setWidth(25); // Name
            $sheet->getColumnDimension('D')->setWidth(8);  // Zone
            $sheet->getColumnDimension('E')->setWidth(12); // AM Time-In
            $sheet->getColumnDimension('F')->setWidth(12); // AM Time-Out
            $sheet->getColumnDimension('G')->setWidth(10); // AM Status
            $sheet->getColumnDimension('H')->setWidth(12); // PM Time-In
            $sheet->getColumnDimension('I')->setWidth(12); // PM Time-Out
            $sheet->getColumnDimension('J')->setWidth(10); // PM Status
            
            // Save the document
            $outputDir = FCPATH . 'uploads/generated/';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $eventTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $event['title']);
            $fileName = 'Attendance_Report_' . $eventTitle . '_' . date('Y-m-d_H-i-s') . '.xlsx';
            $outputPath = $outputDir . $fileName;
            
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($outputPath);
            
            log_message('info', 'Excel document saved to: ' . $outputPath);
            return $outputPath;
            
        } catch (\Exception $e) {
            log_message('error', 'Error in generateAttendanceExcelDocument: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Generate Word attendance report
     */
    private function generateAttendanceWordDocument($event, $attendanceRecords, $logos = [], $barangayName = null)
    {
        try {
            log_message('info', 'Starting Attendance Report Word document creation...');
            
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            
            // Set document properties
            $properties = $phpWord->getDocInfo();
            $properties->setCreator('K-NECT System');
            $properties->setCompany('Sangguniang Kabataan');
            $properties->setTitle('Attendance Report - ' . $event['title']);
            $properties->setDescription('Attendance report generated from K-NECT System');
            $properties->setSubject('Attendance Report');
            
            // Add section with landscape orientation
            $section = $phpWord->addSection([
                'orientation' => 'landscape',
                'marginLeft' => 720,
                'marginRight' => 720,
                'marginTop' => 720,
                'marginBottom' => 720
            ]);
            
            // Header styles
            $headerStyle = ['name' => 'Arial', 'size' => 12, 'bold' => true];
            $subHeaderStyle = ['name' => 'Arial', 'size' => 10, 'bold' => false];
            $titleStyle = ['name' => 'Arial', 'size' => 14, 'bold' => true];
            $tableHeaderStyle = ['name' => 'Arial', 'size' => 8, 'bold' => true];
            $tableCellStyle = ['name' => 'Arial', 'size' => 8];
            
            // Create header section with logos
            $headerTable = $section->addTable([
                'borderSize' => 0,
                'borderColor' => 'FFFFFF',
                'width' => 100 * 50,
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
            ]);
            $headerTable->addRow();
            
            // Left logo cell (SK)
            $leftCell = $headerTable->addCell(2000, ['valign' => 'center']);
            if (isset($logos['sk'])) {
                $logoPath = FCPATH . $logos['sk']['file_path'];
                if (file_exists($logoPath)) {
                    try {
                        $leftCell->addImage($logoPath, [
                            'width' => 50.4,
                            'height' => 50.4,
                            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
                        ]);
                    } catch (\Exception $e) {
                        $leftCell->addText('SK LOGO', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                    }
                } else {
                    $leftCell->addText('SK LOGO', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                }
            } else {
                $leftCell->addText('SK LOGO', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            }
            
            // Center text cell
            $centerCell = $headerTable->addCell(6000, ['valign' => 'center']);
            $centerCell->addText('REPUBLIC OF THE PHILIPPINES', $headerStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $centerCell->addText('PROVINCE OF CAMARINES SUR', $headerStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $centerCell->addText('CITY OF IRIGA', $headerStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $centerCell->addText('SANGGUNIANG KABATAAN', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            if ($barangayName) {
                $centerCell->addText('NG BARANGAY ' . strtoupper($barangayName), $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            }
            
            // Right logo cell (Iriga City)
            $rightCell = $headerTable->addCell(2000, ['valign' => 'center']);
            if (isset($logos['iriga_city'])) {
                $logoPath = FCPATH . $logos['iriga_city']['file_path'];
                if (file_exists($logoPath)) {
                    try {
                        $rightCell->addImage($logoPath, [
                            'width' => 50.4,
                            'height' => 50.4,
                            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
                        ]);
                    } catch (\Exception $e) {
                        $rightCell->addText('IRIGA LOGO', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                    }
                } else {
                    $rightCell->addText('IRIGA LOGO', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                }
            } else {
                $rightCell->addText('IRIGA LOGO', $subHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            }
            
            // Add title and event details
            $section->addTextBreak();
            $section->addText('ATTENDANCE REPORT', $titleStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $section->addTextBreak();
            
            // Event information - FIXED: Remove space after paragraphs
            $section->addText('Event: ' . $event['title'], $subHeaderStyle, ['spaceAfter' => 0]);
            $section->addText('Date: ' . date('F j, Y', strtotime($event['start_datetime'])), $subHeaderStyle, ['spaceAfter' => 0]);
            $section->addText('Time: ' . date('g:i A', strtotime($event['start_datetime'])) . ' - ' . date('g:i A', strtotime($event['end_datetime'])), $subHeaderStyle, ['spaceAfter' => 0]);
            if (!empty($event['location'])) {
                $section->addText('Location: ' . $event['location'], $subHeaderStyle, ['spaceAfter' => 0]);
            }
            $section->addTextBreak();
            
            // Create attendance table with optimized column widths for landscape
            $table = $section->addTable([
                'borderSize' => 4,
                'borderColor' => '000000',
                'cellMargin' => 20,
                'width' => 100 * 50,
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
            ]);
            
            // Add table header with proper column widths
            $table->addRow();
            $table->addCell(1000)->addText('No.', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(1500)->addText('KK Number', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(3500)->addText('Name', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(1000)->addText('Zone', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(1200)->addText('AM Time-In', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(1200)->addText('AM Time-Out', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(1200)->addText('AM Status', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(1200)->addText('PM Time-In', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(1200)->addText('PM Time-Out', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(1200)->addText('PM Status', $tableHeaderStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            
            // Add data rows
            foreach ($attendanceRecords as $index => $record) {
                // AM Time-In
                $amTimeIn = '-';
                if (!empty($record['time-in_am'])) {
                    $amTimeIn = date('h:i A', strtotime($record['time-in_am']));
                }
                
                // AM Time-Out
                $amTimeOut = '-';
                if (!empty($record['time-out_am'])) {
                    $amTimeOut = date('h:i A', strtotime($record['time-out_am']));
                }
                
                // PM Time-In
                $pmTimeIn = '-';
                if (!empty($record['time-in_pm'])) {
                    $pmTimeIn = date('h:i A', strtotime($record['time-in_pm']));
                }
                
                // PM Time-Out
                $pmTimeOut = '-';
                if (!empty($record['time-out_pm'])) {
                    $pmTimeOut = date('h:i A', strtotime($record['time-out_pm']));
                }
                
                // AM Status
                $amStatus = 'Absent';
                if (!empty($record['time-in_am'])) {
                    if (!empty($record['status_am']) && strtolower($record['status_am']) === 'late') {
                        $amStatus = 'Late';
                    } elseif (!empty($record['time-out_am'])) {
                        $amStatus = 'Complete';
                    } else {
                        $amStatus = 'Present';
                    }
                }
                
                // PM Status
                $pmStatus = 'Absent';
                if (!empty($record['time-in_pm'])) {
                    if (!empty($record['status_pm']) && strtolower($record['status_pm']) === 'late') {
                        $pmStatus = 'Late';
                    } elseif (!empty($record['time-out_pm'])) {
                        $pmStatus = 'Complete';
                    } else {
                        $pmStatus = 'Present';
                    }
                }
                
                // Format name as Lastname, Firstname Middlename
                $userName = $record['user_name'] ?? 'N/A';
                $nameParts = explode(' ', trim($userName));
                if (count($nameParts) >= 2) {
                    $firstName = $nameParts[0];
                    $lastName = end($nameParts);
                    $middleName = count($nameParts) > 2 ? implode(' ', array_slice($nameParts, 1, -1)) : '';
                    $formattedName = $lastName . ', ' . $firstName . ($middleName ? ' ' . $middleName : '');
                } else {
                    $formattedName = $userName;
                }
                
                $table->addRow();
                $table->addCell(1000)->addText($index + 1, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                $table->addCell(1500)->addText($record['permanent_user_id'] ?? 'N/A', $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                $table->addCell(3500)->addText($formattedName, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT]);
                $table->addCell(1000)->addText($record['zone_purok'] ?? 'N/A', $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                $table->addCell(1200)->addText($amTimeIn, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                $table->addCell(1200)->addText($amTimeOut, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                $table->addCell(1200)->addText($amStatus, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                $table->addCell(1200)->addText($pmTimeIn, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                $table->addCell(1200)->addText($pmTimeOut, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                $table->addCell(1200)->addText($pmStatus, $tableCellStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            }
            
            // Save the document
            $outputDir = FCPATH . 'uploads/generated/';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $eventTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $event['title']);
            $fileName = 'Attendance_Report_' . $eventTitle . '_' . date('Y-m-d_H-i-s') . '.docx';
            $outputPath = $outputDir . $fileName;
            
            $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save($outputPath);
            
            log_message('info', 'Word document saved to: ' . $outputPath);
            return $outputPath;
            
        } catch (\Exception $e) {
            log_message('error', 'Error in generateAttendanceWordDocument: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Get logos for document generation
     */
    private function getLogosForDocument()
    {
        try {
            $systemLogoModel = new \App\Models\SystemLogoModel();
            $logos = [];
            
            // Get SK logo
            $skLogo = $systemLogoModel->where('logo_type', 'sk')
                                   ->where('is_active', true)
                                   ->orderBy('created_at', 'DESC')
                                   ->first();
            if ($skLogo) {
                $logos['sk'] = $skLogo;
                log_message('info', 'SK logo found: ' . $skLogo['file_path']);
            } else {
                log_message('warning', 'SK logo not found');
            }
            
            // Get Iriga City logo
            $irigaLogo = $systemLogoModel->where('logo_type', 'iriga_city')
                                        ->where('is_active', true)
                                        ->orderBy('created_at', 'DESC')
                                        ->first();
            if ($irigaLogo) {
                $logos['iriga_city'] = $irigaLogo;
                log_message('info', 'Iriga City logo found: ' . $irigaLogo['file_path']);
            } else {
                log_message('warning', 'Iriga City logo not found');
            }
            
            return $logos;
        } catch (\Exception $e) {
            log_message('error', 'Error getting logos for document: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get attendance data for a specific event with user information
     */
    private function getAttendanceDataForEvent($eventId)
    {
        try {
            $attendanceModel = new AttendanceModel();
            $userModel = new \App\Models\UserModel();
            $addressModel = new \App\Models\AddressModel();
            
            // Get attendance records for this event
            $attendanceRecords = $attendanceModel->where('event_id', $eventId)->findAll();
            
            $barangayName = null;
            
            // Enhance records with user information
            foreach ($attendanceRecords as &$record) {
                // Get user information
                if (!empty($record['user_id'])) {
                    $user = $userModel->where('user_id', $record['user_id'])->first();
                    if ($user) {
                        $record['permanent_user_id'] = $user['user_id'];
                        $record['user_name'] = trim($user['first_name'] . ' ' . ($user['middle_name'] ? $user['middle_name'] . ' ' : '') . $user['last_name']);
                        
                        // Get address information for zone and barangay
                        $address = $addressModel->where('user_id', $user['id'])->first();
                        if ($address) {
                            $record['zone_purok'] = $address['zone_purok'];
                            // Store the first barangay name found for document header - FIXED: Convert ID to name
                            if (!$barangayName && !empty($address['barangay'])) {
                                $barangayName = BarangayHelper::getBarangayName($address['barangay']);
                            }
                            // Also store barangay name in record for table display
                            $record['barangay_name'] = BarangayHelper::getBarangayName($address['barangay']);
                        }
                    }
                }
            }
            
            // Add barangay name to the result for use in document headers
            return [
                'records' => $attendanceRecords,
                'barangay_name' => $barangayName
            ];
        } catch (\Exception $e) {
            log_message('error', 'Error getting attendance data for event: ' . $e->getMessage());
            return [
                'records' => [],
                'barangay_name' => null
            ];
        }
    }

    // ================== ATTENDANCE SYSTEM (END) ==================
    
    public function autoTimeoutSession()
    {
        $eventId = $this->request->getPost('event_id');
        $session = $this->request->getPost('session'); // 'morning' or 'afternoon'
        
        if (!$eventId || !$session) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing required fields']);
        }

        try {
            $attendanceModel = new AttendanceModel();
            
            // Get all attendance records for this event that need timeout
            $timeInField = ($session === 'morning') ? 'time-in_am' : 'time-in_pm';
            $timeOutField = ($session === 'morning') ? 'time-out_am' : 'time-out_pm';
            
            $attendanceRecords = $attendanceModel->where('event_id', $eventId)
                ->where($timeInField . ' IS NOT NULL')
                ->where($timeOutField . ' IS NULL')
                ->findAll();
            
            $timeoutCount = 0;
            $currentDateTime = date('Y-m-d H:i:s');
            
            foreach ($attendanceRecords as $record) {
                // Update the timeout field
                $updateData = [$timeOutField => $currentDateTime];
                $attendanceModel->update($record['attendance_id'], $updateData);
                $timeoutCount++;
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => "Successfully timed out {$timeoutCount} users for {$session} session",
                'timeout_count' => $timeoutCount
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in auto timeout: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Failed to auto timeout users: ' . $e->getMessage()
            ]);
        }
    }
    
    public function checkAttendanceSettings()
    {
        $eventId = $this->request->getGet('event_id') ?? 1;
        
        $eventAttendanceModel = new EventAttendanceModel();
        $attendanceSettings = $eventAttendanceModel->getEventAttendanceSettings($eventId);
        
        $currentTime = date('H:i');
        $currentDate = date('Y-m-d H:i:s');
        
        $response = [
            'event_id' => $eventId,
            'current_time' => $currentTime,
            'current_date' => $currentDate,
            'attendance_settings' => $attendanceSettings
        ];
        
        if ($attendanceSettings) {
            // Check morning session
            $morningActive = false;
            if ($attendanceSettings['start_attendance_am'] && $attendanceSettings['end_attendance_am']) {
                $morningActive = ($currentTime >= $attendanceSettings['start_attendance_am'] && 
                                $currentTime <= $attendanceSettings['end_attendance_am']);
            }
            
            // Check afternoon session
            $afternoonActive = false;
            if ($attendanceSettings['start_attendance_pm'] && $attendanceSettings['end_attendance_pm']) {
                $afternoonActive = ($currentTime >= $attendanceSettings['start_attendance_pm'] && 
                                  $currentTime <= $attendanceSettings['end_attendance_pm']);
            }
            
            $response['session_status'] = [
                'morning_active' => $morningActive,
                'afternoon_active' => $afternoonActive,
                'morning_times' => [
                    'start' => $attendanceSettings['start_attendance_am'],
                    'end' => $attendanceSettings['end_attendance_am']
                ],
                'afternoon_times' => [
                    'start' => $attendanceSettings['start_attendance_pm'],
                    'end' => $attendanceSettings['end_attendance_pm']
                ]
            ];
        } else {
            $response['message'] = 'No attendance settings found for this event';
        }
        
        return $this->response->setJSON($response);
    }

    /**
     * Get current attendance status and session state for real-time updates
     */
    public function getAttendanceStatus($eventId)
    {
        try {
            $eventAttendanceModel = new EventAttendanceModel();
            $attendanceModel = new AttendanceModel();
            
            // Get current attendance settings
            $attendanceSettings = $eventAttendanceModel->getEventAttendanceSettings($eventId);
            
            // Set timezone
            date_default_timezone_set('Asia/Manila');
            $currentTime = date('H:i');
            $currentDateTime = date('Y-m-d H:i:s');
            
            // Calculate session states
            $morningStatus = $this->calculateSessionStatus($attendanceSettings, 'morning', $currentTime);
            $afternoonStatus = $this->calculateSessionStatus($attendanceSettings, 'afternoon', $currentTime);
            
            // Get latest attendance records
            $attendanceRecords = $attendanceModel->getEventAttendanceWithUserDetails($eventId);
            
            // Process records for display
            $processedRecords = [];
            foreach ($attendanceRecords as $record) {
                $age = '';
                if ($record['birthdate']) {
                    $birthDate = new \DateTime($record['birthdate']);
                    $today = new \DateTime('today');
                    $age = $birthDate->diff($today)->y;
                }
                
                $barangayName = '';
                if ($record['barangay']) {
                    $barangayName = BarangayHelper::getBarangayName($record['barangay']);
                }
                
                $fullName = trim($record['first_name'] . ' ' . ($record['middle_name'] ? $record['middle_name'] . ' ' : '') . $record['last_name']);
                
                $processedRecords[] = [
                    'attendance_id' => $record['attendance_id'],
                    'user_id' => $record['user_id'],
                    'user_name' => $fullName,
                    'first_name' => $record['first_name'],
                    'middle_name' => $record['middle_name'],
                    'last_name' => $record['last_name'],
                    'permanent_user_id' => $record['permanent_user_id'],
                    'rfid_code' => $record['rfid_code'],
                    'time-in_am' => $record['time-in_am'],
                    'time-out_am' => $record['time-out_am'],
                    'time-in_pm' => $record['time-in_pm'],
                    'time-out_pm' => $record['time-out_pm'],
                    'status_am' => $record['status_am'],
                    'status_pm' => $record['status_pm'],
                    'age' => $age,
                    'sex' => $record['sex'] == 1 ? 'Male' : 'Female',
                    'barangay_name' => $barangayName,
                    'zone_purok' => $record['zone_purok']
                ];
            }
            
            return $this->response->setJSON([
                'success' => true,
                'current_time' => $currentTime,
                'current_datetime' => $currentDateTime,
                'morning_status' => $morningStatus,
                'afternoon_status' => $afternoonStatus,
                'attendance_settings' => $attendanceSettings,
                'attendance_records' => $processedRecords,
                'settings_last_updated' => $attendanceSettings['updated_at'] ?? null
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error getting attendance status: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to get attendance status'
            ]);
        }
    }

    /**
     * Get session status for specific session
     */
    public function getSessionStatus($eventId)
    {
        try {
            $session = $this->request->getGet('session') ?? 'morning';
            $eventAttendanceModel = new EventAttendanceModel();
            
            $attendanceSettings = $eventAttendanceModel->getEventAttendanceSettings($eventId);
            
            date_default_timezone_set('Asia/Manila');
            $currentTime = date('H:i');
            
            $sessionStatus = $this->calculateSessionStatus($attendanceSettings, $session, $currentTime);
            
            return $this->response->setJSON([
                'success' => true,
                'session_status' => $sessionStatus,
                'current_time' => $currentTime
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to get session status'
            ]);
        }
    }

    /**
     * Calculate session status based on current time and settings
     */
    private function calculateSessionStatus($attendanceSettings, $session, $currentTime)
    {
        if (!$attendanceSettings) {
            return [
                'status' => 'inactive',
                'message' => 'No attendance settings configured',
                'active' => false
            ];
        }
        
        $startTime = null;
        $endTime = null;
        
        if ($session === 'morning') {
            $startTime = $attendanceSettings['start_attendance_am'] ?? null;
            $endTime = $attendanceSettings['end_attendance_am'] ?? null;
        } else {
            $startTime = $attendanceSettings['start_attendance_pm'] ?? null;
            $endTime = $attendanceSettings['end_attendance_pm'] ?? null;
        }
        
        if (!$startTime || !$endTime) {
            return [
                'status' => 'inactive',
                'message' => ucfirst($session) . ' session not configured',
                'active' => false,
                'start_time' => null,
                'end_time' => null
            ];
        }
        
        // Format times for display (12-hour format)
        $formattedStartTime = $this->formatTimeTo12Hour($startTime);
        $formattedEndTime = $this->formatTimeTo12Hour($endTime);
        
        // Convert time strings to minutes for precise comparison
        $currentMinutes = $this->timeStringToMinutes($currentTime);
        $startMinutes = $this->timeStringToMinutes($startTime);
        $endMinutes = $this->timeStringToMinutes($endTime);
        
        // Log for debugging
        error_log("Session: $session, Current: $currentTime ($currentMinutes min), Start: $startTime ($startMinutes min), End: $endTime ($endMinutes min)");
        
        // Compare times using minute values for exact precision
        if ($currentMinutes < $startMinutes) {
            return [
                'status' => 'waiting',
                'message' => ucfirst($session) . " Pending",
                'active' => false,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'countdown_target' => $startTime,
                'display_start_time' => $formattedStartTime,
                'display_end_time' => $formattedEndTime
            ];
        } else if ($currentMinutes >= $startMinutes && $currentMinutes < $endMinutes) {
            // Session is active from start minute until BEFORE end minute (not including end minute)
            return [
                'status' => 'active',
                'message' => ucfirst($session) . " Active",
                'active' => true,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'countdown_target' => $endTime,
                'display_start_time' => $formattedStartTime,
                'display_end_time' => $formattedEndTime
            ];
        } else {
            return [
                'status' => 'ended',
                'message' => ucfirst($session) . " Ended",
                'active' => false,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'display_start_time' => $formattedStartTime,
                'display_end_time' => $formattedEndTime
            ];
        }
    }

    /**
     * Format time from 24-hour (HH:MM) to 12-hour format (H:MM AM/PM)
     */
    private function formatTimeTo12Hour($timeString)
    {
        if (!$timeString) return '';
        
        // Parse the time string (HH:MM format)
        $timeParts = explode(':', $timeString);
        $hours = (int)$timeParts[0];
        $minutes = $timeParts[1];
        
        // Determine AM/PM
        $ampm = $hours >= 12 ? 'PM' : 'AM';
        
        // Convert to 12-hour format
        $displayHours = $hours % 12;
        $displayHours = $displayHours ? $displayHours : 12; // 0 should be 12
        
        return "{$displayHours}:{$minutes} {$ampm}";
    }

    /**
     * Convert time string (HH:MM) to total minutes for precise comparison
     */
    private function timeStringToMinutes($timeString)
    {
        if (!$timeString) return 0;
        
        $timeParts = explode(':', $timeString);
        $hours = (int)$timeParts[0];
        $minutes = (int)$timeParts[1];
        
        return $hours * 60 + $minutes;
    }

    /**
     * Automatically mark users as timed out when session ends
     */
    public function autoMarkTimeouts()
    {
        try {
            $eventId = $this->request->getPost('event_id');
            $session = $this->request->getPost('session'); // 'morning' or 'afternoon'
            
            if (!$eventId || !$session) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Missing required parameters'
                ]);
            }
            
            $attendanceModel = new AttendanceModel();
            $eventAttendanceModel = new EventAttendanceModel();
            
            // Get attendance settings
            $attendanceSettings = $eventAttendanceModel->getEventAttendanceSettings($eventId);
            if (!$attendanceSettings) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Attendance settings not found'
                ]);
            }
            
            date_default_timezone_set('Asia/Manila');
            $currentTime = date('H:i:s');
            
            // Determine if session has ended
            $endTime = null;
            $timeoutField = null;
            $statusField = null;
            
            if ($session === 'morning') {
                $endTime = $attendanceSettings['end_attendance_am'];
                $timeoutField = 'time-out_am';
                $statusField = 'status_am';
            } else {
                $endTime = $attendanceSettings['end_attendance_pm'];
                $timeoutField = 'time-out_pm';
                $statusField = 'status_pm';
            }
            
            if (!$endTime || $currentTime < $endTime) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Session has not ended yet'
                ]);
            }
            
            // Find users who are marked as Present but don't have timeout
            $usersToTimeout = $attendanceModel
                ->where('event_id', $eventId)
                ->where($statusField, 'Present')
                ->where("({$timeoutField} IS NULL OR {$timeoutField} = '')")
                ->findAll();
            
            $updatedCount = 0;
            foreach ($usersToTimeout as $user) {
                $updateData = [
                    $timeoutField => $endTime,
                    $statusField => 'Time-Out',
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                if ($attendanceModel->update($user['attendance_id'], $updateData)) {
                    $updatedCount++;
                }
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => "Automatically marked {$updatedCount} users as timed out",
                'updated_count' => $updatedCount
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in autoMarkTimeouts: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to auto-mark timeouts: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Process attendance records into stacked time-in and time-out entries
     * @param array $attendanceRecords Raw attendance records from database
     * @return array Processed stacked entries
     */
    private function processStackedAttendanceData($attendanceRecords)
    {
        $stackedData = [];
        
        foreach ($attendanceRecords as $record) {
            // Calculate age
            $age = '';
            if ($record['birthdate']) {
                $birthDate = new \DateTime($record['birthdate']);
                $today = new \DateTime('today');
                $age = $birthDate->diff($today)->y;
            }
            
            // Get barangay name
            $barangayName = '';
            if ($record['barangay']) {
                $barangayName = BarangayHelper::getBarangayName($record['barangay']);
            }
            
            // Format full name
            $fullName = trim($record['first_name'] . ' ' . ($record['middle_name'] ? $record['middle_name'] . ' ' : '') . $record['last_name']);
            
            // Base user data
            $userData = [
                'user_id' => $record['user_id'],
                'name' => $fullName,
                'first_name' => $record['first_name'],
                'last_name' => $record['last_name'],
                'middle_name' => $record['middle_name'] ?? '',
                'permanent_user_id' => $record['permanent_user_id'],
                'rfid_code' => $record['rfid_code'],
                'age' => $age,
                'sex' => $record['sex'] == 1 ? 'Male' : 'Female',
                'user_type' => $record['user_type'],
                'barangay' => $barangayName,
                'zone_purok' => $record['zone_purok'] ?? '',
                'profile_picture' => $record['profile_picture'],
                'attendance_id' => $record['attendance_id']
            ];
            
            // Process morning session entries
            if ($record['time-in_am']) {
                // Morning time-in entry
                $morningTimeIn = $userData;
                $morningTimeIn['session'] = 'morning';
                $morningTimeIn['time'] = $record['time-in_am'];
                $morningTimeIn['action'] = 'check_in';
                $morningTimeIn['status'] = $record['status_am'] ?? 'Present';
                $morningTimeIn['time_sort'] = strtotime($record['time-in_am']);
                $stackedData[] = $morningTimeIn;
                
                // Morning time-out entry (if exists)
                if ($record['time-out_am']) {
                    $morningTimeOut = $userData;
                    $morningTimeOut['session'] = 'morning';
                    $morningTimeOut['time'] = $record['time-out_am'];
                    $morningTimeOut['action'] = 'check_out';
                    $morningTimeOut['status'] = $record['status_am'] ?? 'Present';
                    $morningTimeOut['time_sort'] = strtotime($record['time-out_am']);
                    $stackedData[] = $morningTimeOut;
                }
            }
            
            // Process afternoon session entries
            if ($record['time-in_pm']) {
                // Afternoon time-in entry
                $afternoonTimeIn = $userData;
                $afternoonTimeIn['session'] = 'afternoon';
                $afternoonTimeIn['time'] = $record['time-in_pm'];
                $afternoonTimeIn['action'] = 'check_in';
                $afternoonTimeIn['status'] = $record['status_pm'] ?? 'Present';
                $afternoonTimeIn['time_sort'] = strtotime($record['time-in_pm']);
                $stackedData[] = $afternoonTimeIn;
                
                // Afternoon time-out entry (if exists)
                if ($record['time-out_pm']) {
                    $afternoonTimeOut = $userData;
                    $afternoonTimeOut['session'] = 'afternoon';
                    $afternoonTimeOut['time'] = $record['time-out_pm'];
                    $afternoonTimeOut['action'] = 'check_out';
                    $afternoonTimeOut['status'] = $record['status_pm'] ?? 'Present';
                    $afternoonTimeOut['time_sort'] = strtotime($record['time-out_pm']);
                    $stackedData[] = $afternoonTimeOut;
                }
            }
        }
        
        // Sort by time (newest first)
        usort($stackedData, function($a, $b) {
            return $b['time_sort'] - $a['time_sort'];
        });
        
        // Remove the sort helper field
        foreach ($stackedData as &$entry) {
            unset($entry['time_sort']);
        }
        
        return $stackedData;
    }
}
