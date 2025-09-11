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
							<a href="<?= base_url('/kk/dashboard') ?>" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl text-gray-700 hover:bg-gray-50 font-medium <?= (uri_string() == 'kk/dashboard') ? 'active' : '' ?>">
								<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h7v7H3V3zM14 3h7v7h-7V3zM14 14h7v7h-7v-7zM3 14h7v7H3v-7z"/>
								</svg>
								Dashboard
							</a>
                            
							<a href="<?= base_url('/events') ?>" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl text-gray-700 hover:bg-gray-50 font-medium <?= (uri_string() == 'events' || strpos(uri_string(), 'events/') === 0) ? 'active' : '' ?>">
								<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
								</svg>
								Events
							</a>
							
                            <a href="<?= base_url('/documents') ?>" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl text-gray-700 hover:bg-gray-50 font-medium <?= (strpos(uri_string(), 'documents') !== false) ? 'active' : '' ?>">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Documents
                            </a>
                            
                            <a href="<?= base_url('/kk/attendance') ?>" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl text-gray-700 hover:bg-gray-50 font-medium <?= (uri_string() == 'kk/attendance') ? 'active' : '' ?>">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Attendance
                            </a>
                            
                            <!-- <a href="#" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl text-gray-700 hover:bg-gray-50 font-medium">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                </svg>
                                Bulletin
                                <span class="ml-auto bg-red-100 text-red-800 text-sm px-2 py-1 rounded-full">3</span>
                            </a> -->
                        </div>
                    </div>
                </nav>

				<!-- Settings Section -->
                <div class="p-4 border-t border-gray-100">
                    <a href="<?= base_url('/kk/settings') ?>" class="nav-item flex items-center gap-3 p-3 rounded-xl text-gray-700 hover:bg-gray-50 font-medium <?= (uri_string() == 'kk/settings') ? 'active' : '' ?>">
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

