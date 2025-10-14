/**
 * K-NECT AJAX CSRF Protection Handler
 * Automatically adds CSRF token to all AJAX requests
 * Version: 1.0.0
 * Date: October 13, 2025
 */

(function() {
    'use strict';

    // Get CSRF token from meta tag
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function getCsrfTokenName() {
        const meta = document.querySelector('meta[name="csrf-token-name"]');
        return meta ? meta.getAttribute('content') : 'csrf_test_name';
    }

    // Update CSRF token in meta tag (for token regeneration)
    function updateCsrfToken(newToken) {
        const meta = document.querySelector('meta[name="csrf-token"]');
        if (meta && newToken) {
            meta.setAttribute('content', newToken);
        }
    }

    // ===== FETCH API INTERCEPTOR =====
    if (window.fetch) {
        const originalFetch = window.fetch;
        window.fetch = function(url, options = {}) {
            // Only add CSRF to POST, PUT, PATCH, DELETE
            const method = (options.method || 'GET').toUpperCase();
            const needsCsrf = ['POST', 'PUT', 'PATCH', 'DELETE'].includes(method);

            if (needsCsrf) {
                options.headers = options.headers || {};
                
                // Add CSRF token to headers
                if (options.headers instanceof Headers) {
                    options.headers.set('X-CSRF-TOKEN', getCsrfToken());
                } else {
                    options.headers['X-CSRF-TOKEN'] = getCsrfToken();
                }

                // If sending FormData, append CSRF token
                if (options.body instanceof FormData) {
                    const tokenName = getCsrfTokenName();
                    const token = getCsrfToken();
                    if (!options.body.has(tokenName)) {
                        options.body.append(tokenName, token);
                    }
                }
                // If sending JSON, add token to body
                else if (options.body && typeof options.body === 'string') {
                    const contentType = options.headers['Content-Type'] || options.headers['content-type'] || '';
                    if (contentType.includes('application/json')) {
                        try {
                            const data = JSON.parse(options.body);
                            data[getCsrfTokenName()] = getCsrfToken();
                            options.body = JSON.stringify(data);
                        } catch (e) {
                            console.warn('CSRF: Failed to add token to JSON body:', e);
                        }
                    }
                }
            }

            return originalFetch(url, options).then(response => {
                // Update CSRF token if server sends new one
                const newToken = response.headers.get('X-CSRF-TOKEN');
                if (newToken) {
                    updateCsrfToken(newToken);
                }
                return response;
            }).catch(error => {
                // Check if it's a CSRF error
                if (error.message && error.message.includes('403')) {
                    console.error('CSRF: Token validation failed. Please refresh the page.');
                }
                throw error;
            });
        };
    }

    // ===== JQUERY AJAX INTERCEPTOR =====
    if (window.jQuery) {
        jQuery.ajaxSetup({
            beforeSend: function(xhr, settings) {
                const method = (settings.type || 'GET').toUpperCase();
                const needsCsrf = ['POST', 'PUT', 'PATCH', 'DELETE'].includes(method);
                
                if (needsCsrf) {
                    // Add CSRF token to headers
                    xhr.setRequestHeader('X-CSRF-TOKEN', getCsrfToken());
                    
                    // If sending data, append CSRF token
                    if (settings.data) {
                        const tokenName = getCsrfTokenName();
                        const token = getCsrfToken();
                        
                        if (typeof settings.data === 'string') {
                            // URL-encoded data - check if token already exists
                            if (!settings.data.includes(tokenName + '=')) {
                                settings.data += (settings.data ? '&' : '') + tokenName + '=' + encodeURIComponent(token);
                            }
                        } else if (settings.data instanceof FormData) {
                            // FormData
                            if (!settings.data.has(tokenName)) {
                                settings.data.append(tokenName, token);
                            }
                        } else if (typeof settings.data === 'object') {
                            // Plain object
                            if (!settings.data[tokenName]) {
                                settings.data[tokenName] = token;
                            }
                        }
                    }
                }
            },
            complete: function(xhr) {
                // Update CSRF token if server sends new one
                const newToken = xhr.getResponseHeader('X-CSRF-TOKEN');
                if (newToken) {
                    updateCsrfToken(newToken);
                }
            },
            error: function(xhr, status, error) {
                // Handle CSRF errors
                if (xhr.status === 403) {
                    console.error('CSRF: Token validation failed. Please refresh the page.');
                    
                    // Show user-friendly error if SweetAlert2 is available
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Session Expired',
                            text: 'Your session has expired. Please refresh the page and try again.',
                            icon: 'warning',
                            confirmButtonText: 'Refresh Page',
                            confirmButtonColor: '#3b82f6'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    }
                }
            }
        });
    }

    // ===== XMLHTTPREQUEST INTERCEPTOR =====
    if (window.XMLHttpRequest) {
        const originalOpen = XMLHttpRequest.prototype.open;
        const originalSend = XMLHttpRequest.prototype.send;

        XMLHttpRequest.prototype.open = function(method, url, async, user, password) {
            this._method = method.toUpperCase();
            this._url = url;
            return originalOpen.apply(this, arguments);
        };

        XMLHttpRequest.prototype.send = function(data) {
            const needsCsrf = ['POST', 'PUT', 'PATCH', 'DELETE'].includes(this._method);
            
            if (needsCsrf) {
                // Add CSRF token to headers
                this.setRequestHeader('X-CSRF-TOKEN', getCsrfToken());
                
                // If sending FormData, append CSRF token
                if (data instanceof FormData) {
                    const tokenName = getCsrfTokenName();
                    if (!data.has(tokenName)) {
                        data.append(tokenName, getCsrfToken());
                    }
                }
            }

            // Add error handler
            const originalOnError = this.onerror;
            this.onerror = function(e) {
                if (this.status === 403) {
                    console.error('CSRF: Token validation failed. Please refresh the page.');
                }
                if (originalOnError) {
                    originalOnError.apply(this, arguments);
                }
            };

            return originalSend.apply(this, arguments);
        };
    }

    // Export utility functions for manual use if needed
    window.KNectCSRF = {
        getToken: getCsrfToken,
        getTokenName: getCsrfTokenName,
        updateToken: updateCsrfToken
    };

})();
