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
                                <span class="font-medium"><?= count($attendance_records) ?> Events</span>
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
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                            <?php foreach ($attendance_records as $record): ?>
                                <!-- Attendance Card -->
                                <div class="attendance-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1">
                                    
                                    <!-- Event Banner Image -->
                                    <div class="relative h-32 bg-gradient-to-br from-blue-50 to-blue-100">
                                        <?php if (!empty($record['event_banner'])): ?>
                                            <img src="<?= base_url('/previewDocument/event/' . $record['event_banner']) ?>" 
                                                 alt="<?= esc($record['event_title']) ?>" 
                                                 class="w-full h-full object-cover"
                                                 loading="lazy"
                                                 onerror="this.style.display='none'">
                                        <?php else: ?>
                                            <div class="w-full h-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                                <svg class="w-16 h-16 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                        
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
                                    <div class="p-4">
                                        <!-- Event Title and Basic Info -->
                                        <div class="mb-4">
                                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 leading-tight">
                                                <?= esc($record['event_title']) ?>
                                            </h3>
                                            
                                            <!-- Event Date and Time -->
                                            <div class="space-y-1">
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

                                        <!-- Attendance Details -->
                                        <?php if ($record['overall_status'] === 'Attended'): ?>
                                            <div class="space-y-3">
                                                <!-- Morning Session -->
                                                <?php if ($record['time_in_am'] || $record['time_out_am']): ?>
                                                    <div class="bg-blue-50 rounded-lg p-3 border border-blue-100">
                                                        <div class="text-xs font-semibold text-blue-800 mb-2 flex items-center">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Morning Session
                                                        </div>
                                                        <div class="grid grid-cols-2 gap-2">
                                                            <?php if ($record['time_in_am']): ?>
                                                                <div class="text-center bg-white p-2 rounded-md border border-blue-200">
                                                                    <div class="text-xs text-gray-600 mb-1">Time In</div>
                                                                    <div class="text-sm font-mono font-bold text-green-700">
                                                                        <?= date('g:i A', strtotime($record['time_in_am'])) ?>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if ($record['time_out_am']): ?>
                                                                <div class="text-center bg-white p-2 rounded-md border border-blue-200">
                                                                    <div class="text-xs text-gray-600 mb-1">Time Out</div>
                                                                    <div class="text-sm font-mono font-bold text-red-700">
                                                                        <?= date('g:i A', strtotime($record['time_out_am'])) ?>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <!-- Afternoon Session -->
                                                <?php if ($record['time_in_pm'] || $record['time_out_pm']): ?>
                                                    <div class="bg-orange-50 rounded-lg p-3 border border-orange-100">
                                                        <div class="text-xs font-semibold text-orange-800 mb-2 flex items-center">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Afternoon Session
                                                        </div>
                                                        <div class="grid grid-cols-2 gap-2">
                                                            <?php if ($record['time_in_pm']): ?>
                                                                <div class="text-center bg-white p-2 rounded-md border border-orange-200">
                                                                    <div class="text-xs text-gray-600 mb-1">Time In</div>
                                                                    <div class="text-sm font-mono font-bold text-green-700">
                                                                        <?= date('g:i A', strtotime($record['time_in_pm'])) ?>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if ($record['time_out_pm']): ?>
                                                                <div class="text-center bg-white p-2 rounded-md border border-orange-200">
                                                                    <div class="text-xs text-gray-600 mb-1">Time Out</div>
                                                                    <div class="text-sm font-mono font-bold text-red-700">
                                                                        <?= date('g:i A', strtotime($record['time_out_pm'])) ?>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="bg-gray-50 rounded-lg p-4 text-center border border-gray-200">
                                                <div class="text-sm text-gray-600 flex items-center justify-center">
                                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    Registered but no attendance recorded
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div> <!-- End content with padding -->
    </div> <!-- End content container -->
</div> <!-- End main content area -->

<style>
/* Line clamp utility for text truncation */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Card styles */
.attendance-card {
    transition: all 0.3s ease-in-out;
    height: fit-content;
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
    
    // Add click interaction for cards (if needed for future enhancement)
    cards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Prevent default action if clicking on interactive elements
            if (e.target.closest('button') || e.target.closest('a')) {
                return;
            }
            
            // Add subtle click feedback
            this.style.transform = 'scale(0.98) translateY(-2px)';
            setTimeout(() => {
                this.style.transform = 'translateY(-4px)';
            }, 100);
        });
    });
});
</script>

