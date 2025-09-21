<style>
/* Shared responsive tweaks for Bulletin UIs across roles */
:root{--bulletin-radius:0.75rem}
/* Shared container that aligns header with cards */
.bulletin-wrap{max-width:112rem;margin-left:auto;margin-right:auto;padding-left:1rem;padding-right:1rem}
@media (min-width:640px){.bulletin-wrap{padding-left:1.5rem;padding-right:1.5rem}}
@media (min-width:1024px){.bulletin-wrap{padding-left:2rem;padding-right:2rem}}

/* Header sizing that scales well when zoomed */
.bulletin-header{border-radius:1rem;border:1px solid #e5e7eb;background:#fff;box-shadow:0 1px 2px rgba(0,0,0,.04)}
.bulletin-header .title{font-weight:800;line-height:1.1;letter-spacing:-.015em;font-size:clamp(1.1rem,2.2vw,1.6rem)}
.bulletin-header .actions{display:flex;gap:.5rem;flex-wrap:wrap}
.bulletin-header .controls{display:flex;flex-wrap:wrap;gap:.6rem}
.bulletin-header input[type="text"],
.bulletin-header select{
  height:2.5rem; /* 40px */
  font-size:.9rem;
}
@media (max-width:480px){
  .bulletin-header .controls > *{flex:1 1 100%}
}

/* Media blocks: switch to aspect-ratio so heights scale gracefully */
.media{position:relative; width:100%; aspect-ratio:16/9; height:auto;}
.media img{width:100%; height:100%; object-fit:cover; display:block}

/* Allow a bit taller media on small screens for better readability */
@media (max-width: 640px){
  .media{aspect-ratio:4/3}
}
@media (min-width: 1280px){
  .media{aspect-ratio:16/9}
}

/* Post card spacing and clamp safety */
.post-card{background:#fff; border:1px solid #e5e7eb; border-radius:var(--bulletin-radius); overflow:hidden}
.post-card .chip{display:inline-flex; align-items:center; gap:.25rem; padding:.15rem .5rem; border-radius:9999px; font-size:.7rem; font-weight:600}

/* Chip active visual */
.chip.active{outline:2px solid rgba(59,130,246,.3)}

/* Ensure grids don’t collapse awkwardly on mid widths */
#posts-container{grid-template-columns:repeat(auto-fill,minmax(250px,1fr))}
@media (max-width: 640px){
  #posts-container{grid-template-columns:1fr}
}

/* Align section blocks to container width too */
.bulletin-section{max-width:112rem;margin-left:auto;margin-right:auto}

/* Document carousel: ensure cards shrink on narrow viewports */
.doc-carousel .card{width:min(18rem, 80vw)}

/* Utility: prevent oversized images inside content */
article img{max-width:100%; height:auto}
</style>
