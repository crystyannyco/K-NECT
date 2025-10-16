<!-- KK Bulletin: sleeker, cleaner UI with compact hero, filters, and responsive cards -->
<div class="flex-1 lg:ml-64 pt-16 min-h-screen bg-gray-50">
    <!-- Removed blue hero banner per request -->

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Urgent -->
        <?php if (!empty($urgent_posts)): ?>
    <div class="mb-6">
            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
            <h2 class="text-lg font-semibold text-red-900 mb-4">Urgent Announcements</h2>
            <div class="space-y-3">
                <?php foreach ($urgent_posts as $urgent): ?>
                <div class="bg-white rounded-lg border border-red-200 p-4">
                    <h3 class="text-base font-semibold text-gray-900 mb-1"><a href="<?= base_url('/bulletin/view/' . $urgent['id']) ?>" class="hover:text-red-600"><?= esc($urgent['title']) ?></a></h3>
                    <div class="text-xs text-gray-500"><?= !empty($urgent['published_at']) ? date('M d, Y', strtotime($urgent['published_at'])) : '' ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Featured mosaic -->
        <?php if (!empty($featured_posts)): ?>
    <div class="mb-6">
            <div class="flex items-center mb-4">
                <!-- Featured Posts heading intentionally minimal (button removed) -->
            </div>
            <?php $primary = $featured_posts[0]; $others = array_slice($featured_posts, 1, 4); ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 lg:auto-rows-[220px] gap-5">
                <!-- Primary -->
                <article class="relative overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm lg:col-span-2 lg:row-span-2">
                    <?php $pImg = !empty($primary['featured_image']) ? base_url('/uploads/bulletin/' . $primary['featured_image']) : null; ?>
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
                                <span><?= !empty($primary['published_at']) || !empty($primary['created_at']) ? date('M d, Y', strtotime($primary['published_at'] ?: $primary['created_at'])) : '' ?></span>
                            </div>
                        </div>
                    </div>
                </article>
                <!-- Secondary -->
                <?php foreach ($others as $item): ?>
                <article class="relative overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm h-52 lg:h-auto">
                    <?php $sImg = !empty($item['featured_image']) ? base_url('/uploads/bulletin/' . $item['featured_image']) : null; ?>
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
                            <div class="mt-1 text-xs text-white/80"><?= !empty($item['published_at']) || !empty($item['created_at']) ? date('M d, Y', strtotime($item['published_at'] ?: $item['created_at'])) : '' ?></div>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Events preview -->
        <?php if (!empty($recent_events)): ?>
    <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center"><i class="fa-regular fa-calendar-days text-blue-600 mr-2"></i>Upcoming Events</h2>
            <div class="grid gap-5 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <?php foreach ($recent_events as $event): ?>
                    <?php $date = !empty($event['event_date']) ? strtotime($event['event_date']) : null; $banner = !empty($event['event_banner']) ? base_url('uploads/event/' . $event['event_banner']) : null; ?>
                    <article class="overflow-hidden rounded-xl border bg-white shadow-sm hover:shadow-md transition">
                        <div class="relative h-36 w-full text-white">
                            <?php if ($banner): ?>
                                <img src="<?= $banner ?>" alt="<?= esc($event['title'] ?? 'Event') ?>" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <?php else: ?>
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-blue-800"></div>
                            <?php endif; ?>
                            <div class="absolute top-3 left-3 text-center bg-white/10 rounded-lg px-2 py-1">
                                <div class="text-xs uppercase tracking-wide text-white/90"><?= $date ? date('M', $date) : '' ?></div>
                                <div class="text-lg font-bold -mt-1"><?= $date ? date('d', $date) : '' ?></div>
                            </div>
                            <div class="absolute bottom-3 left-3 right-3">
                                <p class="text-sm font-semibold line-clamp-2"><?= esc($event['title'] ?? 'Scheduled Event') ?></p>
                            </div>
                        </div>
                        <div class="p-4 text-xs text-gray-600 flex items-center justify-between">
                            <span><i class="fa-regular fa-clock mr-1"></i><?= $date ? date('M d, Y g:i A', $date) : '' ?></span>
                            <span class="inline-flex items-center gap-1 text-blue-700"><i class="fa-regular fa-star"></i>Upcoming</span>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    <!-- Documents preview (converted to horizontal carousel) -->
    <?php if (!empty($recent_documents)): ?>
    <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center"><i class="fa-regular fa-folder-open text-blue-600 mr-2"></i>Uploaded Documents</h2>
            <div class="doc-carousel relative">
                <button type="button" aria-label="Previous documents" class="doc-carousel-btn prev disabled:opacity-40 disabled:cursor-not-allowed absolute left-0 top-1/2 -translate-y-1/2 z-10 w-9 h-9 rounded-full bg-white shadow flex items-center justify-center text-gray-600 hover:text-blue-600 hover:shadow-md transition"><i class="fa-solid fa-chevron-left"></i></button>
                <div class="pointer-events-none absolute inset-y-0 left-0 w-10 bg-gradient-to-r from-white to-transparent hidden md:block"></div>
                <div class="pointer-events-none absolute inset-y-0 right-0 w-10 bg-gradient-to-l from-white to-transparent hidden md:block"></div>
                <div class="doc-carousel-viewport overflow-x-auto">
                    <div class="doc-carousel-track flex gap-5">
                        <?php foreach ($recent_documents as $doc): ?>
                            <?php $filePath = $doc['file_path'] ?? ''; $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION)); $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']); $docUrl = base_url($filePath); $icon = 'fa-file-lines text-gray-500'; if ($ext === 'pdf') $icon = 'fa-file-pdf text-blue-600'; elseif (in_array($ext,['doc','docx'])) $icon = 'fa-file-word text-blue-600'; elseif (in_array($ext,['xls','xlsx','csv'])) $icon = 'fa-file-excel text-blue-600'; elseif (in_array($ext,['ppt','pptx'])) $icon = 'fa-file-powerpoint text-blue-600'; ?>
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

    <!-- All posts: visible by default; enhanced UI -->
    <div id="all-posts" class="mt-4">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-4">
                <div class="flex items-center gap-2 text-gray-700 font-semibold text-lg"><i class="fa-solid fa-list text-blue-600"></i><span>All Posts</span></div>
                <div class="flex items-center gap-3 w-full lg:w-auto">
                    <div class="relative flex-1 lg:flex-none">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input id="search-posts" type="text" placeholder="Search posts..." class="w-full lg:w-72 pl-10 pr-3 py-2 rounded-xl bg-white border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    </div>
                    <select id="category-filter" class="px-3 py-2 rounded-xl bg-white text-gray-800 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= esc($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div id="posts-container" class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <article class="group relative flex flex-col bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition" data-id="<?= $post['id'] ?>">
                            <div class="relative media w-full overflow-hidden rounded-t-xl">
                                <?php if (!empty($post['featured_image'])): ?>
                                    <img src="<?= base_url('/uploads/bulletin/' . $post['featured_image']) ?>" alt="<?= esc($post['title']) ?>" class="w-full h-full object-cover duration-500 group-hover:scale-105">
                                <?php else: ?>
                                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center text-blue-300">
                                        <i class="fa-regular fa-image text-3xl"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent opacity-60 group-hover:opacity-70 transition"></div>
                                <div class="absolute top-2 left-2 flex flex-wrap gap-1">
                                    <?php if (!empty($post['category_name'])): ?><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold" style="background-color: <?= $post['category_color'] ?>20;color: <?= $post['category_color'] ?>"><?= esc($post['category_name']) ?></span><?php endif; ?>
                                    <?php if (!empty($post['is_featured'])): ?><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-700">Featured</span><?php endif; ?>
                                    <?php if (!empty($post['is_urgent'])): ?><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-100 text-red-700">Urgent</span><?php endif; ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-700"><?= ucfirst($post['visibility'] ?? 'public') ?></span>
                                </div>
                            </div>
                            <div class="p-4 flex flex-col gap-2 flex-1">
                                <h3 class="text-base font-semibold text-gray-900 leading-snug line-clamp-2 group-hover:text-blue-600 transition"><a href="<?= base_url('/bulletin/view/' . $post['id']) ?>" class="stretched-link relative z-10"><?= esc($post['title']) ?></a></h3>
                                <p class="text-sm text-gray-600 leading-relaxed line-clamp-3">
                                    <?= esc(($post['excerpt'] ?: strip_tags($post['content'] ?? ''))) ?>
                                </p>
                            </div>
                            <div class="px-4 pb-4 flex items-center justify-between text-xs text-gray-500">
                                <div class="flex items-center gap-2">
                                    <div class="h-6 w-6 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center text-[10px] font-medium shadow-sm">
                                        <?php $initial = strtoupper(substr(($post['first_name'] ?? 'U'),0,1)); echo esc($initial); ?>
                                    </div>
                                    <span><?= esc(($post['first_name'] ?? '') . ' ' . ($post['last_name'] ?? '')) ?></span>
                                    <span class="text-gray-400">•</span>
                                    <span><?= !empty($post['published_at']) || !empty($post['created_at']) ? date('M d, Y', strtotime($post['published_at'] ?: $post['created_at'])) : '' ?></span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <i class="fa-regular fa-eye w-3 h-3"></i>
                                    <span><?= number_format((int)($post['view_count'] ?? 0)) ?></span>
                                </div>
                            </div>
                            <a href="<?= base_url('/bulletin/view/' . $post['id']) ?>" class="absolute inset-0" aria-label="Read post: <?= esc($post['title']) ?>"></a>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full">
                        <div class="bg-white border border-dashed border-gray-300 rounded-xl p-10 text-center">
                            <div class="mx-auto w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-4"><i class="fa-regular fa-newspaper text-xl"></i></div>
                            <h3 class="text-lg font-semibold text-gray-900">No posts found</h3>
                            <p class="text-gray-500 mt-1">Try different keywords or filters.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Live search & category filter for All Posts grid
(function(){
  const postsContainer = document.getElementById('posts-container');
  const searchInput = document.getElementById('search-posts');
  const categoryFilter = document.getElementById('category-filter');
  const baseViewUrl = '<?= base_url('/bulletin/view/') ?>';
  const baseImgUrl = '<?= base_url('/uploads/bulletin/') ?>';

    function boolish(v){
        if (v === true || v === 1 || v === '1') return true;
        if (typeof v === 'string'){
            const s=v.toLowerCase();
            if (s==='true' || s==='yes') return true;
        }
        return false;
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
            </div>`).join('');
    }
    function renderPosts(posts){
        if (!Array.isArray(posts) || posts.length === 0){
            postsContainer.innerHTML = `<div class="col-span-full text-center py-12 sm:py-16"><div class="text-gray-400 mb-4"><svg class="w-16 h-16 sm:w-20 sm:h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg></div><h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">No posts found</h3><p class="text-sm sm:text-base text-gray-500 px-4">No posts match your current filters. Try adjusting your search criteria.</p></div>`;return;}
        postsContainer.innerHTML = posts.map(post=>{
            const title = post.title || 'Untitled';
            const hasImg = !!post.featured_image;
            const categoryChip = post.category_name ? `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-medium" style="background-color:${post.category_color}20;color:${post.category_color}">${post.category_name}</span>` : '';
            const featChip = boolish(post.is_featured) ? `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-medium bg-yellow-100 text-yellow-800">⭐ Featured</span>` : '';
            const urgentChip = boolish(post.is_urgent) ? `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-medium bg-red-100 text-red-800">🚨 Urgent</span>` : '';
            const visChip = `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-medium bg-gray-100 text-gray-700">${(post.visibility||'public')[0].toUpperCase()+(post.visibility||'public').slice(1)}</span>`;
            const excerpt = (post.excerpt || (post.content||'').replace(/<[^>]*>/g,'')).substring(0,120);
            const dateStr = post.published_at || post.created_at ? new Date(post.published_at || post.created_at).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'}) : '';
            const views = parseInt(post.view_count||0).toLocaleString();
            const authorName = `${post.first_name || ''} ${post.last_name || ''}`.trim() || 'Unknown';
            return `<article class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden group">
                ${hasImg ? `<div class="relative h-40 sm:h-48 overflow-hidden bg-gray-100"><img src="${baseImgUrl}${post.featured_image}" alt="${title}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"><div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div></div>` : ''}
                <div class="p-4 sm:p-5">
                    <div class="flex flex-wrap items-center gap-2 mb-3">${urgentChip}${featChip}${categoryChip}${visChip}</div>
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

    async function fetchAndRender({ q = '', categoryId = '', type = 'all' } = {}){
    try{
      let url='';
      if(q){ url = `<?= base_url('/bulletin/search') ?>?q=${encodeURIComponent(q)}&limit=30&offset=0`; }
      else if(categoryId){ url = `<?= base_url('/bulletin/category') ?>/${encodeURIComponent(categoryId)}?limit=30&offset=0`; }
      else{ return; }
      const res = await fetch(url,{ headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'} });
      const data = await res.json();
      if(!data.success) throw new Error(data.message||'Failed');
            let out = Array.isArray(data.posts)?data.posts:[];
            if(type && type !== 'all'){
                out = out.filter(p => type === 'featured' ? boolish(p.is_featured) : boolish(p.is_urgent));
            }
            renderPosts(out);
    }catch(e){ console.error(e); }
  }

    // Chip filters
    const chipButtons = document.querySelectorAll('[data-kk-chip]');
    function setActiveChip(key){
        chipButtons.forEach(b=>{
            const isActive = b.dataset.kkChip===key;
            b.classList.toggle('ring-2', isActive);
            b.classList.toggle('ring-blue-300', isActive);
        });
    }
    const serverPosts = <?= json_encode($posts ?? []) ?>;
    chipButtons.forEach(btn=> btn.addEventListener('click', ()=>{
            const key = btn.dataset.kkChip;
            setActiveChip(key);
            if(key==='featured') return renderPosts(serverPosts.filter(p=>boolish(p.is_featured)));
            if(key==='urgent') return renderPosts(serverPosts.filter(p=>boolish(p.is_urgent)));
            renderPosts(serverPosts);
    }));

        let t;
    if(searchInput){ searchInput.addEventListener('input', ()=>{ clearTimeout(t); const val=searchInput.value.trim(); showSkeleton(); t=setTimeout(()=>fetchAndRender({ q: val }), 350); }); }
    if(categoryFilter){ categoryFilter.addEventListener('change', ()=>{ showSkeleton(); fetchAndRender({ categoryId: categoryFilter.value }); }); }
    // Initialize chips to All on load
    setActiveChip('all');

        // Header search form wiring (guarded - header removed)
        const headerForm = document.getElementById('headerSearchForm');
        const headerSearch = document.getElementById('header-search');
        const headerCategory = document.getElementById('header-category');
        const headerStatus = document.getElementById('header-status');
        const headerClear = document.getElementById('header-clear-btn');

        if (headerForm){
            headerForm.addEventListener('submit', (e)=>{
                e.preventDefault();
                const q = (headerSearch?.value||'').trim();
                const categoryId = headerCategory?.value||'';
                const type = headerStatus?.value||'all';
                showSkeleton();
                if(!q && !categoryId){
                    const base = Array.isArray(serverPosts)?serverPosts:[];
                    const filtered = type==='all' ? base : base.filter(p => type==='featured'? boolish(p.is_featured) : boolish(p.is_urgent));
                    renderPosts(filtered);
                } else {
                    fetchAndRender({ q, categoryId, type });
                }
            });
        }
        if (headerClear){
            headerClear.addEventListener('click', ()=>{
                if(headerSearch) headerSearch.value='';
                if(headerCategory) headerCategory.value='';
                if(headerStatus) headerStatus.value='all';
                renderPosts(Array.isArray(serverPosts)?serverPosts:[]);
                setActiveChip('all');
            });
        }
})();

// IntersectionObserver animations
(() => {
    const animated = document.querySelectorAll('.animate-fade-in, .animate-slide-up, .animate-fade-in-delay, .animate-fade-in-more');
    const io = new IntersectionObserver(entries => {
        entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('in'); io.unobserve(e.target); }});
    }, { threshold: 0.15 });
    animated.forEach(el => io.observe(el));
})();

function previewDocument(url,isImage){
  // Open document in new tab
  window.open(url, '_blank');
}

// Document carousel init
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.doc-carousel').forEach(root => {
        const viewport = root.querySelector('.doc-carousel-viewport');
        const prevBtn = root.querySelector('.doc-carousel-btn.prev');
        const nextBtn = root.querySelector('.doc-carousel-btn.next');
        if(!viewport || !prevBtn || !nextBtn) return;
    const scrollStep = () => Math.min(viewport.clientWidth * 0.9,  (viewport.querySelector('.doc-carousel-track > article')?.clientWidth || 250) * 2 + 40);
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
        // Wheel -> horizontal
        viewport.addEventListener('wheel', e=>{ if(Math.abs(e.deltaX) < Math.abs(e.deltaY)){ viewport.scrollLeft += e.deltaY; e.preventDefault(); } }, { passive:false });
        // Keyboard
        viewport.tabIndex = 0;
        viewport.addEventListener('keydown', e=>{ if(e.key==='ArrowRight'){ viewport.scrollBy({left:scrollStep(),behavior:'smooth'});} if(e.key==='ArrowLeft'){ viewport.scrollBy({left:-scrollStep(),behavior:'smooth'});} });
        update();
    });
});
</script>

<style>
@keyframes fadeInUp{0%{opacity:0;transform:translateY(14px)}100%{opacity:1;transform:translateY(0)}}
@keyframes sheen{0%{transform:translateX(-100%)}50%{transform:translateX(100%)}100%{transform:translateX(100%)}}
@keyframes pulseSlow{0%,100%{opacity:.55}50%{opacity:.25}}
.animate-fade-in,.animate-slide-up,.animate-fade-in-delay,.animate-fade-in-more{opacity:0;transition:opacity .6s ease,transform .6s ease}
.animate-fade-in.in{animation:fadeInUp .7s ease forwards}
.animate-slide-up.in{animation:fadeInUp .8s ease forwards}
.animate-fade-in-delay.in{animation:fadeInUp .9s ease forwards}
.animate-fade-in-more.in{animation:fadeInUp 1s ease forwards}
.animate-sheen{position:relative;overflow:hidden}
.animate-sheen::after{content:"";position:absolute;inset:0;background:linear-gradient(90deg,transparent,rgba(255,255,255,.9),transparent);transform:translateX(-100%);animation:sheen 6s linear infinite}
.animate-pulse-slow{animation:pulseSlow 8s ease-in-out infinite}
.line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.line-clamp-3{display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
/* Document carousel specific */
.doc-carousel-viewport{scroll-behavior:smooth;}
.doc-carousel-viewport::-webkit-scrollbar{height:8px}
.doc-carousel-viewport::-webkit-scrollbar-track{background:transparent}
.doc-carousel-viewport::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:4px}
.doc-carousel-viewport:hover::-webkit-scrollbar-thumb{background:#94a3b8}
</style>
