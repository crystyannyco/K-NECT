<!-- Main Content Area -->
<?= $this->include('K-NECT/includes/bulletin-assets') ?>
<div class="flex-1 lg:ml-64 min-h-screen bg-gray-50 pt-24">
    <!-- Unified compact header removed per request -->

    <!-- System-wide Stats removed per request -->

    <!-- Urgent Posts Section -->
    <?php if (!empty($urgent_posts)): ?>
    <div class="py-4 px-6">
        <div class="bg-red-50 border border-red-200 p-4 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-red-900">Urgent Announcements</h3>
                    <div class="mt-2">
                        <?php foreach ($urgent_posts as $urgent): ?>
                        <div class="text-sm text-red-700 mb-1 flex items-center justify-between">
                            <a href="<?= base_url('/bulletin/view/' . $urgent['id']) ?>" class="font-medium hover:text-red-900">
                                <?= esc($urgent['title']) ?>
                            </a>
                            <a href="<?= base_url('/bulletin/edit/' . $urgent['id']) ?>" class="text-red-600 hover:text-red-800 ml-4">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Content Grid -->
    <div class="py-6 px-6">
        <div class="grid grid-cols-1 gap-6">
            <!-- Main Content Area -->
            <div class="w-full">

                <!-- Featured Posts (moved above Events) -->
                <?php if (!empty($featured_posts)): ?>
                <div class="mb-8">
                    <!-- Featured Posts heading removed per request -->
                    <?php $primary = $featured_posts[0]; $others = array_slice($featured_posts, 1, 4); ?>
                    <div class="grid grid-cols-1 lg:grid-cols-3 lg:auto-rows-[220px] gap-5">
                        <!-- Primary featured (large) -->
                        <article class="relative overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm lg:col-span-2 lg:row-span-2">
                            <?php 
                                $pImg = !empty($primary['featured_image']) ? base_url('uploads/bulletin/' . $primary['featured_image']) : null;
                            ?>
                            <div class="relative w-full h-64 md:h-72 lg:h-full">
                                <?php if ($pImg): ?>
                                    <img src="<?= $pImg ?>" alt="<?= esc($primary['title']) ?>" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                                <?php else: ?>
                                    <div class="absolute inset-0 bg-gradient-to-br from-slate-700 to-slate-900"></div>
                                <?php endif; ?>
                                <div class="absolute top-3 left-3 flex gap-2 flex-wrap">
                                    <?php if (!empty($primary['category_name'])): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-white/90 text-gray-800"><?= esc($primary['category_name']) ?></span>
                                    <?php endif; ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Featured</span>
                                </div>
                                <div class="absolute bottom-4 left-4 right-4 text-white">
                                    <h3 class="text-2xl font-bold leading-tight mb-2">
                                        <a href="<?= base_url('/bulletin/view/' . $primary['id']) ?>" class="hover:underline">
                                            <?= esc($primary['title']) ?>
                                        </a>
                                    </h3>
                                    <p class="hidden md:block text-white/90 mb-3">
                                        <?= esc($primary['excerpt'] ?: substr(strip_tags($primary['content'] ?? ''), 0, 140) . '...') ?>
                                    </p>
                                    <div class="flex items-center text-sm text-white/80 gap-3">
                                        <span class="inline-flex items-center gap-1"><i class="fa-regular fa-calendar"></i> <?= !empty($primary['published_at']) || !empty($primary['created_at']) ? date('M d, Y', strtotime($primary['published_at'] ?: $primary['created_at'])) : '' ?></span>
                                    </div>
                                </div>
                            </div>
                        </article>

                        <!-- Secondary featured (small grid) -->
                        <?php foreach ($others as $item): ?>
                        <article class="relative overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm h-52 lg:h-auto">
                            <?php $sImg = !empty($item['featured_image']) ? base_url('uploads/bulletin/' . $item['featured_image']) : null; ?>
                            <div class="relative w-full h-full">
                                <?php if ($sImg): ?>
                                    <img src="<?= $sImg ?>" alt="<?= esc($item['title']) ?>" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                                <?php else: ?>
                                    <div class="absolute inset-0 bg-gradient-to-br from-slate-600 to-slate-800"></div>
                                <?php endif; ?>
                                <div class="absolute top-2 left-2 flex gap-2 flex-wrap">
                                    <?php if (!empty($item['category_name'])): ?>
                                        <span class="inline-flex items-center rounded-full bg-white/90 text-gray-800 text-[10px] px-2 py-0.5 font-medium"><?= esc($item['category_name']) ?></span>
                                    <?php endif; ?>
                                    <span class="inline-flex items-center rounded-full bg-yellow-100 text-yellow-800 text-[10px] px-2 py-0.5 font-medium">Featured</span>
                                </div>
                                <div class="absolute bottom-2 left-2 right-2 text-white">
                                    <h4 class="text-base font-semibold leading-snug line-clamp-2">
                                        <a href="<?= base_url('/bulletin/view/' . $item['id']) ?>" class="hover:underline">
                                            <?= esc($item['title']) ?>
                                        </a>
                                    </h4>
                                    <div class="mt-1 text-xs text-white/80 flex items-center gap-2">
                                        <i class="fa-regular fa-calendar"></i> <?= !empty($item['published_at']) || !empty($item['created_at']) ? date('M d, Y', strtotime($item['published_at'] ?: $item['created_at'])) : '' ?>
                                    </div>
                                </div>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Upcoming Events Preview -->
                <?php if (!empty($recent_events)): ?>
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fa-regular fa-calendar-days text-blue-600 mr-2"></i>
                        Upcoming Events
                    </h2>
                    <div class="grid gap-5 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        <?php foreach ($recent_events as $event): ?>
                            <?php 
                                $date = !empty($event['event_date']) ? strtotime($event['event_date']) : null;
                                $month = $date ? date('M', $date) : '';
                                $day = $date ? date('d', $date) : '';
                                $banner = !empty($event['event_banner']) ? base_url('uploads/event/' . $event['event_banner']) : null;
                            ?>
                            <article class="overflow-hidden rounded-xl border bg-white shadow-sm hover:shadow-md transition">
                                <div class="relative h-36 w-full text-white">
                                    <?php if ($banner): ?>
                                        <img src="<?= $banner ?>" alt="<?= esc($event['title'] ?? 'Event') ?>" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                    <?php else: ?>
                                        <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-blue-400"></div>
                                        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 20%, rgba(255,255,255,.2), transparent 40%), radial-gradient(circle at 80% 30%, rgba(255,255,255,.2), transparent 40%);"></div>
                                    <?php endif; ?>
                                    <div class="absolute top-3 left-3 text-center bg-white/10 rounded-lg px-2 py-1">
                                        <div class="text-xs uppercase tracking-wide text-white/90"><?= esc($month) ?></div>
                                        <div class="text-lg font-bold -mt-1"><?= esc($day) ?></div>
                                    </div>
                                    <div class="absolute bottom-3 left-3 right-3">
                                        <p class="text-sm font-semibold line-clamp-2"><?= esc($event['title'] ?? 'Scheduled Event') ?></p>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <div class="flex items-center justify-between text-xs text-gray-600">
                                        <span><i class="fa-regular fa-clock mr-1"></i><?= $date ? date('M d, Y g:i A', $date) : '' ?></span>
                                        <span class="inline-flex items-center gap-1 text-blue-700"><i class="fa-regular fa-star"></i>Upcoming</span>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                <!-- Featured Posts (original block removed; moved above) -->

                <!-- Uploaded Documents Preview (carousel) -->
                <?php if (!empty($recent_documents)): ?>
                <div class="mb-8 px-0">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fa-regular fa-folder-open text-blue-600 mr-2"></i>
                        Uploaded Documents
                    </h2>
                    <div class="doc-carousel relative">
                        <button type="button" aria-label="Previous documents" class="doc-carousel-btn prev disabled:opacity-40 disabled:cursor-not-allowed absolute left-0 top-1/2 -translate-y-1/2 z-10 w-9 h-9 rounded-full bg-white shadow flex items-center justify-center text-gray-600 hover:text-blue-600 hover:shadow-md transition"><i class="fa-solid fa-chevron-left"></i></button>
                        <div class="pointer-events-none absolute inset-y-0 left-0 w-10 bg-gradient-to-r from-white to-transparent hidden md:block"></div>
                        <div class="pointer-events-none absolute inset-y-0 right-0 w-10 bg-gradient-to-l from-white to-transparent hidden md:block"></div>
                        <div class="doc-carousel-viewport overflow-x-auto">
                            <div class="doc-carousel-track flex gap-5">
                                <?php foreach ($recent_documents as $doc): ?>
                                    <?php 
                                        $filePath = $doc['file_path'] ?? '';
                                        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                        $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                                        $docUrl = base_url($filePath);
                                        $icon = 'fa-file-lines text-gray-500';
                                        if ($ext === 'pdf') $icon = 'fa-file-pdf text-red-500';
                                        elseif (in_array($ext,['doc','docx'])) $icon = 'fa-file-word text-blue-500';
                                        elseif (in_array($ext,['xls','xlsx','csv'])) $icon = 'fa-file-excel text-green-600';
                                        elseif (in_array($ext,['ppt','pptx'])) $icon = 'fa-file-powerpoint text-orange-500';
                                    ?>
                                    <article class="w-64 sm:w-72 flex-shrink-0 bg-white rounded-xl border shadow-sm overflow-hidden hover:shadow-md transition">
                                        <div class="relative h-36 w-full overflow-hidden bg-gray-100 flex items-center justify-center">
                                            <?php if ($isImage): ?>
                                                <img src="<?= $docUrl ?>" alt="<?= esc($doc['filename'] ?? 'Document') ?>" class="w-full h-full object-cover">
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                                            <?php else: ?>
                                                <i class="fa-regular <?= $icon ?> text-5xl"></i>
                                            <?php endif; ?>
                                            <div class="absolute top-3 left-3">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-white text-gray-800"><?= strtoupper($ext ?: 'FILE') ?></span>
                                            </div>
                                            <div class="absolute bottom-3 right-3">
                                                <button onclick="previewDocument('<?= esc($docUrl) ?>', <?= $isImage ? 'true':'false' ?>)" class="inline-flex items-center px-3 py-1.5 rounded-md bg-white/90 text-gray-800 text-xs font-medium hover:bg-white">
                                                    <i class="fa-regular fa-eye mr-1"></i> Preview
                                                </button>
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <h3 class="text-sm font-semibold text-gray-900 truncate" title="<?= esc($doc['filename'] ?? 'Untitled Document') ?>">
                                                <?= esc($doc['filename'] ?? 'Untitled Document') ?>
                                            </h3>
                                            <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                                                <span><?= !empty($doc['created_at']) ? date('M d, Y', strtotime($doc['created_at'])) : '' ?></span>
                                                <a href="<?= esc($docUrl) ?>" target="_blank" class="text-blue-600 hover:text-blue-700">Open</a>
                                            </div>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <button type="button" aria-label="Next documents" class="doc-carousel-btn next disabled:opacity-40 disabled:cursor-not-allowed absolute right-0 top-1/2 -translate-y-1/2 z-10 w-9 h-9 rounded-full bg-white shadow flex items-center justify-center text-gray-600 hover:text-blue-600 hover:shadow-md transition"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Reintroduced compact hero header under Documents -->
                <div class="px-0 py-4 bulletin-wrap">
                    <div class="mx-auto">
                        <div class="p-4 md:p-5 rounded-2xl shadow-md border border-gray-200 bg-white flex flex-col gap-3 animate-fade-in bulletin-header">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-1">
                                <div class="min-w-0">
                                    <?php $welcomeName = $currentUser['first_name'] ?? session('first_name') ?? ($currentUser['full_name'] ?? session('full_name') ?? 'Admin'); ?>
                                    <div class="text-[1.4rem] md:text-[1.6rem] font-extrabold text-gray-900 leading-tight tracking-tight animate-slide-up truncate">
                                        Welcome, <span class="text-blue-700 drop-shadow-[0_1px_0_rgba(59,130,246,0.25)]"><?= esc($welcomeName) ?></span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="<?= base_url('/bulletin/categories') ?>" class="inline-flex items-center justify-center gap-2 text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:ring-offset-1 focus:ring-offset-white px-3.5 py-2 rounded-lg text-sm font-medium shadow-sm border border-gray-200">
                                        <i class="fa-regular fa-folder-open"></i>
                                        <span class="whitespace-nowrap">Manage Categories</span>
                                    </a>
                                    <a href="<?= base_url('/bulletin/create') ?>" class="inline-flex items-center justify-center gap-2 text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500/60 focus:ring-offset-1 focus:ring-offset-white px-3.5 py-2 rounded-lg text-sm font-semibold shadow-sm">
                                        <i class="fa-solid fa-plus"></i>
                                        <span class="whitespace-nowrap">Create Post</span>
                                    </a>
                                    <button type="button" onclick="window.location.reload()" class="inline-flex items-center justify-center gap-2 text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500/60 focus:ring-offset-1 focus:ring-offset-white px-3.5 py-2 rounded-lg text-sm font-semibold shadow-sm">
                                        <i class="fa-solid fa-rotate"></i>
                                        <span class="whitespace-nowrap">Refresh</span>
                                    </button>
                                </div>
                            </div>

                            <form id="headerSearchForm" class="flex flex-col sm:flex-row gap-2.5 w-full items-center bg-gradient-to-r from-blue-50 to-indigo-50/70 rounded-2xl shadow-sm p-3.5 md:p-4 border border-blue-100 transition-all duration-300 hover:shadow-md">
                                <div class="relative flex-1 w-full">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                                    </div>
                                    <input type="text" id="header-search" placeholder="Search posts..." class="block w-full pl-10 pr-3 h-10 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500/70 focus:border-blue-500 bg-white text-gray-900 placeholder-gray-400 text-sm shadow-sm" />
                                </div>

                                <select id="header-category" class="w-full sm:w-auto h-10 border border-gray-200 rounded-lg px-3.5 text-sm focus:ring-2 focus:ring-blue-500/70 focus:border-blue-500 bg-white text-gray-900 shadow-sm">
                                    <option value="">All Categories</option>
                                    <?php foreach (($categories ?? []) as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>

                                <select id="header-status" class="w-full sm:w-auto h-10 border border-gray-200 rounded-lg px-3.5 text-sm focus:ring-2 focus:ring-blue-500/70 focus:border-blue-500 bg-white text-gray-900 shadow-sm">
                                    <option value="all">All Status</option>
                                    <option value="featured">Featured</option>
                                    <option value="urgent">Urgent</option>
                                </select>

                                <button id="header-search-btn" type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-10 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 rounded-lg text-sm font-semibold shadow-sm hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500/60 focus:ring-offset-1 focus:ring-offset-white transition">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    <span>Search</span>
                                </button>

                                <button id="header-clear-btn" type="button" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-10 bg-white text-gray-700 px-4 rounded-lg text-sm font-semibold shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:ring-offset-1 focus:ring-offset-white border border-gray-200">
                                    <i class="fa-regular fa-circle-xmark"></i>
                                    <span>Clear</span>
                                </button>
                            </form>

                            <div class="flex flex-wrap items-center gap-2">
                                <button type="button" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full border text-sm font-medium bg-white text-gray-700 border-gray-200 hover:border-blue-200 hover:bg-blue-50/60" data-kk-chip="all">All</button>
                                <button type="button" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full border text-sm font-medium bg-yellow-50 text-yellow-800 border-yellow-200 hover:bg-yellow-100" data-kk-chip="featured">Featured</button>
                                <button type="button" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full border text-sm font-medium bg-red-50 text-red-700 border-red-200 hover:bg-red-100" data-kk-chip="urgent">Urgent</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- All Posts Grid - Visible by default, sleek header -->
                <div id="allPostsSection" class="mt-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-list text-gray-500"></i>
                        <span>All Posts</span>
                    </h2>
                    <div id="posts-container" class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        <?php if (!empty($posts)): ?>
                            <?php foreach ($posts as $post): ?>
                            <article class="group relative flex flex-col bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition" data-id="<?= $post['id'] ?>">
                                <div class="relative media w-full overflow-hidden rounded-t-xl">
                                    <?php if ($post['featured_image']): ?>
                                        <img src="<?= base_url('uploads/bulletin/' . $post['featured_image']) ?>" alt="<?= esc($post['title']) ?>" class="w-full h-full object-cover duration-500 group-hover:scale-105">
                                    <?php else: ?>
                                        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center text-blue-300"><i class="fa-regular fa-image text-3xl"></i></div>
                                    <?php endif; ?>
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent opacity-60 group-hover:opacity-70 transition"></div>
                                    <div class="absolute top-2 left-2 flex flex-wrap gap-1">
                                        <?php if ($post['category_name']): ?><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold" style="background-color: <?= $post['category_color'] ?>20;color: <?= $post['category_color'] ?>;"><?= esc($post['category_name']) ?></span><?php endif; ?>
                                        <?php if ($post['is_featured']): ?><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-yellow-100 text-yellow-700">Featured</span><?php endif; ?>
                                        <?php if ($post['is_urgent']): ?><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-100 text-red-700">Urgent</span><?php endif; ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-700"><?= ucfirst($post['visibility']) ?></span>
                                        <?php if ($post['status'] !== 'published'): ?><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-200 text-gray-700"><?= ucfirst($post['status']) ?></span><?php endif; ?>
                                    </div>
                                </div>
                                <div class="p-4 flex flex-col gap-2 flex-1">
                                    <h3 class="text-base font-semibold text-gray-900 leading-snug line-clamp-2 group-hover:text-blue-600 transition">
                                        <a href="<?= base_url('/bulletin/view/' . $post['id']) ?>" class="relative z-10 hover:underline">
                                            <?= esc($post['title']) ?>
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-600 leading-relaxed line-clamp-3"><?= esc($post['excerpt'] ?: substr(strip_tags($post['content']), 0, 220) . '...') ?></p>
                                </div>
                                <div class="px-4 pb-4 flex items-center justify-between text-xs text-gray-500">
                                    <div class="flex items-center gap-2">
                                        <div class="h-6 w-6 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center text-[10px] font-medium shadow-sm">
                                            <?php $initial = strtoupper(substr(($post['first_name'] ?? 'U'),0,1)); echo esc($initial); ?>
                                        </div>
                                        <span><?= esc($post['first_name'] . ' ' . $post['last_name']) ?></span>
                                        <span class="text-gray-400">•</span>
                                        <span><?= date('M d, Y', strtotime($post['published_at'] ?: $post['created_at'])) ?></span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="flex items-center gap-1"><i class="fa-regular fa-eye"></i><?= number_format($post['view_count']) ?></span>
                                    </div>
                                </div>
                                <!-- Removed full overlay anchor to allow edit/delete clicks -->
                            </article>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-span-full">
                                <div class="bg-white border border-dashed border-gray-300 rounded-xl p-10 text-center">
                                    <div class="mx-auto w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                                        <i class="fa-regular fa-newspaper text-xl"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">No posts yet</h3>
                                    <p class="text-gray-500 mt-1">Create your first announcement to populate the bulletin.</p>
                                    <a href="<?= base_url('/bulletin/create') ?>" class="mt-4 inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
                                        <i class="fa-solid fa-plus mr-2"></i>Create Post
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 hidden">
                <div class="space-y-6">
                    <!-- Sidebar content (Quick Actions removed) -->

                        <style>
                            .chip-btn { display:inline-flex; align-items:center; padding:0.375rem 0.75rem; border-radius:9999px; font-size:0.75rem; font-weight:600; color:rgba(255,255,255,0.9); border:1px solid rgba(255,255,255,0.2); transition:all .2s ease; background:rgba(255,255,255,0.08); }
                            .chip-btn:hover { color:#fff; border-color:rgba(255,255,255,0.4); }
                            .chip-btn.active { background:rgba(255,255,255,0.22); }
                            .card { background:#fff; border-radius:0.75rem; border:1px solid #e5e7eb; box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04); transition: box-shadow .2s ease; overflow:hidden; }
                            .card:hover { box-shadow:0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); }
                            .card-title { font-size:1.125rem; line-height:1.5rem; font-weight:700; color:#111827; }
                            .card-excerpt { color:#4b5563; margin-top:0.25rem; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }
                            .badge { display:inline-flex; align-items:center; padding:0.125rem 0.625rem; border-radius:9999px; font-size:0.75rem; font-weight:600; }
                            .shadow-strong { box-shadow: 0 10px 40px -10px rgba(0,0,0,.15), 0 2px 10px -2px rgba(0,0,0,.05); }
                        </style>

                        <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const postsContainer = document.getElementById('posts-container');
                            // Header controls just below Documents
                            const headerForm = document.getElementById('headerSearchForm');
                            const headerSearch = document.getElementById('header-search');
                            const headerCategory = document.getElementById('header-category');
                            const headerStatus = document.getElementById('header-status');
                            const headerClearBtn = document.getElementById('header-clear-btn');
                            const headerChips = document.querySelectorAll('[data-kk-chip]');
                            // Legacy fallbacks (if ever present)
                            const legacySearch = document.getElementById('search-posts');
                            const legacyCategory = document.getElementById('category-filter');

                            // All Posts section references (now always visible)
                            const allPostsSection = document.getElementById('allPostsSection');
                            function ensureAllPostsVisible() { /* no-op, section is visible by default */ }

                            const baseViewUrl = '<?= base_url('/bulletin/view/') ?>';
                            const baseImgUrl = '<?= base_url('uploads/bulletin/') ?>';

                            function setActiveChip(key) {
                                // update quick chips visual state
                                headerChips.forEach(btn => {
                                    const isActive = btn.dataset.kkChip === key;
                                    btn.classList.toggle('ring-2', isActive);
                                    btn.classList.toggle('ring-blue-500/60', isActive);
                                    btn.classList.toggle('border-blue-300', isActive);
                                });
                                // reflect in status select if present
                                if (headerStatus) {
                                    headerStatus.value = key === 'all' ? 'all' : key;
                                }
                            }

                                                        function showSkeleton(count=4){
                                                                postsContainer.innerHTML = Array.from({length:count}).map(()=>`
                                                                    <div class=\"flex flex-col bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden\">
                                                                        <div class=\"h-40 w-full bg-slate-200 animate-pulse\"></div>
                                                                        <div class=\"p-4 space-y-3 flex-1\">
                                                                            <div class=\"h-4 w-3/4 bg-slate-200 rounded animate-pulse\"></div>
                                                                            <div class=\"h-3 w-full bg-slate-200 rounded animate-pulse\"></div>
                                                                            <div class=\"h-3 w-5/6 bg-slate-200 rounded animate-pulse\"></div>
                                                                        </div>
                                                                        <div class=\"px-4 pb-4 flex items-center gap-3\">
                                                                            <div class=\"h-6 w-6 rounded-full bg-slate-200 animate-pulse\"></div>
                                                                            <div class=\"h-3 w-24 bg-slate-200 rounded animate-pulse\"></div>
                                                                        </div>
                                                                    </div>
                                                                `).join('');
                                                        }
                            function boolish(v){
                                if (v === true || v === 1 || v === '1') return true;
                                if (typeof v === 'string') {
                                    const s = v.toLowerCase();
                                    if (s === 'true' || s === 'yes') return true;
                                }
                                return false;
                            }

                            function renderPosts(posts){
                                if (!Array.isArray(posts) || posts.length === 0) {
                                    postsContainer.innerHTML = `<div class=\"col-span-full\"><div class=\"bg-white border border-dashed border-gray-300 rounded-xl p-10 text-center\"><div class=\"mx-auto w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-4\"><i class=\"fa-regular fa-newspaper text-xl\"></i></div><h3 class=\"text-lg font-semibold text-gray-900\">No posts found</h3><p class=\"text-gray-500 mt-1\">Try different keywords or filters.</p></div></div>`;
                                    return;
                                }
                                postsContainer.innerHTML = posts.map(post => {
                                    const title = post.title || 'Untitled';
                                    const hasImg = !!post.featured_image;
                                    const categoryChip = post.category_name ? `<span class=\"inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold\" style=\"background-color:${post.category_color}20;color:${post.category_color}\">${post.category_name}</span>` : '';
                                    const featChip = boolish(post.is_featured) ? `<span class=\"inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-yellow-100 text-yellow-700\">Featured</span>` : '';
                                    const urgentChip = boolish(post.is_urgent) ? `<span class=\"inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-100 text-red-700\">Urgent</span>` : '';
                                    const visChip = `<span class=\"inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-700\">${(post.visibility||'public')[0].toUpperCase()+(post.visibility||'public').slice(1)}</span>`;
                                    const statusChip = (post.status && post.status !== 'published') ? `<span class=\"inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-200 text-gray-700\">${post.status[0].toUpperCase()+post.status.slice(1)}</span>` : '';
                                    const excerpt = (post.excerpt || (post.content||'').replace(/<[^>]*>/g,'')).substring(0,220);
                                    const dateStr = post.published_at || post.created_at ? new Date(post.published_at || post.created_at).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'}) : '';
                                    const views = parseInt(post.view_count||0).toLocaleString();
                                    const initial = ((post.first_name||'U')[0]||'U').toUpperCase();
                                    return `<article class=\"group relative flex flex-col bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition\" data-id=\"${post.id}\">\n <div class=\"relative media w-full overflow-hidden rounded-t-xl\">\n ${hasImg?`<img src=\"${baseImgUrl}${post.featured_image}\" alt=\"${title}\" class=\"w-full h-full object-cover duration-500 group-hover:scale-105\">`:`<div class=\"absolute inset-0 bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center text-blue-300\"><i class=\"fa-regular fa-image text-3xl\"></i></div>`}\n <div class=\"absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent opacity-60 group-hover:opacity-70 transition\"></div>\n <div class=\"absolute top-2 left-2 flex flex-wrap gap-1\">${categoryChip}${featChip}${urgentChip}${visChip}${statusChip}</div>\n </div>\n <div class=\"p-4 flex flex-col gap-2 flex-1\">\n <h3 class=\"text-base font-semibold text-gray-900 leading-snug line-clamp-2 group-hover:text-blue-600 transition\"><a href=\"${baseViewUrl}${post.id}\" class=\"relative z-10\">${title}</a></h3>\n <p class=\"text-sm text-gray-600 leading-relaxed line-clamp-3\">${excerpt}${excerpt.length>=220?'...':''}</p>\n </div>\n <div class=\"px-4 pb-4 flex items-center justify-between text-xs text-gray-500\">\n <div class=\"flex items-center gap-2\">\n <div class=\"h-6 w-6 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center text-[10px] font-medium shadow-sm\">${initial}</div>\n <span>${(post.first_name||'')+' '+(post.last_name||'')}</span><span class=\"text-gray-400\">•</span><span>${dateStr}</span>\n </div>\n <div class=\"flex items-center gap-3\"><span class=\"flex items-center gap-1\"><i class=\"fa-regular fa-eye\"></i>${views}</span></div>\n </div>\n <a href=\"${baseViewUrl}${post.id}\" class=\"absolute inset-0\" aria-label=\"Read post: ${title}\"></a>\n</article>`;
                                }).join('');
                            }

                            async function fetchAndRender({ q = '', categoryId = '', status = 'all' } = {}) {
                                try {
                                    let url = '';
                                    let list = [];
                                    // Decide data source
                                    if (q) {
                                        url = `<?= base_url('/bulletin/search') ?>?q=${encodeURIComponent(q)}&limit=30&offset=0`;
                                    } else if (categoryId) {
                                        url = `<?= base_url('/bulletin/category') ?>/${encodeURIComponent(categoryId)}?limit=30&offset=0`;
                                    }

                                    if (url) {
                                        showSkeleton();
                                        const res = await fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                                        const data = await res.json();
                                        if (!data.success) throw new Error(data.message || 'Failed to load');
                                        list = Array.isArray(data.posts) ? data.posts : [];
                                    } else {
                                        list = <?= json_encode($posts ?? []) ?>;
                                    }

                                    // Apply status filter client-side
                                    if (status === 'featured') list = list.filter(p => boolish(p.is_featured));
                                    else if (status === 'urgent') list = list.filter(p => boolish(p.is_urgent));

                                    renderPosts(list);
                                } catch (e) {
                                    console.error(e);
                                    renderPosts([]);
                                }
                            }

                            // Debounced typing in header search
                            let searchDebounce;
                            if (headerSearch) {
                                headerSearch.addEventListener('input', () => {
                                    clearTimeout(searchDebounce);
                                    const term = headerSearch.value.trim();
                                    searchDebounce = setTimeout(() => {
                                        ensureAllPostsVisible();
                                        setActiveChip('all');
                                        fetchAndRender({ q: term, categoryId: headerCategory ? headerCategory.value : '', status: 'all' });
                                    }, 300);
                                });
                            }

                            // Category change
                            if (headerCategory) {
                                headerCategory.addEventListener('change', () => {
                                    ensureAllPostsVisible();
                                    setActiveChip('all');
                                    const cid = headerCategory.value;
                                    const term = headerSearch ? headerSearch.value.trim() : '';
                                    fetchAndRender({ q: term, categoryId: cid, status: 'all' });
                                });
                            }

                            // Status change via dropdown
                            if (headerStatus) {
                                headerStatus.addEventListener('change', () => {
                                    ensureAllPostsVisible();
                                    const status = headerStatus.value;
                                    setActiveChip(status === 'all' ? 'all' : status);
                                    const cid = headerCategory ? headerCategory.value : '';
                                    const term = headerSearch ? headerSearch.value.trim() : '';
                                    fetchAndRender({ q: term, categoryId: cid, status });
                                });
                            }

                            // Quick chips (All/Featured/Urgent)
                            headerChips.forEach(btn => btn.addEventListener('click', () => {
                                const key = btn.dataset.kkChip; // 'all' | 'featured' | 'urgent'
                                setActiveChip(key);
                                ensureAllPostsVisible();
                                const cid = headerCategory ? headerCategory.value : '';
                                const term = headerSearch ? headerSearch.value.trim() : '';
                                fetchAndRender({ q: term, categoryId: cid, status: key });
                            }));

                            // Header form submit (Search button)
                            if (headerForm) {
                                headerForm.addEventListener('submit', (e) => {
                                    e.preventDefault();
                                    ensureAllPostsVisible();
                                    const term = headerSearch ? headerSearch.value.trim() : '';
                                    const cid = headerCategory ? headerCategory.value : '';
                                    const status = headerStatus ? headerStatus.value : 'all';
                                    fetchAndRender({ q: term, categoryId: cid, status });
                                });
                            }

                            // Clear button
                            if (headerClearBtn) {
                                headerClearBtn.addEventListener('click', () => {
                                    if (headerSearch) headerSearch.value = '';
                                    if (headerCategory) headerCategory.value = '';
                                    if (headerStatus) headerStatus.value = 'all';
                                    setActiveChip('all');
                                    ensureAllPostsVisible();
                                    renderPosts(<?= json_encode($posts ?? []) ?>);
                                });
                            }

                            // Legacy fallbacks (if an older header exists somewhere)
                            if (legacySearch) {
                                let legacyDebounce;
                                legacySearch.addEventListener('input', () => {
                                    clearTimeout(legacyDebounce);
                                    const term = legacySearch.value.trim();
                                    legacyDebounce = setTimeout(() => {
                                        ensureAllPostsVisible();
                                        fetchAndRender({ q: term });
                                    }, 300);
                                });
                            }
                            if (legacyCategory) {
                                legacyCategory.addEventListener('change', () => {
                                    ensureAllPostsVisible();
                                    fetchAndRender({ categoryId: legacyCategory.value });
                                });
                            }

                            // Initial render
                            setActiveChip('all');
                            renderPosts(<?= json_encode($posts ?? []) ?>);
                        });

                        async function confirmDelete(postId) {
                            const result = await Swal.fire({
                                title: 'Delete post?',
                                text: 'This action cannot be undone.',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Delete',
                                cancelButtonText: 'Cancel',
                                confirmButtonColor: '#dc2626'
                            });
                            if (!result.isConfirmed) return;

                            try {
                                const res = await fetch(`<?= base_url('/bulletin/delete/') ?>${postId}`, {
                                    method: 'DELETE',
                                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                                });
                                const data = await res.json();
                                if (data.success) {
                                    Swal.fire({ icon: 'success', title: 'Deleted', timer: 1200, showConfirmButton: false });
                                    setTimeout(() => location.reload(), 650);
                                } else {
                                    Swal.fire({ icon: 'error', title: 'Failed', text: data.message || 'Failed to delete post.' });
                                }
                            } catch (e) {
                                Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while deleting the post.' });
                            }
                        }

                        // Simple preview using SweetAlert2; images inline, other files in iframe if embeddable
                        function previewDocument(url, isImage) {
                            if (isImage) {
                                Swal.fire({
                                    html: `<img src="${url}" alt="Document" style="width:100%;height:auto;border-radius:0.5rem" />`,
                                    width: '60rem',
                                    showConfirmButton: false,
                                    showCloseButton: true,
                                    background: '#0B1220',
                                    color: '#fff'
                                });
                                return;
                            }
                            // Try iframe preview; some types (like PDF) will work; otherwise open in new tab
                            const iframe = `<iframe src="${url}" style="width:100%;height:70vh;border:0;border-radius:0.5rem;background:#fff"></iframe>`;
                            Swal.fire({
                                html: iframe,
                                width: '70rem',
                                showConfirmButton: false,
                                showCloseButton: true
                            }).then(() => {
                                // Fallback open if blocked or blank
                                // No-op; user can click Open link on card
                            });
                        }
                                                // Document carousel init
                                                document.addEventListener('DOMContentLoaded', () => {
                                                    document.querySelectorAll('.doc-carousel').forEach(root => {
                                                        const viewport = root.querySelector('.doc-carousel-viewport');
                                                        const prevBtn = root.querySelector('.doc-carousel-btn.prev');
                                                        const nextBtn = root.querySelector('.doc-carousel-btn.next');
                                                        if(!viewport || !prevBtn || !nextBtn) return;
                                                        const scrollStep = () => Math.min(viewport.clientWidth * 0.9, (viewport.querySelector('.doc-carousel-track > article')?.clientWidth || 250) * 2 + 40);
                                                        function update(){
                                                            const maxScroll = viewport.scrollWidth - viewport.clientWidth - 2;
                                                            prevBtn.disabled = viewport.scrollLeft <= 0;
                                                            nextBtn.disabled = viewport.scrollLeft >= maxScroll;
                                                            prevBtn.classList.toggle('opacity-40', prevBtn.disabled);
                                                            nextBtn.classList.toggle('opacity-40', nextBtn.disabled);
                                                        }
                                                        prevBtn.addEventListener('click', ()=> viewport.scrollBy({ left: -scrollStep(), behavior:'smooth'}));
                                                        nextBtn.addEventListener('click', ()=> viewport.scrollBy({ left: scrollStep(), behavior:'smooth'}));
                                                        viewport.addEventListener('scroll', update);
                                                        window.addEventListener('resize', update);
                                                        viewport.addEventListener('wheel', e=>{ if(Math.abs(e.deltaX) < Math.abs(e.deltaY)){ viewport.scrollLeft += e.deltaY; e.preventDefault(); } }, { passive:false });
                                                        viewport.tabIndex = 0;
                                                        viewport.addEventListener('keydown', e=>{ if(e.key==='ArrowRight'){ viewport.scrollBy({left:scrollStep(),behavior:'smooth'});} if(e.key==='ArrowLeft'){ viewport.scrollBy({left:-scrollStep(),behavior:'smooth'});} });
                                                        update();
                                                    });
                                                });
                        </script>
<script>
// IntersectionObserver animations (ported from KK view)
(() => {
    const animated = document.querySelectorAll('.animate-slide-up, .animate-fade-in-delay');
    const io = new IntersectionObserver(entries => {
        entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('in'); io.unobserve(e.target); }});
    }, { threshold: 0.15 });
    animated.forEach(el => io.observe(el));
})();
</script>
<script>
// Page-load toasts for redirects
document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const t = params.get('toast');
    if (t === 'created') showToast('Bulletin post created successfully');
    if (t === 'updated') showToast('Bulletin post updated successfully');
    if (t === 'deleted') showToast('Bulletin post deleted');
    if (t) {
        // Clean up query so refresh doesn't repeat toast
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});
function showToast(message, type='success'){
    let c = document.getElementById('toastContainer');
    if (!c){ c = document.createElement('div'); c.id='toastContainer'; c.className='fixed top-4 right-4 z-[100000] flex flex-col gap-2 items-end pointer-events-none'; document.body.appendChild(c); }
    const el = document.createElement('div');
    el.className = `pointer-events-auto max-w-sm w-80 rounded-lg shadow-lg ring-1 ring-black/10 px-4 py-3 text-sm text-white ${type==='success'?'bg-emerald-600':'bg-rose-600'}`;
    el.textContent = message;
    c.appendChild(el);
    setTimeout(()=>{ el.style.opacity='0'; el.style.transform='translateY(-4px)'; el.style.transition='all .25s ease'; }, 2000);
    setTimeout(()=>{ el.remove(); }, 2400);
}
</script>
<style>
@keyframes fadeInUp{0%{opacity:0;transform:translateY(14px)}100%{opacity:1;transform:translateY(0)}}
.animate-slide-up,.animate-fade-in-delay{opacity:0;transition:opacity .6s ease,transform .6s ease}
.animate-slide-up.in{animation:fadeInUp .65s ease forwards}
.animate-fade-in-delay.in{animation:fadeInUp .85s ease forwards}
.line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.line-clamp-3{display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
</style>

