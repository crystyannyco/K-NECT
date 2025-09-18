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
                            <option value="city-wide">City-wide</option>
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
                    <div>
                        <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md transition-colors" onclick="refreshCharts()">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Apply Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Summary Cards -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-sm p-6 summary-card" data-metric="total_published_events">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-sm font-medium text-gray-500 metric-label">Published Events</h3>
                        <p class="text-2xl font-bold text-gray-900 metric-value"><?= $event_summary['total_published_events'] ?? 0 ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 summary-card" data-metric="total_unique_participants">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-sm font-medium text-gray-500 metric-label">Unique Participants</h3>
                        <p class="text-2xl font-bold text-gray-900 metric-value"><?= $event_summary['total_unique_participants'] ?? 0 ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 summary-card" data-metric="total_attendances">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-sm font-medium text-gray-500 metric-label">Total Attendances</h3>
                        <p class="text-2xl font-bold text-gray-900 metric-value"><?= $event_summary['total_attendances'] ?? 0 ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 summary-card" data-metric="avg_attendance_duration">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-100 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-sm font-medium text-gray-500 metric-label">Avg Duration</h3>
                        <p class="text-2xl font-bold text-gray-900 metric-value"><?= round($event_summary['avg_attendance_duration'] ?? 0) ?> min</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Event Participation Trend -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Event Participation Trend
                    </h3>
                </div>
                <div class="p-6">
                    <div id="participationTrendChart" style="height: 300px;"></div>
                </div>
            </div>

            <!-- Most Popular Event Categories -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                        </svg>
                        Most Popular Event Categories
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
    let participationTrendChart, popularCategoriesChart, topBarangaysChart, genderParticipationChart;

    // Initialize charts when document is ready
    $(document).ready(function() {
        loadParticipationTrendChart();
        loadPopularCategoriesChart();
        loadGenderParticipationChart();
        loadTopActiveSKOfficialsTable();
        loadTopActiveKKMembersTable();
        loadAttendanceConsistencyTable();
        
        <?php if ($view_type === 'citywide'): ?>
        // Initially load top barangays chart (for "All Barangays" view)
        loadTopBarangaysChart();
        
        // Add event listener for filter changes
        $('#barangayFilter').on('change', function() {
            const selectedValue = $(this).val();
            toggleTopBarangaysSection(selectedValue);
        });
        <?php endif; ?>
    });

    // Function to refresh all charts (for filter changes)
    function refreshCharts() {
        loadParticipationTrendChart();
        loadPopularCategoriesChart();
        loadGenderParticipationChart();
        loadTopActiveSKOfficialsTable();
        loadTopActiveKKMembersTable();
        loadAttendanceConsistencyTable();
        loadEventSummaryCards(); // Refresh summary cards with filtered data
        
        <?php if ($view_type === 'citywide'): ?>
        // Handle top barangays section visibility
        const selectedBarangay = $('#barangayFilter').val();
        toggleTopBarangaysSection(selectedBarangay);
        <?php endif; ?>
    }

    <?php if ($view_type === 'citywide'): ?>
    // Function to toggle Top Engaged Barangays section visibility and Gender Participation expansion
    function toggleTopBarangaysSection(selectedValue) {
        const topBarangaysSection = $('#topEngagedBarangaysSection');
        const genderParticipationSection = $('#genderParticipationSection');
        
        if (selectedValue === 'all') {
            // Show barangays section and make gender participation normal width
            topBarangaysSection.show();
            genderParticipationSection.removeClass('lg:col-span-2');
            loadTopBarangaysChart();
        } else {
            // Hide barangays section and expand gender participation to full width
            topBarangaysSection.hide();
            genderParticipationSection.addClass('lg:col-span-2');
            
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

    // Load Event Participation Trend Chart
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

        $.get(`${baseApiUrl}/event-participation-trend?${params.toString()}`)
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
                        title: {
                            text: 'Total Participants'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">Participants: </td>' +
                            '<td style="padding:0"><b>{point.y}</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    colors: ['#4A90E2'],
                    series: [{
                        name: 'Participants',
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
                $('#participationTrendChart').html('<div class="text-center text-gray-500">Error loading participation trend data</div>');
            });
    }

    // Load Most Popular Event Categories Chart
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

        $.get(`${baseApiUrl}/popular-event-categories?${params.toString()}`)
            .done(function(data) {
                popularCategoriesChart = Highcharts.chart('popularCategoriesChart', {
                    chart: {
                        type: 'bar'
                    },
                    title: {
                        text: null
                    },
                    xAxis: {
                        categories: data.categories,
                        title: {
                            text: 'Event Categories'
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Total Participants'
                        }
                    },
                    colors: ['#28a745'],
                    series: [{
                        name: 'Participants',
                        data: data.series,
                        dataLabels: {
                            enabled: true
                        }
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
                        type: 'bar'
                    },
                    title: {
                        text: null
                    },
                    xAxis: {
                        categories: data.categories,
                        title: {
                            text: 'Barangays'
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Total Participants'
                        }
                    },
                    colors: ['#6f42c1'],
                    series: [{
                        name: 'Participants',
                        data: data.series,
                        dataLabels: {
                            enabled: true
                        }
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
                        title: {
                            text: 'Participants'
                        },
                        stackLabels: {
                            enabled: true,
                            style: {
                                fontWeight: 'bold',
                                color: 'gray'
                            }
                        }
                    },
                    plotOptions: {
                        column: {
                            stacking: 'normal',
                            dataLabels: {
                                enabled: false
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
