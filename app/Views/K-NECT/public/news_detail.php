<?php
/** @var array $post */
/** @var string $page_title */
/** @var string $canonicalUrl */
/** @var string $pageDescription */
/** @var string|null $siteLogoUrl */
/** @var array $relatedPosts */

$resolveNewsImg = function($val) {
    if(!$val) return null;
    // If already an absolute URL
    if(preg_match('#^https?://#i',$val)) return $val;
    // Normalize leading slashes
    $trim = ltrim($val,'/');
    // If path already begins with uploads/bulletin keep as is
    if(stripos($trim,'uploads/bulletin/') === 0) {
        return base_url($trim);
    }
    // Some DB rows might only store filename; prepend standard directory
    return base_url('uploads/bulletin/'.$trim);
};

$featuredImage = !empty($post['featured_image']) ? $resolveNewsImg($post['featured_image']) : null;
$categoryName = $post['category_name'] ?? null;
$categoryColor = $post['category_color'] ?? null;
$author = trim(($post['first_name'] ?? '').' '.($post['last_name'] ?? '')) ?: ($post['username'] ?? 'Author');
$barangayName = $post['barangay_name'] ?? null;
$publishedAt = $post['published_at'] ? (new DateTime($post['published_at']))->format('F j, Y g:i A') : null;
$viewCount = $post['view_count'] ?? 0;

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= esc($page_title) ?></title>
    <meta name="description" content="<?= esc($pageDescription) ?>" />
    <link rel="canonical" href="<?= esc($canonicalUrl) ?>" />
    <meta property="og:title" content="<?= esc($post['title']) ?>" />
    <meta property="og:description" content="<?= esc(mb_strimwidth(strip_tags($post['excerpt'] ?? $post['content'] ?? ''),0,140,'…')) ?>" />
    <?php if($featuredImage): ?><meta property="og:image" content="<?= esc($featuredImage) ?>" /><?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Domine:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        :root{--ink:#0f172a;--sub:#334155;--muted:#64748b;--border:#e2e8f0;--bg:#f8fafc;--panel:#ffffff;--primary:#2563eb;--primary-50:#eff6ff;--primary-100:#dbeafe;--primary-600:#2563eb;--primary-700:#1d4ed8;--radius:18px}
        *{box-sizing:border-box}body{margin:0;font-family:'Inter',system-ui,sans-serif;background:var(--bg);color:var(--ink);line-height:1.5}
        a{text-decoration:none;color:inherit}
        img{max-width:100%;display:block}
        .container{max-width:1250px;margin:0 auto;padding:0 1.25rem}
        header.site{position:sticky;top:0;background:rgba(255,255,255,.92);backdrop-filter:blur(10px);border-bottom:1px solid var(--border);z-index:60}
        .nav{display:flex;align-items:center;gap:1rem;height:78px}
        .brand img{height:60px;width:auto}
        .nav-links{display:flex;gap:1rem;margin-left:auto;font-size:.9rem;font-weight:600}
        .nav-links a{position:relative;padding:.25rem 0;color:var(--ink)}
        .nav-links a:after{content:"";position:absolute;left:0;bottom:-3px;height:2px;width:0;background:var(--primary);transition:.3s;border-radius:2px}
        .nav-links a:hover:after{width:100%}
        .btn{display:inline-flex;align-items:center;gap:.55rem;font-size:.75rem;font-weight:700;padding:.55rem .95rem;border:1px solid var(--primary);color:var(--primary);border-radius:40px;background:#fff;transition:.25s;box-shadow:0 6px 16px -10px rgba(37,99,235,.35)}
        .btn:hover{background:var(--primary);color:#fff}
        .btn-primary{background:var(--primary);color:#fff;border-color:var(--primary)}
        .btn-primary:hover{background:var(--primary-700)}
    /* Hero Banner (article) - enhanced visibility */
    /* Full-image mode: show entire picture without cropping */
    /* Compact full-image hero: show entire image within a smaller vertical space */
    .news-hero{position:relative;margin:0 0 1.75rem;display:flex;align-items:flex-end}
    .news-hero.full-image{display:block;--hero-max-h:clamp(200px,38vh,420px)}
    .news-hero.full-image .hero-media{position:relative;max-height:var(--hero-max-h);display:flex;justify-content:center;align-items:center;overflow:hidden;padding:0;background:#0f172a;border-radius:28px;box-shadow:0 14px 46px -24px rgba(15,46,105,.4),0 6px 18px -10px rgba(15,46,105,.25)}
    .news-hero.full-image .hero-media img{position:relative;max-height:100%;width:auto;height:auto;object-fit:contain;filter:none;transform:none;margin:0 auto}
    /* Fallback (non full-image mode) retains original cover behavior */
    .hero-media{position:absolute;inset:0;overflow:hidden;background:#1d4ed8;}
    .hero-media img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;filter:brightness(.95) saturate(1.05);}
    .hero-media::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(15,23,42,0) 0%,rgba(15,23,42,.25) 55%,rgba(248,250,252,.92) 92%, #f8fafc 100%)}
    .hero-inner:before{content:"";position:absolute;left:0;right:0;bottom:-1.4rem;height:120px;background:radial-gradient(circle at 50% 0,rgba(15,23,42,.16),rgba(15,23,42,0) 70%);pointer-events:none}
    .hero-surface{backdrop-filter:blur(3px);background:rgba(255,255,255,.55);padding:1.4rem 1.6rem 1.6rem;border-radius:32px 32px 0 0;box-shadow:0 12px 40px -18px rgba(15,46,105,.35);}
    @media (max-width:900px){.hero-surface{padding:1.1rem 1rem 1.3rem;border-radius:28px 28px 0 0}}
        .hero-inner{position:relative;z-index:2;width:100%}
        .badge-line{display:flex;flex-wrap:wrap;gap:.5rem;margin:0 0 .9rem}
        .pill{font:600 .6rem/1 'Inter',sans-serif;letter-spacing:.6px;text-transform:uppercase;padding:.45rem .7rem;border-radius:999px;display:inline-flex;align-items:center;gap:.4rem;background:var(--primary-50);color:var(--primary-700);border:1px solid var(--primary-100)}
        .pill.category{background:linear-gradient(135deg,#9333ea,#6366f1);color:#fff;border:none}
        h1.title{font-family:'Domine',serif;font-size:clamp(2.1rem,3.2vw,3rem);line-height:1.08;margin:0 0 .9rem;color:#0f172a;text-shadow:0 2px 6px rgba(255,255,255,.55)}
        .meta{display:flex;flex-wrap:wrap;gap:.75rem 1.25rem;font-size:.85rem;font-weight:500;color:#334155;margin-bottom:.8rem}
        .meta span{display:inline-flex;align-items:center;gap:.45rem}
        main.news-main{margin:0 0 3rem}
        .layout{display:grid;grid-template-columns:minmax(0,1fr) 340px;gap:2rem;align-items:start}
        @media(max-width:980px){.layout{grid-template-columns:1fr}}
        article.news-body{background:#ffffff;border:1px solid var(--border);border-radius:24px;padding:2rem 2rem 2.4rem;box-shadow:0 18px 40px -24px rgba(15,46,105,.25),0 4px 10px -6px rgba(15,46,105,.15);position:relative;overflow:hidden}
        article.news-body:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 85% 15%,rgba(37,99,235,.08),transparent 60%);pointer-events:none}
        .news-content{font-size:1rem;line-height:1.75;color:#334155}
        .news-content p{margin:0 0 1.2rem}
        .news-content h2,.news-content h3,.news-content h4{font-family:'Inter',sans-serif;font-weight:600;line-height:1.2;margin:2rem 0 1rem;color:#0f172a}
        .news-content ul,.news-content ol{padding-left:1.2rem;margin:0 0 1.2rem}
        aside.sidebar{display:flex;flex-direction:column;gap:1.25rem}
        .side-block{background:#ffffff;border:1px solid var(--border);border-radius:20px;padding:1.15rem 1.25rem 1.3rem;position:relative;box-shadow:0 10px 26px -16px rgba(15,46,105,.18)}
        .side-block h3{margin:.15rem 0 1rem;font:700 .7rem/1 'Inter',sans-serif;letter-spacing:.55px;text-transform:uppercase;color:#64748b}
        .info-list{display:flex;flex-direction:column;gap:.65rem;font-size:.86rem;color:#334155}
        .info-list span{display:flex;gap:.55rem;align-items:flex-start;line-height:1.4}
        .related-wrap{margin-top:3rem}
        .related-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1rem}
        .rel-card{background:#fff;border:1px solid var(--border);border-radius:18px;padding:.85rem .9rem;display:flex;flex-direction:column;gap:.5rem;box-shadow:0 8px 22px -18px rgba(15,46,105,.2);transition:.3s}
        .rel-card:hover{transform:translateY(-4px);box-shadow:0 16px 38px -20px rgba(15,46,105,.28)}
        .rel-card h4{margin:0;font:600 .9rem/1.2 'Inter',sans-serif;color:#0f172a;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
        .rel-meta{font-size:.65rem;font-weight:600;letter-spacing:.5px;text-transform:uppercase;color:#475569}
        footer.site{background:linear-gradient(180deg,#0b1a3a,#0f172a);color:#d1d5db;padding:2.4rem 0 2rem;margin-top:3rem}
        footer.site h4{margin:0 0 1rem;font-size:.75rem;letter-spacing:.8px;text-transform:uppercase;color:#fff}
        .fwrap{display:grid;gap:2rem;grid-template-columns:repeat(auto-fit,minmax(200px,1fr))}
        footer.site a{display:block;font-size:.75rem;color:#d1d5db;padding:.3rem 0}
        footer.site a:hover{color:#93c5fd}
        .copyright{text-align:center;margin-top:1.6rem;font-size:.7rem;letter-spacing:.3px}
        .sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}
    </style>
</head>
<body>
<header class="site" role="banner">
    <div class="container nav">
        <a class="brand" href="<?= base_url('/') ?>" aria-label="K-NECT home">
            <img src="<?= esc($siteLogoUrl ?? base_url('favicon.ico')) ?>" alt="K-NECT logo" style="height:60px;width:auto;object-fit:contain" />
        </a>
        <nav class="nav-links" aria-label="Primary">
            <a href="<?= base_url('/') ?>#programs">Programs</a>
            <a href="<?= base_url('/') ?>#stories">Stories</a>
            <a href="<?= base_url('/') ?>#highlights">Highlights</a>
            <a href="<?= base_url('/') ?>#events">Events</a>
        </nav>
        <div style="margin-left:auto;display:flex;gap:.55rem;">
            <a href="<?= base_url('login') ?>" class="btn">Sign In</a>
            <a href="<?= base_url('profiling') ?>" class="btn-primary btn">Profile Now</a>
        </div>
    </div>
</header>
<nav aria-label="Breadcrumb" style="background:var(--primary-50);border-bottom:1px solid var(--border);font-size:.7rem;font-weight:600;letter-spacing:.5px;">
    <div class="container" style="padding:.55rem 1.25rem;display:flex;align-items:center;gap:.4rem;flex-wrap:wrap;">
        <a href="<?= base_url('/') ?>" style="color:var(--primary-700);">Home</a>
        <span style="opacity:.5">/</span>
        <a href="<?= base_url('/') ?>#stories" style="color:var(--primary-700);">Stories</a>
        <span style="opacity:.5">/</span>
        <span aria-current="page" style="color:#0f172a;white-space:nowrap;max-width:40ch;overflow:hidden;text-overflow:ellipsis;"><?= esc($post['title']) ?></span>
    </div>
</nav>
<section class="news-hero full-image" aria-label="News banner">
    <div class="hero-media" aria-hidden="true">
        <?php if($featuredImage): ?>
            <img src="<?= esc($featuredImage) ?>" alt="Featured image for <?= esc($post['title']) ?>" loading="eager" />
        <?php else: ?>
            <img src="https://via.placeholder.com/1600x900?text=News+Image" alt="Placeholder image" loading="eager" />
        <?php endif; ?>
    </div>
    <div class="container hero-inner">
        <div class="hero-surface">
        <div class="badge-line">
            <?php if($categoryName): ?><span class="pill category" style="<?= $categoryColor? 'background: linear-gradient(135deg,'.$categoryColor.',#6366f1);':'' ?>"><?= esc($categoryName) ?></span><?php endif; ?>
        </div>
        <h1 class="title"><?= esc($post['title']) ?></h1>
        <div class="meta" aria-label="Article metadata">
            <?php if($publishedAt): ?><span><i class="fa-regular fa-calendar"></i> <?= esc($publishedAt) ?></span><?php endif; ?>
            <span><i class="fa-regular fa-user"></i> <?= esc($author) ?></span>
            <?php if($barangayName): ?><span><i class="fa-regular fa-flag"></i> <?= esc($barangayName) ?></span><?php endif; ?>
            <span><i class="fa-regular fa-eye"></i> <?= esc(number_format($viewCount)) ?> views</span>
        </div>
        <div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-top:.4rem">
            <button type="button" class="btn" style="--btn-bg:#fff;--btn-border:var(--primary-600);font-size:.65rem" onclick="navigator.share?navigator.share({title:'<?= esc(addslashes($post['title'])) ?>',url:window.location.href}):copyLink()"><i class="fa-solid fa-share-nodes"></i> Share</button>
            <button type="button" class="btn" style="font-size:.65rem" onclick="copyLink()"><i class="fa-regular fa-copy"></i> Copy Link</button>
        </div>
        </div>
    </div>
</section>
<main class="news-main">
    <div class="container layout">
        <article class="news-body" aria-labelledby="news-content-heading">
            <h2 id="news-content-heading" class="sr-only">Article Content</h2>
            <div class="news-content">
                <?php
                    $content = $post['content'] ?? '';
                    $content = preg_replace('#<\s*(script|style)(.|\n)*?<\s*/\s*\1>#i','',$content);
                    $allowed = '<p><br><strong><em><ul><ol><li><h2><h3><h4><blockquote><a><b><i><img>'; 
                    $sanitized = strip_tags($content,$allowed);
                    echo $sanitized ? $sanitized : '<p><em>No content available.</em></p>';
                ?>
            </div>
        </article>
        <aside class="sidebar" aria-label="Supplemental article info">
            <section class="side-block" aria-labelledby="about-heading">
                <h3 id="about-heading">About</h3>
                <div class="info-list">
                    <?php if($categoryName): ?><span><i class="fa-solid fa-bookmark"></i> Category: <?= esc($categoryName) ?></span><?php endif; ?>
                    <?php if($barangayName): ?><span><i class="fa-regular fa-flag"></i> Barangay: <?= esc($barangayName) ?></span><?php endif; ?>
                    <span><i class="fa-regular fa-user"></i> Author: <?= esc($author) ?></span>
                    <?php if($publishedAt): ?><span><i class="fa-regular fa-calendar"></i> Published: <?= esc($publishedAt) ?></span><?php endif; ?>
                    <span><i class="fa-regular fa-eye"></i> Views: <?= esc(number_format($viewCount)) ?></span>
                </div>
            </section>
            <?php if(!empty($relatedPosts)): ?>
            <section class="side-block" aria-labelledby="rel-heading">
                <h3 id="rel-heading">Related Stories</h3>
                <div class="info-list" style="gap:.9rem">
                    <?php foreach($relatedPosts as $rp):
                        $rpImg = !empty($rp['featured_image']) ? ($resolveNewsImg)($rp['featured_image']) : 'https://via.placeholder.com/400x300?text=News';
                        $rpDate = !empty($rp['published_at']) ? date('M j, Y', strtotime($rp['published_at'])) : '';
                    ?>
                    <a href="<?= base_url('news/'.$rp['id']) ?>" style="display:grid;grid-template-columns:54px 1fr;gap:.6rem;align-items:center;text-decoration:none;color:inherit">
                        <span style="width:54px;height:54px;overflow:hidden;border-radius:12px;background:#f1f5f9;display:block;border:1px solid var(--border);box-shadow:0 4px 10px -6px rgba(15,46,105,.18)"><img src="<?= esc($rpImg) ?>" alt="<?= esc($rp['title']) ?>" style="width:100%;height:100%;object-fit:cover"></span>
                        <span style="display:flex;flex-direction:column;gap:.25rem">
                            <strong style="font-size:.7rem;line-height:1.1;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;"><?= esc($rp['title']) ?></strong>
                            <small style="font-size:.55rem;font-weight:600;letter-spacing:.5px;color:#475569;"><?= esc($rpDate) ?></small>
                        </span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
        </aside>
    </div>
</main>
<section class="related-wrap" aria-label="Related articles section">
    <div class="container">
        <?php if(!empty($relatedPosts)): ?>
        <h2 style="font:700 .8rem/1 'Inter',sans-serif;letter-spacing:.6px;text-transform:uppercase;color:#64748b;margin:0 0 1.2rem">More Stories</h2>
        <div class="related-grid">
            <?php foreach($relatedPosts as $rp):
                $rpImg = !empty($rp['featured_image']) ? ($resolveNewsImg)($rp['featured_image']) : 'https://via.placeholder.com/600x400?text=News';
                $rpDate = !empty($rp['published_at']) ? date('M j, Y', strtotime($rp['published_at'])) : '';
            ?>
            <a class="rel-card" href="<?= base_url('news/'.$rp['id']) ?>" aria-label="Read story: <?= esc($rp['title']) ?>">
                <div style="position:relative;width:100%;aspect-ratio:4/3;overflow:hidden;border-radius:12px;background:#f1f5f9;border:1px solid var(--border);box-shadow:0 6px 16px -10px rgba(15,46,105,.12)">
                    <img src="<?= esc($rpImg) ?>" alt="<?= esc($rp['title']) ?> image" style="width:100%;height:100%;object-fit:cover" loading="lazy" />
                </div>
                <h4><?= esc($rp['title']) ?></h4>
                <div class="rel-meta"><i class="fa-regular fa-calendar"></i> <?= esc($rpDate) ?></div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<footer class="site">
    <div class="container fwrap">
        <div>
            <h4>Platform</h4>
            <a href="<?= base_url('/') ?>#top">Overview</a>
            <a href="<?= base_url('/') ?>#programs">Programs</a>
            <a href="<?= base_url('/') ?>#stories">Stories</a>
        </div>
        <div>
            <h4>Engagement</h4>
            <a href="<?= base_url('/') ?>#events">Events</a>
            <a href="<?= base_url('/') ?>#highlights">Highlights</a>
        </div>
        <div>
            <h4>Legal</h4>
            <a href="#">Privacy</a>
            <a href="#">Terms</a>
        </div>
        <div>
            <h4>Access</h4>
            <a href="<?= base_url('login') ?>">Sign In</a>
        </div>
    </div>
    <div class="container copyright">&copy; <?= date('Y') ?> K-NECT Platform. All rights reserved.</div>
</footer>
<script>
    function copyLink(){
        const url = window.location.href;
        if(navigator.clipboard){
            navigator.clipboard.writeText(url).then(()=>{toast('Link copied');}).catch(()=>fallback());
        } else { fallback(); }
        function fallback(){
            const ta=document.createElement('textarea');
            ta.value=url;document.body.appendChild(ta);ta.select();try{document.execCommand('copy');toast('Link copied')}catch(e){};ta.remove();
        }
    }
    function toast(msg){
        let t=document.getElementById('toast');
        if(!t){t=document.createElement('div');t.id='toast';document.body.appendChild(t);Object.assign(t.style,{position:'fixed',left:'50%',bottom:'28px',transform:'translateX(-50%)',background:'#0f172a',color:'#fff',padding:'.6rem .95rem',borderRadius:'14px',fontSize:'.75rem',fontWeight:'600',letterSpacing:'.5px',boxShadow:'0 8px 24px -10px rgba(15,23,42,.4)',zIndex:200,opacity:'0',transition:'opacity .3s, transform .3s'});} 
        t.textContent=msg;t.style.opacity='1';t.style.transform='translateX(-50%) translateY(0)';
        clearTimeout(window.__toastTimer);window.__toastTimer=setTimeout(()=>{t.style.opacity='0';},2200);
    }
</script>
</body>
</html>
