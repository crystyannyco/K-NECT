<?php
namespace App\Controllers;

use App\Models\EventModel;
use App\Models\UserModel;
use App\Models\BarangayModel;
use CodeIgniter\Controller;
helper('sms');
use App\Controllers\GoogleCalendarController;

class EventController extends BaseController
{
    /**
     * Load view with proper header/sidebar based on user type
     */
    protected function loadView($view, $data = [])
    {
        $session = session();
        $userType = $session->get('user_type');
        
        // Merge any additional data
        $data = array_merge($this->data, $data);
        
        // Load appropriate template based on user type
        switch($userType) {
            case 'sk':
                return 
                    parent::loadView('K-NECT/SK/Template/Header', $data) .
                    parent::loadView('K-NECT/SK/Template/Sidebar', $data) .
                    parent::loadView($view, $data);
                    
            case 'kk':
                return 
                    parent::loadView('K-NECT/KK/Template/Header', $data) .
                    parent::loadView('K-NECT/KK/Template/Sidebar', $data) .
                    parent::loadView($view, $data);
                    
            case 'pederasyon':
                return 
                    parent::loadView('K-NECT/Pederasyon/Template/Header', $data) .
                    parent::loadView('K-NECT/Pederasyon/Template/Sidebar', $data) .
                    parent::loadView($view, $data);
                    
            default:
                // Fallback to basic view for unknown user types
                return parent::loadView($view, $data);
        }
    }
    
    public function index()
    {
        $session = session();
        $userType = $session->get('user_type');
        $role = $session->get('role');
        
        // Redirect based on user type
        if ($userType === 'sk' || $role === 'admin') {
            // SK Officials should see their barangay-specific events
            return $this->barangayEvents();
        } else if ($userType === 'kk') {
            // KK members should see their barangay-specific events (view-only)
            return $this->kkEvents();
        } else if ($userType === 'pederasyon' || $role === 'super_admin') {
            // Pederasyon should see city-wide events
            return $this->cityEvents();
        } else {
            // Default fallback
            return $this->cityEvents();
        }
    }

    /**
     * City-wide events view for Pederasyon (Super Admin)
     */
    public function cityEvents()
    {
        log_message('error', 'Test error log from EventController::cityEvents');
        $eventModel = new EventModel();
        $user = session();
        $role = $user->get('role');
        $barangayId = $user->get('barangay_id');
        
        // Super admin sees all events - ordered by created_at desc (newest first)
        $data['events'] = $eventModel->orderBy('created_at', 'DESC')->findAll();
        
        // Get predefined categories (same as in the form)
        $categories = [
            'health',
            'education',
            'economic empowerment',
            'social inclusion and equity',
            'peace building and security',
            'governance',
            'active citizenship',
            'environment',
            'global mobility',
            'others'
        ];
        $data['categories'] = $categories;
        
        // Get Google Calendar ID for logged-in user's barangay
        $calendarId = null;
        if ($barangayId) {
            $barangayModel = new \App\Models\BarangayModel();
            $barangay = $barangayModel->find($barangayId);
            if ($barangay && !empty($barangay['google_calendar_id'])) {
                $calendarId = $barangay['google_calendar_id'];
            }
        }
        $data['calendar_id'] = $calendarId;
        
        // Set calendar_tabs for super_admin
        $tabs = [];
        $barangayModel = new \App\Models\BarangayModel();
        $FEDERATION_CALENDAR_ID = 'knect.system@gmail.com';
        $barangays = $barangayModel->findAll();
        foreach ($barangays as $barangay) {
            $tabs[] = [
                'label' => $barangay['name'],
                'calendar_id' => $barangay['google_calendar_id'],
            ];
        }
        $tabs[] = [
            'label' => 'City-wide',
            'calendar_id' => $FEDERATION_CALENDAR_ID,
        ];
        
        $data['calendar_tabs'] = $tabs;
        $data['calendar_role'] = $role;
        $data['barangays'] = $barangays; // Pass barangays for dropdown
    return $this->loadView('K-NECT/events/city_list', $data);
    }

    /**
     * Barangay-specific events view for SK Officials (Admin)
     */
    public function barangayEvents()
    {
        $session = session();
        $skBarangay = $session->get('sk_barangay');
        $barangayId = $session->get('barangay_id') ?: $skBarangay;
        $userId = $session->get('user_id');
        $username = $session->get('username');
        
        $eventModel = new EventModel();
        $barangayModel = new \App\Models\BarangayModel();
        
        // Get barangay name
        $barangayName = '';
        if ($barangayId) {
            $barangay = $barangayModel->find($barangayId);
            $barangayName = $barangay ? $barangay['name'] : 'Unknown Barangay';
        }
        
        // Get only events for this barangay - ordered by created_at desc (newest first)
        $events = $eventModel->where('barangay_id', $barangayId)->orderBy('created_at', 'DESC')->findAll();
        
        // Get predefined categories (same as in the form)
        $categories = [
            'health',
            'education',
            'economic empowerment',
            'social inclusion and equity',
            'peace building and security',
            'governance',
            'active citizenship',
            'environment',
            'global mobility',
            'others'
        ];
        
        // Get Google Calendar ID for this barangay
        $calendarId = null;
        if ($barangayId) {
            $barangay = $barangayModel->find($barangayId);
            if ($barangay && !empty($barangay['google_calendar_id'])) {
                $calendarId = $barangay['google_calendar_id'];
            }
        }
        
        $data = [
            'events' => $events,
            'categories' => $categories,
            'calendar_id' => $calendarId,
            'calendar_tabs' => [], // No tabs for barangay-specific view
            'calendar_role' => 'admin',
            'user_id' => $userId,
            'username' => $username,
            'sk_barangay' => $skBarangay,
            'barangay_id' => $barangayId,
            'barangay_name' => $barangayName,
            'barangays' => [] // SK Officials only see their own barangay
        ];
        
    return $this->loadView('K-NECT/events/barangay_list', $data);
    }

    /**
     * Barangay-specific events view for KK Members (Read-only)
     */
    public function kkEvents()
    {
        $session = session();
        $barangayId = $session->get('barangay_id');
        $userId = $session->get('user_id');
        $username = $session->get('username');
        
        $eventModel = new EventModel();
        $barangayModel = new \App\Models\BarangayModel();
        
        // Get barangay name
        $barangayName = '';
        if ($barangayId) {
            $barangay = $barangayModel->find($barangayId);
            $barangayName = $barangay ? $barangay['name'] : 'Unknown Barangay';
        }
        
        // Get only published events for this barangay - ordered by created_at desc (newest first)
        $events = $eventModel->where('barangay_id', $barangayId)
                             ->where('status', 'Published')
                             ->orderBy('created_at', 'DESC')
                             ->findAll();
        
        // Get predefined categories (same as in the form)
        $categories = [
            'health',
            'education',
            'economic empowerment',
            'social inclusion and equity',
            'peace building and security',
            'governance',
            'active citizenship',
            'environment',
            'global mobility',
            'others'
        ];
        
        // Get Google Calendar ID for this barangay
        $calendarId = null;
        if ($barangayId) {
            $barangay = $barangayModel->find($barangayId);
            if ($barangay && !empty($barangay['google_calendar_id'])) {
                $calendarId = $barangay['google_calendar_id'];
            }
        }
        
        $data = [
            'events' => $events,
            'categories' => $categories,
            'calendar_id' => $calendarId,
            'calendar_tabs' => [], // No tabs for barangay-specific view
            'calendar_role' => 'member', // Different role for KK members
            'user_id' => $userId,
            'username' => $username,
            'barangay_id' => $barangayId,
            'barangay_name' => $barangayName,
            'barangays' => [], // KK members only see their own barangay
            'user_type' => 'kk' // Add user type for view logic
        ];
        
    return $this->loadView('K-NECT/events/kk_list', $data);
    }

    public function create()
    {
        // Access control: Only SK officials and Pederasyon can create events
        $session = session();
        $userType = $session->get('user_type');
        if ($userType === 'kk') {
            return redirect()->to('/events')->with('error', 'You do not have permission to create events.');
        }
        
        // Check if this is an AJAX request for modal
        if ($this->request->isAJAX()) {
            return view('K-NECT/events/_form');
        }
        
        return $this->loadView('K-NECT/events/form');
    }

    public function store()
    {
        // Access control: Only SK officials and Pederasyon can create events
        $session = session();
        $userType = $session->get('user_type');
        if ($userType === 'kk') {
            return redirect()->to('/events')->with('error', 'You do not have permission to create events.');
        }
        
        try {
        $eventModel = new EventModel();
        $createdBy = session('user_id') ?: 1;
        $barangayId = session('barangay_id');
        $role = session('role');
        $inputBarangayId = $this->request->getPost('barangay_id');

        if ($role === 'super_admin') {
            if ($inputBarangayId === null || $inputBarangayId === '' || $inputBarangayId === false) {
                $inputBarangayId = 0;
            }
        } else {
            $inputBarangayId = $barangayId;
        }

        $action = $this->request->getPost('submit_action');
        $isDraft = ($action === 'draft');
        
        $data = [
            'title' => $this->request->getPost('title') ?: ($isDraft ? 'Draft Event' : ''),
            'description' => $this->request->getPost('description') ?: '',
            'start_datetime' => $this->request->getPost('start_datetime') ?: '',
            'end_datetime' => $this->request->getPost('end_datetime') ?: '',
            'location' => $this->request->getPost('location') ?: '',
            'created_by' => $createdBy,
            'barangay_id' => $inputBarangayId,
            'category' => $this->request->getPost('category') ?: '',
        ];

        // Handle scheduling options
        $schedulingEnabled = $this->request->getPost('scheduling_enabled') ? 1 : 0;
        $data['scheduling_enabled'] = $schedulingEnabled;
        
        if ($schedulingEnabled) {
            $scheduledDatetime = $this->request->getPost('scheduled_publish_datetime');
            if (empty($scheduledDatetime) && !$isDraft) {
                return $this->handleErrorResponse('Scheduled publish date and time is required when scheduling is enabled.');
            }
            
            // Validate that scheduled datetime is in the future
            if (!empty($scheduledDatetime) && !$isDraft) {
                $currentTime = new \DateTime('now', new \DateTimeZone('Asia/Manila'));
                $scheduledTime = new \DateTime($scheduledDatetime, new \DateTimeZone('Asia/Manila'));
                
                if ($scheduledTime <= $currentTime) {
                    $currentTimeStr = $currentTime->format('Y-m-d H:i');
                    return $this->handleErrorResponse("Scheduled publish date and time must be after the current time ({$currentTimeStr}). Please select a future date and time.");
                }
            }
            
            $data['scheduled_publish_datetime'] = $scheduledDatetime;
        }

        // Handle SMS notification options
        $smsNotificationEnabled = $this->request->getPost('sms_notification_enabled') ? 1 : 0;
        $data['sms_notification_enabled'] = $smsNotificationEnabled;
        
        if ($smsNotificationEnabled && !$isDraft) {
            // Handle recipient scope (for superadmin only)
            if ($role === 'super_admin' && $inputBarangayId == 0) {
                $recipientScope = $this->request->getPost('sms_recipient_scope');
                $data['sms_recipient_scope'] = $recipientScope;
                
                if ($recipientScope === 'specific_barangays') {
                    $selectedBarangays = $this->request->getPost('sms_recipient_barangays');
                    if (empty($selectedBarangays)) {
                        return $this->handleErrorResponse('Please select at least one barangay for SMS notifications.');
                    }
                    $data['sms_recipient_barangays'] = json_encode($selectedBarangays);
                }
            }
            
            // Handle recipient roles
            $recipientRoles = $this->request->getPost('sms_recipient_roles');
            if (empty($recipientRoles)) {
                return redirect()->back()->withInput()->with('error', 'Please select at least one recipient role for SMS notifications.');
            }
            
            // Validate that if "All SK Officials" is selected, individual roles are not selected
            if (in_array('all_officials', $recipientRoles)) {
                $individualRoles = ['chairman', 'secretary', 'treasurer'];
                foreach ($individualRoles as $role) {
                    if (in_array($role, $recipientRoles)) {
                        return redirect()->back()->withInput()->with('error', 'Cannot select "All SK Officials" and individual SK roles at the same time.');
                    }
                }
            }
            
            $data['sms_recipient_roles'] = json_encode($recipientRoles);
        } elseif ($smsNotificationEnabled && $isDraft) {
            // For drafts, just save the SMS settings without validation
            if ($role === 'super_admin' && $inputBarangayId == 0) {
                $recipientScope = $this->request->getPost('sms_recipient_scope');
                $data['sms_recipient_scope'] = $recipientScope;
                
                if ($recipientScope === 'specific_barangays') {
                    $selectedBarangays = $this->request->getPost('sms_recipient_barangays');
                    $data['sms_recipient_barangays'] = json_encode($selectedBarangays ?: []);
                }
            }
            
            $recipientRoles = $this->request->getPost('sms_recipient_roles');
            $data['sms_recipient_roles'] = json_encode($recipientRoles ?: []);
        } else {
            // If SMS notifications are disabled, clear all SMS-related fields
            $data['sms_recipient_scope'] = null;
            $data['sms_recipient_barangays'] = null;
            $data['sms_recipient_roles'] = null;
        }

        // Handle event_banner upload
        $file = $this->request->getFile('event_banner');
        $fileError = '';
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = $file->getClientName();
            $fileSize = round($file->getSize() / (1024 * 1024), 2); // Size in MB
            
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                $fileError = "Invalid file type. Allowed formats: JPG, JPEG, PNG, WEBP.";
            } elseif ($file->getSize() > $maxSize) {
                $fileError = "Event banner is too large ({$fileSize} MB). Maximum allowed size is 5MB.";
            } else {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/event', $newName); // Save to public/uploads/event
                $data['event_banner'] = $newName;
                log_message('error', 'File uploaded successfully: ' . $newName);
            }
        } elseif ($file && $file->getError() > 0 && $file->getError() !== 4) {
            // Handle file upload errors (excluding error code 4 which means no file was uploaded)
            $errorMessages = [
                1 => 'The uploaded file exceeds the maximum file size allowed by the server.',
                2 => 'The uploaded file exceeds the maximum file size allowed by the form.',
                3 => 'The uploaded file was only partially uploaded.',
                6 => 'Missing a temporary folder.',
                7 => 'Failed to write file to disk.',
                8 => 'A PHP extension stopped the file upload.'
            ];
            $errorMessage = $errorMessages[$file->getError()] ?? 'Unknown file upload error.';
            $fileError = 'File upload error: ' . $errorMessage;
            log_message('error', 'File upload error: ' . $errorMessage);
        } elseif ($file && $file->getError() === 4) {
            // No file was uploaded - this is normal, not an error
            log_message('error', 'No file uploaded - this is normal for optional file uploads');
            $data['event_banner'] = null; // Allow null for new events (both drafts and published)
        } else {
            // No file uploaded or file is not valid - allow null for drafts
            log_message('error', 'No file uploaded or file is not valid');
            $data['event_banner'] = null; // Allow null for new events (both drafts and published)
        }

        // Check for file errors and return JSON error response
        if (!empty($fileError)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $fileError
            ]);
        }

        // Determine event status based on action and scheduling settings
        log_message('error', 'STORE - STATUS DETERMINATION - Action: ' . $action . ', Scheduling Enabled: ' . ($schedulingEnabled ? 'true' : 'false') . ', Scheduled Datetime: ' . ($data['scheduled_publish_datetime'] ?? 'null'));
        switch ($action) {
            case 'draft':
                $data['status'] = 'Draft';
                log_message('error', 'STORE - STATUS SET TO: Draft');
                break;
            case 'schedule':
                // Auto-enable scheduling for schedule action
                $data['scheduling_enabled'] = 1;
                if (!empty($data['scheduled_publish_datetime'])) {
                    // Additional validation: ensure scheduled datetime is in the future when scheduling
                    $currentTime = new \DateTime();
                    $scheduledTime = new \DateTime($data['scheduled_publish_datetime']);
                    
                    if ($scheduledTime <= $currentTime) {
                        return $this->handleErrorResponse('Scheduled publish date and time must be after the current date and time. Please select a future date and time.');
                    }
                    
                    $data['status'] = 'Scheduled';
                    log_message('error', 'STORE - STATUS SET TO: Scheduled (via schedule action)');
                } else {
                    log_message('error', 'STORE - SCHEDULE ACTION FAILED - Missing scheduled datetime');
                    return $this->handleErrorResponse('Scheduled publish date and time is required when scheduling an event.');
                }
                break;
            case 'publish':
                // When user clicks "Publish Now", always publish immediately regardless of scheduling settings
                $data['status'] = 'Published';
                $data['publish_date'] = date('Y-m-d H:i:s');
                // Clear any scheduled datetime since we're publishing immediately
                $data['scheduled_publish_datetime'] = null;
                $data['scheduling_enabled'] = 0;
                log_message('error', 'STORE - STATUS SET TO: Published (via publish action - immediate publish)');
                break;
            default:
                $data['status'] = 'Published';
                $data['publish_date'] = date('Y-m-d H:i:s');
                log_message('error', 'STORE - STATUS SET TO: Published (default case)');
                break;
        }
        
        try {
            log_message('error', 'Saving event data: ' . json_encode($data));
            $eventModel->save($data);
            $eventId = $eventModel->getInsertID();
            $event = $eventModel->find($eventId);
            
            // Convert event object to array for easier access
            if (is_object($event)) {
                $event = $event->toArray();
            }
            
            log_message('error', 'Event saved successfully. ID: ' . $eventId . ', Event banner: ' . ($event['event_banner'] ?? 'null'));
        } catch (\Exception $e) {
            log_message('error', 'Error saving event: ' . $e->getMessage());
            log_message('error', 'Event data: ' . json_encode($data));
            return $this->handleErrorResponse('Error saving event: ' . $e->getMessage());
        }

        // Handle immediate publishing (not drafts, not scheduled)
        $googleCalendarSync = true; // Initialize to true, will be set to false if sync fails
        if ($data['status'] === 'Published') {
            // Google Calendar sync
            if ($event['barangay_id'] == 0) {
                $calendarId = 'knect.system@gmail.com';
            } else {
                $barangayModel = new \App\Models\BarangayModel();
                $barangay = $barangayModel->find($event['barangay_id']);
                if (is_object($barangay)) {
                    $barangay = $barangay->toArray();
                }
                $calendarId = $barangay ? $barangay['google_calendar_id'] : 'knect.system@gmail.com';
            }

            $startDT = new \DateTime($event['start_datetime'], new \DateTimeZone('Asia/Manila'));
            $endDT = new \DateTime($event['end_datetime'], new \DateTimeZone('Asia/Manila'));
            $startRFC = $startDT->format('c');
            $endRFC = $endDT->format('c');

            $googleEventData = [
                'summary' => $event['title'],
                'description' => $event['description'],
                'location' => $event['location'],
                'start' => ['dateTime' => $startRFC, 'timeZone' => 'Asia/Manila'],
                'end' => ['dateTime' => $endRFC, 'timeZone' => 'Asia/Manila'],
            ];

            log_message('error', '[GCAL SYNC] Attempting to sync event to Google Calendar. CalendarID: ' . $calendarId . ' | EventData: ' . json_encode($googleEventData));
            $googleCalendar = new GoogleCalendarController();
            $googleEventId = $googleCalendar->addEventToGoogleCalendar($calendarId, $googleEventData);

            if ($googleEventId) {
                $eventModel->update($eventId, ['google_event_id' => $googleEventId]);
                log_message('error', '[GCAL SYNC] Success! Google Event ID: ' . $googleEventId);
                $googleCalendarSync = true;
            } else {
                log_message('error', '[GCAL SYNC] FAILED to sync event to Google Calendar. CalendarID: ' . $calendarId . ' | EventData: ' . json_encode($googleEventData));
                $googleCalendarSync = false;
                // Continue with event save even if Google Calendar sync fails
            }
            
            // Send SMS notifications if enabled
            if ($smsNotificationEnabled) {
                $this->sendSmsNotifications($event);
            }
        }

        // Handle scheduled events after saving
        if ($data['status'] === 'Scheduled') {
            // Schedule the event for automatic publishing
            // Merge the saved data with the retrieved event
            $eventForScheduling = array_merge($data, [
                'event_id' => $eventId,
                'title' => $data['title'],
                'barangay_id' => $data['barangay_id'],
                'scheduled_publish_datetime' => $data['scheduled_publish_datetime']
            ]);
            $this->scheduleEventForPublishing($eventForScheduling);
        }

        // Set appropriate success message based on status
        $successMessage = 'Event created successfully.';
        if ($data['status'] === 'Draft') {
            $successMessage = 'Event saved as draft successfully.';
        } elseif ($data['status'] === 'Scheduled') {
            $successMessage = 'Event scheduled successfully. It will be published at the scheduled time.';
        } elseif ($data['status'] === 'Published' && !$googleCalendarSync) {
            $successMessage = 'Event published successfully, but failed to sync with Google Calendar. Please check calendar permissions.';
        }

        // Check if this is an AJAX request
        if ($this->request->isAJAX()) {
            $response = [
                'success' => true,
                'message' => $successMessage,
                'event' => $event,
                'google_calendar_sync' => $googleCalendarSync
            ];
            
            // If Google Calendar sync failed for published events, return as warning
            if ($data['status'] === 'Published' && !$googleCalendarSync) {
                $response['success'] = true; // Still successful (event was saved)
                $response['message'] = 'Event published successfully, but failed to sync with Google Calendar. Please check calendar permissions and network connectivity.';
            }
            
            return $this->response->setJSON($response);
        }

        // Redirect to appropriate page based on event type
        log_message('error', 'Redirecting after event creation. InputBarangayId: ' . $inputBarangayId . ', Status: ' . $data['status']);
        if ($inputBarangayId == 0) {
            log_message('error', 'Redirecting to city-events with message: ' . $successMessage);
            return redirect()->to('/city-events')->with('success', $successMessage);
        }
        log_message('error', 'Redirecting to events with message: ' . $successMessage);
        return redirect()->to('/events')->with('success', $successMessage);
        } catch (\Exception $e) {
            log_message('error', 'STORE METHOD EXCEPTION: ' . $e->getMessage());
            log_message('error', 'EXCEPTION TRACE: ' . $e->getTraceAsString());
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'An error occurred while saving the event: ' . $e->getMessage()
                ]);
            }
            
            return redirect()->back()->withInput()->with('error', 'An error occurred while saving the event: ' . $e->getMessage());
        }
    }

    public function edit($event_id)
    {
        // Access control: Only SK officials and Pederasyon can edit events
        $session = session();
        $userType = $session->get('user_type');
        if ($userType === 'kk') {
            return redirect()->to('/events')->with('error', 'You do not have permission to edit events.');
        }
        
        $eventModel = new EventModel();
        $event = $eventModel->find($event_id);
        $role = session('role');
        $barangayId = session('barangay_id');
        if ($role !== 'super_admin' && $event['barangay_id'] != $barangayId) {
            return redirect()->to('/events')->with('error', 'Unauthorized');
        }

        // Check if event can be edited based on temporal status
        if (!$eventModel->canEventBeEdited($event)) {
            $temporalStatus = $eventModel->getEventTemporalStatus($event);
            if ($temporalStatus === 'completed') {
                return redirect()->to('/events')->with('error', 'This event has already been completed and cannot be edited.');
            }
        }
        
        // Get editable fields for the event
        $editableFields = $eventModel->getEditableFields($event);
        $temporalStatus = $eventModel->getEventTemporalStatus($event);
        
        // Check if this is an AJAX request for modal
        if ($this->request->isAJAX()) {
            return view('K-NECT/events/_form', [
                'event' => $event,
                'editableFields' => $editableFields,
                'temporalStatus' => $temporalStatus
            ]);
        }
        
        $data['event'] = $event;
        $data['editableFields'] = $editableFields;
        $data['temporalStatus'] = $temporalStatus;
        return $this->loadView('K-NECT/events/form', $data);
    }

    public function update($event_id)
    {
        // Access control: Only SK officials and Pederasyon can update events
        $session = session();
        $userType = $session->get('user_type');
        if ($userType === 'kk') {
            return redirect()->to('/events')->with('error', 'You do not have permission to update events.');
        }
        
        log_message('error', 'FILES in update: ' . print_r($_FILES, true));
        log_message('error', 'POST data in update: ' . print_r($_POST, true));
        $eventModel = new EventModel();
        $event = $eventModel->find($event_id);
        $role = session('role');
        $barangayId = session('barangay_id');
        if ($role !== 'super_admin' && $event['barangay_id'] != $barangayId) {
            return redirect()->to('/events')->with('error', 'Unauthorized');
        }

        // Check if event can be edited based on temporal status
        if (!$eventModel->canEventBeEdited($event)) {
            $temporalStatus = $eventModel->getEventTemporalStatus($event);
            if ($temporalStatus === 'completed') {
                return redirect()->to('/events')->with('error', 'This event has already been completed and cannot be edited.');
            }
        }

        // Get editable fields for the event
        $editableFields = $eventModel->getEditableFields($event);
        $temporalStatus = $eventModel->getEventTemporalStatus($event);
        
        $action = $this->request->getPost('submit_action');
        $isDraft = ($action === 'draft');
        
        $data = [
            'title' => $this->request->getPost('title') ?: ($isDraft ? 'Draft Event' : ''),
            'description' => $this->request->getPost('description') ?: '',
            'start_datetime' => $this->request->getPost('start_datetime') ?: '',
            'end_datetime' => $this->request->getPost('end_datetime') ?: '',
            'location' => $this->request->getPost('location') ?: '',
            'category' => $this->request->getPost('category') ?: '',
        ];

        // For ongoing published events, prevent updating start_datetime
        if ($temporalStatus === 'ongoing' && !in_array('start_datetime', $editableFields)) {
            // Remove start_datetime from the update data and use the existing value
            $data['start_datetime'] = $event['start_datetime'];
            
            // Add a warning message about start datetime restriction
            session()->setFlashdata('warning', 'Note: Start date and time cannot be modified for ongoing events.');
        }

        // Handle scheduling options
        $schedulingEnabled = $this->request->getPost('scheduling_enabled') ? 1 : 0;
        $data['scheduling_enabled'] = $schedulingEnabled;
        
        if ($schedulingEnabled) {
            $scheduledDatetime = $this->request->getPost('scheduled_publish_datetime');
            if (empty($scheduledDatetime) && !$isDraft) {
                return $this->handleErrorResponse('Scheduled publish date and time is required when scheduling is enabled.');
            }
            
            // Validate that scheduled datetime is in the future
            if (!empty($scheduledDatetime) && !$isDraft) {
                $currentTime = new \DateTime('now', new \DateTimeZone('Asia/Manila'));
                $scheduledTime = new \DateTime($scheduledDatetime, new \DateTimeZone('Asia/Manila'));
                
                if ($scheduledTime <= $currentTime) {
                    $currentTimeStr = $currentTime->format('Y-m-d H:i');
                    return $this->handleErrorResponse("Scheduled publish date and time must be after the current time ({$currentTimeStr}). Please select a future date and time.");
                }
            }
            
            $data['scheduled_publish_datetime'] = $scheduledDatetime;
        } else {
            $data['scheduled_publish_datetime'] = null;
        }

        // Handle SMS notification options
        $smsNotificationEnabled = $this->request->getPost('sms_notification_enabled') ? 1 : 0;
        $data['sms_notification_enabled'] = $smsNotificationEnabled;
        
        if ($smsNotificationEnabled && !$isDraft) {
            // Handle recipient scope (for superadmin only)
            if ($role === 'super_admin' && $event['barangay_id'] == 0) {
                $recipientScope = $this->request->getPost('sms_recipient_scope');
                $data['sms_recipient_scope'] = $recipientScope;
                
                if ($recipientScope === 'specific_barangays') {
                    $selectedBarangays = $this->request->getPost('sms_recipient_barangays');
                    if (empty($selectedBarangays)) {
                        return $this->handleErrorResponse('Please select at least one barangay for SMS notifications.');
                    }
                    $data['sms_recipient_barangays'] = json_encode($selectedBarangays);
                } else {
                    $data['sms_recipient_barangays'] = null;
                }
            }
            
            // Handle recipient roles
            $recipientRoles = $this->request->getPost('sms_recipient_roles');
            if (empty($recipientRoles)) {
                return $this->handleErrorResponse('Please select at least one recipient role for SMS notifications.');
            }
            
            // Validate that if "All SK Officials" is selected, individual roles are not selected
            if (in_array('all_officials', $recipientRoles)) {
                $individualRoles = ['chairman', 'secretary', 'treasurer'];
                foreach ($individualRoles as $role) {
                    if (in_array($role, $recipientRoles)) {
                        return $this->handleErrorResponse('Cannot select "All SK Officials" and individual SK roles at the same time.');
                    }
                }
            }
            
            $data['sms_recipient_roles'] = json_encode($recipientRoles);
        } elseif ($smsNotificationEnabled && $isDraft) {
            // For drafts, just save the SMS settings without validation
            if ($role === 'super_admin' && $event['barangay_id'] == 0) {
                $recipientScope = $this->request->getPost('sms_recipient_scope');
                $data['sms_recipient_scope'] = $recipientScope;
                
                if ($recipientScope === 'specific_barangays') {
                    $selectedBarangays = $this->request->getPost('sms_recipient_barangays');
                    $data['sms_recipient_barangays'] = json_encode($selectedBarangays ?: []);
                } else {
                    $data['sms_recipient_barangays'] = null;
                }
            }
            
            $recipientRoles = $this->request->getPost('sms_recipient_roles');
            $data['sms_recipient_roles'] = json_encode($recipientRoles ?: []);
        } else {
            $data['sms_recipient_scope'] = null;
            $data['sms_recipient_barangays'] = null;
            $data['sms_recipient_roles'] = null;
        }

        // Handle event_banner upload
        $file = $this->request->getFile('event_banner');
        $fileError = '';
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        log_message('error', 'Update - File upload debug - File object: ' . ($file ? 'exists' : 'null'));
        if ($file) {
            log_message('error', 'Update - File upload debug - isValid: ' . ($file->isValid() ? 'true' : 'false') . ', hasMoved: ' . ($file->hasMoved() ? 'true' : 'false') . ', getError: ' . $file->getError());
            log_message('error', 'Update - File error code: ' . $file->getError());
        }
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = $file->getClientName();
            $fileSize = round($file->getSize() / (1024 * 1024), 2); // Size in MB
            
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                $fileError = "Invalid file type for '{$fileName}'. Allowed formats: JPG, JPEG, PNG, WEBP.";
            } elseif ($file->getSize() > $maxSize) {
                $fileError = "Event banner '{$fileName}' is too large ({$fileSize} MB). Maximum allowed size is 5MB.";
            } else {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/event', $newName); // Save to public/uploads/event
                
                // Delete old banner file if it exists and is different from the new one
                if (!empty($event['event_banner']) && $event['event_banner'] !== $newName) {
                    $oldBannerPath = FCPATH . 'uploads/event/' . $event['event_banner'];
                    if (file_exists($oldBannerPath)) {
                        try {
                            unlink($oldBannerPath);
                            log_message('info', 'Update - Old event banner deleted: ' . $oldBannerPath);
                        } catch (\Exception $e) {
                            log_message('error', 'Update - Failed to delete old event banner: ' . $oldBannerPath . ' - ' . $e->getMessage());
                            // Continue with update even if old banner deletion fails
                        }
                    }
                }
                
                $data['event_banner'] = $newName;
                log_message('error', 'Update - File uploaded successfully: ' . $newName);
            }
        } elseif ($file && $file->getError() > 0 && $file->getError() !== 4) {
            // Handle file upload errors (excluding error code 4 which means no file was uploaded)
            $errorMessages = [
                1 => 'The uploaded file exceeds the maximum file size allowed by the server.',
                2 => 'The uploaded file exceeds the maximum file size allowed by the form.',
                3 => 'The uploaded file was only partially uploaded.',
                6 => 'Missing a temporary folder.',
                7 => 'Failed to write file to disk.',
                8 => 'A PHP extension stopped the file upload.'
            ];
            $errorMessage = $errorMessages[$file->getError()] ?? 'Unknown file upload error.';
            $fileError = 'File upload error: ' . $errorMessage;
            log_message('error', 'File upload error: ' . $errorMessage);
        } elseif ($file && $file->getError() === 4) {
            // No file was uploaded - this is normal, not an error
            log_message('error', 'No file uploaded - this is normal for optional file uploads');
            // If no new file uploaded, keep the old banner
            $data['event_banner'] = $event['event_banner'];
        } else {
            // If no new file uploaded, keep the old banner
            log_message('error', 'Update - No new file uploaded, keeping old banner: ' . ($event['event_banner'] ?? 'null'));
            $data['event_banner'] = $event['event_banner'];
        }

        // Check for file errors and return JSON error response
        if (!empty($fileError)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $fileError
            ]);
        }

        // Determine event status based on action and scheduling settings
        log_message('error', 'UPDATE - STATUS DETERMINATION - Action: ' . $action . ', Scheduling Enabled: ' . ($schedulingEnabled ? 'true' : 'false') . ', Scheduled Datetime: ' . ($data['scheduled_publish_datetime'] ?? 'null'));
        switch ($action) {
            case 'draft':
                $data['status'] = 'Draft';
                log_message('error', 'UPDATE - STATUS SET TO: Draft');
                break;
            case 'schedule':
                // Auto-enable scheduling for schedule action
                $data['scheduling_enabled'] = 1;
                if (!empty($data['scheduled_publish_datetime'])) {
                    // Additional validation: ensure scheduled datetime is in the future when scheduling
                    $currentTime = new \DateTime();
                    $scheduledTime = new \DateTime($data['scheduled_publish_datetime']);
                    
                    if ($scheduledTime <= $currentTime) {
                        return $this->handleErrorResponse('Scheduled publish date and time must be after the current date and time. Please select a future date and time.');
                    }
                    
                    $data['status'] = 'Scheduled';
                    log_message('error', 'UPDATE - STATUS SET TO: Scheduled (via schedule action)');
                } else {
                    log_message('error', 'UPDATE - SCHEDULE ACTION FAILED - Missing scheduled datetime');
                    return $this->handleErrorResponse('Scheduled publish date and time is required when scheduling an event.');
                }
                break;
            case 'publish':
                // When user clicks "Publish Now", always publish immediately regardless of scheduling settings
                $data['status'] = 'Published';
                $data['publish_date'] = date('Y-m-d H:i:s');
                // Clear any scheduled datetime since we're publishing immediately
                $data['scheduled_publish_datetime'] = null;
                $data['scheduling_enabled'] = 0;
                log_message('error', 'UPDATE - STATUS SET TO: Published (via publish action - immediate publish)');
                break;
            default:
                $data['status'] = 'Published';
                $data['publish_date'] = date('Y-m-d H:i:s');
                log_message('error', 'UPDATE - STATUS SET TO: Published (default case)');
                break;
        }
        
        // Now update the event with all data
        try {
            $eventModel->update($event_id, $data);
            $updatedEvent = $eventModel->find($event_id);
        } catch (\Exception $e) {
            log_message('error', 'Error updating event: ' . $e->getMessage());
            log_message('error', 'Event data: ' . json_encode($data));
            return $this->handleErrorResponse('Error updating event: ' . $e->getMessage());
        }

        // Handle scheduled events after updating
        if ($updatedEvent['status'] === 'Scheduled') {
            // Schedule the event for automatic publishing
            $this->scheduleEventForPublishing($updatedEvent);
        }

        // Handle immediate publishing (not drafts, not scheduled)
        $googleCalendarSync = true; // Initialize to true, will be set to false if sync fails
        if ($updatedEvent['status'] === 'Published') {
            // Google Calendar sync
            if ($updatedEvent['barangay_id'] == 0) {
                $calendarId = 'knect.system@gmail.com';
            } else {
                $barangayModel = new \App\Models\BarangayModel();
                $barangay = $barangayModel->find($updatedEvent['barangay_id']);
                if (is_object($barangay)) {
                    $barangay = $barangay->toArray();
                }
                $calendarId = $barangay ? $barangay['google_calendar_id'] : 'knect.system@gmail.com';
            }

            $startDT = new \DateTime($updatedEvent['start_datetime'], new \DateTimeZone('Asia/Manila'));
            $endDT = new \DateTime($updatedEvent['end_datetime'], new \DateTimeZone('Asia/Manila'));
            $startRFC = $startDT->format('c');
            $endRFC = $endDT->format('c');

            $googleEventData = [
                'summary' => $updatedEvent['title'],
                'description' => $updatedEvent['description'],
                'location' => $updatedEvent['location'],
                'start' => ['dateTime' => $startRFC, 'timeZone' => 'Asia/Manila'],
                'end' => ['dateTime' => $endRFC, 'timeZone' => 'Asia/Manila'],
            ];

            log_message('error', '[GCAL SYNC] Attempting to update event in Google Calendar. CalendarID: ' . $calendarId . ' | GoogleEventID: ' . ($updatedEvent['google_event_id'] ?? 'NULL') . ' | EventData: ' . json_encode($googleEventData));
            $googleCalendar = new GoogleCalendarController();
            if (!empty($updatedEvent['google_event_id'])) {
                $updateSuccess = $googleCalendar->updateGoogleCalendarEvent($calendarId, $updatedEvent['google_event_id'], $googleEventData);
                if ($updateSuccess) {
                    log_message('error', '[GCAL SYNC] Successfully updated event in Google Calendar. Google Event ID: ' . $updatedEvent['google_event_id']);
                    $googleCalendarSync = true;
                } else {
                    log_message('error', '[GCAL SYNC] FAILED to update event in Google Calendar. CalendarID: ' . $calendarId . ' | GoogleEventID: ' . $updatedEvent['google_event_id'] . ' | EventData: ' . json_encode($googleEventData));
                    $googleCalendarSync = false;
                    // Continue with event update even if Google Calendar sync fails
                }
            } else {
                log_message('error', '[GCAL SYNC] No Google Event ID found for event ' . $event_id . '. Attempting to create new Google Calendar event.');
                $googleEventId = $googleCalendar->addEventToGoogleCalendar($calendarId, $googleEventData);
                if ($googleEventId) {
                    $eventModel->update($event_id, ['google_event_id' => $googleEventId]);
                    log_message('error', '[GCAL SYNC] Success! Google Event ID: ' . $googleEventId);
                    $googleCalendarSync = true;
                } else {
                    log_message('error', '[GCAL SYNC] FAILED to sync event to Google Calendar. CalendarID: ' . $calendarId . ' | EventData: ' . json_encode($googleEventData));
                    $googleCalendarSync = false;
                    // Continue with event update even if Google Calendar sync fails
                }
            }
            
            // Send SMS notifications if enabled
            if ($smsNotificationEnabled) {
                $this->sendSmsNotifications($updatedEvent);
            }
        }

        // Set appropriate success message based on status
        $successMessage = 'Event updated successfully.';
        if ($updatedEvent['status'] === 'Draft') {
            $successMessage = 'Event saved as draft successfully.';
        } elseif ($updatedEvent['status'] === 'Scheduled') {
            $successMessage = 'Event scheduled successfully. It will be published at the scheduled time.';
        } elseif ($updatedEvent['status'] === 'Published' && !$googleCalendarSync) {
            $successMessage = 'Event updated successfully, but failed to sync with Google Calendar. Please check calendar permissions.';
        }

        // Check if this is an AJAX request
        if ($this->request->isAJAX()) {
            $response = [
                'success' => true,
                'message' => $successMessage,
                'event' => $updatedEvent,
                'google_calendar_sync' => $googleCalendarSync
            ];
            
            // If Google Calendar sync failed for published events, return as warning
            if ($updatedEvent['status'] === 'Published' && !$googleCalendarSync) {
                $response['success'] = true; // Still successful (event was saved)
                $response['message'] = 'Event updated successfully, but failed to sync with Google Calendar. Please check calendar permissions and network connectivity.';
            }
            
            return $this->response->setJSON($response);
        }

        // Redirect to appropriate page based on event type
        if ($updatedEvent['barangay_id'] == 0) {
            return redirect()->to('/city-events')->with('success', $successMessage);
        }
        return redirect()->to('/events')->with('success', $successMessage);
    }

    public function delete($event_id)
    {
        // Access control: Only SK officials and Pederasyon can delete events
        $session = session();
        $userType = $session->get('user_type');
        if ($userType === 'kk') {
            // Check if this is an AJAX request
            $isAjax = $this->request->isAJAX();
            if ($isAjax) {
                return $this->response->setJSON(['success' => false, 'message' => 'You do not have permission to delete events.']);
            }
            return redirect()->to('/events')->with('error', 'You do not have permission to delete events.');
        }
        
        log_message('error', 'Delete method called with event_id: ' . $event_id);
        
        // Check if this is an AJAX request
        $isAjax = $this->request->isAJAX();
        
        // Validate event_id
        if (!$event_id || $event_id <= 0) {
            log_message('error', 'Invalid event_id provided: ' . $event_id);
            if ($isAjax) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid event ID provided']);
            }
            return redirect()->to('/events')->with('error', 'Invalid event ID provided');
        }
        
        $eventModel = new EventModel();
        $event = $eventModel->find($event_id);
        
        if (!$event) {
            log_message('error', 'Event not found with ID: ' . $event_id);
            if ($isAjax) {
                return $this->response->setJSON(['success' => false, 'message' => 'Event not found']);
            }
            return redirect()->to('/events')->with('error', 'Event not found');
        }
        
        $role = session('role');
        $barangayId = session('barangay_id');
        
        // Authorization check
        if ($role !== 'super_admin' && $event['barangay_id'] != $barangayId) {
            log_message('error', 'Unauthorized delete attempt - Role: ' . $role . ', Event barangay: ' . $event['barangay_id'] . ', User barangay: ' . $barangayId);
            if ($isAjax) {
                return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
            }
            return redirect()->to('/events')->with('error', 'Unauthorized');
        }
        
        log_message('info', 'Deleting event: ' . $event['title'] . ' (ID: ' . $event_id . ')');
        
        // Google Calendar sync
        try {
            if ($event['barangay_id'] == 0) {
                $calendarId = 'knect.system@gmail.com';
            } else {
                $barangayModel = new \App\Models\BarangayModel();
                $barangay = $barangayModel->find($event['barangay_id']);
                if (is_object($barangay)) {
                    $barangay = $barangay->toArray();
                }
                $calendarId = $barangay ? $barangay['google_calendar_id'] : 'knect.system@gmail.com';
            }
            $googleCalendar = new GoogleCalendarController();
            if (!empty($event['google_event_id'])) {
                $googleCalendar->deleteGoogleCalendarEvent($calendarId, $event['google_event_id']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Google Calendar delete error: ' . $e->getMessage());
            // Continue with local delete even if Google Calendar fails
        }
        
        // Delete event banner file if it exists
        if (!empty($event['event_banner'])) {
            $bannerPath = FCPATH . 'uploads/event/' . $event['event_banner'];
            if (file_exists($bannerPath)) {
                try {
                    unlink($bannerPath);
                    log_message('info', 'Event banner deleted: ' . $bannerPath);
                } catch (\Exception $e) {
                    log_message('error', 'Failed to delete event banner: ' . $bannerPath . ' - ' . $e->getMessage());
                    // Continue with deletion even if banner file deletion fails
                }
            } else {
                log_message('info', 'Event banner file not found: ' . $bannerPath);
            }
        }
        
        // Delete the event from database
        try {
            $eventModel->delete($event_id);
            log_message('info', 'Event deleted successfully: ' . $event['title']);
        } catch (\Exception $e) {
            log_message('error', 'Database delete error: ' . $e->getMessage());
            if ($isAjax) {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete event']);
            }
            return redirect()->to('/events')->with('error', 'Failed to delete event');
        }
        
        // Send SMS notifications
        try {
            if ($event['barangay_id'] == 0) {
                $startDate = (new \DateTime($event['start_datetime']))->format('F d, Y');
                $sms = "SK Pederasyon - Iriga City\n\nEVENT CANCELLED\n\nWe regret to inform you that the {$event['title']} on $startDate has been cancelled.";
                $this->notifyAllUsers($sms);
            } else {
                // Barangay-specific SMS
                $barangayModel = new \App\Models\BarangayModel();
                $barangay = $barangayModel->find($event['barangay_id']);
                $barangayName = $barangay['name'] ?? 'Barangay';
                $startDate = (new \DateTime($event['start_datetime']))->format('F d, Y');
                $sms = "SK $barangayName\n\nEVENT CANCELLED\n\nWe regret to inform you that the {$event['title']} on $startDate has been cancelled.";
                $this->notifyAllUsers($sms, $event['barangay_id']);
            }
        } catch (\Exception $e) {
            log_message('error', 'SMS notification error: ' . $e->getMessage());
            // Don't fail the delete if SMS fails
        }
        
        // Return response based on request type
        if ($isAjax) {
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Event deleted successfully',
                'redirect_url' => $event['barangay_id'] == 0 ? '/city-events' : '/events'
            ]);
        }
        
        // Redirect to appropriate page
        if ($event['barangay_id'] == 0) {
            return redirect()->to('/city-events')->with('success', 'Event deleted successfully');
        }
        return redirect()->to('/events')->with('success', 'Event deleted successfully');
    }

    public function calendar()
    {
        if (!session('user_id')) {
            return redirect()->to('/login');
        }
        $user = session();
        $role = $user->get('role');
        $barangayId = $user->get('barangay_id');
        $barangayModel = new BarangayModel();
        $tabs = [];
        $FEDERATION_CALENDAR_ID = 'knect.system@gmail.com';

        if ($role === 'super_admin') {
            $barangays = $barangayModel->findAll();
            foreach ($barangays as $barangay) {
                $tabs[] = [
                    'label' => $barangay['name'],
                    'calendar_id' => $barangay['google_calendar_id'],
                ];
            }
            $tabs[] = [
                'label' => 'City-wide',
                'calendar_id' => $FEDERATION_CALENDAR_ID,
            ];
        } else if ($role === 'admin' || $role === 'user') {
            $barangay = $barangayModel->find($barangayId);
            if ($barangay) {
                $tabs[] = [
                    'label' => $barangay['name'],
                    'calendar_id' => $barangay['google_calendar_id'],
                ];
            }
            $tabs[] = [
                'label' => 'City-wide',
                'calendar_id' => $FEDERATION_CALENDAR_ID,
            ];
        }
    return view('K-NECT/events/calendar_tabs', ['tabs' => $tabs, 'role' => $role]);
    }

    public function getEventsJson()
    {
        $eventModel = new \App\Models\EventModel();
        $events = $eventModel->orderBy('created_at', 'DESC')->findAll();
        // Format for FullCalendar
        $calendarEvents = array_map(function($event) {
            return [
                'event_id' => $event['event_id'],
                'title' => $event['title'],
                'start' => $event['start_datetime'],
                'end' => $event['end_datetime'],
                'description' => $event['description'],
                'location' => $event['location'],
            ];
        }, $events);
        return $this->response->setJSON($calendarEvents);
    }

    public function ajax_add()
    {
        if ($this->request->getMethod() !== 'post' || !$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Invalid request']);
        }
        $data = $this->request->getJSON(true);
        $eventModel = new \App\Models\EventModel();
        $saveData = [
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            'location' => $data['location'] ?? '',
            'start_datetime' => $data['start_datetime'] ?? '',
            'end_datetime' => $data['end_datetime'] ?? '',
            'created_by' => session('user_id') ?: 1,
        ];
        $result = $eventModel->save($saveData);
        return $this->response->setJSON(['success' => (bool)$result]);
    }

    private function notifyAllUsers($message, $barangayId = null) {
        $userModel = new UserModel();
        if ($barangayId === null) {
            $users = $userModel->findAll();
        } else {
            $users = $userModel->where('barangay_id', $barangayId)->findAll();
        }
        foreach ($users as $user) {
            if (!empty($user['phone_number'])) {
                send_sms($user['phone_number'], $message);
            }
        }
    }

    private function sendSmsNotifications($event) {
        $userModel = new UserModel();
        $recipients = $this->getSmsRecipients($event, $userModel);
        
        if (empty($recipients)) {
            log_message('info', 'No SMS recipients found for event: ' . $event['title']);
            return;
        }
        
        $message = $this->formatSmsMessage($event);
        
        foreach ($recipients as $recipient) {
            if (!empty($recipient['phone_number'])) {
                try {
                    send_sms($recipient['phone_number'], $message);
                    log_message('info', "SMS sent to: {$recipient['phone_number']}");
                } catch (\Exception $e) {
                    log_message('error', "Failed to send SMS to {$recipient['phone_number']}: " . $e->getMessage());
                }
            }
        }
    }
    
    private function getSmsRecipients($event, $userModel) {
        $recipients = [];
        $recipientRoles = json_decode($event['sms_recipient_roles'], true) ?? [];
        
        // Build query based on recipient scope and roles
        $query = $userModel->select('user.*, barangay.name as barangay_name')
                          ->join('barangay', 'barangay.barangay_id = user.barangay_id', 'left');
        
        // Handle recipient scope for superadmin
        if ($event['barangay_id'] == 0 && $event['sms_recipient_scope']) {
            if ($event['sms_recipient_scope'] === 'specific_barangays') {
                $selectedBarangays = json_decode($event['sms_recipient_barangays'], true) ?? [];
                if (!empty($selectedBarangays)) {
                    $query->whereIn('user.barangay_id', $selectedBarangays);
                }
            }
            // For 'all_barangays', no additional filter needed
        } else {
            // For regular events, only include users from the same barangay
            $query->where('user.barangay_id', $event['barangay_id']);
        }
        
        // Filter by roles
        if (!empty($recipientRoles)) {
            $roleConditions = [];
            foreach ($recipientRoles as $role) {
                switch ($role) {
                    case 'all_pederasyon_officials':
                        $roleConditions[] = "user.position LIKE '%Pederasyon%'";
                        break;
                    case 'pederasyon_officers':
                        $roleConditions[] = "(user.position LIKE '%Pederasyon President%' OR user.position LIKE '%Pederasyon Vice President%' OR user.position LIKE '%Pederasyon Secretary%' OR user.position LIKE '%Pederasyon Treasurer%' OR user.position LIKE '%Pederasyon Auditor%' OR user.position LIKE '%Pederasyon Public Information Officer%' OR user.position LIKE '%Pederasyon Sergeant at Arms%')";
                        break;
                    case 'pederasyon_members':
                        $roleConditions[] = "(user.position LIKE '%Pederasyon%' AND user.position NOT LIKE '%Pederasyon President%' AND user.position NOT LIKE '%Pederasyon Vice President%' AND user.position NOT LIKE '%Pederasyon Secretary%' AND user.position NOT LIKE '%Pederasyon Treasurer%' AND user.position NOT LIKE '%Pederasyon Auditor%' AND user.position NOT LIKE '%Pederasyon Public Information Officer%' AND user.position NOT LIKE '%Pederasyon Sergeant at Arms%')";
                        break;
                    case 'all_officials':
                        $roleConditions[] = "user.position LIKE '%SK%'";
                        break;
                    case 'chairperson':
                        $roleConditions[] = "user.position LIKE '%Chairperson%'";
                        break;
                    case 'secretary':
                        $roleConditions[] = "user.position LIKE '%Secretary%'";
                        break;
                    case 'treasurer':
                        $roleConditions[] = "user.position LIKE '%Treasurer%'";
                        break;
                }
            }
            if (!empty($roleConditions)) {
                $query->where('(' . implode(' OR ', $roleConditions) . ')');
            }
        }
        
        return $query->findAll();
    }
    
    private function formatSmsMessage($event) {
        $startDate = (new \DateTime($event['start_datetime']))->format('F d, Y');
        $startTime = (new \DateTime($event['start_datetime']))->format('h:i A');
        $endTime = (new \DateTime($event['end_datetime']))->format('h:i A');
        
        $message = "NEW EVENT: {$event['title']}\n";
        $message .= "Date: {$startDate}\n";
        $message .= "Time: {$startTime} - {$endTime}\n";
        $message .= "Location: {$event['location']}\n";
        $message .= "Description: {$event['description']}";
        
        return $message;
    }

    private function getUserModel() {
        return new UserModel();
    }

    private function handleErrorResponse($message, $redirectUrl = null) {
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $message
            ]);
        }
        
        if ($redirectUrl) {
            return redirect()->to($redirectUrl)->withInput()->with('error', $message);
        }
        
        return redirect()->back()->withInput()->with('error', $message);
    }
    
    /**
     * Schedule event for automatic publishing via Firebase Functions
     */
    private function scheduleEventForPublishing($eventData) {
        try {
            // Ensure all required fields are present
            if (!isset($eventData['barangay_id']) || !isset($eventData['title']) || !isset($eventData['scheduled_publish_datetime'])) {
                log_message('error', 'SCHEDULE EVENT - Missing required fields in eventData: ' . json_encode($eventData));
                return false;
            }
            
            // Firebase Functions URL (you'll need to replace this with your actual Firebase Functions URL)
            $firebaseFunctionUrl = 'https://your-project-id.cloudfunctions.net/checkScheduledEvents';
            
            // Prepare the event data for Firebase
            $firebaseData = [
                'event_id' => $eventData['event_id'] ?? null,
                'title' => $eventData['title'],
                'scheduled_publish_datetime' => $eventData['scheduled_publish_datetime'],
                'barangay_id' => $eventData['barangay_id'],
                'status' => 'Scheduled'
            ];
            
            // Make HTTP request to Firebase Function using CodeIgniter HTTP Client
            $client = \Config\Services::curlrequest();
            
            $response = $client->post($firebaseFunctionUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $firebaseData,
                'timeout' => 30
            ]);
            
            $httpCode = $response->getStatusCode();
            $responseBody = $response->getBody();
            
            if ($httpCode === 200) {
                log_message('info', 'Event scheduled for publishing via Firebase Functions: ' . $eventData['title']);
            } else {
                log_message('error', 'Failed to schedule event via Firebase Functions. HTTP Code: ' . $httpCode . ', Response: ' . $responseBody);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error scheduling event via Firebase Functions: ' . $e->getMessage());
        }
    }
    
    /**
     * Manually trigger event publishing (for testing)
     */
    public function manualPublishEvent($eventId) {
        try {
            $eventModel = new EventModel();
            $event = $eventModel->find($eventId);
            
            if (!$event) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Event not found'
                ]);
            }
            
            // Firebase Functions URL for manual publishing
            $firebaseFunctionUrl = 'https://your-project-id.cloudfunctions.net/manualPublishEvent';
            
            $firebaseData = [
                'eventId' => $eventId
            ];
            
            // Make HTTP request to Firebase Function using CodeIgniter HTTP Client
            $client = \Config\Services::curlrequest();
            
            $response = $client->post($firebaseFunctionUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $firebaseData,
                'timeout' => 30
            ]);
            
            $httpCode = $response->getStatusCode();
            $responseBody = $response->getBody();
            
            if ($httpCode === 200) {
                $result = json_decode($responseBody, true);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => $result['message'] ?? 'Event published successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to publish event via Firebase Functions'
                ]);
            }
    } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Bulk delete events
     */
    public function bulkDelete() {
        try {
            log_message('info', 'Bulk delete request received');
            
            // Access control: Only SK officials and Pederasyon can bulk delete events
            $session = session();
            $userType = $session->get('user_type');
            if ($userType === 'kk') {
                log_message('warning', 'Unauthorized bulk delete attempt by KK user');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'You do not have permission to delete events.'
                ]);
            }
            
            // Check if user is authorized (super admin or admin)
            $role = session('role');
            log_message('info', 'User role: ' . $role);
            
            if ($role !== 'super_admin' && $role !== 'admin') {
                log_message('warning', 'Unauthorized bulk delete attempt by user with role: ' . $role);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Unauthorized: Only super admin and admin can delete events'
                ]);
            }
            
            // Get the JSON input
            $jsonInput = $this->request->getJSON();
            log_message('info', 'JSON input received: ' . json_encode($jsonInput));
            
            $eventIds = $jsonInput->event_ids ?? [];
            log_message('info', 'Event IDs to delete: ' . json_encode($eventIds));
            
            if (empty($eventIds)) {
                log_message('warning', 'No event IDs provided for bulk delete');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No events selected for deletion'
                ]);
            }
            
            $eventModel = new EventModel();
            $googleCalendarController = new GoogleCalendarController();
            $deletedCount = 0;
            $errors = [];
            
            foreach ($eventIds as $eventId) {
                try {
                    // Get event details before deletion
                    $event = $eventModel->find($eventId);
                    
                    if (!$event) {
                        $errors[] = "Event ID {$eventId} not found";
                        continue;
                    }
                    
                    // Check if user has permission to delete this event
                    $userBarangayId = session('barangay_id');
                    $userRole = session('role');
                    
                    // Super admin can delete any event, regular admin can only delete their barangay's events
                    if ($userRole !== 'super_admin' && $event['barangay_id'] != $userBarangayId) {
                        $errors[] = "Event '{$event['title']}' is not from your barangay";
                        continue;
                    }
                    
                    // Delete from Google Calendar if it has a Google Event ID
                    if (!empty($event['google_event_id'])) {
                        // Get the appropriate calendar ID based on barangay
                        $barangayModel = new \App\Models\BarangayModel();
                        $barangay = $barangayModel->find($event['barangay_id']);
                        if (is_object($barangay)) {
                            $barangay = $barangay->toArray();
                        }
                        $calendarId = $barangay && !empty($barangay['google_calendar_id']) 
                            ? $barangay['google_calendar_id'] 
                            : 'knect.system@gmail.com'; // Default to city-wide calendar
                        
                        $googleCalendarController->deleteGoogleCalendarEvent($calendarId, $event['google_event_id']);
                        log_message('info', "Deleted Google Calendar event: {$event['google_event_id']} for event: {$event['title']} from calendar: {$calendarId}");
                    }
                    
                    // Delete event banner file if it exists
                    if (!empty($event['event_banner'])) {
                        $bannerPath = FCPATH . 'uploads/event/' . $event['event_banner'];
                        if (file_exists($bannerPath)) {
                            try {
                                unlink($bannerPath);
                                log_message('info', "Bulk delete - Event banner deleted: {$bannerPath}");
                            } catch (\Exception $e) {
                                log_message('error', "Bulk delete - Failed to delete event banner: {$bannerPath} - " . $e->getMessage());
                                // Continue with deletion even if banner file deletion fails
                            }
                        } else {
                            log_message('info', "Bulk delete - Event banner file not found: {$bannerPath}");
                        }
                    }
                    
                    // Delete the event from database
                    $eventModel->delete($eventId);
                    $deletedCount++;
                    
                    log_message('info', "Bulk deleted event: {$event['title']} (ID: {$eventId})");
                    
                } catch (\Exception $e) {
                    $errors[] = "Failed to delete event ID {$eventId}: " . $e->getMessage();
                    log_message('error', "Bulk delete error for event ID {$eventId}: " . $e->getMessage());
                }
            }
            
            if ($deletedCount > 0) {
                $message = "Successfully deleted {$deletedCount} event(s)";
                if (!empty($errors)) {
                    $message .= ". Errors: " . implode(', ', $errors);
                }
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => $message,
                    'deleted_count' => $deletedCount,
                    'errors' => $errors
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No events were deleted. Errors: ' . implode(', ', $errors)
                ]);
            }
            
    } catch (\Exception $e) {
            log_message('error', 'Bulk delete error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Bulk delete failed: ' . $e->getMessage()
            ]);
        }
    }
} 