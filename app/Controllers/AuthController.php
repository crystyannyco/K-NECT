<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserExtInfoModel;
use App\Models\AddressModel;

class AuthController extends BaseController
{
    public function loginProcess()
    {
        $userExtInfoModel = new UserExtInfoModel();
        $userModel = new UserModel();

        $login = $this->request->getPost('login');
        $password = $this->request->getPost('password');
        $isAjax = $this->request->isAJAX();

        // Sanitize input to prevent any potential issues
        $login = trim($login ?? '');
        $password = trim($password ?? '');
        
        // Basic validation
        if (empty($login) || empty($password)) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'type' => 'validation',
                    'message' => 'Username and password are required.'
                ]);
            } else {
                return redirect()->to('login')->with('error', 'Username and password are required.');
            }
        }

        log_message('info', 'Login attempt - User: ' . $login . ', Method: ' . ($isAjax ? 'AJAX' : 'Form'));

        try {
            // Try KK Member (username/email + password)
            $user = $userModel->where('username', $login)->orWhere('email', $login)->first();
        if ($user && isset($user['password']) && password_verify($password, $user['password'])) {
            // Check if user is active (is_active = 1 only)
            if (isset($user['is_active']) && $user['is_active'] != 1) {
                $inactiveMessage = '';
                if ($user['is_active'] == 2) {
                    $inactiveMessage = 'Your account has been deactivated because you are 31 years old or above.';
                } elseif ($user['is_active'] == 3) {
                    $inactiveMessage = 'Your account has been deactivated due to inactivity for more than 1 year.';
                } elseif ($user['is_active'] == 4) {
                    $inactiveMessage = 'Your account has been deactivated due to special circumstances.';
                } elseif ($user['is_active'] == 5) {
                    $inactiveMessage = 'Your account has been manually deactivated. Please contact administrator.';
                } else {
                    $inactiveMessage = 'Your account has been deactivated. Please contact administrator.';
                }
                
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'type' => 'inactive',
                        'message' => $inactiveMessage
                    ]);
                } else {
                    return redirect()->to('login')->with('error', $inactiveMessage);
                }
            }
            
            if ($user['status'] == 3) {
                $extInfo = $userExtInfoModel->where('user_id', $user['id'])->first();
                $reason = isset($extInfo['reason']) && $extInfo['reason'] !== '' ? $extInfo['reason'] : 'No reason provided.';
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'type' => 'rejected',
                        'message' => 'Your account has been rejected.',
                        'reason' => $reason,
                        'user_id' => $user['id']
                    ]);
                } else {
                    return redirect()->to('login')->with('error', 'Your account has been rejected. Reason: ' . $reason);
                }
            }
            if ($user['status'] == 1) {
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'type' => 'pending',
                        'message' => 'Your account is not yet approved. Please wait for approval.'
                    ]);
                } else {
                    return redirect()->to('login')->with('error', 'Your account is not yet approved.');
                }
            } else if ($user['status'] == 2) {
                $session = session();
                $session->set('user_id', $user['user_id']); // Use the permanent user_id field
                $session->set('username', $user['username']);
                $session->set('user_type', 'kk'); // Set user type for identification
                
                // Get user's barangay information for KK members  
                $addressModel = new AddressModel();
                $address = $addressModel->where('user_id', $user['id'])->first();
                if ($address) {
                    $session->set('barangay_id', $address['barangay']);
                }
                
                // Set role based on user_type for backward compatibility
                $session->set('role', 'user'); // KK = User (user_type 1)
                
                // Update last_login timestamp using Philippine Time
                date_default_timezone_set('Asia/Manila');
                $userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
                
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => true,
                        'redirect' => base_url('kk/dashboard')
                    ]);
                } else {
                    return redirect()->to('kk/dashboard');
                }
            }
        }

        // Try SK Official (sk_username + sk_password)
        $user = $userModel->where('sk_username', $login)->first();
        if ($user && isset($user['sk_password'])) {
            // Check if user has SK access (user_type 2 or 3)
            if (!in_array($user['user_type'], [2, 3])) {
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'type' => 'unauthorized',
                        'message' => 'You do not have access to the SK system. Please contact administrator.'
                    ]);
                } else {
                    return redirect()->to('login')->with('error', 'You do not have access to the SK system. Please contact administrator.');
                }
            }
            
            $skPassword = $user['sk_password'];
            $isHashed = strlen($skPassword) === 60 && preg_match('/^\$2y\$/', $skPassword); // bcrypt hash check
            $valid = false;
            if ($isHashed) {
                $valid = password_verify($password, $skPassword);
            } else {
                $valid = ($password === $skPassword);
            }
            if ($valid) {
                // Check if user is active (is_active = 1)
                if (isset($user['is_active']) && $user['is_active'] != 1) {
                    $inactiveMessage = '';
                    if ($user['is_active'] == 2) {
                        $inactiveMessage = 'Your account has been deactivated because you are 31 years old or above.';
                    } elseif ($user['is_active'] == 3) {
                        $inactiveMessage = 'Your account has been deactivated due to inactivity for more than 1 year.';
                    } else {
                        $inactiveMessage = 'Your account has been deactivated. Please contact administrator.';
                    }
                    
                    if ($isAjax) {
                        return $this->response->setJSON([
                            'success' => false,
                            'type' => 'inactive',
                            'message' => $inactiveMessage
                        ]);
                    } else {
                        return redirect()->to('login')->with('error', $inactiveMessage);
                    }
                }
                
                // Check if password needs to be changed (not hashed yet)
                if (!$isHashed) {
                    // Store temporary session data for password change
                    $session = session();
                    $session->setTempdata('temp_user_id', $user['id'], 300); // 5 minutes
                    $session->setTempdata('temp_user_type', 'sk', 300);
                    $session->setTempdata('temp_username', $user['sk_username'], 300);
                    $session->setTempdata('temp_permanent_id', $user['user_id'], 300);
                    
                    // Get user's barangay information for SK officials
                    $addressModel = new AddressModel();
                    $address = $addressModel->where('user_id', $user['id'])->first();
                    if ($address) {
                        $session->setTempdata('temp_sk_barangay', $address['barangay'], 300);
                    }
                    
                    if ($isAjax) {
                        return $this->response->setJSON([
                            'success' => true,
                            'redirect' => base_url('change-password')
                        ]);
                    } else {
                        return redirect()->to('change-password');
                    }
                }
                
                if ($user['status'] == 3) {
                    $extInfo = $userExtInfoModel->where('user_id', $user['id'])->first();
                    $reason = isset($extInfo['reason']) && $extInfo['reason'] !== '' ? $extInfo['reason'] : 'No reason provided.';
                    if ($isAjax) {
                        return $this->response->setJSON([
                            'success' => false,
                            'type' => 'rejected',
                            'message' => 'Your account has been rejected.',
                            'reason' => $reason,
                            'user_id' => $user['id']
                        ]);
                    } else {
                        return redirect()->to('login')->with('error', 'Your account has been rejected. Reason: ' . $reason);
                    }
                }
                if ($user['status'] == 1) {
                    if ($isAjax) {
                        return $this->response->setJSON([
                            'success' => false,
                            'type' => 'pending',
                            'message' => 'Your account is not yet approved. Please wait for approval.'
                        ]);
                    } else {
                        return redirect()->to('login')->with('error', 'Your account is not yet approved.');
                    }
                } else if ($user['status'] == 2) {
                    $session = session();
                    $session->set('user_id', $user['user_id']); // Use the permanent user_id field
                    $session->set('username', $user['sk_username']);
                    $session->set('user_type', 'sk'); // Set user type for identification
                    
                    // Get user's barangay information for SK officials
                    $addressModel = new AddressModel();
                    $address = $addressModel->where('user_id', $user['id'])->first();
                    if ($address) {
                        $session->set('sk_barangay', $address['barangay']);
                        // Also set for easier access in EventController
                        $session->set('barangay_id', $address['barangay']);
                    }
                    
                    // Set role based on user_type for backward compatibility
                    $session->set('role', 'admin'); // SK = Admin (user_type 2)
                    
                    // Update last_login timestamp using Philippine Time
                    date_default_timezone_set('Asia/Manila');
                    $userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
                    
                    if ($isAjax) {
                        return $this->response->setJSON([
                            'success' => true,
                            'redirect' => base_url('sk/dashboard')  // Redirect SK Officials to dashboard
                        ]);
                    } else {
                        return redirect()->to('sk/dashboard');  // Redirect SK Officials to dashboard
                    }
                } else {
                    // Handle other status values
                    if ($isAjax) {
                        return $this->response->setJSON([
                            'success' => false,
                            'type' => 'invalid_status',
                            'message' => 'Your account status is invalid. Please contact administrator.'
                        ]);
                    } else {
                        return redirect()->to('login')->with('error', 'Your account status is invalid. Please contact administrator.');
                    }
                }
            }
        }

        // Try Pederasyon Officer (ped_username + ped_password)
        $user = $userModel->where('ped_username', $login)->first();
        
        // If no exact match, try case-insensitive search
        if (!$user) {
            $user = $userModel->where('LOWER(ped_username)', strtolower($login))->first();
        }
        
        if ($user && isset($user['ped_password'])) {
            log_message('info', 'Pederasyon user found - ID: ' . $user['id'] . ', Type: ' . $user['user_type'] . ', Status: ' . $user['status']);
            log_message('error', 'User found with ped_password. User ID: ' . $user['id'] . ', User Type: ' . $user['user_type']);
            
            // Check if user has Pederasyon access (user_type 3 only)
            if ($user['user_type'] != 3) {
                log_message('warning', 'Pederasyon access denied - User type: ' . $user['user_type']);
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'type' => 'unauthorized',
                        'message' => 'You do not have access to the Pederasyon system. Please contact administrator.'
                    ]);
                } else {
                    return redirect()->to('login')->with('error', 'You do not have access to the Pederasyon system. Please contact administrator.');
                }
            }
            
            $pedPassword = $user['ped_password'];
            $isHashed = strlen($pedPassword) === 60 && preg_match('/^\$2y\$/', $pedPassword); // bcrypt hash check
            
            $valid = false;
            if ($isHashed) {
                $valid = password_verify($password, $pedPassword);
            } else {
                $valid = ($password === $pedPassword);
            }
            
            if ($valid) {
                log_message('info', 'Pederasyon authentication successful - Hashed: ' . ($isHashed ? 'Yes' : 'No'));
                
                // Check if user is active (is_active = 1)
                if (isset($user['is_active']) && $user['is_active'] != 1) {
                    $inactiveMessage = '';
                    if ($user['is_active'] == 2) {
                        $inactiveMessage = 'Your account has been deactivated because you are 31 years old or above.';
                    } elseif ($user['is_active'] == 3) {
                        $inactiveMessage = 'Your account has been deactivated due to inactivity for more than 1 year.';
                    } else {
                        $inactiveMessage = 'Your account has been deactivated. Please contact administrator.';
                    }
                    
                    if ($isAjax) {
                        return $this->response->setJSON([
                            'success' => false,
                            'type' => 'inactive',
                            'message' => $inactiveMessage
                        ]);
                    } else {
                        return redirect()->to('login')->with('error', $inactiveMessage);
                    }
                }
                
                // Check if password needs to be changed (not hashed yet)
                if (!$isHashed) {
                    log_message('info', 'Redirecting to change password - temporary password detected');
                    // Store temporary session data for password change
                    $session = session();
                    $session->setTempdata('temp_user_id', $user['id'], 300); // 5 minutes
                    $session->setTempdata('temp_user_type', 'pederasyon', 300);
                    $session->setTempdata('temp_username', $user['ped_username'], 300);
                    $session->setTempdata('temp_permanent_id', $user['user_id'], 300);
                    
                    if ($isAjax) {
                        return $this->response->setJSON([
                            'success' => true,
                            'redirect' => base_url('change-password')
                        ]);
                    } else {
                        return redirect()->to('change-password');
                    }
                }
                if ($user['status'] == 3) {
                    $extInfo = $userExtInfoModel->where('user_id', $user['id'])->first();
                    $reason = isset($extInfo['reason']) && $extInfo['reason'] !== '' ? $extInfo['reason'] : 'No reason provided.';
                    if ($isAjax) {
                        return $this->response->setJSON([
                            'success' => false,
                            'type' => 'rejected',
                            'message' => 'Your account has been rejected.',
                            'reason' => $reason,
                            'user_id' => $user['id']
                        ]);
                    } else {
                        return redirect()->to('login')->with('error', 'Your account has been rejected. Reason: ' . $reason);
                    }
                }
                if ($user['status'] == 1) {
                    if ($isAjax) {
                        return $this->response->setJSON([
                            'success' => false,
                            'type' => 'pending',
                            'message' => 'Your account is not yet approved. Please wait for approval.'
                        ]);
                    } else {
                        return redirect()->to('login')->with('error', 'Your account is not yet approved.');
                    }
                } else if ($user['status'] == 2) {
                    $session = session();
                    $session->set('user_id', $user['user_id']); // Use the permanent user_id field
                    $session->set('username', $user['ped_username']);
                    $session->set('user_type', 'pederasyon'); // Set user type for identification
                    
                    // Set role based on user_type for backward compatibility
                    $session->set('role', 'super_admin'); // Pederasyon = Super Admin (user_type 3)
                    
                    // Update last_login timestamp using Philippine Time
                    date_default_timezone_set('Asia/Manila');
                    $userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
                    
                    if ($isAjax) {
                        return $this->response->setJSON([
                            'success' => true,
                            'redirect' => base_url('pederasyon/dashboard')
                        ]);
                    } else {
                        return redirect()->to('pederasyon/dashboard');
                    }
                } else {
                    // Handle other status values
                    if ($isAjax) {
                        return $this->response->setJSON([
                            'success' => false,
                            'type' => 'invalid_status',
                            'message' => 'Your account status is invalid. Please contact administrator.'
                        ]);
                    } else {
                        return redirect()->to('login')->with('error', 'Your account status is invalid. Please contact administrator.');
                    }
                }
            }
        }

        // If all authentication methods fail
        log_message('warning', 'Authentication failed for user: ' . $login);
        
        if ($isAjax) {
            return $this->response->setJSON([
                'success' => false,
                'type' => 'invalid',
                'message' => 'Invalid username or password.'
            ]);
        } else {
            return redirect()->to('login')->with('error', 'Invalid username or password.');
        }
        
        } catch (\Exception $e) {
            log_message('error', 'Login process error: ' . $e->getMessage());
            log_message('error', 'Error occurred for user: ' . $login);
            
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'type' => 'error',
                    'message' => 'An error occurred during login. Please try again.'
                ]);
            } else {
                return redirect()->to('login')->with('error', 'An error occurred during login. Please try again.');
            }
        }
    }

    public function logout()
    {
        $session = session();

        // Destroy the session completely
        $session->destroy();

        // Set response headers to prevent caching
        $this->response->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
        $this->response->setHeader('Pragma', 'no-cache');
        $this->response->setHeader('Expires', '0');

        // Redirect to login with success message
        return redirect()->to('login')->with('success', 'You have been logged out successfully.');
    }

    public function changePassword()
    {
        $session = session();
        
        // Check if user has temporary session data
        if (!$session->getTempdata('temp_user_id') || !$session->getTempdata('temp_user_type')) {
            return redirect()->to('login')->with('error', 'Session expired. Please login again.');
        }
        
        $data = [
            'user_type' => $session->getTempdata('temp_user_type'),
            'username' => $session->getTempdata('temp_username')
        ];
        
        return $this->loadView('K-NECT/change_password', $data);
    }

    public function changePasswordProcess()
    {
        $session = session();
        $userModel = new UserModel();
        
        // Check if user has temporary session data
        if (!$session->getTempdata('temp_user_id') || !$session->getTempdata('temp_user_type')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Session expired. Please login again.'
                ]);
            }
            return redirect()->to('login')->with('error', 'Session expired. Please login again.');
        }
        
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');
        $isAjax = $this->request->isAJAX();
        
        // Validation
        if (empty($newPassword) || empty($confirmPassword)) {
            $message = 'All fields are required.';
            if ($isAjax) {
                return $this->response->setJSON(['success' => false, 'message' => $message]);
            }
            return redirect()->back()->with('error', $message);
        }
        
        if (strlen($newPassword) < 8) {
            $message = 'Password must be at least 8 characters long.';
            if ($isAjax) {
                return $this->response->setJSON(['success' => false, 'message' => $message]);
            }
            return redirect()->back()->with('error', $message);
        }
        
        // Strong password validation
        $passwordChecks = [
            'uppercase' => preg_match('/[A-Z]/', $newPassword),
            'lowercase' => preg_match('/[a-z]/', $newPassword),
            'number' => preg_match('/\d/', $newPassword),
            'special' => preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>?]/', $newPassword)
        ];
        
        $failedChecks = [];
        if (!$passwordChecks['uppercase']) $failedChecks[] = 'at least one uppercase letter';
        if (!$passwordChecks['lowercase']) $failedChecks[] = 'at least one lowercase letter';
        if (!$passwordChecks['number']) $failedChecks[] = 'at least one number';
        if (!$passwordChecks['special']) $failedChecks[] = 'at least one special character';
        
        if (!empty($failedChecks)) {
            $message = 'Password must contain: ' . implode(', ', $failedChecks) . '.';
            if ($isAjax) {
                return $this->response->setJSON(['success' => false, 'message' => $message]);
            }
            return redirect()->back()->with('error', $message);
        }
        
        if ($newPassword !== $confirmPassword) {
            $message = 'Passwords do not match.';
            if ($isAjax) {
                return $this->response->setJSON(['success' => false, 'message' => $message]);
            }
            return redirect()->back()->with('error', $message);
        }
        
        // Get temporary data
        $tempUserId = $session->getTempdata('temp_user_id');
        $tempUserType = $session->getTempdata('temp_user_type');
        $tempUsername = $session->getTempdata('temp_username');
        $tempPermanentId = $session->getTempdata('temp_permanent_id');
        $tempSkBarangay = $session->getTempdata('temp_sk_barangay');
        
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Set Philippine Time for accurate timestamps
        date_default_timezone_set('Asia/Manila');
        
        // Update the password based on user type
        if ($tempUserType === 'sk') {
            $userModel->update($tempUserId, ['sk_password' => $hashedPassword, 'last_login' => date('Y-m-d H:i:s')]);
            $dashboardUrl = 'sk/dashboard';  // Redirect SK Officials to dashboard
        } else if ($tempUserType === 'pederasyon') {
            $userModel->update($tempUserId, ['ped_password' => $hashedPassword, 'last_login' => date('Y-m-d H:i:s')]);
            $dashboardUrl = 'pederasyon/dashboard';
        }
        
        // Clear all temporary data and session (log out user)
        $session->removeTempdata('temp_user_id');
        $session->removeTempdata('temp_user_type');
        $session->removeTempdata('temp_username');
        $session->removeTempdata('temp_permanent_id');
        $session->removeTempdata('temp_sk_barangay');
        
        // Destroy the session completely to log out the user
        $session->destroy();
        
        if ($isAjax) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Password changed successfully! Please log in with your new password.',
                'redirect' => base_url('login')
            ]);
        }
        
        return redirect()->to('login')->with('success', 'Password changed successfully! Please log in with your new password.');
    }

    /**
     * Display forgot password form
     */
    public function forgotPassword()
    {
        return $this->loadView('K-NECT/forgot-password');
    }

    /**
     * Verify username and determine account type
     */
    public function verifyUsername()
    {
        $userModel = new UserModel();
        $username = $this->request->getPost('username');
        $isAjax = $this->request->isAJAX();

        // Validate username
        if (empty($username)) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Username is required.'
                ]);
            }
            return redirect()->back()->with('error', 'Username is required.');
        }

        $username = trim($username);
        $user = null;
        $accountType = null;
        $accountTypeLabel = null;

        // Check KK Member (username field)
        $user = $userModel->where('username', $username)->first();
        if ($user) {
            $accountType = 'kk';
            $accountTypeLabel = 'KK (Katipunan ng Kabataan)';
        }

        // Check SK Official (sk_username field)
        if (!$user) {
            $user = $userModel->where('sk_username', $username)->first();
            if ($user) {
                $accountType = 'sk';
                $accountTypeLabel = 'SK (Sangguniang Kabataan)';
            }
        }

        // Check Pederasyon Officer (ped_username field)
        if (!$user) {
            $user = $userModel->where('ped_username', $username)->first();
            if ($user) {
                $accountType = 'pederasyon';
                $accountTypeLabel = 'Pederasyon';
            }
        }

        // If no user found
        if (!$user) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Username not found. Please check and try again.'
                ]);
            }
            return redirect()->back()->with('error', 'Username not found. Please check and try again.');
        }

        // Username verified successfully
        if ($isAjax) {
            // include full name for display in UI
            $fullName = isset($user['first_name']) || isset($user['last_name']) ? trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) : '';
            return $this->response->setJSON([
                'success' => true,
                'account_type' => $accountType,
                'account_type_label' => $accountTypeLabel,
                'full_name' => $fullName,
                'message' => 'Username verified. Please enter your registered email.'
            ]);
        }

        return redirect()->back()->with('success', 'Username verified. Please enter your registered email.');
    }

    /**
     * Choose verification method (SMS or Email)
     */
    public function chooseVerificationMethod()
    {
        $username = $this->request->getPost('username');
        $accountType = $this->request->getPost('account_type');
        $method = $this->request->getPost('method'); // 'sms' or 'email'
        $isAjax = $this->request->isAJAX();

        // Validate inputs
        if (empty($username) || empty($accountType) || empty($method)) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Missing required information.'
                ]);
            }
            return redirect()->back()->with('error', 'Missing required information.');
        }

        // Validate method
        if (!in_array($method, ['sms', 'email'])) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid verification method selected.'
                ]);
            }
            return redirect()->back()->with('error', 'Invalid verification method selected.');
        }

        // Store method in session for next step
        session()->set('password_reset_method', $method);
        session()->set('password_reset_username', $username);
        session()->set('password_reset_account_type', $accountType);

        if ($isAjax) {
            return $this->response->setJSON([
                'success' => true,
                'method' => $method,
                'message' => 'Verification method selected. Please enter your ' . ($method === 'sms' ? 'phone number' : 'email address') . '.'
            ]);
        }

        return redirect()->back()->with('success', 'Verification method selected.');
    }

    /**
     * Verify contact information (email or phone number)
     */
    public function verifyContactInfo()
    {
        helper('otp');
        $userModel = new UserModel();
        
        $username = $this->request->getPost('username');
        $accountType = $this->request->getPost('account_type');
        $method = $this->request->getPost('method');
        $contactInfo = trim($this->request->getPost('contact_info'));
        $isAjax = $this->request->isAJAX();

        // Validate inputs
        if (empty($username) || empty($accountType) || empty($method) || empty($contactInfo)) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'All fields are required.'
                ]);
            }
            return redirect()->back()->with('error', 'All fields are required.');
        }

        // Find user based on account type
        $user = null;
        switch ($accountType) {
            case 'kk':
                $user = $userModel->where('username', $username)->first();
                break;
            case 'sk':
                $user = $userModel->where('sk_username', $username)->first();
                break;
            case 'pederasyon':
                $user = $userModel->where('ped_username', $username)->first();
                break;
        }

        if (!$user) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Account not found.'
                ]);
            }
            return redirect()->back()->with('error', 'Account not found.');
        }

        // Verify contact info matches
        if ($method === 'email') {
            // Validate email format
            if (!filter_var($contactInfo, FILTER_VALIDATE_EMAIL)) {
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Please enter a valid email address.'
                    ]);
                }
                return redirect()->back()->with('error', 'Please enter a valid email address.');
            }

            if (strtolower($user['email']) !== strtolower($contactInfo)) {
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'The email address does not match our records.'
                    ]);
                }
                return redirect()->back()->with('error', 'The email address does not match our records.');
            }

            $maskedContact = mask_email($contactInfo);
        } else { // SMS
            // Validate phone number format
            $cleanInput = preg_replace('/[^0-9]/', '', $contactInfo);
            
            // Must be exactly 10 digits
            if (strlen($cleanInput) !== 10) {
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Phone number must be exactly 10 digits.'
                    ]);
                }
                return redirect()->back()->with('error', 'Phone number must be exactly 10 digits.');
            }
            
            // Must start with 9 (not 0)
            if (!preg_match('/^9/', $cleanInput)) {
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Phone number must start with 9 (e.g., 9171234567).'
                    ]);
                }
                return redirect()->back()->with('error', 'Phone number must start with 9.');
            }
            
            // Normalize stored phone number for comparison
            $cleanStored = preg_replace('/[^0-9]/', '', $user['phone_number']);
            
            // Remove leading +63 or 63 from stored number if present
            if (preg_match('/^63/', $cleanStored)) {
                $cleanStored = substr($cleanStored, 2);
            }
            
            // Remove leading 0 from input or stored if present
            $cleanInput = ltrim($cleanInput, '0');
            $cleanStored = ltrim($cleanStored, '0');

            if ($cleanInput !== $cleanStored) {
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'The phone number does not match the registered number.'
                    ]);
                }
                return redirect()->back()->with('error', 'The phone number does not match the records.');
            }

            $maskedContact = mask_phone_number('+63' . $cleanInput);
        }

        // Store verified contact info and password reset data in session
        // For SMS, store with +63 prefix for sending
        $contactToStore = $method === 'sms' ? '+63' . $cleanInput : $contactInfo;
        
        session()->set([
            'password_reset_contact' => $contactToStore,
            'password_reset_user_id' => $user['id'],
            'password_reset_method' => $method,
            'password_reset_username' => $username,
            'password_reset_account_type' => $accountType
        ]);

        if ($isAjax) {
            return $this->response->setJSON([
                'success' => true,
                'masked_contact' => $maskedContact,
                'method' => $method,
                'message' => 'Contact information verified. Ready to send OTP.'
            ]);
        }

        return redirect()->back()->with('success', 'Contact information verified.');
    }

    /**
     * Send OTP via SMS or Email
     */
    public function sendOtp()
    {
        helper(['otp', 'sms']);
        $userModel = new UserModel();
        $userOtpModel = new \App\Models\UserOtpModel();
        
        $userId = session('password_reset_user_id');
        $method = session('password_reset_method');
        $contactInfo = session('password_reset_contact');
        $username = session('password_reset_username');
        $accountType = session('password_reset_account_type');
        $isAjax = $this->request->isAJAX();

        // Validate session data
        if (!$userId || !$method || !$contactInfo) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Session expired. Please start over.'
                ]);
            }
            return redirect()->to('forgot-password')->with('error', 'Session expired. Please start over.');
        }

        $user = $userModel->find($userId);
        if (!$user) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not found.'
                ]);
            }
            return redirect()->to('forgot-password')->with('error', 'User not found.');
        }

        // Check rate limiting using user_otp table
        $existingOtp = $userOtpModel->getLatestOtpForUser($userId);
        $lastRequest = $existingOtp['otp_last_request'] ?? null;
        $rateLimit = check_otp_rate_limit($lastRequest, 34);
        if (!$rateLimit['allowed']) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Please wait ' . $rateLimit['remainingSeconds'] . ' seconds before requesting another OTP.',
                    'remainingSeconds' => $rateLimit['remainingSeconds']
                ]);
            }
            return redirect()->back()->with('error', 'Please wait before requesting another OTP.');
        }

        // Generate OTP
        $otp = generate_otp();
        $otpHash = hash_otp($otp);
        $expiresAt = get_otp_expiry_time(5); // 5 minutes

        // Save OTP to user_otp table
        date_default_timezone_set('Asia/Manila');
        $userOtpModel->upsertOtp($userId, [
            'otp_code' => $otpHash,
            'otp_expires_at' => $expiresAt,
            'otp_type' => $method,
            'otp_verified' => 0,
            'otp_attempts' => 0,
            'otp_last_request' => date('Y-m-d H:i:s')
        ]);

        // Send OTP
        $sendSuccess = false;
        $errorMessage = '';

        try {
            if ($method === 'sms') {
                $result = send_otp_sms($contactInfo, $otp);
                $sendSuccess = $result && !isset($result['error']);
                if (!$sendSuccess) {
                    $errorMessage = isset($result['error']) ? $result['error'] : 'Failed to send SMS';
                }
            } else { // email
                $accountTypeLabel = $this->getAccountTypeLabel($accountType);
                $userName = $user['first_name'] . ' ' . $user['last_name'];
                $sendSuccess = send_otp_email($contactInfo, $otp, $userName, $accountTypeLabel);
                if (!$sendSuccess) {
                    $errorMessage = 'Failed to send email';
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'OTP sending error: ' . $e->getMessage());
            $errorMessage = 'An error occurred while sending OTP';
        }

        if ($sendSuccess) {
            log_message('info', "OTP sent via {$method} to user ID: {$userId}");
            
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'OTP has been sent to your ' . ($method === 'sms' ? 'phone' : 'email') . '. Please check and enter the code.',
                    'method' => $method
                ]);
            }
            return redirect()->to('verify-otp')->with('success', 'OTP has been sent.');
        } else {
            log_message('error', "Failed to send OTP via {$method} to user ID: {$userId}. Error: {$errorMessage}");
            
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to send OTP. Please try again or contact support.'
                ]);
            }
            return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
        }
    }

    /**
     * Verify OTP code entered by user
     */
    public function verifyOtp()
    {
        helper('otp');
        $userModel = new UserModel();
        $userOtpModel = new \App\Models\UserOtpModel();
        
        $userId = session('password_reset_user_id');
        $otpInput = $this->request->getPost('otp');
        $isAjax = $this->request->isAJAX();

        // Validate inputs
        if (!$userId || empty($otpInput)) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid request. Please start over.'
                ]);
            }
            return redirect()->to('forgot-password')->with('error', 'Invalid request.');
        }

        $user = $userModel->find($userId);
        if (!$user) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not found.'
                ]);
            }
            return redirect()->to('forgot-password')->with('error', 'User not found.');
        }

        // Get OTP record from user_otp table
        $otpRecord = $userOtpModel->getLatestOtpForUser($userId);
        
        // Check if OTP exists
        if (!$otpRecord || empty($otpRecord['otp_code'])) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No OTP found. Please request a new one.'
                ]);
            }
            return redirect()->back()->with('error', 'No OTP found. Please request a new one.');
        }

        // Check if OTP has expired
        if (is_otp_expired($otpRecord['otp_expires_at'])) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'OTP has expired. Please request a new one.',
                    'expired' => true
                ]);
            }
            return redirect()->back()->with('error', 'OTP has expired. Please request a new one.');
        }

        // Check attempt limit (max 5 attempts)
        if ($otpRecord['otp_attempts'] >= 5) {
            // Clear OTP to force new request
            $userOtpModel->clearOtp($userId);
            
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Too many failed attempts. Please request a new OTP.',
                    'locked' => true
                ]);
            }
            return redirect()->back()->with('error', 'Too many failed attempts. Please request a new OTP.');
        }

        // Verify OTP
        if (verify_otp($otpInput, $otpRecord['otp_code'])) {
            // OTP is correct - mark as verified
            $userOtpModel->update($otpRecord['id'], [
                'otp_verified' => 1,
                'otp_attempts' => 0
            ]);

            // Generate token for password reset
            $token = bin2hex(random_bytes(32));
            session()->set('password_reset_token', $token);

            log_message('info', "OTP verified successfully for user ID: {$userId}");

            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'OTP verified successfully. You can now reset your password.',
                    'redirect' => base_url('reset-password-otp?token=' . $token)
                ]);
            }
            return redirect()->to('reset-password-otp?token=' . $token)->with('success', 'OTP verified successfully.');
        } else {
            // Incorrect OTP - increment attempts
            $newAttempts = $otpRecord['otp_attempts'] + 1;
            $userOtpModel->update($otpRecord['id'], [
                'otp_attempts' => $newAttempts
            ]);

            $remainingAttempts = 5 - $newAttempts;
            
            log_message('warning', "Failed OTP verification for user ID: {$userId}. Attempts: {$newAttempts}");

            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Incorrect OTP. You have ' . $remainingAttempts . ' attempt(s) remaining.',
                    'remainingAttempts' => $remainingAttempts
                ]);
            }
            return redirect()->back()->with('error', 'Incorrect OTP. You have ' . $remainingAttempts . ' attempt(s) remaining.');
        }
    }

    /**
     * Display OTP verification page
     */
    public function verifyOtpPage()
    {
        $userId = session('password_reset_user_id');
        $method = session('password_reset_method');

        if (!$userId || !$method) {
            return redirect()->to('forgot-password')->with('error', 'Session expired. Please start over.');
        }

        helper('otp');
        $userModel = new UserModel();
        $userOtpModel = new \App\Models\UserOtpModel();
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->to('forgot-password')->with('error', 'User not found.');
        }

        // Get OTP record from user_otp table
        $otpRecord = $userOtpModel->getLatestOtpForUser($userId);

        // Calculate remaining time
        $remainingSeconds = 300; // Default 5 minutes
        if ($otpRecord && !empty($otpRecord['otp_expires_at'])) {
            date_default_timezone_set('Asia/Manila');
            $now = new \DateTime();
            $expiresAt = new \DateTime($otpRecord['otp_expires_at']);
            $diff = $expiresAt->getTimestamp() - $now->getTimestamp();
            $remainingSeconds = max(0, $diff); // Don't go negative
        }

        $data = [
            'method' => $method,
            'masked_contact' => $method === 'sms' ? mask_phone_number($user['phone_number']) : mask_email($user['email']),
            'can_resend' => ($otpRecord && !empty($otpRecord['otp_last_request'])) ? check_otp_rate_limit($otpRecord['otp_last_request'], 34)['allowed'] : true,
            'remaining_seconds' => $remainingSeconds
        ];

        return $this->loadView('K-NECT/verify-otp', $data);
    }

    /**
     * Display password reset form (after OTP verification)
     */
    public function resetPasswordOtp()
    {
        $token = $this->request->getGet('token');
        $sessionToken = session('password_reset_token');
        $userId = session('password_reset_user_id');
        $otpVerified = session('password_reset_user_id') ? true : false;

        // Verify token and OTP verification status
        if (empty($token) || empty($sessionToken) || $token !== $sessionToken || !$userId) {
            return redirect()->to('forgot-password')->with('error', 'Invalid or expired session. Please start over.');
        }

        // Double-check OTP verification in user_otp table
        $userModel = new UserModel();
        $userOtpModel = new \App\Models\UserOtpModel();
        $user = $userModel->find($userId);
        $otpRecord = $userOtpModel->getLatestOtpForUser($userId);

        if (!$user || !$otpRecord || $otpRecord['otp_verified'] != 1) {
            return redirect()->to('forgot-password')->with('error', 'OTP verification required. Please start over.');
        }

        $data = [
            'token' => $token,
            'account_type' => session('password_reset_account_type')
        ];

        return $this->loadView('K-NECT/reset-password-otp', $data);
    }

    /**
     * Process password reset after OTP verification
     */
    public function processResetPasswordOtp()
    {
        $userModel = new UserModel();
        $token = $this->request->getPost('token');
        $sessionToken = session('password_reset_token');
        $userId = session('password_reset_user_id');
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');
        $isAjax = $this->request->isAJAX();

        // Validate inputs
        if (empty($token) || empty($password) || empty($confirmPassword)) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'All fields are required.'
                ]);
            }
            return redirect()->back()->with('error', 'All fields are required.');
        }

        // Verify token
        if ($token !== $sessionToken || !$userId) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid session. Please start over.'
                ]);
            }
            return redirect()->to('forgot-password')->with('error', 'Invalid session.');
        }

        if ($password !== $confirmPassword) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Passwords do not match.'
                ]);
            }
            return redirect()->back()->with('error', 'Passwords do not match.');
        }

        if (strlen($password) < 6) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Password must be at least 6 characters long.'
                ]);
            }
            return redirect()->back()->with('error', 'Password must be at least 6 characters long.');
        }

        $user = $userModel->find($userId);
        $userOtpModel = new \App\Models\UserOtpModel();
        $otpRecord = $userOtpModel->getLatestOtpForUser($userId);
        
        if (!$user || !$otpRecord || $otpRecord['otp_verified'] != 1) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'OTP verification required.'
                ]);
            }
            return redirect()->to('forgot-password')->with('error', 'OTP verification required.');
        }

        // Hash new password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Determine which password field to update
        $accountType = session('password_reset_account_type') ?? 'kk';
        $passwordField = 'password'; // Default for KK
        
        switch ($accountType) {
            case 'sk':
                $passwordField = 'sk_password';
                break;
            case 'pederasyon':
                $passwordField = 'ped_password';
                break;
            case 'kk':
            default:
                $passwordField = 'password';
                break;
        }

        // Update password
        $updateData = [
            $passwordField => $hashedPassword
        ];
        
        $userModel->update($userId, $updateData);
        
        // Clear OTP data from user_otp table
        $userOtpModel->clearOtp($userId);

        // Clear session data
        session()->remove('password_reset_method');
        session()->remove('password_reset_username');
        session()->remove('password_reset_account_type');
        session()->remove('password_reset_contact');
        session()->remove('password_reset_user_id');
        session()->remove('password_reset_token');

        log_message('info', "Password reset completed successfully for user ID: {$userId}");

        if ($isAjax) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Password has been reset successfully! You can now log in with your new password.',
                'redirect' => base_url('login')
            ]);
        }

        return redirect()->to('login')->with('success', 'Password has been reset successfully! You can now log in with your new password.');
    }

    /**
     * Helper method to get account type label
     */
    private function getAccountTypeLabel($accountType)
    {
        switch ($accountType) {
            case 'sk':
                return 'SK (Sangguniang Kabataan)';
            case 'pederasyon':
                return 'Pederasyon';
            case 'kk':
            default:
                return 'KK (Katipunan ng Kabataan)';
        }
    }

    /**
     * Send password reset email (Legacy method - kept for backwards compatibility)
     */
    public function sendResetEmail()
    {
        $userModel = new UserModel();
        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $accountType = $this->request->getPost('account_type');
        $isAjax = $this->request->isAJAX();

        // Validate inputs
        if (empty($username) || empty($email) || empty($accountType)) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Missing required information. Please start over.'
                ]);
            }
            return redirect()->back()->with('error', 'Missing required information. Please start over.');
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Please enter a valid email address.'
                ]);
            }
            return redirect()->back()->with('error', 'Please enter a valid email address.');
        }

        // Find user based on account type and username
        $user = null;
        switch ($accountType) {
            case 'kk':
                $user = $userModel->where('username', $username)->first();
                break;
            case 'sk':
                $user = $userModel->where('sk_username', $username)->first();
                break;
            case 'pederasyon':
                $user = $userModel->where('ped_username', $username)->first();
                break;
        }

        // Verify user exists
        if (!$user) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Account not found. Please start over.'
                ]);
            }
            return redirect()->back()->with('error', 'Account not found. Please start over.');
        }

        // Verify email matches the account
        if (strtolower(trim($user['email'])) !== strtolower(trim($email))) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'The email address does not match the registered email for this ' . $accountType . ' account.'
                ]);
            }
            return redirect()->back()->with('error', 'The email address does not match the registered email for this ' . $accountType . ' account.');
        }

        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);
        
        // Set token expiry to 30 minutes from now
        date_default_timezone_set('Asia/Manila');
        $expiry = date('Y-m-d H:i:s', time() + (30 * 60));

        // Save token hash, expiry, and account type to database
        $userModel->update($user['id'], [
            'reset_token_hash' => $tokenHash,
            'reset_token_expires_at' => $expiry,
            'reset_account_type' => $accountType // Store which account type (kk/sk/pederasyon)
        ]);

        // Send email with reset link
        $resetLink = base_url('reset-password?token=' . $token);
        
        // Determine account type label for email
        $accountTypeLabel = '';
        switch ($accountType) {
            case 'sk':
                $accountTypeLabel = 'SK (Sangguniang Kabataan)';
                break;
            case 'pederasyon':
                $accountTypeLabel = 'Pederasyon';
                break;
            case 'kk':
            default:
                $accountTypeLabel = 'KK (Katipunan ng Kabataan)';
                break;
        }
        
        $emailService = \Config\Services::email();
        $emailService->setTo($email);
        $emailService->setSubject('Password Reset - K-NECT ' . $accountTypeLabel . ' Account');
        
        $message = view('emails/password_reset', [
            'resetLink' => $resetLink,
            'userName' => $user['first_name'] . ' ' . $user['last_name'],
            'accountType' => $accountType,
            'accountTypeLabel' => $accountTypeLabel
        ]);
        
        $emailService->setMessage($message);

        try {
            if ($emailService->send()) {
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Password reset link has been sent to your email.'
                    ]);
                }
                return redirect()->back()->with('success', 'Password reset link has been sent to your email.');
            } else {
                log_message('error', 'Failed to send password reset email: ' . $emailService->printDebugger(['headers']));
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to send email. Please try again later.'
                    ]);
                }
                return redirect()->back()->with('error', 'Failed to send email. Please try again later.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Email sending exception: ' . $e->getMessage());
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'An error occurred. Please try again later.'
                ]);
            }
            return redirect()->back()->with('error', 'An error occurred. Please try again later.');
        }
    }

    /**
     * Display reset password form
     */
    public function resetPassword()
    {
        $token = $this->request->getGet('token');
        
        if (empty($token)) {
            return redirect()->to('login')->with('error', 'Invalid reset link.');
        }

        $userModel = new UserModel();
        $tokenHash = hash('sha256', $token);
        
        // Find user with this token
        $user = $userModel->where('reset_token_hash', $tokenHash)->first();
        
        if (!$user) {
            return redirect()->to('login')->with('error', 'Invalid or expired reset link.');
        }

        // Check if token has expired
        date_default_timezone_set('Asia/Manila');
        if (strtotime($user['reset_token_expires_at']) <= time()) {
            return redirect()->to('login')->with('error', 'This reset link has expired. Please request a new one.');
        }

        $data = [
            'token' => $token
        ];

        return $this->loadView('K-NECT/reset-password', $data);
    }

    /**
     * Process password reset
     */
    public function processResetPassword()
    {
        $userModel = new UserModel();
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');
        $isAjax = $this->request->isAJAX();

        // Validate inputs
        if (empty($token) || empty($password) || empty($confirmPassword)) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'All fields are required.'
                ]);
            }
            return redirect()->back()->with('error', 'All fields are required.');
        }

        if ($password !== $confirmPassword) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Passwords do not match.'
                ]);
            }
            return redirect()->back()->with('error', 'Passwords do not match.');
        }

        if (strlen($password) < 6) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Password must be at least 6 characters long.'
                ]);
            }
            return redirect()->back()->with('error', 'Password must be at least 6 characters long.');
        }

        // Verify token
        $tokenHash = hash('sha256', $token);
        $user = $userModel->where('reset_token_hash', $tokenHash)->first();

        if (!$user) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid or expired reset link.'
                ]);
            }
            return redirect()->to('login')->with('error', 'Invalid or expired reset link.');
        }

        // Check if token has expired
        date_default_timezone_set('Asia/Manila');
        if (strtotime($user['reset_token_expires_at']) <= time()) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'This reset link has expired. Please request a new one.'
                ]);
            }
            return redirect()->to('login')->with('error', 'This reset link has expired. Please request a new one.');
        }

        // Hash new password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Determine which password field to update based on account type
        $accountType = $user['reset_account_type'] ?? 'kk'; // Default to KK if not set
        $passwordField = 'password'; // Default for KK
        
        switch ($accountType) {
            case 'sk':
                $passwordField = 'sk_password';
                break;
            case 'pederasyon':
                $passwordField = 'ped_password';
                break;
            case 'kk':
            default:
                $passwordField = 'password';
                break;
        }

        // Update the correct password field and clear reset token
        $updateData = [
            $passwordField => $hashedPassword,
            'reset_token_hash' => null,
            'reset_token_expires_at' => null,
            'reset_account_type' => null
        ];
        
        $userModel->update($user['id'], $updateData);

        if ($isAjax) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Password has been reset successfully! You can now log in with your new password.',
                'redirect' => base_url('login')
            ]);
        }

        return redirect()->to('login')->with('success', 'Password has been reset successfully! You can now log in with your new password.');
    }
}
