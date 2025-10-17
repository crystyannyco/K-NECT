<?php $title = 'Document Management'; ?>

<!-- PDF.js for PDF preview -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

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
<div class="max-w-7xl mx-auto p-0">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200">
        <div class="px-6 py-4 space-y-4 sm:space-y-6">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center space-x-2 text-sm text-gray-600">
            <a href="<?= base_url('sk/dashboard') ?>" class="hover:text-blue-600 transition-colors flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-blue-600 font-medium">Documents</span>
        </nav>

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-2">
                <div>
                <h1 class="text-2xl font-bold text-blue-900 tracking-tight flex items-center gap-2 drop-shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7V3a1 1 0 011-1h8a1 1 0 011 1v4m-2 4h2a2 2 0 012 2v7a2 2 0 01-2 2H7a2 2 0 01-2-2v-7a2 2 0 012-2h2m2 0V3" /></svg>
                        Document Management
                    </h1>
                <div class="text-sm text-blue-700 mt-1 font-medium opacity-80">SK Admin - All your documents, organized and searchable</div>
                </div>
                <?php if (in_array(session('role'), ['admin', 'super_admin'])): ?>
            <a href="<?= base_url('admin/documents/upload') ?>" class="bg-gradient-to-r from-blue-500 to-blue-400 text-white px-6 py-2 rounded-xl font-bold shadow-lg hover:from-blue-600 hover:to-blue-500 transition-all text-base flex items-center gap-2 border-2 border-blue-200 hover:border-blue-400 focus:ring-2 focus:ring-blue-200 outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Upload Document
                </a>
                <?php endif; ?>
            </div>

        <!-- Search and Filter Section -->
        <div class="bg-gradient-to-br from-blue-50/50 to-white rounded-xl shadow-sm p-6 border border-blue-100 backdrop-blur-sm">
            <form method="GET" action="<?= base_url('admin/documents') ?>" class="space-y-5">
                <!-- Search Input - Full Width -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" id="search" value="<?= esc($_GET['search'] ?? '') ?>"
                           class="w-full pl-10 pr-10 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 focus:outline-none shadow-sm transition-all duration-200 placeholder-gray-400 text-sm bg-white hover:border-blue-300"
                           placeholder="Search documents...">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <div class="text-blue-400 cursor-help group relative" tabindex="0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 hover:text-blue-600 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                            </svg>
                            <span class="invisible group-hover:visible absolute right-0 top-8 bg-gray-800 text-white text-xs rounded-lg py-2 px-3 whitespace-nowrap z-10 shadow-lg">
                                Search by filename or description
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Filters and Search Button Row -->
                <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                    <!-- Category Filter -->
                    <select name="category" id="category" class="flex-1 border border-blue-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 focus:outline-none shadow-sm text-sm bg-white hover:border-blue-300 transition-all cursor-pointer">
                        <option value="">All Categories</option>
                        <?php foreach (($categories ?? []) as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= (isset($selectedCategory) && $selectedCategory == $cat['id']) ? 'selected' : '' ?>>
                                <?= esc($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Visibility Filter -->
                    <select name="visibility" id="visibility" class="flex-1 border border-blue-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 focus:outline-none shadow-sm text-sm bg-white hover:border-blue-300 transition-all cursor-pointer">
                        <option value="">All Visibility</option>
                        <option value="pederasyon" <?= (isset($_GET['visibility']) && $_GET['visibility'] === 'pederasyon') ? 'selected' : '' ?>>Pederasyon</option>
                        <option value="sk" <?= (isset($_GET['visibility']) && $_GET['visibility'] === 'sk') ? 'selected' : '' ?>>SK</option>
                        <option value="kk" <?= (isset($_GET['visibility']) && $_GET['visibility'] === 'kk') ? 'selected' : '' ?>>KK</option>
                    </select>

                    <!-- Search Button -->
                    <button type="submit" class="inline-flex items-center justify-center gap-2 bg-blue-600 text-white px-6 py-2 rounded-lg font-medium shadow-sm hover:bg-blue-700 hover:shadow transition-all focus:ring-2 focus:ring-blue-400 focus:outline-none whitespace-nowrap text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Search Documents
                    </button>

                    <!-- Clear Filters Button -->
                    <?php if (!empty($_GET['search']) || !empty($_GET['category']) || !empty($_GET['status'])): ?>
                    <a href="<?= base_url('admin/documents') ?>" class="inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-700 px-5 py-2 rounded-lg font-medium shadow-sm hover:bg-gray-200 transition-all focus:ring-2 focus:ring-gray-300 focus:outline-none whitespace-nowrap text-sm" title="Clear all filters">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear Filters
                    </a>
                    <?php endif; ?>
                </div>
            </form>
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

    <?php if (session('role') === 'super_admin'):
        $hasPending = false;
        foreach ($documents as $doc) {
            if (($doc['approval_status'] ?? 'pending') === 'pending') {
                $hasPending = true;
                break;
            }
        }
        if (!$hasPending): ?>
            <div class="mb-4 p-2 bg-red-50 border border-red-200 rounded text-sm text-red-900">
                <b>Notice:</b> There are no documents pending approval.
        </div>
        <?php endif; endif; ?>

<!-- Results Summary -->
<div class="flex items-center justify-between mb-4">
    <div class="text-xs text-gray-600">
        <?php 
        $totalDocs = count($documents ?? []);
        $start = $start ?? 0;
        $perPage = $perPage ?? 10;
        $total = $total ?? $totalDocs;
        ?>
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
<div class="mb-4 flex items-center justify-between bg-gray-50 p-3 rounded-lg border">
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
        <?php if (in_array(session('role'), ['admin', 'super_admin'])): ?>
        <button id="bulkDelete" class="bg-red-600 text-white px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors disabled:opacity-50 flex items-center gap-1" disabled>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Delete
        </button>
        <?php endif; ?>
    </div>
</div>

    <div class="py-4 px-2">
        <div class="space-y-6">
                <?php if (empty($documents)): ?>
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
                <?php else: ?>
                <?php foreach ($documents as $i => $doc): ?>
                <?php $status = $doc['approval_status'] ?? 'pending'; ?>
                <?php 
                $uploaderRole = null;
                if (isset($doc['uploaded_by']) && isset($userRoles)) {
                    $uploaderRole = $userRoles[strtolower(trim($doc['uploaded_by']))] ?? null;
                }
                $previewUrl = base_url('admin/documents/preview/' . $doc['id']);
                $isImage = strpos($doc['mimetype'], 'image/') === 0;
                $isPdf = $doc['mimetype'] === 'application/pdf';
                // DEBUG: Output to verify PDF detection
                // echo "<!-- DEBUG: File={$doc['filename']}, Mimetype={$doc['mimetype']}, isPdf=" . ($isPdf ? 'YES' : 'NO') . " -->";
                ?>
                <div class="flex flex-col md:flex-row items-center bg-white rounded-xl shadow-md p-4 border border-gray-100 hover:shadow-lg transition-all duration-300 relative">
                  <!-- Document Checkbox -->
                  <div class="absolute top-3 left-3 z-20">
                      <input type="checkbox" 
                             class="document-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                             value="<?= $doc['id'] ?>" 
                             data-filename="<?= esc($doc['filename'] ?? 'document') ?>"
                             data-filepath="<?= esc($doc['filepath'] ?? '') ?>"
                             data-uploader="<?= esc($doc['uploaded_by'] ?? '') ?>"
                             data-uploader-role="<?= esc($uploaderRole ?? '') ?>">
                  </div>
                  
                  <!-- Preview -->
                    <div class="preview-area flex-shrink-0 w-32 h-40 flex items-center justify-center bg-white rounded-lg shadow-inner border border-gray-200 overflow-hidden mr-4 relative">
                    <?php if ($isPdf): ?>
                        <!-- Always show PDF preview, even if thumbnail exists -->
                        <div class="w-full h-full relative overflow-hidden">
                            <iframe src="<?= $previewUrl ?>#toolbar=0&navpanes=0&scrollbar=0&page=1&view=FitH" 
                                    class="absolute inset-0 w-full h-full border-0" 
                                    scrolling="no"
                                    style="pointer-events: none; transform: scale(1.1); transform-origin: top left;"></iframe>
                            <!-- Overlay to hide any remaining scrollbar -->
                            <div class="absolute right-0 top-0 w-4 h-full bg-white z-10"></div>
                            <div class="absolute bottom-0 left-0 w-full h-4 bg-white z-10"></div>
                        </div>
                    <?php elseif ($isImage): ?>
                        <img src="<?= $previewUrl ?>" 
                             alt="Image Preview" 
                             class="object-contain w-full h-full" 
                             data-type="image" 
                             data-filename="<?= esc($doc['filename']) ?>" />
                    <?php elseif (!empty($doc['thumbnail_path']) && file_exists(FCPATH . $doc['thumbnail_path'])): ?>
                        <img src="<?= base_url('uploads/thumbnails/' . basename($doc['thumbnail_path'])) ?>" 
                             alt="Document Preview" 
                             class="object-contain w-full h-full" 
                             data-type="document" 
                             data-filename="<?= esc($doc['filename']) ?>" />
                    <?php else: ?>
                        <?php 
                            $fileType = get_file_type_from_mimetype($doc['mimetype']);
                            $defaultImage = get_default_image($fileType, $doc['filename']);
                        ?>
                        <img src="<?= $defaultImage ?>" 
                             alt="<?= esc($doc['filename']) ?> preview" 
                             class="object-contain w-full h-full" 
                             data-type="<?= $fileType ?>" 
                             data-filename="<?= esc($doc['filename']) ?>" />
                    <?php endif; ?>
                  </div>
                    
                  <!-- Info -->
                  <div class="flex-1 flex flex-col gap-2 min-w-0">
                    <div class="flex items-center justify-between">
                            <h2 class="text-lg font-bold text-blue-900 truncate">
                                <button onclick="openDocumentModal(<?= $doc['id'] ?>)" class="hover:underline text-left">
                                    <?= esc($doc['filename'] ?? 'Untitled document') ?>
                                </button>
                      </h2>
                      <div class="flex items-center gap-2">
                                <!-- Visibility Badge -->
                                <span class="px-3 py-1 rounded-full text-xs font-bold shadow
                                    <?php 
                                        if (($doc['visibility'] ?? '') === 'pederasyon') echo 'bg-purple-100 text-purple-800'; 
                                        elseif (($doc['visibility'] ?? '') === 'sk') echo 'bg-blue-100 text-blue-800'; 
                                        else echo 'bg-green-100 text-green-800'; 
                                    ?>">
                                    <?= strtoupper($doc['visibility'] ?? 'N/A') ?>
                                </span>
                                <!-- Barangay Badge -->
                                <?php if (!empty($doc['barangay_id'])): ?>
                                    <span class="px-2 py-1 rounded text-xs font-medium bg-green-50 text-green-700 shadow-sm">
                                        <?php 
                                            $docModel = new \App\Models\DocumentModel();
                                            $barangayName = $docModel->getBarangayName($doc['barangay_id']);
                                            echo esc($barangayName ?? 'Unknown Barangay');
                                        ?>
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-500 shadow-sm italic">No barangay</span>
                                <?php endif; ?>
                      </div>
                    </div>
                        
                    <div class="text-gray-700 text-sm mt-1">
                      <span class="font-semibold">Type:</span> <?= esc($doc['mimetype']) ?>
                      <span class="mx-2">|</span>
                      <span class="font-semibold">Size:</span> <?= number_format($doc['filesize']/1024, 2) ?> KB
                      <span class="mx-2">|</span>
                            <span class="font-semibold">Uploaded:</span> <?php $createdAt = $doc['created_at'] ?? ($doc['uploaded_at'] ?? null); echo $createdAt ? date('M j, Y', strtotime($createdAt)) : '—'; ?>
                    </div>

                    <div class="flex items-center gap-2 mt-1">
                      <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 font-bold text-sm">
                        <?= strtoupper(substr(esc($doc['uploaded_by']), 0, 1)) ?>
                      </span>
                            <span class="text-gray-800 font-semibold text-base"><?= esc($doc['uploaded_by']) ?></span>
                      <?php if ($uploaderRole): ?>
                            <span class="ml-2 text-xs px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200 uppercase font-semibold tracking-wide">
                                <?= esc($uploaderRole) ?>
                            </span>
                      <?php endif; ?>
                    </div>

                        <?php if (!empty($doc['category_name'])): ?>
                        <div class="flex items-center gap-2 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span class="text-blue-700 font-medium"><?= esc($doc['category_name']) ?></span>
                        </div>
                        <?php endif; ?>

                    <?php if (!empty($doc['tags'])): ?>
                    <div class="flex flex-wrap gap-1 mt-1">
                        <?php foreach (explode(',', $doc['tags']) as $tag): ?>
                            <span class="inline-block bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs font-semibold shadow-sm">#<?= esc(trim($tag)) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                  </div>

                  <!-- Actions -->
                  <div class="relative ml-4 mt-4 md:mt-0">
                    <button onclick="toggleDropdown(event, 'dropdown-<?= $doc['id'] ?>')" 
                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-colors"
                            title="Actions">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                        </svg>
                    </button>
                    
                    <div id="dropdown-<?= $doc['id'] ?>" 
                         class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                        
                        <a href="<?= base_url('admin/documents/download/' . $doc['id']) ?>" 
                           class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download
                        </a>
                        
                        <button onclick="openDocumentModal(<?= $doc['id'] ?>)" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors w-full text-left">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                            </svg>
                            View Details
                        </button>
                                
                        <?php
                        $canEditDelete = false;
                        if (session('role') === 'super_admin') {
                            $canEditDelete = true;
                        } elseif (session('role') === 'admin') {
                            $canEditDelete = (strtolower(trim($doc['uploaded_by'])) === strtolower(trim(session('username'))) && ($uploaderRole !== 'super_admin'));
                        }
                        ?>
                                
                                <?php if ($canEditDelete): ?>
                            <a href="<?= base_url('admin/documents/edit/' . $doc['id']) ?>" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                            
                            <button onclick="deleteDocument(<?= $doc['id'] ?>)" 
                                    class="w-full text-left flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        <?php endif; ?>
                    </div>
                  </div>
                            </div>
                <?php endforeach; ?>
                <?php endif; ?>
        </div>
    </div>
        
        <?php if (isset($pager)): ?>
        <div class="mt-8 flex justify-center">
            <?= $pager->links('default', 'default_full') ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function deleteDocument(documentId) {
    console.log('deleteDocument called with ID:', documentId);
    
    showConfirmModal({
        title: 'Delete Document?',
        message: 'Are you sure you want to delete this document? This action cannot be undone!',
        confirmText: 'Yes, Delete',
        cancelText: 'Cancel',
        confirmColor: 'red',
        icon: 'warning',
        onConfirm: () => {
            console.log('User confirmed deletion');
            showInfoToast('Deleting document...', 2000);

            // Use AJAX to delete the document
            const formData = new FormData();
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch('<?= base_url('admin/documents/delete/') ?>' + documentId, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessToast(data.message || 'Document deleted successfully!');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showErrorToast(data.error || 'Failed to delete the document. Please try again.');
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                showErrorToast('An error occurred while deleting the document.');
            });
        }
    });
}

// Bulk Operations JavaScript
document.addEventListener('DOMContentLoaded', function() {
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
            
            // Check if any selected documents are from super admin (for SK admin role)
            <?php if (session('role') === 'admin'): ?>
            const hasSuperAdminDocs = Array.from(selectedDocs).some(checkbox => 
                checkbox.dataset.uploaderRole === 'super_admin'
            );
            
            if (bulkDeleteBtn) {
                if (hasSuperAdminDocs) {
                    bulkDeleteBtn.disabled = true;
                    bulkDeleteBtn.title = 'Cannot delete documents uploaded by Super Admin';
                    bulkDeleteBtn.classList.add('opacity-50');
                } else {
                    bulkDeleteBtn.disabled = false;
                    bulkDeleteBtn.title = 'Delete selected documents';
                    bulkDeleteBtn.classList.remove('opacity-50');
                }
            }
            <?php else: ?>
            if (bulkDeleteBtn) bulkDeleteBtn.disabled = false;
            <?php endif; ?>
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
    selectAllCheckbox.addEventListener('change', function() {
        documentCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkUI();
    });

    // Handle individual document checkbox changes
    documentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkUI);
    });

    // Bulk download functionality
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

    // Bulk delete functionality
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            const selectedDocs = document.querySelectorAll('.document-checkbox:checked');
            if (selectedDocs.length === 0) return;

            <?php if (session('role') === 'admin'): ?>
            // Check if any selected documents are from super admin
            const superAdminDocs = Array.from(selectedDocs).filter(checkbox => 
                checkbox.dataset.uploaderRole === 'super_admin'
            );
            
            if (superAdminDocs.length > 0) {
                showErrorToast('You cannot delete documents uploaded by Super Admin.');
                return;
            }
            <?php endif; ?>

            const filenames = Array.from(selectedDocs).map(cb => cb.dataset.filename).join(', ');
            
            showConfirmModal({
                title: 'Delete Multiple Documents?',
                message: `Are you sure you want to delete ${selectedDocs.length} document(s)? This action cannot be undone.`,
                confirmText: 'Yes, Delete All',
                cancelText: 'Cancel',
                confirmColor: 'red',
                icon: 'warning',
                onConfirm: () => {
                    showInfoToast(`Deleting ${selectedDocs.length} document(s)...`, 2000);

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
                            showSuccessToast(data.message || `${selectedDocs.length} document(s) deleted successfully!`);
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            showErrorToast(data.error || 'Failed to delete the selected documents.');
                        }
                    })
                    .catch(error => {
                        console.error('Bulk delete error:', error);
                        showErrorToast('An error occurred while deleting the documents.');
                    });
                }
            });
        });
    }

    // Initialize UI
    updateBulkUI();
});
</script>

<!-- Document Detail Modal (KK Style) -->
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
                <!-- Admin Action Buttons -->
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
                
                <!-- Edit/Delete buttons (role-based) -->
                <button id="modalEditBtn" class="hidden px-3 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded-lg transition-colors text-xs font-medium" title="Edit Document">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </button>
                <button id="modalDeleteBtn" class="hidden px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors text-xs font-medium" title="Delete Document">
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

<!-- Toast Notification System -->
<script src="<?= base_url('assets/js/toast-notifications.js') ?>"></script>

<script>
// Document modal functions (adapted from KK side for SK admin)
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
        // Show error in modal instead of redirecting
        showModalError('Failed to load document details. Please try again.');
    }
}

// Populate modal with document data
function populateModal(doc) {
    // Update header
    document.getElementById('modalTitle').textContent = doc.filename || 'Untitled Document';
    document.getElementById('modalSubtitle').textContent = `Uploaded on ${new Date(doc.uploaded_at || doc.created_at).toLocaleDateString()}`;
    
    // Update visibility - handle missing visibility gracefully
    const statusElement = document.getElementById('modalStatus');
    if (statusElement) {
        let statusClass = '';
        let statusText = '';
        
        const visibility = doc.visibility || 'unknown';
        switch(visibility) {
            case 'pederasyon':
                statusClass = 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800';
                statusText = 'PEDERASYON';
                break;
            case 'sk':
                statusClass = 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800';
                statusText = 'SK';
                break;
            case 'kk':
                statusClass = 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800';
                statusText = 'KK';
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
    
    // Tags (if present)
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
    
    // Setup admin action buttons
    setupAdminButtons(doc);
    
    // Generate preview
    generateModalPreview(doc);
}

// Setup admin-specific buttons
function setupAdminButtons(doc) {
    const editBtn = document.getElementById('modalEditBtn');
    const deleteBtn = document.getElementById('modalDeleteBtn');
    
    // Show/hide edit and delete buttons based on permissions
    <?php if (session('role') === 'super_admin'): ?>
    editBtn.classList.remove('hidden');
    deleteBtn.classList.remove('hidden');
    editBtn.onclick = () => {
        window.location.href = `<?= base_url('admin/documents/edit/') ?>${doc.id}`;
    };
    deleteBtn.onclick = () => {
        confirmDocumentDelete(doc.id, doc.filename || 'this document');
    };
    <?php elseif (session('role') === 'admin'): ?>
    // SK admin can only edit/delete their own documents, not super_admin documents
    if (doc.uploaded_by === '<?= session('username') ?>' && doc.uploader_role !== 'super_admin') {
        editBtn.classList.remove('hidden');
        deleteBtn.classList.remove('hidden');
        editBtn.onclick = () => {
            window.location.href = `<?= base_url('admin/documents/edit/') ?>${doc.id}`;
        };
        deleteBtn.onclick = () => {
            confirmDocumentDelete(doc.id, doc.filename || 'this document');
        };
    } else {
        editBtn.classList.add('hidden');
        deleteBtn.classList.add('hidden');
    }
    <?php else: ?>
    editBtn.classList.add('hidden');
    deleteBtn.classList.add('hidden');
    <?php endif; ?>
}

// Generate preview in modal (adapted from KK side)
function generateModalPreview(doc) {
    const previewContainer = document.getElementById('modalPreview');
    if (!previewContainer || !doc.filepath) {
        console.error('Preview container not found or no filepath');
        return;
    }
    
    const previewUrl = `<?= base_url('admin/documents/preview/') ?>${doc.id}`;
    
    if (doc.thumbnail_path) {
        // Use existing thumbnail with document-like presentation
        previewContainer.innerHTML = `
            <div class="w-full h-full p-6 flex items-center justify-center">
                <div class="bg-white border-4 border-gray-300 rounded-lg shadow-xl max-w-full max-h-full overflow-hidden">
                    <img src="<?= base_url('uploads/thumbnails/') ?>${doc.thumbnail_path.split('/').pop()}" 
                         alt="Document Preview" 
                         class="w-full h-full object-contain" 
                         style="min-height: 500px; max-height: 700px;"
                         data-type="document" 
                         data-filename="${doc.filename}" />
                </div>
            </div>
        `;
    } else if (doc.mimetype && doc.mimetype.startsWith('image/')) {
        // Show image directly with document frame
        previewContainer.innerHTML = `
            <div class="w-full h-full p-6 flex items-center justify-center">
                <div class="bg-white border-4 border-gray-300 rounded-lg shadow-xl max-w-full max-h-full overflow-hidden">
                    <img src="${previewUrl}" 
                         alt="Image Preview" 
                         class="w-full h-full object-contain" 
                         style="min-height: 500px; max-height: 700px;"
                         data-type="image" 
                         data-filename="${doc.filename}" />
                </div>
            </div>
        `;
    } else if (doc.mimetype === 'application/pdf') {
        // Generate PDF preview with document styling
        previewContainer.innerHTML = `
            <div class="w-full h-full p-6 flex items-center justify-center">
                <div class="bg-white border-4 border-gray-300 rounded-lg shadow-xl relative overflow-hidden" id="pdf-modal-preview" style="min-height: 600px; min-width: 500px; max-height: 700px;">
                    <canvas id="pdf-modal-canvas" class="w-full h-full object-contain"></canvas>
                    <div class="absolute inset-0 flex items-center justify-center bg-white" id="pdf-modal-loading">
                        <div class="text-center">
                            <div class="animate-spin w-10 h-10 border-4 border-blue-200 border-t-blue-600 rounded-full mx-auto mb-6"></div>
                            <p class="text-gray-500 font-medium">Loading PDF preview...</p>
                            <p class="text-gray-400 text-sm mt-2">Please wait...</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Load PDF with better sizing
        if (typeof pdfjsLib !== 'undefined') {
            try {
                const loadingTask = pdfjsLib.getDocument(previewUrl);
                loadingTask.promise.then(function(pdf) {
                    pdf.getPage(1).then(function(page) {
                        const canvas = document.getElementById('pdf-modal-canvas');
                        const context = canvas.getContext('2d');
                        
                        // Get container dimensions for optimal scaling
                        const container = document.getElementById('pdf-modal-preview');
                        const containerWidth = 500; // Minimum width
                        const containerHeight = 600; // Minimum height
                        
                        const viewport = page.getViewport({scale: 1});
                        
                        // Calculate scale to fit nicely in the container
                        const scaleX = containerWidth / viewport.width;
                        const scaleY = containerHeight / viewport.height;
                        const scale = Math.min(scaleX, scaleY, 2.5); // Max scale of 2.5 for quality
                        
                        const scaledViewport = page.getViewport({scale: scale});
                        canvas.height = scaledViewport.height;
                        canvas.width = scaledViewport.width;
                        
                        // Update container size to match canvas
                        container.style.width = scaledViewport.width + 'px';
                        container.style.height = scaledViewport.height + 'px';
                        
                        const renderContext = {
                            canvasContext: context,
                            viewport: scaledViewport
                        };
                        
                        page.render(renderContext).promise.then(function() {
                            document.getElementById('pdf-modal-loading').style.display = 'none';
                        });
                    });
                }).catch(function(error) {
                    console.error('Error loading PDF:', error);
                    const loadingElement = document.getElementById('pdf-modal-loading');
                    if (loadingElement) {
                        loadingElement.innerHTML = `
                            <div class="text-center p-12">
                                <div class="w-24 h-24 bg-red-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <p class="text-red-600 font-semibold text-lg mb-2">PDF Preview Failed</p>
                                <p class="text-gray-500 text-sm">Click download to view the document</p>
                            </div>
                        `;
                    }
                });
            } catch (error) {
                console.error('PDF.js error:', error);
            }
        } else {
            console.error('PDF.js not loaded');
        }
    } else {
        // Show file type icon with document styling
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

// Admin functions
function confirmDocumentDelete(documentId, filename) {
    showConfirmModal({
        title: 'Delete Document?',
        message: `Are you sure you want to delete "${filename}"? This action cannot be undone.`,
        confirmText: 'Yes, Delete',
        cancelText: 'Cancel',
        confirmColor: 'red',
        icon: 'warning',
        onConfirm: () => {
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

<?php if (session('role') === 'super_admin'): ?>
function updateDocumentStatus(documentId, status) {
    const action = status === 'approved' ? 'approve' : 'reject';
    
    if (!confirm(`Are you sure you want to ${action} this document?`)) {
        return;
    }

    // Here you would make an API call to update the status
    // For now, we'll just show a success message and reload
    showSuccessToast(`Document has been ${status}!`);
    setTimeout(() => {
        location.reload();
    }, 1000);
}
<?php endif; ?>

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
</script>


<!-- Dropdown Toggle Script -->
<script>
function toggleDropdown(event, dropdownId) {
    event.stopPropagation();
    
    // Close all other dropdowns
    document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
        if (dropdown.id !== dropdownId) {
            dropdown.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    const dropdown = document.getElementById(dropdownId);
    dropdown.classList.toggle('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('[id^="dropdown-"]') && !event.target.closest('button[onclick^="toggleDropdown"]')) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
            dropdown.classList.add('hidden');
        });
    }
});
</script>

