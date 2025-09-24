

<div class="flex-1 flex flex-col min-h-0 ml-0 lg:ml-64 pt-16">
	<main class="flex-1 overflow-auto p-4 lg:p-6 bg-gray-50">
		<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5 lg:p-6 mb-4 sm:mb-5 lg:mb-6">
			<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
				<div class="flex-1">
					<h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Account Settings</h1>
					<p class="text-gray-600 mt-1 text-sm sm:text-base">Manage your profile information and password</p>
				</div>
			</div>
		</div>

		<div id="account-settings" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
			<div class="border-b border-gray-200">
				<div class="flex overflow-x-auto scrollbar-hide" role="tablist">
					<button type="button" id="tab-profile" class="tab-button whitespace-nowrap px-5 py-3 text-sm font-medium border-b-2 border-blue-600 text-blue-600" data-tab="profile" role="tab" aria-selected="true" aria-controls="profile-tab">Profile</button>
					<button type="button" id="tab-security" class="tab-button whitespace-nowrap px-5 py-3 text-sm font-medium border-b-2 border-transparent text-gray-600" data-tab="security" role="tab" aria-selected="false" aria-controls="security-tab">Security</button>
				</div>
			</div>

			<div class="p-6">
				<div id="profile-tab" class="tab-content" role="tabpanel" aria-labelledby="tab-profile">
					<form action="<?= base_url('pederasyon/account-settings/profile') ?>" method="post" enctype="multipart/form-data">
						<?= csrf_field() ?>
						<div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 mb-8 pb-8 border-b border-gray-200">
							<div class="flex-shrink-0">
								<div class="relative">
									<?php if (!empty($userExtInfo['profile_picture'])): ?>
										<?php 
											$pp = $userExtInfo['profile_picture'];
											$ppUrl = (strpos($pp, '/') !== false) ? base_url($pp) : base_url('uploads/profile_pictures/' . $pp);
										?>
										<img id="profile-image" src="<?= esc($ppUrl) ?>" alt="Profile Picture" class="w-32 h-32 object-cover border-4 border-white shadow-lg rounded-full">
									<?php else: ?>
										<div id="profile-placeholder" class="w-32 h-32 bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-lg border-4 border-white rounded-full">
											<span class="text-white text-3xl font-bold">
												<?= $user ? strtoupper(substr($user['first_name'] ?? 'U', 0, 1) . substr($user['last_name'] ?? '', 0, 1)) : 'U' ?>
											</span>
										</div>
										<img id="profile-image" src="" alt="Profile Picture" class="w-32 h-32 object-cover border-4 border-white shadow-lg rounded-full hidden">
									<?php endif; ?>
								</div>
							</div>
							<div class="flex-1 text-center sm:text-left">
								<h3 class="text-lg font-semibold mb-2">Profile Picture</h3>
								<p class="text-sm text-gray-600 mb-4">Upload a clear photo</p>
								<div class="flex flex-col sm:flex-row items-center gap-3 justify-center sm:justify-start">
									<input type="file" id="profile-upload" name="profile_picture" accept="image/*" class="hidden">
									<label for="profile-upload" class="cursor-pointer inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">Choose Photo</label>
									<div class="text-xs text-gray-500 sm:ml-2">
										<div>JPEG/PNG/GIF up to 2MB</div>
										<div id="selected-file-name" class="text-gray-600"></div>
									</div>
								</div>
							</div>
						</div>

						<h3 class="text-lg font-semibold mb-4">Personal Information</h3>
						<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
							<div>
								<label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
								<input type="text" name="first_name" value="<?= esc($user['first_name'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50" disabled>
								<input type="hidden" name="first_name" value="<?= esc($user['first_name'] ?? '') ?>">
							</div>
							<div>
								<label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
								<input type="text" name="last_name" value="<?= esc($user['last_name'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50" disabled>
								<input type="hidden" name="last_name" value="<?= esc($user['last_name'] ?? '') ?>">
							</div>
							<div>
								<label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
								<input type="email" name="email" value="<?= esc($user['email'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
							</div>
							<div>
								<label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
								<input type="tel" name="phone" value="<?= esc($user['phone_number'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" inputmode="tel" autocomplete="tel">
							</div>
							<div>
								<label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
								<input type="date" name="birthdate" value="<?= esc($user['birthdate'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50" disabled>
								<input type="hidden" name="birthdate" value="<?= esc($user['birthdate'] ?? '') ?>">
							</div>
							<div>
								<label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
								<select name="gender" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50" disabled>
									<option value="">Select Gender</option>
									<option value="1" <?= ($user['sex'] ?? '') == '1' ? 'selected' : '' ?>>Male</option>
									<option value="2" <?= ($user['sex'] ?? '') == '2' ? 'selected' : '' ?>>Female</option>
								</select>
								<input type="hidden" name="gender" value="<?= esc($user['sex'] ?? '') ?>">
							</div>
						</div>

						<h3 class="text-lg font-semibold mb-4">Address Information</h3>
						<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
							<div class="md:col-span-2">
								<label class="block text-sm font-medium text-gray-700 mb-1">Street Address / Zone/Purok</label>
								<input type="text" name="street" value="<?= esc($address['zone_purok'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
							</div>
							<div>
								<label class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
								<input type="text" value="<?= esc($address['barangay'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50" disabled>
								<input type="hidden" name="barangay" value="<?= esc($address['barangay'] ?? '') ?>">
							</div>
							<div>
								<label class="block text-sm font-medium text-gray-700 mb-1">City</label>
								<input type="text" value="Iriga City" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50" disabled>
								<input type="hidden" name="city" value="Iriga City">
							</div>
							<div>
								<label class="block text-sm font-medium text-gray-700 mb-1">Province</label>
								<input type="text" value="Camarines Sur" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50" disabled>
								<input type="hidden" name="province" value="Camarines Sur">
							</div>
							<div>
								<label class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
								<input type="text" value="4431" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50" disabled>
								<input type="hidden" name="postal_code" value="4431">
							</div>
						</div>

						<div class="flex justify-end">
							<button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">Save Changes</button>
						</div>
					</form>
				</div>

				<div id="security-tab" class="tab-content hidden" role="tabpanel" aria-labelledby="tab-security">
					<div class="mb-8 pb-8 border-b border-gray-200">
						<h3 class="text-lg font-semibold mb-4">Change Password</h3>
						<form id="password-form" action="<?= base_url('pederasyon/account-settings/password') ?>" method="post">
							<?= csrf_field() ?>
							<div class="space-y-4 max-w-md">
								<div>
									<label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
									<div class="relative">
										<input type="password" id="current_password" name="current_password" class="w-full px-3 pr-10 py-2 border border-gray-300 rounded-md shadow-sm">
									</div>
								</div>
								<div>
									<label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
									<div class="relative">
										<input type="password" id="new_password" name="ped_password" class="w-full px-3 pr-10 py-2 border border-gray-300 rounded-md shadow-sm">
										<button type="button" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none" aria-label="Toggle new password visibility" data-toggle-password data-target="#new_password">
											<svg data-icon="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
												<path d="M2 12c2.5-4 6.5-7 10-7s7.5 3 10 7c-2.5 4-6.5 7-10 7s-7.5-3-10-7z"/>
												<circle cx="12" cy="12" r="2.5"/>
											</svg>
											<svg data-icon="hide" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
												<path d="M2 12c2.5-4 6.5-7 10-7s7.5 3 10 7c-2.5 4-6.5 7-10 7s-7.5-3-10-7z"/>
												<circle cx="12" cy="12" r="2.5"/>
												<path d="M4 4c5 5 11 11 16 16"/>
											</svg>
										</button>
									</div>
									<div id="password-requirements" class="mt-2 space-y-1 text-xs hidden">
										<div id="req-length" class="flex items-center space-x-2"><span class="requirement-icon">✗</span><span class="text-gray-600">At least 8 characters</span></div>
										<div id="req-uppercase" class="flex items-center space-x-2"><span class="requirement-icon">✗</span><span class="text-gray-600">One uppercase letter</span></div>
										<div id="req-lowercase" class="flex items-center space-x-2"><span class="requirement-icon">✗</span><span class="text-gray-600">One lowercase letter</span></div>
										<div id="req-number" class="flex items-center space-x-2"><span class="requirement-icon">✗</span><span class="text-gray-600">One number</span></div>
										<div id="req-special" class="flex items-center space-x-2"><span class="requirement-icon">✗</span><span class="text-gray-600">One special character</span></div>
									</div>
								</div>
								<div>
									<label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
									<div class="relative">
										<input type="password" id="confirm_password" name="confirm_password" class="w-full px-3 pr-10 py-2 border border-gray-300 rounded-md shadow-sm">
										<button type="button" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none" aria-label="Toggle confirm password visibility" data-toggle-password data-target="#confirm_password">
											<svg data-icon="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
												<path d="M2 12c2.5-4 6.5-7 10-7s7.5 3 10 7c-2.5 4-6.5 7-10 7s-7.5-3-10-7z"/>
												<circle cx="12" cy="12" r="2.5"/>
											</svg>
											<svg data-icon="hide" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
												<path d="M2 12c2.5-4 6.5-7 10-7s7.5 3 10 7c-2.5 4-6.5 7-10 7s-7.5-3-10-7z"/>
												<circle cx="12" cy="12" r="2.5"/>
												<path d="M4 4c5 5 11 11 16 16"/>
											</svg>
										</button>
									</div>
									<div id="confirm-password-error" class="mt-1 text-xs text-red-500 hidden">Passwords do not match</div>
									<div id="confirm-password-success" class="mt-1 text-xs text-green-500 hidden">Passwords match</div>
								</div>
								<div class="flex justify-end">
									<button type="submit" id="pwdSubmitButton" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md">Update Password</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</main>
</div>

<script>
// Toast notification utility (reused across pages)
if (typeof window.showNotification !== 'function') {
	window.showNotification = function(message, type = 'info') {
		let toastContainer = document.getElementById('toastContainer');
		if (!toastContainer) {
			toastContainer = document.createElement('div');
			toastContainer.id = 'toastContainer';
			toastContainer.className = 'fixed top-4 right-4 z-[100000] flex flex-col gap-2 items-end pointer-events-none';
			document.body.appendChild(toastContainer);
		} else {
			toastContainer.className = 'fixed top-4 right-4 z-[100000] flex flex-col gap-2 items-end pointer-events-none';
		}

		const n = document.createElement('div');
		n.className = 'pointer-events-auto p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full min-w-[280px] max-w-md break-words';
		switch(type) {
			case 'success': n.className += ' bg-green-500 text-white'; break;
			case 'error': n.className += ' bg-red-500 text-white'; break;
			case 'warning': n.className += ' bg-orange-500 text-white'; break;
			default: n.className += ' bg-blue-500 text-white';
		}
		let icon = '';
		switch(type) {
			case 'success': icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>'; break;
			case 'error': icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" /></svg>'; break;
			case 'warning': icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>'; break;
			default: icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01" /></svg>';
		}
		n.innerHTML = '<div class="flex items-center">' + icon + '<span class="mr-2">' + message + '</span><button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200 focus:outline-none"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></button></div>';
		document.body.appendChild(toastContainer);
		toastContainer.appendChild(n);
		setTimeout(() => { n.classList.remove('translate-x-full'); }, 100);
		setTimeout(() => { n.classList.add('translate-x-full'); setTimeout(() => { if (n.parentElement) n.remove(); }, 300); }, 5000);
	}
}

// Password visibility toggles
(() => {
	const handler = (btn) => {
		const targetSel = btn.getAttribute('data-target');
		if (!targetSel) return;
		const input = document.querySelector(targetSel);
		if (!input) return;
		const showIcon = btn.querySelector('[data-icon="show"]');
		const hideIcon = btn.querySelector('[data-icon="hide"]');
		const isPassword = input.type === 'password';
		input.type = isPassword ? 'text' : 'password';
		if (showIcon && hideIcon) {
			if (isPassword) { showIcon.classList.add('hidden'); hideIcon.classList.remove('hidden'); }
			else { hideIcon.classList.add('hidden'); showIcon.classList.remove('hidden'); }
		}
	};
	document.addEventListener('click', (e) => {
		const btn = e.target.closest('[data-toggle-password]');
		if (!btn) return;
		handler(btn);
	});
})();

(function() {
	const container = document.getElementById('account-settings');
	if (!container) return;
	const buttons = container.querySelectorAll('.tab-button');
	const tabs = container.querySelectorAll('.tab-content');

	function activate(btn) {
		buttons.forEach(b => {
			b.classList.remove('text-blue-600', 'border-blue-600');
			b.classList.add('text-gray-600', 'border-transparent');
			b.setAttribute('aria-selected', b === btn ? 'true' : 'false');
		});
		btn.classList.add('text-blue-600', 'border-blue-600');
		btn.classList.remove('text-gray-600', 'border-transparent');

		tabs.forEach(tc => tc.classList.add('hidden'));
		const tabId = btn.dataset.tab + '-tab';
		const panel = container.querySelector('#' + tabId);
		if (panel) panel.classList.remove('hidden');
	}

	buttons.forEach(btn => {
		btn.addEventListener('click', (e) => {
			e.preventDefault();
			activate(btn);
			// Persist selection via URL hash
			if (history.replaceState) {
				history.replaceState(null, '', '#' + btn.dataset.tab);
			} else {
				location.hash = btn.dataset.tab;
			}
		});
	});

	// Initialize active tab from hash
	const fromHash = (location.hash || '').replace('#', '');
	const initialBtn = Array.from(buttons).find(b => b.dataset.tab === fromHash) || buttons[0];
	if (initialBtn) activate(initialBtn);
})();

// Ensure the global header (if any) doesn't duplicate these flash toasts
window.__suppressFlashToast = true;
// Flash toasts from server-side
window.addEventListener('DOMContentLoaded', function() {
	<?php if (session()->getFlashdata('success')): ?>
		showNotification('<?= esc(session()->getFlashdata('success'), 'js') ?>', 'success');
	<?php endif; ?>
	<?php if (session()->getFlashdata('error')): ?>
		showNotification('<?= esc(session()->getFlashdata('error'), 'js') ?>', 'error');
	<?php endif; ?>
});

const input = document.getElementById('profile-upload');
if (input) {
	input.addEventListener('change', () => {
		const file = input.files && input.files[0] ? input.files[0] : null;
		const nameEl = document.getElementById('selected-file-name');
		if (nameEl) nameEl.textContent = file ? file.name : '';

		if (!file) return;
		if (!file.type.startsWith('image/')) {
			if (nameEl) nameEl.textContent = 'Please select a valid image file.';
			if (typeof showNotification === 'function') showNotification('Please select a valid image file.', 'error');
			input.value = '';
			return;
		}
		if (file.size > 2 * 1024 * 1024) {
			if (nameEl) nameEl.textContent = 'File too large (max 2MB).';
			if (typeof showNotification === 'function') showNotification('File too large (max 2MB).', 'error');
			input.value = '';
			return;
		}

		const img = document.getElementById('profile-image');
		const placeholder = document.getElementById('profile-placeholder');
		if (img) {
			const url = URL.createObjectURL(file);
			img.src = url;
			img.alt = 'Selected profile picture preview';
			img.classList.remove('hidden');
			if (placeholder) placeholder.classList.add('hidden');
			img.onload = () => URL.revokeObjectURL(url);
		}
	});
}

// Password form validation (JS-driven)
const pwdForm = document.getElementById('password-form');
if (pwdForm) {
	pwdForm.addEventListener('submit', function(e) {
		const cur = pwdForm.querySelector('input[name="current_password"]');
		// Select by id since name is role-specific (ped_password)
		const nw = document.getElementById('new_password');
		const conf = pwdForm.querySelector('input[name="confirm_password"]');

		[cur, nw, conf].forEach(el => el.classList.remove('border-red-500'));

		let ok = true;
		if (!cur.value) { cur.classList.add('border-red-500'); ok = false; }
		if (!nw.value || nw.value.length < 8) { nw.classList.add('border-red-500'); ok = false; }
		if (!conf.value || conf.value !== nw.value) { conf.classList.add('border-red-500'); ok = false; }

		if (!ok) {
			e.preventDefault();
			if (typeof showNotification === 'function') {
				showNotification('Please correct the highlighted fields. Password must be at least 8 characters and both new passwords must match.', 'error');
			}
		}
	});
		const newPassword = document.getElementById('new_password');
		const confirmPassword = document.getElementById('confirm_password');
		const submitButton = document.getElementById('pwdSubmitButton');
		const reqBox = document.getElementById('password-requirements');
		const reqs = {
			length: document.getElementById('req-length'),
			uppercase: document.getElementById('req-uppercase'),
			lowercase: document.getElementById('req-lowercase'),
			number: document.getElementById('req-number'),
			special: document.getElementById('req-special')
		};

		function updateReqs(pwd) {
			if (!reqBox) return;
			if (pwd && pwd.length) reqBox.classList.remove('hidden'); else reqBox.classList.add('hidden');
			const checks = {
				length: pwd.length >= 8,
				uppercase: /[A-Z]/.test(pwd),
				lowercase: /[a-z]/.test(pwd),
				number: /\d/.test(pwd),
				special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>?]/.test(pwd)
			};
			Object.keys(checks).forEach(k => {
				const el = reqs[k]; if (!el) return;
				const icon = el.querySelector('.requirement-icon');
				const text = el.querySelector('span:last-child');
				if (checks[k]) { icon.textContent = '✓'; icon.style.color = '#10b981'; text.style.color = '#10b981'; }
				else { icon.textContent = '✗'; icon.style.color = '#ef4444'; text.style.color = '#6b7280'; }
			});
			return checks;
		}

		function updateMatch() {
			const err = document.getElementById('confirm-password-error');
			const ok = document.getElementById('confirm-password-success');
			if (!confirmPassword || !newPassword) return;
			if (!confirmPassword.value) { 
				confirmPassword.classList.remove('border-red-500','border-green-500');
				err && err.classList.add('hidden');
				ok && ok.classList.add('hidden');
				toggleSubmit();
				return;
			}
			if (confirmPassword.value !== newPassword.value) {
				confirmPassword.classList.add('border-red-500');
				confirmPassword.classList.remove('border-green-500');
				err && err.classList.remove('hidden');
				ok && ok.classList.add('hidden');
			} else {
				confirmPassword.classList.remove('border-red-500');
				confirmPassword.classList.add('border-green-500');
				err && err.classList.add('hidden');
				ok && ok.classList.remove('hidden');
			}
			toggleSubmit();
		}

		function toggleSubmit() {
			if (!submitButton) return;
			const pwd = newPassword ? newPassword.value : '';
			const conf = confirmPassword ? confirmPassword.value : '';
			const checks = updateReqs(pwd) || {};
			const allOk = ['length','uppercase','lowercase','number','special'].every(k => checks[k]);
			const match = pwd && conf && pwd === conf;
			if (allOk && match) { submitButton.disabled = false; submitButton.classList.remove('bg-gray-400','cursor-not-allowed'); submitButton.classList.add('bg-blue-600','hover:bg-blue-700'); }
			else { submitButton.disabled = true; submitButton.classList.add('bg-gray-400','cursor-not-allowed'); submitButton.classList.remove('bg-blue-600','hover:bg-blue-700'); }
		}

		if (newPassword) newPassword.addEventListener('input', () => { updateReqs(newPassword.value); updateMatch(); });
		if (confirmPassword) confirmPassword.addEventListener('input', updateMatch);
		toggleSubmit();
}

	// Instant validation for Account Settings: email validity/uniqueness, PH phone (+63), and numeric-only zone
	(function initAccountSettingsValidation(){
		const form = document.querySelector('#profile-tab form');
		if (!form) return;

		function getOrMakeErrorEl(input, id){
			const existing = document.getElementById(id);
			if (existing) return existing;
			const el = document.createElement('p');
			el.id = id;
			el.className = 'mt-1 text-xs text-red-600 hidden';
			input.insertAdjacentElement('afterend', el);
			return el;
		}
		function showError(input, el, msg){
			input.classList.add('border-red-500');
			input.classList.remove('border-green-500');
			input.setAttribute('aria-invalid','true');
			if (el){ el.textContent = msg || ''; el.classList.remove('hidden'); }
		}
		function clearError(input, el){
			input.classList.remove('border-red-500');
			input.classList.add('border-green-500');
			input.removeAttribute('aria-invalid');
			if (el){ el.textContent = ''; el.classList.add('hidden'); }
		}

		// Capture initial signature for change detection
		function fieldSignature(){
			const els = form.querySelectorAll('input, select, textarea');
			const parts = [];
			els.forEach((el, idx)=>{
				const type = (el.type||'').toLowerCase();
				if (type === 'submit' || type === 'button' || type === 'file') return;
				let val = '';
				if (type === 'checkbox' || type === 'radio'){
					val = el.checked ? (el.value||'1') : '';
				} else if (el.id === 'phone' || el.id === 'phone_number' || el.name === 'phone' || el.name === 'phone_number'){
					val = normalizePhone(el.value||'');
				} else {
					val = (el.value||'').trim();
				}
				const key = (el.name || el.id || ('idx'+idx));
				parts.push(key+'='+val);
			});
			return parts.sort().join('|');
		}
		const initialSig = fieldSignature();

		// Email validation + optional uniqueness check (debounced)
		const emailInput = form.querySelector('input[type="email"], input[name="email"], #email');
		const initialEmail = emailInput ? (emailInput.value || '') : '';
		const emailErr = emailInput ? getOrMakeErrorEl(emailInput, 'email-error') : null;
		const EMAIL_CHECK_URL = '<?= base_url('pederasyon/account-settings/check-email') ?>';
		const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/i;
		let emailTimer;
		async function isEmailAvailable(email){
			if (!emailRegex.test(email) || email === initialEmail) return true;
			try{
				const res = await fetch(EMAIL_CHECK_URL + '?email=' + encodeURIComponent(email), { headers: { 'X-Requested-With':'XMLHttpRequest' } });
				if (!res.ok) return true;
				const data = await res.json().catch(()=>null);
				if (data && typeof data.available === 'boolean') return data.available;
				return true;
			}catch{ return true; }
		}
		function validateEmail(){
			if (!emailInput) return true;
			const v = (emailInput.value || '').trim();
			if (!v){ showError(emailInput, emailErr, 'Email is required.'); return false; }
			if (!emailRegex.test(v)){ showError(emailInput, emailErr, 'Please enter a valid email address.'); return false; }
			clearError(emailInput, emailErr);
			clearTimeout(emailTimer);
			emailTimer = setTimeout(async ()=>{
				const available = await isEmailAvailable(v);
				if (!available){ showError(emailInput, emailErr, 'This email is already in use.'); if (typeof showNotification==='function'){ showNotification('Email already in use.', 'error'); } }
			}, 350);
			return true;
		}
		if (emailInput){
			emailInput.addEventListener('input', validateEmail);
			emailInput.addEventListener('blur', validateEmail);
		}

		// Phone number: enforce +63 and exactly 10 digits after, reject leading 0 and >10 digits
		const phoneInput = form.querySelector('#phone, #phone_number, input[name="phone"], input[name="phone_number"]');
		const phoneErr = phoneInput ? getOrMakeErrorEl(phoneInput, 'phone-error-inline') : null;
		function normalizePhone(raw){
			let digits = String(raw || '').replace(/\D/g,'');
			if (digits.startsWith('63')) digits = digits.slice(2);
			if (digits.startsWith('0')) digits = digits.slice(1);
			digits = digits.slice(0,10);
			return '+63' + digits;
		}
		function validatePhone(){
			if (!phoneInput) return true;
			const val = phoneInput.value || '';
			const normalized = normalizePhone(val);
			if (normalized !== val) phoneInput.value = normalized;
			const digits = normalized.replace(/\D/g,'').slice(2);
			if (!digits){ showError(phoneInput, phoneErr, 'Phone number is required.'); return false; }
			if (digits.length !== 10){ showError(phoneInput, phoneErr, 'Phone must be +63 followed by 10 digits.'); return false; }
			clearError(phoneInput, phoneErr);
			return true;
		}
		if (phoneInput){
			if (!phoneInput.value){ phoneInput.value = '+63'; }
			phoneInput.addEventListener('input', validatePhone);
			phoneInput.addEventListener('blur', validatePhone);
		}

		// Zone numeric-only
		const zoneInput = form.querySelector('#zone, input[name="zone"], input[name="zone_purok"], input[name="purok"], input[name="zoneNo"], input[name="zone_no"]');
		const zoneErr = zoneInput ? getOrMakeErrorEl(zoneInput, 'zone-error') : null;
		function validateZone(){
			if (!zoneInput) return true;
			const before = zoneInput.value || '';
			const after = before.replace(/\D/g,'');
			if (after !== before) zoneInput.value = after;
			if (after && !/^\d+$/.test(after)){ showError(zoneInput, zoneErr, 'Zone must be numbers only.'); return false; }
			clearError(zoneInput, zoneErr);
			return true;
		}
		if (zoneInput){
			zoneInput.addEventListener('input', validateZone);
			zoneInput.addEventListener('blur', validateZone);
		}

		form.addEventListener('submit', async (e)=>{
			// Required fields quick check
			const missing = [];
			const vEmail = emailInput ? (emailInput.value||'').trim() : '';
			if (!vEmail) missing.push('Email');
			const phoneDigits = phoneInput ? (normalizePhone(phoneInput.value||'').replace(/\D/g,'').slice(2)) : '';
			if (!phoneDigits) missing.push('Phone');
			if (missing.length){
				e.preventDefault();
				if (typeof showNotification==='function') showNotification('Please fill in: '+missing.join(', ')+'.', 'warning');
				return;
			}

			// No-change guard (allow submit if a file was selected)
			const currentSig = fieldSignature();
			const fileChanged = !!(form.querySelector('input[type="file"][name="profile_picture"]')?.files?.length);
			if (currentSig === initialSig && !fileChanged){
				e.preventDefault();
				if (typeof showNotification==='function') showNotification('No changes to save.', 'info');
				return;
			}

			const okEmail = validateEmail();
			const okPhone = validatePhone();
			const okZone = validateZone();
			let okUnique = true;
			if (emailInput){
				const v = (emailInput.value||'').trim();
				if (emailRegex.test(v) && v !== initialEmail){
					try{ okUnique = await isEmailAvailable(v); }catch{ okUnique = true; }
					if (!okUnique){ showError(emailInput, emailErr, 'This email is already in use.'); }
				}
			}
			if (!(okEmail && okPhone && okZone && okUnique)){
				e.preventDefault();
				if (typeof showNotification==='function') showNotification('Please fix the highlighted fields before saving.', 'error');
			}
		});
	})();
</script>
