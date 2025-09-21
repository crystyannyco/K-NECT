<?php $title = 'Document Library'; ?>
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<div class="max-w-7xl mx-auto p-0">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200">
        <div class="px-6 py-4 space-y-4 sm:space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-2">
            <div class="flex-1">
                <h1 class="text-2xl lg:text-3xl font-bold text-blue-900 tracking-tight flex items-center gap-2 drop-shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 lg:h-7 lg:w-7 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7V3a1 1 0 011-1h8a1 1 0 011 1v4m-2 4h2a2 2 0 012 2v7a2 2 0 01-2 2H7a2 2 0 01-2-2v-7a2 2 0 012-2h2m2 0V3" />
                    </svg>
                    Document Library
                </h1>
                <div class="text-sm text-blue-700 mt-1 font-medium opacity-80">KK Viewer - Browse and search approved documents</div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl shadow-sm p-4 lg:p-6 border border-blue-100 transition-all duration-300 hover:shadow-md">
            <form method="GET" action="<?= base_url('documents') ?>" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Search Input -->
                    <div class="sm:col-span-2 lg:col-span-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" id="search" value="<?= esc($_GET['search'] ?? '') ?>"
                                   class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 placeholder-gray-400 text-sm shadow-sm"
                                   placeholder="Search documents...">
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="lg:col-span-1">
                        <select name="category" id="category" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 shadow-sm text-sm">
                            <option value="">All Categories</option>
                            <?php foreach (($categories ?? []) as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= (isset($selectedCategory) && $selectedCategory == $cat['id']) ? 'selected' : '' ?>>
                                    <?= esc($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Action Buttons Column -->
                    <div class="lg:col-span-1 flex flex-col sm:flex-row gap-3">
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-2.5 rounded-xl font-semibold shadow-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center justify-center gap-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span class="whitespace-nowrap">Search</span>
                        </button>

                        <?php if (!empty($_GET['search']) || !empty($_GET['category'])): ?>
                        <a href="<?= base_url('documents') ?>" class="w-full bg-gray-100 text-gray-700 px-6 py-2.5 rounded-xl font-semibold shadow-sm hover:bg-gray-200 transition-all duration-200 flex items-center justify-center gap-2 text-sm" title="Show all documents">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span class="whitespace-nowrap">Clear</span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>

        <!-- Results Summary -->
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                <?php 
                $perPage = isset($perPage) ? (int) $perPage : (isset($_GET['per_page']) ? (int) $_GET['per_page'] : 10);
                $page = isset($page) ? (int) $page : (isset($_GET['page']) ? (int) $_GET['page'] : 1);
                $total = isset($total) ? (int) $total : ((isset($documents) && is_array($documents)) ? count($documents) : 0);
                $currentPage = max(1, (int) $page);
                $start = ($currentPage - 1) * $perPage;
                $totalPages = (int) max(1, ceil($total / max(1, $perPage)));
                ?>
                Showing <?= $total > 0 ? ($start + 1) : 0 ?> to <?= min($start + $perPage, $total) ?> of <?= $total ?> documents
            </div>
        </div>

        <!-- Documents Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (empty($documents)): ?>
            <!-- Empty State -->
            <div class="col-span-full bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl p-16 text-center">
                <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mx-auto mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">No documents found</h3>
                <p class="text-gray-600 text-lg">Try adjusting your search or browse all available documents.</p>
            </div>
            <?php else: ?>
            <?php foreach ($documents as $i => $doc): ?>
            <?php 
            $previewUrl = base_url('documents/preview/' . $doc['id']);
            $isImage = strpos($doc['mimetype'], 'image/') === 0;
            $isPdf = $doc['mimetype'] === 'application/pdf';
            $fileExtension = strtoupper(pathinfo($doc['filename'], PATHINFO_EXTENSION));
            ?>
            <!-- Document Card -->
            <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200">
                <!-- Document Preview -->
                <div class="relative h-48 bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center overflow-hidden">
                    <?php if (!empty($doc['thumbnail_path']) && file_exists(WRITEPATH . $doc['thumbnail_path'])): ?>
                        <!-- PDF/Document Thumbnail Preview -->
                        <img src="<?= base_url('uploads/thumbnails/' . basename($doc['thumbnail_path'])) ?>" 
                             alt="Document Preview" 
                             class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300" />
                    <?php elseif ($isImage): ?>
                        <!-- Image Preview -->
                        <img src="<?= $previewUrl ?>" 
                             alt="Document Preview" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                    <?php elseif ($isPdf): ?>
                        <!-- PDF First Page Preview using PDF.js -->
                        <div class="w-full h-full flex items-center justify-center bg-white relative" id="pdf-preview-<?= $doc['id'] ?>">
                            <canvas id="pdf-canvas-<?= $doc['id'] ?>" class="max-w-full max-h-full object-contain"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center" id="pdf-loading-<?= $doc['id'] ?>">
                                <div class="flex flex-col items-center text-gray-500">
                                    <svg class="animate-spin h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="text-sm">Loading preview...</span>
                                </div>
                            </div>
                        </div>
                        <script>
                        // Load PDF preview for this document
                        (function() {
                            const pdfjsLib = window['pdfjs-dist/build/pdf'];
                            if (!pdfjsLib) {
                                console.log('PDF.js not loaded, falling back to icon');
                                document.getElementById('pdf-loading-<?= $doc['id'] ?>').innerHTML = `
                                    <div class="flex flex-col items-center justify-center text-center p-6">
                                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-3 shadow-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <span class="text-sm font-bold text-gray-600"><?= $fileExtension ?></span>
                                    </div>
                                `;
                                return;
                            }
                            
                            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
                            
                            const loadingTask = pdfjsLib.getDocument('<?= $previewUrl ?>');
                            loadingTask.promise.then(function(pdf) {
                                pdf.getPage(1).then(function(page) {
                                    const canvas = document.getElementById('pdf-canvas-<?= $doc['id'] ?>');
                                    const context = canvas.getContext('2d');
                                    
                                    const container = canvas.parentElement;
                                    const containerWidth = container.clientWidth;
                                    const containerHeight = container.clientHeight;
                                    
                                    const viewport = page.getViewport({scale: 1});
                                    const scale = Math.min(containerWidth / viewport.width, containerHeight / viewport.height) * 0.9;
                                    const scaledViewport = page.getViewport({scale: scale});
                                    
                                    canvas.height = scaledViewport.height;
                                    canvas.width = scaledViewport.width;
                                    
                                    const renderContext = {
                                        canvasContext: context,
                                        viewport: scaledViewport
                                    };
                                    
                                    page.render(renderContext).promise.then(function() {
                                        document.getElementById('pdf-loading-<?= $doc['id'] ?>').style.display = 'none';
                                        canvas.style.display = 'block';
                                    });
                                });
                            }).catch(function(error) {
                                console.error('Error loading PDF:', error);
                                document.getElementById('pdf-loading-<?= $doc['id'] ?>').innerHTML = `
                                    <div class="flex flex-col items-center justify-center text-center p-6">
                                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-3 shadow-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <span class="text-sm font-bold text-gray-600"><?= $fileExtension ?></span>
                                    </div>
                                `;
                            });
                        })();
                        </script>
                    <?php else: ?>
                        <!-- File Type Icon for unsupported formats -->
                        <div class="flex flex-col items-center justify-center text-center p-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-3 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-600"><?= $fileExtension ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Quick Action Overlay -->
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center">
                        <button onclick="openDocumentModal(<?= $doc['id'] ?>)" 
                               class="opacity-0 group-hover:opacity-100 bg-white text-gray-900 px-4 py-2 rounded-xl font-semibold shadow-lg transform translate-y-2 group-hover:translate-y-0 transition-all duration-300">
                            View Document
                        </button>
                    </div>
                </div>

                <!-- Document Info -->
                <div class="p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                        <button onclick="openDocumentModal(<?= $doc['id'] ?>)" class="hover:underline text-left w-full">
                            <?= esc($doc['filename'] ?? 'Untitled document') ?>
                        </button>
                    </h3>
                    
                    <?php if (!empty($doc['description'])): ?>
                    <p class="text-gray-600 text-sm mb-3 line-clamp-2"><?= esc(substr($doc['description'], 0, 100)) ?><?= strlen($doc['description']) > 100 ? '...' : '' ?></p>
                    <?php endif; ?>
                    
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                        <span><?= date('M j, Y', strtotime($doc['uploaded_at'] ?? $doc['created_at'])) ?></span>
                        <span><?= number_format($doc['filesize']/1024, 0) ?> KB</span>
                    </div>

                    <!-- Tags -->
                    <?php if (!empty($doc['tags'])): ?>
                    <div class="flex flex-wrap gap-1">
                        <?php foreach (array_slice(explode(',', $doc['tags']), 0, 3) as $tag): ?>
                        <span class="inline-block bg-blue-50 text-blue-600 px-2 py-1 rounded-lg text-xs font-medium">#<?= esc(trim($tag)) ?></span>
                        <?php endforeach; ?>
                        <?php if (count(explode(',', $doc['tags'])) > 3): ?>
                        <span class="inline-block text-gray-400 px-2 py-1 text-xs">+<?= count(explode(',', $doc['tags'])) - 3 ?> more</span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <!-- Pagination -->
        <?php if (($totalPages ?? 1) > 1): ?>
        <div class="flex flex-col sm:flex-row items-center justify-between mt-8 gap-4">
            <div class="text-sm text-gray-600">Page <?= $currentPage ?> of <?= $totalPages ?></div>
            <div class="flex items-center gap-2">
                <?php if ($currentPage > 1): ?>
                <a href="<?= base_url('documents?' . http_build_query(array_merge($_GET, ['page' => $currentPage - 1]))) ?>"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors text-sm font-medium shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    Previous
                </a>
                <?php endif; ?>
                
                <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                <a href="<?= base_url('documents?' . http_build_query(array_merge($_GET, ['page' => $i]))) ?>"
                   class="px-4 py-2 rounded-xl text-sm font-medium transition-colors shadow-sm <?= $i === $currentPage ? 'bg-blue-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' ?>">
                    <?= $i ?>
                </a>
                <?php endfor; ?>
                
                <?php if ($currentPage < $totalPages): ?>
                <a href="<?= base_url('documents?' . http_build_query(array_merge($_GET, ['page' => $currentPage + 1]))) ?>"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors text-sm font-medium shadow-sm">
                    Next
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Document Detail Modal -->
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
                <!-- More Visible Action Buttons -->
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

                <!-- Compact Document Information -->
                <div class="col-span-1 p-6 bg-white border-l border-gray-200">
                    <div class="space-y-6">
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

<script>
// Global function to handle PDF preview generation for documents without thumbnails
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
    
    // Show modal with animation
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
    }, 300);
}

// Fetch document details via AJAX
async function fetchDocumentDetails(documentId) {
    try {
        const response = await fetch(`<?= base_url('documents/api/detail/') ?>${documentId}`);
        if (!response.ok) throw new Error('Failed to fetch document details');
        
        const data = await response.json();
        currentDocumentData = data;
        populateModal(data);
    } catch (error) {
        console.error('Error fetching document details:', error);
        // Fallback to direct navigation if AJAX fails
        window.location.href = `<?= base_url('documents/detail/') ?>${documentId}`;
    }
}

// Populate modal with document data
function populateModal(doc) {
    // Update header
    document.getElementById('modalTitle').textContent = doc.filename || 'Untitled Document';
    document.getElementById('modalSubtitle').textContent = `Uploaded on ${new Date(doc.uploaded_at || doc.created_at).toLocaleDateString()}`;
    
    // Update information
    document.getElementById('modalUploadedBy').textContent = doc.uploaded_by || doc.username || 'Unknown';
    document.getElementById('modalDate').textContent = new Date(doc.uploaded_at || doc.created_at).toLocaleDateString('en-US', { 
        year: 'numeric', month: 'long', day: 'numeric' 
    });
    document.getElementById('modalSize').textContent = `${Math.round(doc.filesize / 1024)} KB`;
    
    // Description
    if (doc.description && doc.description.trim()) {
        document.getElementById('modalDescription').classList.remove('hidden');
        document.getElementById('modalDescriptionText').textContent = doc.description;
    } else {
        document.getElementById('modalDescription').classList.add('hidden');
    }
    
    // Category
    if (doc.category_name) {
        document.getElementById('modalCategory').classList.remove('hidden');
        document.getElementById('modalCategoryText').textContent = doc.category_name;
    } else {
        document.getElementById('modalCategory').classList.add('hidden');
    }
    
    // Tags
    if (doc.tags && doc.tags.trim()) {
        document.getElementById('modalTags').classList.remove('hidden');
        const tagsList = document.getElementById('modalTagsList');
        tagsList.innerHTML = '';
        doc.tags.split(',').forEach(tag => {
            const tagSpan = document.createElement('span');
            tagSpan.className = 'inline-block bg-blue-100 text-blue-600 px-2 py-1 rounded-md text-xs font-medium';
            tagSpan.textContent = `#${tag.trim()}`;
            tagsList.appendChild(tagSpan);
        });
    } else {
        document.getElementById('modalTags').classList.add('hidden');
    }
    
    // Preview button
    const isImageOrPdf = doc.mimetype.startsWith('image/') || doc.mimetype === 'application/pdf';
    if (isImageOrPdf) {
        document.getElementById('modalPreviewBtn').classList.remove('hidden');
    } else {
        document.getElementById('modalPreviewBtn').classList.add('hidden');
    }
    
    // Generate preview
    generateModalPreview(doc);
}

// Generate preview in modal
function generateModalPreview(doc) {
    const previewContainer = document.getElementById('modalPreview');
    const previewUrl = `<?= base_url('documents/preview/') ?>${doc.id}`;
    
    if (doc.thumbnail_path) {
        // Use existing thumbnail with document-like presentation
        previewContainer.innerHTML = `
            <div class="w-full h-full p-6 flex items-center justify-center">
                <div class="bg-white border-4 border-gray-300 rounded-lg shadow-xl max-w-full max-h-full overflow-hidden">
                    <img src="<?= base_url('uploads/thumbnails/') ?>${doc.thumbnail_path.split('/').pop()}" 
                         alt="Document Preview" 
                         class="w-full h-full object-contain" 
                         style="min-height: 500px; max-height: 700px;" />
                </div>
            </div>
        `;
    } else if (doc.mimetype.startsWith('image/')) {
        // Show image directly with document frame
        previewContainer.innerHTML = `
            <div class="w-full h-full p-6 flex items-center justify-center">
                <div class="bg-white border-4 border-gray-300 rounded-lg shadow-xl max-w-full max-h-full overflow-hidden">
                    <img src="${previewUrl}" 
                         alt="Document Preview" 
                         class="w-full h-full object-contain" 
                         style="min-height: 500px; max-height: 700px;" />
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
                document.getElementById('pdf-modal-loading').innerHTML = `
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
            });
        }
    } else {
        // Show file type icon with document styling
        const extension = doc.filename.split('.').pop().toUpperCase();
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
                    <p class="text-gray-400 text-sm mt-4">${doc.mimetype}</p>
                </div>
            </div>
        `;
    }
}

// Open full preview
function openPreview() {
    if (currentDocumentId) {
        window.open(`<?= base_url('documents/preview/') ?>${currentDocumentId}`, '_blank');
    }
}

// Download document
function downloadDocument() {
    if (currentDocumentId) {
        window.location.href = `<?= base_url('documents/download/') ?>${currentDocumentId}`;
    }
}

// Close modal when clicking outside
document.getElementById('documentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDocumentModal();
    }
});

// Close modal with escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('documentModal').classList.contains('hidden')) {
        closeDocumentModal();
    }
});
</script>
