/**
 * K-NECT Image Fallback Utility
 * Handles automatic fallback to default images when images fail to load
 */

// Default image URLs - these will be set by PHP
window.KNECT_DEFAULTS = window.KNECT_DEFAULTS || {
    avatar: '',
    document: '',
    pdf: '',
    word: '',
    excel: '',
    image: '',
    event: ''
};

/**
 * Set up image fallback for an element
 * @param {HTMLImageElement} img - The image element
 * @param {string} fallbackType - Type of fallback (avatar, document, pdf, etc.)
 * @param {string} filename - Optional filename to determine type from extension
 */
function setupImageFallback(img, fallbackType = 'document', filename = null) {
    if (!img || img.tagName !== 'IMG') return;
    
    // Don't set up fallback if already has one
    if (img.hasAttribute('data-fallback-setup')) return;
    
    img.setAttribute('data-fallback-setup', 'true');
    
    let fallbackUrl = getFallbackUrl(fallbackType, filename);
    
    img.onerror = function() {
        // Prevent infinite loop
        if (this.src === fallbackUrl) return;
        
        this.onerror = null;
        this.src = fallbackUrl;
        this.classList.add('fallback-image');
        
        // Dispatch custom event
        this.dispatchEvent(new CustomEvent('imageFallback', {
            detail: { originalSrc: this.getAttribute('data-original-src') || this.src, fallbackSrc: fallbackUrl }
        }));
    };
    
    // Store original src
    if (!img.hasAttribute('data-original-src')) {
        img.setAttribute('data-original-src', img.src);
    }
}

/**
 * Get fallback URL based on type and filename
 * @param {string} fallbackType 
 * @param {string} filename 
 * @returns {string}
 */
function getFallbackUrl(fallbackType, filename = null) {
    // Try to determine type from filename
    if (filename) {
        const ext = filename.split('.').pop().toLowerCase();
        switch (ext) {
            case 'pdf':
                return window.KNECT_DEFAULTS.pdf;
            case 'doc':
            case 'docx':
                return window.KNECT_DEFAULTS.word;
            case 'xls':
            case 'xlsx':
            case 'csv':
                return window.KNECT_DEFAULTS.excel;
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'webp':
            case 'svg':
            case 'bmp':
                return window.KNECT_DEFAULTS.image;
        }
    }
    
    // Use specified type
    return window.KNECT_DEFAULTS[fallbackType] || window.KNECT_DEFAULTS.document;
}

/**
 * Create a safe image element with fallback
 * @param {string} src - Image source URL
 * @param {string} alt - Alt text
 * @param {string} fallbackType - Type of fallback
 * @param {Object} attributes - Additional attributes
 * @returns {HTMLImageElement}
 */
function createSafeImage(src, alt = '', fallbackType = 'document', attributes = {}) {
    const img = document.createElement('img');
    img.alt = alt;
    
    // Set attributes
    Object.entries(attributes).forEach(([key, value]) => {
        img.setAttribute(key, value);
    });
    
    setupImageFallback(img, fallbackType, attributes.filename);
    img.src = src || getFallbackUrl(fallbackType, attributes.filename);
    
    return img;
}

/**
 * Auto-setup fallbacks for existing images on page load
 */
function autoSetupImageFallbacks() {
    // Profile images
    document.querySelectorAll('img[data-type="profile"], img[data-type="avatar"]').forEach(img => {
        setupImageFallback(img, 'avatar');
    });
    
    // Document thumbnails
    document.querySelectorAll('img[data-type="document"]').forEach(img => {
        const filename = img.getAttribute('data-filename');
        setupImageFallback(img, 'document', filename);
    });
    
    // Event banners
    document.querySelectorAll('img[data-type="event"], img[data-type="banner"]').forEach(img => {
        setupImageFallback(img, 'event');
    });
    
    // Generic images with fallback attribute
    document.querySelectorAll('img[data-fallback]').forEach(img => {
        const fallbackType = img.getAttribute('data-fallback');
        const filename = img.getAttribute('data-filename');
        setupImageFallback(img, fallbackType, filename);
    });
}

/**
 * Update profile image with fallback
 * @param {string} selector - CSS selector for the image
 * @param {string} src - New image source
 */
function updateProfileImage(selector, src) {
    const img = document.querySelector(selector);
    if (img) {
        setupImageFallback(img, 'avatar');
        img.src = src || window.KNECT_DEFAULTS.avatar;
    }
}

/**
 * Create document preview with appropriate fallback
 * @param {Object} doc - Document object with mimetype, filename, etc.
 * @returns {HTMLElement}
 */
function createDocumentPreview(doc) {
    const container = document.createElement('div');
    container.className = 'document-preview';
    
    const img = createSafeImage(
        doc.thumbnail_path ? `${window.baseUrl}uploads/thumbnails/${doc.thumbnail_path}` : null,
        `${doc.title || doc.filename} preview`,
        'document',
        { 
            filename: doc.filename,
            'data-mimetype': doc.mimetype,
            class: 'w-full h-full object-contain'
        }
    );
    
    container.appendChild(img);
    return container;
}

// Initialize on DOM content loaded
document.addEventListener('DOMContentLoaded', function() {
    autoSetupImageFallbacks();
});

// Re-run setup when new content is added dynamically
const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        mutation.addedNodes.forEach(function(node) {
            if (node.nodeType === 1) { // Element node
                if (node.tagName === 'IMG') {
                    const fallbackType = node.getAttribute('data-fallback') || 
                                       node.getAttribute('data-type') || 'document';
                    const filename = node.getAttribute('data-filename');
                    setupImageFallback(node, fallbackType, filename);
                } else {
                    // Check for images within the added node
                    node.querySelectorAll('img[data-fallback], img[data-type]').forEach(img => {
                        const fallbackType = img.getAttribute('data-fallback') || 
                                           img.getAttribute('data-type') || 'document';
                        const filename = img.getAttribute('data-filename');
                        setupImageFallback(img, fallbackType, filename);
                    });
                }
            }
        });
    });
});

observer.observe(document.body, { childList: true, subtree: true });

// Export functions for global use
window.KNECTImages = {
    setupImageFallback,
    getFallbackUrl,
    createSafeImage,
    updateProfileImage,
    createDocumentPreview,
    autoSetupImageFallbacks
};
