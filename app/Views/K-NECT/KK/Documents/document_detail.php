

<div class="max-w-6xl mx-auto p-0 mt-6">
    <div class="relative z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-100/60 to-white/80 rounded-2xl blur-xl opacity-80"></div>
        <div class="relative rounded-2xl shadow-lg border border-blue-200 bg-white/70 backdrop-blur-lg overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-6 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-t-2xl">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-white/20 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7V3a1 1 0 011-1h8a1 1 0 011 1v4m-2 4h2a2 2 0 012 2v7a2 2 0 01-2 2H7a2 2 0 01-2-2v-7a2 2 0 012-2h2m2 0V3" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold drop-shadow-lg mb-1"><?= esc($document['filename']) ?></h1>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 font-bold text-sm">
                                    <?= strtoupper(substr(esc($document['uploaded_by']), 0, 1)) ?>
                                </span>
                                <span class="font-medium text-white/90 text-sm"><?= esc($document['uploaded_by']) ?></span>
                                <?php if ($uploaderRole): ?>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-white/20 text-white border border-white/30 uppercase font-semibold tracking-wide">
                                    <?= esc($uploaderRole) ?>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        <span class="px-3 py-1.5 rounded-lg text-sm font-semibold shadow-md transition-all
                            <?php if ($document['approval_status'] === 'approved') echo 'bg-green-100 text-green-800'; elseif ($document['approval_status'] === 'pending') echo 'bg-yellow-100 text-yellow-800'; else echo 'bg-red-100 text-red-800'; ?>">
                            <?php if ($document['approval_status'] === 'approved'): ?>
                                <svg class="inline h-4 w-4 mr-1 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            <?php elseif ($document['approval_status'] === 'pending'): ?>
                                <svg class="inline h-4 w-4 mr-1 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            <?php else: ?>
                                <svg class="inline h-4 w-4 mr-1 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            <?php endif; ?>
                            <?= ucfirst($document['approval_status']) ?>
                        </span>
                        <span class="text-xs text-white/80">Uploaded: <?= date('M j, Y g:i A', strtotime($document['uploaded_at'])) ?></span>
                    </div>
                </div>
            </div>

                            <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3 px-6 py-4 bg-white/80 border-b border-blue-100">
                    <!-- Preview button for supported file types -->
                    <?php 
                    $isImageOrPdf = strpos($document['mimetype'], 'image/') === 0 || $document['mimetype'] === 'application/pdf';
                    $isOfficeDocument = in_array($document['mimetype'], [
                        'application/msword', // .doc
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
                        'application/vnd.ms-excel', // .xls
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
                        'application/vnd.ms-powerpoint', // .ppt
                        'application/vnd.openxmlformats-officedocument.presentationml.presentation' // .pptx
                    ]);
                    ?>
                    
                    <?php if ($isImageOrPdf): ?>
                    <a href="<?= base_url('documents/preview/' . $document['id']) ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:bg-blue-700 transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Preview
                    </a>
                    <?php elseif ($isOfficeDocument): ?>
                    <a href="<?= base_url('documents/download/' . $document['id']) ?>" class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:bg-orange-700 transition-colors flex items-center gap-2" title="Office documents cannot be previewed. Click to download.">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download to View
                    </a>
                    <?php endif; ?>

                    <a href="<?= base_url('documents/download/' . $document['id']) ?>" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:bg-green-700 transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download
                    </a>

                    <a href="<?= base_url('documents') ?>" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:bg-gray-200 transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to List
                    </a>
                </div>

            <!-- Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Document Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4">Document Information</h3>
                        
                        <div class="bg-white rounded-lg p-4 border border-blue-100 shadow-sm">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-gray-700">File Type:</span>
                                    <span class="text-gray-900"><?= esc($document['mimetype']) ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-gray-700">File Size:</span>
                                    <span class="text-gray-900"><?= number_format($document['filesize']/1024, 2) ?> KB</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-gray-700">Document ID:</span>
                                    <span class="text-gray-900">#<?= $document['id'] ?></span>
                                </div>
                                <?php if (!empty($document['filename'])): ?>
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-gray-700">Document Name:</span>
                                    <span class="text-gray-900"><?= esc($document['filename']) ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($document['description'])): ?>
                                <div class="flex items-start justify-between">
                                    <span class="font-medium text-gray-700">Description:</span>
                                    <span class="text-gray-900 max-w-xs text-right"><?= esc($document['description']) ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($document['tags'])): ?>
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-gray-700">Tags:</span>
                                    <div class="flex flex-wrap gap-1">
                                        <?php foreach (explode(',', $document['tags']) as $tag): ?>
                                        <span class="inline-block bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs font-medium">#<?= esc(trim($tag)) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-gray-700">Downloadable:</span>
                                    <span class="text-gray-900"><?= ($document['downloadable'] ?? 1) ? 'Yes' : 'No' ?></span>
                                </div>
                                <?php if (!empty($document['visibility'])): ?>
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-gray-700">Visibility:</span>
                                    <span class="text-gray-900"><?= esc($document['visibility']) ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if ($document['approval_status'] !== 'pending' && !empty($document['approved_by'])): ?>
                        <div class="bg-white rounded-lg p-4 border border-blue-100 shadow-sm">
                            <h4 class="font-medium text-gray-700 mb-2">Approval Details</h4>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600"><?= $document['approval_status'] === 'approved' ? 'Approved By' : 'Rejected By' ?>:</span>
                                    <span class="text-sm font-medium text-gray-900"><?= esc($document['approved_by']) ?></span>
                                </div>
                                <?php if (!empty($document['approval_comment'])): ?>
                                <div class="flex items-start justify-between">
                                    <span class="text-sm text-gray-600">Comments:</span>
                                    <span class="text-sm text-gray-900 max-w-xs text-right"><?= esc($document['approval_comment']) ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- File Preview -->
                    <?php 
                    $isImageOrPdf = strpos($document['mimetype'], 'image/') === 0 || $document['mimetype'] === 'application/pdf';
                    $isOfficeDocument = in_array($document['mimetype'], [
                        'application/msword', // .doc
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
                        'application/vnd.ms-excel', // .xls
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
                        'application/vnd.ms-powerpoint', // .ppt
                        'application/vnd.openxmlformats-officedocument.presentationml.presentation' // .pptx
                    ]);
                    ?>
                    
                    <?php if ($isImageOrPdf): ?>
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4">File Preview</h3>
                        <div class="bg-white rounded-lg border border-blue-100 shadow-sm overflow-hidden">
                            <iframe src="<?= base_url('documents/preview/' . $document['id']) ?>" 
                                    class="w-full h-80 border-0 transition-all"
                                    title="Document Preview">
                            </iframe>
                        </div>
                    </div>
                    <?php elseif ($isOfficeDocument): ?>
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4">Office Document</h3>
                        <div class="bg-white rounded-lg p-6 border border-blue-100 shadow-sm">
                            <div class="text-center">
                                <?php if (strpos($document['mimetype'], 'word') !== false): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-blue-600 text-sm font-medium mb-2">Microsoft Word Document</p>
                                <?php elseif (strpos($document['mimetype'], 'excel') !== false): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-green-600 text-sm font-medium mb-2">Microsoft Excel Spreadsheet</p>
                                <?php elseif (strpos($document['mimetype'], 'powerpoint') !== false): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-orange-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-10 0a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2m0 0V2a2 2 0 00-2-2H9a2 2 0 00-2 2v2" />
                                    </svg>
                                    <p class="text-orange-600 text-sm font-medium mb-2">Microsoft PowerPoint Presentation</p>
                                <?php endif; ?>
                                <p class="text-gray-600 text-sm mb-3">This document cannot be previewed directly in the browser.</p>
                                <p class="text-gray-500 text-xs mb-4"><?= esc($document['mimetype']) ?></p>
                                <a href="<?= base_url('documents/download/' . $document['id']) ?>" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Download to View
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4">File Information</h3>
                        <div class="bg-white rounded-lg p-6 border border-blue-100 shadow-sm flex items-center justify-center">
                            <div class="text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-gray-600 text-sm">Preview not available for this file type</p>
                                <p class="text-gray-500 text-xs mt-1"><?= esc($document['mimetype']) ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


