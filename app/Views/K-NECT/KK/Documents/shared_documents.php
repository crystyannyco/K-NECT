

<div class="max-w-6xl mx-auto p-0 mt-6">
    <div class="relative z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-100/60 to-white/80 rounded-2xl blur-xl opacity-80"></div>
        <div class="relative rounded-2xl shadow-lg border border-blue-200 bg-white/70 backdrop-blur-lg overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-6 text-white rounded-t-2xl" style="background-color: #001833;">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-white/20 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold drop-shadow-lg mb-1">Shared Documents</h1>
                            <p class="text-white/80 text-sm">Documents shared with you by other users</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="<?= base_url('documents') ?>" class="bg-white/20 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:bg-white/30 transition-colors flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h4m0 0V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2h4" />
            </svg>
            All Documents
        </a>
                    </div>
                </div>
    </div>

            <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
            <div class="px-6 py-4 bg-green-50 border-b border-green-100">
                <div class="flex items-center justify-between bg-green-100 text-green-800 px-4 py-3 rounded-lg shadow-sm">
                    <span class="flex items-center gap-2 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <?= session()->getFlashdata('success') ?>
            </span>
                    <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900 text-lg">&times;</button>
                </div>
        </div>
    <?php endif; ?>

            <!-- Content -->
            <div class="p-6">
                <div class="bg-white rounded-lg border border-blue-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-blue-50">
                                <tr>
                                    <th class="py-3 px-4 text-left font-semibold text-blue-900 text-sm">Document</th>
                                    <th class="py-3 px-4 text-left font-semibold text-blue-900 text-sm">Type</th>
                                    <th class="py-3 px-4 text-left font-semibold text-blue-900 text-sm">Size</th>
                                    <th class="py-3 px-4 text-left font-semibold text-blue-900 text-sm">Shared By</th>
                                    <th class="py-3 px-4 text-left font-semibold text-blue-900 text-sm">Shared At</th>
                                    <th class="py-3 px-4 text-left font-semibold text-blue-900 text-sm">Permissions</th>
                                    <th class="py-3 px-4 text-left font-semibold text-blue-900 text-sm">Expires</th>
                                    <th class="py-3 px-4 text-center font-semibold text-blue-900 text-sm">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($sharedDocs)): ?>
                    <tr>
                                        <td colspan="8" class="py-12 text-center text-gray-500">
                                            <div class="flex flex-col items-center gap-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                                                <span class="text-sm">No documents have been shared with you yet.</span>
                                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                <?php foreach ($sharedDocs as $i => $doc): ?>
                                    <tr class="<?= $i % 2 === 0 ? 'bg-gray-50' : 'bg-white' ?> hover:bg-blue-50 transition-colors">
                                        <td class="py-3 px-4">
                                            <div class="font-medium text-blue-700 text-sm"><?= esc($doc['filename']) ?></div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="text-sm text-gray-600"><?= esc($doc['mimetype']) ?></span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="text-sm text-gray-600"><?= number_format($doc['filesize']/1024, 2) ?> KB</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-700 font-bold text-xs">
                                                    <?= strtoupper(substr(esc($doc['shared_by']), 0, 1)) ?>
                                                </span>
                                                <span class="text-sm text-gray-900"><?= esc($doc['shared_by']) ?></span>
                                            </div>
                                        </td>
                        <td class="py-3 px-4">
                                            <span class="text-sm text-gray-600"><?= date('M j, Y', strtotime($doc['shared_at'])) ?></span>
                        </td>
                        <td class="py-3 px-4">
                                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium 
                                <?php
                                switch($doc['permissions']) {
                                    case 'view': echo 'bg-gray-100 text-gray-800'; break;
                                    case 'download': echo 'bg-blue-100 text-blue-800'; break;
                                    case 'edit': echo 'bg-yellow-100 text-yellow-800'; break;
                                    case 'admin': echo 'bg-red-100 text-red-800'; break;
                                    default: echo 'bg-gray-100 text-gray-800';
                                }
                                ?>">
                                <?= ucfirst($doc['permissions']) ?>
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <?php if ($doc['expires_at']): ?>
                                                <span class="text-sm text-orange-600">
                                    <?= date('M j, Y', strtotime($doc['expires_at'])) ?>
                                </span>
                            <?php else: ?>
                                                <span class="text-sm text-gray-500">Never</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4 text-center">
                                            <div class="flex flex-col gap-2 items-center">
                                <?php if (in_array($doc['permissions'], ['download', 'edit', 'admin'])): ?>
                                                    <a href="<?= base_url('documents/download/' . $doc['document_id']) ?>" 
                                                       class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium shadow-sm hover:bg-blue-700 transition-colors flex items-center gap-1.5 w-full justify-center" 
                                                       title="Download">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span>Download</span>
                                    </a>
                                <?php endif; ?>
                                
                                                <a href="<?= base_url('documents/detail/' . $doc['document_id']) ?>" 
                                                   class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium shadow-sm hover:bg-blue-700 transition-colors flex items-center gap-1.5 w-full justify-center" 
                                                   title="Preview">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>Preview</span>
                                </a>
                                

                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 
