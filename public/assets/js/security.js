/**
 * Security Enhancement Script
 * Automatically adds rel="noopener noreferrer" to all external links
 * This prevents Reverse Tabnabbing attacks
 */

(function() {
    'use strict';
    
    /**
     * Add security attributes to external links
     */
    function secureExternalLinks() {
        // Get all links
        const links = document.querySelectorAll('a[href]');
        
        links.forEach(link => {
            // Check if link opens in new tab/window
            if (link.getAttribute('target') === '_blank') {
                const currentRel = link.getAttribute('rel') || '';
                
                // Add noopener and noreferrer if not already present
                const relValues = currentRel.split(' ').filter(v => v.length > 0);
                
                if (!relValues.includes('noopener')) {
                    relValues.push('noopener');
                }
                
                if (!relValues.includes('noreferrer')) {
                    relValues.push('noreferrer');
                }
                
                link.setAttribute('rel', relValues.join(' '));
            }
            
            // Also check for external links (different origin)
            try {
                const linkUrl = new URL(link.href, window.location.href);
                const isExternal = linkUrl.hostname !== window.location.hostname;
                
                if (isExternal && link.getAttribute('target') !== '_self') {
                    // External link - add security attributes
                    const currentRel = link.getAttribute('rel') || '';
                    const relValues = currentRel.split(' ').filter(v => v.length > 0);
                    
                    if (!relValues.includes('noopener')) {
                        relValues.push('noopener');
                    }
                    
                    if (!relValues.includes('noreferrer')) {
                        relValues.push('noreferrer');
                    }
                    
                    link.setAttribute('rel', relValues.join(' '));
                }
            } catch (e) {
                // Invalid URL, skip
            }
        });
    }
    
    /**
     * Sanitize user input in forms (basic XSS prevention)
     */
    function sanitizeFormInputs() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                // Get all text inputs, textareas
                const inputs = form.querySelectorAll('input[type="text"], input[type="search"], textarea');
                
                inputs.forEach(input => {
                    // Trim whitespace
                    input.value = input.value.trim();
                });
            });
        });
    }
    
    /**
     * Prevent form double submission
     */
    function preventDoubleSubmit() {
        const forms = document.querySelectorAll('form[method="post"]');
        
        forms.forEach(form => {
            let isSubmitting = false;
            
            form.addEventListener('submit', function(e) {
                if (isSubmitting) {
                    e.preventDefault();
                    return false;
                }
                isSubmitting = true;
                
                // Re-enable after 3 seconds as fallback
                setTimeout(() => {
                    isSubmitting = false;
                }, 3000);
            });
        });
    }
    
    /**
     * Add security headers to fetch requests
     */
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        // Add security headers to fetch requests
        if (args[1]) {
            args[1].credentials = args[1].credentials || 'same-origin';
        } else {
            args[1] = { credentials: 'same-origin' };
        }
        
        return originalFetch.apply(this, args);
    };
    
    /**
     * Initialize security enhancements
     */
    function initSecurity() {
        secureExternalLinks();
        sanitizeFormInputs();
        preventDoubleSubmit();
        
        // Re-run on dynamic content changes
        if (window.MutationObserver) {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.addedNodes.length > 0) {
                        secureExternalLinks();
                    }
                });
            });
            
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    }
    
    // Run when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSecurity);
    } else {
        initSecurity();
    }
    
})();
