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
                            <p class="text-gray-600 mt-1 text-sm sm:text-base">Events you have attended with recorded time-in/time-out</p>
                        </div>
                        <?php 
                            // Filter only attended events (not just registered)
                            $attended_events = array_filter($attendance_records, function($record) {
                                return isset($record['overall_status']) && $record['overall_status'] === 'Attended';
                            });
                            $attended_count = count($attended_events);
                        ?>
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="flex items-center text-sm text-gray-600 bg-green-50 rounded-lg px-3 py-2 border border-green-200">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-medium text-green-700"><span id="attendance-count"><?= $attended_count ?></span> Events Attended</span>
                            </div>
                        </div>
                    </div>

                    <!-- Filters Section - Moved below header for better mobile UX -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5 mt-4">
                        <div class="flex flex-col gap-3">
                            <!-- Search Input - Full width on mobile -->
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input id="attendance-search" type="text" placeholder="Search events..." 
                                       class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                       autocomplete="off" />
                            </div>

                            <!-- Date Range Filters - Stack on mobile, side-by-side on larger screens -->
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
                                <div class="flex-1 flex items-center gap-2">
                                    <label class="text-sm text-gray-600 whitespace-nowrap">From:</label>
                                    <input id="attendance-date-start" type="date" 
                                           class="flex-1 px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" />
                                </div>
                                <div class="flex-1 flex items-center gap-2">
                                    <label class="text-sm text-gray-600 whitespace-nowrap">To:</label>
                                    <input id="attendance-date-end" type="date" 
                                           class="flex-1 px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" />
                                </div>
                                <button id="attendance-clear" type="button" 
                                    class="w-full sm:w-auto px-4 py-2 rounded-lg border border-green-600 bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300 text-sm font-medium transition-colors flex items-center justify-center gap-2">
                                    Clear Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance Records -->
                <div class="mb-6">
                    <?php if (empty($attended_events)): ?>
                        <!-- Empty State -->
                        <div class="flex flex-col items-center justify-center text-center py-16 px-4">
                            <div class="max-w-md mx-auto">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No Attended Events Yet</h3>
                                <p class="text-base text-gray-600 mb-2">You haven't attended any events yet.</p>
                                <p class="text-sm text-gray-500 mb-6">Register for events and time-in to build your attendance record.</p>
                                <a href="<?= base_url('/events') ?>" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"/>
                                    </svg>
                                    Browse Events
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="attendance-table min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-600">Event</th>
                                            <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-600">Date</th>
                                            <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-600">Time</th>
                                            <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-600">Morning Session</th>
                                            <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-600">Afternoon Session</th>
                        	                <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200" id="attendanceTableBody">
                                        <?php foreach ($attended_events as $record): ?>
                                            <?php
                                                $eventDate = !empty($record['event_date']) ? date('Y-m-d', strtotime($record['event_date'])) : '';
                                                $displayDate = !empty($record['event_date']) ? date('M d, Y', strtotime($record['event_date'])) : '—';
                                                $amIn = !empty($record['time_in_am']) ? date('g:i A', strtotime($record['time_in_am'])) : null;
                                                $amOut = !empty($record['time_out_am']) ? date('g:i A', strtotime($record['time_out_am'])) : null;
                                                $pmIn = !empty($record['time_in_pm']) ? date('g:i A', strtotime($record['time_in_pm'])) : null;
                                                $pmOut = !empty($record['time_out_pm']) ? date('g:i A', strtotime($record['time_out_pm'])) : null;
                                                $status = $record['overall_status'] ?? '';
                                            ?>
                                            <tr class="attendance-row" 
                                                data-date="<?= $eventDate ?>"
                                                data-keywords="<?= esc(strtolower(($record['event_title'] ?? '') . ' ' . ($record['event_location'] ?? '') . ' ' . ($record['event_date'] ?? '') . ' ' . ($record['event_time'] ?? '') . ' ' . ($record['overall_status'] ?? '')), 'attr') ?>"
                                            >
                                                <td class="px-4 py-3 align-top">
                                                    <div class="font-semibold text-gray-900 leading-tight">
                                                        <?= esc($record['event_title']) ?>
                                                    </div>
                                                    <?php if (!empty($record['event_location'])): ?>
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            <?= esc($record['event_location']) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-4 py-3 align-top whitespace-nowrap text-gray-700">
                                                    <?= esc($displayDate) ?>
                                                </td>
                                                <td class="px-4 py-3 align-top text-gray-700 min-w-[120px]">
                                                    <?= esc($record['event_time'] ?? '—') ?>
                                                </td>
                                                <td class="px-4 py-3 align-top attendance-session-cell text-gray-700">
                                                    <?php if ($amIn || $amOut): ?>
                                                        <?php if ($amIn): ?>
                                                            <div class="flex items-center gap-2">
                                                                <span class="text-xs text-gray-500">In:</span>
                                                                <span class="font-mono text-sm text-gray-900"><?= esc($amIn) ?></span>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if ($amOut): ?>
                                                            <div class="flex items-center gap-2">
                                                                <span class="text-xs text-gray-500">Out:</span>
                                                                <span class="font-mono text-sm text-gray-900"><?= esc($amOut) ?></span>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="text-xs text-gray-400">No record</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-4 py-3 align-top attendance-session-cell text-gray-700">
                                                    <?php if ($pmIn || $pmOut): ?>
                                                        <?php if ($pmIn): ?>
                                                            <div class="flex items-center gap-2">
                                                                <span class="text-xs text-gray-500">In:</span>
                                                                <span class="font-mono text-sm text-gray-900"><?= esc($pmIn) ?></span>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if ($pmOut): ?>
                                                            <div class="flex items-center gap-2">
                                                                <span class="text-xs text-gray-500">Out:</span>
                                                                <span class="font-mono text-sm text-gray-900"><?= esc($pmOut) ?></span>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="text-xs text-gray-400">No record</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-4 py-3 align-top">
                                                    <?php if (strtolower($status) === 'attended'): ?>
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold badge-attended">
                                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                            Attended
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold badge-registered">
                                                            <?= esc($status ?: 'Registered') ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- No results after filtering -->
                        <div id="attendance-empty-filtered" class="hidden text-center py-12 px-4">
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

<style>
.attendance-session-cell div + div {
    margin-top: 0.25rem;
}

.badge-attended {
    background-color: #dcfce7;
    color: #166534;
}

.badge-registered {
    background-color: #e5e7eb;
    color: #374151;
}

.bg-blue-50 {
    background-color: #eff6ff;
}

.bg-orange-50 {
    background-color: #fff7ed;
}

.text-blue-800 {
    color: #1e40af;
}

.text-orange-800 {
    color: #9a3412;
}

.border-blue-200 {
    border-color: #bfdbfe;
}

.border-blue-300 {
    border-color: #93c5fd;
}

.border-orange-200 {
    border-color: #fed7aa;
}

.border-orange-300 {
    border-color: #fbbf24;
}

.attendance-table tbody tr:hover {
    background-color: #f8fafc;
    transition: background-color 0.2s ease;
}

.touch-manipulation {
    touch-action: manipulation;
    -webkit-tap-highlight-color: transparent;
}

@media (min-width: 1024px) {
    .lg\:ml-64 {
        width: calc(100% - 16rem) !important;
        margin-left: 16rem !important;
        max-width: calc(100% - 16rem) !important;
        position: relative !important;
        right: 0 !important;
    }

    #sidebar {
        width: 16rem !important;
        min-width: 16rem !important;
        flex: 0 0 16rem !important;
    }
}

@media (max-width: 1023px) {
    .lg\:ml-64 {
        width: 100% !important;
        margin-left: 0 !important;
    }
}

.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.font-mono {
    font-family: ui-monospace, SFMono-Regular, "SF Mono", Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rows = Array.from(document.querySelectorAll('.attendance-row'));
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

        rows.forEach(row => {
            let matches = true;

            if (q) {
                const keywords = normalize(row.dataset.keywords || row.textContent || '');
                if (!keywords.includes(q)) {
                    matches = false;
                }
            }

            if (matches && (startDate || endDate)) {
                const rowDate = (row.dataset.date || '').slice(0, 10);
                if (!rowDate) {
                    matches = false;
                } else {
                    if (startDate && rowDate < startDate) matches = false;
                    if (endDate && rowDate > endDate) matches = false;
                }
            }

            row.style.display = matches ? '' : 'none';
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
