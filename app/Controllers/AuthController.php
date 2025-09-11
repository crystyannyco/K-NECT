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
}
