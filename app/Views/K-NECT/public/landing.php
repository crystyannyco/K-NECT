<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>K-Nect - Youth Governance System</title>
    <meta name="description" content="<?= esc($pageDescription ?? 'Unified youth engagement platform for announcements, events, resources, and data-driven community impact.') ?>" />
    <meta name="robots" content="index,follow" />
    <link rel="canonical" href="<?= esc($canonicalUrl ?? current_url()) ?>" />
    <meta property="og:title" content="K-Nect - Youth Governance System" />
    <meta property="og:description" content="<?= esc($pageDescription ?? 'Unified youth engagement platform for announcements, events, resources, and data-driven community impact.') ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="<?= base_url('assets/images/K-Nect-Logo.png') ?>" />
    <meta property="og:url" content="<?= esc($canonicalUrl ?? current_url()) ?>" />
    <meta property="og:site_name" content="K-Nect" />
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="K-Nect - Youth Governance System" />
    <meta name="twitter:description" content="<?= esc($pageDescription ?? 'Unified youth engagement platform for announcements, events, resources, and data-driven community impact.') ?>" />
    <meta name="twitter:image" content="<?= base_url('assets/images/K-Nect-Logo.png') ?>" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Domine:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        :root {
            --ink:#0f172a; /* slate-900 */
            --sub:#334155; /* slate-700 */
            --muted:#64748b; /* slate-500 */
            --border:#e2e8f0; /* slate-200 */
            --bg:#f8fafc; /* slate-50 */
            --panel:#ffffff;
            /* Primary blue palette */
            --primary-50:#eff6ff;
            --primary-100:#dbeafe;
            --primary-200:#bfdbfe;
            --primary-300:#93c5fd;
            --primary-400:#60a5fa;
            --primary:#2563eb; /* 600 */
            --primary-700:#1d4ed8;
            --primary-800:#1e40af;
            --accent:#38bdf8; /* sky-400 for subtle accents */
            --radius:18px;
        }
        *{box-sizing:border-box}
        body{margin:0;font-family:'Inter',system-ui,sans-serif;background:var(--bg);color:var(--ink)}
        a{text-decoration:none;color:inherit}
        img{max-width:100%;display:block}
        .container{max-width:1250px;margin:0 auto;padding:0 1.25rem}
        .sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}
        /* Header */
        header{position:sticky;top:0;background:rgba(255,255,255,.92);backdrop-filter:blur(10px);border-bottom:1px solid var(--border);z-index:60}
    .nav{display:flex;align-items:center;gap:1rem;height:78px}
    .brand img{height:25px;width:auto}
        .nav-links{display:flex;gap:1rem;margin-left:auto;font-size:.9rem;font-weight:600}
        .nav-links a{position:relative;padding:.25rem 0;color:var(--ink)}
        .nav-links a:after{content:"";position:absolute;left:0;bottom:-3px;height:2px;width:0;background:var(--primary);transition:.3s;border-radius:2px}
        .nav-links a:hover:after{width:100%}
    .btn{display:inline-flex;align-items:center;gap:.55rem;font-size:.85rem;font-weight:700;padding:.6rem 1rem;border:1px solid var(--primary);color:var(--primary);border-radius:40px;background:#fff;transition:.25s;box-shadow:0 6px 16px -10px rgba(37,99,235,.35)}
        .btn:hover{background:var(--primary);color:#fff;border-color:var(--primary)}
        .btn-primary{background:var(--primary);color:#fff;border-color:var(--primary)}
        .btn-primary:hover{background:var(--primary-700)}
    .btn-ghost{border-color:transparent;color:var(--primary);background:transparent}
    .btn-ghost:hover{background:var(--primary-50)}
        /* Hero Section */
        .hero{padding:2rem 0 1.5rem}
        .hero-grid{display:grid;grid-template-columns:2fr 3fr;gap:2rem;align-items:stretch}
    .hero-copy{background:linear-gradient(180deg,#ffffff, #f8fbff);border:1px solid var(--border);border-radius:var(--radius);padding:2.4rem;display:flex;flex-direction:column;justify-content:center;box-shadow:0 10px 30px -18px rgba(2,6,23,.25)}
        .eyebrow{display:inline-flex;gap:.5rem;align-items:center;font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:.6px;color:var(--primary)}
    .eyebrow .dot{width:7px;height:7px;background:linear-gradient(135deg,var(--primary-300),var(--primary));border-radius:50%;box-shadow:0 0 0 3px var(--primary-100)}
        .hero-copy h1{font-family:'Domine',serif;font-size:clamp(2.1rem,3.5vw,3rem);line-height:1.05;margin:.6rem 0 1rem}
        .hero-copy p{margin:0 0 1.4rem;color:var(--sub);font-size:1rem;line-height:1.6}
        .hero-actions{display:flex;flex-wrap:wrap;gap:.75rem}
        .hero-media{display:grid;grid-template-columns:1fr 1fr;grid-template-rows:1fr 1fr;gap:.8rem;max-height:480px;height:480px}
    .hero-card{position:relative;overflow:hidden;border-radius:16px;border:1px solid var(--border);background:#fff}
        .hero-card.tall{grid-row:span 2}
    .hero-card .ph{display:flex;align-items:center;justify-content:center;background:linear-gradient(180deg,var(--primary-50),#ffffff);height:100%}
    .hero-card img{width:100%;height:100%;object-fit:cover;transition:transform .5s ease}
    .hero-card:hover img{transform:scale(1.02)}
    .hero-tag{position:absolute;left:10px;top:10px;background:#fff;border-radius:999px;padding:.35rem .6rem;font-size:.62rem;font-weight:800;letter-spacing:.5px}
        /* Sections */
        section{margin:0 0 2rem 0}
        .section-head{display:flex;align-items:end;justify-content:space-between;margin-bottom:1rem}
        .section-title{font-family:'Domine',serif;font-size:1.6rem;margin:0}
        .muted{color:var(--muted);font-size:.9rem}
    /* Centered Top Performing Barangays heading */
    #highlights .tp-head-centered{flex-direction:column;align-items:center;justify-content:center;gap:.55rem;margin-bottom:1.4rem;text-align:center}
    #highlights .tp-head-centered .section-title{font-size:clamp(1.6rem,2.4vw,2.2rem);line-height:1.05}
    #highlights .tp-controls-row{display:flex;align-items:center;flex-wrap:wrap;gap:.6rem;justify-content:center;font-size:.75rem;color:var(--muted)}
    #highlights .tp-controls-row .tp-range{display:inline-flex;gap:.35rem}
    #highlights .tp-status{font-size:.65rem;color:var(--muted);min-height:1em;text-align:center}
    /* Top Programs (Features) */
    #programs{background:linear-gradient(180deg,var(--primary-50),var(--primary-100));padding:2.4rem 0;border-top:0;border-bottom:0;overflow:hidden}
    #programs .programs-grid{display:grid;grid-template-columns:1.1fr 1.9fr;gap:1.5rem;align-items:stretch}
    #programs .programs-copy{display:flex;flex-direction:column;justify-content:center;padding:1rem 1rem 1rem 0}
    #programs .programs-copy .section-title{font-size:clamp(1.8rem,3.5vw,2.6rem);letter-spacing:.3px;color:#0b1220;margin:0 0 .6rem}
    #programs .programs-copy .lead{color:#475569;max-width:48ch}
    /* Logo blue flame animation */
    #programs .logo-flame{position:relative;display:inline-block}
    #programs .logo-flame img{position:relative;z-index:2;filter:drop-shadow(0 8px 22px rgba(37,99,235,.28))}
    #programs .logo-flame::before{content:"";position:absolute;inset:-18% -22%;z-index:1;border-radius:50% 50% 40% 40%/60% 60% 40% 40%;
        background:
          radial-gradient(60% 70% at 50% 100%, rgba(59,130,246,.55), transparent 60%),
          radial-gradient(40% 50% at 20% 60%, rgba(96,165,250,.55), transparent 65%),
          radial-gradient(40% 50% at 80% 60%, rgba(59,130,246,.45), transparent 65%),
          radial-gradient(30% 40% at 50% 30%, rgba(147,197,253,.45), transparent 60%);
        filter:blur(16px) saturate(130%);
        animation:flameWobble 3.5s ease-in-out infinite alternate}
    #programs .logo-flame::after{content:"";position:absolute;inset:-10% -12%;z-index:0;
        background:radial-gradient(closest-side, rgba(59,130,246,.25), transparent 70%);
        filter:blur(18px);
        animation:glowPulse 2.4s ease-in-out infinite}
    @keyframes flameWobble{0%{transform:translateY(2px) scale(1) rotate(0deg);opacity:.9}50%{transform:translateY(-4px) scale(1.03) rotate(-1deg);opacity:1}100%{transform:translateY(-6px) scale(1.05) rotate(1deg);opacity:.95}}
    @keyframes glowPulse{0%,100%{opacity:.7}50%{opacity:1}}
    /* Rising blue embers */
    #programs .logo-flame .flame-sparks{position:absolute;inset:-6% -8% -14% -8%;z-index:1;pointer-events:none;mix-blend-mode:screen}
    /* Layer 1 */
    #programs .logo-flame .flame-sparks.s1{
        background:
          radial-gradient(circle 7px at center, rgba(96,165,250,.55) 0 70%, transparent 72%) no-repeat,
          radial-gradient(circle 6px at center, rgba(59,130,246,.55) 0 70%, transparent 72%) no-repeat,
          radial-gradient(circle 5px at center, rgba(147,197,253,.55) 0 70%, transparent 72%) no-repeat;
        background-size: 14px 18px, 12px 16px, 10px 14px;
        background-position: 22% 100%, 58% 105%, 78% 100%;
        animation:sparksRise1 3.2s linear infinite;
        filter:blur(.3px)
    }
    /* Layer 2 with different timing/positions */
    #programs .logo-flame .flame-sparks.s2{
        background:
          radial-gradient(circle 6px at center, rgba(56,189,248,.45) 0 70%, transparent 72%) no-repeat,
          radial-gradient(circle 5px at center, rgba(59,130,246,.45) 0 70%, transparent 72%) no-repeat;
        background-size: 12px 16px, 10px 14px;
        background-position: 35% 102%, 70% 106%;
        animation:sparksRise2 2.8s linear infinite .6s;
        filter:blur(.4px)
    }
    @keyframes sparksRise1{
        0%   {background-position:22% 100%, 58% 105%, 78% 100%; opacity:.8}
        50%  {opacity:1}
        100% {background-position:24% 6%, 56% 0%, 76% 8%; opacity:.7}
    }
    @keyframes sparksRise2{
        0%   {background-position:35% 102%, 70% 106%; opacity:.7}
        50%  {opacity:1}
        100% {background-position:33% 4%, 68% 2%; opacity:.65}
    }
    /* Programs carousel */
    #programs .carousel{position:relative;padding:.1rem 0}
    #programs .car-viewport{position:relative;overflow:hidden;border-radius:18px}
    #programs .car-track{--gap:.8rem;display:flex;gap:var(--gap);transition:transform .45s ease;will-change:transform}
    #programs .car-slide{flex:0 0 100%;min-width:100%}
    #programs .car-card{position:relative;background:linear-gradient(180deg,#ffffff 0%, #f9fbff 100%);border:1px solid var(--border);border-radius:18px;min-height:280px;padding:1.4rem 1.4rem 1.6rem;box-shadow:0 12px 30px -20px rgba(2,6,23,.25);transition:transform .2s ease, box-shadow .2s ease, border-color .2s ease;width:100%;box-sizing:border-box}
    #programs .car-card:hover{transform:translateY(-3px);box-shadow:0 18px 40px -24px rgba(2,6,23,.28);border-color:var(--primary-100)}
    #programs .car-card::before{content:attr(data-num);position:absolute;top:1rem;left:1.1rem;font-family:'Domine',serif;font-size:clamp(4rem,8.5vw,6rem);font-weight:800;line-height:1;color:var(--primary-100);z-index:0}
    #programs .car-card h3{position:relative;z-index:1;margin:0 0 .35rem;font-size:1.05rem}
    #programs .car-card p{position:relative;z-index:1;margin:0 0 .65rem;color:#475569}
    #programs .car-divider{position:relative;height:1px;background:var(--border);margin:.6rem 0 .8rem;z-index:1}
    #programs .car-chip{position:absolute;top:.9rem;right:.9rem;z-index:1;background:rgba(255,255,255,.75);backdrop-filter:blur(4px);border:1px solid rgba(15,23,42,.12);color:#0f172a;border-radius:999px;padding:.18rem .55rem;font-size:.68rem;font-weight:700;letter-spacing:.2px}
    #programs .car-link{position:relative;z-index:1;color:var(--primary);font-weight:700;font-size:.9rem}
    #programs .car-link:hover{text-decoration:underline}
    #programs .car-btn[disabled]{opacity:.5;cursor:not-allowed}
    #programs .car-btn{position:absolute;top:50%;transform:translateY(-50%);width:36px;height:36px;border-radius:999px;border:1px solid rgba(15,23,42,.12);background:rgba(255,255,255,.95);backdrop-filter:blur(4px);color:var(--primary-700);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px -6px rgba(2,6,23,.25);cursor:pointer;z-index:5;transition:background .2s ease,color .2s ease,border-color .2s ease,opacity .2s ease}
    #programs .car-btn:hover{background:#ffffff;color:var(--primary);border-color:var(--primary-200)}
    #programs .car-btn:focus-visible{outline:2px solid var(--primary-300);outline-offset:2px}
    #programs .car-btn i{font-size:14px;line-height:1}
    #programs .car-btn.prev{left:12px}
    #programs .car-btn.next{right:12px}
    /* Desktop: wider spacing */
    @media(min-width:1024px){
        #programs .programs-grid{gap:2rem}
        #programs .car-btn{width:40px;height:40px}
        #programs .car-btn.prev{left:16px}
        #programs .car-btn.next{right:16px}
    }
    /* Mobile & tablet adjustments */
    @media(max-width:1023px){
        /* make the programs grid a single, fluid column */
        #programs .programs-grid{grid-template-columns:1fr;gap:1rem}
        /* hide the left/logo column on small screens */
        #programs .programs-copy{padding:0 0 1rem 0;display:none}
        /* Keep arrows inside, visible and compact */
        #programs .car-btn{width:32px;height:32px;background:rgba(255,255,255,.95)}
        #programs .car-btn.prev{left:8px}
        #programs .car-btn.next{right:8px}
    }
    /* Mobile: adjust card sizing */
    @media(max-width:639px){
        #programs .car-card{min-height:200px;padding:1rem}
    }
    @media(max-width:520px){
        #programs .logo-flame img{max-width:180px}
        #programs .car-card{min-height:180px;padding:.9rem}
    }
    /* Respect reduced motion */
    @media (prefers-reduced-motion: reduce){
        #programs .car-card{transition:none}
        #programs .logo-flame::before,
        #programs .logo-flame::after,
        #programs .logo-flame .flame-sparks.s1,
        #programs .logo-flame .flame-sparks.s2{animation:none}
    }
    /* Latest Stories - horizontal carousel */
    #stories .news-carousel{position:relative;padding:.1rem 0}
    #stories .news-viewport{position:relative;overflow:hidden;border-radius:14px}
    #stories .news-track{--gap:.8rem;display:flex;gap:var(--gap);transition:transform .45s ease;will-change:transform}
    #stories .news-slide{flex:0 0 100%;min-width:100%}
    #stories .news-card{display:block;position:relative;border:1px solid var(--border);border-radius:14px;overflow:hidden;background:#f8fafc;box-shadow:0 8px 24px -22px rgba(2,6,23,.2);transition:transform .2s ease, box-shadow .2s ease}
    #stories .news-card::after{content:"";position:absolute;inset:0;border-radius:inherit;padding:1px;background:linear-gradient(180deg,rgba(59,130,246,.15),rgba(59,130,246,0));-webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);-webkit-mask-composite:x;mask-composite:exclude;pointer-events:none}
    #stories .news-card:hover{transform:translateY(-1px);box-shadow:0 14px 30px -24px rgba(2,6,23,.22)}
    #stories .news-media{position:relative;aspect-ratio:1/1;background:#e5e7eb}
    #stories .news-media img{width:100%;height:100%;object-fit:cover;display:block}
    #stories .news-info{position:absolute;left:0;right:0;bottom:0;padding:.4rem .5rem;color:#fff;background:linear-gradient(to top, rgba(2,6,23,.6), rgba(2,6,23,0));font-size:.8rem;font-weight:700;letter-spacing:.2px;text-shadow:0 1px 2px rgba(0,0,0,.35);display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden;white-space:normal}
    #stories .news-chip{position:absolute;top:.4rem;left:.4rem;z-index:2;background:rgba(255,255,255,.8);backdrop-filter:blur(4px);border:1px solid rgba(15,23,42,.12);color:#0f172a;border-radius:999px;padding:.15rem .45rem;font-size:.65rem;font-weight:800;letter-spacing:.2px}
    /* arrows - attached to carousel */
    #stories .news-btn{position:absolute;top:50%;transform:translateY(-50%);width:36px;height:36px;border-radius:999px;border:1px solid rgba(15,23,42,.12);background:rgba(255,255,255,.95);backdrop-filter:blur(4px);color:var(--primary-700);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px -6px rgba(2,6,23,.25);cursor:pointer;z-index:5;transition:background .2s ease,color .2s ease,border-color .2s ease,opacity .2s ease}
    #stories .news-btn:hover{background:#ffffff;color:var(--primary);border-color:var(--primary-200)}
    #stories .news-btn:focus-visible{outline:2px solid var(--primary-300);outline-offset:2px}
    #stories .news-btn.prev{left:12px}
    #stories .news-btn.next{right:12px}
    #stories .news-btn[disabled]{opacity:.5;cursor:not-allowed}
    /* pagination dots */
    #stories .news-dots{display:flex;gap:.4rem;justify-content:center;margin:.5rem 0 0}
    #stories .news-dot{width:7px;height:7px;border-radius:999px;border:1px solid rgba(15,23,42,.2);background:#ffffff;opacity:.7;cursor:pointer}
    #stories .news-dot[aria-selected="true"]{background:var(--primary);border-color:var(--primary);opacity:1}
    /* responsive adjustments */
    @media (min-width:1024px){
        #stories .news-btn{width:40px;height:40px}
        #stories .news-btn.prev{left:16px}
        #stories .news-btn.next{right:16px}
    }
    @media (max-width:1023px){
        #stories .news-btn{width:32px;height:32px;background:#ffffff;border:1px solid var(--primary-200)}
        #stories .news-btn.prev{left:8px}
        #stories .news-btn.next{right:8px}
    }
    @media (max-width:639px){
        #stories .news-card{border-radius:12px}
    }
    @media (max-width:520px){
        #stories .news-btn{width:28px;height:28px;background:#ffffff;border:1px solid var(--primary-200)}
        #stories .news-btn.prev{left:4px}
        #stories .news-btn.next{right:4px}
    }
    @media (max-width:400px){
        #stories .news-btn{width:26px;height:26px;background:#ffffff;border:1px solid var(--primary-200)}
        #stories .news-btn.prev{left:2px}
        #stories .news-btn.next{right:2px}
    }
    @media (max-width:360px){
        #stories .news-btn{width:24px;height:24px;background:#ffffff;border:1px solid var(--primary-200)}
        #stories .news-btn.prev{left:1px}
        #stories .news-btn.next{right:1px}
    }
    /* skeleton placeholders */
    .skeleton{background:linear-gradient(90deg,#e5e7eb,#f1f5f9 30%,#e5e7eb 60%);background-size:200% 100%;animation:shimmer 1.2s linear infinite}
    @keyframes shimmer{0%{background-position:200% 0}100%{background-position:-200% 0}}
        .story-meta{color:var(--muted);font-size:.75rem}
        /* Top Performing Barangays - White/Blue Podium Redesign */
        #top-barangays{position:relative}
        #top-barangays .score-info{position:relative;display:inline-flex;align-items:center;gap:.35rem;font-size:.7rem;font-weight:600;padding:.35rem .55rem;border-radius:999px;background:#ecf4ff;color:#1d4ed8;border:1px solid #d0e3ff;cursor:help}
        #top-barangays .score-info i{font-size:.75rem;color:#1e40af}
        #top-barangays .score-info .tip{position:absolute;left:50%;bottom:110%;transform:translateX(-50%) scale(.85);transform-origin:bottom center;min-width:210px;background:#0f172a;color:#f1f5f9;font-size:.65rem;line-height:1.4;padding:.55rem .65rem;border-radius:10px;border:1px solid #1e293b;opacity:0;pointer-events:none;transition:.25s ease;box-shadow:0 10px 30px -12px rgba(0,0,0,.45);z-index:30}
        #top-barangays .score-info .tip:after{content:"";position:absolute;left:50%;top:100%;transform:translateX(-50%);width:12px;height:8px;background:conic-gradient(from 45deg at 50% 0,#0f172a 0 25%,#0f172a 25% 75%,#0f172a 75% 100%);clip-path:polygon(50% 100%,0 0,100% 0)}
        #top-barangays .score-info:hover .tip,#top-barangays .score-info:focus .tip{opacity:1;transform:translateX(-50%) scale(1)}
        .tp-wrap{display:block}
        .tp-podium{display:flex;gap:1rem;align-items:flex-end;justify-content:center;padding:1.6rem 1rem 1.55rem;border:1px solid #1e3a8a;border-radius:34px;background:linear-gradient(145deg,#1d4ed8,#1e40af);box-shadow:0 18px 42px -20px rgba(15,46,105,.4),0 4px 10px -4px rgba(15,46,105,.4);position:relative;width:100%;color:#e2ecff}
        .podium-spot.rank-1{order:-1}
        .tp-content{display:flex;flex-direction:column;gap:2.2rem}
    .tp-podium.compact{grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1.4rem;padding:1.5rem 2rem 1.45rem}
    .tp-podium.has-placeholders .podium-spot.placeholder .podium-avatar{background:repeating-conic-gradient(from 0deg,#f1f5f9 0 15deg,#e7eef7 15deg 30deg);opacity:.85}
    .tp-podium.has-placeholders .podium-spot.placeholder{filter:grayscale(.15);opacity:.85}
    .tp-podium.has-placeholders:after{content:"Partial rankings – more data coming soon";position:absolute;bottom:-1.1rem;left:50%;transform:translate(-50%,100%);font:600 .6rem/1 'Inter',sans-serif;letter-spacing:.7px;text-transform:uppercase;color:#64748b;background:#ffffffd9;padding:.35rem .6rem;border:1px solid var(--border);border-radius:14px;backdrop-filter:blur(6px);box-shadow:0 8px 22px -12px rgba(15,23,42,.25)}
    /* Extra breathing room below placeholder pill before next section */
    .tp-podium.has-placeholders{margin-bottom:3.2rem}
    /* Events heading: center title and muted subtitle */
    #events .section-head{position:relative;text-align:center;flex-direction:column;gap:.35rem;margin-bottom:1.1rem;padding-top:.2rem;display:flex;align-items:center;justify-content:center}
        .podium-spot{position:relative;display:flex;flex-direction:column;align-items:center;gap:.55rem;min-width:0}
        .podium-spot.rank-1{transform:translateY(-14px)}
    .podium-avatar{--size:110px;width:var(--size);height:var(--size);border-radius:50%;position:relative;display:grid;place-items:center;background:linear-gradient(135deg,#1e40af,#2563eb);box-shadow:0 10px 26px -12px rgba(0,0,0,.45),0 0 0 1px #1e3a8a,0 0 0 5px #1d4ed8 inset;overflow:hidden;transition:.4s cubic-bezier(.4,.65,.25,1)}
    .podium-avatar:before{content:"";position:absolute;inset:0;border-radius:inherit;padding:3px;background:linear-gradient(140deg,#93c5fd,#fff,#60a5fa);-webkit-mask:linear-gradient(#000 0 0) content-box,linear-gradient(#000 0 0);-webkit-mask-composite:xor;mask-composite:exclude;opacity:.95}
    .podium-spot.rank-1 .podium-avatar{box-shadow:0 18px 40px -16px rgba(0,0,0,.55),0 0 0 1px #2563eb,0 0 0 6px #1e40af inset}
    .podium-spot.placeholder .podium-avatar{box-shadow:0 6px 14px -8px rgba(2,6,23,.15),0 0 0 1px #e2e8f0}
    .podium-avatar img{width:74%;height:74%;object-fit:cover;border-radius:50%;background:#fff;filter:drop-shadow(0 2px 5px rgba(0,0,0,.45))}
        .podium-avatar .initials{font:700 2.1rem/1 'Inter',sans-serif;color:#ffffff}
        .podium-crown{position:absolute;top:-18px;left:50%;transform:translateX(-50%);font-size:2rem;filter:drop-shadow(0 4px 6px rgba(0,0,0,.25));animation:crownIn .6s cubic-bezier(.45,.9,.3,1) .15s both}
        @keyframes crownIn{0%{transform:translate(-50%,10px) scale(.6);opacity:0}60%{transform:translate(-50%, -2px) scale(1.08);opacity:1}100%{transform:translate(-50%,0) scale(1)}}
        .podium-label{font:600 .7rem/1 'Inter',sans-serif;letter-spacing:.5px;text-transform:uppercase;color:#dbeafe;}
        .podium-name{margin:0;font:700 1.05rem/1.15 'Inter',sans-serif;color:#ffffff;text-align:center;max-width:12ch;text-shadow:0 2px 6px rgba(0,0,0,.4)}
        .podium-score{font:700 1.25rem/1 'Inter',sans-serif;color:#ffffff;display:flex;align-items:center;gap:.35rem;text-shadow:0 2px 6px rgba(0,0,0,.35)}
        .podium-score small{font:600 .65rem/1.1 'Inter',sans-serif;color:#dbeafe}
        .podium-rank-badge{position:absolute;bottom:-6px;left:50%;transform:translateX(-50%);background:#ffffff;color:#1d4ed8;font:600 .65rem/1 'Inter',sans-serif;padding:.3rem .6rem;border-radius:999px;box-shadow:0 4px 12px -4px rgba(0,0,0,.35)}
        .podium-spot.rank-2 .podium-avatar{--size:96px}
        .podium-spot.rank-3 .podium-avatar{--size:92px}
        .podium-spot.rank-2{transform:translateY(-4px)}
        .podium-spot.rank-3{transform:translateY(-2px)}
    .podium-spot.placeholder .podium-avatar{background:linear-gradient(135deg,#f1f5f9,#e2e8f0);position:relative}
    .podium-spot.placeholder .podium-avatar:after{content:"";position:absolute;inset:4px;border:2px dashed #cbd5e1;border-radius:50%;mix-blend-mode:multiply}
    .podium-spot.placeholder .initials{font:600 1.2rem/1 'Inter',sans-serif;color:#94a3b8}
    .podium-score[data-score]{position:relative}
    .podium-score[data-score]::after{content:attr(data-score);position:absolute;left:-9999px;top:-9999px} /* accessibility no-op sizing trick */
        @media(max-width:720px){
            /* allow podium spots to wrap and flex; provide breathing room */
            .tp-podium{
                flex-wrap:wrap;
                justify-content:center;
                gap:1.2rem;
                padding:1.2rem .9rem 1rem;
            }

            /* each spot can grow/shrink — keeps layout balanced across widths */
            .podium-spot{
                flex:1 1 140px; /* grow, shrink, base */
                min-width:120px;
                max-width:260px;
                display:flex;
                flex-direction:column;
                align-items:center;
            }

            /* champion spans full width on narrow screens and is visually elevated */
            .podium-spot.rank-1{
                flex-basis:100%;
                max-width:640px;
                order:-1;
                transform:translateY(0);
                margin-bottom:0;
            }

            /* runners-up sit side-by-side under the champion */
            .podium-spot.rank-2,.podium-spot.rank-3{transform:translateY(0)}

            /* avatar sizing tuned for mobile */
            .podium-avatar{--size:84px}
            .podium-spot.rank-1 .podium-avatar{--size:110px}
        }

        /* Desktop / wider screens: use a grid so the champion is centered above the two runners-up */
        @media(min-width:721px){
            .tp-podium{
                display:grid;
                grid-template-columns:1fr 1.2fr 1fr;
                grid-template-rows:auto 1fr;
                gap:1.4rem;
                align-items:end;
                justify-items:center;
                padding:1.6rem 1rem 1.55rem;
            }
            .podium-spot.rank-1{grid-column:2;grid-row:1;justify-self:center;transform:translateY(-20px)}
            .podium-spot.rank-2{grid-column:1;grid-row:2;justify-self:center;transform:translateY(-6px)}
            .podium-spot.rank-3{grid-column:3;grid-row:2;justify-self:center;transform:translateY(-4px)}
            .podium-spot{min-width:0}
        }
        /* Remaining list */
        .tp-remaining{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1rem}
        .tp-card{background:#fff;border:1px solid var(--border);border-radius:22px;padding:.9rem .95rem 1rem;display:flex;flex-direction:column;gap:.55rem;box-shadow:0 10px 26px -14px rgba(15,46,105,.22),0 2px 6px -2px rgba(15,46,105,.15);position:relative;overflow:hidden;transition:.35s cubic-bezier(.4,.65,.25,1)}
        .tp-card:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 85% 20%,rgba(29,78,216,.08),transparent 60%);opacity:0;transition:.5s}
        .tp-card:hover{transform:translateY(-4px);box-shadow:0 18px 34px -16px rgba(15,46,105,.28),0 4px 10px -4px rgba(15,46,105,.18)}
        .tp-card:hover:before{opacity:1}
        .tp-card h4{margin:0;font:600 .95rem/1.2 'Inter',sans-serif;color:#102341;display:flex;align-items:center;gap:.4rem}
        .tp-rank-pill{background:#1d4ed8;color:#fff;font:600 .55rem/1 'Inter',sans-serif;padding:.25rem .55rem;border-radius:999px;letter-spacing:.5px}
        .tp-progress{height:6px;background:#e2e8f0;border-radius:6px;overflow:hidden;position:relative;margin-top:.2rem}
        .tp-progress span{display:block;height:100%;width:0;background:linear-gradient(90deg,#1d4ed8,#2563eb);transition:width 1s cubic-bezier(.65,.05,.36,1)}
        .tp-metrics{display:flex;gap:.9rem;font:500 .65rem/1 'Inter',sans-serif;color:#475569;flex-wrap:wrap;margin-top:.35rem}
        .tp-metrics span{display:inline-flex;align-items:center;gap:.3rem}
        .tp-metrics i{color:#1d4ed8;font-size:.65rem}
        /* Full table retains existing styling below */
        /* Enhanced controls & skeletons */
        .tp-range{display:inline-flex;background:#ffffff;border:1px solid var(--border);padding:.2rem;border-radius:999px;box-shadow:0 2px 4px -2px rgba(0,0,0,.05),0 4px 12px -6px rgba(0,0,0,.06)}
        .tp-range-btn{background:transparent;border:0;font:inherit;padding:.35rem .85rem;border-radius:999px;cursor:pointer;color:var(--muted);font-size:.65rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase;transition:.18s color,.18s background,.18s box-shadow;background:transparent}
        .tp-range-btn:hover{color:var(--ink)}
        .tp-range-btn:focus{outline:none;box-shadow:0 0 0 3px rgba(59,130,246,.18),0 6px 12px -8px rgba(0,0,0,.12);border-radius:999px}
        .tp-range-btn:focus-visible{box-shadow:0 0 0 3px rgba(59,130,246,.28);}
    .tp-range-btn.active{background:linear-gradient(135deg,var(--primary-600),var(--primary));box-shadow:0 6px 14px -6px rgba(37,99,235,.55),0 0 0 1px rgba(255,255,255,.12) inset;}
        .tp-expand{background:linear-gradient(135deg,#f1f5f9,#ffffff);border:1px solid var(--border);color:var(--primary-700);padding:.45rem .9rem;border-radius:999px;font-size:.65rem;font-weight:700;letter-spacing:.5px;cursor:pointer;display:inline-flex;align-items:center;gap:.4rem;transition:.25s}
        .tp-expand:hover{background:#ffffff;box-shadow:0 4px 14px -8px rgba(0,0,0,.15)}
        @keyframes tpShimmer{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
    .tp-item{position:relative;}
    .tp-rank-badge{display:inline-flex;align-items:center;gap:.25rem;margin-left:.45rem;font-size:.55rem;padding:.28rem .55rem;border-radius:8px;background:linear-gradient(135deg,#fbbf24,#f59e0b);color:#1e293b;letter-spacing:.5px;text-transform:uppercase;font-weight:800;box-shadow:0 3px 10px -4px rgba(245,158,11,.6),0 0 0 1px rgba(255,255,255,.4) inset}
    .tp-rank-badge:before{content:"🥇";font-size:.8rem}
    .tp-rank-badge.r2{background:linear-gradient(135deg,#cbd5e1,#94a3b8);color:#0f172a;box-shadow:0 3px 10px -4px rgba(100,116,139,.5),0 0 0 1px rgba(255,255,255,.35) inset}
    .tp-rank-badge.r2:before{content:"🥈"}
    .tp-rank-badge.r3{background:linear-gradient(135deg,#f59e0b,#d97706);color:#1e293b;box-shadow:0 3px 10px -4px rgba(217,119,6,.55),0 0 0 1px rgba(255,255,255,.35) inset}
    .tp-rank-badge.r3:before{content:"🥉"}
        .tp-full{margin-top:1.25rem;border:1px solid var(--border);border-radius:24px;padding:1rem 1.15rem;background:#fff;box-shadow:0 10px 30px -18px rgba(2,6,23,.18)}
        .tp-table{width:100%;display:block;overflow-x:auto;font-size:.75rem}
        .tp-full-head{display:grid;grid-template-columns:60px 1fr 80px 120px 80px 160px 80px;font-weight:600;font-size:.65rem;text-transform:uppercase;letter-spacing:.55px;color:var(--muted);padding:.25rem 0;border-bottom:1px dashed var(--border)}
        .tp-full-row{display:grid;grid-template-columns:60px 1fr 80px 120px 80px 160px 80px;align-items:center;gap:.5rem;padding:.55rem 0;border-bottom:1px dotted var(--border);}
        .tp-full-row:last-child{border-bottom:0}
        .tp-full-row .c-rank{font-weight:700;font-size:.9rem}
        .tp-full-row .c-name{font-weight:600}
        .tp-full-row .c-bar .tp-bar{height:8px}
        .mini-bar{height:8px;border-radius:6px !important;background:var(--track,#e2e8f0)}
        .mini-bar span{border-radius:inherit}
        .tp-desc{margin:0 0 .9rem;font-size:.9rem;max-width:50ch;line-height:1.5;opacity:.9}
        @media (max-width:960px){
            .tp-full-head,.tp-full-row{grid-template-columns:50px 1fr 60px 90px 60px 120px 70px}
        }
    .tp-list{background:linear-gradient(145deg,#ffffff,#f8fafc);border:1px solid var(--border);border-radius:28px;padding:1.15rem 1.3rem 1.4rem;display:flex;flex-direction:column;gap:1rem;box-shadow:0 22px 48px -26px rgba(2,6,23,.35),0 0 0 1px rgba(255,255,255,.6) inset}
        .tp-item{position:relative;padding:.78rem .55rem .78rem .55rem;border-radius:18px;display:grid;grid-template-columns:auto 1fr auto;align-items:center;gap:.95rem;background:linear-gradient(145deg,#ffffff,#f1f5f9);border:1px solid var(--border);box-shadow:0 6px 18px -10px rgba(15,23,42,.18);overflow:hidden;transition:.35s cubic-bezier(.4,.65,.25,1)}
        .tp-item:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 88% 18%,rgba(37,99,235,.08),transparent 60%);opacity:0;transition:.5s}
        .tp-item:hover{transform:translateY(-4px);box-shadow:0 14px 34px -16px rgba(15,23,42,.3)}
        .tp-item:hover:before{opacity:1}
        .tp-rank{width:38px;height:38px;border-radius:12px;background:var(--primary-50);display:flex;align-items:center;justify-content:center;font-weight:800;color:var(--primary-700);font-size:.9rem;box-shadow:0 4px 10px -6px rgba(2,6,23,.25)}
    .tp-item h4{margin:0;font-size:.97rem;font-weight:700;line-height:1.18}
        .tp-bars{display:flex;flex-direction:column;gap:.45rem;margin-top:.35rem}
        .tp-bar{--p:0;position:relative;height:8px;border-radius:10px;background:linear-gradient(145deg,#e2e8f0,#f1f5f9);overflow:hidden;box-shadow:inset 0 1px 2px rgba(0,0,0,.08)}
    .tp-bar span{position:absolute;inset:0;background:linear-gradient(90deg,#2563eb,#1d4ed8 40%,#3b82f6);width:0;transition:width 1.1s cubic-bezier(.65,.05,.36,1)}
    .tp-bar span:after{content:"";position:absolute;inset:0;background:linear-gradient(120deg,rgba(255,255,255,0),rgba(255,255,255,.5),rgba(255,255,255,0));mix-blend-mode:overlay;opacity:.55;animation:barSheen 3s ease-in-out infinite}
    @keyframes barSheen{0%{transform:translateX(-60%)}50%{transform:translateX(0)}100%{transform:translateX(80%)}}
        .tp-metric{font-size:.7rem;font-weight:600;letter-spacing:.4px;color:var(--muted);display:flex;align-items:center;gap:.45rem}
        .tp-metric i{font-size:.65rem;color:var(--primary-600)}
        .tp-score-small{font-weight:800;font-size:1rem;line-height:1}
        .tp-empty{background:#fff;border:1px dashed var(--border);border-radius:18px;padding:1.4rem;text-align:center;color:var(--muted);font-size:.9rem}
        @media(max-width:980px){.tp-champion{min-height:auto}}
            /* Featured / Upcoming Events redesigned */
            #events{position:relative}
            /* Refined Upcoming Events heading: compact, centered, accented */
            #events .section-head{position:relative;text-align:center;flex-direction:column;gap:.4rem;margin-bottom:1.1rem;padding-top:.2rem}
            #events .section-head .section-title{font-size:clamp(1.35rem,2vw,1.65rem);font-weight:700;letter-spacing:.4px;display:inline-flex;align-items:center;justify-content:center;gap:.5rem;margin:0 auto;line-height:1.05;position:relative}
            #events .section-head .section-title:before{content:"";width:12px;height:12px;border-radius:4px;background:linear-gradient(135deg,var(--primary-400),var(--primary-700));box-shadow:0 0 0 3px var(--primary-100),0 2px 8px -2px rgba(37,99,235,.5)}
            #events .section-head .section-title:after{content:"";position:absolute;left:50%;bottom:-4px;transform:translateX(-50%);width:72%;height:4px;background:linear-gradient(90deg,var(--primary-300),var(--primary-600));border-radius:3px;opacity:.9}
            #events .section-head .muted{font-size:.62rem;letter-spacing:.55px;text-transform:uppercase;font-weight:600;color:var(--muted);margin-top:.15rem}
            #events .section-head .muted:after{content:none}
            .events-grid{--card-min:250px;display:grid;grid-template-columns:repeat(auto-fill,minmax(var(--card-min),1fr));gap:1.4rem;margin-top:1.2rem}
            .event-card{background:#ffffff;border:1px solid var(--border);border-radius:22px;display:flex;flex-direction:column;overflow:hidden;position:relative;box-shadow:0 18px 40px -24px rgba(15,46,105,.25),0 4px 10px -6px rgba(15,46,105,.15);transition:.45s cubic-bezier(.4,.65,.25,1)}
            .event-card:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 85% 15%,rgba(37,99,235,.12),transparent 60%);opacity:0;transition:.6s ease}
            .event-media{position:relative;aspect-ratio:4/3;background:#e2e8f0;overflow:hidden}
            .event-media img{width:100%;height:100%;object-fit:cover;display:block;transition:transform 1.2s cubic-bezier(.25,.65,.3,1);filter:saturate(1.05)}
            .event-card:hover .event-media img{transform:scale(1.08)}
            .event-card:hover{transform:translateY(-6px);box-shadow:0 26px 50px -26px rgba(15,46,105,.32),0 6px 18px -8px rgba(15,46,105,.22)}
            .event-card:hover:before{opacity:1}
            .event-body{padding:1rem 1rem 1.1rem;display:flex;flex-direction:column;gap:.65rem}
            .event-title{margin:0;font:600 1rem/1.25 'Inter',sans-serif;color:#0f172a;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
            .event-meta{display:flex;flex-direction:column;gap:.35rem;font-size:.68rem;font-weight:600;letter-spacing:.5px;text-transform:uppercase;color:#475569}
            .event-meta span{display:inline-flex;align-items:center;gap:.4rem}
            .event-meta i{font-size:.7rem;color:#1d4ed8}
            .event-organizer{font:600 .7rem/1.1 'Inter',sans-serif;color:#1d4ed8;margin-top:.1rem}
            .event-actions{margin-top:.4rem;display:flex;gap:.6rem;flex-wrap:wrap}
            .event-btn{--btn-bg:#ffffff;--btn-border:var(--primary-600);--btn-color:var(--primary-700);background:var(--btn-bg);color:var(--btn-color);border:1.5px solid var(--btn-border);padding:.5rem .85rem;font:700 .65rem/1 'Inter',sans-serif;letter-spacing:.6px;border-radius:10px;cursor:pointer;display:inline-flex;align-items:center;gap:.4rem;position:relative;transition:.35s}
            .event-btn:hover{background:linear-gradient(135deg,#1d4ed8,#2563eb);color:#fff;box-shadow:0 10px 26px -12px rgba(29,78,216,.5)}
            .event-badge{position:absolute;top:.55rem;left:.55rem;background:linear-gradient(135deg,#f59e0b,#fbbf24);color:#1e293b;font:700 .55rem/1 'Inter',sans-serif;padding:.35rem .55rem;border-radius:8px;letter-spacing:.5px;box-shadow:0 4px 12px -6px rgba(217,119,6,.5);z-index:2}
            .event-gradient-bg{pointer-events:none;position:absolute;inset:0;background:linear-gradient(140deg,rgba(255,255,255,0),rgba(255,255,255,.45));mix-blend-mode:overlay}
            .event-card:focus-within{outline:2px solid var(--primary-400);outline-offset:2px}
            .event-empty{border:1px dashed var(--border);border-radius:20px;padding:2rem;text-align:center;color:var(--muted);background:#fff}
            @media(max-width:680px){.events-grid{--card-min:200px;gap:1rem}.event-body{padding:.85rem .85rem 1rem}.event-title{font-size:.9rem}}
        @media(max-width:640px){.tp-champion h3{font-size:1.4rem}.tp-champion .tp-score{font-size:2rem}.tp-rank{width:34px;height:34px;font-size:.8rem}.tp-item{grid-template-columns:auto 1fr auto}}
        
        /* Organizational Chart */
        #org-chart {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            padding: 3rem 0;
        }
        .org-chart-wrapper {
            max-width: 1100px;
            margin: 0 auto;
        }
        .chairman-container {
            display: flex;
            justify-content: center;
            margin-bottom: 2.5rem;
        }
        .officer-card {
            background: linear-gradient(180deg, #ffffff 0%, #f9fbff 100%);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 1.5rem;
            box-shadow: 0 12px 30px -20px rgba(2,6,23,.2);
            transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
            text-align: center;
        }
        .officer-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 40px -24px rgba(2,6,23,.28);
            border-color: var(--primary-200);
        }
        .chairman-card {
            width: 280px;
        }
        .chairman-card::after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(180deg, rgba(59,130,246,.15), rgba(59,130,246,0));
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }
        .chairman-card .officer-photo {
            width: 160px;
            height: 160px;
            margin: 0 auto 1.2rem;
        }
        .officer-photo {
            width: 120px;
            height: 120px;
            margin: 0 auto 1rem;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid var(--primary-100);
            box-shadow: 0 8px 24px -12px rgba(37,99,235,.4);
            position: relative;
        }
        .officer-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .officer-initials {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-100), var(--primary-200));
            color: var(--primary-700);
            font-size: 2rem;
            font-weight: 700;
            font-family: 'Domine', serif;
        }
        .chairman-card .officer-initials {
            font-size: 2.8rem;
        }
        .officer-info {
            position: relative;
            z-index: 1;
        }
        .officer-name {
            font-family: 'Domine', serif;
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0 0 0.4rem;
            color: var(--ink);
        }
        .chairman-card .officer-name {
            font-size: 1.4rem;
        }
        .officer-position {
            color: var(--primary);
            font-size: 0.85rem;
            font-weight: 600;
            margin: 0;
            letter-spacing: 0.3px;
        }
        .chairman-card .officer-position {
            font-size: 0.95rem;
        }
        .text-gray-400 {
            color: #9ca3af;
            font-weight: 500;
        }
        .officers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            max-width: 900px;
            margin: 0 auto;
        }
        @media(max-width: 768px) {
            .officers-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            .chairman-card {
                width: 100%;
                max-width: 280px;
            }
        }
        @media(max-width: 520px) {
            .officers-grid {
                grid-template-columns: 1fr;
            }
            .officer-card {
                padding: 1.2rem;
            }
        }
        
        /* Newsletter */
    .newsletter{background:linear-gradient(180deg,var(--primary-50) 0%, #ffffff 100%);border:1px solid var(--border);border-radius:20px;padding:1.6rem;box-shadow:0 10px 30px -18px rgba(2,6,23,.2)}
        .newsletter form{display:flex;gap:.6rem;flex-wrap:wrap}
        .newsletter input{flex:1 1 260px;padding:.7rem .9rem;border:1px solid var(--border);border-radius:999px;font-size:.95rem}
        .newsletter button{padding:.7rem 1rem;border-radius:999px}
        /* Footer */
        footer{background:linear-gradient(180deg,#0b1a3a,#0f172a);color:#d1d5db;padding:3rem 0 2rem;margin-top:3rem}
        .fwrap{display:grid;gap:2rem;grid-template-columns:repeat(auto-fit,minmax(220px,1fr))}
        footer h4{margin:0 0 1rem;font-size:.8rem;letter-spacing:.8px;text-transform:uppercase;color:#fff}
        footer a{display:block;font-size:.8rem;color:#d1d5db;padding:.3rem 0}
        footer a:hover{color:#93c5fd}
        .copyright{text-align:center;margin-top:1.8rem;font-size:.75rem;letter-spacing:.3px}
        /* Responsive */
    @media(max-width:1100px){.hero-grid{grid-template-columns:1fr}.cards{grid-template-columns:repeat(2,1fr)}.highlights{grid-template-columns:1fr}}
        @media(max-width:768px){.hero-media{max-height:380px;height:380px}}
        @media(max-width:640px){.cards{grid-template-columns:1fr}.nav-links{display:none}.hero-media{max-height:320px;height:320px;gap:.6rem}}
        @media(max-width:480px){.hero-media{max-height:280px;height:280px;gap:.5rem}}
    </style>
</head>
<body>
    <header>
        <div class="container nav" role="navigation" aria-label="Primary">
            <a class="brand" href="<?= base_url('/') ?>" aria-label="K-NECT home">
                <img src="<?= base_url('/assets/images/K-Nect-Logo.png') ?>" alt="K-NECT logo" width="25" class="px-12" loading="eager" decoding="async" />
            </a>
            <nav class="nav-links" aria-label="In-page sections">
                <a href="#programs">Programs</a>
                <a href="#stories">Stories</a>
                <a href="#highlights">Highlights</a>
                <a href="#org-chart">Officers</a>
                <a href="#newsletter">Newsletter</a>
            </nav>
            <div style="margin-left:auto;display:flex;gap:.65rem;">
                <a href="<?= base_url('login') ?>" class="btn" aria-label="Sign in">Sign In</a>
                <a href="<?= base_url('profiling') ?>" class="btn-primary btn" aria-label="Profile now">Profile Now</a>
            </div>
        </div>
    </header>

    <main>
        <!-- HERO -->
        <section class="hero">
            <div class="container hero-grid" id="top">
                <div class="hero-copy">
                    <span class="eyebrow"><span class="dot"></span> Youth Engagement Platform</span>
                    <h1>Empowering Youth Leadership & Community Impact</h1>
                    <p>The unified hub for announcements, events, documents, and data-driven insights across barangays and the city federation.</p>
                    <div class="hero-actions">
                        <a href="<?= base_url('profiling') ?>" class="btn-primary btn"><i class="fa-solid fa-gauge-high"></i> Profile Now</a>
                        <a href="#programs" class="btn"><i class="fa-solid fa-circle-info"></i> Explore</a>
                    </div>
                </div>
                <?php $heroPost = $posts[0] ?? null; ?>
                <?php
                    $heroImages = [];
                    if (!empty($posts)) {
                        foreach ($posts as $hp) {
                            if (!empty($hp['featured_image'])) {
                                $heroImages[] = base_url('/uploads/bulletin/'.$hp['featured_image']);
                            }
                            if (count($heroImages) >= 3) break;
                        }
                    }
                    if (empty($heroImages)) {
                        $heroImages = [
                            'https://via.placeholder.com/900x900?text=Community',
                            'https://via.placeholder.com/900x900?text=Events',
                            'https://via.placeholder.com/900x900?text=Resources',
                        ];
                    }
                ?>
                <div class="hero-media" aria-label="Highlights">
                    <div class="hero-card tall">
                        <span class="hero-tag">Featured</span>
                        <img src="<?= esc($heroImages[0]) ?>" alt="Featured highlight" loading="eager" decoding="async" />
                    </div>
                    <div class="hero-card">
                        <div class="ph"><img src="<?= esc($heroImages[1] ?? $heroImages[0]) ?>" alt="Secondary highlight" loading="lazy" /></div>
                    </div>
                    <div class="hero-card">
                        <div class="ph"><img src="<?= esc($heroImages[2] ?? $heroImages[0]) ?>" alt="Additional highlight" loading="lazy" /></div>
                    </div>
                </div>
            </div>
        </section>

        

        <!-- TOP PROGRAMS / FEATURES -->
        <section id="programs" aria-labelledby="programs-title">
            <div class="container">
                <div class="programs-grid">
                    <div class="programs-copy" style="align-items:center;justify-content:center;text-align:center;">
                        <h2 id="programs-title" class="sr-only">Features</h2>
                        <span class="logo-flame">
                            <span class="flame-sparks s1" aria-hidden="true"></span>
                            <span class="flame-sparks s2" aria-hidden="true"></span>
                            <img src="<?= base_url('assets/images/SK-pederasyon-Logo.png') ?>" alt="Pederasyon logo" width="240" height="240" style="max-width:240px;width:100%;height:auto;object-fit:contain;" />
                        </span>
                    </div>
                    <div class="carousel" aria-roledescription="carousel" aria-label="Programs carousel">
                        <button class="car-btn prev" aria-label="Previous" type="button"><i class="fa-solid fa-chevron-left" aria-hidden="true"></i></button>
                        <div class="car-viewport">
                            <div class="car-track" role="list">
                                <?php $programs = (isset($services) && is_array($services) && !empty($services)) ? $services : [
                                    ['icon'=>'fa-handshake-angle','title'=>'Youth Partnership','desc'=>'Connect with civic partners and initiatives that matter.','link'=>'#'],
                                    ['icon'=>'fa-graduation-cap','title'=>'Scholarship Support','desc'=>'Find education assistance and grant opportunities.','link'=>'#'],
                                    ['icon'=>'fa-people-group','title'=>'Community Outreach','desc'=>'Volunteer for barangay and city-wide engagements.','link'=>'#'],
                                    ['icon'=>'fa-chart-simple','title'=>'Data Insights','desc'=>'Use dashboards and reports for planning.','link'=>'#'],
                                ]; ?>
                                <?php $i=1; foreach ($programs as $s):
                                    $icon = $s['icon'] ?? 'fa-star';
                                    $title = $s['title'] ?? 'Feature';
                                    $desc = $s['desc'] ?? 'Learn more about this program.';
                                    $link = $s['link'] ?? '#';
                                ?>
                                <div class="car-slide" role="listitem">
                                    <article class="car-card" data-num="<?= $i ?>">
                                        <span class="car-chip">Program</span>
                                        <h3><?= esc($title) ?></h3>
                                        <p><?= esc($desc) ?></p>
                                        <div class="car-divider" aria-hidden="true"></div>
                                        <a class="car-link" href="<?= esc($link) ?>">Read more</a>
                                    </article>
                                </div>
                                <?php $i++; endforeach; ?>
                            </div>
                        </div>
                        <button class="car-btn next" aria-label="Next" type="button"><i class="fa-solid fa-chevron-right" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        </section>

        <!-- LATEST STORIES -->
        <section id="stories">
            <div class="container">
                <div class="section-head">
                    <h2 class="section-title">Latest Stories</h2>
                    <span class="muted">Updates from the bulletin</span>
                </div>
                <?php $stories = is_array($posts ?? null) ? $posts : []; ?>
                <div class="news-carousel" aria-roledescription="carousel" aria-label="Latest stories carousel" aria-live="polite">
                    <button class="news-btn prev" aria-label="Previous" type="button"><i class="fa-solid fa-chevron-left" aria-hidden="true"></i></button>
                    <div class="news-viewport">
                        <div class="news-track" role="list">
                            <?php if (!empty($stories)): foreach ($stories as $p): 
                                $img = !empty($p['featured_image']) ? base_url('/uploads/bulletin/'.$p['featured_image']) : 'https://via.placeholder.com/640x800?text=Story';
                                $title = esc($p['title'] ?? '');
                                $date = !empty($p['published_at']) ? date('M j, Y', strtotime($p['published_at'])) : '';
                                $href = !empty($p['id']) ? base_url('/news/'.$p['id']) : '#';
                            ?>
                            <div class="news-slide" role="listitem">
                                <a class="news-card" href="<?= $href ?>">
                                    <div class="news-media">
                                        <span class="news-chip">Story</span>
                                        <img src="<?= $img ?>" alt="<?= $title ?>" loading="lazy" />
                                        <?php if ($title || $date): ?>
                                        <div class="news-info">
                                            <?= $title ?><?= $date ? ' • '.$date : '' ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            </div>
                            <?php endforeach; else: ?>
                                <?php for($k=0;$k<3;$k++): ?>
                                <div class="news-slide" role="listitem">
                                    <div class="news-card">
                                        <div class="news-media skeleton" aria-hidden="true"></div>
                                    </div>
                                </div>
                                <?php endfor; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <button class="news-btn next" aria-label="Next" type="button"><i class="fa-solid fa-chevron-right" aria-hidden="true"></i></button>
                    <div class="news-dots" role="tablist" aria-label="Slide pagination"></div>
                </div>
            </div>
        </section>

        <!-- TOP PERFORMING BARANGAYS (Enhanced) -->
        <section id="highlights" aria-labelledby="tp-title">
            <div class="container" id="top-barangays" data-initial='<?= esc(json_encode($topBarangays ?? []), 'attr') ?>' data-window='<?= (int)($topBarangaysWindowDays ?? 30) ?>' data-sklogo='<?= isset($skLogo) && $skLogo ? esc(base_url($skLogo), 'attr') : '' ?>'>
                <div class="section-head tp-head-centered">
                    <h2 class="section-title" id="tp-title">Top Performing Barangays</h2>
                    <div class="tp-controls-row">
                        <div class="tp-range" role="group" aria-label="Timeframe">
                            <button type="button" class="tp-range-btn active" data-days="7">7d</button>
                            <button type="button" class="tp-range-btn" data-days="30">30d</button>
                            <button type="button" class="tp-range-btn" data-days="90">90d</button>
                        </div>
                        <span class="score-info" tabindex="0" aria-label="Scoring formula details"><i class="fa-solid fa-circle-info" aria-hidden="true"></i> Score
                            <span class="tip" role="tooltip">(Events ×3) + (Participants ×0.5) + (Posts ×2). Bars normalized to leader.</span>
                        </span>
                        <button type="button" class="tp-expand" aria-expanded="false" aria-controls="tp-full-list" hidden>View All</button>
                    </div>
                    <div class="tp-status" aria-live="polite"></div>
                </div>
                <div class="tp-wrap" aria-live="polite">
                    <div class="tp-content"></div>
                </div>
                <div id="tp-full-list" class="tp-full" hidden aria-label="Full ranking list"></div>
            </div>
        </section>

        <!-- UPCOMING EVENTS (optional) -->
        <section id="events">
            <div class="container">
                <div class="section-head">
                    <h2 class="section-title">Upcoming Events</h2>
                    <span class="muted">Be part of the next activity</span>
                </div>
                <?php if (!empty($events)): ?>
                <div class="events-grid" aria-label="List of upcoming events">
                    <?php foreach (($events ?? []) as $e): ?>
                        <?php
                            $banner = !empty($e['event_banner']) ? base_url('uploads/event/'.$e['event_banner']) : 'https://via.placeholder.com/640x480?text=Event';
                            $dateRaw = !empty($e['event_date']) ? strtotime($e['event_date']) : null;
                            $dateFull = $dateRaw ? date('D, d M Y', $dateRaw) : 'TBA';
                            $timePart = $dateRaw ? date('g:i A', $dateRaw) : '';
                            $organizer = !empty($e['organizer']) ? $e['organizer'] : null; // not provided in current query but placeholder
                            $location = !empty($e['location']) ? $e['location'] : null;
                            $category = !empty($e['category']) ? $e['category'] : null;
                            $id = esc($e['id'] ?? $e['event_id'] ?? '');
                        ?>
                        <article class="event-card" aria-labelledby="evt-title-<?= $id ?>" aria-describedby="evt-meta-<?= $id ?>">
                            <a class="event-link" href="<?= base_url('event/'.$id) ?>" style="text-decoration:none;color:inherit;display:block">
                                <div class="event-media">
                                    <img src="<?= esc($banner) ?>" alt="<?= esc($e['title']) ?> banner" loading="lazy">
                                    <span class="event-badge">Upcoming</span>
                                    <div class="event-gradient-bg"></div>
                                </div>
                                <div class="event-body">
                                    <h3 id="evt-title-<?= $id ?>" class="event-title"><?= esc($e['title']) ?></h3>
                                    <div id="evt-meta-<?= $id ?>" class="event-meta" aria-label="Event schedule and metadata">
                                        <span><i class="fa-regular fa-calendar" aria-hidden="true"></i><?= esc($dateFull) ?></span>
                                        <?php if($timePart): ?><span><i class="fa-regular fa-clock" aria-hidden="true"></i><?= esc($timePart) ?></span><?php endif; ?>
                                        <?php if($location): ?><span><i class="fa-solid fa-location-dot" aria-hidden="true"></i><?= esc($location) ?></span><?php endif; ?>
                                    </div>
                                    <?php if($organizer): ?><div class="event-organizer" aria-label="Organizer">Organized by <strong><?= esc($organizer) ?></strong></div><?php endif; ?>
                                    <?php if($category): ?><div class="event-category-pill" aria-label="Category"><?= esc(ucwords($category)) ?></div><?php endif; ?>
                                </div>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                    <div class="event-empty">No events available yet.</div>
                <?php endif; ?>
            </div>
        </section>

        <!-- ORGANIZATIONAL CHART -->
        <?php if (!empty($pedOfficers)): ?>
        <section id="org-chart" class="py-16 bg-gradient-to-b from-white to-gray-50">
            <div class="container">
                <div class="section-head" style="flex-direction: column; align-items: center; text-align: center; margin-bottom: 2rem;">
                    <h2 class="section-title" style="margin: 0; font-size: clamp(1.8rem, 3vw, 2.2rem); font-weight: 700; letter-spacing: 0.5px; color: var(--primary);">PEDERASYON OFFICERS</h2>
                    <span class="muted" style="font-size: 0.95rem; margin-top: 0.3rem;">CY <?= date('Y') ?> - Present</span>
                </div>
                
                <div class="org-chart-wrapper">
                    <?php
                    // Position titles mapping
                    $positionTitles = [
                        1 => 'SK Pederasyon President',
                        2 => 'Vice President',
                        3 => 'Secretary',
                        4 => 'Treasurer',
                        5 => 'Auditor',
                        6 => 'Public Information Officer',
                        7 => 'Sergeant at Arms'
                    ];
                    
                    // Create array of all positions with officer data or empty placeholder
                    $allPositions = [];
                    foreach ($positionTitles as $pos => $title) {
                        $allPositions[$pos] = [
                            'position' => $pos,
                            'title' => $title,
                            'officer' => null
                        ];
                    }
                    
                    // Fill in officer data where available
                    foreach ($pedOfficers as $officer) {
                        $pos = $officer['ped_position'];
                        if (isset($allPositions[$pos])) {
                            $allPositions[$pos]['officer'] = $officer;
                        }
                    }
                    
                    // Separate chairman (position 1) from other officers
                    $chairman = $allPositions[1];
                    $officers = array_slice($allPositions, 1); // positions 2-7
                    ?>
                    
                    <!-- Chairman Card (centered, larger) -->
                    <div class="chairman-container">
                        <div class="officer-card chairman-card">
                            <div class="officer-photo">
                                <?php if ($chairman['officer'] && !empty($chairman['officer']['profile_picture'])): ?>
                                    <?php 
                                        $pp = $chairman['officer']['profile_picture'];
                                        // Handle different profile picture formats
                                        if (strpos($pp, '/') !== false) {
                                            $ppUrl = base_url($pp);
                                        } else {
                                            $ppUrl = base_url('uploads/profile_pictures/' . $pp);
                                        }
                                    ?>
                                    <img src="<?= esc($ppUrl) ?>" 
                                         alt="<?= esc($chairman['officer']['first_name'] . ' ' . $chairman['officer']['last_name']) ?>" 
                                         loading="lazy">
                                <?php else: ?>
                                    <div class="officer-initials">
                                        <?php if ($chairman['officer']): ?>
                                            <?= strtoupper(substr($chairman['officer']['first_name'], 0, 1) . substr($chairman['officer']['last_name'], 0, 1)) ?>
                                        <?php else: ?>
                                            <i class="fa-solid fa-user" style="font-size: 3rem; color: var(--primary-400);"></i>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="officer-info">
                                <h3 class="officer-name">
                                    <?php if ($chairman['officer']): ?>
                                        Hon. <?= esc($chairman['officer']['first_name'] . ' ' . $chairman['officer']['last_name']) ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">To Be Appointed</span>
                                    <?php endif; ?>
                                </h3>
                                <p class="officer-position"><?= $chairman['title'] ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Officers Grid -->
                    <div class="officers-grid">
                        <?php foreach ($officers as $position): ?>
                        <div class="officer-card">
                            <div class="officer-photo">
                                <?php if ($position['officer'] && !empty($position['officer']['profile_picture'])): ?>
                                    <?php 
                                        $pp = $position['officer']['profile_picture'];
                                        // Handle different profile picture formats
                                        if (strpos($pp, '/') !== false) {
                                            $ppUrl = base_url($pp);
                                        } else {
                                            $ppUrl = base_url('uploads/profile_pictures/' . $pp);
                                        }
                                    ?>
                                    <img src="<?= esc($ppUrl) ?>" 
                                         alt="<?= esc($position['officer']['first_name'] . ' ' . $position['officer']['last_name']) ?>" 
                                         loading="lazy">
                                <?php else: ?>
                                    <div class="officer-initials">
                                        <?php if ($position['officer']): ?>
                                            <?= strtoupper(substr($position['officer']['first_name'], 0, 1) . substr($position['officer']['last_name'], 0, 1)) ?>
                                        <?php else: ?>
                                            <i class="fa-solid fa-user" style="font-size: 2.2rem; color: var(--primary-400);"></i>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="officer-info">
                                <h3 class="officer-name">
                                    <?php if ($position['officer']): ?>
                                        Hon. <?= esc($position['officer']['first_name'] . ' ' . $position['officer']['last_name']) ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">To Be Appointed</span>
                                    <?php endif; ?>
                                </h3>
                                <p class="officer-position"><?= $position['title'] ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- NEWSLETTER -->
        <section id="newsletter">
            <div class="container newsletter">
                <div class="section-head" style="margin:0 0 .6rem;">
                    <h2 class="section-title" style="margin:0;">Get updates in your inbox</h2>
                    <span class="muted">We’ll only send important platform news.</span>
                </div>
                <form onsubmit="event.preventDefault(); alert('Thanks! You’re on the list.');">
                    <label class="sr-only" for="email">Email address</label>
                    <input id="email" type="email" name="email" placeholder="your@email.com" aria-label="Email" required />
                    <button class="btn-primary btn" type="submit">Subscribe</button>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <div class="container fwrap">
            <div>
                <h4>Platform</h4>
                <a href="#top">Overview</a>
                <a href="#programs">Programs</a>
                <a href="#stories">Stories</a>
            </div>
            <div>
                <h4>Engagement</h4>
                <a href="#events">Events</a>
                <a href="#newsletter">Newsletter</a>
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
        (function(){
            const sec = document.getElementById('programs');
            if(!sec) return;
            const viewport = sec.querySelector('.car-viewport');
            const track = sec.querySelector('.car-track');
            const slides = Array.from(sec.querySelectorAll('.car-slide'));
            const prev = sec.querySelector('.car-btn.prev');
            const next = sec.querySelector('.car-btn.next');
            if(!viewport || !track || slides.length === 0) return;

            let index = 0;
            function slidesPerView(){
                return window.innerWidth >= 1024 ? 2 : 1;
            }
            function maxIndex(){
                return Math.max(0, slides.length - slidesPerView());
            }
            function stepSize(){
                if(slides.length > 1){
                    const a = slides[0].getBoundingClientRect();
                    const b = slides[1].getBoundingClientRect();
                    const d = b.left - a.left;
                    return d > 0 ? d : viewport.clientWidth; // fallback
                }
                return viewport.clientWidth;
            }
            function update(){
                const step = stepSize();
                track.style.transform = `translateX(${-index * step}px)`;
                prev && (prev.disabled = index <= 0);
                next && (next.disabled = index >= maxIndex());
            }
            update();
            window.addEventListener('resize', () => { index = Math.min(index, maxIndex()); requestAnimationFrame(update); });
            prev && prev.addEventListener('click', () => { index = Math.max(0, index - 1); update(); });
            next && next.addEventListener('click', () => { index = Math.min(maxIndex(), index + 1); update(); });

            // Touch swipe navigation
            let startX = 0, deltaX = 0, isTouching = false;
            const threshold = 40; // px to trigger slide
            viewport.addEventListener('touchstart', (e) => {
                if(!e.touches || e.touches.length !== 1) return;
                isTouching = true;
                startX = e.touches[0].clientX;
                deltaX = 0;
            }, {passive:true});
            viewport.addEventListener('touchmove', (e) => {
                if(!isTouching || !e.touches) return;
                deltaX = e.touches[0].clientX - startX;
            }, {passive:true});
            viewport.addEventListener('touchend', () => {
                if(!isTouching) return;
                if(Math.abs(deltaX) > threshold){
                    if(deltaX < 0) { index = Math.min(maxIndex(), index + 1); }
                    else { index = Math.max(0, index - 1); }
                    update();
                }
                isTouching = false;
                startX = 0; deltaX = 0;
            });
        })();

        // Animate Top Performing Barangays progress bars on visibility
        (function(){
            const root = document.getElementById('top-barangays');
            if(!root) return;
            const content = root.querySelector('.tp-content');
            const full = root.querySelector('#tp-full-list');
            const expandBtn = root.querySelector('.tp-expand');
            const status = root.querySelector('.tp-status');
            const rangeBtns = Array.from(root.querySelectorAll('.tp-range-btn'));
            let currentDays = parseInt(root.getAttribute('data-window')||'30',10);
            let cache = {}; // key=days
            const API_BASE = '<?= rtrim(base_url(), '/') ?>/';

            function setStatus(msg){ if(status) status.textContent = msg || ''; }
            function showSkeleton(){ /* skeleton removed: no-op */ }

            function animateBars(scope){
                if(!('IntersectionObserver' in window)){
                    scope.querySelectorAll('.tp-bar').forEach(b=>{
                        const p=parseFloat(b.getAttribute('data-p')||'0');
                        b.querySelector('span').style.width=p+'%';
                    });
                    return;
                }
                const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                const bars=[...scope.querySelectorAll('.tp-bar')];
                if(!bars.length) return;
                const obs=new IntersectionObserver(es=>{
                    es.forEach(e=>{
                        if(e.isIntersecting){
                            const b=e.target;const p=parseFloat(b.getAttribute('data-p')||'0');
                            const span=b.querySelector('span');
                            if(prefersReduced){ span.style.transition='none'; }
                            requestAnimationFrame(()=>{ span.style.width=p+'%'; });
                            obs.unobserve(b);
                        }
                    })
                },{threshold:.3});
                bars.forEach(b=>obs.observe(b));
            }

            function podiumTemplate(top){
                const order=[1,0,2]; // visual ordering 2-1-3
                const skLogo = root.getAttribute('data-sklogo')||'';
                let placeholderCount=0;
                const columns = order.map(slotIdx=>{
                    const r = top[slotIdx];
                    if(!r){
                        placeholderCount++;
                        return `<div class="podium-spot placeholder" aria-hidden="true">
                            <div class="podium-avatar"><div class="initials" style="opacity:.3">--</div></div>
                            <div class="podium-name" style="opacity:.35">Waiting</div>
                        </div>`;
                    }
                    const rank = top.indexOf(r)+1;
                    const initials=(r.name||'').split(/\s+/).slice(0,2).map(w=>w[0]).join('').toUpperCase();
                    const logoCandidate = r.logo || (rank===1 ? skLogo : '');
                    const avatar = logoCandidate
                        ? `<img src="${escapeHtml(logoCandidate)}" alt="${escapeHtml(r.name)} logo" loading="lazy" onerror="this.replaceWith(Object.assign(document.createElement('div'),{className:'initials',textContent:'${initials}'}))">`
                        : `<div class="initials" aria-hidden="true">${initials}</div>`;
                    return `<div class="podium-spot rank-${rank}" aria-label="Rank ${rank} ${escapeHtml(r.name)} score ${Number(r.score).toFixed(1)}">
                        ${rank===1?'<div class="podium-crown" aria-hidden="true">👑</div>':''}
                        <div class="podium-avatar">${avatar}<div class="podium-rank-badge" aria-hidden="true">#${rank}</div></div>
                        ${rank===1?'<div class="podium-label" aria-hidden="true">Current Leader</div>':''}
                        <div class="podium-name">${escapeHtml(r.name)}</div>
                        <div class="podium-score" data-score="${Number(r.score).toFixed(2)}">${Number(r.score).toFixed(1)} <small>score</small></div>
                    </div>`;
                });
                const cls=["tp-podium"]; if(top.length<3) cls.push('compact'); if(placeholderCount) cls.push('has-placeholders');
                return `<div class="${cls.join(' ')}" role="group" aria-label="Top 3 barangays">${columns.join('')}</div>`;
            }
            function remainingTemplate(list){
                if(!list.length) return '';
                let html='<div class="tp-remaining" aria-label="Other top barangays">';
                list.forEach((r,i)=>{
                    const rank=i+4; // since list starts after top3
                    const pct=r.score_percent;
                    html+=`<div class="tp-card" aria-label="Rank ${rank} ${escapeHtml(r.name)} score ${Number(r.score).toFixed(1)}">
                        <h4><span class="tp-rank-pill">#${rank}</span> ${escapeHtml(r.name)}</h4>
                        <div class="tp-progress" data-p="${pct}" aria-hidden="true"><span></span></div>
                        <div class="tp-metrics" role="group" aria-label="Metrics">
                            <span title="Events"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i>${r.events_count}</span>
                            <span title="Participants"><i class="fa-solid fa-users" aria-hidden="true"></i>${r.participants_count}</span>
                            <span title="Posts"><i class="fa-solid fa-newspaper" aria-hidden="true"></i>${r.posts_count}</span>
                            <span title="Score"><i class="fa-solid fa-star" aria-hidden="true"></i>${Number(r.score).toFixed(1)}</span>
                        </div>
                    </div>`;
                });
                html+='</div>';
                return html;
            }
            function fullRowTemplate(r,i){
                return `<div class="tp-full-row" role="row">
                    <div class="c-rank" role="cell">#${i+1}</div>
                    <div class="c-name" role="cell">${escapeHtml(r.name)}</div>
                    <div class="c-num" role="cell">${r.events_count}</div>
                    <div class="c-num" role="cell">${r.participants_count}</div>
                    <div class="c-num" role="cell">${r.posts_count}</div>
                    <div class="c-bar" role="cell"><div class="tp-bar mini-bar" data-p="${r.score_percent}"><span></span></div></div>
                    <div class="c-score" role="cell">${Number(r.score).toFixed(1)}</div>
                </div>`;
            }
            function escapeHtml(s){
                return (s||'').replace(/[&<>"']/g,m=>({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#39;"}[m]));
            }

            function render(data){
                if(!content) return;
                if(!data || !data.length){
                    content.innerHTML = '<div class="tp-empty">No performance data available yet. Check back soon.</div>';
                    full.innerHTML = '';
                    if(expandBtn) expandBtn.hidden = true;
                    return;
                }
                const podium = data.slice(0,3);
                const remaining = data.slice(3,9); // show next 6
                let html = podiumTemplate(podium);
                html += remainingTemplate(remaining);
                if(expandBtn) expandBtn.hidden = data.length<=3;
                content.innerHTML = html;
                // helper caption already via CSS pseudo element; no extra DOM needed
                // full list
                let fullHtml = '<div class="tp-table" role="table" aria-label="Full barangay rankings"><div class="tp-full-head" role="row">'+
                    '<div role="columnheader">Rank</div><div role="columnheader">Barangay</div><div role="columnheader">Events</div><div role="columnheader">Participants</div><div role="columnheader">Posts</div><div role="columnheader">Progress</div><div role="columnheader">Score</div></div>';
                data.forEach((r,i)=>{ fullHtml += fullRowTemplate(r,i); });
                fullHtml += '</div>';
                full.innerHTML = fullHtml;
                // animate progress
                requestAnimationFrame(()=>{
                   root.querySelectorAll('.tp-progress').forEach(bar=>{
                       const p=parseFloat(bar.getAttribute('data-p')||'0');
                       const span=bar.querySelector('span');
                       if(span) span.style.width=p+'%';
                   });
                   // count-up podium scores
                   const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                   if(!prefersReduced){
                        root.querySelectorAll('.podium-score').forEach(el=>{
                            const target=parseFloat(el.getAttribute('data-score')||'0');
                            let start=0; const dur=800; const startTs=performance.now();
                            function step(ts){
                                const t=Math.min(1,(ts-startTs)/dur);
                                const eased=t<.5?4*t*t*t:(t-1)*(2*t-2)*(2*t-2)+1; // easeInOutCubic variant
                                const val=(start+(target-start)*eased).toFixed(1);
                                el.firstChild && (el.firstChild.textContent=val+' ');
                                if(t<1) requestAnimationFrame(step); else el.firstChild && (el.firstChild.textContent=target.toFixed(1)+' ');
                            }
                            requestAnimationFrame(step);
                        });
                   }
                });
                // skeleton removed
            }

            function hydrateInitial(){
                try {
                    const initial = JSON.parse(root.getAttribute('data-initial')||'[]');
                    cache[currentDays]=initial;
                    render(initial);
                } catch(e){ console.warn('Failed to parse initial rankings'); }
            }

            async function load(days){
                currentDays = days;
                setStatus('Loading '+days+' day performance...');
                // skeleton removed
                try {
                    if(cache[days]){ render(cache[days]); setStatus('Updated for last '+days+' days'); return; }
                    const res = await fetch(API_BASE+`api/top-barangays?days=${days}&limit=0`, {headers:{'Accept':'application/json'}});
                    if(!res.ok) throw new Error('Network');
                    const json = await res.json();
                    if(json.ok){ cache[days]=json.data; render(json.data); setStatus('Updated for last '+days+' days'); }
                    else { throw new Error('API error'); }
                } catch(err){
                    setStatus('Unable to load rankings');
                } finally { /* no skeleton */ }
            }

            // events
            rangeBtns.forEach(btn=>{
                btn.addEventListener('click',()=>{
                    if(btn.classList.contains('active')) return;
                    rangeBtns.forEach(b=>b.classList.remove('active'));
                    btn.classList.add('active');
                    const d = parseInt(btn.getAttribute('data-days'),10);
                    load(d);
                });
                // set default active based on initial window
                if(parseInt(btn.getAttribute('data-days'),10)===currentDays){ btn.classList.add('active'); }
            });
            if(expandBtn){
                expandBtn.addEventListener('click',()=>{
                    const expanded = expandBtn.getAttribute('aria-expanded')==='true';
                    expandBtn.setAttribute('aria-expanded', expanded?'false':'true');
                    full.hidden = expanded; // toggle
                    expandBtn.textContent = expanded ? 'View All' : 'Collapse';
                });
            }

            hydrateInitial();
            // skeleton removed; nothing to hide
            // If initial days != any preset, mark 30d
            if(!rangeBtns.some(b=>b.classList.contains('active'))){
                rangeBtns.forEach(b=>b.classList.remove('active'));
                const d30 = rangeBtns.find(b=>b.getAttribute('data-days')==='30');
                d30 && d30.classList.add('active');
            }
        })();

        // Latest Stories carousel
        (function(){
            const sec = document.getElementById('stories');
            if(!sec) return;
            const viewport = sec.querySelector('.news-viewport');
            const track = sec.querySelector('.news-track');
            const slides = Array.from(sec.querySelectorAll('.news-slide'));
            const prev = sec.querySelector('.news-btn.prev');
            const next = sec.querySelector('.news-btn.next');
            const dotsWrap = sec.querySelector('.news-dots');
            if(!viewport || !track || slides.length === 0) return;

            let index = 0;
            function slidesPerView(){
                if (window.innerWidth >= 1280) return 4;
                if (window.innerWidth >= 768) return 3;
                return 1; // mobile shows ~88% width slide but still 1 per view logically
            }
            function maxIndex(){
                return Math.max(0, slides.length - slidesPerView());
            }
            function stepSize(){
                if(slides.length > 1){
                    const a = slides[0].getBoundingClientRect();
                    const b = slides[1].getBoundingClientRect();
                    const d = b.left - a.left;
                    return d > 0 ? d : viewport.clientWidth; // fallback
                }
                return viewport.clientWidth;
            }
            function update(){
                const step = stepSize();
                track.style.transform = `translateX(${-index * step}px)`;
                if(prev) prev.disabled = index <= 0;
                if(next) next.disabled = index >= maxIndex();
                // update dots
                if(dotsWrap){
                    const children = Array.from(dotsWrap.children);
                    children.forEach((d,i)=>{
                        d.setAttribute('aria-selected', i === index ? 'true' : 'false');
                        d.tabIndex = i === index ? 0 : -1;
                    });
                }
            }
            update();
            // build dots
            if(dotsWrap){
                const total = slides.length - slidesPerView() + 1;
                const dotsCount = Math.max(1, total);
                dotsWrap.innerHTML = '';
                for(let i=0;i<dotsCount;i++){
                    const b = document.createElement('button');
                    b.className = 'news-dot';
                    b.type = 'button';
                    b.setAttribute('role','tab');
                    b.setAttribute('aria-label', `Go to slide ${i+1}`);
                    b.addEventListener('click', ()=>{ index = i; update(); });
                    b.addEventListener('keydown', (e)=>{
                        if(e.key==='ArrowLeft'){ index = Math.max(0, index - 1); update(); }
                        if(e.key==='ArrowRight'){ index = Math.min(maxIndex(), index + 1); update(); }
                    });
                    dotsWrap.appendChild(b);
                }
                update();
            }
            window.addEventListener('resize', () => { index = Math.min(index, maxIndex()); requestAnimationFrame(update); });
            prev && prev.addEventListener('click', () => { index = Math.max(0, index - 1); update(); });
            next && next.addEventListener('click', () => { index = Math.min(maxIndex(), index + 1); update(); });

            // Touch swipe
            let startX = 0, deltaX = 0, isTouching = false;
            const threshold = 40;
            viewport.addEventListener('touchstart', (e) => {
                if(!e.touches || e.touches.length !== 1) return;
                isTouching = true; startX = e.touches[0].clientX; deltaX = 0;
            }, {passive:true});
            viewport.addEventListener('touchmove', (e) => {
                if(!isTouching || !e.touches) return; deltaX = e.touches[0].clientX - startX;
            }, {passive:true});
            viewport.addEventListener('touchend', () => {
                if(!isTouching) return;
                if(Math.abs(deltaX) > threshold){
                    if(deltaX < 0) index = Math.min(maxIndex(), index + 1);
                    else index = Math.max(0, index - 1);
                    update();
                }
                isTouching = false; startX = 0; deltaX = 0;
            });
        })();
    </script>
</body>
</html>
