    </div> <!-- Close flex container from sidebar -->
    
    <!-- Image Fallback System -->
    <script>
        // Set up default image URLs for the fallback system
        window.KNECT_DEFAULTS = {
            avatar: '<?= get_default_image('avatar') ?>',
            document: '<?= get_default_image('document') ?>',
            pdf: '<?= get_default_image('pdf') ?>',
            word: '<?= get_default_image('word') ?>',
            excel: '<?= get_default_image('excel') ?>',
            image: '<?= get_default_image('image') ?>',
            event: '<?= get_default_image('event') ?>'
        };
        window.baseUrl = '<?= base_url() ?>';
    </script>
    <script src="<?= base_url('assets/js/image-fallback.js') ?>"></script>
    
    <script>
        // Legacy fallback system for backward compatibility
        (function () {
            const defaultFallback = '<?= get_default_image('avatar') ?>';

            function setFallback(img) {
                if (!img) return;
                const fallback = img.getAttribute('data-fallback') || defaultFallback;
                // avoid infinite loop by marking applied fallback
                if (img.dataset.fallbackApplied === fallback) return;
                img.dataset.fallbackApplied = fallback;
                img.src = fallback;
            }

            function wireImage(img) {
                if (!img || img.dataset.fallbackWired) return;
                img.dataset.fallbackWired = '1';

                // If src is empty, set fallback immediately
                if (!img.getAttribute('src')) {
                    setFallback(img);
                }

                img.addEventListener('error', function onErr() {
                    img.removeEventListener('error', onErr);
                    setFallback(img);
                }, { once: true });
            }

            document.addEventListener('DOMContentLoaded', function () {
                // Wire existing images
                Array.prototype.forEach.call(document.images, wireImage);

                // Observe future images
                const observer = new MutationObserver(function (mutations) {
                    for (const m of mutations) {
                        for (const node of m.addedNodes) {
                            if (!(node instanceof Element)) continue;
                            if (node.tagName === 'IMG') wireImage(node);
                            node.querySelectorAll && node.querySelectorAll('img').forEach(wireImage);
                        }
                    }
                });
                observer.observe(document.body, { childList: true, subtree: true });
            });
        })();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/panzoom/panzoom.umd.js"></script>
</body>
</html>
