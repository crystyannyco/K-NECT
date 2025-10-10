<!-- ===== MAIN CONTENT AREA ===== -->
<div class="flex-1 flex flex-col min-h-0 ml-64 pt-16">
    <main class="flex-1 overflow-auto p-6 bg-gray-50">
        
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900"><?= isset($page_title) ? $page_title : 'Event Analytics' ?></h1>
            <p class="text-gray-600">Comprehensive event participation analysis and insights</p>
        </div>

        <!-- Custom CSS for smooth transitions -->
        <style>
            #genderParticipationSection {
                transition: all 0.3s ease-in-out;
            }
            #topEngagedBarangaysSection {
                transition: all 0.3s ease-in-out;
            }
        </style>

        <!-- Filter Section (Only for City-wide view) -->
        <?php if ($view_type === 'citywide'): ?>
        <div class="mb-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filters
                </h3>
                <div class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-64">
                        <label for="barangayFilter" class="block text-sm font-medium text-gray-700 mb-2">Filter by Barangay:</label>
                        <select id="barangayFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="all">All Barangays</option>
                            <?php if (isset($barangays)): ?>
                                <?php foreach ($barangays as $barangay): ?>
                                    <option value="<?= $barangay['barangay_id'] ?>"><?= $barangay['name'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="flex-1 min-w-48">
                        <label for="monthsFilter" class="block text-sm font-medium text-gray-700 mb-2">Time Range:</label>
                        <select id="monthsFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="6">Last 6 months</option>
                            <option value="12" selected>Last 12 months</option>
                            <option value="24">Last 24 months</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 summary-card" data-metric="total_published_events">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-blue-900 to-blue-800 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500 font-medium metric-label">Published Events</p>
                        <p class="text-2xl font-bold text-gray-800 metric-value"><?= $event_summary['total_published_events'] ?? 0 ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 summary-card" data-metric="total_unique_participants">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-gray-700 to-gray-600 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500 font-medium metric-label">Unique Participants</p>
                        <p class="text-2xl font-bold text-gray-800 metric-value"><?= $event_summary['total_unique_participants'] ?? 0 ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 summary-card" data-metric="avg_participation_rate">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-blue-600 to-blue-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500 font-medium metric-label">Avg Participation Rate</p>
                        <p class="text-2xl font-bold text-gray-800 metric-value"><?= round($event_summary['avg_participation_rate'] ?? 0, 1) ?>%</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 summary-card" data-metric="avg_attendance_duration">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-slate-700 to-slate-600 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500 font-medium metric-label">Avg Duration</p>
                        <p class="text-2xl font-bold text-gray-800 metric-value"><?= round($event_summary['avg_attendance_duration'] ?? 0) ?> min</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Engaged Barangays / Participation Rate Per Event - Full Width -->
        <div id="participationRateSection" class="bg-white rounded-lg shadow-sm mb-6">
            <div class="p-6 border-b border-gray-200">
                <h3 id="participationRateSectionTitle" class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span id="participationRateTitleText"><?= $view_type === 'citywide' ? 'Top Engaged Barangays' : 'Participation Rate Per Event' ?></span>
                    <span id="participationRateSubtitle" class="ml-2 text-sm font-normal text-gray-500"><?= $view_type === 'citywide' ? '(Avg Participation Rate %)' : '(Actual Attendees / Target Participants)' ?></span>
                </h3>
            </div>
            <div class="p-6">
                <div id="participationRatePerEventChart" style="height: 400px;"></div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Event Participation Rate Trend -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Participation Rate Trend
                        <span class="ml-2 text-sm font-normal text-gray-500">(%)</span>
                    </h3>
                </div>
                <div class="p-6">
                    <div id="participationTrendChart" style="height: 300px;"></div>
                </div>
            </div>

            <!-- Event Categories by Participation Rate -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                        </svg>
                        Categories by Participation Rate
                        <span class="ml-2 text-sm font-normal text-gray-500">(%)</span>
                    </h3>
                </div>
                <div class="p-6">
                    <div id="popularCategoriesChart" style="height: 300px;"></div>
                </div>
            </div>

            <?php if ($view_type === 'citywide'): ?>
            <!-- Top Engaged Barangays -->
            <div id="topEngagedBarangaysSection" class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Top Engaged Barangays
                    </h3>
                </div>
                <div class="p-6">
                    <div id="topBarangaysChart" style="height: 300px;"></div>
                </div>
            </div>

            <!-- Participation by Gender -->
            <div id="genderParticipationSection" class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Participation by Gender per Event
                    </h3>
                </div>
                <div class="p-6">
                    <div id="genderParticipationChart" style="height: 450px;"></div>
                </div>
            </div>
            <?php else: ?>
            <!-- Participation by Gender - Full Width for SK View -->
            <div class="bg-white rounded-lg shadow-sm lg:col-span-2">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Participation by Gender per Event
                    </h3>
                </div>
                <div class="p-6">
                    <div id="genderParticipationChart" style="height: 450px;"></div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Tables Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Active SK Officials -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Top Active SK Officials
                    </h3>
                </div>
                <div class="p-6">
                    <div id="activeSKOfficialsTable" class="overflow-x-auto">
                        <div class="text-center text-gray-500">Loading...</div>
                    </div>
                </div>
            </div>

            <!-- Top Active KK Members -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Top Active KK Members
                    </h3>
                </div>
                <div class="p-6">
                    <div id="activeKKMembersTable" class="overflow-x-auto">
                        <div class="text-center text-gray-500">Loading...</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Consistency - Full Width -->
        <div class="bg-white rounded-lg shadow-sm mt-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Attendance Consistency
                </h3>
            </div>
            <div class="p-6">
                <div id="attendanceConsistencyTable" class="overflow-x-auto">
                    <div class="text-center text-gray-500">Loading...</div>
                </div>
            </div>
        </div>

    </main>
</div>

<!-- Include Highcharts -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/heatmap.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Global variables
    const viewType = '<?= $view_type ?>';
    const baseApiUrl = viewType === 'citywide' ? '/analytics/pederasyon' : '/analytics/sk';

    // Chart instances
    let participationRatePerEventChart, participationTrendChart, popularCategoriesChart, topBarangaysChart, genderParticipationChart;

    // Initialize charts when document is ready
    $(document).ready(function() {
        <?php if ($view_type === 'citywide'): ?>
        // Load appropriate chart based on initial filter selection
        const initialBarangay = $('#barangayFilter').val();
        loadParticipationRateChart(initialBarangay);
        <?php else: ?>
        loadParticipationRatePerEventChart();
        <?php endif; ?>
        
        loadParticipationTrendChart();
        loadPopularCategoriesChart();
        loadTopActiveSKOfficialsTable();
        loadTopActiveKKMembersTable();
        loadAttendanceConsistencyTable();
        
        <?php if ($view_type === 'citywide'): ?>
        // Initialize visibility based on initial filter selection
        const initialBarangaySelection = $('#barangayFilter').val();
        toggleTopBarangaysSection(initialBarangaySelection);
        <?php else: ?>
        // For SK view, always load gender participation chart
        loadGenderParticipationChart();
        <?php endif; ?>
        
        <?php if ($view_type === 'citywide'): ?>
        
        // Add event listeners for auto-refresh on filter changes
        $('#barangayFilter').on('change', function() {
            refreshCharts(); // Auto-refresh all charts when barangay filter changes
        });
        
        $('#monthsFilter').on('change', function() {
            refreshCharts(); // Auto-refresh all charts when time range filter changes
        });
        <?php endif; ?>
    });

    // Function to refresh all charts (for filter changes)
    function refreshCharts() {
        <?php if ($view_type === 'citywide'): ?>
        // Load appropriate chart based on filter selection
        const selectedBarangay = $('#barangayFilter').val();
        loadParticipationRateChart(selectedBarangay);
        toggleTopBarangaysSection(selectedBarangay);
        
        // Gender participation chart is loaded within toggleTopBarangaysSection for non-"All Barangays" selections
        <?php else: ?>
        loadParticipationRatePerEventChart();
        loadGenderParticipationChart();
        <?php endif; ?>
        
        loadParticipationTrendChart();
        loadPopularCategoriesChart();
        loadTopActiveSKOfficialsTable();
        loadTopActiveKKMembersTable();
        loadAttendanceConsistencyTable();
        loadEventSummaryCards(); // Refresh summary cards with filtered data
    }

    <?php if ($view_type === 'citywide'): ?>
    // Function to toggle Top Engaged Barangays section visibility and Gender Participation expansion
    function toggleTopBarangaysSection(selectedValue) {
        const topBarangaysSection = $('#topEngagedBarangaysSection');
        const genderParticipationSection = $('#genderParticipationSection');
        
        if (selectedValue === 'all') {
            // Hide the Top Engaged Barangays section for "All Barangays" filter
            // Keep only the "Top Engaged Barangays (Avg Participation Rate %)" in the participation rate section above
            topBarangaysSection.hide();
            
            // Show gender participation chart with full width
            genderParticipationSection.show();
            genderParticipationSection.addClass('lg:col-span-2');
            
            // Load gender participation chart
            loadGenderParticipationChart();
        } else {
            // Hide Top Engaged Barangays for "City-wide" and specific barangay selections
            topBarangaysSection.hide();
            
            // Show gender participation chart with full width
            genderParticipationSection.show();
            genderParticipationSection.addClass('lg:col-span-2');
            
            // Load gender participation chart
            loadGenderParticipationChart();
            
            // Trigger chart resize to fit new container width
            setTimeout(function() {
                if (typeof genderParticipationChart !== 'undefined' && genderParticipationChart) {
                    genderParticipationChart.reflow();
                }
            }, 100);
        }
    }
    <?php endif; ?>

    // Load Event Summary Cards
    function loadEventSummaryCards() {
        const params = new URLSearchParams({
            view_type: viewType
        });
        
        if (viewType === 'citywide') {
            const barangayId = $('#barangayFilter').val();
            const months = $('#monthsFilter').val();
            
            // Always send barangay_id to backend to distinguish between all/city-wide/specific
            if (barangayId) {
                params.append('barangay_id', barangayId);
            }
            if (months) {
                params.append('months', months);
            }
        }

        $.get(`${baseApiUrl}/event-summary?${params.toString()}`)
            .done(function(data) {
                // Update each summary card based on its data-metric attribute
                $('.summary-card').each(function() {
                    const $card = $(this);
                    const metric = $card.data('metric');
                    const $valueElement = $card.find('.metric-value');
                    
                    if (data[metric] !== undefined) {
                        if (metric === 'avg_attendance_duration') {
                            $valueElement.text(Math.round(data[metric] || 0) + ' min');
                        } else if (metric === 'avg_participation_rate') {
                            $valueElement.text(Math.round((data[metric] || 0) * 10) / 10 + '%');
                        } else {
                            $valueElement.text(data[metric] || 0);
                        }
                    }
                });
            })
            .fail(function() {
                console.error('Failed to load event summary cards');
            });
    }

    <?php if ($view_type === 'citywide'): ?>
    // Load appropriate chart based on filter selection
    function loadParticipationRateChart(selectedBarangay) {
        if (selectedBarangay === 'all') {
            // Show Top Engaged Barangays by Participation Rate
            $('#participationRateTitleText').text('Top Engaged Barangays');
            $('#participationRateSubtitle').text('(Avg Participation Rate %)');
            loadTopEngagedBarangaysByRate();
        } else {
            // Show Participation Rate Per Event
            $('#participationRateTitleText').text('Participation Rate Per Event');
            $('#participationRateSubtitle').text('(Actual Attendees / Target Participants)');
            loadParticipationRatePerEventChart();
        }
    }

    // Load Top Engaged Barangays by Participation Rate
    function loadTopEngagedBarangaysByRate() {
        $.get(`${baseApiUrl}/top-barangays-by-participation-rate`)
            .done(function(data) {
                participationRatePerEventChart = Highcharts.chart('participationRatePerEventChart', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: null
                    },
                    xAxis: {
                        categories: data.categories,
                        title: {
                            text: 'Barangays'
                        },
                        labels: {
                            rotation: -45,
                            style: {
                                fontSize: '11px'
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        max: 100,
                        title: {
                            text: 'Average Participation Rate (%)'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:11px"><b>{point.key}</b></span><br/>',
                        pointFormat: '<span style="color:{point.color}">●</span> Avg Participation Rate: <b>{point.y:.1f}%</b><br/>' +
                            '<span style="color:#999">Total Events: {point.eventCount}</span>',
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y:.1f}%'
                            },
                            colorByPoint: false
                        }
                    },
                    colors: ['#8b5cf6'],
                    legend: {
                        enabled: false
                    },
                    series: [{
                        name: 'Participation Rate',
                        data: data.series
                    }],
                    exporting: {
                        enabled: true
                    }
                });
            })
            .fail(function() {
                $('#participationRatePerEventChart').html('<div class="text-center text-gray-500">Error loading top barangays by participation rate data</div>');
            });
    }
    <?php endif; ?>

    // Load Participation Rate Per Event Chart
    function loadParticipationRatePerEventChart() {
        const params = new URLSearchParams({
            view_type: viewType
        });
        
        if (viewType === 'citywide') {
            const barangayId = $('#barangayFilter').val();
            
            // Always send barangay_id to backend to distinguish between all/city-wide/specific
            if (barangayId) {
                params.append('barangay_id', barangayId);
            }
        }

        $.get(`${baseApiUrl}/participation-rate-per-event?${params.toString()}`)
            .done(function(data) {
                participationRatePerEventChart = Highcharts.chart('participationRatePerEventChart', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: null
                    },
                    xAxis: {
                        categories: data.categories,
                        title: {
                            text: 'Events'
                        },
                        labels: {
                            rotation: -45,
                            style: {
                                fontSize: '10px'
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        max: 100,
                        title: {
                            text: 'Participation Rate (%)'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:11px"><b>{point.key}</b></span><br/>',
                        pointFormat: '<span style="color:{point.color}">●</span> Participation Rate: <b>{point.y:.1f}%</b><br/>' +
                            '<span style="color:#999">Target: {point.target}</span><br/>' +
                            '<span style="color:#999">Actual: {point.actual}</span>',
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y:.1f}%'
                            },
                            colorByPoint: false
                        }
                    },
                    colors: ['#3b82f6'],
                    legend: {
                        enabled: false
                    },
                    series: [{
                        name: 'Participation Rate',
                        data: data.series
                    }],
                    exporting: {
                        enabled: true
                    }
                });
            })
            .fail(function() {
                $('#participationRatePerEventChart').html('<div class="text-center text-gray-500">Error loading participation rate per event data</div>');
            });
    }

    // Load Participation Rate Trend Chart
    function loadParticipationTrendChart() {
        const params = new URLSearchParams({
            view_type: viewType
        });
        
        if (viewType === 'citywide') {
            const barangayId = $('#barangayFilter').val();
            const months = $('#monthsFilter').val();
            
            // Always send barangay_id to backend to distinguish between all/city-wide/specific
            if (barangayId) {
                params.append('barangay_id', barangayId);
            }
            if (months) {
                params.append('months', months);
            }
        }

        $.get(`${baseApiUrl}/participation-rate-trend?${params.toString()}`)
            .done(function(data) {
                participationTrendChart = Highcharts.chart('participationTrendChart', {
                    chart: {
                        type: 'line'
                    },
                    title: {
                        text: null
                    },
                    xAxis: {
                        categories: data.categories,
                        title: {
                            text: 'Month'
                        }
                    },
                    yAxis: {
                        min: 0,
                        max: 100,
                        title: {
                            text: 'Average Participation Rate (%)'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">Participation Rate: </td>' +
                            '<td style="padding:0"><b>{point.y:.1f}%</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    colors: ['#4A90E2'],
                    legend: {
                        enabled: false
                    },
                    series: [{
                        name: 'Participation Rate',
                        data: data.series[0],
                        marker: {
                            enabled: true,
                            radius: 4
                        }
                    }],
                    exporting: {
                        enabled: true
                    }
                });
            })
            .fail(function() {
                $('#participationTrendChart').html('<div class="text-center text-gray-500">Error loading participation rate trend data</div>');
            });
    }

    // Load Event Categories by Participation Rate Chart
    function loadPopularCategoriesChart() {
        const params = new URLSearchParams({
            view_type: viewType
        });
        
        if (viewType === 'citywide') {
            const barangayId = $('#barangayFilter').val();
            
            // Always send barangay_id to backend to distinguish between all/city-wide/specific
            if (barangayId) {
                params.append('barangay_id', barangayId);
            }
        }

        $.get(`${baseApiUrl}/categories-by-participation-rate?${params.toString()}`)
            .done(function(data) {
                popularCategoriesChart = Highcharts.chart('popularCategoriesChart', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: null
                    },
                    xAxis: {
                        categories: data.categories,
                        title: {
                            text: 'Event Categories'
                        },
                        labels: {
                            rotation: -45,
                            style: {
                                fontSize: '11px'
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        max: 100,
                        title: {
                            text: 'Average Participation Rate (%)'
                        }
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y:.1f}%'
                            }
                        }
                    },
                    colors: ['#28a745'],
                    legend: {
                        enabled: false
                    },
                    series: [{
                        name: 'Participation Rate',
                        data: data.series
                    }],
                    exporting: {
                        enabled: true
                    }
                });
            })
            .fail(function() {
                $('#popularCategoriesChart').html('<div class="text-center text-gray-500">Error loading popular categories data</div>');
            });
    }

    <?php if ($view_type === 'citywide'): ?>
    // Load Top Engaged Barangays Chart
    function loadTopBarangaysChart() {
        $.get(`${baseApiUrl}/top-engaged-barangays`)
            .done(function(data) {
                topBarangaysChart = Highcharts.chart('topBarangaysChart', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: null
                    },
                    xAxis: {
                        categories: data.categories,
                        title: {
                            text: 'Barangays'
                        },
                        labels: {
                            rotation: -45,
                            style: {
                                fontSize: '11px'
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        allowDecimals: false,
                        title: {
                            text: 'Total Participants'
                        }
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true
                            }
                        }
                    },
                    colors: ['#6f42c1'],
                    series: [{
                        name: 'Participants',
                        data: data.series
                    }],
                    exporting: {
                        enabled: true
                    }
                });
            })
            .fail(function() {
                $('#topBarangaysChart').html('<div class="text-center text-gray-500">Error loading top barangays data</div>');
            });
    }
    <?php endif; ?>

    // Load Gender Participation Chart
    function loadGenderParticipationChart() {
        const params = new URLSearchParams({
            view_type: viewType
        });
        
        if (viewType === 'citywide') {
            const barangayId = $('#barangayFilter').val();
            
            // Always send barangay_id to backend to distinguish between all/city-wide/specific
            if (barangayId) {
                params.append('barangay_id', barangayId);
            }
        }

        $.get(`${baseApiUrl}/participation-by-gender?${params.toString()}`)
            .done(function(data) {
                genderParticipationChart = Highcharts.chart('genderParticipationChart', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: null
                    },
                    xAxis: {
                        categories: data.categories,
                        title: {
                            text: 'Events'
                        },
                        labels: {
                            rotation: -45,
                            style: {
                                fontSize: '11px'
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        allowDecimals: false,
                        title: {
                            text: 'Participants'
                        }
                    },
                    legend: {
                        enabled: true,
                        align: 'center',
                        verticalAlign: 'bottom',
                        layout: 'horizontal'
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y}'
                            }
                        }
                    },
                    series: data.series,
                    exporting: {
                        enabled: true
                    }
                });
            })
            .fail(function() {
                $('#genderParticipationChart').html('<div class="text-center text-gray-500">Error loading gender participation data</div>');
            });
    }

    // Load Top Active SK Officials Table
    function loadTopActiveSKOfficialsTable() {
        const params = new URLSearchParams({
            view_type: viewType
        });
        
        if (viewType === 'citywide') {
            const barangayId = $('#barangayFilter').val();
            
            // Always send barangay_id to backend to distinguish between all/city-wide/specific
            if (barangayId) {
                params.append('barangay_id', barangayId);
            }
        }

        $.get(`${baseApiUrl}/top-active-sk-officials?${params.toString()}`)
            .done(function(data) {
                let tableHtml = `
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                                ${viewType === 'citywide' ? '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barangay</th>' : ''}
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Events Attended</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                `;
                
                data.forEach((member, index) => {
                    tableHtml += `
                        <tr class="${index < 3 ? 'bg-blue-50' : ''}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                ${index < 3 ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">' + (index + 1) + '</span>' : (index + 1)}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${member.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${member.position}</td>
                            ${viewType === 'citywide' ? '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' + (member.barangay || 'N/A') + '</td>' : ''}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${member.events_attended}</td>
                        </tr>
                    `;
                });
                
                tableHtml += '</tbody></table>';
                $('#activeSKOfficialsTable').html(tableHtml);
            })
            .fail(function() {
                $('#activeSKOfficialsTable').html('<div class="text-center text-gray-500">Error loading SK officials data</div>');
            });
    }

    // Load Top Active KK Members Table
    function loadTopActiveKKMembersTable() {
        const params = new URLSearchParams({
            view_type: viewType
        });
        
        if (viewType === 'citywide') {
            const barangayId = $('#barangayFilter').val();
            
            // Always send barangay_id to backend to distinguish between all/city-wide/specific
            if (barangayId) {
                params.append('barangay_id', barangayId);
            }
        }

        $.get(`${baseApiUrl}/top-active-kk-members?${params.toString()}`)
            .done(function(data) {
                let tableHtml = `
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                ${viewType === 'citywide' ? '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barangay</th>' : ''}
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Events Attended</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                `;
                
                data.forEach((member, index) => {
                    tableHtml += `
                        <tr class="${index < 3 ? 'bg-green-50' : ''}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                ${index < 3 ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">' + (index + 1) + '</span>' : (index + 1)}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${member.name}</td>
                            ${viewType === 'citywide' ? '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' + (member.barangay || 'N/A') + '</td>' : ''}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${member.events_attended}</td>
                        </tr>
                    `;
                });
                
                tableHtml += '</tbody></table>';
                $('#activeKKMembersTable').html(tableHtml);
            })
            .fail(function() {
                $('#activeKKMembersTable').html('<div class="text-center text-gray-500">Error loading KK members data</div>');
            });
    }

    // Load Attendance Consistency Table
    function loadAttendanceConsistencyTable() {
        const params = new URLSearchParams({
            view_type: viewType
        });
        
        if (viewType === 'citywide') {
            const barangayId = $('#barangayFilter').val();
            
            // Always send barangay_id to backend to distinguish between all/city-wide/specific
            if (barangayId) {
                params.append('barangay_id', barangayId);
            }
        }

        $.get(`${baseApiUrl}/attendance-consistency?${params.toString()}`)
            .done(function(data) {
                let tableHtml = `
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                ${viewType === 'citywide' ? '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barangay</th>' : ''}
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Events</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Attendees</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consistency Rate</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                `;
                
                data.forEach((item) => {
                    const rate = parseFloat(item.consistency_rate) || 0;
                    const rateColor = rate >= 80 ? 'text-green-600' : rate >= 60 ? 'text-yellow-600' : 'text-red-600';
                    
                    tableHtml += `
                        <tr>
                            ${viewType === 'citywide' ? '<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">' + item.barangay + '</td>' : ''}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.total_events}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.total_attendees}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm ${rateColor}">${rate.toFixed(1)}%</td>
                        </tr>
                    `;
                });
                
                tableHtml += '</tbody></table>';
                $('#attendanceConsistencyTable').html(tableHtml);
            })
            .fail(function() {
                $('#attendanceConsistencyTable').html('<div class="text-center text-gray-500">Error loading attendance consistency data</div>');
            });
    }
</script>
