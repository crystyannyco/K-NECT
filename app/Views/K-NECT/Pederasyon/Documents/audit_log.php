

<div class="max-w-7xl mx-auto p-0">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Header Section -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Document Audit Log
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Track all document activities and changes</p>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="window.location.reload()" class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Refresh
                    </button>
                    <a href="<?= base_url('admin/documents') ?>" class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Documents
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Activities</p>
                            <p class="text-2xl font-semibold text-gray-900"><?= count($logs) ?></p>
                        </div>
                        <div class="h-8 w-8 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Uploads</p>
                            <p class="text-2xl font-semibold text-gray-900"><?= count(array_filter($logs, fn($log) => in_array($log['action'], ['upload', 'version_upload']))) ?></p>
                        </div>
                        <div class="h-8 w-8 bg-blue-50 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Downloads</p>
                            <p class="text-2xl font-semibold text-gray-900"><?= count(array_filter($logs, fn($log) => $log['action'] === 'download')) ?></p>
                        </div>
                        <div class="h-8 w-8 bg-blue-50 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Other Actions</p>
                            <p class="text-2xl font-semibold text-gray-900"><?= count(array_filter($logs, fn($log) => !in_array($log['action'], ['upload', 'version_upload', 'download']))) ?></p>
                        </div>
                        <div class="h-8 w-8 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <?php if (empty($logs)): ?>
                <div class="text-center py-12">
                    <div class="mx-auto h-12 w-12 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No audit logs</h3>
                    <p class="mt-1 text-sm text-gray-500">No document activities have been recorded yet.</p>
                </div>
            <?php else: ?>
                <!-- Responsive Card Layout -->
                <div class="space-y-4">
                    <?php foreach ($logs as $log): ?>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between">
                                <!-- Left Side - Document Info -->
                                <div class="flex items-start space-x-3 flex-1 min-w-0">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <h3 class="text-sm font-medium text-gray-900 truncate" title="<?= esc($log['filename'] ?? 'Unknown Document') ?>">
                                                <?= esc($log['filename'] ?? 'Unknown Document') ?>
                                            </h3>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                <?php
                                                    switch($log['action']) {
                                                        case 'upload':
                                                        case 'version_upload':
                                                            echo 'bg-blue-100 text-blue-800';
                                                            break;
                                                        case 'download':
                                                            echo 'bg-gray-100 text-gray-800';
                                                            break;
                                                        case 'delete':
                                                            echo 'bg-red-100 text-red-800';
                                                            break;
                                                        default:
                                                            echo 'bg-gray-100 text-gray-800';
                                                    }
                                                ?>">
                                                <?= ucfirst(str_replace('_', ' ', $log['action'])) ?>
                                            </span>
                                        </div>
                                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                                            <span class="flex items-center space-x-1">
                                                <div class="h-6 w-6 bg-blue-600 rounded-full flex items-center justify-center">
                                                    <span class="text-xs font-medium text-white">
                                                        <?= strtoupper(substr(esc($log['performed_by']), 0, 1)) ?>
                                                    </span>
                                                </div>
                                                <span><?= esc($log['performed_by']) ?></span>
                                            </span>
                                            <span>ID: <?= esc($log['document_id']) ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Right Side - Timestamp -->
                                <div class="flex-shrink-0 ml-4 text-right">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= date('M d, Y', strtotime($log['performed_at'])) ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?= date('h:i A', strtotime($log['performed_at'])) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if (isset($pager) && $pager): ?>
        <div class="px-6 py-3 border-t border-gray-200">
            <div class="flex justify-center">
                <?= $pager->links('default', 'default_full') ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

 
