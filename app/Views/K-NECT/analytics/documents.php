<!-- ===== MAIN CONTENT AREA ===== -->
<div class="flex-1 flex flex-col min-h-0 ml-64 pt-16">
    <main class="flex-1 overflow-auto p-6 bg-gray-50">
        
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900"><?= isset($page_title) ? $page_title : 'Document Analytics' ?></h1>
            <p class="text-gray-600">Document usage and governance analytics</p>
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
                            <option value="city-wide">City-wide</option>
                            <?php if (isset($barangays)): ?>
                                <?php foreach ($barangays as $barangay): ?>
                                    <option value="<?= $barangay['barangay_id'] ?>"><?= $barangay['name'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
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
            <div class="bg-white rounded-lg shadow-sm p-6 summary-card" data-metric="total_approved_documents">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-sm font-medium text-gray-500 metric-label">Approved Documents</h3>
                        <p class="text-2xl font-bold text-gray-900 metric-value"><?= $document_summary['total_approved_documents'] ?? 0 ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 summary-card" data-metric="total_pending_documents">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-sm font-medium text-gray-500 metric-label">Pending Approval</h3>
                        <p class="text-2xl font-bold text-gray-900 metric-value"><?= $document_summary['total_pending_documents'] ?? 0 ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 summary-card" data-metric="total_downloads">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-sm font-medium text-gray-500 metric-label">Total Downloads</h3>
                        <p class="text-2xl font-bold text-gray-900 metric-value"><?= $document_summary['total_downloads'] ?? 0 ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 summary-card" data-metric="avg_approval_time_days">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-sm font-medium text-gray-500 metric-label">Avg Approval Time</h3>
                        <p class="text-2xl font-bold text-gray-900 metric-value"><?= round($document_summary['avg_approval_time_days'] ?? 0, 1) ?> days</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Most Accessed Document Categories -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                        </svg>
                        Most Accessed Document Categories
                    </h3>
                </div>
                <div class="p-6">
                    <div id="documentCategoriesChart" style="height: 300px;"></div>
                </div>
            </div>

            <!-- Document Approval Time -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Document Approval Time Distribution
                    </h3>
                </div>
                <div class="p-6">
                    <div id="approvalTimeChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>

        <!-- Top Downloaded Documents Table -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m-12 4h18a2 2 0 002-2V7a2 2 0 00-2-2H3a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Top Downloaded Documents
                </h3>
            </div>
            <div class="p-6">
                <div id="topDocumentsTable" class="overflow-x-auto">
                    <div class="text-center text-gray-500">Loading...</div>
                </div>
            </div>
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
    let documentCategoriesChart, approvalTimeChart;

    // Initialize charts when document is ready
    $(document).ready(function() {
        loadDocumentCategoriesChart();
        loadApprovalTimeChart();
        loadTopDocumentsTable();
    });

    // Function to refresh all charts (for filter changes)
    function refreshCharts() {
        loadDocumentCategoriesChart();
        loadApprovalTimeChart();
        loadTopDocumentsTable();
        loadDocumentSummaryCards(); // Refresh summary cards with filtered data
    }

    // Load Document Summary Cards
    function loadDocumentSummaryCards() {
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

        $.get(`${baseApiUrl}/document-summary?${params.toString()}`)
            .done(function(data) {
                // Update each summary card based on its data-metric attribute
                $('.summary-card').each(function() {
                    const $card = $(this);
                    const metric = $card.data('metric');
                    const $valueElement = $card.find('.metric-value');
                    
                    if (data[metric] !== undefined) {
                        if (metric === 'avg_approval_time_days') {
                            $valueElement.text(Math.round(data[metric] * 10) / 10 + ' days');
                        } else {
                            $valueElement.text(data[metric] || 0);
                        }
                    }
                });
            })
            .fail(function() {
                console.error('Failed to load document summary cards');
            });
    }

    // Load Most Accessed Document Categories Chart
    function loadDocumentCategoriesChart() {
        const params = new URLSearchParams({
            view_type: viewType
        });
        
        if (viewType === 'citywide') {
            const barangayId = $('#barangayFilter').val();
            if (barangayId) {
                params.append('barangay_id', barangayId);
            }
        }

        $.get(`${baseApiUrl}/document-categories?${params.toString()}`)
            .done(function(data) {
                documentCategoriesChart = Highcharts.chart('documentCategoriesChart', {
                    chart: {
                        type: 'bar'
                    },
                    title: {
                        text: null
                    },
                    xAxis: {
                        categories: data.categories,
                        title: {
                            text: 'Document Categories'
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Total Downloads'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">Downloads: </td>' +
                            '<td style="padding:0"><b>{point.y}</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    colors: ['#28a745'],
                    series: [{
                        name: 'Downloads',
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
                $('#documentCategoriesChart').html('<div class="text-center text-gray-500">Error loading document categories data</div>');
            });
    }

    // Load Document Approval Time Chart
    function loadApprovalTimeChart() {
        const params = new URLSearchParams({
            view_type: viewType
        });
        
        if (viewType === 'citywide') {
            const barangayId = $('#barangayFilter').val();
            if (barangayId) {
                params.append('barangay_id', barangayId);
            }
        }

        $.get(`${baseApiUrl}/document-approval-time?${params.toString()}`)
            .done(function(data) {
                approvalTimeChart = Highcharts.chart('approvalTimeChart', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: null
                    },
                    xAxis: {
                        categories: data.categories,
                        title: {
                            text: 'Approval Time Range'
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Number of Documents'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">Documents: </td>' +
                            '<td style="padding:0"><b>{point.y}</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    colors: ['#6f42c1'],
                    series: [{
                        name: 'Documents',
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
                $('#approvalTimeChart').html('<div class="text-center text-gray-500">Error loading approval time data</div>');
            });
    }

    // Load Top Downloaded Documents Table
    function loadTopDocumentsTable() {
        $.get(`${baseApiUrl}/top-downloaded-documents`)
            .done(function(data) {
                let tableHtml = `
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Downloads</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                `;
                
                data.forEach((document, index) => {
                    const uploadDate = new Date(document.uploaded_at).toLocaleDateString();
                    
                    tableHtml += `
                        <tr class="${index < 3 ? 'bg-yellow-50' : ''}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                ${index < 3 ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">' + (index + 1) + '</span>' : (index + 1)}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="max-w-xs truncate" title="${document.filename}">
                                    ${document.filename}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${document.category || 'Uncategorized'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    ${document.download_count}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${uploadDate}</td>
                        </tr>
                    `;
                });
                
                if (data.length === 0) {
                    tableHtml += `
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No documents found</td>
                        </tr>
                    `;
                }
                
                tableHtml += '</tbody></table>';
                $('#topDocumentsTable').html(tableHtml);
            })
            .fail(function() {
                $('#topDocumentsTable').html('<div class="text-center text-gray-500">Error loading top documents data</div>');
            });
    }
</script>
