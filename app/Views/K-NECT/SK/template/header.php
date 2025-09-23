<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title>Knect - Youth Governance Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            // Suppress CDN warning
        }
    </script>
    <script>
        tailwind.config = {
            // Suppress CDN warning
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/5.0.4/css/fixedColumns.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/5.0.4/js/dataTables.fixedColumns.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/5.0.4/js/fixedColumns.dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    
    <!-- Panzoom -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/panzoom/panzoom.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/panzoom/panzoom.controls.css" />
    
    <!-- Invisible Event Auto-refresh -->
    <script src="/assets/js/invisible-event-refresh.js"></script>
    
    <!-- Panzoom JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/panzoom/panzoom.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/panzoom/panzoom.controls.umd.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Tagify -->
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
    
    <!-- K-NECT Image Fallback System -->
    <link href="<?= base_url('assets/css/image-fallback.css') ?>" rel="stylesheet" type="text/css" />

</head>
<body class="bg-gray-50 min-h-screen font-['Inter']">
    <!-- ===== HEADER SECTION ===== -->
    <!-- Fixed header aligned with main content (not overlapping sidebar) -->
    <header class="bg-white shadow-sm border-b border-gray-200 fixed top-0 left-0 lg:left-64 right-0 z-40">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Page Title Section -->
                <div class="flex items-center">
                    <button id="sidebarToggle" class="lg:hidden p-2 text-gray-400 hover:text-gray-600 mr-2 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded" aria-label="Toggle sidebar">
                        <svg class="w-6 h-6 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path id="hamburgerPath" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div class="flex-shrink-0">
                        <?php
                        // Dynamic page title based on current URL
                        $uri = uri_string();
                        $pageTitle = 'Dashboard';
                        $pageDescription = 'SK Management System';
                        
                        // Map URLs to titles and descriptions for SK section
                        $pageMap = [
                            'sk/dashboard' => [
                                'title' => 'Dashboard', 
                                'description' => 'SK overview and statistics'
                            ],
                            'sk/analytics' => [
                                'title' => 'Analytics', 
                                'description' => 'Demographic data and insights'
                            ],
                            'sk/event-analytics' => [
                                'title' => 'Event Analytics',
                                'description' => 'Participation trends and engagement'
                            ],
                            'sk/document-analytics' => [
                                'title' => 'Document Analytics',
                                'description' => 'Usage, categories, and approvals'
                            ],
                            'sk/performance-analytics' => [
                                'title' => 'Performance',
                                'description' => 'Barangay performance and activity'
                            ],
                            'sk/youth-profile' => [
                                'title' => 'Youth Profile', 
                                'description' => 'Community youth information'
                            ],
                            'sk/rfid-assignment' => [
                                'title' => 'RFID Assignment',
                                'description' => 'Manage RFID assignments for youth'
                            ],
                            'sk/profile' => [
                                'title' => 'My Profile', 
                                'description' => 'View and manage personal information'
                            ],
                            'events' => [
                                'title' => 'Events', 
                                'description' => 'Upcoming activities and programs'
                            ],
                            'events/create' => [
                                'title' => 'Create Event',
                                'description' => 'Plan and publish a new event'
                            ],
                            'events/edit' => [
                                'title' => 'Edit Event',
                                'description' => 'Update event details'
                            ],
                            'events/calendar' => [
                                'title' => 'Event Calendar',
                                'description' => 'Calendar view of events'
                            ],
                            'sk/attendance' => [
                                'title' => 'Attendance Management', 
                                'description' => 'Event participation tracking'
                            ],
                            'sk/attendanceReport' => [
                                'title' => 'Attendance Report',
                                'description' => 'Reports and exports'
                            ],
                            'admin/documents' => [
                                'title' => 'Document Management', 
                                'description' => 'Manage official documents and files'
                            ],
                            'documents' => [
                                'title' => 'Documents',
                                'description' => 'Files and official papers'
                            ],
                            'sk/sk-official' => [
                                'title' => 'SK Officials Management', 
                                'description' => 'Manage SK officials and their roles'
                            ],
                            'sk/settings' => [
                                'title' => 'SK Settings',
                                'description' => 'Manage SK settings'
                            ],
                            'bulletin' => [
                                'title' => 'Bulletin Board',
                                'description' => 'Announcements and updates'
                            ],
                            'bulletin/create' => [
                                'title' => 'Create Post',
                                'description' => 'Publish a new announcement'
                            ],
                            'bulletin/edit' => [
                                'title' => 'Edit Post',
                                'description' => 'Update announcement content'
                            ],
                            'bulletin/view' => [
                                'title' => 'View Post',
                                'description' => 'Announcement details'
                            ],
                        ];
                        
                        // Choose the best title/description based on current URI
                        if (isset($pageMap[$uri])) {
                            $pageTitle = $pageMap[$uri]['title'];
                            $pageDescription = $pageMap[$uri]['description'];
                        } else {
                            // Pick the longest matching route prefix
                            $bestRoute = null;
                            foreach ($pageMap as $route => $info) {
                                if (strpos($uri, $route) === 0) {
                                    if ($bestRoute === null || strlen($route) > strlen($bestRoute)) {
                                        $bestRoute = $route;
                                    }
                                }
                            }
                            if ($bestRoute !== null) {
                                $pageTitle = $pageMap[$bestRoute]['title'];
                                $pageDescription = $pageMap[$bestRoute]['description'];
                            }
                        }
                        ?>
                        <h2 class="text-lg font-semibold text-gray-900"><?= $pageTitle ?></h2>
                        <p class="text-sm text-gray-500"><?= $pageDescription ?></p>
                    </div>
                </div>
                    
                    <!-- User Profile Section -->
                    <div class="flex items-center space-x-4">
                        <!-- User Profile Dropdown -->
                        <div class="relative">
                        <button id="userDropdownBtn" class="flex items-center space-x-3 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg p-2">
                            <div class="flex items-center space-x-3">
                                <?php
                                    $profilePic = $currentUser['profile_picture'] ?? '';
                                    $imageData = safe_image_url($profilePic, 'avatar');
                                ?>
                                <img class="h-8 w-8 rounded-full object-cover" 
                                     src="<?= esc($imageData['src']) ?>" 
                                     alt="Profile"
                                     data-type="avatar"
                                     data-fallback="<?= esc($imageData['fallback']) ?>">
                                <div class="text-left hidden sm:block">
                                    <p class="text-sm font-medium text-gray-900">
                                        <?= $currentUser ? esc($currentUser['full_name']) : 'User' ?>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <?php if ($currentUser): ?>
                                            <?php if (!empty($currentUser['position_text'])): ?>
                                                <?= esc($currentUser['user_type_text']) ?> <?= esc($currentUser['position_text']) ?>
                                            <?php else: ?>
                                                <?= esc($currentUser['user_type_text']) ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <svg id="dropdownArrow" class="w-4 h-4 text-gray-400 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path id="arrowPath" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div id="userDropdownMenu" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                            <!-- User Info Section -->
                            <div class="p-4 border-b border-gray-200">
                                <div class="flex items-center space-x-3">
                                    <?php
                                        $profilePic = $currentUser['profile_picture'] ?? '';
                                        $imageData = safe_image_url($profilePic, 'avatar');
                                    ?>
                                    <img class="h-12 w-12 rounded-full object-cover" 
                                         src="<?= esc($imageData['src']) ?>" 
                                         alt="Profile"
                                         data-type="avatar"
                                         data-fallback="<?= esc($imageData['fallback']) ?>">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900">
                                            <?= $currentUser ? esc($currentUser['full_name']) : 'User' ?>
                                        </h4>
                                        <p class="text-xs text-gray-500">
                                            <?php if ($currentUser): ?>
                                                <?php if (!empty($currentUser['position_text'])): ?>
                                                    <?= esc($currentUser['user_type_text']) ?> - <?= esc($currentUser['position_text']) ?>
                                                <?php else: ?>
                                                    <?= esc($currentUser['user_type_text']) ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </p>
                                        <?php if ($currentUser && !empty($currentUser['barangay_name'])): ?>
                                            <p class="text-xs text-gray-500">
                                                Barangay <?= esc($currentUser['barangay_name']) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="p-2">
                                <a href="<?= base_url('sk/profile') ?>" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md flex items-center">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Profile
                                </a>
                                <form id="logoutForm" action="<?= base_url('logout') ?>" method="post" class="w-full">
                                    <button id="logoutBtn" type="submit" class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-md flex items-center">
                                        <svg class="w-4 h-4 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- ===== END HEADER SECTION ===== -->

    <script>
        // User dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownBtn = document.getElementById('userDropdownBtn');
            const dropdownMenu = document.getElementById('userDropdownMenu');
            const arrowPath = document.getElementById('arrowPath');
            const logoutForm = document.getElementById('logoutForm');
            const logoutBtn = document.getElementById('logoutBtn');
            
            // Arrow paths
            const downArrow = "M19 9l-7 7-7-7"; // Chevron down
            const upArrow = "M5 15l7-7 7 7";   // Chevron up
            
            if (dropdownBtn && dropdownMenu && arrowPath) {
                dropdownBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isHidden = dropdownMenu.classList.contains('hidden');
                    
                    // Toggle dropdown visibility
                    dropdownMenu.classList.toggle('hidden');
                    
                    // Change arrow direction
                    if (isHidden) {
                        // Dropdown is opening - change to up arrow
                        arrowPath.setAttribute('d', upArrow);
                    } else {
                        // Dropdown is closing - change to down arrow
                        arrowPath.setAttribute('d', downArrow);
                    }
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.classList.add('hidden');
                        // Reset arrow to down
                        arrowPath.setAttribute('d', downArrow);
                    }
                });
            }

            // Helper: header toast
            function showHeaderToast(message, type = 'info') {
                const note = document.createElement('div');
                note.className = 'header-stacked-toast fixed top-4 right-4 z-[99999] px-4 py-3 rounded-md shadow transition transform translate-x-full';
                let bg = '#3b82f6', color = '#fff';
                if (type === 'success') bg = '#16a34a';
                if (type === 'warning') bg = '#f59e0b';
                if (type === 'error') bg = '#dc2626';
                note.style.background = bg; note.style.color = color; note.textContent = message;
                const existing = document.querySelectorAll('.header-stacked-toast');
                note.style.marginTop = (existing.length * 56) + 'px';
                document.body.appendChild(note);
                setTimeout(() => note.classList.remove('translate-x-full'), 50);
                setTimeout(() => { note.classList.add('translate-x-full'); setTimeout(() => note.remove(), 350); }, 4800);
            }

            // Intercept logout to enforce credentials download requirement
            if (logoutForm && logoutBtn) {
                logoutForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    fetch('<?= base_url('pederasyon/credential-download-status') ?>', { credentials: 'same-origin' })
                        .then(r => r.json())
                        .then(st => {
                            if (st && st.success && st.require) {
                                const needSk = !st.sk;
                                const needPed = !st.pederasyon;
                                const msgs = [];
                                if (needSk) msgs.push('Credentials download required: SK');
                                if (needPed) msgs.push('Credentials download required: Pederasyon');
                                if (msgs.length) {
                                    msgs.forEach(m => showHeaderToast(m, 'warning'));
                                    // Redirect to Pederasyon Youth List to use credentials modal
                                    setTimeout(() => { window.location.href = '<?= base_url('pederasyon/youthlist') ?>'; }, 700);
                                    return;
                                }
                            }
                            // Allowed to logout
                            logoutForm.submit();
                        })
                        .catch(() => showHeaderToast('Unable to verify credential downloads. Please try again.', 'error'));
                });
            }

            // On-load reminder if required
            try {
                fetch('<?= base_url('pederasyon/credential-download-status') ?>', { credentials: 'same-origin' })
                    .then(r => r.ok ? r.json() : Promise.reject(new Error('Network error')))
                    .then(st => {
                        if (st && st.success && st.require) {
                            const needSk = !st.sk;
                            const needPed = !st.pederasyon;
                            if (needSk || needPed) {
                                showHeaderToast('Please download updated credentials. Logout is disabled until both SK and Pederasyon are downloaded.', 'warning');
                            }
                        }
                    })
                    .catch(() => {});
            } catch (e) {}

            // Sidebar toggle functionality
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const hamburgerPath = document.getElementById('hamburgerPath');
            
            // Hamburger and X icon paths
            const hamburgerIcon = "M4 6h16M4 12h16M4 18h16";
            const xIcon = "M6 18L18 6M6 6l12 12";
            
            // Function to close sidebar
            function closeSidebar() {
                sidebar.classList.remove('sidebar-open');
                sidebarOverlay.classList.add('hidden');
                hamburgerPath.setAttribute('d', hamburgerIcon);
            }
            
            if (sidebarToggle && sidebar && sidebarOverlay && hamburgerPath) {
                // Toggle sidebar on burger button click
                sidebarToggle.addEventListener('click', function() {
                    const isOpen = sidebar.classList.contains('sidebar-open');
                    
                    sidebar.classList.toggle('sidebar-open');
                    sidebarOverlay.classList.toggle('hidden');
                    
                    // Animate hamburger to X
                    if (!isOpen) {
                        // Opening sidebar - change to X
                        hamburgerPath.setAttribute('d', xIcon);
                    } else {
                        // Closing sidebar - change to hamburger
                        hamburgerPath.setAttribute('d', hamburgerIcon);
                    }
                });
                
                // Close sidebar when clicking close button
                if (sidebarClose) {
                    sidebarClose.addEventListener('click', closeSidebar);
                }
                
                // Close sidebar when clicking on overlay
                sidebarOverlay.addEventListener('click', closeSidebar);
                
                // Close sidebar on window resize if screen becomes large
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 1024) {
                        closeSidebar();
                    }
                });
                
                // Close sidebar with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && sidebar.classList.contains('sidebar-open')) {
                        closeSidebar();
                    }
                });
            }
        });
    </script>

<style>
        .sidebar-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-glass {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(25px);
            border-right: 1px solid rgba(0, 0, 0, 0.08);
        }
        
        /* Responsive Sidebar Styles */
        .sidebar-open {
            transform: translateX(0) !important;
        }
        
        @media (max-width: 1023px) {
            #sidebar {
                z-index: 50;
            }
        }
        .nav-item {
            transition: all 0.2s ease;
        }
        .nav-item:hover {
            background-color: #f5f5f5;
        }
        .nav-item.active {
            background-color: #eef2ff;
            color: #111827;
            border-left: 4px solid #3b82f6;
            box-shadow: none;
        }
        .shadow-strong {
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.15), 0 2px 10px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
    

<style>
        .sidebar-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-glass {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(25px);
            border-right: 1px solid rgba(0, 0, 0, 0.08);
        }
        
        /* Responsive Sidebar Styles */
        .sidebar-open {
            transform: translateX(0) !important;
        }
        
        @media (max-width: 1023px) {
            #sidebar {
                z-index: 50;
            }
        }
        .nav-item {
            transition: all 0.2s ease;
        }
        .nav-item:hover {
            background-color: #f5f5f5;
        }
        .nav-item.active {
            background-color: #eef2ff;
            color: #111827;
            border-left: 4px solid #3b82f6;
            box-shadow: none;
        }
        .shadow-strong {
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.15), 0 2px 10px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
    
<script>
    // Initialize current user variable
    <?php 
    $currentUser = $currentUser ?? [
        'first_name' => 'Guest',
        'last_name' => 'User',
        'profile_picture' => '',
        'full_name' => 'Guest User',
        'user_type_text' => 'Guest',
        'position_text' => ''
    ];
    ?>
    
    // Handle session messages: prefer toast if available, fallback to SweetAlert2
    document.addEventListener('DOMContentLoaded', function() {
        if (window.__suppressFlashToast) { return; }
        <?php if (session('success')): ?>
            (function(){
                var msg = '<?= addslashes(session('success')) ?>';
                if (typeof window.showNotification === 'function') {
                    window.showNotification(msg, 'success');
                } else if (typeof Swal !== 'undefined' && Swal.fire) {
                    Swal.fire({ title: 'Success!', text: msg, icon: 'success', confirmButtonColor: '#2563eb' });
                }
            })();
        <?php endif; ?>

        <?php if (session('error')): ?>
            (function(){
                var msg = '<?= addslashes(session('error')) ?>';
                if (typeof window.showNotification === 'function') {
                    window.showNotification(msg, 'error');
                } else if (typeof Swal !== 'undefined' && Swal.fire) {
                    Swal.fire({ title: 'Error!', text: msg, icon: 'error', confirmButtonColor: '#d33' });
                }
            })();
        <?php endif; ?>
    });
</script>
