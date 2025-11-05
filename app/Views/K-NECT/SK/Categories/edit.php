<?php $title = 'Edit Category'; ?>

<div class="max-w-3xl mx-auto p-0 mt-6">
    <div class="relative z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-100/60 to-white/80 rounded-2xl blur-xl opacity-80"></div>
        <div class="relative rounded-2xl shadow-lg border border-blue-200 bg-white/70 backdrop-blur-lg overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-6 text-white rounded-t-2xl" style="background-color: #001833;">
                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-white/20 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold drop-shadow-lg mb-1">Edit Category</h1>
                        <p class="text-white/80 text-sm">Update your barangay category</p>
                    </div>
                </div>
            </div>

            <?php 
                $isCityWide = empty($category['barangay_id']);
                $canEdit = !$isCityWide && ($category['barangay_id'] == $barangayId);
            ?>

            <?php if ($isCityWide): ?>
            <!-- City-Wide Warning -->
            <div class="px-6 py-4 bg-yellow-50 border-b border-yellow-100">
                <div class="flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div class="text-sm text-yellow-800">
                        <strong>Read-Only:</strong> This is a city-wide category created by Pederasyon. Only Pederasyon can edit city-wide categories.
                    </div>
                </div>
            </div>
            <?php elseif (!$canEdit): ?>
            <!-- Other Barangay Warning -->
            <div class="px-6 py-4 bg-red-50 border-b border-red-100">
                <div class="flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div class="text-sm text-red-800">
                        <strong>Access Denied:</strong> This category belongs to another barangay. You can only edit categories for your own barangay.
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Flash Messages -->
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
                <form action="<?= base_url('admin/categories/edit/' . $category['id']) ?>" method="post" class="space-y-6">
                    <?= csrf_field() ?>
                    
                    <!-- Category Scope Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-blue-900 mb-1">Category Information</div>
                                <div class="text-sm text-blue-700">
                                    <strong>Scope:</strong> 
                                    <?php if ($isCityWide): ?>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-purple-100 text-purple-700 text-xs font-medium rounded-full ml-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                            </svg>
                                            City-Wide
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-full ml-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Barangay-Specific
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Category Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="<?= old('name', $category['name']) ?>"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all <?= (!$canEdit) ? 'bg-gray-100 cursor-not-allowed' : '' ?>" 
                                placeholder="e.g., Youth Programs, Sports Events, Financial Reports"
                                <?= (!$canEdit) ? 'readonly' : 'required' ?>
                                maxlength="100"
                            >
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            <?= $canEdit ? 'Update the category name (max 100 characters)' : 'This category cannot be edited' ?>
                        </p>
                        <?php if (isset($validation) && $validation->hasError('name')): ?>
                        <p class="mt-2 text-sm text-red-600">
                            <?= $validation->getError('name') ?>
                        </p>
                        <?php endif; ?>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                        <?php if ($canEdit): ?>
                        <button 
                            type="submit" 
                            class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-medium shadow-sm hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center justify-center gap-2"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Update Category</span>
                        </button>
                        <?php endif; ?>
                        <a 
                            href="<?= base_url('admin/categories') ?>" 
                            class="<?= $canEdit ? 'flex-1' : 'w-full' ?> bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-medium shadow-sm hover:bg-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 flex items-center justify-center gap-2"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span><?= $canEdit ? 'Cancel' : 'Back to Categories' ?></span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
