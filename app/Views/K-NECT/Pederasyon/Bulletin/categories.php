<!-- Main Content Area -->
<div class="flex-1 lg:ml-64 min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">
                        🏷️ Category Management
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Manage bulletin board categories and tags
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-4 flex space-x-3">
                    <button onclick="showCreateModal()" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Category
                    </button>
                    <a href="<?= base_url('/bulletin') ?>" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Bulletin
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-4 sm:px-6 lg:px-8 py-6">
        <div class="max-w-7xl mx-auto">
            <!-- Categories Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Categories</h2>
                    <p class="mt-1 text-sm text-gray-500">Organize posts by category</p>
                </div>
                
                <div class="p-6">
                    <div id="categoriesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Categories will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Quick Add Categories -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Quick Setup</h2>
                    <p class="mt-1 text-sm text-gray-500">Add common categories with one click</p>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <button onclick="addQuickCategory('Announcements', 'General announcements and notifications', '#3B82F6')" 
                                class="p-3 text-center border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 bg-blue-500 rounded-full mx-auto mb-2"></div>
                            <div class="text-sm font-medium">Announcements</div>
                        </button>
                        <button onclick="addQuickCategory('Events', 'Upcoming events and activities', '#10B981')" 
                                class="p-3 text-center border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 bg-green-500 rounded-full mx-auto mb-2"></div>
                            <div class="text-sm font-medium">Events</div>
                        </button>
                        <button onclick="addQuickCategory('Youth Activities', 'Youth-related programs and activities', '#F59E0B')" 
                                class="p-3 text-center border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 bg-yellow-500 rounded-full mx-auto mb-2"></div>
                            <div class="text-sm font-medium">Youth Activities</div>
                        </button>
                        <button onclick="addQuickCategory('Sports', 'Sports events and tournaments', '#3B82F6')" 
                                class="p-3 text-center border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 bg-blue-500 rounded-full mx-auto mb-2"></div>
                            <div class="text-sm font-medium">Sports</div>
                        </button>
                        <button onclick="addQuickCategory('Health & Wellness', 'Health programs and wellness activities', '#EF4444')" 
                                class="p-3 text-center border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 bg-red-500 rounded-full mx-auto mb-2"></div>
                            <div class="text-sm font-medium">Health</div>
                        </button>
                        <button onclick="addQuickCategory('Education', 'Educational programs and scholarships', '#06B6D4')" 
                                class="p-3 text-center border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 bg-cyan-500 rounded-full mx-auto mb-2"></div>
                            <div class="text-sm font-medium">Education</div>
                        </button>
                        <button onclick="addQuickCategory('Community Service', 'Volunteer work and community projects', '#84CC16')" 
                                class="p-3 text-center border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 bg-lime-500 rounded-full mx-auto mb-2"></div>
                            <div class="text-sm font-medium">Community</div>
                        </button>
                        <button onclick="addQuickCategory('Emergency Alerts', 'Important emergency information', '#DC2626')" 
                                class="p-3 text-center border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 bg-red-600 rounded-full mx-auto mb-2"></div>
                            <div class="text-sm font-medium">Emergency</div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Category Modal -->
<div id="categoryModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"></div>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="categoryForm">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modalTitle">
                                Create New Category
                            </h3>
                            
                            <input type="hidden" id="categoryId" name="category_id">
                            
                            <div class="mb-4">
                                <label for="categoryName" class="block text-sm font-medium text-gray-700 mb-2">
                                    Category Name *
                                </label>
                                <input type="text" id="categoryName" name="name" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter category name">
                            </div>
                            
                            <div class="mb-4">
                                <label for="categoryDescription" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea id="categoryDescription" name="description" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Enter category description"></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label for="categoryColor" class="block text-sm font-medium text-gray-700 mb-2">
                                    Color
                                </label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" id="categoryColor" name="color" value="#3B82F6"
                                           class="h-10 w-20 border border-gray-300 rounded cursor-pointer">
                                    <span class="text-sm text-gray-500">Choose a color for this category</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" id="saveBtn"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Category
                    </button>
                    <button type="button" onclick="closeModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
<div id="messageContainer" class="fixed top-4 right-4 z-50 hidden">
    <div id="messageAlert" class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg id="messageIcon" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p id="messageText" class="text-sm font-medium text-gray-900"></p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button onclick="hideMessage()" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
    
    // Form submission
    document.getElementById('categoryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveCategory();
    });
});

function loadCategories() {
    fetch('<?= base_url('/bulletin/categories/list') ?>', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayCategories(data.categories || []);
            } else {
                displayCategories([]);
            }
        })
        .catch(error => {
            console.error('Error loading categories:', error);
            displayCategories([]);
        });
}

function displayCategories(categories) {
    const grid = document.getElementById('categoriesGrid');
    
    if (categories.length === 0) {
        grid.innerHTML = `
            <div class="col-span-full text-center py-8">
                <div class="text-gray-400 mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <h3 class="text-sm font-medium text-gray-900">No categories yet</h3>
                <p class="text-sm text-gray-500 mt-1">Get started by creating your first category or using quick setup options above.</p>
            </div>
        `;
        return;
    }
    
    grid.innerHTML = categories.map(category => `
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <div class="flex items-start justify-between mb-2">
                <div class="flex items-center">
                    <div class="w-4 h-4 rounded-full mr-2" style="background-color: ${category.color}"></div>
                    <h3 class="font-medium text-gray-900">${category.name}</h3>
                </div>
                <div class="flex space-x-1">
                    <button onclick="editCategory(${category.id})" class="text-gray-400 hover:text-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="deleteCategory(${category.id})" class="text-gray-400 hover:text-red-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-2">${category.description || 'No description'}</p>
            <div class="text-xs text-gray-500">
                ${(category.post_count || 0)} posts
            </div>
        </div>
    `).join('');
}

function showCreateModal() {
    document.getElementById('modalTitle').textContent = 'Create New Category';
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryId').value = '';
    document.getElementById('categoryColor').value = '#3B82F6';
    document.getElementById('saveBtn').textContent = 'Save Category';
    document.getElementById('categoryModal').classList.remove('hidden');
}

function editCategory(id) {
    fetch(`<?= base_url('/bulletin/categories/') ?>${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const category = data.category;
                document.getElementById('modalTitle').textContent = 'Edit Category';
                document.getElementById('categoryId').value = category.id;
                document.getElementById('categoryName').value = category.name;
                document.getElementById('categoryDescription').value = category.description || '';
                document.getElementById('categoryColor').value = category.color;
                document.getElementById('saveBtn').textContent = 'Update Category';
                document.getElementById('categoryModal').classList.remove('hidden');
            }
        })
        .catch(() => {
            showMessage('Error loading category data', 'error');
        });
}

function saveCategory() {
    const formData = new FormData(document.getElementById('categoryForm'));
    const categoryId = document.getElementById('categoryId').value;
    const url = categoryId ? 
        `<?= base_url('/bulletin/categories/update/') ?>${categoryId}` : 
        '<?= base_url('/bulletin/categories/store') ?>';
    
    fetch(url, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message, 'success');
            closeModal();
            loadCategories();
        } else {
            showMessage(data.message || 'Failed to save category', 'error');
        }
    })
    .catch(() => {
        showMessage('An error occurred while saving the category', 'error');
    });
}

function deleteCategory(id) {
    if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
        fetch(`<?= base_url('/bulletin/categories/delete/') ?>${id}`, {
            method: 'DELETE',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                loadCategories();
            } else {
                showMessage(data.message || 'Failed to delete category', 'error');
            }
        })
        .catch(() => {
            showMessage('An error occurred while deleting the category', 'error');
        });
    }
}

function addQuickCategory(name, description, color) {
    const formData = new FormData();
    formData.append('name', name);
    formData.append('description', description);
    formData.append('color', color);
    
    fetch('<?= base_url('/bulletin/categories/store') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(`${name} category added successfully!`, 'success');
            loadCategories();
        } else {
            showMessage(data.message || 'Failed to add category', 'error');
        }
    })
    .catch(() => {
        showMessage('An error occurred while adding the category', 'error');
    });
}

function closeModal() {
    document.getElementById('categoryModal').classList.add('hidden');
}

function showMessage(message, type) {
    const container = document.getElementById('messageContainer');
    const alert = document.getElementById('messageAlert');
    const icon = document.getElementById('messageIcon');
    const text = document.getElementById('messageText');
    
    text.textContent = message;
    
    // Reset classes
    alert.className = 'max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden';
    
    if (type === 'success') {
        alert.classList.add('border-l-4', 'border-green-400');
        icon.className = 'h-6 w-6 text-green-400';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
    } else {
        alert.classList.add('border-l-4', 'border-red-400');
        icon.className = 'h-6 w-6 text-red-400';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>';
    }
    
    container.classList.remove('hidden');
    
    // Auto hide after 5 seconds
    setTimeout(hideMessage, 5000);
}

function hideMessage() {
    document.getElementById('messageContainer').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('categoryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
