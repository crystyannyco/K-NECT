

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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 21V7a2 2 0 00-2-2H6a2 2 0 00-2 2v14" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold drop-shadow-lg mb-1">Categories</h1>
                            <p class="text-white/80 text-sm">Manage document categories and organization</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="<?= base_url('admin/categories/add') ?>" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:bg-green-700 transition-colors flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Category
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
                                    <th class="py-3 px-4 text-left font-semibold text-blue-900 text-sm">ID</th>
                                    <th class="py-3 px-4 text-left font-semibold text-blue-900 text-sm">Name</th>
                                    <th class="py-3 px-4 text-center font-semibold text-blue-900 text-sm">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($categories)): ?>
                                    <tr>
                                        <td colspan="3" class="py-12 text-center text-gray-500">
                                            <div class="flex flex-col items-center gap-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 21V7a2 2 0 00-2-2H6a2 2 0 00-2 2v14" />
                                                </svg>
                                                <span class="text-sm">No categories found. Add your first category!</span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                <?php foreach ($categories as $i => $cat): ?>
                                    <tr class="<?= $i % 2 === 0 ? 'bg-gray-50' : 'bg-white' ?> hover:bg-blue-50 transition-colors">
                                        <td class="py-3 px-4">
                                            <span class="text-sm text-gray-600"><?= $cat['id'] ?></span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="text-sm font-medium text-gray-900"><?= esc($cat['name']) ?></span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
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
                                                   class="text-white px-3 py-1.5 rounded-lg text-xs font-medium shadow-sm hover:bg-gray-800 transition-colors flex items-center gap-1.5" 
                                                   style="background-color: #001833;" 
                                                   title="Delete category" 
                                                   onclick="return confirm('Delete this category?')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    <span>Delete</span>
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

 
