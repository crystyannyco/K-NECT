<?php
$isCityWide = false;
if (isset($event) && isset($event['barangay_id'])) {
    $isCityWide = (int)$event['barangay_id'] === 0;
} elseif (isset($_POST['barangay_id']) && (int)$_POST['barangay_id'] === 0) {
    $isCityWide = true;
} elseif (isset($_GET['barangay_id']) && (int)$_GET['barangay_id'] === 0) {
    $isCityWide = true;
}

// Check temporal status for existing events
$temporalStatus = null;
$isStartDateDisabled = false;
$startDateDisabledMessage = '';

if (isset($event) && $event['status'] === 'Published') {
    $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Manila'));
    $startDateTime = new DateTime($event['start_datetime'], new DateTimeZone('Asia/Manila'));
    $endDateTime = new DateTime($event['end_datetime'], new DateTimeZone('Asia/Manila'));
    
    if ($currentDateTime < $startDateTime) {
        $temporalStatus = 'upcoming';
    } elseif ($currentDateTime >= $startDateTime && $currentDateTime <= $endDateTime) {
        $temporalStatus = 'ongoing';
        $isStartDateDisabled = true;
        $startDateDisabledMessage = 'Start date and time cannot be modified for ongoing events.';
    } else {
        $temporalStatus = 'completed';
    }
}

// Get user role for SMS notification options
$userRole = session('role');
$isSuperAdmin = $userRole === 'super_admin';
?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-blue-700"><?= isset($event) ? 'Edit Event' : 'Create Event' ?></h2>
    <button onclick="closeEventModal()" class="text-gray-400 hover:text-gray-700 text-3xl font-bold">
        <i class="fas fa-times"></i>
    </button>
</div>

<form id="eventForm" method="post" enctype="multipart/form-data" action="<?= isset($event) ? '/events/update/' . $event['event_id'] : '/events/store' ?>" class="space-y-6">
    <?php if (!isset($event) && $isCityWide): ?>
        <input type="hidden" name="barangay_id" value="0">
    <?php endif; ?>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
            <input 
                type="text" 
                id="title" 
                name="title" 
                value="<?= isset($event) ? esc($event['title']) : '' ?>" 
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                placeholder="Enter event title"
            >
            <p id="title-error" class="text-red-500 text-xs sm:text-sm mt-1" style="display: none;"></p>
        </div>

        <div>
            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
            <select id="category" name="category" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                <option value="">Select category</option>
                <?php
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
                $selectedCategory = $event['category'] ?? '';
                foreach ($categories as $cat): ?>
                    <option value="<?= $cat ?>" <?= $selectedCategory === $cat ? 'selected' : '' ?>><?= ucfirst($cat) ?></option>
                <?php endforeach; ?>
            </select>
            <p id="category-error" class="text-red-500 text-xs sm:text-sm mt-1" style="display: none;"></p>
        </div>
    </div>

    <div class="form-group mb-4">
        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location <span class="text-red-500">*</span></label>
        <input 
            type="text" 
            id="location" 
            name="location" 
            value="<?= isset($event) ? esc($event['location']) : '' ?>"
            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
            placeholder="Enter event location"
        >
        <p id="location-error" class="text-red-500 text-xs sm:text-sm mt-1" style="display: none;"></p>
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
        <textarea 
            id="description" 
            name="description" 
            rows="4"
            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent resize-vertical"
            placeholder="Enter event description"
        ><?= isset($event) ? esc($event['description']) : '' ?></textarea>
        <p id="description-error" class="text-red-500 text-xs sm:text-sm mt-1" style="display: none;"></p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="start_datetime" class="block text-sm font-medium text-gray-700 mb-2">Start Date & Time <span class="text-red-500">*</span></label>
            <input 
                type="datetime-local" 
                id="start_datetime" 
                name="start_datetime" 
                value="<?= isset($event) ? date('Y-m-d\TH:i', strtotime($event['start_datetime'])) : '' ?>" 
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent <?= $isStartDateDisabled ? 'bg-gray-100 cursor-not-allowed' : '' ?>"
                <?= $isStartDateDisabled ? 'disabled readonly' : '' ?>
            >
            <?php if ($isStartDateDisabled): ?>
                <p class="text-amber-600 text-xs sm:text-sm mt-1 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <?= $startDateDisabledMessage ?>
                </p>
            <?php endif; ?>
            <p id="start_datetime-error" class="text-red-500 text-xs sm:text-sm mt-1" style="display: none;"></p>
        </div>

        <div>
            <label for="end_datetime" class="block text-sm font-medium text-gray-700 mb-2">End Date & Time <span class="text-red-500">*</span></label>
            <input 
                type="datetime-local" 
                id="end_datetime" 
                name="end_datetime" 
                value="<?= isset($event) ? date('Y-m-d\TH:i', strtotime($event['end_datetime'])) : '' ?>" 
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
            >
            <p id="end_datetime-error" class="text-red-500 text-xs sm:text-sm mt-1" style="display: none;"></p>
        </div>
    </div>

    <!-- Event Banner Section (profiling-like UI) -->
    <div class="form-group mb-4">
        <label for="event_banner" class="block text-sm font-medium text-gray-700 mb-2">Event Banner</label>

        <?php
            $event_banner_file = isset($event['event_banner']) ? $event['event_banner'] : (old('event_banner') ?? '');
            $has_event_banner_error = isset(session('file_errors')['event_banner']) && session('file_errors')['event_banner'];
        ?>

        <style>
        /* Field validation styles */
        @keyframes shake {
            0%, 20%, 50%, 80%, 100% {
                transform: translateX(0);
            }
            10%, 30%, 70%, 90% {
                transform: translateX(-5px);
            }
            40%, 60% {
                transform: translateX(5px);
            }
        }
        
        .shake {
            animation: shake 0.5s ease-in-out;
        }
        
        .field-error {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }
        
        /* File upload styles */
        .file-upload-container {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        
        .file-upload-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-upload-button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 32px 16px;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            background-color: #f9fafb;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            min-height: 120px;
        }
        
        .file-upload-button:hover {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
        
        .file-upload-button.dragover {
            border-color: #3b82f6;
            background-color: #dbeafe;
            transform: scale(1.02);
        }
        
        .file-upload-button.has-file {
            border-color: #10b981;
            background-color: #ecfdf5;
        }
        
        .file-upload-button.error {
            border-color: #ef4444;
            background-color: #fef2f2;
        }
        
        .file-upload-text {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        </style>

        <div class="file-upload-container" data-has-existing-file="<?= ($event_banner_file && !$has_event_banner_error) ? 'true' : 'false' ?>">
            <input type="file" id="event_banner" name="event_banner" accept="image/jpeg,image/jpg,image/png,image/webp" class="file-upload-input" />

            <div class="file-upload-button <?= $event_banner_file && !$has_event_banner_error ? 'has-file' : '' ?> <?= $has_event_banner_error ? 'error' : '' ?>" id="event_banner_button">
                <div class="file-upload-text">
                    <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700" id="event_banner_text">
                        <?php if ($event_banner_file && !$has_event_banner_error): ?>
                            <strong>New file selected:</strong><br>
                            <span class="text-green-600"><?= esc($event_banner_file) ?></span><br>
                            <span class="text-xs text-blue-500">Ready to upload</span>
                        <?php elseif ($has_event_banner_error): ?>
                            <strong>Photo needs to be re-uploaded</strong><br>
                            <span class="text-red-500">Previous photo had errors</span>
                        <?php else: ?>
                            Click to upload or drag and drop<br>
                            <span class="text-xs text-gray-500">JPG, PNG, WEBP up to 5MB</span>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>

        <?php if ($has_event_banner_error): ?>
            <p class="text-red-500 text-xs sm:text-sm mt-1" id="file-error">
                <?= session('file_errors')['event_banner'] ?>
            </p>
        <?php else: ?>
            <p id="file-error" class="text-red-500 text-xs sm:text-sm mt-1" style="display: none;"></p>
        <?php endif; ?>
    </div>

    <!-- Scheduling Options Section -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Scheduling options</h3>
            <div class="flex items-center">
                <span class="text-sm text-gray-600 mr-2">Set date and time</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="scheduling_enabled" name="scheduling_enabled" value="1" 
                           <?= (isset($event['scheduling_enabled']) && $event['scheduling_enabled']) ? 'checked' : '' ?>
                           class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
        </div>
        
        <div id="scheduling_datetime_group" class="<?= (isset($event['scheduling_enabled']) && $event['scheduling_enabled']) ? '' : 'hidden' ?>">
            <label for="scheduled_publish_datetime" class="block text-sm font-medium text-gray-700 mb-2">Scheduled Publish Date & Time</label>
            <input 
                type="datetime-local" 
                id="scheduled_publish_datetime" 
                name="scheduled_publish_datetime" 
                value="<?= isset($event['scheduled_publish_datetime']) ? date('Y-m-d\TH:i', strtotime($event['scheduled_publish_datetime'])) : '' ?>" 
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
            >
        </div>
    </div>

    <!-- SMS Notification Settings Section -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">SMS notification settings</h3>
            <div class="flex items-center">
                <span class="text-sm text-gray-600 mr-2">Enable SMS notifications</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="sms_notification_enabled" name="sms_notification_enabled" value="1" 
                           <?= (isset($event['sms_notification_enabled']) && $event['sms_notification_enabled']) ? 'checked' : '' ?>
                           class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
        </div>
        
        <div id="sms_recipient_group" class="<?= (isset($event['sms_notification_enabled']) && $event['sms_notification_enabled']) ? '' : 'hidden' ?>">
            <p class="text-sm text-gray-600 mb-4">Adjust your SMS notification settings to control who will receive SMS notifications about this event.</p>
            
            <?php if ($isSuperAdmin): ?>
            <!-- Pederasyon Officials (City Level) -->
            <div id="city_level_officials_group" class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">City-Level Officials</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="sms_recipient_roles[]" value="all_pederasyon_officials" 
                                   <?= (isset($event['sms_recipient_roles']) && in_array('all_pederasyon_officials', json_decode($event['sms_recipient_roles'], true) ?? [])) ? 'checked' : '' ?>
                                   class="mr-2 all-pederasyon-officials-checkbox">
                            <span class="text-sm text-gray-700">All Pederasyon Officials</span>
                        </label>
                        <div class="pederasyon-roles-group ml-4 space-y-1">
                            <label class="flex items-center">
                                <input type="checkbox" name="sms_recipient_roles[]" value="pederasyon_officers" 
                                       <?= (isset($event['sms_recipient_roles']) && in_array('pederasyon_officers', json_decode($event['sms_recipient_roles'], true) ?? [])) ? 'checked' : '' ?>
                                       class="mr-2 pederasyon-role-checkbox">
                                <span class="text-sm text-gray-700">Pederasyon Officers</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="sms_recipient_roles[]" value="pederasyon_members" 
                                       <?= (isset($event['sms_recipient_roles']) && in_array('pederasyon_members', json_decode($event['sms_recipient_roles'], true) ?? [])) ? 'checked' : '' ?>
                                       class="mr-2 pederasyon-role-checkbox">
                                <span class="text-sm text-gray-700">Pederasyon Members</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Superadmin SMS Recipient Options -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recipient Scope <span class="text-red-500">*</span></label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="sms_recipient_scope" value="all_barangays" 
                                   <?= (isset($event['sms_recipient_scope']) && $event['sms_recipient_scope'] === 'all_barangays') ? 'checked' : '' ?>
                                   class="mr-2">
                            <span class="text-sm text-gray-700">All barangays</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="sms_recipient_scope" value="specific_barangays" 
                                   <?= (isset($event['sms_recipient_scope']) && $event['sms_recipient_scope'] === 'specific_barangays') ? 'checked' : '' ?>
                                   class="mr-2">
                            <span class="text-sm text-gray-700">Choose specific barangay/s</span>
                        </label>
                    </div>
                </div>
                
                <div id="specific_barangays_group" class="<?= (isset($event['sms_recipient_scope']) && $event['sms_recipient_scope'] === 'specific_barangays') ? '' : 'hidden' ?>">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Barangays <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-4 gap-2 max-h-40 overflow-y-auto">
                        <?php
                        $barangayModel = new \App\Models\BarangayModel();
                        $barangays = $barangayModel->findAll();
                        $selectedBarangays = isset($event['sms_recipient_barangays']) ? json_decode($event['sms_recipient_barangays'], true) : [];
                        foreach ($barangays as $barangay): 
                            // Skip "City-wide" barangay since "All barangays" scope already covers this
                            if (strtolower($barangay['name']) === 'city-wide') continue;
                        ?>
                            <label class="flex items-center">
                                <input type="checkbox" name="sms_recipient_barangays[]" value="<?= $barangay['barangay_id'] ?>"
                                       <?= in_array($barangay['barangay_id'], $selectedBarangays) ? 'checked' : '' ?>
                                       class="mr-2">
                                <span class="text-sm text-gray-700"><?= esc($barangay['name']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <!-- Admin SMS Recipient Options -->
            <div class="space-y-4">
                <p class="text-sm text-gray-600">As an Admin, you can send SMS notifications to users in your barangay.</p>
            </div>
            <?php endif; ?>
            
            <!-- Recipient Roles (for both Superadmin and Admin) -->
            <div id="barangay_level_roles_group" class="mt-4 <?= ($isSuperAdmin && (!isset($event['sms_recipient_scope']) || $event['sms_recipient_scope'] === 'specific_barangays' && empty($selectedBarangays))) ? 'opacity-50 pointer-events-none' : '' ?>">
                <?php if ($isSuperAdmin): ?>
                <label class="block text-sm font-medium text-gray-700 mb-2">Barangay-Level Officials</label>
                <?php else: ?>
                <label class="block text-sm font-medium text-gray-700 mb-2">Recipient Roles <span class="text-red-500">*</span></label>
                <?php endif; ?>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="sms_recipient_roles[]" value="all_officials" 
                               <?= (isset($event['sms_recipient_roles']) && in_array('all_officials', json_decode($event['sms_recipient_roles'], true) ?? [])) ? 'checked' : '' ?>
                               class="mr-2 all-officials-checkbox">
                        <span class="text-sm text-gray-700">All SK Officials</span>
                    </label>
                    <div class="individual-roles-group ml-4 space-y-1">
                        <label class="flex items-center">
                            <input type="checkbox" name="sms_recipient_roles[]" value="chairperson" 
                                   <?= (isset($event['sms_recipient_roles']) && in_array('chairperson', json_decode($event['sms_recipient_roles'], true) ?? [])) ? 'checked' : '' ?>
                                   class="mr-2 individual-role-checkbox">
                            <span class="text-sm text-gray-700">SK Chairperson</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="sms_recipient_roles[]" value="secretary" 
                                   <?= (isset($event['sms_recipient_roles']) && in_array('secretary', json_decode($event['sms_recipient_roles'], true) ?? [])) ? 'checked' : '' ?>
                                   class="mr-2 individual-role-checkbox">
                            <span class="text-sm text-gray-700">SK Secretary</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="sms_recipient_roles[]" value="treasurer" 
                                   <?= (isset($event['sms_recipient_roles']) && in_array('treasurer', json_decode($event['sms_recipient_roles'], true) ?? [])) ? 'checked' : '' ?>
                                   class="mr-2 individual-role-checkbox">
                            <span class="text-sm text-gray-700">SK Treasurer</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="sms_recipient_roles[]" value="sk_members" 
                                   <?= (isset($event['sms_recipient_roles']) && in_array('sk_members', json_decode($event['sms_recipient_roles'], true) ?? [])) ? 'checked' : '' ?>
                                   class="mr-2 individual-role-checkbox">
                            <span class="text-sm text-gray-700">SK Members</span>
                        </label>
                    </div>
                    <label class="flex items-center">
                        <input type="checkbox" name="sms_recipient_roles[]" value="kk_members" 
                               <?= (isset($event['sms_recipient_roles']) && in_array('kk_members', json_decode($event['sms_recipient_roles'], true) ?? [])) ? 'checked' : '' ?>
                               class="mr-2">
                        <span class="text-sm text-gray-700">KK Members</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
        <?php if (isset($event) && $event['status'] === 'Published'): ?>
            <!-- For published events, show only Update Event button -->
            <button type="submit" name="submit_action" value="publish" class="bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg hover:bg-blue-700 transition duration-200">
                <i class="fas fa-save mr-2"></i>Update Event
            </button>
        <?php elseif (isset($event) && $event['status'] === 'Scheduled'): ?>
            <!-- For scheduled events, show Move to Drafts, Reschedule, and Publish Now buttons -->
            <button type="submit" name="submit_action" value="draft" class="bg-yellow-500 text-white font-semibold py-2 px-6 rounded-lg hover:bg-yellow-600 transition duration-200">
                <i class="fas fa-file-alt mr-2"></i>Move to Drafts
            </button>
            <button type="submit" name="submit_action" value="schedule" id="schedule_btn" class="bg-blue-500 text-white font-semibold py-2 px-6 rounded-lg hover:bg-blue-600 transition duration-200">
                <i class="fas fa-clock mr-2"></i>Reschedule
            </button>
            <button type="submit" name="submit_action" value="publish" class="bg-green-500 text-white font-semibold py-2 px-6 rounded-lg hover:bg-green-600 transition duration-200">
                <i class="fas fa-rocket mr-2"></i>Publish Now
            </button>
        <?php else: ?>
            <!-- For new events or drafts, show Save Draft, Schedule, and Publish Now buttons -->
            <button type="submit" name="submit_action" value="draft" class="bg-yellow-500 text-white font-semibold py-2 px-6 rounded-lg hover:bg-yellow-600 transition duration-200">
                <i class="fas fa-file-alt mr-2"></i>Save Draft
            </button>
            <button type="submit" name="submit_action" value="schedule" id="schedule_btn" class="bg-blue-500 text-white font-semibold py-2 px-6 rounded-lg hover:bg-blue-600 transition duration-200">
                <i class="fas fa-clock mr-2"></i>Schedule
            </button>
            <button type="submit" name="submit_action" value="publish" class="bg-green-500 text-white font-semibold py-2 px-6 rounded-lg hover:bg-green-600 transition duration-200">
                <i class="fas fa-rocket mr-2"></i>Publish Now
            </button>
        <?php endif; ?>
    </div>
</form>

<script>
// Remove required attributes when submitting as draft
document.addEventListener('DOMContentLoaded', function() {
    // Handle scheduling toggle
    const schedulingEnabled = document.getElementById('scheduling_enabled');
    const schedulingGroup = document.getElementById('scheduling_datetime_group');
    
    if (schedulingEnabled && schedulingGroup) {
        schedulingEnabled.addEventListener('change', function() {
            if (this.checked) {
                schedulingGroup.classList.remove('hidden');
            } else {
                schedulingGroup.classList.add('hidden');
            }
        });
    }
    
    // Handle SMS notification toggle
    const smsEnabled = document.getElementById('sms_notification_enabled');
    const smsGroup = document.getElementById('sms_recipient_group');
    
    if (smsEnabled && smsGroup) {
        smsEnabled.addEventListener('change', function() {
            if (this.checked) {
                smsGroup.classList.remove('hidden');
            } else {
                smsGroup.classList.add('hidden');
            }
        });
    }
    
    const draftButton = document.querySelector('button[value="draft"]');
    if (draftButton) {
        draftButton.addEventListener('click', function(e) {
            // Remove required attributes for draft submission except for title
            const requiredFields = document.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                // Keep title as required, remove required from other fields
                if (field.id !== 'title') {
                    field.removeAttribute('required');
                }
            });
        });
    }
    
    // Validate scheduling when publishing (only for new events, not published events)
    const publishButton = document.querySelector('button[value="publish"]');
    if (publishButton) {
        publishButton.addEventListener('click', function(e) {
            // Only validate scheduling for new events, not for updating published events
            const isPublishedEvent = <?= (isset($event) && $event['status'] === 'Published') ? 'true' : 'false' ?>;
            
            if (!isPublishedEvent) {
                const schedulingEnabled = document.getElementById('scheduling_enabled');
                const scheduledDatetime = document.getElementById('scheduled_publish_datetime');
                
                if (schedulingEnabled && schedulingEnabled.checked && 
                    scheduledDatetime && !scheduledDatetime.value) {
                    e.preventDefault();
                    alert('Please select a scheduled publish date and time when scheduling is enabled.');
                    return false;
                }
                
                // Validate that scheduled datetime is after current time
                if (scheduledDatetime && scheduledDatetime.value) {
                    const currentTime = new Date();
                    const scheduledTime = new Date(scheduledDatetime.value);
                    
                    if (scheduledTime <= currentTime) {
                        e.preventDefault();
                        alert('Scheduled publish date and time must be after the current date and time.');
                        return false;
                    }
                }
            }
        });
    }
    
    // Validate scheduling when publishing (only for new events, not published events)
    const publishButton = document.querySelector('button[value="publish"]');
    if (publishButton) {
        publishButton.addEventListener('click', function(e) {
            // Only validate scheduling for new events, not for updating published events
            const isPublishedEvent = <?= (isset($event) && $event['status'] === 'Published') ? 'true' : 'false' ?>;
            
            if (!isPublishedEvent) {
                const schedulingEnabled = document.getElementById('scheduling_enabled');
                const scheduledDatetime = document.getElementById('scheduled_publish_datetime');
                
                if (schedulingEnabled && schedulingEnabled.checked && 
                    scheduledDatetime && !scheduledDatetime.value) {
                    e.preventDefault();
                    alert('Please select a scheduled publish date and time when scheduling is enabled.');
                    return false;
                }
                
                // Validate that scheduled datetime is after current time
                if (scheduledDatetime && scheduledDatetime.value) {
                    const currentTime = new Date();
                    const scheduledTime = new Date(scheduledDatetime.value);
                    
                    if (scheduledTime <= currentTime) {
                        e.preventDefault();
                        alert('Scheduled publish date and time must be after the current date and time.');
                        return false;
                    }
                }
            }
        });
    }
    
    // Check if this form is loaded in a modal (AJAX context)
    // Look for modal container or check if form is inside a modal
    const eventModal = document.getElementById('eventModal');
    const isInModal = eventModal && (eventModal.contains(document.currentScript) || 
                      document.querySelector('#eventModalContent form') !== null);
    
    // Only add inline event listeners if NOT in modal context (to avoid conflicts with AJAX handlers)
    if (!isInModal) {
        // Validate scheduling when rescheduling (for scheduled events)
        const scheduleButton = document.querySelector('button[value="schedule"]');
        if (scheduleButton) {
            scheduleButton.addEventListener('click', function(e) {
                const isScheduledEvent = <?= (isset($event) && $event['status'] === 'Scheduled') ? 'true' : 'false' ?>;
                const schedulingEnabled = document.getElementById('scheduling_enabled');
                const scheduledDatetime = document.getElementById('scheduled_publish_datetime');
                
                // For new events or drafts, auto-enable scheduling if not already enabled
                if (!isScheduledEvent && (!schedulingEnabled || !schedulingEnabled.checked)) {
                    if (schedulingEnabled) {
                        schedulingEnabled.checked = true;
                        schedulingEnabled.dispatchEvent(new Event('change'));
                    }
                }
                
                // Always require scheduled datetime for Schedule button
                if (scheduledDatetime && !scheduledDatetime.value) {
                    e.preventDefault();
                    showInlineSchedulingError(isScheduledEvent ? 'Please select a new scheduled publish date and time when rescheduling.' : 'Please select a scheduled publish date and time.');
                    setTimeout(() => {
                        scheduledDatetime.focus();
                    }, 100);
                    return false;
                }
                
                // Validate that scheduled datetime is after current time
                if (scheduledDatetime && scheduledDatetime.value) {
                    const currentTime = new Date();
                    const scheduledTime = new Date(scheduledDatetime.value);
                    
                    if (scheduledTime <= currentTime) {
                        e.preventDefault();
                        showInlineSchedulingError('Scheduled publish date and time must be after the current date and time.');
                        scheduledDatetime.focus();
                        return false;
                    }
                }
            });
        }
        
        // Handle form submission to ensure submit_action is set
        const form = document.getElementById('eventForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const submitAction = e.submitter?.value || 'publish';
                
                // Validate scheduled datetime if scheduling is enabled
                const schedulingEnabled = document.getElementById('scheduling_enabled');
                if (schedulingEnabled && schedulingEnabled.checked) {
                    const scheduledDatetimeInput = document.getElementById('scheduled_publish_datetime');
                    if (scheduledDatetimeInput && !validateScheduledDateTime(scheduledDatetimeInput)) {
                        e.preventDefault();
                        return false;
                    }
                    
                    // Ensure scheduled datetime is provided for non-draft submissions
                    if (submitAction !== 'draft' && (!scheduledDatetimeInput.value || scheduledDatetimeInput.value.trim() === '')) {
                        showInlineSchedulingError('Scheduled publish date and time is required when scheduling is enabled.');
                        scheduledDatetimeInput.focus();
                        e.preventDefault();
                        return false;
                    }
                }
                
                const submitActionInput = document.createElement('input');
                submitActionInput.type = 'hidden';
                submitActionInput.name = 'submit_action';
                submitActionInput.value = submitAction;
                form.appendChild(submitActionInput);
            });
        }
    }
    
    // Add comprehensive file validation for event banner
    const fileInput = document.getElementById('event_banner');
    const fileError = document.getElementById('file-error');
    
    function showFileError(message) {
        fileError.textContent = message;
        fileError.classList.remove('hidden');
        fileInput.classList.add('border-red-500');
        fileInput.classList.remove('border-gray-300');
    }
    
    function hideFileError() {
        fileError.classList.add('hidden');
        fileInput.classList.remove('border-red-500');
        fileInput.classList.add('border-gray-300');
    }
    
    // Helper function for inline scheduling error messages
    function showInlineSchedulingError(message) {
        // Get or create scheduling error element
        let errorElement = document.getElementById('scheduling-error');
        if (!errorElement) {
            errorElement = document.createElement('p');
            errorElement.id = 'scheduling-error';
            errorElement.className = 'text-red-500 text-xs sm:text-sm mt-1';
            
            // Insert after the scheduled datetime field
            const datetimeField = document.getElementById('scheduled_publish_datetime');
            if (datetimeField && datetimeField.parentNode) {
                datetimeField.parentNode.insertBefore(errorElement, datetimeField.nextSibling);
            } else {
                // Fallback: insert after scheduling section
                const schedulingSection = document.getElementById('scheduling_datetime_group');
                if (schedulingSection) {
                    schedulingSection.appendChild(errorElement);
                }
            }
        }
        
        errorElement.textContent = message;
        errorElement.style.display = 'block';
        
        // Add field error styling and shake animation to datetime input
        const datetimeInput = document.getElementById('scheduled_publish_datetime');
        if (datetimeInput) {
            datetimeInput.classList.add('field-error', 'shake');
            // Remove shake animation after it completes
            setTimeout(() => {
                datetimeInput.classList.remove('shake');
            }, 500);
        }
    }
    
    function clearInlineSchedulingError() {
        const errorElement = document.getElementById('scheduling-error');
        if (errorElement) {
            errorElement.style.display = 'none';
        }
        
        // Remove field error styling from datetime input
        const datetimeInput = document.getElementById('scheduled_publish_datetime');
        if (datetimeInput) {
            datetimeInput.classList.remove('field-error', 'shake');
        }
    }
    
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            hideFileError(); // Clear previous errors
            
            if (file) {
                // Validate file size
                const maxSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxSize) {
                    const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                    showFileError(`File "${file.name}" is too large (${fileSizeMB} MB). Maximum allowed size is 5MB.`);
                    e.target.value = '';
                    return;
                }
                
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    showFileError(`Invalid file type for "${file.name}". Allowed formats: JPG, JPEG, PNG, WEBP.`);
                    e.target.value = '';
                    return;
                }
                
                console.log('File validation passed:', file.name, 'Size:', (file.size / 1024).toFixed(2) + 'KB', 'Type:', file.type);
            }
        });
    }
    
    // Function to validate scheduled datetime (used only during form submission)
    function validateScheduledDateTime(input) {
        if (!input.value) return true;
        
        const currentTime = new Date();
        const scheduledTime = new Date(input.value);
        
        // Add a small buffer (1 minute) to account for processing time
        const minimumTime = new Date(currentTime.getTime() + 60000);
        
        if (scheduledTime <= minimumTime) {
            const currentTimeStr = formatDateTime(currentTime);
            showInlineSchedulingError(`Scheduled publish date and time must be after the current time (${currentTimeStr}). Please select a future date and time.`);
            input.focus();
            return false;
        }
        return true;
    }
    
    // Function to format date time for display
    function formatDateTime(date) {
        return date.getFullYear() + '-' + 
               String(date.getMonth() + 1).padStart(2, '0') + '-' + 
               String(date.getDate()).padStart(2, '0') + ' ' +
               String(date.getHours()).padStart(2, '0') + ':' + 
               String(date.getMinutes()).padStart(2, '0');
    }
    
    // Handle scheduling toggle
    const schedulingToggle = document.getElementById('scheduling_enabled');
    if (schedulingToggle) {
        schedulingToggle.addEventListener('change', function() {
            const schedulingSection = document.getElementById('scheduling_datetime_group');
            if (schedulingSection) {
                if (this.checked) {
                    schedulingSection.style.display = 'block';
                    schedulingSection.classList.remove('hidden');
                } else {
                    schedulingSection.style.display = 'none';
                    schedulingSection.classList.add('hidden');
                }
            }
        });
    }
    
    // Handle SMS notification toggle
    const smsToggle = document.getElementById('sms_notification_enabled');
    if (smsToggle) {
        smsToggle.addEventListener('change', function() {
            const smsSection = document.getElementById('sms_recipient_group');
            if (smsSection) {
                if (this.checked) {
                    smsSection.style.display = 'block';
                    smsSection.classList.remove('hidden');
                } else {
                    smsSection.style.display = 'none';
                    smsSection.classList.add('hidden');
                }
            }
        });
    }
    
    // Handle SMS recipient scope toggle (for superadmin)
    const recipientScopes = document.querySelectorAll('input[name="sms_recipient_scope"]');
    recipientScopes.forEach(function(scope) {
        scope.addEventListener('change', function() {
            const specificBarangaysGroup = document.getElementById('specific_barangays_group');
            const barangayLevelRolesGroup = document.getElementById('barangay_level_roles_group');
            
            if (this.value === 'specific_barangays' && this.checked) {
                if (specificBarangaysGroup) {
                    specificBarangaysGroup.classList.remove('hidden');
                }
                // Only disable barangay-level roles, not city-level officials
                if (barangayLevelRolesGroup) {
                    barangayLevelRolesGroup.classList.add('opacity-50', 'pointer-events-none');
                }
            } else if (this.value === 'all_barangays' && this.checked) {
                if (specificBarangaysGroup) {
                    specificBarangaysGroup.classList.add('hidden');
                }
                // Enable barangay-level roles for "all barangays"
                if (barangayLevelRolesGroup) {
                    barangayLevelRolesGroup.classList.remove('opacity-50', 'pointer-events-none');
                }
            }
        });
    });
    
    // Handle barangay selection (only affects barangay-level roles, not city-level officials)
    const barangayCheckboxes = document.querySelectorAll('input[name="sms_recipient_barangays[]"]');
    barangayCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const selectedBarangays = document.querySelectorAll('input[name="sms_recipient_barangays[]"]:checked');
            const barangayLevelRolesGroup = document.getElementById('barangay_level_roles_group');
            
            if (selectedBarangays.length > 0) {
                // Enable barangay-level roles when barangays are selected
                if (barangayLevelRolesGroup) {
                    barangayLevelRolesGroup.classList.remove('opacity-50', 'pointer-events-none');
                }
            } else {
                // Disable barangay-level roles when no barangays are selected (in specific_barangays mode)
                const specificBarangaysScope = document.querySelector('input[name="sms_recipient_scope"][value="specific_barangays"]');
                if (specificBarangaysScope && specificBarangaysScope.checked && barangayLevelRolesGroup) {
                    barangayLevelRolesGroup.classList.add('opacity-50', 'pointer-events-none');
                }
            }
        });
    });
    
    // Handle checkbox logic
    const allPederasyonOfficialsCheckbox = document.querySelector('.all-pederasyon-officials-checkbox');
    const pederasyonRoleCheckboxes = document.querySelectorAll('.pederasyon-role-checkbox');
    const allOfficialsCheckbox = document.querySelector('.all-officials-checkbox');
    const individualRoleCheckboxes = document.querySelectorAll('.individual-role-checkbox');
    
    // Handle "All Pederasyon Officials" checkbox logic
    if (allPederasyonOfficialsCheckbox) {
        allPederasyonOfficialsCheckbox.addEventListener('change', function() {
            if (this.checked) {
                // When "All Pederasyon Officials" is checked, check all Pederasyon suboptions
                pederasyonRoleCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = true;
                });
            } else {
                // When "All Pederasyon Officials" is unchecked, uncheck all Pederasyon suboptions
                pederasyonRoleCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                });
            }
        });
    }

    // Handle Pederasyon suboption checkboxes
    pederasyonRoleCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            // If any Pederasyon suboption is unchecked, uncheck "All Pederasyon Officials"
            if (!this.checked && allPederasyonOfficialsCheckbox) {
                allPederasyonOfficialsCheckbox.checked = false;
            }
            // If all Pederasyon suboptions are checked, check "All Pederasyon Officials"
            else if (this.checked && allPederasyonOfficialsCheckbox) {
                const allPederasyonChecked = Array.from(pederasyonRoleCheckboxes).every(cb => cb.checked);
                if (allPederasyonChecked) {
                    allPederasyonOfficialsCheckbox.checked = true;
                }
            }
        });
    });

    // Handle "All SK Officials" checkbox logic
    if (allOfficialsCheckbox) {
        allOfficialsCheckbox.addEventListener('change', function() {
            if (this.checked) {
                // When "All SK Officials" is checked, check all individual SK role checkboxes
                individualRoleCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = true;
                });
            } else {
                // When "All SK Officials" is unchecked, uncheck all individual SK role checkboxes
                individualRoleCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                });
            }
        });
    }
    
    individualRoleCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            // If any individual role is unchecked, uncheck "All SK Officials"
            if (!this.checked && allOfficialsCheckbox) {
                allOfficialsCheckbox.checked = false;
            }
            // If all individual roles are checked, check "All SK Officials"
            else if (this.checked && allOfficialsCheckbox) {
                const allIndividualChecked = Array.from(individualRoleCheckboxes).every(cb => cb.checked);
                if (allIndividualChecked) {
                    allOfficialsCheckbox.checked = true;
                }
            }
        });
    });

    // ===== DATE/TIME PICKER RESTRICTIONS =====
    // Function to get current date and time in local timezone
    function getCurrentDateTime() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    // Function to set minimum datetime for inputs
    function setMinimumDateTime() {
        const currentDateTime = getCurrentDateTime();
        
        // Set minimum for event start datetime
        const startDatetimeInput = document.getElementById('start_datetime');
        if (startDatetimeInput) {
            startDatetimeInput.min = currentDateTime;
            
            // Update end datetime minimum when start changes (without showing alerts)
            startDatetimeInput.addEventListener('change', function() {
                const endDatetimeInput = document.getElementById('end_datetime');
                if (endDatetimeInput && this.value) {
                    endDatetimeInput.min = this.value;
                }
            });
        }
        
        // Set minimum for event end datetime
        const endDatetimeInput = document.getElementById('end_datetime');
        if (endDatetimeInput) {
            endDatetimeInput.min = currentDateTime;
        }
        
        // Set minimum for scheduled publish datetime
        const scheduledDatetimeInput = document.getElementById('scheduled_publish_datetime');
        if (scheduledDatetimeInput) {
            scheduledDatetimeInput.min = currentDateTime;
        }
    }

    // Initialize restrictions when form loads
    setMinimumDateTime();
    
    // Update minimum times every minute to keep them current
    setInterval(function() {
        const currentDateTime = getCurrentDateTime();
        
        const startInput = document.getElementById('start_datetime');
        const endInput = document.getElementById('end_datetime');
        const scheduledInput = document.getElementById('scheduled_publish_datetime');
        
        if (startInput && !startInput.value) {
            startInput.min = currentDateTime;
        }
        if (endInput && !endInput.value) {
            endInput.min = currentDateTime;
        }
        if (scheduledInput && !scheduledInput.value) {
            scheduledInput.min = currentDateTime;
        }
    }, 60000); // Update every minute
});
</script>