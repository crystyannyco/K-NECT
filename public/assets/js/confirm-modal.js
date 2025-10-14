/**
 * K-NECT Confirmation Modal System
 * Modern confirmation dialogs for destructive actions
 */

// Create confirmation modal
function showConfirmModal(options) {
    const {
        title = 'Are you sure?',
        message = 'This action cannot be undone.',
        confirmText = 'Confirm',
        cancelText = 'Cancel',
        confirmColor = 'red', // red, blue, green
        icon = 'warning', // warning, question, info
        onConfirm = () => {},
        onCancel = () => {}
    } = options;

    // Remove any existing modal
    const existingModal = document.getElementById('confirmModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Icon SVGs
    const icons = {
        warning: `
            <svg class="w-16 h-16 text-yellow-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        `,
        question: `
            <svg class="w-16 h-16 text-blue-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        `,
        info: `
            <svg class="w-16 h-16 text-blue-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        `
    };

    // Button colors
    const colors = {
        red: 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
        blue: 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
        green: 'bg-green-600 hover:bg-green-700 focus:ring-green-500'
    };

    // Create modal HTML
    const modalHTML = `
        <div id="confirmModal" class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <!-- Center modal -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-2xl px-6 pt-8 pb-6 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div>
                        <div class="mx-auto flex items-center justify-center mb-6">
                            ${icons[icon] || icons.warning}
                        </div>
                        <div class="text-center">
                            <h3 class="text-2xl font-bold text-gray-900 mb-3" id="modal-title">
                                ${title}
                            </h3>
                            <div class="mt-2">
                                <p class="text-base text-gray-600">
                                    ${message}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 flex flex-col-reverse sm:flex-row gap-3">
                        <button type="button" id="confirmModalCancel" class="flex-1 inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            ${cancelText}
                        </button>
                        <button type="button" id="confirmModalConfirm" class="flex-1 inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-3 ${colors[confirmColor] || colors.red} text-base font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors">
                            ${confirmText}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHTML);

    const modal = document.getElementById('confirmModal');
    const confirmBtn = document.getElementById('confirmModalConfirm');
    const cancelBtn = document.getElementById('confirmModalCancel');

    // Show modal with animation
    setTimeout(() => {
        modal.querySelector('.bg-gray-900').classList.add('opacity-100');
        modal.querySelector('.inline-block').classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
    }, 10);

    // Handle confirm
    confirmBtn.addEventListener('click', () => {
        closeModal();
        onConfirm();
    });

    // Handle cancel
    cancelBtn.addEventListener('click', () => {
        closeModal();
        onCancel();
    });

    // Handle click outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal || e.target.classList.contains('bg-gray-900')) {
            closeModal();
            onCancel();
        }
    });

    // Handle escape key
    const escapeHandler = (e) => {
        if (e.key === 'Escape') {
            closeModal();
            onCancel();
            document.removeEventListener('keydown', escapeHandler);
        }
    };
    document.addEventListener('keydown', escapeHandler);

    function closeModal() {
        const modal = document.getElementById('confirmModal');
        if (modal) {
            modal.querySelector('.bg-gray-900').classList.remove('opacity-100');
            modal.querySelector('.inline-block').classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            setTimeout(() => {
                modal.remove();
            }, 300);
        }
    }
}

// Export for global use
window.showConfirmModal = showConfirmModal;
