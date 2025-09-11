<style>
/* Shared responsive tweaks for Bulletin UIs across roles */
:root{--bulletin-radius:0.75rem}

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

/* Ensure grids don’t collapse awkwardly on mid widths */
#posts-container{grid-template-columns:repeat(auto-fill,minmax(250px,1fr))}
@media (max-width: 640px){
  #posts-container{grid-template-columns:1fr}
}

/* Document carousel: ensure cards shrink on narrow viewports */
.doc-carousel .card{width:min(18rem, 80vw)}

/* Utility: prevent oversized images inside content */
article img{max-width:100%; height:auto}
</style>
