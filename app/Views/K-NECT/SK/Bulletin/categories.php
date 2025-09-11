<!-- Main Content Area -->
<div class="flex-1 lg:ml-64 min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">
                        📋 Bulletin Categories
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        View and manage bulletin post categories for your barangay
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-4 sm:px-6 lg:px-8 py-6">
        <!-- Info Message -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Note:</strong> As an SK official, you can view categories but only Pederasyon officers can create, edit, or delete them. 
                        Contact your Pederasyon officer if you need new categories for your posts.
                    </p>
                </div>
            </div>
        </div>

        <!-- Categories Grid -->
        <?php if (!empty($categories)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($categories as $category): ?>
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full mr-3" style="background-color: <?= esc($category['color']) ?>"></div>
                        <h3 class="text-lg font-semibold text-gray-900"><?= esc($category['name']) ?></h3>
                    </div>
                </div>
                
                <?php if ($category['description']): ?>
                <p class="text-gray-600 mb-4"><?= esc($category['description']) ?></p>
                <?php endif; ?>
                
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <span>
                        <i class="fas fa-newspaper mr-1"></i>
                        <?= $category['post_count'] ?? 0 ?> posts
                    </span>
                    <span>
                        <i class="fas fa-calendar mr-1"></i>
                        Created <?= date('M j, Y', strtotime($category['created_at'])) ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <!-- No Categories Message -->
        <div class="text-center py-12">
            <i class="fas fa-tags text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-medium text-gray-900 mb-2">No categories available</h3>
            <p class="text-gray-500 mb-4">Categories will appear here once they are created by Pederasyon officers.</p>
            <div class="text-sm text-gray-400">
                <p>To get started with posting:</p>
                <ol class="list-decimal list-inside mt-2 space-y-1">
                    <li>Wait for categories to be created by Pederasyon officers</li>
                    <li>Use the "Create Post" feature to add bulletin posts</li>
                    <li>Organize your posts using the available categories</li>
                </ol>
            </div>
        </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex justify-center space-x-4">
                <a href="<?= base_url('/bulletin') ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Bulletin
                </a>
                <a href="<?= base_url('/bulletin/create') ?>" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create New Post
                </a>
            </div>
        </div>
    </div>
</div> 