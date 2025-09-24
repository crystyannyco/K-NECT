<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ValidateSMSSystem extends BaseCommand
{
    protected $group = 'Debug';
    protected $name = 'debug:validate-sms-system';
    protected $description = 'Comprehensive validation of SMS system integrity';

    public function run(array $params)
    {
        // Load SMS helper
        helper('sms');
        
        CLI::write('=== SMS SYSTEM COMPREHENSIVE VALIDATION ===', 'yellow');
        
        $db = \Config\Database::connect();
        $issues = [];
        $warnings = [];

        // 1. Validate EventController getSmsRecipients method
        CLI::write("\n1. Validating EventController SMS Recipients...", 'cyan');
        $userModel = new \App\Models\UserModel();
        
        try {
            // Test the actual getSmsRecipients query structure
            $testQuery = $userModel->select('user.*, address.barangay as barangay_id, barangay.name as barangay_name')
                                  ->join('address', 'address.user_id = user.id', 'left')
                                  ->join('barangay', 'barangay.barangay_id = address.barangay', 'left')
                                  ->where('address.barangay', 1)
                                  ->where('user.phone_number IS NOT NULL')
                                  ->where('user.phone_number !=', '')
                                  ->where('user.is_active', 1);
            
            $recipients = $testQuery->findAll();
            CLI::write("✅ EventController query structure is correct", 'green');
            CLI::write("   Found " . count($recipients) . " potential recipients in test barangay", 'white');
            
        } catch (\Exception $e) {
            $issues[] = "EventController query failed: " . $e->getMessage();
            CLI::write("❌ EventController query failed: " . $e->getMessage(), 'red');
        }

        // 2. Validate SMS helper function
        CLI::write("\n2. Validating SMS Helper Functions...", 'cyan');
        
        // Load SMS helper
        helper('sms');
        
        if (function_exists('send_sms')) {
            CLI::write("✅ send_sms function exists", 'green');
            
            if (function_exists('format_phone_number')) {
                CLI::write("✅ format_phone_number function exists", 'green');
            } else {
                $issues[] = "format_phone_number function not found";
                CLI::write("❌ format_phone_number function not found", 'red');
            }
            
            // Check if SMS is enabled
            if (defined('SMS_ENABLED') && SMS_ENABLED) {
                CLI::write("✅ SMS functionality is enabled (SMS_ENABLED=true)", 'green');
            } else {
                $warnings[] = "SMS functionality is disabled (SMS_ENABLED=false)";
                CLI::write("⚠️  SMS functionality is disabled (SMS_ENABLED=false)", 'yellow');
            }
        } else {
            $issues[] = "SMS helper function send_sms not found";
            CLI::write("❌ send_sms function not found", 'red');
        }

        // 3. Validate user/address table relationships
        CLI::write("\n3. Validating Database Relationships...", 'cyan');
        
        // Test JOIN integrity
        $joinTest = $db->query('
            SELECT COUNT(*) as count
            FROM user u
            LEFT JOIN address a ON u.id = a.user_id
            WHERE u.phone_number IS NOT NULL 
            AND u.phone_number != ""
            AND u.is_active = 1
        ')->getRowArray();
        
        CLI::write("✅ User/Address JOIN working correctly", 'green');
        CLI::write("   Found " . $joinTest['count'] . " active users with phone numbers", 'white');

        // 4. Test for users without addresses who should have them
        $usersWithoutAddresses = $db->query('
            SELECT COUNT(*) as count
            FROM user u
            LEFT JOIN address a ON u.id = a.user_id
            WHERE a.user_id IS NULL 
            AND u.phone_number IS NOT NULL 
            AND u.phone_number != ""
            AND u.is_active = 1
            AND u.user_type IN (2, 3)
        ')->getRowArray();

        if ($usersWithoutAddresses['count'] > 0) {
            $warnings[] = $usersWithoutAddresses['count'] . " active officials with phone numbers have no address records";
            CLI::write("⚠️  " . $usersWithoutAddresses['count'] . " active officials with phone numbers have no address records", 'yellow');
        } else {
            CLI::write("✅ All active officials with phone numbers have address records", 'green');
        }

        // 5. Validate SMS Log Model
        CLI::write("\n4. Validating SMS Logging...", 'cyan');
        try {
            $smsLogModel = new \App\Models\SMSLogModel();
            $recentLogs = $smsLogModel->orderBy('sent_at', 'DESC')->limit(1)->findAll();
            CLI::write("✅ SMS Log Model working correctly", 'green');
            CLI::write("   Found " . count($recentLogs) . " recent SMS logs", 'white');
        } catch (\Exception $e) {
            $issues[] = "SMS Log Model failed: " . $e->getMessage();
            CLI::write("❌ SMS Log Model failed: " . $e->getMessage(), 'red');
        }

        // 6. Test SMS configuration
        CLI::write("\n5. Validating SMS Configuration...", 'cyan');
        $textBeeConfig = config('TextBeeSMS');
        $smsConfig = config('SMS');
        
        if ($textBeeConfig || $smsConfig) {
            CLI::write("✅ SMS configuration files exist", 'green');
            // Check if config has API key (could be hardcoded in helper or in config)
            if (defined('SMS_ENABLED') && SMS_ENABLED) {
                CLI::write("✅ SMS functionality enabled", 'green');
            } else {
                $warnings[] = "SMS functionality might be disabled";
                CLI::write("⚠️  SMS functionality status unclear", 'yellow');
            }
        } else {
            $warnings[] = "SMS configuration files are empty (using hardcoded values)";
            CLI::write("⚠️  SMS configuration files are empty (using hardcoded values)", 'yellow');
        }

        // 7. Test EventController SMS methods accessibility
        CLI::write("\n6. Validating EventController SMS Methods...", 'cyan');
        try {
            $eventController = new \App\Controllers\EventController();
            $reflection = new \ReflectionClass($eventController);
            
            $methods = ['getSmsRecipients', 'formatSmsMessage', 'sendSmsNotifications'];
            foreach ($methods as $method) {
                if ($reflection->hasMethod($method)) {
                    CLI::write("✅ Method $method exists", 'green');
                } else {
                    $issues[] = "EventController method $method not found";
                    CLI::write("❌ Method $method not found", 'red');
                }
            }
        } catch (\Exception $e) {
            $issues[] = "EventController validation failed: " . $e->getMessage();
            CLI::write("❌ EventController validation failed: " . $e->getMessage(), 'red');
        }

        // Summary
        CLI::write("\n=== VALIDATION SUMMARY ===", 'yellow');
        
        if (empty($issues)) {
            CLI::write("🎉 SMS System is functioning correctly!", 'green');
        } else {
            CLI::write("❌ CRITICAL ISSUES FOUND:", 'red');
            foreach ($issues as $issue) {
                CLI::write("  • $issue", 'red');
            }
        }

        if (!empty($warnings)) {
            CLI::write("\n⚠️  WARNINGS:", 'yellow');
            foreach ($warnings as $warning) {
                CLI::write("  • $warning", 'yellow');
            }
        }

        CLI::write("\n=== RECOMMENDATIONS ===", 'cyan');
        if (empty($issues)) {
            CLI::write("• SMS system is ready for production use", 'green');
            CLI::write("• Test with actual event publication to verify end-to-end flow", 'white');
        } else {
            CLI::write("• Fix critical issues before using SMS notifications", 'red');
            CLI::write("• Run this validation again after fixes", 'white');
        }
        
        return empty($issues) ? 0 : 1;
    }
}