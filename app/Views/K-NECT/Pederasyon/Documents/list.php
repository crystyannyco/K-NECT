<?php
// SKP Documents List View for Super Admin
// Safe defaults for optional filters to prevent undefined variable notices
$selectedStatus = $selectedStatus ?? ($_GET['status'] ?? '');
$selectedDate   = $selectedDate   ?? ($_GET['date_filter'] ?? '');
?>
<style>
/* Completely hide scrollbars from PDF previews */
.preview-area {
    position: relative;
}
.preview-area iframe,
.preview-area embed {
    scrollbar-width: none !important; 
    -ms-overflow-style: none !important; 
    overflow: hidden !important;
    border: none !important;
    margin: -10px !important; /* Crop out scrollbar area */
    width: calc(100% + 20px) !important;
    height: calc(100% + 20px) !important;
}
.preview-area iframe::-webkit-scrollbar,
.preview-area embed::-webkit-scrollbar {
    width: 0px !important;
    height: 0px !important;
    display: none !important;
    background: transparent !important;
}
</style>
<?php
// Safe pagination defaults to prevent undefined variables
$perPage = isset($perPage) ? (int) $perPage : (isset($_GET['per_page']) ? (int) $_GET['per_page'] : 10);
$page = isset($page) ? (int) $page : (isset($_GET['page']) ? (int) $_GET['page'] : 1);
$total = isset($total) ? (int) $total : ((isset($documents) && is_array($documents)) ? count($documents) : 0);
$currentPage = max(1, (int) $page);
$start = ($currentPage - 1) * $perPage;
$totalPages = (int) max(1, ceil($total / max(1, $perPage)));
?>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto p-0">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 space-y-4 sm:space-y-6">
                <!-- Header Section -->
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-2">
        <div>
                    <h1 class="text-2xl font-bold text-blue-900 tracking-tight flex items-center gap-2 drop-shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7V3a1 1 0 011-1h8a1 1 0 011 1v4m-2 4h2a2 2 0 012 2v7a2 2 0 01-2 2H7a2 2 0 01-2-2v-7a2 2 0 012-2h2m2 0V3" />
                        </svg>
                        Document Management
                    </h1>
                    <div class="text-sm text-blue-700 mt-1 font-medium opacity-80">Super Admin - Complete document control and approval system</div>
        </div>
        <div class="flex items-center gap-3">
                    <button onclick="openCategoryModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:bg-green-700 transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Manage Categories
                    </button>
                    <a href="<?= base_url('admin/documents/upload') ?>" class="bg-gradient-to-r from-blue-500 to-blue-400 text-white px-6 py-2 rounded-xl font-bold shadow-lg hover:from-blue-600 hover:to-blue-500 transition-all text-base flex items-center gap-2 border-2 border-blue-200 hover:border-blue-400 focus:ring-2 focus:ring-blue-200 outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Upload Document
            </a>
        </div>
    </div>

                <!-- Search and Filter Section -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 lg:p-6 shadow-lg border border-blue-100">
                    <form method="GET" action="<?= base_url('admin/documents') ?>" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                            <!-- Search Input -->
                            <div class="md:col-span-2 lg:col-span-2 xl:col-span-2">
                                <div class="relative">
                                    <input type="text" name="search" value="<?= esc($search ?? '') ?>" 
                                           placeholder="Search documents..." 
                                           class="w-full pl-10 pr-4 py-2.5 border border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-sm bg-white shadow-sm">
                                    <svg class="absolute left-3 top-3 h-4 w-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Status Filter -->
                            <div class="lg:col-span-1">
                                <select name="status" class="w-full px-3 py-2.5 border border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-sm bg-white shadow-sm">
                                    <option value="">All Status</option>
                                    <option value="pending" <?= $selectedStatus === 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="approved" <?= $selectedStatus === 'approved' ? 'selected' : '' ?>>Approved</option>
                                    <option value="rejected" <?= $selectedStatus === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                </select>
                            </div>

                            <!-- Category Filter -->
                            <div class="lg:col-span-1">
                                <select name="category_id" class="w-full px-3 py-2.5 border border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-sm bg-white shadow-sm">
                                    <option value="">All Categories</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= $selectedCategory == $cat['id'] ? 'selected' : '' ?>>
                                            <?= esc($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-2 lg:col-span-1">
                                <button type="submit" class="w-full sm:flex-1 bg-blue-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors flex items-center justify-center gap-2 shadow-sm">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Search</span>
                                </button>
                                <?php if (!empty($_GET['search']) || !empty($_GET['category']) || !empty($_GET['status']) || !empty($_GET['date_filter'])): ?>
                                <a href="<?= base_url('admin/documents') ?>" class="w-full sm:flex-1 bg-gray-500 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-600 transition-colors flex items-center justify-center gap-2 shadow-sm">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Reset</span>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Date Filter Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="md:col-span-1">
                                <select name="date_filter" class="w-full px-3 py-2.5 border border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-sm bg-white shadow-sm">
                                    <option value="">All Time</option>
                                    <option value="today" <?= $selectedDate === 'today' ? 'selected' : '' ?>>Today</option>
                                    <option value="week" <?= $selectedDate === 'week' ? 'selected' : '' ?>>This Week</option>
                                    <option value="month" <?= $selectedDate === 'month' ? 'selected' : '' ?>>This Month</option>
                                    <option value="year" <?= $selectedDate === 'year' ? 'selected' : '' ?>>This Year</option>
                                </select>
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>

<script>
// Toast notification function - defined early for flash messages
function showSuccessToast(message) {
    // Create toast container if it doesn't exist
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(toastContainer);
    }
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-3 transform translate-x-full opacity-0 transition-all duration-300 ease-out max-w-sm';
    toast.innerHTML = `
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="flex-1">
            <p class="font-medium text-sm">${message}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="flex-shrink-0 text-white hover:text-green-200 transition-colors">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    `;
    
    // Add to container
    toastContainer.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
    }, 100);
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 300);
    }, 5000);
}
</script>

<?php if (session()->getFlashdata('success')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showSuccessToast('<?= addslashes(session()->getFlashdata('success')) ?>');
        });
    </script>
<?php endif; ?>

<!-- Main Content Area -->
<div class="max-w-7xl mx-auto p-0">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200">
        <div class="px-6 py-4 space-y-4 sm:space-y-6">
            <!-- Results Summary -->
            <div class="flex items-center justify-between">
                <div class="text-xs text-gray-600">
                    Showing <?= $start + 1 ?> to <?= min($start + $perPage, $total) ?> of <?= $total ?> documents
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-600">Items per page:</span>
                    <select id="perPage" class="text-xs border border-gray-300 rounded px-2 py-1 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="10" <?= ($perPage == 10) ? 'selected' : '' ?>>10</option>
                        <option value="25" <?= ($perPage == 25) ? 'selected' : '' ?>>25</option>
                        <option value="50" <?= ($perPage == 50) ? 'selected' : '' ?>>50</option>
                        <option value="100" <?= ($perPage == 100) ? 'selected' : '' ?>>100</option>
                    </select>
                </div>
            </div>

            <!-- Bulk Operations Section -->
            <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg border">
        <div class="flex items-center gap-4">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" id="selectAllDocs" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <span class="text-sm font-medium text-gray-700">Select All</span>
            </label>
            <span id="selectedCount" class="text-sm text-gray-600">0 selected</span>
        </div>
        <div id="bulkActions" class="flex items-center gap-2 transition-all duration-300 opacity-0 scale-95 transform">
            <button id="bulkDownload" class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors disabled:opacity-50 flex items-center gap-1" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Download
            </button>
            <button id="bulkDelete" class="bg-red-600 text-white px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors disabled:opacity-50 flex items-center gap-1" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete
            </button>
        </div>
    </div>

    <!-- Documents List -->
    <?php if (!empty($documents)): ?>
    <div class="space-y-4">
        <?php foreach ($documents as $doc): ?>
                    <?php
        // Get uploader role for access control using unified model
        $uploaderRole = null;
        if (!empty($doc['uploaded_by'])) {
            $uploaderRole = $userRoles[strtolower(trim($doc['uploaded_by']))] ?? null;
        }
        
        $previewUrl = base_url('admin/documents/preview/' . $doc['id']);
        $isImage = strpos($doc['mimetype'], 'image/') === 0;
        $isPdf = $doc['mimetype'] === 'application/pdf';
        ?>
        
        <div class="flex flex-col sm:flex-row items-start sm:items-center bg-white rounded-lg shadow-sm p-3 sm:p-4 border border-gray-100 hover:shadow-md transition-all duration-300 relative">
            <!-- Document Checkbox -->
            <div class="absolute top-2 left-2 z-20">
                <input type="checkbox" 
                       class="document-checkbox w-3.5 h-3.5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                       value="<?= $doc['id'] ?>" 
                       data-filename="<?= esc($doc['filename'] ?? 'document') ?>"
                       data-filepath="<?= esc($doc['filepath'] ?? '') ?>"
                       data-uploader="<?= esc($doc['uploaded_by'] ?? '') ?>"
                       data-uploader-role="<?= esc($uploaderRole ?? '') ?>">
            </div>
            
            <!-- Preview -->
            <div class="preview-area flex-shrink-0 w-20 h-24 sm:w-24 sm:h-28 flex items-center justify-center bg-gray-50 rounded-md border border-gray-200 overflow-hidden mr-0 sm:mr-3 mb-3 sm:mb-0 relative">
                <?php if (!empty($doc['thumbnail_path']) && file_exists(FCPATH . $doc['thumbnail_path'])): ?>
                    <img src="<?= base_url('uploads/thumbnails/' . basename($doc['thumbnail_path'])) ?>" alt="PDF Preview" class="object-contain w-full h-full" />
                <?php elseif ($isImage): ?>
                    <img src="<?= $previewUrl ?>" alt="Preview" class="object-contain w-full h-full" />
                <?php elseif ($isPdf): ?>
                    <div class="w-full h-full relative overflow-hidden">
                        <iframe src="<?= $previewUrl ?>#toolbar=0&navpanes=0&scrollbar=0&page=1&view=FitH" 
                                class="absolute inset-0 w-full h-full border-0" 
                                scrolling="no"
                                style="pointer-events: none; transform: scale(1.1); transform-origin: top left;"></iframe>
                        <!-- Overlay to hide any remaining scrollbar -->
                        <div class="absolute right-0 top-0 w-4 h-full bg-white z-10"></div>
                        <div class="absolute bottom-0 left-0 w-full h-4 bg-white z-10"></div>
                    </div>
                <?php else: ?>
                    <img src="<?= base_url('assets/images/file-not-found.svg') ?>" alt="No preview available" class="object-contain w-full h-full" />
                <?php endif; ?>
            </div>
            
            <!-- Info -->
            <div class="flex-1 flex flex-col gap-1.5 sm:gap-2 min-w-0 w-full">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <h2 class="text-sm sm:text-base font-semibold text-blue-900 truncate">
                        <a href="javascript:void(0)" onclick="openDocumentModal(<?= $doc['id'] ?>)" class="hover:underline cursor-pointer">
                            <?= esc($doc['filename'] ?? 'Untitled document') ?>
                        </a>
                    </h2>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="px-2 py-1 rounded-full text-xs font-medium <?= $doc['approval_status'] === 'approved' ? 'bg-green-100 text-green-700' : ($doc['approval_status'] === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') ?>">
                            <?= ucfirst($doc['approval_status']) ?>
                        </span>
                        <?php if (session('role') === 'super_admin' && $doc['approval_status'] === 'pending'): ?>
                        <div class="flex gap-1">
                            <form action="<?= base_url('admin/documents/approve/' . $doc['id']) ?>" method="post" class="approve-form">
                                <?= csrf_field() ?>
                                <button type="submit" class="bg-green-500 text-white hover:bg-green-600 px-2 py-1 rounded text-xs font-medium flex items-center gap-1 transition focus:outline-none focus:ring-2 focus:ring-green-200" title="Approve document">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="hidden sm:inline">Approve</span>
                                </button>
                            </form>
                            <form action="<?= base_url('admin/documents/reject/' . $doc['id']) ?>" method="post" class="reject-form">
                                <?= csrf_field() ?>
                                <button type="submit" class="bg-red-500 text-white hover:bg-red-600 px-2 py-1 rounded text-xs font-medium flex items-center gap-1 transition focus:outline-none focus:ring-2 focus:ring-red-200" title="Reject document">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span class="hidden sm:inline">Reject</span>
                                </button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="text-gray-600 text-xs mt-1 flex flex-wrap gap-x-3 gap-y-1">
                    <span><span class="font-medium">Type:</span> <?= esc($doc['mimetype']) ?></span>
                    <span><span class="font-medium">Size:</span> <?= number_format($doc['filesize']/1024, 2) ?> KB</span>
                    <span><span class="font-medium">Uploaded:</span> <?php $createdAt = $doc['created_at'] ?? ($doc['uploaded_at'] ?? null); echo $createdAt ? date('M j, Y', strtotime($createdAt)) : '—'; ?></span>
                </div>

                <div class="flex items-center gap-2 mt-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-700 font-semibold text-xs">
                        <?= strtoupper(substr(esc($doc['uploaded_by']), 0, 1)) ?>
                    </span>
                    <span class="text-gray-700 font-medium text-sm"><?= esc($doc['uploaded_by']) ?></span>
                    <?php if ($uploaderRole): ?>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 border border-blue-200 uppercase font-medium">
                        <?= esc($uploaderRole) ?>
                    </span>
                    <?php endif; ?>
                </div>

                <?php if (!empty($doc['category_name'])): ?>
                <div class="flex items-center gap-2 mt-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span class="text-blue-600 font-medium text-xs"><?= esc($doc['category_name']) ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($doc['tags'])): ?>
                <div class="flex flex-wrap gap-1 mt-1.5">
                    <?php foreach (explode(',', $doc['tags']) as $tag): ?>
                        <span class="inline-block bg-blue-100 text-blue-600 px-1.5 py-0.5 rounded text-xs font-medium">#<?= esc(trim($tag)) ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Actions -->
            <div class="relative ml-auto mt-2 sm:mt-0" x-data="{ open: false }">
                <button @click="open = !open" 
                        @click.away="open = false"
                        class="inline-flex items-center justify-center w-7 h-7 rounded-md text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors"
                        title="Actions">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                    </svg>
                </button>
                
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-1 w-44 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                    
                    <a href="<?= base_url('admin/documents/download/' . $doc['id']) ?>" 
                       class="flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download
                    </a>
                    
                    <a href="javascript:void(0)" onclick="openDocumentModal(<?= $doc['id'] ?>)" 
                                    class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors w-full text-left cursor-pointer">
                                     <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                         </svg>
                         View Details
                     </a>
                                 
                                 <a href="<?= base_url('admin/documents/share/' . $doc['id']) ?>" 
                                    class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors">
                                     <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                                     </svg>
                                     Share
                                 </a>
                                 
                                 <a href="<?= base_url('admin/documents/edit/' . $doc['id']) ?>" 
                                    class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 transition-colors">
                                     <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                        </a>
                     
                                 <button onclick="deleteDocument(<?= $doc['id'] ?>)" 
                                         class="w-full text-left flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors"
                                         data-doc-id="<?= $doc['id'] ?>"
                                         type="button"
                                         title="Delete document">
                                     <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                          </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
            </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div class="flex items-center justify-between mt-8">
        <div class="text-sm text-gray-600">
            Page <?= $currentPage ?> of <?= $totalPages ?>
            </div>
        <div class="flex items-center gap-2">
            <?php if ($currentPage > 1): ?>
            <a href="<?= base_url('admin/documents?' . http_build_query(array_merge($_GET, ['page' => $currentPage - 1]))) ?>"
               class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Previous
            </a>
            <?php endif; ?>

            <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
            <a href="<?= base_url('admin/documents?' . http_build_query(array_merge($_GET, ['page' => $i]))) ?>"
               class="px-3 py-2 rounded-lg text-sm font-medium <?= $i === $currentPage ? 'bg-blue-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' ?> transition-colors">
                <?= $i ?>
            </a>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
            <a href="<?= base_url('admin/documents?' . http_build_query(array_merge($_GET, ['page' => $currentPage + 1]))) ?>"
               class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium">
                Next
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php else: ?>
    <!-- Empty State -->
    <div class="bg-white rounded-2xl p-12 text-center shadow-soft border border-gray-100">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">No documents found</h3>
        <p class="text-gray-600 mb-6">Try adjusting your search criteria or upload a new document.</p>
        <a href="<?= base_url('admin/documents/upload') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Upload Document
        </a>
    </div>
    <?php endif; ?>
</div>

<!-- Category Management Modal -->
<div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="categoryModalContent">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-white">Category Management</h3>
                        <p class="text-blue-100 text-sm">Organize your documents with categories</p>
                    </div>
                </div>
                <button onclick="closeCategoryModal()" class="text-white/80 hover:text-white transition-colors p-2 hover:bg-white/10 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Content -->
        <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 88px);">
            <!-- Add New Category Section -->
            <div class="bg-gray-50 rounded-xl p-5 mb-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add New Category
                </h4>
                <form id="addCategoryForm" class="flex gap-3">
                    <div class="flex-1">
                        <input type="text" id="newCategoryName" name="name" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm placeholder-gray-400 shadow-sm"
                               placeholder="Enter category name (e.g., Reports, Policies, Forms)" required>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-all duration-200 text-sm font-medium shadow-sm hover:shadow-md flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Category
                    </button>
                </form>
            </div>

            <!-- Categories List Section -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        Existing Categories
                    </h4>
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-1 rounded-full">
                        <?= count($categories) ?> total
                    </span>
                </div>
                
                <div id="categoriesList" class="space-y-3">
                    <?php if (empty($categories)): ?>
                        <div class="text-center py-12 bg-gray-50 rounded-xl">
                            <div class="mx-auto h-16 w-16 text-gray-300 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No categories yet</h3>
                            <p class="text-gray-500">Create your first category to start organizing documents</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($categories as $category): ?>
                        <div class="group bg-white border border-gray-200 rounded-xl p-4 hover:border-blue-300 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a1.994 1.994 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h5 class="font-medium text-gray-900"><?= esc($category['name']) ?></h5>
                                        <p class="text-sm text-gray-500">
                                            Category ID: #<?= $category['id'] ?>
                                            <?php 
                                            // Check if category is in use
                                            $db = \Config\Database::connect();
                                            $usage_count = $db->table('document_category')->where('category_id', $category['id'])->countAllResults();
                                            if ($usage_count > 0) {
                                                echo " • Used by {$usage_count} document" . ($usage_count > 1 ? 's' : '');
                                            } else {
                                                echo " • Not in use";
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button onclick="editCategory(<?= $category['id'] ?>, '<?= esc($category['name']) ?>')" 
                                            class="bg-blue-50 text-blue-600 hover:bg-blue-100 p-2 rounded-lg transition-colors" 
                                            title="Edit category">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <?php if ($usage_count > 0): ?>
                                    <button onclick="showCategoryUsage(<?= $category['id'] ?>, '<?= esc($category['name']) ?>', <?= $usage_count ?>)" 
                                            class="bg-orange-50 text-orange-600 hover:bg-orange-100 p-2 rounded-lg transition-colors" 
                                            title="Category is in use - click to see details">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                        </svg>
                                    </button>
                                    <?php else: ?>
                                    <button onclick="deleteCategory(<?= $category['id'] ?>)" 
                                            class="bg-red-50 text-red-600 hover:bg-red-100 p-2 rounded-lg transition-colors" 
                                            title="Delete category">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PDF.js for PDF preview -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<!-- Document Detail Modal (KK Style for Super Admin) -->
<div id="documentModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-6 hidden">
    <div class="bg-white rounded-3xl shadow-2xl max-w-7xl w-full max-h-[95vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-100 bg-gray-50">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h2 id="modalTitle" class="text-base font-semibold text-gray-900">Document Details</h2>
                    <p id="modalSubtitle" class="text-xs text-gray-500">Loading...</p>
                </div>
            </div>
            <div class="flex items-center gap-1">
                <!-- Super Admin Action Buttons -->
                <button id="modalPreviewBtn" onclick="openPreview()" class="hidden px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors text-xs font-medium" title="Open Full Preview">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    Preview
                </button>
                <button id="modalDownloadBtn" onclick="downloadDocument()" class="px-3 py-2 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg transition-colors text-xs font-medium" title="Download">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Download
                </button>
                
                <!-- Super Admin Approval buttons -->
                <button id="modalApproveBtn" class="hidden px-3 py-2 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg transition-colors text-xs font-medium" title="Approve Document">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Approve
                </button>
                <button id="modalRejectBtn" class="hidden px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors text-xs font-medium" title="Reject Document">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Reject
                </button>
                
                <!-- Edit/Delete buttons -->
                <button id="modalEditBtn" class="px-3 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded-lg transition-colors text-xs font-medium" title="Edit Document">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </button>
                <button id="modalDeleteBtn" class="px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors text-xs font-medium" title="Delete Document">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete
                </button>
                
                <button onclick="closeDocumentModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors ml-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="overflow-y-auto max-h-[calc(95vh-80px)]">
            <div class="grid grid-cols-6 gap-0 h-full">
                <!-- Large Document Preview -->
                <div class="col-span-5 p-8 bg-gradient-to-br from-gray-50 to-gray-100">
                    <div id="modalPreview" class="bg-white rounded-2xl shadow-lg border-2 border-gray-200 min-h-[600px] flex items-center justify-center overflow-hidden">
                        <div class="text-center">
                            <div class="animate-spin w-8 h-8 border-4 border-blue-200 border-t-blue-600 rounded-full mx-auto mb-4"></div>
                            <p class="text-gray-500">Loading preview...</p>
                        </div>
                    </div>
                </div>

                <!-- Document Information Sidebar -->
                <div class="col-span-1 p-6 bg-white border-l border-gray-200">
                    <div class="space-y-6">
                        <!-- Status Badge -->
                        <div>
                            <span class="text-xs text-gray-400 uppercase tracking-wide">Status</span>
                            <div id="modalStatus" class="mt-1"></div>
                        </div>
                        
                        <div id="modalDescription" class="hidden">
                            <h4 class="text-xs font-semibold text-gray-900 mb-2 uppercase tracking-wide">Description</h4>
                            <p id="modalDescriptionText" class="text-xs text-gray-600 leading-relaxed"></p>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <span class="text-xs text-gray-400 uppercase tracking-wide">Uploaded By</span>
                                <p id="modalUploadedBy" class="text-sm font-medium text-gray-900 mt-1"></p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 uppercase tracking-wide">Uploaded</span>
                                <p id="modalDate" class="text-sm font-medium text-gray-900 mt-1"></p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 uppercase tracking-wide">File Size</span>
                                <p id="modalSize" class="text-sm font-medium text-gray-900 mt-1"></p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 uppercase tracking-wide">File Type</span>
                                <p id="modalFileType" class="text-sm font-medium text-gray-900 mt-1"></p>
                            </div>
                            <div id="modalCategory" class="hidden">
                                <span class="text-xs text-gray-400 uppercase tracking-wide">Category</span>
                                <p id="modalCategoryText" class="text-sm font-medium text-blue-600 mt-1"></p>
                            </div>
                        </div>

                        <div id="modalTags" class="hidden">
                            <h4 class="text-xs font-semibold text-gray-400 mb-3 uppercase tracking-wide">Tags</h4>
                            <div id="modalTagsList" class="flex flex-wrap gap-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 for modal dialogs -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Document modal functions (for Super Admin)
document.addEventListener('DOMContentLoaded', function() {
    // Initialize PDF.js worker
    if (typeof pdfjsLib !== 'undefined') {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    }
});

let currentDocumentId = null;
let currentDocumentData = null;

// Open document modal
function openDocumentModal(documentId) {
    currentDocumentId = documentId;
    const modal = document.getElementById('documentModal');
    const modalContent = document.getElementById('modalContent');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
    
    // Fetch document details
    fetchDocumentDetails(documentId);
}

// Close document modal
function closeDocumentModal() {
    const modal = document.getElementById('documentModal');
    const modalContent = document.getElementById('modalContent');
    
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        currentDocumentId = null;
        currentDocumentData = null;
    }, 300);
}

// Fetch document details via AJAX
async function fetchDocumentDetails(documentId) {
    try {
        const response = await fetch(`<?= base_url('documents/api/detail/') ?>${documentId}`);
        if (!response.ok) throw new Error('Failed to fetch document details');
        
        const data = await response.json();
        console.log('Document data received:', data); // Debug log
        currentDocumentData = data;
        populateModal(data);
    } catch (error) {
        console.error('Error fetching document details:', error);
        showModalError('Failed to load document details. Please try again.');
    }
}

// Populate modal with document data
function populateModal(doc) {
    // Update header
    document.getElementById('modalTitle').textContent = doc.filename || 'Untitled Document';
    document.getElementById('modalSubtitle').textContent = `Uploaded on ${new Date(doc.uploaded_at || doc.created_at).toLocaleDateString()}`;
    
    // Update status
    const statusElement = document.getElementById('modalStatus');
    if (statusElement) {
        let statusClass = '';
        let statusText = '';
        
        const status = doc.approval_status || 'unknown';
        switch(status) {
            case 'approved':
                statusClass = 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800';
                statusText = '✓ Approved';
                break;
            case 'pending':
                statusClass = 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800';
                statusText = '⏳ Pending';
                break;
            case 'rejected':
                statusClass = 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800';
                statusText = '✗ Rejected';
                break;
            default:
                statusClass = 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800';
                statusText = status || 'Unknown';
        }
        
        statusElement.innerHTML = `<span class="${statusClass}">${statusText}</span>`;
    }
    
    // Update information
    document.getElementById('modalUploadedBy').textContent = doc.uploaded_by || doc.username || 'Unknown';
    document.getElementById('modalDate').textContent = new Date(doc.uploaded_at || doc.created_at).toLocaleDateString('en-US', { 
        year: 'numeric', month: 'long', day: 'numeric' 
    });
    document.getElementById('modalSize').textContent = `${Math.round((doc.filesize || 0) / 1024)} KB`;
    document.getElementById('modalFileType').textContent = doc.mimetype || 'Unknown';
    
    // Description
    const descElement = document.getElementById('modalDescription');
    const descTextElement = document.getElementById('modalDescriptionText');
    if (descElement && descTextElement) {
        if (doc.description && doc.description.trim()) {
            descElement.classList.remove('hidden');
            descTextElement.textContent = doc.description;
        } else {
            descElement.classList.add('hidden');
        }
    }
    
    // Category
    const catElement = document.getElementById('modalCategory');
    const catTextElement = document.getElementById('modalCategoryText');
    if (catElement && catTextElement) {
        if (doc.category_name) {
            catElement.classList.remove('hidden');
            catTextElement.textContent = doc.category_name;
        } else {
            catElement.classList.add('hidden');
        }
    }
    
    // Tags
    const tagsElement = document.getElementById('modalTags');
    const tagsListElement = document.getElementById('modalTagsList');
    if (tagsElement && tagsListElement) {
        if (doc.tags && doc.tags.trim()) {
            tagsElement.classList.remove('hidden');
            tagsListElement.innerHTML = '';
            doc.tags.split(',').forEach(tag => {
                const tagSpan = document.createElement('span');
                tagSpan.className = 'inline-block bg-blue-100 text-blue-600 px-2 py-1 rounded-md text-xs font-medium';
                tagSpan.textContent = `#${tag.trim()}`;
                tagsListElement.appendChild(tagSpan);
            });
        } else {
            tagsElement.classList.add('hidden');
        }
    }
    
    // Preview button
    const previewBtn = document.getElementById('modalPreviewBtn');
    if (previewBtn) {
        const isImageOrPdf = doc.mimetype && (doc.mimetype.startsWith('image/') || doc.mimetype === 'application/pdf');
        if (isImageOrPdf) {
            previewBtn.classList.remove('hidden');
        } else {
            previewBtn.classList.add('hidden');
        }
    }
    
    // Setup super admin action buttons
    setupSuperAdminButtons(doc);
    
    // Generate preview
    generateModalPreview(doc);
}

// Setup super admin specific buttons
function setupSuperAdminButtons(doc) {
    const editBtn = document.getElementById('modalEditBtn');
    const deleteBtn = document.getElementById('modalDeleteBtn');
    const approveBtn = document.getElementById('modalApproveBtn');
    const rejectBtn = document.getElementById('modalRejectBtn');
    
    // Super admin can edit/delete any document
    editBtn.onclick = () => {
        window.location.href = `<?= base_url('admin/documents/edit/') ?>${doc.id}`;
    };
    deleteBtn.onclick = () => {
        confirmDocumentDelete(doc.id, doc.filename || 'this document');
    };
    
    // Approval buttons for pending documents
    if (doc.approval_status === 'pending') {
        approveBtn.classList.remove('hidden');
        rejectBtn.classList.remove('hidden');
        approveBtn.onclick = () => updateDocumentStatus(doc.id, 'approved');
        rejectBtn.onclick = () => updateDocumentStatus(doc.id, 'rejected');
    } else {
        approveBtn.classList.add('hidden');
        rejectBtn.classList.add('hidden');
    }
}

// Generate preview in modal
function generateModalPreview(doc) {
    const previewContainer = document.getElementById('modalPreview');
    if (!previewContainer || !doc.filepath) {
        console.error('Preview container not found or no filepath');
        return;
    }
    
    const previewUrl = `<?= base_url('admin/documents/preview/') ?>${doc.id}`;
    
    if (doc.thumbnail_path) {
        // Use existing thumbnail
        previewContainer.innerHTML = `
            <div class="w-full h-full p-6 flex items-center justify-center">
                <div class="bg-white border-4 border-gray-300 rounded-lg shadow-xl max-w-full max-h-full overflow-hidden">
                    <img src="<?= base_url('uploads/thumbnails/') ?>${doc.thumbnail_path.split('/').pop()}" 
                         alt="Document Preview" 
                         class="w-full h-full object-contain" 
                         style="min-height: 500px; max-height: 700px;"
                         onerror="this.src='<?= base_url('assets/images/file-not-found.svg') ?>'" />
                </div>
            </div>
        `;
    } else if (doc.mimetype && doc.mimetype.startsWith('image/')) {
        // Show image
        previewContainer.innerHTML = `
            <div class="w-full h-full p-6 flex items-center justify-center">
                <div class="bg-white border-4 border-gray-300 rounded-lg shadow-xl max-w-full max-h-full overflow-hidden">
                    <img src="${previewUrl}" 
                         alt="Document Preview" 
                         class="w-full h-full object-contain" 
                         style="min-height: 500px; max-height: 700px;"
                         onerror="this.src='<?= base_url('assets/images/file-not-found.svg') ?>'" />
                </div>
            </div>
        `;
    } else if (doc.mimetype === 'application/pdf') {
        // PDF preview with PDF.js
        previewContainer.innerHTML = `
            <div class="w-full h-full p-6 flex items-center justify-center">
                <div class="bg-white border-4 border-gray-300 rounded-lg shadow-xl relative overflow-hidden" id="pdf-modal-preview" style="min-height: 600px; min-width: 500px; max-height: 700px;">
                    <canvas id="pdf-modal-canvas" class="w-full h-full object-contain"></canvas>
                    <div class="absolute inset-0 flex items-center justify-center bg-white" id="pdf-modal-loading">
                        <div class="text-center">
                            <div class="animate-spin w-10 h-10 border-4 border-blue-200 border-t-blue-600 rounded-full mx-auto mb-6"></div>
                            <p class="text-gray-500 font-medium">Loading PDF preview...</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Load PDF
        if (typeof pdfjsLib !== 'undefined') {
            try {
                const loadingTask = pdfjsLib.getDocument(previewUrl);
                loadingTask.promise.then(function(pdf) {
                    pdf.getPage(1).then(function(page) {
                        const canvas = document.getElementById('pdf-modal-canvas');
                        const context = canvas.getContext('2d');
                        const container = document.getElementById('pdf-modal-preview');
                        
                        const viewport = page.getViewport({scale: 1});
                        const scale = Math.min(500 / viewport.width, 600 / viewport.height, 2.5);
                        const scaledViewport = page.getViewport({scale: scale});
                        
                        canvas.height = scaledViewport.height;
                        canvas.width = scaledViewport.width;
                        container.style.width = scaledViewport.width + 'px';
                        container.style.height = scaledViewport.height + 'px';
                        
                        page.render({
                            canvasContext: context,
                            viewport: scaledViewport
                        }).promise.then(function() {
                            document.getElementById('pdf-modal-loading').style.display = 'none';
                        });
                    });
                }).catch(function(error) {
                    console.error('Error loading PDF:', error);
                    const loadingElement = document.getElementById('pdf-modal-loading');
                    if (loadingElement) {
                        loadingElement.innerHTML = `
                            <div class="text-center p-12">
                                <p class="text-red-600 font-semibold text-lg mb-2">PDF Preview Failed</p>
                                <p class="text-gray-500 text-sm">Click download to view the document</p>
                            </div>
                        `;
                    }
                });
            } catch (error) {
                console.error('PDF.js error:', error);
            }
        }
    } else {
        // File type icon
        const extension = doc.filename ? doc.filename.split('.').pop().toUpperCase() : 'FILE';
        previewContainer.innerHTML = `
            <div class="w-full h-full p-6 flex items-center justify-center">
                <div class="bg-white border-4 border-gray-300 rounded-lg shadow-xl p-16 text-center" style="min-height: 500px; min-width: 400px;">
                    <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-blue-200 rounded-3xl flex items-center justify-center mx-auto mb-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <p class="text-blue-600 font-bold text-2xl mb-3">${extension} File</p>
                    <p class="text-gray-500 text-lg">Preview not available</p>
                    <p class="text-gray-400 text-sm mt-4">${doc.mimetype || 'Unknown type'}</p>
                </div>
            </div>
        `;
    }
}

// Download document
function downloadDocument() {
    if (currentDocumentId) {
        window.location.href = `<?= base_url('admin/documents/download/') ?>${currentDocumentId}`;
    }
}

// Open full preview
function openPreview() {
    if (currentDocumentId) {
        window.open(`<?= base_url('admin/documents/preview/') ?>${currentDocumentId}`, '_blank');
    }
}

// Confirm document delete
function confirmDocumentDelete(documentId, filename) {
    Swal.fire({
        title: 'Delete Document?',
        text: `Are you sure you want to delete "${filename}"? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `<?= base_url('admin/documents/delete/') ?>${documentId}`;
            form.style.display = 'none';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '<?= csrf_token() ?>';
            csrfInput.value = '<?= csrf_hash() ?>';
            form.appendChild(csrfInput);

            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Update document status (approve/reject)
function updateDocumentStatus(documentId, status) {
    const action = status === 'approved' ? 'approve' : 'reject';
    
    Swal.fire({
        title: `${action.charAt(0).toUpperCase() + action.slice(1)} Document?`,
        text: `Are you sure you want to ${action} this document?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: status === 'approved' ? '#059669' : '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: `Yes, ${action} it!`,
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Success!',
                text: `Document has been ${status}.`,
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        }
    });
}

// Show error in modal
function showModalError(message) {
    document.getElementById('modalTitle').textContent = 'Error';
    document.getElementById('modalSubtitle').textContent = message;
    document.getElementById('modalPreview').innerHTML = `
        <div class="text-center text-red-500 p-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-lg font-medium">${message}</p>
        </div>
    `;
}

// Close modal when clicking outside
document.getElementById('documentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDocumentModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('documentModal').classList.contains('hidden')) {
        closeDocumentModal();
    }
});

function openCategoryModal() {
    const modal = document.getElementById('categoryModal');
    const modalContent = document.getElementById('categoryModalContent');
    modal.classList.remove('hidden');
    
    // Trigger entrance animation
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeCategoryModal() {
    const modal = document.getElementById('categoryModal');
    const modalContent = document.getElementById('categoryModalContent');
    
    // Trigger exit animation
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    // Hide modal after animation
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('categoryModal');
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeCategoryModal();
        }
    });
});

// Add Category Form Handler
document.getElementById('addCategoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const addBtn = this.querySelector('button[type="submit"]');
    const originalText = addBtn.innerHTML;
    
    // Show loading state
    addBtn.disabled = true;
    addBtn.innerHTML = `
        <svg class="animate-spin h-4 w-4 text-white mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Adding...
    `;
    
    fetch('<?= base_url('admin/categories/add') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        addBtn.disabled = false;
        addBtn.innerHTML = originalText;
        
        if (data.success) {
            // Clear the form
            document.getElementById('newCategoryName').value = '';
            
            // Show success notification with custom green style
            showSuccessToast('Category added successfully!');
            
            // Refresh the page to update categories
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            Swal.fire({
                title: 'Error!',
                text: data.message || 'Failed to add category.',
                icon: 'error',
                confirmButtonColor: '#ef4444'
            });
        }
    })
    .catch(error => {
        addBtn.disabled = false;
        addBtn.innerHTML = originalText;
        console.error('Error:', error);
        Swal.fire({
            title: 'Error!',
            text: 'An error occurred while adding the category.',
            icon: 'error',
            confirmButtonColor: '#ef4444'
        });
    });
});

function editCategory(id, name) {
    Swal.fire({
        title: 'Edit Category',
        input: 'text',
        inputLabel: 'Category Name',
        inputValue: name,
        inputPlaceholder: 'Enter category name',
        showCancelButton: true,
        confirmButtonText: 'Update',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6b7280',
        inputValidator: (value) => {
            if (!value || !value.trim()) {
                return 'Category name is required!';
            }
            if (value.trim().length < 2) {
                return 'Category name must be at least 2 characters!';
            }
        }
    }).then((result) => {
        if (result.isConfirmed && result.value.trim() !== name) {
            const newName = result.value.trim();
            
            // Show loading
            Swal.fire({
                title: 'Updating...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            const formData = new FormData();
            formData.append('name', newName);
            
            fetch('<?= base_url('admin/categories/edit/') ?>' + id, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessToast('Category updated successfully!');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Failed to update category.',
                        icon: 'error',
                        confirmButtonColor: '#ef4444'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while updating the category.',
                    icon: 'error',
                    confirmButtonColor: '#ef4444'
                });
            });
        }
    });
}

function showCategoryUsage(id, name, count) {
    Swal.fire({
        title: 'Category in Use',
        html: `
            <div class="text-left">
                <p class="mb-3">The category <strong>"${name}"</strong> cannot be deleted because it is currently being used by <strong>${count}</strong> document${count > 1 ? 's' : ''}.</p>
                <p class="text-sm text-gray-600">To delete this category, you must first:</p>
                <ol class="text-sm text-gray-600 mt-2 ml-4 list-decimal">
                    <li>Remove this category from all documents that use it</li>
                    <li>Or assign those documents to a different category</li>
                </ol>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Got it',
        confirmButtonColor: '#2563eb',
        customClass: {
            popup: 'text-left'
        }
    });
}

function deleteCategory(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This category will be permanently deleted. This action cannot be undone!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Deleting...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Use fetch with proper AJAX headers
            const formData = new FormData();
            formData.append('ajax_request', '1');
            
            <?php if (function_exists('csrf_token')): ?>
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
            <?php endif; ?>
            
            fetch('<?= base_url('admin/categories/delete/') ?>' + id, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessToast('Category deleted successfully!');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An unexpected error occurred. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}

function deleteDocument(id) {
    // Check if SweetAlert is loaded
    if (typeof Swal === 'undefined') {
        alert('Error: SweetAlert library is not loaded. Please refresh the page.');
        return;
    }
    
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading indicator
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait while we delete the document.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Use AJAX to delete the document
            const formData = new FormData();
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch('<?= base_url('admin/documents/delete/') ?>' + id, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(data => {
                // Check if response contains success indicator
                if (data.includes('success') || data.includes('deleted')) {
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'The document has been successfully deleted.',
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Reload the page to update the document list
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to delete the document. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    });
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while deleting the document.',
                    icon: 'error',
                    confirmButtonColor: '#d33'
                });
            });
        }
    });
}

// Per page change handler
const perPageElement = document.getElementById('perPage');
if (perPageElement) {
    perPageElement.addEventListener('change', function() {
        const url = new URL(window.location);
        url.searchParams.set('per_page', this.value);
        url.searchParams.set('page', '1'); // Reset to first page
        window.location.href = url.toString();
    });
}

// Bulk Operations JavaScript
const selectAllCheckbox = document.getElementById('selectAllDocs');
const documentCheckboxes = document.querySelectorAll('.document-checkbox');
const selectedCountSpan = document.getElementById('selectedCount');
const bulkActions = document.getElementById('bulkActions');
const bulkDownloadBtn = document.getElementById('bulkDownload');
const bulkDeleteBtn = document.getElementById('bulkDelete');

// Update UI based on selected documents
function updateBulkUI() {
    const selectedDocs = document.querySelectorAll('.document-checkbox:checked');
    const count = selectedDocs.length;
    
    selectedCountSpan.textContent = count === 0 ? '0 selected' : `${count} selected`;
    
    if (count > 0) {
        // Show buttons with animation
        bulkActions.classList.remove('opacity-0', 'scale-95');
        bulkActions.classList.add('opacity-100', 'scale-100');
        bulkDownloadBtn.disabled = false;
        if (bulkDeleteBtn) bulkDeleteBtn.disabled = false;
    } else {
        // Hide buttons with animation
        bulkActions.classList.remove('opacity-100', 'scale-100');
        bulkActions.classList.add('opacity-0', 'scale-95');
        bulkDownloadBtn.disabled = true;
        if (bulkDeleteBtn) bulkDeleteBtn.disabled = true;
    }
    
    // Update select all checkbox state
    if (count === 0) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = false;
    } else if (count === documentCheckboxes.length) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = true;
    } else {
        selectAllCheckbox.indeterminate = true;
        selectAllCheckbox.checked = false;
    }
}

// Select/deselect all documents
if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener('change', function() {
        documentCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkUI();
    });
}

// Handle individual document checkbox changes
documentCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', updateBulkUI);
});

// Bulk download functionality
if (bulkDownloadBtn) {
    bulkDownloadBtn.addEventListener('click', function() {
        const selectedDocs = document.querySelectorAll('.document-checkbox:checked');
        if (selectedDocs.length === 0) return;

        // Create a form to submit document IDs for bulk download
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= base_url('admin/documents/bulk-download') ?>';
        form.style.display = 'none';

        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        form.appendChild(csrfInput);

        // Add selected document IDs
        selectedDocs.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'document_ids[]';
            input.value = checkbox.value;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    });
}

// Bulk delete functionality
if (bulkDeleteBtn) {
    bulkDeleteBtn.addEventListener('click', function() {
        const selectedDocs = document.querySelectorAll('.document-checkbox:checked');
        if (selectedDocs.length === 0) return;

        Swal.fire({
            title: 'Delete Selected Documents?',
            text: `Are you sure you want to delete ${selectedDocs.length} document(s)? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete them!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading indicator
                Swal.fire({
                    title: 'Deleting Documents...',
                    text: `Please wait while we delete ${selectedDocs.length} document(s).`,
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Create FormData for AJAX request
                const formData = new FormData();
                formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
                
                // Add selected document IDs
                selectedDocs.forEach(checkbox => {
                    formData.append('document_ids[]', checkbox.value);
                });

                // Use AJAX for bulk delete
                fetch('<?= base_url('admin/documents/bulk-delete') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: data.message || `${selectedDocs.length} document(s) have been successfully deleted.`,
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Reload the page to update the document list
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.error || 'Failed to delete the selected documents.',
                            icon: 'error',
                            confirmButtonColor: '#d33'
                        });
                    }
                })
                .catch(error => {
                    console.error('Bulk delete error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while deleting the documents.',
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    });
                });
            }
        });
    });
}

// Initialize UI
updateBulkUI();
</script>

        </div>
    </div>
</div>
