<!-- Main Content Area -->
<div class="flex-1 lg:ml-64 min-h-screen bg-gray-50 pt-16">
    <!-- Main Content Grid -->
    <div class="px-4 sm:px-6 lg:px-8 py-6">
        <div class="max-w-7xl mx-auto space-y-6">
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
                                $pImg = !empty($primary['featured_image']) ? base_url('/uploads/bulletin/' . $primary['featured_image']) : null;
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
                            <?php $sImg = !empty($item['featured_image']) ? base_url('/uploads/bulletin/' . $item['featured_image']) : null; ?>
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

                <!-- Upcoming events preview removed (simplified) -->
                <!-- Featured Posts (original block removed; moved above) -->

                <!-- Uploaded Documents Preview (carousel) -->
                <!-- Uploaded documents carousel removed (simplified) -->

                <!-- Compact Hero Header (aligned with SK compact variant) -->
                <section class="relative overflow-hidden rounded-xl border border-gray-200 bg-white shadow-md p-4 sm:p-5 mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="space-y-2 max-w-2xl relative z-10">
                        <?php $welcomeName = $currentUser['first_name'] ?? session('first_name') ?? ($currentUser['full_name'] ?? session('full_name') ?? 'Admin'); ?>
                        <h1 class="text-xl md:text-2xl font-extrabold tracking-tight flex items-center gap-2 text-blue-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Bulletin Management
                        </h1>
                        <div class="text-xs sm:text-sm text-blue-700/80 font-medium">Curate federation-wide announcements efficiently.</div>
                        <p class="text-[11px] sm:text-xs text-gray-600 leading-relaxed">Featured posts appear first. Category, visibility, and status chips help scanning quickly.</p>
                        <div class="flex flex-wrap gap-2 pt-1">
                            <a href="<?= base_url('/bulletin/create') ?>" class="bg-gradient-to-r from-blue-600 to-blue-500 text-white px-4 py-1.5 rounded-lg font-semibold shadow-sm hover:from-blue-700 hover:to-blue-600 transition-all text-xs flex items-center gap-1.5 border border-blue-200 hover:border-blue-300 focus:ring-2 focus:ring-blue-200 outline-none"><i class="fa-solid fa-plus text-[11px]"></i><span>New</span></a>
                            <a href="<?= base_url('/bulletin/categories') ?>" class="bg-white px-4 py-1.5 rounded-lg font-semibold shadow-sm text-blue-700 border border-blue-200 hover:bg-blue-50 hover:border-blue-300 transition-all text-xs flex items-center gap-1.5"><i class="fa-regular fa-folder-open text-[11px]"></i><span>Categories</span></a>
                            <button type="button" onclick="window.location.reload()" class="bg-gray-100 px-4 py-1.5 rounded-lg font-semibold shadow-sm text-gray-700 border border-gray-200 hover:bg-gray-200 hover:border-gray-300 transition-all text-xs flex items-center gap-1.5"><i class="fa-solid fa-rotate text-[11px]"></i><span>Refresh</span></button>
                        </div>
                    </div>
                    <div class="md:self-stretch flex items-center relative">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl bg-gradient-to-br from-blue-50 via-white to-blue-100 border border-blue-100 flex items-center justify-center">
                            <i class="fa-solid fa-newspaper text-3xl sm:text-4xl text-blue-600"></i>
                        </div>
                        <div class="absolute -right-5 -top-5 w-24 h-24 bg-blue-100/40 rounded-full blur-xl pointer-events-none"></div>
                    </div>
                    <span class="absolute top-2 right-2 inline-flex items-center px-1.5 py-0.5 rounded bg-blue-100 text-blue-700 text-[9px] font-semibold border border-blue-200">Hero</span>
                </section>

                <!-- All Posts Grid - Visible by default, sleek header -->
                <div id="allPostsSection" class="mt-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-list text-gray-500"></i>
                        <span>All Posts</span>
                    </h2>
                    <div id="posts-container" class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                        <?php if (!empty($posts)): ?>
                            <?php foreach ($posts as $post): ?>
                            <article class="group relative flex flex-col bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition" data-id="<?= $post['id'] ?>">
                                <div class="relative media w-full overflow-hidden rounded-t-xl">
                                    <?php if ($post['featured_image']): ?>
                                        <img src="<?= base_url('/uploads/bulletin/' . $post['featured_image']) ?>" alt="<?= esc($post['title']) ?>" class="w-full h-full object-cover duration-500 group-hover:scale-105">
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
                            const baseImgUrl = '<?= base_url('/uploads/bulletin/') ?>';

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
                                    postsContainer.innerHTML = `<div class="col-span-full text-center py-12 sm:py-16"><div class="text-gray-400 mb-4"><svg class="w-16 h-16 sm:w-20 sm:h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg></div><h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">No posts found</h3><p class="text-sm sm:text-base text-gray-500 px-4">No posts match your current filters. Try adjusting your search criteria.</p></div>`;
                                    return;
                                }
                                postsContainer.innerHTML = posts.map(post => {
                                    const title = post.title || 'Untitled';
                                    const hasImg = !!post.featured_image;
                                    const categoryChip = post.category_name ? `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-medium" style="background-color:${post.category_color}20;color:${post.category_color}">${post.category_name}</span>` : '';
                                    const featChip = boolish(post.is_featured) ? `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-medium bg-yellow-100 text-yellow-800">⭐ Featured</span>` : '';
                                    const urgentChip = boolish(post.is_urgent) ? `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-medium bg-red-100 text-red-800">🚨 Urgent</span>` : '';
                                    const visChip = `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-medium bg-gray-100 text-gray-700">${(post.visibility||'public')[0].toUpperCase()+(post.visibility||'public').slice(1)}</span>`;
                                    const statusChip = (post.status && post.status !== 'published') ? `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-medium bg-gray-200 text-gray-700">${post.status[0].toUpperCase()+post.status.slice(1)}</span>` : '';
                                    const excerpt = (post.excerpt || (post.content||'').replace(/<[^>]*>/g,'')).substring(0,120);
                                    const dateStr = post.published_at || post.created_at ? new Date(post.published_at || post.created_at).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'}) : '';
                                    const views = parseInt(post.view_count||0).toLocaleString();
                                    const authorName = `${post.first_name || ''} ${post.last_name || ''}`.trim() || 'Unknown';
                                    return `<article class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden group">
                                        ${hasImg ? `<div class="relative h-40 sm:h-48 overflow-hidden bg-gray-100"><img src="${baseImgUrl}${post.featured_image}" alt="${title}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"><div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div></div>` : ''}
                                        <div class="p-4 sm:p-5">
                                            <div class="flex flex-wrap items-center gap-2 mb-3">${urgentChip}${featChip}${categoryChip}${visChip}${statusChip}</div>
                                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                                <a href="${baseViewUrl}${post.id}" class="hover:underline">${title}</a>
                                            </h3>
                                            <p class="text-xs sm:text-sm text-gray-600 mb-4 leading-relaxed line-clamp-3">${excerpt}${excerpt.length>=120?'...':''}</p>
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs text-gray-500 pt-3 border-t border-gray-100">
                                                <div class="flex items-center flex-wrap gap-2 sm:gap-3">
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                        <span class="truncate max-w-[120px]">${authorName}</span>
                                                    </span>
                                                    <span class="hidden sm:inline">•</span>
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        ${dateStr}
                                                    </span>
                                                    <span class="flex items-center gap-1 text-gray-400">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                        ${views}
                                                    </span>
                                                </div>
                                                <a href="${baseViewUrl}${post.id}" class="text-blue-600 hover:text-blue-800 font-medium text-xs sm:text-sm transition-colors">Read more →</a>
                                            </div>
                                        </div>
                                    </article>`;
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
                            showConfirmModal({
                                title: 'Delete Post?',
                                message: 'Are you sure you want to delete this post? This action cannot be undone.',
                                confirmText: 'Yes, Delete',
                                cancelText: 'Cancel',
                                confirmColor: 'red',
                                icon: 'warning',
                                onConfirm: async () => {
                                    try {
                                        const res = await fetch(`<?= base_url('/bulletin/delete/') ?>${postId}`, {
                                            method: 'DELETE',
                                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                                        });
                                        const data = await res.json();
                                        if (data.success) {
                                            showSuccessToast('Post deleted successfully!');
                                            setTimeout(() => location.reload(), 1000);
                                        } else {
                                            showErrorToast(data.message || 'Failed to delete post.');
                                        }
                                    } catch (e) {
                                        showErrorToast('An error occurred while deleting the post.');
                                    }
                                }
                            });
                        }

                        // Simple preview - open documents in new tab
                        function previewDocument(url, isImage) {
                            window.open(url, '_blank');
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
    if (t === 'created') showNotification('Bulletin post created successfully', 'success');
    if (t === 'updated') showNotification('Bulletin post updated successfully', 'success');
    if (t === 'deleted') showNotification('Bulletin post deleted', 'success');
    if (t) {
        // Clean up query so refresh doesn't repeat toast
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});

// Unified toast notification function (matches youthlist.php style)
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `stacked-toast fixed right-4 z-[99999] p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
    
    switch(type) {
        case 'success':
            notification.className += ' bg-green-500 text-white';
            break;
        case 'error':
            notification.className += ' bg-red-500 text-white';
            break;
        case 'warning':
            notification.className += ' bg-yellow-500 text-white';
            break;
        default:
            notification.className += ' bg-blue-500 text-white';
    }
    
    // Calculate stacking position based on existing notifications
    const existingToasts = document.querySelectorAll('.stacked-toast');
    let topOffset = 16; // Initial top offset (1rem = 16px)
    existingToasts.forEach(toast => {
        topOffset += toast.offsetHeight + 16; // Add height + 16px gap
    });
    notification.style.top = topOffset + 'px';
    
    // Get appropriate icon based on type
    let icon = '';
    switch(type) {
        case 'success':
            icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
            break;
        case 'error':
            icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" /></svg>';
            break;
        case 'warning':
            icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" /></svg>';
            break;
        case 'info':
        default:
            icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01" /></svg>';
            break;
    }
    
    notification.innerHTML = `
        <div class="flex items-center">
            ${icon}
            <span class="mr-2">${message}</span>
            <button onclick="this.parentElement.parentElement.remove(); repositionToasts();" class="ml-2 text-white hover:text-gray-200 focus:outline-none">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
                repositionToasts();
            }
        }, 300);
    }, 5000);
}

// Helper function to reposition remaining toasts after one is removed
function repositionToasts() {
    const toasts = document.querySelectorAll('.stacked-toast');
    let topOffset = 16;
    toasts.forEach(toast => {
        toast.style.top = topOffset + 'px';
        topOffset += toast.offsetHeight + 16;
    });
}

// Legacy alias for backward compatibility
function showToast(message, type='success') {
    showNotification(message, type);
}

// Legacy aliases for old function names
function showSuccessToast(message) {
    showNotification(message, 'success');
}

function showErrorToast(message) {
    showNotification(message, 'error');
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

