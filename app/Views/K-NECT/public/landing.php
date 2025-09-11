<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= esc($page_title ?? 'K-NECT Platform') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Domine:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        :root { --ink:#111827; --sub:#374151; --muted:#6b7280; --border:#e5e7eb; --bg:#faf8f2; --panel:#ffffff; --accent:#111827; --soft:#f4f1eb; --radius:18px; }
        *{box-sizing:border-box;} body{margin:0;font-family:'Inter',system-ui,sans-serif;background:var(--bg);color:var(--ink);} a{text-decoration:none;color:inherit;} img{max-width:100%;display:block;}
        header{padding:.15rem 1.1rem;position:sticky;top:0;background:rgba(255,255,255,.92);backdrop-filter:blur(12px);border-bottom:1px solid var(--border);z-index:60;}
    .nav{max-width:1250px;margin:0 auto;display:flex;align-items:center;gap:1rem;} .nav .brand{display:flex;align-items:center;} .nav .brand img{height:80px;width:auto;display:block;}
    @media(max-width:600px){ header{padding:.1rem .9rem;} .nav .brand img{ height:64px; } }
        .nav-links{display:flex;gap:1rem;margin-left:auto;font-size:.85rem;font-weight:500;} .nav-links a{position:relative;padding:.1rem 0;} .nav-links a:after{content:"";position:absolute;left:0;bottom:-3px;height:2px;width:0;background:#111827;transition:.35s;border-radius:2px;} .nav-links a:hover:after{width:100%;}
        .btn{display:inline-flex;align-items:center;gap:.55rem;font-size:.75rem;font-weight:600;padding:.65rem 1.1rem;border:1px solid #111827;color:#111827;border-radius:40px;background:#fff;transition:.35s;} .btn:hover{background:#111827;color:#fff;} header .btn{padding:.45rem .9rem;}
        .btn-primary{background:#111827;color:#fff;border-color:#111827;} .btn-primary:hover{background:#000;}
        .layout{max-width:1340px;margin:2.4rem auto 4rem;padding:0 1.25rem;display:grid;grid-template-columns:1fr 380px;gap:2.25rem;}
        @media(max-width:1100px){.layout{grid-template-columns:1fr;} .desktop-side{display:none;}}
        /* HERO SPLIT */
        .hero-split{display:grid;grid-template-columns:repeat(12,1fr);gap:2.25rem;margin-bottom:2.5rem;align-items:stretch;}
        .hero-left{grid-column:span 5;background:var(--panel);border:1px solid var(--border);border-radius:var(--radius);padding:3.2rem 2.4rem;display:flex;flex-direction:column;justify-content:center;}
        .hero-left h1{font-family:'Domine',serif;font-size:clamp(2.4rem,3vw,3rem);line-height:1.05;margin:0 0 1.2rem;color:var(--ink);} .hero-left p{margin:0 0 1.6rem;font-size:.95rem;line-height:1.55;color:var(--sub);max-width:36ch;}
        .hero-media{grid-column:span 7;background:#ddd;border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;position:relative;display:flex;flex-direction:column;}
        .hero-media img.cover{width:100%;height:100%;object-fit:cover;aspect-ratio:16/9;}
        .hero-badges{display:flex;flex-wrap:wrap;gap:.55rem;margin-bottom:1.2rem;}
        .badge{display:inline-flex;align-items:center;font-size:.62rem;font-weight:600;letter-spacing:.5px;text-transform:uppercase;background:#111827;color:#fff;padding:.45rem .7rem;border-radius:30px;}
        .badge.alt{background:#fef08a;color:#1c1917;} .badge.line{background:#fff;color:#111827;border:1px solid #111827;}
        .cta-row{display:flex;flex-wrap:wrap;gap:.7rem;}
        /* PROMO BAND */
        .promo{background:#f4d469;border:1px solid #e7c95d;border-radius:var(--radius);padding:2.2rem 2rem;margin-bottom:3rem;position:relative;overflow:hidden;}
        .promo h2{margin:0 0 1rem;font-family:'Domine',serif;font-size:1.9rem;max-width:26ch;line-height:1.15;}
        .pattern{height:84px;background:repeating-linear-gradient(45deg,#f59e0b 0 40px,#2563eb 40px 80px,#dc2626 80px 120px,#2563eb 120px 160px);border-radius:14px;margin-top:1.4rem;opacity:.9;}
        /* RESOURCES GRID */
        .section-title{font-family:'Domine',serif;font-size:1.55rem;margin:0 0 1.6rem;}
        .resource-grid{display:grid;gap:1rem;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));margin-bottom:3.2rem;}
        .resource-card{background:var(--panel);border:1px solid var(--border);border-radius:14px;padding:1.2rem 1.05rem 1.25rem;display:flex;flex-direction:column;gap:.75rem;transition:.35s;}
        .resource-card:hover{box-shadow:0 6px 20px -6px rgba(17,24,39,.15);transform:translateY(-4px);} .resource-icon{width:2.2rem;height:2.2rem;border-radius:10px;background:#111827;color:#fff;display:flex;align-items:center;justify-content:center;font-size:.9rem;}
        .resource-card h3{margin:0;font-size:.9rem;font-weight:600;color:var(--ink);} .resource-card p{margin:0;font-size:.7rem;line-height:1.4;color:var(--muted);} .link-more{font-size:.65rem;font-weight:600;letter-spacing:.5px;text-transform:uppercase;display:inline-flex;align-items:center;gap:.35rem;margin-top:auto;color:#111827;}
        /* SERVICES STRIP */
        .services-row{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1.2rem;margin-bottom:3.5rem;}
        .service{position:relative;background:#111827;color:#fff;border-radius:18px;padding:1.4rem 1.25rem 1.6rem;overflow:hidden;min-height:180px;display:flex;flex-direction:column;gap:.75rem;}
        .service:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 70% 20%,rgba(255,255,255,.15),transparent 60%);opacity:.7;}
        .service i{font-size:1.2rem;} .service h3{margin:0;font-size:1rem;font-family:'Domine',serif;} .service p{margin:0;font-size:.72rem;line-height:1.45;color:#e5e7eb;}
        /* POSTS SECTION */
        .posts{margin-bottom:3.5rem;}
        .post-grid{display:grid;gap:1.2rem;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));}
        .post{background:var(--panel);border:1px solid var(--border);border-radius:16px;overflow:hidden;display:flex;flex-direction:column;transition:.4s;position:relative;}
        .post:hover{box-shadow:0 10px 28px -6px rgba(17,24,39,.2);transform:translateY(-5px);} .post figure{margin:0;height:150px;overflow:hidden;position:relative;} .post figure img{width:100%;height:100%;object-fit:cover;}
        .post .chips{position:absolute;top:10px;left:10px;display:flex;flex-wrap:wrap;gap:.4rem;}
        .chip{background:#fff;border-radius:30px;padding:.35rem .6rem;font-size:.55rem;font-weight:600;letter-spacing:.5px;box-shadow:0 2px 4px rgba(0,0,0,.06);} .chip.yellow{background:#fef08a;} .chip.red{background:#fecaca;} .chip.line{background:#f1f5f9;}
        .post .body{padding:1rem 1rem 1.1rem;display:flex;flex-direction:column;gap:.75rem;flex:1;} .post h4{margin:0;font-size:.95rem;font-weight:600;line-height:1.25;color:var(--ink);} .post p{margin:0;font-size:.7rem;line-height:1.4;color:var(--muted);} .meta{display:flex;align-items:center;gap:.55rem;font-size:.6rem;color:var(--muted);margin-top:auto;}
        /* EVENTS */
        .events{margin-bottom:3.5rem;} .events-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1.1rem;}
        .event-card{background:#fff;border:1px solid var(--border);border-radius:16px;overflow:hidden;display:flex;flex-direction:column;transition:.35s;} .event-card:hover{box-shadow:0 8px 24px -6px rgba(17,24,39,.18);transform:translateY(-4px);} .event-banner{height:110px;overflow:hidden;position:relative;} .event-banner img{width:100%;height:100%;object-fit:cover;} .event-date{position:absolute;bottom:8px;left:8px;background:#111827;color:#fff;font-size:.55rem;padding:.35rem .55rem;border-radius:6px;font-weight:600;letter-spacing:.5px;}
        .event-body{padding:.85rem .85rem 1rem;display:flex;flex-direction:column;gap:.55rem;} .event-body h5{margin:0;font-size:.8rem;line-height:1.25;color:var(--ink);} .event-body span{font-size:.6rem;color:var(--muted);} .more-link{font-size:.6rem;font-weight:600;display:inline-flex;align-items:center;gap:.35rem;}
        /* SIDEBAR */
        .sidebar-block{background:var(--panel);border:1px solid var(--border);border-radius:18px;padding:1.6rem 1.4rem;display:flex;flex-direction:column;gap:1.1rem;margin-bottom:1.5rem;}
        .sidebar-block h3{margin:0;font-family:'Domine',serif;font-size:1.05rem;}
        .mini-post{display:flex;gap:.75rem;align-items:center;padding:.55rem 0;border-bottom:1px solid var(--border);} .mini-post:last-child{border-bottom:0;} .mini-post img{width:60px;height:48px;object-fit:cover;border-radius:10px;border:1px solid var(--border);} .mini-post h4{margin:0;font-size:.65rem;line-height:1.2;font-weight:600;} .mini-post span{font-size:.55rem;color:var(--muted);}
        /* FOOTER */
        footer{background:#111827;color:#d1d5db;padding:3.5rem 1.5rem 2.5rem;margin-top:4rem;}
        .fwrap{max-width:1250px;margin:0 auto;display:grid;gap:2.5rem;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));}
        footer h4{margin:0 0 1rem;font-size:.75rem;letter-spacing:.8px;text-transform:uppercase;color:#fff;} footer a{display:block;font-size:.7rem;color:#d1d5db;padding:.3rem 0;} footer a:hover{color:#fff;}
        .copyright{text-align:center;margin-top:2.4rem;font-size:.65rem;letter-spacing:.5px;}
        @media(max-width:900px){.hero-split{grid-template-columns:1fr;}.hero-left{grid-column:span 12;}.hero-media{grid-column:span 12;height:320px;}.layout{margin-top:1.5rem;}}
    </style>
</head>
<body>
    <header>
        <div class="nav">
            <div class="brand"><img src="<?= base_url('/assets/images/K-Nect-Logo.png') ?>" alt="K-NECT logo" style="height:30px;width:auto;display:block;margin:20px;"></div>
            <nav class="nav-links">
                <a href="#services">Services</a>
                <a href="#resources">Resources</a>
                <a href="#posts">Updates</a>
                <a href="#events">Events</a>
            </nav>
            <div style="margin-left:auto;display:flex;gap:.65rem;">
                <a href="<?= base_url('login') ?>" class="btn">Sign In</a>
                <a href="<?= base_url('login') ?>" class="btn-primary btn">Access Portal</a>
            </div>
        </div>
    </header>
    <main class="layout">
        <div>
            <!-- HERO -->
            <section class="hero-split" id="top">
                <div class="hero-left">
                    <div class="hero-badges">
                        <span class="badge line">Youth</span>
                        <span class="badge line">Participation</span>
                    </div>
                    <h1>Empowering Youth Leadership & Community Impact</h1>
                    <p>The integrated platform for announcements, events, documents, analytics and unified governance across every barangay and city federation leadership circle.</p>
                    <div class="cta-row">
                        <a href="<?= base_url('login') ?>" class="btn-primary btn"><i class="fa-solid fa-gauge-high"></i> Enter Portal</a>
                        <a href="#services" class="btn"><i class="fa-solid fa-circle-info"></i> Explore</a>
                    </div>
                </div>
                <div class="hero-media">
                    <?php $heroPost = $posts[0] ?? null; if($heroPost): ?>
                        <img class="cover" src="<?= !empty($heroPost['featured_image']) ? base_url('/uploads/bulletin/'.$heroPost['featured_image']) : 'https://via.placeholder.com/1200x700?text=Youth+Engagement' ?>" alt="Hero Feature">
                    <?php else: ?>
                        <img class="cover" src="https://via.placeholder.com/1200x700?text=K-NECT+Platform" alt="K-NECT">
                    <?php endif; ?>
                </div>
            </section>

            <!-- PROMO / ADMISSIONS STYLE BANNER -->
            <div class="promo" id="services">
                <h2>Join a unified, data‑driven youth development ecosystem today</h2>
                <a href="<?= base_url('login') ?>" class="btn" style="background:#111827;color:#fff;border-color:#111827;">Get Access</a>
                <div class="pattern"></div>
            </div>

            <!-- SERVICES STRIP -->
            <section class="services-row">
                <?php foreach (($services ?? []) as $s): ?>
                    <div class="service">
                        <i class="fa-solid <?= esc($s['icon']) ?>"></i>
                        <h3><?= esc($s['title']) ?></h3>
                        <p><?= esc($s['desc']) ?></p>
                    </div>
                <?php endforeach; ?>
            </section>

            <!-- RESOURCES GRID -->
            <section id="resources">
                <h2 class="section-title">Essential Resources for Youth Programs</h2>
                <div class="resource-grid">
                    <?php foreach (($resources ?? []) as $r): ?>
                        <div class="resource-card">
                            <div class="resource-icon"><i class="fa-solid <?= esc($r['icon']) ?>"></i></div>
                            <h3><?= esc($r['title']) ?></h3>
                            <p><?= esc($r['desc']) ?></p>
                            <span class="link-more">Learn More <i class="fa-solid fa-arrow-right"></i></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- POSTS -->
            <section class="posts" id="posts">
                <h2 class="section-title">Latest Platform Updates</h2>
                <div class="post-grid">
                    <?php foreach (($posts ?? []) as $p): ?>
                        <article class="post">
                            <figure>
                                <img src="<?= !empty($p['featured_image']) ? base_url('/uploads/bulletin/'.$p['featured_image']) : 'https://via.placeholder.com/800x600?text=Post' ?>" alt="<?= esc($p['title']) ?>">
                                <div class="chips">
                                    <?php if(!empty($p['category_name'])): ?><span class="chip" style="background:<?= esc($p['category_color'] ?? '#f1f5f9') ?>;color:#111827;"><?= esc(strtoupper($p['category_name'])) ?></span><?php endif; ?>
                                    <?php if(!empty($p['is_featured'])): ?><span class="chip yellow">FEATURED</span><?php endif; ?>
                                    <?php if(!empty($p['is_urgent'])): ?><span class="chip red">URGENT</span><?php endif; ?>
                                </div>
                            </figure>
                            <div class="body">
                                <h4><?= esc($p['title']) ?></h4>
                                <p><?= esc($p['excerpt'] ?: substr(strip_tags($p['content'] ?? ''),0,110).'...') ?></p>
                                <div class="meta">
                                    <span><i class="fa-regular fa-calendar"></i> <?= !empty($p['published_at']) ? date('M j, Y', strtotime($p['published_at'])) : '' ?></span>
                                    <span><i class="fa-regular fa-eye"></i> <?= number_format($p['view_count'] ?? 0) ?></span>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- EVENTS -->
            <section class="events" id="events">
                <h2 class="section-title">Upcoming Youth Events</h2>
                <div class="events-grid">
                    <?php foreach (($events ?? []) as $e): ?>
                        <div class="event-card">
                            <div class="event-banner">
                                <img src="<?= !empty($e['event_banner']) ? base_url('uploads/event/'.$e['event_banner']) : 'https://via.placeholder.com/600x400?text=Event' ?>" alt="<?= esc($e['title']) ?>">
                                <div class="event-date"><?= date('M j', strtotime($e['event_date'] ?? $e['created_at'])) ?></div>
                            </div>
                            <div class="event-body">
                                <h5><?= esc($e['title']) ?></h5>
                                <span><?= !empty($e['event_date']) ? date('M j, Y g:i A', strtotime($e['event_date'])) : '' ?></span>
                                <span class="more-link">Discover <i class="fa-solid fa-arrow-right"></i></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
        <aside class="desktop-side">
            <div class="sidebar-block">
                <h3>Featured Highlight</h3>
                <?php if($heroPost): ?>
                    <div class="mini-post" style="padding:0;border:0;flex-direction:column;align-items:stretch;">
                        <img src="<?= !empty($heroPost['featured_image']) ? base_url('/uploads/bulletin/'.$heroPost['featured_image']) : 'https://via.placeholder.com/600x400?text=Highlight' ?>" alt="Highlight" style="height:180px;width:100%;object-fit:cover;">
                        <h4 style="font-size:.8rem;margin-top:.8rem;"><?= esc($heroPost['title']) ?></h4>
                        <span><?= !empty($heroPost['published_at']) ? date('M j, Y', strtotime($heroPost['published_at'])) : '' ?></span>
                    </div>
                <?php else: ?>
                    <p style="font-size:.7rem;color:var(--muted);">No feature available yet.</p>
                <?php endif; ?>
            </div>
            <div class="sidebar-block">
                <h3>Recent Posts</h3>
                <?php foreach (array_slice($posts ?? [],1,4) as $mp): ?>
                    <div class="mini-post">
                        <img src="<?= !empty($mp['featured_image']) ? base_url('/uploads/bulletin/'.$mp['featured_image']) : 'https://via.placeholder.com/120x90?text=Post' ?>" alt="<?= esc($mp['title']) ?>">
                        <div style="display:flex;flex-direction:column;gap:.25rem;">
                            <h4><?= esc($mp['title']) ?></h4>
                            <span><?= !empty($mp['published_at']) ? date('M j', strtotime($mp['published_at'])) : '' ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="sidebar-block">
                <h3>Quick Access</h3>
                <a href="#posts" class="btn" style="width:100%;justify-content:center;">View Updates</a>
                <a href="#events" class="btn" style="width:100%;justify-content:center;">Events</a>
                <a href="#resources" class="btn" style="width:100%;justify-content:center;">Resources</a>
            </div>
        </aside>
    </main>
    <footer>
        <div class="fwrap">
            <div>
                <h4>Platform</h4>
                <a href="#top">Overview</a>
                <a href="#services">Services</a>
                <a href="#posts">Updates</a>
            </div>
            <div>
                <h4>Engagement</h4>
                <a href="#events">Events</a>
                <a href="#resources">Resources</a>
            </div>
            <div>
                <h4>Legal</h4>
                <a>Privacy</a>
                <a>Terms</a>
            </div>
            <div>
                <h4>Access</h4>
                <a href="<?= base_url('login') ?>">Sign In</a>
            </div>
        </div>
        <div class="copyright">&copy; <?= date('Y') ?> K-NECT Platform. All rights reserved.</div>
    </footer>
</body>
</html>
