<!-- KK Bulletin: bulletin-first layout with hero, mosaic, events, documents, and all posts grid -->
<?= $this->include('K-NECT/includes/bulletin-assets') ?>
<?= $this->include('K-NECT/includes/bulletin-assets') ?>
<div class="flex-1 lg:ml-64 pt-16 min-h-screen bg-gray-50">
    <!-- Hero (revamped: stats removed, added animations) -->
    <?php $welcomeName = isset($currentUser['first_name']) ? $currentUser['first_name'] : ($currentUser['full_name'] ?? 'User'); ?>
    <section class="relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-blue-600 to-blue-500"></div>
        <!-- Further reduced decorative elements for even more compact hero -->
        <div class="pointer-events-none absolute -top-12 -right-12 w-56 h-56 rounded-full bg-white/10 blur-xl animate-pulse-slow"></div>
        <div class="pointer-events-none absolute -bottom-14 -left-12 w-[18rem] h-[18rem] rounded-full bg-white/5 blur-lg"></div>
        <div class="absolute inset-0 opacity-[0.18] mix-blend-overlay"
             style="background-image:linear-gradient(120deg,rgba(255,255,255,.15) 0 20%,transparent 60%),radial-gradient(circle at 25% 25%,rgba(255,255,255,.35) 0,transparent 60%),radial-gradient(circle at 75% 65%,rgba(255,255,255,.25) 0,transparent 55%);"></div>
        <div class="absolute top-0 left-0 h-1 w-full bg-gradient-to-r from-white/0 via-white/70 to-white/0 animate-sheen"></div>
        <div class="relative px-4 sm:px-6 lg:px-8 py-3 md:py-5"> <!-- further reduced vertical padding -->
            <div class="max-w-5xl">
                <!-- Removed label chip -->
                <h1 class="text-xl md:text-2xl font-bold text-white tracking-tight leading-tight animate-slide-up"> <!-- further smaller heading -->
                    Welcome, <span class="text-white drop-shadow-sm"><?= esc($welcomeName) ?></span>
                </h1>
                <p class="mt-1 text-white/90 text-[13px] md:text-sm max-w-2xl animate-fade-in-delay"> <!-- reduced top margin & font size -->
                    Stay updated with the latest announcements and information
                    <?php if (isset($barangay_name) && $barangay_name): ?>
                        from <span class="font-medium text-white"><?= esc($barangay_name) ?></span>
                    <?php endif; ?>
                </p>
                <div class="mt-3 flex flex-wrap gap-2 animate-fade-in-more"> <!-- further reduced spacing above buttons -->
                    <a href="#all-posts" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white text-blue-600 text-sm font-medium shadow hover:shadow-md transition-all focus:outline-none focus:ring-2 focus:ring-white/60">
                        <i class="fa-solid fa-list"></i> Browse Posts
                    </a>
                    <a href="#" onclick="window.scrollTo({top:0,behavior:'smooth'})" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white/10 text-white text-sm font-medium border border-white/20 hover:bg-white/15 transition-all focus:outline-none focus:ring-2 focus:ring-white/50">
                        <i class="fa-regular fa-arrow-up"></i> Back to Top
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-white/50 to-transparent"></div>
    </section>

    <!-- Content -->
    <div class="px-4 sm:px-6 lg:px-8 py-6 space-y-8">
        <!-- Urgent -->
        <?php if (!empty($urgent_posts)): ?>
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
        <?php endif; ?>

        <!-- Featured mosaic -->
        <?php if (!empty($featured_posts)): ?>
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    Featured Posts
                </h2>
                <a href="#all-posts" class="inline-flex items-center px-3 py-2 rounded-md bg-white text-blue-700 border border-blue-200 hover:bg-blue-50 text-sm">View all posts</a>
            </div>
            <?php $primary = $featured_posts[0]; $others = array_slice($featured_posts, 1, 4); ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 lg:auto-rows-[220px] gap-5">
                <!-- Primary -->
                <article class="relative overflow-hidden rounded-xl border border-gray-200 shadow-sm lg:col-span-2 lg:row-span-2">
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
                                <span class="badge bg-white/90 text-gray-800"><?= esc($primary['category_name']) ?></span>
                            <?php endif; ?>
                            <span class="badge bg-blue-100 text-blue-800">Featured</span>
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
                <article class="relative overflow-hidden rounded-xl border border-gray-200 shadow-sm h-52 lg:h-auto">
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
                                <span class="badge bg-white/90 text-gray-800 text-[10px] px-2 py-0.5"><?= esc($item['category_name']) ?></span>
                            <?php endif; ?>
                            <span class="badge bg-blue-100 text-blue-800 text-[10px] px-2 py-0.5">Featured</span>
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
        <div>
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center"><i class="fa-regular fa-calendar-days text-blue-600 mr-2"></i>Upcoming Events</h2>
            <div class="grid gap-5 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <?php foreach ($recent_events as $event): ?>
                    <?php $date = !empty($event['event_date']) ? strtotime($event['event_date']) : null; $banner = !empty($event['event_banner']) ? base_url('uploads/event/' . $event['event_banner']) : null; ?>
                    <article class="card overflow-hidden hover:shadow-md">
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
    <div class="mb-10">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center"><i class="fa-regular fa-folder-open text-blue-600 mr-2"></i>Uploaded Documents</h2>
            <div class="doc-carousel relative">
                <button type="button" aria-label="Previous documents" class="doc-carousel-btn prev disabled:opacity-40 disabled:cursor-not-allowed absolute left-0 top-1/2 -translate-y-1/2 z-10 w-9 h-9 rounded-full bg-white shadow flex items-center justify-center text-gray-600 hover:text-blue-600 hover:shadow-md transition"><i class="fa-solid fa-chevron-left"></i></button>
                <div class="pointer-events-none absolute inset-y-0 left-0 w-10 bg-gradient-to-r from-white to-transparent hidden md:block"></div>
                <div class="pointer-events-none absolute inset-y-0 right-0 w-10 bg-gradient-to-l from-white to-transparent hidden md:block"></div>
                <div class="doc-carousel-viewport overflow-hidden">
                    <div class="doc-carousel-track flex gap-5">
                        <?php foreach ($recent_documents as $doc): ?>
                            <?php $filePath = $doc['file_path'] ?? ''; $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION)); $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']); $docUrl = base_url($filePath); $icon = 'fa-file-lines text-gray-500'; if ($ext === 'pdf') $icon = 'fa-file-pdf text-blue-600'; elseif (in_array($ext,['doc','docx'])) $icon = 'fa-file-word text-blue-600'; elseif (in_array($ext,['xls','xlsx','csv'])) $icon = 'fa-file-excel text-blue-600'; elseif (in_array($ext,['ppt','pptx'])) $icon = 'fa-file-powerpoint text-blue-600'; ?>
                            <article class="card w-64 sm:w-72 flex-shrink-0">
                                <div class="relative h-36 w-full overflow-hidden bg-gray-100 flex items-center justify-center">
                                    <?php if ($isImage): ?>
                                        <img src="<?= $docUrl ?>" alt="<?= esc($doc['title'] ?? 'Document') ?>" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                                    <?php else: ?>
                                        <i class="fa-regular <?= $icon ?> text-5xl"></i>
                                    <?php endif; ?>
                                    <div class="absolute top-3 left-3"><span class="badge bg-white text-gray-800"><?= strtoupper($ext ?: 'FILE') ?></span></div>
                                    <div class="absolute bottom-3 right-3"><button onclick="previewDocument('<?= esc($docUrl) ?>', <?= $isImage ? 'true':'false' ?>)" class="inline-flex items-center px-3 py-1.5 rounded-md bg-white/90 text-gray-800 text-xs font-medium hover:bg-white"><i class="fa-regular fa-eye mr-1"></i> Preview</button></div>
                                </div>
                                <div class="p-4">
                                    <h3 class="text-sm font-semibold text-gray-900 truncate" title="<?= esc($doc['title'] ?? 'Untitled Document') ?>"><?= esc($doc['title'] ?? 'Untitled Document') ?></h3>
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
            <div id="posts-container" class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <article class="post-card group relative flex flex-col" data-id="<?= $post['id'] ?>">
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
                                    <?php if (!empty($post['category_name'])): ?><span class="chip" style="background-color: <?= $post['category_color'] ?>20;color: <?= $post['category_color'] ?>"><?= esc($post['category_name']) ?></span><?php endif; ?>
                                    <?php if (!empty($post['is_featured'])): ?><span class="chip bg-blue-100 text-blue-700">Featured</span><?php endif; ?>
                                    <?php if (!empty($post['is_urgent'])): ?><span class="chip bg-red-100 text-red-700">Urgent</span><?php endif; ?>
                                    <span class="chip bg-gray-100 text-gray-700"><?= ucfirst($post['visibility'] ?? 'public') ?></span>
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

    function showSkeleton(count=4){
        postsContainer.innerHTML = Array.from({length:count}).map(()=>`
            <div class=\"post-card skeleton flex flex-col\">
                <div class=\"skeleton-media h-40 w-full rounded-t-xl\"></div>
                <div class=\"p-4 space-y-3 flex-1\">
                    <div class=\"skeleton-line h-4 w-3/4\"></div>
                    <div class=\"skeleton-line h-3 w-full\"></div>
                    <div class=\"skeleton-line h-3 w-5/6\"></div>
                </div>
                <div class=\"px-4 pb-4 flex items-center gap-3\">
                    <div class=\"skeleton-avatar h-6 w-6 rounded-full\"></div>
                    <div class=\"skeleton-line h-3 w-24\"></div>
                </div>
            </div>`).join('');
    }
    function renderPosts(posts){
        if (!Array.isArray(posts) || posts.length === 0){
            postsContainer.innerHTML = `<div class=\"col-span-full\"><div class=\"bg-white border border-dashed border-gray-300 rounded-xl p-10 text-center\"><div class=\"mx-auto w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-4\"><i class=\"fa-regular fa-newspaper text-xl\"></i></div><h3 class=\"text-lg font-semibold text-gray-900\">No posts found</h3><p class=\"text-gray-500 mt-1\">Try different keywords or filters.</p></div></div>`;return;}
        postsContainer.innerHTML = posts.map(post=>{
            const title = post.title || 'Untitled';
            const hasImg = !!post.featured_image;
            const categoryChip = post.category_name ? `<span class=\"chip\" style=\"background-color:${post.category_color}20;color:${post.category_color}\">${post.category_name}</span>` : '';
            const featChip = post.is_featured ? `<span class=\"chip bg-blue-100 text-blue-700\">Featured</span>` : '';
            const urgentChip = post.is_urgent ? `<span class=\"chip bg-red-100 text-red-700\">Urgent</span>` : '';
            const visChip = `<span class=\"chip bg-gray-100 text-gray-700\">${(post.visibility||'public')[0].toUpperCase()+(post.visibility||'public').slice(1)}</span>`;
            const excerpt = (post.excerpt || (post.content||'').replace(/<[^>]*>/g,'')).substring(0,220);
            const dateStr = post.published_at || post.created_at ? new Date(post.published_at || post.created_at).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'}) : '';
            const views = parseInt(post.view_count||0).toLocaleString();
            const initial = ((post.first_name||'U')[0]||'U').toUpperCase();
            return `<article class=\"post-card group relative flex flex-col\" data-id=\"${post.id}\">\n        <div class=\"relative media w-full overflow-hidden rounded-t-xl\">\n          ${hasImg?`<img src=\"${baseImgUrl}${post.featured_image}\" alt=\"${title}\" class=\"w-full h-full object-cover duration-500 group-hover:scale-105\">`:`<div class=\"absolute inset-0 bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center text-blue-300\"><i class=\"fa-regular fa-image text-3xl\"></i></div>`}\n          <div class=\"absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent opacity-60 group-hover:opacity-70 transition\"></div>\n          <div class=\"absolute top-2 left-2 flex flex-wrap gap-1\">${categoryChip}${featChip}${urgentChip}${visChip}</div>\n        </div>\n        <div class=\"p-4 flex flex-col gap-2 flex-1\">\n          <h3 class=\"text-base font-semibold text-gray-900 leading-snug line-clamp-2 group-hover:text-blue-600 transition\"><a href=\"${baseViewUrl}${post.id}\" class=\"stretched-link relative z-10\">${title}</a></h3>\n          <p class=\"text-sm text-gray-600 leading-relaxed line-clamp-3\">${excerpt}${excerpt.length>=220?'...':''}</p>\n        </div>\n        <div class=\"px-4 pb-4 flex items-center justify-between text-xs text-gray-500\">\n          <div class=\"flex items-center gap-2\">\n            <div class=\"h-6 w-6 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center text-[10px] font-medium shadow-sm\">${initial}</div>\n            <span>${(post.first_name||'')+' '+(post.last_name||'')}</span><span class=\"text-gray-400\">•</span><span>${dateStr}</span>\n          </div>\n          <div class=\"flex items-center gap-1\"><i class=\"fa-regular fa-eye w-3 h-3\"></i><span>${views}</span></div>\n        </div>\n        <a href=\"${baseViewUrl}${post.id}\" class=\"absolute inset-0\" aria-label=\"Read post: ${title}\"></a>\n      </article>`;
        }).join('');
    }

  async function fetchAndRender({ q = '', categoryId = '' } = {}){
    try{
      let url='';
      if(q){ url = `<?= base_url('/bulletin/search') ?>?q=${encodeURIComponent(q)}&limit=30&offset=0`; }
      else if(categoryId){ url = `<?= base_url('/bulletin/category') ?>/${encodeURIComponent(categoryId)}?limit=30&offset=0`; }
      else{ return; }
      const res = await fetch(url,{ headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'} });
      const data = await res.json();
      if(!data.success) throw new Error(data.message||'Failed');
      renderPosts(Array.isArray(data.posts)?data.posts:[]);
    }catch(e){ console.error(e); }
  }

  let t;
    if(searchInput){ searchInput.addEventListener('input', ()=>{ clearTimeout(t); const val=searchInput.value.trim(); showSkeleton(); t=setTimeout(()=>fetchAndRender({ q: val }), 350); }); }
    if(categoryFilter){ categoryFilter.addEventListener('change', ()=>{ showSkeleton(); fetchAndRender({ categoryId: categoryFilter.value }); }); }
})();

// IntersectionObserver animations
(() => {
    const animated = document.querySelectorAll('.card, .animate-fade-in, .animate-slide-up, .animate-fade-in-delay, .animate-fade-in-more');
    const io = new IntersectionObserver(entries => {
        entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('in'); io.unobserve(e.target); }});
    }, { threshold: 0.15 });
    animated.forEach(el => io.observe(el));
})();

function previewDocument(url,isImage){
  if(isImage){ Swal.fire({ html:`<img src="${url}" style="width:100%;height:auto;border-radius:0.5rem"/>`, width:'60rem', showConfirmButton:false, showCloseButton:true, background:'#0B1220', color:'#fff' }); return; }
  Swal.fire({ html:`<iframe src="${url}" style="width:100%;height:70vh;border:0;border-radius:0.5rem;background:#fff"></iframe>`, width:'70rem', showConfirmButton:false, showCloseButton:true });
}

// Document carousel init
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.doc-carousel').forEach(root => {
        const viewport = root.querySelector('.doc-carousel-viewport');
        const prevBtn = root.querySelector('.doc-carousel-btn.prev');
        const nextBtn = root.querySelector('.doc-carousel-btn.next');
        if(!viewport || !prevBtn || !nextBtn) return;
        const scrollStep = () => Math.min(viewport.clientWidth * 0.9,  (viewport.querySelector('.card')?.clientWidth || 250) * 2 + 40);
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
.card{background:#fff;border-radius:.75rem;border:1px solid #e5e7eb;box-shadow:0 1px 3px rgba(0,0,0,.06),0 1px 2px rgba(0,0,0,.04);transition:box-shadow .25s ease,transform .25s ease;overflow:hidden}
.card:hover{box-shadow:0 10px 25px -5px rgba(59,130,246,.25),0 8px 10px -6px rgba(59,130,246,.18);transform:translateY(-3px)}
.card-title{font-size:1.125rem;line-height:1.5rem;font-weight:700;color:#111827}
.card-excerpt{color:#4b5563;margin-top:.25rem;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
.badge{display:inline-flex;align-items:center;padding:.125rem .625rem;border-radius:9999px;font-size:.75rem;font-weight:600}
@keyframes fadeInUp{0%{opacity:0;transform:translateY(14px)}100%{opacity:1;transform:translateY(0)}}
@keyframes sheen{0%{transform:translateX(-100%)}50%{transform:translateX(100%)}100%{transform:translateX(100%)}}
@keyframes pulseSlow{0%,100%{opacity:.55}50%{opacity:.25}}
.animate-fade-in,.animate-slide-up,.animate-fade-in-delay,.animate-fade-in-more,.card{opacity:0;transition:opacity .6s ease,transform .6s ease}
.animate-fade-in.in{animation:fadeInUp .7s ease forwards}
.animate-slide-up.in{animation:fadeInUp .8s ease forwards}
.animate-fade-in-delay.in{animation:fadeInUp .9s ease forwards}
.animate-fade-in-more.in{animation:fadeInUp 1s ease forwards}
.card.in{animation:fadeInUp .6s ease forwards}
.animate-sheen{position:relative;overflow:hidden}
.animate-sheen::after{content:"";position:absolute;inset:0;background:linear-gradient(90deg,transparent,rgba(255,255,255,.9),transparent);transform:translateX(-100%);animation:sheen 6s linear infinite}
.animate-pulse-slow{animation:pulseSlow 8s ease-in-out infinite}
/* Enhanced All Posts UI */
.post-card{background:#fff;border:1px solid #e5e7eb;border-radius:1rem;box-shadow:0 2px 4px rgba(0,0,0,.04),0 1px 2px rgba(0,0,0,.04);transition:box-shadow .35s cubic-bezier(.4,0,.2,1),transform .35s cubic-bezier(.4,0,.2,1);position:relative;overflow:hidden}
.post-card:hover{box-shadow:0 12px 28px -6px rgba(59,130,246,.28),0 8px 16px -8px rgba(59,130,246,.18);transform:translateY(-4px)}
.post-card .stretched-link::after{content:"";position:absolute;inset:0}
.chip{display:inline-flex;align-items:center;font-size:.625rem;letter-spacing:.5px;font-weight:600;padding:.25rem .55rem;border-radius:.65rem;text-transform:uppercase;line-height:1;background:#f1f5f9;color:#334155;backdrop-filter:blur(4px)}
.line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.line-clamp-3{display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
/* Skeleton Loading */
.skeleton{position:relative;border:1px solid #e2e8f0;border-radius:1rem;background:#fff;overflow:hidden}
.skeleton-media,.skeleton-line,.skeleton-avatar{background:linear-gradient(90deg,#f1f5f9 0%,#e2e8f0 50%,#f1f5f9 100%);background-size:200% 100%;animation:skeleton 1.4s ease-in-out infinite;border-radius:.5rem}
.skeleton-line{border-radius:.375rem}
.skeleton-avatar{border-radius:9999px}
@keyframes skeleton{0%{background-position:200% 0}100%{background-position:-200% 0}}
/* Document carousel specific */
.doc-carousel-viewport{scroll-behavior:smooth;}
.doc-carousel-viewport::-webkit-scrollbar{height:8px}
.doc-carousel-viewport::-webkit-scrollbar-track{background:transparent}
.doc-carousel-viewport::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:4px}
.doc-carousel-viewport:hover::-webkit-scrollbar-thumb{background:#94a3b8}
</style>
