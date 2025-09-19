<!-- ===== MAIN CONTENT AREA ===== -->
<div class="flex-1 flex flex-col min-h-0 ml-0 lg:ml-64 pt-16">
    <main class="flex-1 overflow-auto p-6 bg-gray-50">
        
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">My Analytics Dashboard</h1>
            <p class="text-gray-600">Track your participation and engagement in K-NECT activities</p>
        </div>

        <!-- User Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Events Attended -->
            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-blue-900 to-blue-800 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500 font-medium">Events Attended</p>
                        <p class="text-2xl font-bold text-gray-800"><?= $event_stats['events_attended'] ?? 0 ?></p>
                        <p class="text-xs text-gray-400 font-medium">of <?= $event_stats['total_events_available'] ?? 0 ?> available</p>
                    </div>
                </div>
            </div>

            <!-- Attendance Rate -->
            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-gray-700 to-gray-600 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500 font-medium">Attendance Rate</p>
                        <p class="text-2xl font-bold text-gray-800"><?= number_format($event_stats['attendance_percentage'] ?? 0, 1) ?>%</p>
                        <?php if (isset($barangay_comparison['performance_indicator'])): ?>
                            <p class="text-xs font-medium <?= $barangay_comparison['performance_indicator'] == 'Above Average' ? 'text-green-600' : ($barangay_comparison['performance_indicator'] == 'Below Average' ? 'text-red-600' : 'text-gray-400') ?>">
                                <?= $barangay_comparison['performance_indicator'] ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Profile Completeness -->
            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-gray-600 to-gray-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500 font-medium">Profile Complete</p>
                        <p class="text-2xl font-bold text-gray-800"><?= $profile_completeness['completeness_score'] ?? 0 ?>%</p>
                        <p class="text-xs text-gray-400 font-medium">
                            <?= $profile_completeness['completeness_score'] == 100 ? 'Perfect!' : 'Can improve' ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Days as Member -->
            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-slate-700 to-slate-600 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500 font-medium">Days as Member</p>
                        <p class="text-2xl font-bold text-gray-800"><?= $user_summary['days_as_member'] ?? 0 ?></p>
                        <p class="text-xs text-gray-400 font-medium">Since <?= isset($user_summary['join_date']) ? date('M d, Y', strtotime($user_summary['join_date'])) : 'N/A' ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Achievements Section -->
        <?php if (isset($achievements)): ?>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 mb-2">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    Achievements & Badges
                </h2>
                <p class="text-gray-600">Your participation milestones and accomplishments</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                    <?php
                    $badges = [
                        'first_event_badge' => [
                            'name' => 'First Event', 
                            'desc' => 'Attended first event',
                            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>'
                        ],
                        'regular_participant_badge' => [
                            'name' => 'Regular', 
                            'desc' => 'Attended 5+ events',
                            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>'
                        ],
                        'active_member_badge' => [
                            'name' => 'Active', 
                            'desc' => 'Attended 10+ events',
                            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>'
                        ],
                        'super_active_badge' => [
                            'name' => 'Super Active', 
                            'desc' => 'Attended 20+ events',
                            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>'
                        ],
                        'consistent_attendee_badge' => [
                            'name' => 'Consistent', 
                            'desc' => '80%+ attendance',
                            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>'
                        ],
                        'perfect_attendance_badge' => [
                            'name' => 'Perfect', 
                            'desc' => '100% attendance',
                            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>'
                        ],
                        'one_year_member_badge' => [
                            'name' => 'Veteran', 
                            'desc' => '1+ year member',
                            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>'
                        ],
                        'complete_profile_badge' => [
                            'name' => 'Complete', 
                            'desc' => '100% profile',
                            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                        ]
                    ];
                    
                    foreach ($badges as $key => $badge):
                        $earned = isset($achievements[$key]) && $achievements[$key] == 1;
                    ?>
                        <div class="text-center p-4 rounded-xl border transition-all duration-300 <?= $earned ? 'border-blue-200 bg-gradient-to-br from-blue-50 to-blue-100 hover:shadow-md' : 'border-gray-200 bg-gray-50 hover:bg-gray-100' ?>">
                            <div class="flex justify-center mb-2 <?= $earned ? 'text-blue-600' : 'text-gray-400' ?>">
                                <?= $badge['icon'] ?>
                            </div>
                            <div class="text-xs font-semibold <?= $earned ? 'text-blue-800' : 'text-gray-500' ?>"><?= $badge['name'] ?></div>
                            <div class="text-xs mt-1 <?= $earned ? 'text-blue-600' : 'text-gray-400' ?>"><?= $badge['desc'] ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Attendance Trend Chart -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Attendance Trend (Last 12 Months)</h3>
                    <p class="text-gray-600 text-sm">Your event participation over time</p>
                </div>
                <div class="p-6">
                    <div id="attendanceTrendChart" style="height: 300px;"></div>
                </div>
            </div>

            <!-- Favorite Categories Chart -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Favorite Event Categories</h3>
                    <p class="text-gray-600 text-sm">Your most attended event types</p>
                </div>
                <div class="p-6">
                    <div id="favoriteCategoriesChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>

        <!-- Bottom Section: Recent Activity & Attendance -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                    <p class="text-gray-600 text-sm">Your latest actions and participation</p>
                </div>
                <div class="divide-y divide-gray-200">
                    <?php if (!empty($recent_activity)): ?>
                        <?php foreach (array_slice($recent_activity, 0, 8) as $activity): ?>
                            <div class="p-4 hover:bg-gray-50">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <?php if ($activity['activity_type'] == 'attendance'): ?>
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        <?php else: ?>
                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900"><?= esc($activity['activity_description']) ?></p>
                                        <p class="text-xs text-gray-500"><?= date('M d, Y • h:i A', strtotime($activity['activity_date'])) ?></p>
                                        <?php if ($activity['status_detail']): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium mt-1 
                                                <?= $activity['status_detail'] == 'Present' ? 'bg-green-100 text-green-800' : 
                                                   ($activity['status_detail'] == 'Late' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                                <?= esc($activity['status_detail']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-8 text-center">
                            <div class="text-gray-400 mb-4">
                                <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500">No recent activity found.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Attendance -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Event Attendance</h3>
                    <p class="text-gray-600 text-sm">Your attendance record for recent events</p>
                </div>
                <div class="divide-y divide-gray-200">
                    <?php if (!empty($recent_attendance)): ?>
                        <?php foreach ($recent_attendance as $attendance): ?>
                            <div class="p-4 hover:bg-gray-50">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900"><?= esc($attendance['title']) ?></h4>
                                        <p class="text-xs text-gray-600 mt-1">
                                            <?= date('M d, Y • h:i A', strtotime($attendance['start_datetime'])) ?>
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            </svg>
                                            <?= esc($attendance['location']) ?>
                                        </p>
                                        <?php if ($attendance['category']): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium mt-2 bg-blue-100 text-blue-800">
                                                <?= ucwords(str_replace('_', ' ', esc($attendance['category']))) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <?php 
                                        $status = $attendance['attendance_status'];
                                        $statusClass = $status == 'Present' ? 'bg-green-100 text-green-800' : 
                                                     ($status == 'Late' ? 'bg-yellow-100 text-yellow-800' : 
                                                     ($status == 'Absent' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'));
                                        ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= $statusClass ?>">
                                            <?= esc($status) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-8 text-center">
                            <div class="text-gray-400 mb-4">
                                <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500">No recent events found.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Barangay Comparison Info -->
        <?php if (isset($barangay_comparison) && $barangay_comparison): ?>
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-2">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                </svg>
                Barangay Comparison
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center">
                    <p class="text-sm text-blue-600">Your Attendance</p>
                    <p class="text-2xl font-bold text-blue-900"><?= number_format($barangay_comparison['user_attendance_percentage'] ?? 0, 1) ?>%</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-blue-600">Barangay Average</p>
                    <p class="text-2xl font-bold text-blue-900"><?= number_format($barangay_comparison['avg_attendance_percentage'] ?? 0, 1) ?>%</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-blue-600">Your Performance</p>
                    <p class="text-lg font-bold <?= $barangay_comparison['performance_indicator'] == 'Above Average' ? 'text-green-700' : ($barangay_comparison['performance_indicator'] == 'Below Average' ? 'text-red-700' : 'text-blue-900') ?>">
                        <?= $barangay_comparison['performance_indicator'] ?? 'N/A' ?>
                    </p>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </main>
</div>

<!-- Include Chart.js or Highcharts for visualizations -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Attendance Trend Chart
    fetch('/user-analytics/attendance-trend')
        .then(response => response.json())
        .then(data => {
            Highcharts.chart('attendanceTrendChart', {
                chart: {
                    type: 'line',
                    height: 300
                },
                title: {
                    text: null
                },
                credits: {
                    enabled: false
                },
                xAxis: {
                    categories: data.categories || [],
                    title: {
                        text: 'Month'
                    }
                },
                yAxis: [{
                    title: {
                        text: 'Number of Events'
                    },
                    min: 0
                }, {
                    title: {
                        text: 'Attendance Rate (%)'
                    },
                    min: 0,
                    max: 100,
                    opposite: true
                }],
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
                series: data.series || [],
                legend: {
                    enabled: true
                }
            });
        })
        .catch(error => {
            console.error('Error loading attendance trend:', error);
            document.getElementById('attendanceTrendChart').innerHTML = '<div class="text-center text-gray-500 py-8">Unable to load chart data</div>';
        });

    // Favorite Categories Chart
    fetch('/user-analytics/favorite-categories')
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                Highcharts.chart('favoriteCategoriesChart', {
                    chart: {
                        type: 'pie',
                        height: 300
                    },
                    title: {
                        text: null
                    },
                    credits: {
                        enabled: false
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.y} events'
                            },
                            showInLegend: true
                        }
                    },
                    series: [{
                        name: 'Events Attended',
                        colorByPoint: true,
                        data: data
                    }]
                });
            } else {
                document.getElementById('favoriteCategoriesChart').innerHTML = '<div class="text-center text-gray-500 py-8">No event categories data available</div>';
            }
        })
        .catch(error => {
            console.error('Error loading favorite categories:', error);
            document.getElementById('favoriteCategoriesChart').innerHTML = '<div class="text-center text-gray-500 py-8">Unable to load chart data</div>';
        });
});
</script>