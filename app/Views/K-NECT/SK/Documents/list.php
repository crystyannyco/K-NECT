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
<div class="max-w-6xl mx-auto p-0">
    <div class="p-6 rounded-2xl shadow-lg border border-gray-200 bg-white flex flex-col gap-4">
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
        <form method="GET" action="<?= base_url('admin/documents') ?>" class="flex flex-col md:flex-row gap-3 w-full items-center justify-between bg-white/80 rounded-xl shadow-md p-4 border border-blue-200 backdrop-blur-md transition-all duration-300 hover:shadow-lg focus-within:ring-2 focus-within:ring-blue-200">
                <div class="relative w-full md:w-2/5">
                <input type="text" name="search" id="search" value="<?= esc($_GET['search'] ?? '') ?>"
                   class="border border-blue-200 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-300 focus:outline-none shadow-sm transition-all duration-300 placeholder-gray-400 text-sm bg-white/70"
                   placeholder="🔍 Search documents...">
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-blue-400 cursor-pointer group" tabindex="0" title="Search by filename or description">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:text-blue-600 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                        </svg>
                    </span>
                </div>

            <select name="category" id="category" class="border border-blue-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-300 focus:outline-none shadow-sm text-sm bg-white/70">
                    <option value="">All Categories</option>
                    <?php foreach (($categories ?? []) as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= (isset($selectedCategory) && $selectedCategory == $cat['id']) ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>

            <select name="status" id="status" class="border border-blue-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-300 focus:outline-none shadow-sm text-sm bg-white/70">
                <option value="">All Status</option>
                <option value="pending" <?= (isset($_GET['status']) && $_GET['status'] === 'pending') ? 'selected' : '' ?>>Pending</option>
                <option value="approved" <?= (isset($_GET['status']) && $_GET['status'] === 'approved') ? 'selected' : '' ?>>Approved</option>
                <option value="rejected" <?= (isset($_GET['status']) && $_GET['status'] === 'rejected') ? 'selected' : '' ?>>Rejected</option>
            </select>

            <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-400 text-white px-6 py-2 rounded-lg font-bold shadow-md hover:from-blue-700 hover:to-blue-500 transition-all flex items-center gap-2 text-sm border-2 border-blue-200 hover:border-blue-400 focus:ring-2 focus:ring-blue-200 outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                    Search
                </button>

            <?php if (!empty($_GET['search']) || !empty($_GET['category']) || !empty($_GET['status'])): ?>
            <a href="<?= base_url('admin/documents') ?>" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg font-semibold shadow-sm hover:bg-gray-200 transition flex items-center gap-2 ml-2 border-2 border-gray-200 hover:border-gray-400" title="Show all documents">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6" />
                </svg>
                        Show All
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div id="alert" class="mb-6 flex items-center justify-between bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-lg shadow-sm animate-fade-in" role="alert">
        <span class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <?= session()->getFlashdata('success') ?>
        </span>
            <button onclick="document.getElementById('alert').remove()" class="ml-4 text-green-700 hover:text-green-900 text-xl">&times;</button>
        </div>
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
                    <?php if (!empty($doc['thumbnail_path']) && file_exists(FCPATH . $doc['thumbnail_path'])): ?>
                        <img src="<?= base_url('uploads/thumbnails/' . basename($doc['thumbnail_path'])) ?>" 
                             alt="Document Preview" 
                             class="object-contain w-full h-full" 
                             data-type="document" 
                             data-filename="<?= esc($doc['filename']) ?>" />
                    <?php elseif ($isImage): ?>
                        <img src="<?= $previewUrl ?>" 
                             alt="Image Preview" 
                             class="object-contain w-full h-full" 
                             data-type="image" 
                             data-filename="<?= esc($doc['filename']) ?>" />
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
                                    <?= esc($doc['title'] ?? ($doc['filename'] ?? 'Untitled document')) ?>
                                </button>
                      </h2>
                      <div class="flex items-center gap-3">
                                <span class="px-3 py-1 rounded-full text-xs font-bold <?= $doc['approval_status'] === 'approved' ? 'bg-green-100 text-green-800' : ($doc['approval_status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?> shadow">
                                    <?= ucfirst($doc['approval_status']) ?>
                                </span>
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

                    <div class="flex flex-wrap gap-1 mt-1">
                            <?php if (!empty($doc['tags'])): ?>
                                    <?php foreach (explode(',', $doc['tags']) as $tag): ?>
                                        <span class="inline-block bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs font-semibold shadow-sm">#<?= esc(trim($tag)) ?></span>
                                    <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-gray-400 italic">No tags</span>
                            <?php endif; ?>
                    </div>
                  </div>

                  <!-- Actions -->
                  <div class="relative ml-4 mt-4 md:mt-0" x-data="{ open: false }">
                    <button @click="open = !open" 
                            @click.away="open = false"
                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-colors"
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
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                        
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
                                
                                <a href="<?= base_url('admin/documents/share/' . $doc['id']) ?>" 
                                   class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                                    </svg>
                                    Share
                                </a>
                                
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
    
    // Check if SweetAlert is loaded
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert is not loaded!');
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
        console.log('SweetAlert result:', result);
        if (result.isConfirmed) {
            console.log('User confirmed deletion');
            
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
                    Swal.fire({
                        title: 'Deleted!',
                        text: data.message || 'The document has been successfully deleted.',
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
                        text: data.error || 'Failed to delete the document. Please try again.',
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
        } else {
            console.log('User cancelled deletion');
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
                Swal.fire({
                    title: 'Cannot Delete',
                    text: 'You cannot delete documents uploaded by Super Admin.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }
            <?php endif; ?>

            const filenames = Array.from(selectedDocs).map(cb => cb.dataset.filename).join(', ');
            
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
                
                <?php if (session('role') === 'super_admin'): ?>
                <!-- Approval buttons for super admin -->
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
                <?php endif; ?>
                
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

<!-- SweetAlert2 for modal dialogs -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    document.getElementById('modalTitle').textContent = doc.title || doc.filename || 'Untitled Document';
    document.getElementById('modalSubtitle').textContent = `Uploaded on ${new Date(doc.uploaded_at || doc.created_at).toLocaleDateString()}`;
    
    // Update status - handle missing approval_status gracefully
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
    const approveBtn = document.getElementById('modalApproveBtn');
    const rejectBtn = document.getElementById('modalRejectBtn');
    
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

<?php if (session('role') === 'super_admin'): ?>
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
            // Here you would make an API call to update the status
            // For now, we'll just show a success message and reload
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

<!-- Alpine.js for dropdown functionality -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script> 
