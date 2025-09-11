<!-- ===== MAIN CONTENT AREA ===== -->
<div class="flex-1 flex flex-col min-h-0 ml-64 pt-16">
    <main class="flex-1 overflow-auto p-6 bg-gray-50">
        
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900"><?= isset($page_title) ? $page_title : 'Demographics Analytics' ?></h1>
            <p class="text-gray-600">Comprehensive demographics analysis and insights</p>
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
                            <?php foreach ($barangays as $barangay): ?>
                                <option value="<?= $barangay['barangay_id'] ?>"><?= $barangay['name'] ?></option>
                            <?php endforeach; ?>
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
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Youth Members</p>
                        <p class="text-2xl font-bold text-gray-900"><?= number_format($summary['total_users']) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Male (<?= $summary['male_percentage'] ?>%)</p>
                        <p class="text-2xl font-bold text-gray-900"><?= number_format($summary['male_count']) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 bg-pink-100 rounded-lg">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Female (<?= $summary['female_percentage'] ?>%)</p>
                        <p class="text-2xl font-bold text-gray-900"><?= number_format($summary['female_count']) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Largest Age Group</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $summary['largest_age_group'] ?></p>
                        <p class="text-xs text-gray-500"><?= number_format($summary['largest_age_group_count']) ?> members</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Gender Distribution Chart -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Gender Distribution
                    </h3>
                </div>
                <div class="p-6">
                    <div id="genderChart" style="height: 300px;"></div>
                </div>
            </div>

            <!-- Age Group Distribution Chart -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Age Group Breakdown
                    </h3>
                </div>
                <div class="p-6">
                    <div id="ageChart" style="height: 300px;"></div>
                </div>
            </div>

            <!-- Youth Classification Chart -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Youth Classification
                    </h3>
                </div>
                <div class="p-6">
                    <div id="youthClassificationChart" style="height: 300px;"></div>
                </div>
            </div>

            <!-- Civil Status Distribution Chart -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Civil Status Distribution
                    </h3>
                </div>
                <div class="p-6">
                    <div id="civilStatusChart" style="height: 300px;"></div>
                </div>
            </div>

            <!-- Work Status Distribution Chart -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0H8m8 0v2a2 2 0 01-2 2H10a2 2 0 01-2-2V6"/>
                        </svg>
                        Work Status Distribution
                    </h3>
                </div>
                <div class="p-6">
                    <div id="workStatusChart" style="height: 300px;"></div>
                </div>
            </div>

            <!-- Educational Background Chart -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Educational Background
                    </h3>
                </div>
                <div class="p-6">
                    <div id="educationalBackgroundChart" style="height: 300px;"></div>
                </div>
            </div>

            <!-- Gender by Barangay Chart (Only for City-wide) -->
            <?php if ($view_type === 'citywide'): ?>
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Gender Distribution by Barangay
                    </h3>
                </div>
                <div class="p-6">
                    <div id="genderByBarangayChart" style="height: 300px;"></div>
                </div>
            </div>
            <?php endif; ?>
        </div>

    </main>
</div>

<!-- Include Highcharts -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Global variables
    const viewType = '<?= $view_type ?>';
    const baseApiUrl = viewType === 'citywide' ? '/analytics/pederasyon' : '/analytics/sk';

    // Chart instances
    let genderChart, ageChart, youthClassificationChart, civilStatusChart, workStatusChart, educationalBackgroundChart, genderByBarangayChart;

    // Initialize charts when document is ready
    $(document).ready(function() {
        loadGenderChart();
        loadAgeChart();
        loadYouthClassificationChart();
        loadCivilStatusChart();
        loadWorkStatusChart();
        loadEducationalBackgroundChart();
        
        <?php if ($view_type === 'citywide'): ?>
        loadGenderByBarangayChart();
        <?php endif; ?>
    });

    // Function to refresh all charts (for filter changes)
    function refreshCharts() {
        loadGenderChart();
        loadAgeChart();
        loadYouthClassificationChart();
        loadCivilStatusChart();
        loadWorkStatusChart();
        loadEducationalBackgroundChart();
    }

    // Load Gender Distribution Chart
    function loadGenderChart() {
        const params = new URLSearchParams({
            view_type: viewType
        });
        
        if (viewType === 'citywide') {
            const barangayId = $('#barangayFilter').val();
            if (barangayId) {
                params.append('barangay_id', barangayId);
            }
        }

        $.get(`${baseApiUrl}/gender-distribution?${params.toString()}`)
            .done(function(data) {
                genderChart = Highcharts.chart('genderChart', {
                    chart: {
                        type: 'pie'
                    },
                    title: {
                        text: null
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.y}</b> ({point.percentage:.1f}%)'
                    },
                    accessibility: {
                        point: {
                            valueSuffix: '%'
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                            },
                            showInLegend: true
                        }
                    },
                    colors: ['#4A90E2', '#E24A90'],
                    series: [{
                        name: 'Count',
                        colorByPoint: true,
                        data: data
                    }],
                    exporting: {
                        enabled: true
                    }
                });
            })
            .fail(function() {
                $('#genderChart').html('<div class="text-center text-gray-500">Error loading gender distribution data</div>');
            });
    }

    // Load Age Group Distribution Chart
    function loadAgeChart() {
        const params = new URLSearchParams({
            view_type: viewType
        });
        
        if (viewType === 'citywide') {
            const barangayId = $('#barangayFilter').val();
            if (barangayId && barangayId !== 'all') {
                params.append('barangay_id', barangayId);
            }
        }

        $.get(`${baseApiUrl}/age-distribution?${params.toString()}`)
            .done(function(data) {
                ageChart = Highcharts.chart('ageChart', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: null
                    },
                    xAxis: {
                        categories: data.categories,
                        title: {
                            text: 'Age Groups'
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Number of Youth'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y}</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
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
                    colors: ['#28a745'],
                    series: [{
                        name: 'Count',
                        data: data.series
                    }],
                    exporting: {
                        enabled: true
                    }
                });
            })
            .fail(function() {
                $('#ageChart').html('<div class="text-center text-gray-500">Error loading age distribution data</div>');
            });
    }

    // Load Youth Classification Chart
    function loadYouthClassificationChart() {
        const params = new URLSearchParams({
            view_type: viewType
        });
        
        if (viewType === 'citywide') {
            const barangayId = $('#barangayFilter').val();
            if (barangayId && barangayId !== 'all') {
                params.append('barangay_id', barangayId);
            }
        }

        $.get(`${baseApiUrl}/youth-classification?${params.toString()}`)
            .done(function(data) {
                youthClassificationChart = Highcharts.chart('youthClassificationChart', {
                    chart: {
                        type: 'pie'
                    },
                    title: {
                        text: null
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.y}</b> ({point.percentage:.1f}%)'
                    },
                    accessibility: {
                        point: {
                            valueSuffix: '%'
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                            },
                            showInLegend: true,
                            innerSize: '40%'
                        }
                    },
                    colors: ['#17a2b8', '#ffc107', '#dc3545', '#6c757d'],
                    series: [{
                        name: 'Count',
                        colorByPoint: true,
                        data: data
                    }],
                    exporting: {
                        enabled: true
                    }
                });
            })
            .fail(function() {
                $('#youthClassificationChart').html('<div class="text-center text-gray-500">Error loading youth classification data</div>');
            });
    }

    // Load Civil Status Distribution Chart
    function loadCivilStatusChart() {
        const params = new URLSearchParams({
            view_type: viewType
        });
        
        if (viewType === 'citywide') {
            const barangayId = $('#barangayFilter').val();
            if (barangayId && barangayId !== 'all') {
                params.append('barangay_id', barangayId);
            }
        }

        $.get(`${baseApiUrl}/civil-status?${params.toString()}`)
            .done(function(data) {
                civilStatusChart = Highcharts.chart('civilStatusChart', {
                    chart: {
                        type: 'pie'
                    },
                    title: {
                        text: null
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.y}</b> ({point.percentage:.1f}%)'
                    },
                    accessibility: {
                        point: {
                            valueSuffix: '%'
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                            },
                            showInLegend: true
                        }
                    },
                    colors: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6c757d', '#17a2b8'],
                    series: [{
                        name: 'Count',
                        colorByPoint: true,
                        data: data
                    }],
                    exporting: {
                        enabled: true
                    }
                });
            })
            .fail(function() {
                $('#civilStatusChart').html('<div class="text-center text-gray-500">Error loading civil status data</div>');
            });
    }

    // Load Work Status Distribution Chart
    function loadWorkStatusChart() {
        const params = new URLSearchParams({
            view_type: viewType
        });
        
        if (viewType === 'citywide') {
            const barangayId = $('#barangayFilter').val();
            if (barangayId && barangayId !== 'all') {
                params.append('barangay_id', barangayId);
            }
        }

        $.get(`${baseApiUrl}/work-status?${params.toString()}`)
            .done(function(data) {
                workStatusChart = Highcharts.chart('workStatusChart', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: null
                    },
                    xAxis: {
                        categories: data.categories,
                        title: {
                            text: 'Work Status'
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
                            text: 'Number of Youth'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y}</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
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
                        name: 'Count',
                        data: data.series
                    }],
                    exporting: {
                        enabled: true
                    }
                });
            })
            .fail(function() {
                $('#workStatusChart').html('<div class="text-center text-gray-500">Error loading work status data</div>');
            });
    }

    // Load Educational Background Distribution Chart
    function loadEducationalBackgroundChart() {
        const params = new URLSearchParams({
            view_type: viewType
        });
        
        if (viewType === 'citywide') {
            const barangayId = $('#barangayFilter').val();
            if (barangayId && barangayId !== 'all') {
                params.append('barangay_id', barangayId);
            }
        }

        $.get(`${baseApiUrl}/educational-background?${params.toString()}`)
            .done(function(data) {
                educationalBackgroundChart = Highcharts.chart('educationalBackgroundChart', {
                    chart: {
                        type: 'bar'
                    },
                    title: {
                        text: null
                    },
                    xAxis: {
                        categories: data.categories,
                        title: {
                            text: 'Educational Level'
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Number of Youth'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y}</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        bar: {
                            dataLabels: {
                                enabled: true
                            }
                        }
                    },
                    colors: ['#fd7e14'],
                    series: [{
                        name: 'Count',
                        data: data.series
                    }],
                    exporting: {
                        enabled: true
                    }
                });
            })
            .fail(function() {
                $('#educationalBackgroundChart').html('<div class="text-center text-gray-500">Error loading educational background data</div>');
            });
    }

    <?php if ($view_type === 'citywide'): ?>
    // Load Gender by Barangay Chart (City-wide only)
    function loadGenderByBarangayChart() {
        $.get('/analytics/pederasyon/gender-by-barangay')
            .done(function(data) {
                genderByBarangayChart = Highcharts.chart('genderByBarangayChart', {
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
                        title: {
                            text: 'Number of Youth'
                        },
                        stackLabels: {
                            enabled: true,
                            style: {
                                fontWeight: 'bold',
                                color: 'gray'
                            }
                        }
                    },
                    legend: {
                        align: 'right',
                        x: -30,
                        verticalAlign: 'top',
                        y: 25,
                        floating: true,
                        backgroundColor: 'white',
                        borderColor: '#CCC',
                        borderWidth: 1,
                        shadow: false
                    },
                    tooltip: {
                        headerFormat: '<b>{point.x}</b><br/>',
                        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
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
                $('#genderByBarangayChart').html('<div class="text-center text-gray-500">Error loading gender by barangay data</div>');
            });
    }
    <?php endif; ?>
</script>

<style>
    .bg-pink-100 {
        background-color: #fce7f3;
    }
    .text-pink-600 {
        color: #db2777;
    }
    .highcharts-container {
        font-family: inherit;
    }
</style>