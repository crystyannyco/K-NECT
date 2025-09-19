<!-- ===== MAIN CONTENT AREA ===== -->
<div class="flex-1 flex flex-col min-h-0 ml-64 pt-16">
    <main class="flex-1 overflow-auto p-6 bg-gray-50">
        
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900"><?= isset($page_title) ? $page_title : 'Performance Analytics' ?></h1>
            <p class="text-gray-600">Strategic performance insights and forecasting</p>
        </div>

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
                        <label for="inactiveDaysFilter" class="block text-sm font-medium text-gray-700 mb-2">Inactive Period (Days):</label>
                        <select id="inactiveDaysFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="30">30 days</option>
                            <option value="60">60 days</option>
                            <option value="90" selected>90 days</option>
                            <option value="120">120 days</option>
                            <option value="180">180 days</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Barangay Performance Score -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Barangay Performance Score
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Multi-dimensional performance assessment</p>
                </div>
                <div class="p-6">
                    <div id="performanceScoreChart" style="height: 400px;"></div>
                </div>
            </div>

            <!-- Participation Forecast -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        Participation Forecast
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Predictive analysis based on trends</p>
                </div>
                <div class="p-6">
                    <div id="participationForecastChart" style="height: 400px;"></div>
                </div>
            </div>
        </div>

        <?php if ($view_type !== 'citywide'): ?>
        <!-- Inactive Members Table -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Inactive Members
                </h3>
                <p class="text-sm text-gray-600 mt-1">Members who haven't attended events recently</p>
            </div>
            <div class="p-6">
                <div id="inactiveMembersTable" class="overflow-x-auto">
                    <div class="text-center text-gray-500">Loading...</div>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </main>
</div>

<!-- Include Highcharts -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Global variables
    const viewType = '<?= $view_type ?>';
    const baseApiUrl = viewType === 'citywide' ? '/analytics/pederasyon' : '/analytics/sk';

    // Chart instances
    let performanceScoreChart, participationForecastChart;

    // Barangay Color Palette Generator
    function getBarangayColorPalette() {
        return [
            '#1f77b4', // Blue
            '#ff7f0e', // Orange
            '#2ca02c', // Green
            '#d62728', // Red
            '#9467bd', // Purple
            '#8c564b', // Brown
            '#e377c2', // Pink
            '#7f7f7f', // Gray
            '#bcbd22', // Olive
            '#17becf', // Cyan
            '#aec7e8', // Light Blue
            '#ffbb78', // Light Orange
            '#98df8a', // Light Green
            '#ff9896', // Light Red
            '#c5b0d5', // Light Purple
            '#c49c94', // Light Brown
            '#f7b6d3', // Light Pink
            '#c7c7c7', // Light Gray
            '#dbdb8d', // Light Olive
            '#9edae5', // Light Cyan
            '#393b79', // Dark Blue
            '#637939', // Dark Green
            '#8c6d31', // Dark Brown
            '#843c39', // Dark Red
            '#7b4173', // Dark Purple
            '#5254a3', // Steel Blue
            '#6b6ecf', // Medium Blue
            '#9c9ede', // Light Steel Blue
            '#bd9e39', // Gold
            '#e7ba52'  // Light Gold
        ];
    }

    // Get consistent color for a barangay name
    function getBarangayColor(barangayName, index = 0) {
        const colors = getBarangayColorPalette();
        if (typeof barangayName === 'string') {
            // Generate a consistent color based on barangay name hash
            let hash = 0;
            for (let i = 0; i < barangayName.length; i++) {
                hash = barangayName.charCodeAt(i) + ((hash << 5) - hash);
            }
            return colors[Math.abs(hash) % colors.length];
        }
        // Fallback to index-based color
        return colors[index % colors.length];
    }

    // Initialize charts when document is ready
    $(document).ready(function() {
        loadPerformanceScoreChart();
        loadParticipationForecastChart();
        loadInactiveMembersTable();
        
        <?php if ($view_type === 'citywide'): ?>
        // Add event listener for barangay filter changes
        $('#barangayFilter').on('change', function() {
            console.log('Barangay filter changed to:', $(this).val());
            refreshCharts(); // Automatically refresh charts when filter changes
        });
        
        // Add event listener for inactive days filter changes
        $('#inactiveDaysFilter').on('change', function() {
            console.log('Inactive days filter changed to:', $(this).val());
            refreshCharts(); // Automatically refresh charts when filter changes
        });
        <?php endif; ?>
    });

    // Function to refresh all charts (for filter changes)
    function refreshCharts() {
        loadPerformanceScoreChart();
        loadParticipationForecastChart(); // Include participation forecast in refresh
        loadInactiveMembersTable();
    }

    // Load Barangay Performance Score Chart
    function loadPerformanceScoreChart() {
        const params = new URLSearchParams({
            view_type: viewType
        });
        
        if (viewType === 'citywide') {
            const barangayId = $('#barangayFilter').val();
            if (barangayId && barangayId !== 'all') {
                params.append('barangay_id', barangayId);
            }
        }

        $.get(`${baseApiUrl}/barangay-performance-score?${params.toString()}`)
            .done(function(data) {
                console.log('Performance score data received:', data);
                
                // Check if data is valid
                if (!data || !Array.isArray(data)) {
                    console.error('Invalid data format received:', data);
                    $('#performanceScoreChart').html('<div class="text-center text-gray-500">No performance data available</div>');
                    return;
                }
                
                // Check if any data returned
                if (data.length === 0) {
                    const emptyMessage = viewType === 'citywide' 
                        ? 'No barangays found in the system.'
                        : 'No performance data available for this barangay.';
                    const emptyDetail = viewType === 'citywide'
                        ? 'Please add barangays to the system first.'
                        : 'Data appears when this barangay has published events or uploaded documents.';
                        
                    $('#performanceScoreChart').html(`
                        <div class="text-center text-gray-500 py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Performance Data Available</h3>
                            <p class="text-gray-600">${emptyMessage}</p>
                            <p class="text-sm text-gray-500 mt-2">${emptyDetail}</p>
                        </div>
                    `);
                    return;
                }
                
                // Prepare data for radar chart
                const categories = ['Event Participation', 'Document Activity', 'Attendance Consistency'];
                const seriesData = [];
                
                data.forEach(function(barangay, index) {
                    // Ensure values are within 0-100 range and handle null/undefined values
                    const eventScore = Math.min(100, Math.max(0, parseFloat(barangay.event_participation_score) || 0));
                    const documentScore = Math.min(100, Math.max(0, parseFloat(barangay.document_activity_score) || 0));
                    const attendanceScore = Math.min(100, Math.max(0, parseFloat(barangay.attendance_consistency_score) || 0));
                    
                    seriesData.push({
                        name: barangay.barangay,
                        data: [eventScore, documentScore, attendanceScore],
                        pointPlacement: 'on',
                        connectNulls: true,
                        color: getBarangayColor(barangay.barangay, index) // Assign unique color for each barangay
                    });
                });
                
                console.log('Series data prepared:', seriesData);

                performanceScoreChart = Highcharts.chart('performanceScoreChart', {
                    chart: {
                        polar: true,
                        type: 'line'
                    },
                    title: {
                        text: null
                    },
                    pane: {
                        size: '80%',
                        startAngle: 0
                    },
                    xAxis: {
                        categories: categories,
                        tickmarkPlacement: 'on',
                        lineWidth: 0
                    },
                    yAxis: {
                        gridLineInterpolation: 'polygon',
                        lineWidth: 0,
                        min: 0,
                        max: 100,
                        tickInterval: 20,
                        labels: {
                            formatter: function() {
                                return this.value + '%';
                            }
                        }
                    },
                    plotOptions: {
                        series: {
                            connectNulls: true,
                            lineWidth: 2,
                            pointPlacement: 'on',
                            marker: {
                                enabled: true,
                                radius: 4
                            }
                        }
                    },
                    tooltip: {
                        shared: true,
                        pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:.1f}%</b><br/>'
                    },
                    legend: {
                        align: 'right',
                        verticalAlign: 'middle',
                        layout: 'vertical',
                        maxHeight: 200,
                        navigation: {
                            enabled: true
                        }
                    },
                    series: seriesData,
                    responsive: {
                        rules: [{
                            condition: {
                                maxWidth: 500
                            },
                            chartOptions: {
                                legend: {
                                    align: 'center',
                                    verticalAlign: 'bottom',
                                    layout: 'horizontal'
                                },
                                pane: {
                                    size: '70%'
                                }
                            }
                        }]
                    },
                    exporting: {
                        enabled: true
                    }
                });
            })
            .fail(function(xhr, status, error) {
                console.error('Failed to load performance score data:', {
                    status: status,
                    error: error,
                    response: xhr.responseText,
                    url: `${baseApiUrl}/barangay-performance-score?${params.toString()}`
                });
                $('#performanceScoreChart').html('<div class="text-center text-gray-500">Error loading performance score data. Please check browser console for details.</div>');
            });
    }

    // Load Participation Forecast Chart
    function loadParticipationForecastChart() {
        // Build parameters for API call with barangay filtering support
        const params = new URLSearchParams({
            view_type: viewType,
            months: 24
        });
        
        if (viewType === 'citywide') {
            const barangayId = $('#barangayFilter').val();
            if (barangayId && barangayId !== 'all') {
                params.append('barangay_id', barangayId);
            }
        }

        // First get historical data for trend calculation
        $.get(`${baseApiUrl}/event-participation-trend?${params.toString()}`)
            .done(function(data) {
                const historicalData = data.series[0] || [];
                const categories = data.categories || [];
                
                // Simple linear regression for forecasting next 6 months
                const forecastPeriods = 6;
                const forecastData = [];
                const forecastCategories = [];
                
                // Calculate trend
                if (historicalData.length >= 3) {
                    const n = historicalData.length;
                    const sumX = (n * (n + 1)) / 2;
                    const sumY = historicalData.reduce((a, b) => a + b, 0);
                    const sumXY = historicalData.reduce((sum, y, x) => sum + (x + 1) * y, 0);
                    const sumX2 = (n * (n + 1) * (2 * n + 1)) / 6;
                    
                    const slope = (n * sumXY - sumX * sumY) / (n * sumX2 - sumX * sumX);
                    const intercept = (sumY - slope * sumX) / n;
                    
                    // Generate forecast
                    for (let i = 1; i <= forecastPeriods; i++) {
                        const forecastValue = Math.max(0, Math.round(slope * (n + i) + intercept));
                        forecastData.push(forecastValue);
                        
                        // Generate forecast month names (simplified)
                        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                        const currentMonth = new Date().getMonth();
                        const forecastMonth = (currentMonth + i) % 12;
                        const forecastYear = new Date().getFullYear() + Math.floor((currentMonth + i) / 12);
                        forecastCategories.push(`${monthNames[forecastMonth]} ${forecastYear}`);
                    }
                }
                
                // Combine historical and forecast categories
                const allCategories = [...categories, ...forecastCategories];
                
                participationForecastChart = Highcharts.chart('participationForecastChart', {
                    chart: {
                        type: 'line'
                    },
                    title: {
                        text: null
                    },
                    xAxis: {
                        categories: allCategories,
                        title: {
                            text: 'Month'
                        },
                        plotLines: [{
                            color: '#ff0000',
                            dashStyle: 'dash',
                            value: categories.length - 0.5,
                            width: 2,
                            label: {
                                text: 'Forecast',
                                align: 'right',
                                style: {
                                    color: '#ff0000'
                                }
                            }
                        }]
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Participants'
                        }
                    },
                    tooltip: {
                        shared: true,
                        crosshairs: true
                    },
                    series: [{
                        name: 'Historical Data',
                        data: historicalData,
                        color: '#4A90E2',
                        marker: {
                            enabled: true,
                            radius: 4
                        }
                    }, {
                        name: 'Forecast',
                        data: Array(categories.length).fill(null).concat(forecastData),
                        color: '#E24A90',
                        dashStyle: 'dash',
                        marker: {
                            enabled: true,
                            radius: 4,
                            symbol: 'diamond'
                        }
                    }],
                    exporting: {
                        enabled: true
                    }
                });
            })
            .fail(function() {
                $('#participationForecastChart').html('<div class="text-center text-gray-500">Error loading forecast data</div>');
            });
    }

    // Load Inactive Members Table (available for all view types)
    function loadInactiveMembersTable() {
        const params = new URLSearchParams({
            view_type: viewType
        });
        
        if (viewType === 'citywide') {
            const barangayId = $('#barangayFilter').val();
            const inactiveDays = $('#inactiveDaysFilter').val();
            if (barangayId && barangayId !== 'all') {
                params.append('barangay_id', barangayId);
            }
            if (inactiveDays) {
                params.append('inactive_days', inactiveDays);
            }
        }

        $.get(`${baseApiUrl}/inactive-members?${params.toString()}`)
            .done(function(data) {
                let tableHtml = `
                    <div class="mb-4">
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Found <strong>${data.length}</strong> inactive member(s). Consider reaching out to re-engage them.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                ${viewType === 'citywide' ? '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barangay</th>' : ''}
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Event Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                `;
                
                if (data.length > 0) {
                    data.forEach((member) => {
                        const lastEventDate = member.last_event_date !== 'Never attended' ? 
                            new Date(member.last_event_date).toLocaleDateString() : 
                            'Never attended';
                        
                        const statusColor = member.last_event_date === 'Never attended' ? 
                            'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800';
                        
                        tableHtml += `
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${member.name}</td>
                                ${viewType === 'citywide' ? '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' + (member.barangay || 'N/A') + '</td>' : ''}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${lastEventDate}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusColor}">
                                        ${member.last_activity}
                                    </span>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    tableHtml += `
                        <tr>
                            <td colspan="${viewType === 'citywide' ? '4' : '3'}" class="px-6 py-4 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-lg font-medium">Great news!</p>
                                    <p>No inactive members found. Everyone is actively participating!</p>
                                </div>
                            </td>
                        </tr>
                    `;
                }
                
                tableHtml += '</tbody></table>';
                $('#inactiveMembersTable').html(tableHtml);
            })
            .fail(function() {
                $('#inactiveMembersTable').html('<div class="text-center text-gray-500">Error loading inactive members data</div>');
            });
    }
</script>
