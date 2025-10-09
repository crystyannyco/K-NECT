

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
                        <!-- Visibility Badge -->
                        <span class="px-3 py-1.5 rounded-lg text-sm font-semibold shadow-md transition-all
                            <?php 
                                if (($document['visibility'] ?? '') === 'pederasyon') echo 'bg-purple-100 text-purple-800'; 
                                elseif (($document['visibility'] ?? '') === 'sk') echo 'bg-blue-100 text-blue-800'; 
                                else echo 'bg-green-100 text-green-800'; 
                            ?>">
                            <?php if (($document['visibility'] ?? '') === 'pederasyon'): ?>
                                <svg class="inline h-4 w-4 mr-1 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            <?php elseif (($document['visibility'] ?? '') === 'sk'): ?>
                                <svg class="inline h-4 w-4 mr-1 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            <?php else: ?>
                                <svg class="inline h-4 w-4 mr-1 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            <?php endif; ?>
                            <?= strtoupper($document['visibility'] ?? 'N/A') ?>
                        </span>
                        <span class="text-xs text-white/80">Uploaded: <?= date('M j, Y g:i A', strtotime($document['uploaded_at'])) ?></span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3 px-6 py-4 bg-white/80 border-b border-blue-100">
                <a href="<?= base_url('admin/documents/download/' . $document['id']) ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Download
                </a>

                <a href="<?= base_url('admin/documents/edit/' . $document['id']) ?>" class="text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:bg-gray-800 transition-colors flex items-center gap-2" style="background-color: #001833;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>

                <a href="<?= base_url('admin/documents') ?>" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:bg-gray-200 transition-colors flex items-center gap-2">
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
                            </div>
                        </div>

                        <!-- Visibility Information -->
                        <div class="bg-white rounded-lg p-4 border border-blue-100 shadow-sm">
                            <h4 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                                <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Visibility Information
                            </h4>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Visible To:</span>
                                    <span class="px-3 py-1 rounded-lg text-sm font-semibold
                                        <?php 
                                            if ($document['visibility'] === 'pederasyon') echo 'bg-purple-100 text-purple-800'; 
                                            elseif ($document['visibility'] === 'sk') echo 'bg-blue-100 text-blue-800'; 
                                            else echo 'bg-green-100 text-green-800'; 
                                        ?>">
                                        <?= strtoupper($document['visibility'] ?? 'N/A') ?>
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Visibility Scope:</span>
                                    <span class="text-sm font-medium text-gray-900">
                                        <?php if (($document['visibility_scope'] ?? 'all') === 'all'): ?>
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 rounded">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                City-wide
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-50 text-green-700 rounded">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                Specific Barangay
                                            </span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <?php if (($document['visibility_scope'] ?? 'all') === 'specific_barangay' && !empty($document['barangay_id'])): ?>
                                <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                    <span class="text-sm text-gray-600">Barangay:</span>
                                    <span class="text-sm font-semibold text-gray-900">
                                        <?php 
                                            $docModel = new \App\Models\DocumentModel();
                                            $barangayName = $docModel->getBarangayName($document['barangay_id']);
                                            echo esc($barangayName ?? 'Unknown');
                                        ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- File Preview -->
                    <?php if (strpos($document['mimetype'], 'image/') === 0 || $document['mimetype'] === 'application/pdf'): ?>
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4">File Preview</h3>
                        <div class="bg-white rounded-lg border border-blue-100 shadow-sm overflow-hidden">
                            <iframe src="<?= base_url('admin/documents/preview/' . $document['id']) ?>" 
                                    class="w-full h-80 border-0 transition-all"
                                    title="Document Preview">
                            </iframe>
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


