<!-- ===== LAYOUT CONTAINER ===== -->
    <div class="flex h-screen">
        <!-- ===== SIDEBAR SECTION ===== -->
        <!-- Fixed sidebar with navigation menu and logo -->
        <div id="sidebar" class="w-64 sidebar-glass shadow-strong sidebar-transition flex-shrink-0 fixed top-0 left-0 bottom-0 z-30 transform -translate-x-full lg:translate-x-0">
            <div class="flex flex-col h-full">
                <!-- Logo Section -->
                <div class="w-64 px-6 flex items-center justify-between h-16 border-b border-gray-200 bg-white">
                    <div class="flex-shrink-0">
                        <h1 class="text-xl font-bold text-gray-900">K-NECT</h1>
                        <p class="text-xs text-gray-500">Youth Governance</p>
                    </div>
                    <!-- Close button for mobile -->
                    <button id="sidebarClose" class="lg:hidden p-2 text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded" aria-label="Close sidebar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Main Navigation Menu -->
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                    <!-- Primary Navigation Section -->
                    <div class="mb-4">
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 px-3">Main Navigation</h3>
                        <div class="space-y-1">
                            <a href="<?= base_url('/sk/dashboard') ?>" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl text-gray-700 hover:bg-gray-50 font-medium <?= (uri_string() == 'sk/dashboard') ? 'active' : '' ?>">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h7v7H3V3zM14 3h7v7h-7V3zM14 14h7v7h-7v-7zM3 14h7v7H3v-7z"/>
                                </svg>
                                Dashboard
                            </a>
                            
                            <!-- Analytics Dropdown -->
                            <div class="relative">
                                <button class="nav-item w-full flex items-center gap-3 px-3 py-2 rounded-xl text-gray-700 hover:bg-gray-50 font-medium <?= (strpos(uri_string(), 'analytics') !== false) ? 'active' : '' ?>" onclick="toggleDropdown('analyticsDropdown')">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                    Analytics
                                    <svg class="w-4 h-4 ml-auto transform transition-transform duration-200" id="analyticsDropdownIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div class="hidden mt-2 ml-6 space-y-1" id="analyticsDropdown">
                                    <a href="<?= base_url('/sk/analytics') ?>" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg <?= (uri_string() == 'sk/analytics') ? 'bg-gray-100 text-gray-900' : '' ?>">
                                        Demographics
                                    </a>
                                    <a href="<?= base_url('/sk/event-analytics') ?>" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg <?= (uri_string() == 'sk/event-analytics') ? 'bg-gray-100 text-gray-900' : '' ?>">
                                        Event
                                    </a>
                                    <a href="<?= base_url('/sk/document-analytics') ?>" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg <?= (uri_string() == 'sk/document-analytics') ? 'bg-gray-100 text-gray-900' : '' ?>">
                                        Document
                                    </a>
                                    <a href="<?= base_url('/sk/performance-analytics') ?>" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg <?= (uri_string() == 'sk/performance-analytics') ? 'bg-gray-100 text-gray-900' : '' ?>">
                                        Performance
                                    </a>
                                </div>
                            </div>
                            
                            <a href="<?= base_url('/sk/youth-profile') ?>" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl text-gray-700 hover:bg-gray-50 font-medium <?= (uri_string() == 'sk/youth-profile') ? 'active' : '' ?>">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Youth Profile
                            </a>
                            
                            <a href="<?= base_url('/events') ?>" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl text-gray-700 hover:bg-gray-50 font-medium <?= (uri_string() == 'events' || strpos(uri_string(), 'events/') === 0) ? 'active' : '' ?>">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Event
                            </a>

                            <a href="<?= base_url('/sk/attendance') ?>" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl text-gray-700 hover:bg-gray-50 font-medium <?= (uri_string() == 'sk/attendance') ? 'active' : '' ?>">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Attendance
                            </a>

                            <a href="<?= base_url('/bulletin') ?>" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl text-gray-700 hover:bg-gray-50 font-medium <?= (uri_string() == 'bulletin' || strpos(uri_string(), 'bulletin/') === 0) ? 'active' : '' ?>">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                                Bulletin Board
                            </a>
                            
                            <a href="<?= base_url('/admin/documents') ?>" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl text-gray-700 hover:bg-gray-50 font-medium <?= (strpos(uri_string(), 'admin/documents') === 0 || strpos(uri_string(), 'documents') === 0) ? 'active' : '' ?>">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Document
                            </a>

                        </div>
                    </div>

                    <!-- Quick Actions Section -->
                    <div class="mb-4">
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 px-3">Quick Actions</h3>
                        <div class="space-y-1">
                            <a href="<?= base_url('/sk/sk-official') ?>" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl text-gray-700 hover:bg-gray-50 font-medium <?= (uri_string() == 'sk/sk-official') ? 'active' : '' ?>">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                SK Official
                            </a>
                        </div>
                    </div>
                </nav>

                <!-- Settings Section -->
                <div class="p-4 border-t border-gray-100">
                    <a href="<?= base_url('/sk/settings') ?>" class="nav-item flex items-center gap-3 p-3 rounded-xl text-gray-700 hover:bg-gray-50 font-medium <?= (uri_string() == 'sk/settings') ? 'active' : '' ?>">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Settings
                    </a>
                </div>
            </div>
        </div> 
        <!-- ===== END SIDEBAR SECTION ===== -->

        <!-- Sidebar Overlay for Mobile -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden hidden"></div>
<script>
    // Dropdown toggle functionality
    function toggleDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        const icon = document.getElementById(dropdownId + 'Icon');
        
        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            dropdown.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }

    // Keep dropdown open if current page is in dropdown
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        const analyticsPages = ['/sk/analytics', '/sk/event-analytics', '/sk/document-analytics', '/sk/performance-analytics'];
        
        if (analyticsPages.some(page => currentPath.includes(page))) {
            const dropdown = document.getElementById('analyticsDropdown');
            const icon = document.getElementById('analyticsDropdownIcon');
            dropdown.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        }
    });
</script>