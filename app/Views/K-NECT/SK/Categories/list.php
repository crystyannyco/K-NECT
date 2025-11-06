<?php $title = 'Categories'; ?>

<div class="max-w-4xl mx-auto p-0 mt-6">
    <div class="relative z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-100/60 to-white/80 rounded-2xl blur-xl opacity-80"></div>
        <div class="relative rounded-2xl shadow-lg border border-blue-200 bg-white/70 backdrop-blur-lg overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-6 text-white rounded-t-2xl" style="background-color: #001833;">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-white/20 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold drop-shadow-lg mb-1">Document Categories</h1>
                            <p class="text-white/80 text-sm">Manage your barangay document categories</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="<?= base_url('admin/categories/add') ?>" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:bg-green-700 transition-colors flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Category
                        </a>
                        <a href="<?= base_url('admin/documents') ?>" class="bg-white/20 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:bg-white/30 transition-colors flex items-center gap-2 border border-white/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Info Banner -->
            <div class="px-6 py-4 bg-blue-50 border-b border-blue-100">
                <div class="flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm text-blue-800">
                        <strong>Note:</strong> You can manage categories for your barangay. City-wide categories created by Pederasyon are shown but cannot be edited or deleted.
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

            <?php if (session()->getFlashdata('error')): ?>
            <div class="px-6 py-4 bg-red-50 border-b border-red-100">
                <div class="flex items-center justify-between bg-red-100 text-red-800 px-4 py-3 rounded-lg shadow-sm">
                    <span class="flex items-center gap-2 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <?= session()->getFlashdata('error') ?>
                    </span>
                    <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900 text-lg">&times;</button>
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
                                    <th class="py-3 px-4 text-left font-semibold text-blue-900 text-sm">ID</th>
                                    <th class="py-3 px-4 text-left font-semibold text-blue-900 text-sm">Name</th>
                                    <th class="py-3 px-4 text-left font-semibold text-blue-900 text-sm">Scope</th>
                                    <th class="py-3 px-4 text-center font-semibold text-blue-900 text-sm">Documents</th>
                                    <th class="py-3 px-4 text-center font-semibold text-blue-900 text-sm">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($categories)): ?>
                                    <tr>
                                        <td colspan="5" class="py-12 text-center text-gray-500">
                                            <div class="flex flex-col items-center gap-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                </svg>
                                                <span class="text-sm">No categories found. Add your first category!</span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                <?php foreach ($categories as $i => $cat): ?>
                                    <?php 
                                        $isCityWide = empty($cat['barangay_id']);
                                        $canManage = !$isCityWide && ($cat['barangay_id'] == $barangayId);
                                    ?>
                                    <tr class="<?= $i % 2 === 0 ? 'bg-gray-50' : 'bg-white' ?> hover:bg-blue-50 transition-colors">
                                        <td class="py-3 px-4">
                                            <span class="text-sm text-gray-600"><?= $cat['id'] ?></span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="text-sm font-medium text-gray-900"><?= esc($cat['name']) ?></span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <?php if ($isCityWide): ?>
                                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-purple-100 text-purple-700 text-xs font-medium rounded-full">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                                    </svg>
                                                    City-Wide
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    Barangay
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full">
                                                <?= $cat['document_count'] ?? 0 ?>
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <?php if ($canManage): ?>
                                            <div class="flex gap-2 justify-center">
                                                <a href="<?= base_url('admin/categories/edit/' . $cat['id']) ?>" 
                                                   class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium shadow-sm hover:bg-blue-700 transition-colors flex items-center gap-1.5" 
                                                   title="Edit category">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    <span>Edit</span>
                                                </a>
                                                <a href="<?= base_url('admin/categories/delete/' . $cat['id']) ?>" 
                                                   class="text-white px-3 py-1.5 rounded-lg text-xs font-medium shadow-sm hover:bg-red-700 transition-colors flex items-center gap-1.5 bg-red-600" 
                                                   title="Delete category" 
                                                   onclick="return confirm('Delete this category? This action cannot be undone.')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    <span>Delete</span>
                                                </a>
                                            </div>
                                            <?php else: ?>
                                            <span class="text-xs text-gray-400 italic">Read-only</span>
                                            <?php endif; ?>
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
