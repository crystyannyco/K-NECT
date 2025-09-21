<?= $this->include('K-NECT/SK/template/header') ?>
<?= $this->include('K-NECT/SK/template/sidebar') ?>
<?= $this->include('K-NECT/includes/bulletin-assets') ?>

<div class="flex-1 flex flex-col min-h-0 ml-0 lg:ml-64 pt-16">
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
        <div class="max-w-7xl mx-auto p-0">
            <!-- Header panel removed per request -->
            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?= session()->getFlashdata('success') ?></span>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?= session()->getFlashdata('error') ?></span>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('warning')): ?>
                <div class="mb-6 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?= session()->getFlashdata('warning') ?></span>
                </div>
            <?php endif; ?>

            <!-- Actions toolbar removed (Create moved to header) -->

            <div class="grid grid-cols-1 gap-6 px-6">
                <!-- Main Content -->
                <div class="w-full space-y-6">
                    
                    <!-- Urgent Posts -->
                    <?php if (!empty($urgent_posts)): ?>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-red-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Urgent Announcements
                        </h2>
                        <div class="space-y-4">
                            <?php foreach ($urgent_posts as $urgent): ?>
                            <div class="bg-white rounded-lg border border-red-200 p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full font-medium">🚨 URGENT</span>
                                            <?php if ($urgent['category_name']): ?>
                                            <span class="px-2 py-1 text-xs rounded-full font-medium" style="background-color: <?= $urgent['category_color'] ?>20; color: <?= $urgent['category_color'] ?>;">
                                                <?= esc($urgent['category_name']) ?>
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                            <a href="<?= base_url('/bulletin/view/' . $urgent['id']) ?>" class="hover:text-red-600 transition-colors">
                                                <?= esc($urgent['title']) ?>
                                            </a>
                                        </h3>
                                        <p class="text-gray-600 mb-3">
                                            <?= esc($urgent['excerpt'] ?: substr(strip_tags($urgent['content']), 0, 150) . '...') ?>
                                        </p>
                                        <div class="flex items-center justify-between text-sm text-gray-500">
                                            <span>By <?= esc($urgent['first_name'] . ' ' . $urgent['last_name']) ?></span>
                                            <span><?= date('M d, Y', strtotime($urgent['published_at'])) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Featured Posts (mosaic) -->
                    <?php if (!empty($featured_posts)): ?>
                    <div class="mb-8">
                        <!-- Featured Posts heading removed per request -->
                        <?php $primary = $featured_posts[0]; $others = array_slice($featured_posts, 1, 4); ?>
                        <div class="grid grid-cols-1 lg:grid-cols-3 lg:auto-rows-[220px] gap-5">
                            <!-- Primary featured -->
                            <article class="relative overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm lg:col-span-2 lg:row-span-2">
                                <?php $pImg = !empty($primary['featured_image']) ? base_url('uploads/bulletin/' . $primary['featured_image']) : null; ?>
                                <div class="relative w-full h-64 md:h-72 lg:h-full">
                                    <?php if ($pImg): ?>
                                        <img src="<?= $pImg ?>" alt="<?= esc($primary['title']) ?>" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                                    <?php else: ?>
                                        <div class="absolute inset-0 bg-gradient-to-br from-blue-700 to-blue-900"></div>
                                    <?php endif; ?>
                                    <div class="absolute top-3 left-3 flex gap-2 flex-wrap">
                                        <?php if (!empty($primary['category_name'])): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-white/90 text-gray-800"><?= esc($primary['category_name']) ?></span>
                                        <?php endif; ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Featured</span>
                                    </div>
                                    <div class="absolute bottom-4 left-4 right-4 text-white">
                                        <h3 class="text-2xl font-bold leading-tight mb-2"><a href="<?= base_url('/bulletin/view/' . $primary['id']) ?>" class="hover:underline"><?= esc($primary['title']) ?></a></h3>
                                        <p class="hidden md:block text-white/90 mb-3"><?= esc($primary['excerpt'] ?: substr(strip_tags($primary['content'] ?? ''), 0, 140) . '...') ?></p>
                                        <div class="flex items-center text-sm text-white/80 gap-3">
                                            <span class="inline-flex items-center gap-1"><i class="fa-regular fa-calendar"></i> <?= !empty($primary['published_at']) || !empty($primary['created_at']) ? date('M d, Y', strtotime($primary['published_at'] ?: $primary['created_at'])) : '' ?></span>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <!-- Secondary featured -->
                            <?php foreach ($others as $item): ?>
                            <article class="relative overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm h-52 lg:h-auto">
                                <?php $sImg = !empty($item['featured_image']) ? base_url('uploads/bulletin/' . $item['featured_image']) : null; ?>
                                <div class="relative w-full h-full">
                                    <?php if ($sImg): ?>
                                        <img src="<?= $sImg ?>" alt="<?= esc($item['title']) ?>" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                                    <?php else: ?>
                                        <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-blue-800"></div>
                                    <?php endif; ?>
                                    <div class="absolute top-2 left-2 flex gap-2 flex-wrap">
                                        <?php if (!empty($item['category_name'])): ?>
                                            <span class="inline-flex items-center rounded-full bg-white/90 text-gray-800 text-[10px] px-2 py-0.5 font-medium"><?= esc($item['category_name']) ?></span>
                                        <?php endif; ?>
                                        <span class="inline-flex items-center rounded-full bg-blue-100 text-blue-800 text-[10px] px-2 py-0.5 font-medium">Featured</span>
                                    </div>
                                    <div class="absolute bottom-2 left-2 right-2 text-white">
                                        <h4 class="text-base font-semibold leading-snug line-clamp-2"><a href="<?= base_url('/bulletin/view/' . $item['id']) ?>" class="hover:underline"><?= esc($item['title']) ?></a></h4>
                                        <div class="mt-1 text-xs text-white/80 flex items-center gap-2"><i class="fa-regular fa-calendar"></i> <?= !empty($item['published_at']) || !empty($item['created_at']) ? date('M d, Y', strtotime($item['published_at'] ?: $item['created_at'])) : '' ?></div>
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
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center"><i class="fa-regular fa-calendar-days text-blue-600 mr-2"></i>Upcoming Events</h2>
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
                                            <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-blue-800"></div>
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

                    <!-- Uploaded Documents Preview (carousel) -->
                    <?php if (!empty($recent_documents)): ?>
                    <div class="mb-2">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center"><i class="fa-regular fa-folder-open text-blue-600 mr-2"></i>Uploaded Documents</h2>
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
                                            if ($ext === 'pdf') $icon = 'fa-file-pdf text-blue-600';
                                            elseif (in_array($ext,['doc','docx'])) $icon = 'fa-file-word text-blue-600';
                                            elseif (in_array($ext,['xls','xlsx','csv'])) $icon = 'fa-file-excel text-blue-600';
                                            elseif (in_array($ext,['ppt','pptx'])) $icon = 'fa-file-powerpoint text-blue-600';
                                        ?>
                                        <article class="w-64 sm:w-72 flex-shrink-0 bg-white rounded-xl border shadow-sm overflow-hidden hover:shadow-md transition">
                                            <div class="relative h-36 w-full overflow-hidden bg-gray-100 flex items-center justify-center">
                                                <?php if ($isImage): ?>
                                                    <img src="<?= $docUrl ?>" alt="<?= esc($doc['filename'] ?? 'Document') ?>" class="w-full h-full object-cover">
                                                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                                                <?php else: ?>
                                                    <i class="fa-regular <?= $icon ?> text-5xl"></i>
                                                <?php endif; ?>
                                                <div class="absolute top-3 left-3"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-white text-gray-800"><?= strtoupper($ext ?: 'FILE') ?></span></div>
                                                <div class="absolute bottom-3 right-3"><button onclick="previewDocument('<?= esc($docUrl) ?>', <?= $isImage ? 'true':'false' ?>)" class="inline-flex items-center px-3 py-1.5 rounded-md bg-white/90 text-gray-800 text-xs font-medium hover:bg-white"><i class="fa-regular fa-eye mr-1"></i> Preview</button></div>
                                            </div>
                                            <div class="p-4">
                                                <h3 class="text-sm font-semibold text-gray-900 truncate" title="<?= esc($doc['filename'] ?? 'Untitled Document') ?>"><?= esc($doc['filename'] ?? 'Untitled Document') ?></h3>
                                                <div class="mt-2 flex items-center justify-between text-xs text-gray-500"><span><?= !empty($doc['created_at']) ? date('M d, Y', strtotime($doc['created_at'])) : '' ?></span><a href="<?= esc($docUrl) ?>" target="_blank" class="text-blue-600 hover:text-blue-700">Open</a></div>
                                            </div>
                                        </article>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <button type="button" aria-label="Next documents" class="doc-carousel-btn next disabled:opacity-40 disabled:cursor-not-allowed absolute right-0 top-1/2 -translate-y-1/2 z-10 w-9 h-9 rounded-full bg-white shadow flex items-center justify-center text-gray-600 hover:text-blue-600 hover:shadow-md transition"><i class="fa-solid fa-chevron-right"></i></button>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Reintroduced compact hero header under Documents (SK) -->
                    <div class="px-0 py-4 bulletin-wrap">
                        <div class="mx-auto">
                            <div class="p-4 md:p-5 rounded-2xl shadow-md border border-gray-200 bg-white flex flex-col gap-3 animate-fade-in bulletin-header">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-1">
                                    <div class="min-w-0">
                                        <?php $welcomeName = session('first_name') ?? session('full_name') ?? 'Admin'; ?>
                                        <div class="text-[1.4rem] md:text-[1.6rem] font-extrabold text-gray-900 leading-tight tracking-tight animate-slide-up truncate">
                                            Welcome, <span class="text-blue-700 drop-shadow-[0_1px_0_rgba(59,130,246,0.25)]"><?= esc($welcomeName) ?></span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
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

                    <!-- All Posts - Visible by default, sleek header -->
                    <div id="allPostsSection" class="mt-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2"><i class="fa-solid fa-list text-gray-500"></i><span>All Posts</span></h2>
                        <!-- Toolbar removed: use header panel above -->
                        <div id="posts-container" class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"></div>
                    </div>
                </div>
                <!-- Sidebar removed; content is full-width -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const postsContainer = document.getElementById('posts-container');
    // Header panel controls
    const headerForm = document.getElementById('headerSearchForm');
    const headerSearch = document.getElementById('header-search');
    const headerCategory = document.getElementById('header-category');
    const headerStatus = document.getElementById('header-status');
    const headerClearBtn = document.getElementById('header-clear-btn');
    const headerChips = document.querySelectorAll('[data-kk-chip]');
    // Base URLs for rendering links/images (must be defined before first render)
    const baseViewUrl = '<?= base_url('/bulletin/view/') ?>';
    const baseEditUrl = '<?= base_url('/bulletin/edit/') ?>';
    const baseImgUrl = '<?= base_url('/uploads/bulletin/') ?>';
    // All posts section is always visible now

    async function fetchAndRender({ q = '', categoryId = '', status = 'all' } = {}){
        try{
            let url = '';
            let list = [];
            if (q) url = `<?= base_url('/bulletin/search') ?>?q=${encodeURIComponent(q)}&limit=30&offset=0`;
            else if (categoryId) url = `<?= base_url('/bulletin/category') ?>/${encodeURIComponent(categoryId)}?limit=30&offset=0`;

            if (url){
                showSkeleton();
                const res = await fetch(url,{ headers:{ 'Accept':'application/json','X-Requested-With':'XMLHttpRequest' } });
                const data = await res.json();
                if(!data.success) throw new Error(data.message||'Failed to load');
                list = Array.isArray(data.posts)?data.posts:[];
            } else {
                list = <?= json_encode($posts ?? []) ?>;
            }

            // status filter client-side
            if (status === 'featured') list = list.filter(p => boolish(p.is_featured));
            else if (status === 'urgent') list = list.filter(p => boolish(p.is_urgent));

            renderPosts(list);
        }catch(error){
            console.error('Filter error:', error);
            renderPosts([]);
        }
    }

    function renderPosts(posts) {
        if (!Array.isArray(posts) || posts.length === 0) {
            postsContainer.innerHTML = `
                <div class="text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No posts found</h3>
                    <p class="text-gray-500">No posts match your current filters. Try adjusting your search criteria.</p>
                </div>
            `;
            return;
        }

        postsContainer.innerHTML = posts.map(post => {
            const publishedDate = post.published_at ? new Date(post.published_at).toLocaleDateString() : 'N/A';
            const authorName = `${post.first_name || ''} ${post.last_name || ''}`.trim() || 'Unknown Author';
            const postTitle = post.title || 'Untitled Post';
            const postContent = post.content ? post.content.replace(/<[^>]*>/g, '').substring(0, 150) + '...' : '';
            const postId = post.id || '';
            const viewCount = post.view_count || 0;
            const categoryColor = post.category_color || '#6B7280';
            const categoryName = post.category_name || '';

            return `
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-3">
                                ${post.is_urgent ? '<span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full font-medium">🚨 Urgent</span>' : ''}
                                ${post.is_featured ? '<span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full font-medium">⭐ Featured</span>' : ''}
                                ${categoryName ? `<span class="px-2 py-1 text-xs rounded-full font-medium" style="background-color: ${categoryColor}20; color: ${categoryColor};">${categoryName}</span>` : ''}
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded-full font-medium">${post.visibility || 'Public'}</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 hover:text-blue-600 transition-colors">
                                <a href="<?= base_url('/bulletin/view/') ?>${postId}" class="hover:underline">${postTitle}</a>
                            </h3>
                            <p class="text-gray-600 mb-4 leading-relaxed">${postContent}</p>
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <div class="flex items-center space-x-4">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        ${authorName}
                                    </span>
                                    <span>•</span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        ${publishedDate}
                                    </span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="flex items-center text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        ${parseInt(viewCount).toLocaleString()}
                                    </span>
                                    <a href="<?= base_url('/bulletin/view/') ?>${postId}" class="text-blue-600 hover:text-blue-800 font-medium">Read more →</a>
                                    ${(post.author_id == <?= $user_id ?? 0 ?>) && (String(post.barangay_id) == String(<?= (int)($barangay_id ?? 0) ?>)) ? `<a href="<?= base_url('/bulletin/edit/') ?>${postId}" class="text-gray-500 hover:text-blue-600 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>` : ''}
                                </div>
                            </div>
                        </div>
                        ${post.featured_image ? `<div class="ml-6 flex-shrink-0"><img src="<?= base_url('uploads/bulletin/') ?>${post.featured_image}" alt="${postTitle}" class="w-24 h-24 object-cover rounded-lg shadow-sm"></div>` : ''}
                    </div>
                </div>
            `;
        }).join('');
    }

    // Debounced search via header input
    let searchTimeout;
    if (headerSearch){
        headerSearch.addEventListener('input', function(){
            clearTimeout(searchTimeout);
            const term = headerSearch.value.trim();
            searchTimeout = setTimeout(()=> fetchAndRender({ q: term, categoryId: headerCategory?.value||'', status: 'all' }), 300);
        });
    }

    // Category change via header
    if (headerCategory){
        headerCategory.addEventListener('change', ()=>{
            const cid = headerCategory.value;
            const term = headerSearch?.value.trim() || '';
            fetchAndRender({ q: term, categoryId: cid, status: 'all' });
        });
    }

    // Status dropdown
    function setActiveChip(key){
        headerChips.forEach(b=>{
            const isActive = b.dataset.kkChip===key;
            b.classList.toggle('ring-2', isActive);
            b.classList.toggle('ring-blue-500/60', isActive);
            b.classList.toggle('border-blue-300', isActive);
        });
        if (headerStatus) headerStatus.value = key==='all' ? 'all' : key;
    }
    if (headerStatus){
        headerStatus.addEventListener('change', ()=>{
            const status = headerStatus.value;
            setActiveChip(status === 'all' ? 'all' : status);
            const cid = headerCategory?.value||'';
            const term = headerSearch?.value.trim()||'';
            fetchAndRender({ q: term, categoryId: cid, status });
        });
    }

    // Quick chips
    headerChips.forEach(btn => btn.addEventListener('click', ()=>{
        const key = btn.dataset.kkChip;
        setActiveChip(key);
        const cid = headerCategory?.value||'';
        const term = headerSearch?.value.trim()||'';
        fetchAndRender({ q: term, categoryId: cid, status: key });
    }));

    // Header form submit + clear
    if (headerForm){
        headerForm.addEventListener('submit', (e)=>{
            e.preventDefault();
            const term = headerSearch?.value.trim()||'';
            const cid = headerCategory?.value||'';
            const status = headerStatus?.value||'all';
            fetchAndRender({ q: term, categoryId: cid, status });
        });
    }
    if (headerClearBtn){
        headerClearBtn.addEventListener('click', ()=>{
            if (headerSearch) headerSearch.value='';
            if (headerCategory) headerCategory.value='';
            if (headerStatus) headerStatus.value='all';
            setActiveChip('all');
            renderPosts(<?= json_encode($posts ?? []) ?>);
        });
    }

    // Initialize the view
    setActiveChip('all');
    renderPosts(<?= json_encode($posts ?? []) ?>);

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
                    </div>`).join('');
        }
                        function boolish(v){
                            if (v === true || v === 1 || v === '1') return true;
                            if (typeof v === 'string'){
                                const s=v.toLowerCase();
                                if (s==='true' || s==='yes') return true;
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
                            const authorName = `${post.first_name||''} ${post.last_name||''}`.trim();
                            const editLink = ((post.author_id == <?= $user_id ?? 0 ?>) && (String(post.barangay_id) == String(<?= (int)($barangay_id ?? 0) ?>))) ? `<a href=\"${baseEditUrl}${post.id}\" class=\"text-gray-500 hover:text-blue-600 transition-colors\" title=\"Edit\"><i class=\"fa-regular fa-pen-to-square\"></i></a>` : '';
                            return `<article class=\"group relative flex flex-col bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition\" data-id=\"${post.id}\">\n  <div class=\"relative media w-full overflow-hidden rounded-t-xl\">\n    ${hasImg?`<img src=\"${baseImgUrl}${post.featured_image}\" alt=\"${title}\" class=\"w-full h-full object-cover duration-500 group-hover:scale-105\">`:`<div class=\"absolute inset-0 bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center text-blue-300\"><i class=\"fa-regular fa-image text-3xl\"></i></div>`}\n    <div class=\"absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent opacity-60 group-hover:opacity-70 transition\"></div>\n    <div class=\"absolute top-2 left-2 flex flex-wrap gap-1\">${categoryChip}${featChip}${urgentChip}${visChip}${statusChip}</div>\n  </div>\n  <div class=\"p-4 flex flex-col gap-2 flex-1\">\n    <h3 class=\"text-base font-semibold text-gray-900 leading-snug line-clamp-2 group-hover:text-blue-600 transition\"><a href=\"${baseViewUrl}${post.id}\" class=\"stretched-link relative z-10\">${title}</a></h3>\n    <p class=\"text-sm text-gray-600 leading-relaxed line-clamp-3\">${excerpt}${excerpt.length>=220?'...':''}</p>\n  </div>\n  <div class=\"px-4 pb-4 flex items-center justify-between text-xs text-gray-500\">\n    <div class=\"flex items-center gap-2\">\n      <div class=\"h-6 w-6 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center text-[10px] font-medium shadow-sm\">${initial}</div>\n      <span>${authorName}</span><span class=\"text-gray-400\">•</span><span>${dateStr}</span>\n    </div>\n    <div class=\"flex items-center gap-3\"><span class=\"flex items-center gap-1\"><i class=\"fa-regular fa-eye\"></i>${views}</span>${editLink}</div>\n  </div>\n  <a href=\"${baseViewUrl}${post.id}\" class=\"absolute inset-0\" aria-label=\"Read post: ${title}\"></a>\n</article>`;
                }).join('');
        }

    async function fetchAndRender({ q = '', categoryId = '' } = {}) {
        try {
            let url = '';
            if (q) {
                url = `<?= base_url('/bulletin/search') ?>?q=${encodeURIComponent(q)}&limit=30&offset=0`;
            } else if (categoryId) {
                url = `<?= base_url('/bulletin/category') ?>/${encodeURIComponent(categoryId)}?limit=30&offset=0`;
            } else {
                return renderPosts(<?= json_encode($posts ?? []) ?>);
            }
            const res = await fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();
            if (!data.success) throw new Error(data.message || 'Failed to load');
            renderPosts(Array.isArray(data.posts) ? data.posts : []);
        } catch (e) {
            console.error(e); renderPosts([]);
        }
    }

    let searchDebounce;
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(searchDebounce); const term = searchInput.value.trim();
            showSkeleton();
            searchDebounce = setTimeout(() => { fetchAndRender({ q: term }); }, 350);
        });
    }
    if (categoryFilter) {
        categoryFilter.addEventListener('change', () => { const categoryId = categoryFilter.value; showSkeleton(); fetchAndRender({ categoryId }); });
    }

    // Initial render (hidden section uses this container once shown)
    renderPosts(<?= json_encode($posts ?? []) ?>);
});

// Simple preview for documents
function previewDocument(url, isImage) {
    if (isImage) {
        Swal.fire({ html: `<img src="${url}" alt="Document" style="width:100%;height:auto;border-radius:0.5rem" />`, width: '60rem', showConfirmButton: false, showCloseButton: true, background: '#0B1220', color: '#fff' });
        return;
    }
    const iframe = `<iframe src="${url}" style="width:100%;height:70vh;border:0;border-radius:0.5rem;background:#fff"></iframe>`;
    Swal.fire({ html: iframe, width: '70rem', showConfirmButton: false, showCloseButton: true });
}

// Document carousel init (shared logic)
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

<style>
@keyframes fadeInUp{0%{opacity:0;transform:translateY(14px)}100%{opacity:1;transform:translateY(0)}}
.animate-slide-up,.animate-fade-in-delay{opacity:0;transition:opacity .6s ease,transform .6s ease}
.animate-slide-up.in{animation:fadeInUp .65s ease forwards}
.animate-fade-in-delay.in{animation:fadeInUp .85s ease forwards}
.line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.line-clamp-3{display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
.doc-carousel-viewport{scroll-behavior:smooth;}
.doc-carousel-viewport::-webkit-scrollbar{height:8px}
.doc-carousel-viewport::-webkit-scrollbar-track{background:transparent}
.doc-carousel-viewport::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:4px}
.doc-carousel-viewport:hover::-webkit-scrollbar-thumb{background:#94a3b8}
</style>
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
// Page-load toasts for redirects and flash messages
document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const t = params.get('toast');
    if (t === 'created') showToast('Bulletin post created successfully');
    if (t === 'updated') showToast('Bulletin post updated successfully');
    if (t === 'deleted') showToast('Bulletin post deleted');
    if (t) {
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    // Flash data to toast
    const flash = {
        success: <?= json_encode(session()->getFlashdata('success') ?? '') ?>,
        error: <?= json_encode(session()->getFlashdata('error') ?? '') ?>,
        warning: <?= json_encode(session()->getFlashdata('warning') ?? '') ?>
    };
    if (flash.success) showToast(flash.success, 'success');
    if (flash.error) showToast(flash.error, 'error');
    if (flash.warning) showToast(flash.warning, 'error');
    // Hide any static alert boxes if present
    document.querySelectorAll('div[role="alert"]').forEach(el => el.classList.add('hidden'));
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

<?= $this->include('K-NECT/SK/template/footer') ?>
