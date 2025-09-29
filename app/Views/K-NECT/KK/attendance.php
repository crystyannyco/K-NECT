<!-- Main Content Area -->
<div class="min-h-screen bg-gray-50 flex flex-col w-full">
    <!-- Main Content Area with responsive margins -->
    <div class="pt-16 lg:ml-64 flex-1 flex justify-center items-start w-full">
        <div class="p-4 sm:p-6 lg:p-8 w-full max-w-7xl mx-auto">
            <div class="w-full">
                <!-- Page Header -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5 lg:p-6 mb-4 sm:mb-5 lg:mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                        <div class="flex-1">
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">My Attendance</h1>
                            <p class="text-gray-600 mt-1 text-sm sm:text-base">Track your participation in events and activities</p>
                        </div>
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="flex items-center text-sm text-gray-600 bg-gray-50 rounded-lg px-3 py-2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-medium"><span id="attendance-count"><?= count($attendance_records) ?></span> Events</span>
                            </div>

                            <!-- Filters: Search and Date -->
                            <div class="flex items-center gap-2">
                                <div class="relative">
                                    <input id="attendance-search" type="text" placeholder="Search events..." 
                                           class="w-40 sm:w-48 md:w-64 lg:w-72 px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                           autocomplete="off" />
                                </div>
                                <div class="flex items-center gap-2">
                                    <input id="attendance-date-start" type="date" 
                                           class="px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" />
                                    <span class="text-gray-500 text-sm">to</span>
                                    <input id="attendance-date-end" type="date" 
                                           class="px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" />
                                </div>
                                <button id="attendance-clear" type="button" 
                                        class="px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm">Clear</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance Records -->
                <div class="mb-6">
                    <?php if (empty($attendance_records)): ?>
                        <!-- Empty State -->
                        <div class="flex flex-col items-center justify-center text-center py-16 px-4">
                            <div class="max-w-md mx-auto">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No Attendance Records</h3>
                                <p class="text-base text-gray-600 mb-6">You haven't attended any events yet. Start participating!</p>
                                <a href="<?= base_url('kk/events') ?>" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"/>
                                    </svg>
                                    Browse Events
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Attendance Records - Responsive Grid Layout -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 auto-rows-fr">
                            <?php foreach ($attendance_records as $record): ?>
                                <!-- Attendance Card -->
                                <div 
                                    class="attendance-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 flex flex-col h-full"
                                    data-date="<?= !empty($record['event_date']) ? date('Y-m-d', strtotime($record['event_date'])) : '' ?>"
                                    data-keywords="<?= esc(strtolower(($record['event_title'] ?? '') . ' ' . ($record['event_location'] ?? '') . ' ' . ($record['event_date'] ?? '') . ' ' . ($record['event_time'] ?? '') . ' ' . ($record['overall_status'] ?? '')), 'attr') ?>"
                                    data-event='<?= json_encode([
                                        'title' => $record['event_title'] ?? '',
                                        'date' => $record['event_date'] ?? '',
                                        'time' => $record['event_time'] ?? '',
                                        'location' => $record['event_location'] ?? '',
                                        'status' => $record['overall_status'] ?? '',
                                        'banner' => $record['event_banner'] ?? '',
                                        'time_in_am' => $record['time_in_am'] ?? '',
                                        'time_out_am' => $record['time_out_am'] ?? '',
                                        'time_in_pm' => $record['time_in_pm'] ?? '',
                                        'time_out_pm' => $record['time_out_pm'] ?? '',
                                        'description' => $record['event_description'] ?? ''
                                    ]) ?>'
                                >
                                    
                                        <div class="relative h-40 bg-gradient-to-br from-blue-50 to-blue-100 flex-shrink-0">
                                            <img src="<?= !empty($record['event_banner']) ? base_url('uploads/event/' . $record['event_banner']) : base_url('assets/images/default-event-banner.svg') ?>" 
                                                 alt="<?= esc($record['event_title']) ?>" 
                                                 class="w-full h-full object-cover"
                                                 loading="lazy"
                                                 onerror="this.onerror=null; this.src='<?= base_url('assets/images/default-event-banner.svg') ?>';">
                                        
                                            <!-- Status Badge -->
                                            <div class="absolute top-3 right-3">
                                            <?php if ($record['overall_status'] === 'Attended'): ?>
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-green-500 text-white text-xs font-semibold shadow-lg">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Present
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-gray-500 text-white text-xs font-semibold shadow-lg">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Registered
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Card Content -->
                                    <div class="p-4 flex-grow flex flex-col">
                                        <!-- Event Title and Basic Info -->
                                        <div class="mb-2 flex-grow">
                                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 leading-tight overflow-hidden">
                                                <?= esc($record['event_title']) ?>
                                            </h3>
                                            
                                            <!-- Event Date and Time -->
                                            <div class="space-y-2">
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                                    </svg>
                                                    <span class="truncate"><?= $record['event_date'] ?></span>
                                                </div>
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="truncate"><?= $record['event_time'] ?></span>
                                                </div>
                                                <?php if (!empty($record['event_location'])): ?>
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                    <span class="truncate"><?= esc($record['event_location']) ?></span>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Attendance Information -->
                                        <?php if ($record['overall_status'] === 'Attended'): ?>
                                            <!-- Attendance Time Summary -->
                                            <div class="mb-3">
                                                <div class="grid grid-cols-2 gap-2">
                                                    <?php if (!empty($record['time_in_am']) || !empty($record['time_out_am'])): ?>
                                                    <div class="bg-blue-50 rounded-md p-2 border border-blue-100">
                                                        <div class="text-xs font-medium text-blue-700 mb-1">Morning Session</div>
                                                        <div class="grid grid-cols-1 gap-1">
                                                            <?php if (!empty($record['time_in_am'])): ?>
                                                            <div class="bg-white rounded px-2 py-1 flex justify-between items-center">
                                                                <span class="text-xs text-gray-500">In:</span>
                                                                <span class="text-xs font-mono font-medium"><?= substr($record['time_in_am'], 0, 5) ?></span>
                                                            </div>
                                                            <?php endif; ?>
                                                            
                                                            <?php if (!empty($record['time_out_am'])): ?>
                                                            <div class="bg-white rounded px-2 py-1 flex justify-between items-center">
                                                                <span class="text-xs text-gray-500">Out:</span>
                                                                <span class="text-xs font-mono font-medium"><?= substr($record['time_out_am'], 0, 5) ?></span>
                                                            </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($record['time_in_pm']) || !empty($record['time_out_pm'])): ?>
                                                    <div class="bg-orange-50 rounded-md p-2 border border-orange-100">
                                                        <div class="text-xs font-medium text-orange-700 mb-1">Afternoon Session</div>
                                                        <div class="grid grid-cols-1 gap-1">
                                                            <?php if (!empty($record['time_in_pm'])): ?>
                                                            <div class="bg-white rounded px-2 py-1 flex justify-between items-center">
                                                                <span class="text-xs text-gray-500">In:</span>
                                                                <span class="text-xs font-mono font-medium"><?= substr($record['time_in_pm'], 0, 5) ?></span>
                                                            </div>
                                                            <?php endif; ?>
                                                            
                                                            <?php if (!empty($record['time_out_pm'])): ?>
                                                            <div class="bg-white rounded px-2 py-1 flex justify-between items-center">
                                                                <span class="text-xs text-gray-500">Out:</span>
                                                                <span class="text-xs font-mono font-medium"><?= substr($record['time_out_pm'], 0, 5) ?></span>
                                                            </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Attendance Status Summary -->
                                        <div class="mt-auto">
                                            <?php if ($record['overall_status'] === 'Attended'): ?>
                                                <button 
                                                    onclick="openAttendanceModal(this.closest('.attendance-card'))"
                                                    class="w-full bg-green-600 hover:bg-green-700 text-white text-xs font-medium py-2 px-3 rounded-md transition-colors duration-200 flex items-center justify-center"
                                                >
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    View Detailed Attendance
                                                </button>
                                            <?php else: ?>
                                                <button 
                                                    onclick="openAttendanceModal(this.closest('.attendance-card'))"
                                                    class="w-full bg-gray-600 hover:bg-gray-700 text-white text-xs font-medium py-2 px-3 rounded-md transition-colors duration-200 flex items-center justify-center"
                                                >
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    View Event Details
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- No results after filtering -->
                        <div id="attendance-empty-filtered" class="hidden flex flex-col items-center justify-center text-center py-12 px-4">
                            <div class="max-w-md mx-auto">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <h3 class="text-base font-medium text-gray-900 mb-1">No matching records</h3>
                                <p class="text-sm text-gray-600">Try adjusting your search or picking a different date.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div> <!-- End content with padding -->
    </div> <!-- End content container -->
</div> <!-- End main content area -->

<!-- Attendance Detail Modal -->
<div id="attendanceModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="relative">
            <div id="modalBanner" class="h-48 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                <img id="modalBannerImg" class="w-full h-full object-cover hidden" alt="">
                <div id="modalBannerDefault" class="text-center">
                    <svg class="w-20 h-20 text-white opacity-80 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                    </svg>
                    <h3 class="text-white text-lg font-semibold">Event Details</h3>
                </div>
            </div>
            
            <!-- Status Badge -->
            <div class="absolute top-4 right-4">
                <span id="modalStatusBadge" class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold shadow-lg">
                    <svg id="modalStatusIcon" class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"></svg>
                    <span id="modalStatusText"></span>
                </span>
            </div>
            
            <!-- Close Button -->
            <button onclick="closeAttendanceModal()" class="absolute top-4 left-4 bg-black bg-opacity-20 hover:bg-opacity-30 text-white rounded-full p-2 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 max-h-[60vh] overflow-y-auto">
            <!-- Event Info -->
            <div class="mb-6">
                <h2 id="modalTitle" class="text-2xl font-bold text-gray-900 mb-4"></h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-500">Date</div>
                            <div id="modalDate" class="font-medium"></div>
                        </div>
                    </div>
                    
                    <div class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-500">Time</div>
                            <div id="modalTime" class="font-medium"></div>
                        </div>
                    </div>
                    
                    <div id="modalLocationContainer" class="flex items-center text-gray-600 md:col-span-2">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-500">Location</div>
                            <div id="modalLocation" class="font-medium"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Description -->
                <div id="modalDescriptionContainer" class="hidden">
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Description</h4>
                    <p id="modalDescription" class="text-gray-600 leading-relaxed mb-4"></p>
                </div>
            </div>
            
            <!-- Attendance Details -->
            <div id="modalAttendanceDetails">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Attendance Record</h4>
                
                <div id="modalNoAttendance" class="hidden bg-gray-50 rounded-lg p-6 text-center border border-gray-200">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h5 class="text-lg font-medium text-gray-900 mb-1">No Attendance Recorded</h5>
                    <p class="text-gray-600">You were registered for this event but didn't check in.</p>
                </div>
                
                <div id="modalAttendanceData" class="space-y-4">
                    <!-- Morning Session -->
                    <div id="modalMorningSession" class="hidden bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <div class="text-sm font-semibold text-blue-800 mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            Morning Session
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div id="modalTimeInAM" class="hidden bg-white p-4 rounded-lg border border-blue-300">
                                <div class="text-center">
                                    <div class="text-sm text-gray-600 mb-1">Time In</div>
                                    <div class="text-lg font-mono font-bold text-green-700" id="modalTimeInAMValue"></div>
                                </div>
                            </div>
                            <div id="modalTimeOutAM" class="hidden bg-white p-4 rounded-lg border border-blue-300">
                                <div class="text-center">
                                    <div class="text-sm text-gray-600 mb-1">Time Out</div>
                                    <div class="text-lg font-mono font-bold text-red-700" id="modalTimeOutAMValue"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Afternoon Session -->
                    <div id="modalAfternoonSession" class="hidden bg-orange-50 rounded-lg p-4 border border-orange-200">
                        <div class="text-sm font-semibold text-orange-800 mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            Afternoon Session
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div id="modalTimeInPM" class="hidden bg-white p-4 rounded-lg border border-orange-300">
                                <div class="text-center">
                                    <div class="text-sm text-gray-600 mb-1">Time In</div>
                                    <div class="text-lg font-mono font-bold text-green-700" id="modalTimeInPMValue"></div>
                                </div>
                            </div>
                            <div id="modalTimeOutPM" class="hidden bg-white p-4 rounded-lg border border-orange-300">
                                <div class="text-center">
                                    <div class="text-sm text-gray-600 mb-1">Time Out</div>
                                    <div class="text-lg font-mono font-bold text-red-700" id="modalTimeOutPMValue"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Line clamp utility for text truncation */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    max-height: 3rem; /* Control maximum height */
}

/* Card styles */
.attendance-card {
    transition: all 0.3s ease-in-out;
    min-height: 360px; /* Further reduced card height */
    display: flex;
    flex-direction: column;
}

.attendance-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

/* Focus states for accessibility */
.attendance-card:focus-within {
    outline: 2px solid #3B82F6;
    outline-offset: 2px;
}

/* Button styles for attendance actions */
.attendance-card button {
    cursor: pointer;
}

/* Grid improvements */
.auto-rows-fr {
    grid-auto-rows: 1fr;
}

/* Ensure equal height cards */
@supports (display: grid) {
    .grid.auto-rows-fr > .attendance-card {
        height: 100%;
        min-height: auto;
    }
}

/* Responsive layout fixes */
@media (min-width: 1024px) {
    .lg\:ml-64 {
        width: calc(100% - 16rem) !important;
        margin-left: 16rem !important;
        max-width: calc(100% - 16rem) !important;
        position: relative !important;
        right: 0 !important;
    }
    
    /* Fix sidebar width at all zoom levels */
    #sidebar {
        width: 16rem !important;
        min-width: 16rem !important;
        flex: 0 0 16rem !important;
    }
}

/* Mobile view (no sidebar) */
@media (max-width: 1023px) {
    .lg\:ml-64 {
        width: 100% !important;
        margin-left: 0 !important;
    }
}

/* Grid responsive behavior */
@media (max-width: 640px) {
    .attendance-card {
        margin-bottom: 1rem;
    }
}

/* Ensure proper spacing and alignment */
.grid {
    justify-items: stretch;
    align-items: start;
}

/* Truncation for long text */
.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Better visual hierarchy for time displays */
.font-mono {
    font-family: ui-monospace, SFMono-Regular, "SF Mono", Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
}

/* Enhanced hover effects for interactive elements */
.attendance-card .bg-white:hover {
    background-color: #f8fafc;
    transition: background-color 0.2s ease;
}

/* Status badge improvements */
.attendance-card span[class*="bg-green-"] {
    background-color: #10b981 !important;
}

.attendance-card span[class*="bg-gray-"] {
    background-color: #6b7280 !important;
}

/* Improved session section styling */
.bg-blue-50 {
    background-color: #eff6ff;
}

.bg-orange-50 {
    background-color: #fff7ed;
}

.border-blue-100 {
    border-color: #dbeafe;
}

.border-orange-100 {
    border-color: #fed7aa;
}

.border-blue-200 {
    border-color: #bfdbfe;
}

.border-orange-200 {
    border-color: #fde68a;
}

.text-blue-800 {
    color: #1e40af;
}

.text-orange-800 {
    color: #9a3412;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple fade-in animation for cards
    const cards = document.querySelectorAll('.attendance-card');
    
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(10px)';
        
        setTimeout(() => {
            card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 50);
    });

    // Filtering logic
    const searchInput = document.getElementById('attendance-search');
    const dateStartInput = document.getElementById('attendance-date-start');
    const dateEndInput = document.getElementById('attendance-date-end');
    const clearBtn = document.getElementById('attendance-clear');
    const countEl = document.getElementById('attendance-count');
    const emptyFiltered = document.getElementById('attendance-empty-filtered');

    function normalize(val) {
        return (val || '').toString().toLowerCase();
    }

    function applyFilters() {
        const q = normalize(searchInput && searchInput.value);
        const startDate = (dateStartInput && dateStartInput.value) || '';
        const endDate = (dateEndInput && dateEndInput.value) || '';
        let visibleCount = 0;

        cards.forEach(card => {
            let matches = true;

            if (q) {
                const keywords = normalize(card.dataset.keywords || card.textContent || '');
                if (!keywords.includes(q)) {
                    matches = false;
                }
            }

            if (matches && (startDate || endDate)) {
                const cardDate = (card.dataset.date || '').slice(0, 10);
                if (!cardDate) {
                    matches = false;
                } else {
                    if (startDate && cardDate < startDate) matches = false;
                    if (endDate && cardDate > endDate) matches = false;
                }
            }

            card.style.display = matches ? '' : 'none';
            if (matches) visibleCount++;
        });

        if (countEl) countEl.textContent = String(visibleCount);
        if (emptyFiltered) emptyFiltered.classList.toggle('hidden', visibleCount !== 0);
    }

    if (searchInput) searchInput.addEventListener('input', applyFilters, { passive: true });
    if (dateStartInput) dateStartInput.addEventListener('input', applyFilters, { passive: true });
    if (dateStartInput) dateStartInput.addEventListener('change', applyFilters, { passive: true });
    if (dateEndInput) dateEndInput.addEventListener('input', applyFilters, { passive: true });
    if (dateEndInput) dateEndInput.addEventListener('change', applyFilters, { passive: true });
    if (clearBtn) clearBtn.addEventListener('click', () => {
        if (searchInput) searchInput.value = '';
        if (dateStartInput) dateStartInput.value = '';
        if (dateEndInput) dateEndInput.value = '';
        applyFilters();
        if (searchInput) searchInput.focus();
    });

    // Initialize in case of pre-filled values (e.g., browser back/forward cache)
    applyFilters();
});

// Modal Functions
function openAttendanceModal(cardElement) {
    const eventData = JSON.parse(cardElement.getAttribute('data-event'));
    const modal = document.getElementById('attendanceModal');
    
    // Set modal content
    document.getElementById('modalTitle').textContent = eventData.title || 'Event';
    document.getElementById('modalDate').textContent = eventData.date || 'N/A';
    document.getElementById('modalTime').textContent = eventData.time || 'N/A';
    
    // Handle location
    const locationContainer = document.getElementById('modalLocationContainer');
    const locationElement = document.getElementById('modalLocation');
    if (eventData.location) {
        locationElement.textContent = eventData.location;
        locationContainer.classList.remove('hidden');
    } else {
        locationContainer.classList.add('hidden');
    }
    
    // Handle description
    const descContainer = document.getElementById('modalDescriptionContainer');
    const descElement = document.getElementById('modalDescription');
    if (eventData.description && eventData.description.trim()) {
        descElement.textContent = eventData.description;
        descContainer.classList.remove('hidden');
    } else {
        descContainer.classList.add('hidden');
    }
    
    // Handle banner image
    const bannerImg = document.getElementById('modalBannerImg');
    const bannerDefault = document.getElementById('modalBannerDefault');
    if (eventData.banner && eventData.banner.trim() !== '') {
        // Check if the banner file exists, if not fallback to default
        const bannerPath = `<?= base_url('uploads/event/') ?>${eventData.banner}`;
        bannerImg.src = bannerPath;
        bannerImg.onload = function() {
            bannerImg.classList.remove('hidden');
            bannerDefault.classList.add('hidden');
        };
        bannerImg.onerror = function() {
            // If image fails to load, use default banner
            bannerImg.src = '<?= base_url('assets/images/default-event-banner.svg') ?>';
            bannerImg.onload = function() {
                bannerImg.classList.remove('hidden');
                bannerDefault.classList.add('hidden');
            };
            bannerImg.onerror = function() {
                // If even the default fails, show the default div
                bannerImg.classList.add('hidden');
                bannerDefault.classList.remove('hidden');
            };
        };
    } else {
        // No banner specified, use default image
        bannerImg.src = '<?= base_url('assets/images/default-event-banner.svg') ?>';
        bannerImg.onload = function() {
            bannerImg.classList.remove('hidden');
            bannerDefault.classList.add('hidden');
        };
        bannerImg.onerror = function() {
            // If default image fails to load, show the default div
            bannerImg.classList.add('hidden');
            bannerDefault.classList.remove('hidden');
        };
    }
    
    // Handle status badge
    const statusBadge = document.getElementById('modalStatusBadge');
    const statusIcon = document.getElementById('modalStatusIcon');
    const statusText = document.getElementById('modalStatusText');
    
    if (eventData.status === 'Attended') {
        statusBadge.className = 'inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold shadow-lg bg-green-500 text-white';
        statusIcon.innerHTML = '<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>';
        statusText.textContent = 'Present';
    } else {
        statusBadge.className = 'inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold shadow-lg bg-gray-500 text-white';
        statusIcon.innerHTML = '<path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>';
        statusText.textContent = 'Registered';
    }
    
    // Handle attendance data
    const noAttendance = document.getElementById('modalNoAttendance');
    const attendanceData = document.getElementById('modalAttendanceData');
    const morningSession = document.getElementById('modalMorningSession');
    const afternoonSession = document.getElementById('modalAfternoonSession');
    
    // Hide all attendance sections first
    noAttendance.classList.add('hidden');
    morningSession.classList.add('hidden');
    afternoonSession.classList.add('hidden');
    
    if (eventData.status === 'Attended') {
        // Show morning session if data exists
        if (eventData.time_in_am || eventData.time_out_am) {
            morningSession.classList.remove('hidden');
            
            const timeInAM = document.getElementById('modalTimeInAM');
            const timeOutAM = document.getElementById('modalTimeOutAM');
            
            if (eventData.time_in_am) {
                timeInAM.classList.remove('hidden');
                document.getElementById('modalTimeInAMValue').textContent = formatTime(eventData.time_in_am);
            } else {
                timeInAM.classList.add('hidden');
            }
            
            if (eventData.time_out_am) {
                timeOutAM.classList.remove('hidden');
                document.getElementById('modalTimeOutAMValue').textContent = formatTime(eventData.time_out_am);
            } else {
                timeOutAM.classList.add('hidden');
            }
        }
        
        // Show afternoon session if data exists
        if (eventData.time_in_pm || eventData.time_out_pm) {
            afternoonSession.classList.remove('hidden');
            
            const timeInPM = document.getElementById('modalTimeInPM');
            const timeOutPM = document.getElementById('modalTimeOutPM');
            
            if (eventData.time_in_pm) {
                timeInPM.classList.remove('hidden');
                document.getElementById('modalTimeInPMValue').textContent = formatTime(eventData.time_in_pm);
            } else {
                timeInPM.classList.add('hidden');
            }
            
            if (eventData.time_out_pm) {
                timeOutPM.classList.remove('hidden');
                document.getElementById('modalTimeOutPMValue').textContent = formatTime(eventData.time_out_pm);
            } else {
                timeOutPM.classList.add('hidden');
            }
        }
    } else {
        noAttendance.classList.remove('hidden');
    }
    
    // Show modal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAttendanceModal() {
    const modal = document.getElementById('attendanceModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function formatTime(timeString) {
    if (!timeString) return 'N/A';
    try {
        const date = new Date(`2000-01-01 ${timeString}`);
        return date.toLocaleTimeString('en-US', { 
            hour: 'numeric', 
            minute: '2-digit',
            hour12: true 
        });
    } catch (e) {
        return timeString;
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('attendanceModal');
    if (e.target === modal) {
        closeAttendanceModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAttendanceModal();
    }
});
</script>

<script>
// Toast notifications (reuse style from settings.php)
if (typeof window.showNotification !== 'function') {
    window.showNotification = function(message, type = 'info') {
        if (!message) return;
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-[99999] p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;

        switch(type) {
            case 'success':
                notification.className += ' bg-green-500 text-white';
                break;
            case 'error':
                notification.className += ' bg-red-500 text-white';
                break;
            default:
                notification.className += ' bg-blue-500 text-white';
        }

        let icon = '';
        switch(type) {
            case 'success':
                icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
                break;
            case 'error':
                icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" /></svg>';
                break;
            case 'info':
            default:
                icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01" /></svg>';
                break;
        }

        notification.innerHTML = `
            <div class="flex items-center">
                ${icon}
                <span class="mr-2">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200 focus:outline-none">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => { notification.classList.remove('translate-x-full'); }, 100);
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => { if (notification.parentElement) notification.remove(); }, 300);
        }, 3000);
    };
}

// Hook into the existing Clear button to show toast
document.addEventListener('DOMContentLoaded', function() {
    const clearBtn = document.getElementById('attendance-clear');
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            if (typeof window.showNotification === 'function') {
                window.showNotification('Filters cleared.', 'info');
            }
        });
    }
});
</script>

