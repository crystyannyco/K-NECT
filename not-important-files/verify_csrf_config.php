<?php
/**
 * CSRF Configuration Verification Script
 * Run this to verify CSRF settings are correctly applied
 */

// Read Security.php file directly
$securityFile = __DIR__ . '/app/Config/Security.php';

echo "=" . str_repeat("=", 70) . "\n";
echo "CSRF CONFIGURATION VERIFICATION\n";
echo "=" . str_repeat("=", 70) . "\n\n";

if (!file_exists($securityFile)) {
    echo "❌ ERROR: Security.php not found!\n";
    exit(1);
}

$content = file_get_contents($securityFile);

echo "Checking CSRF Settings in: {$securityFile}\n";
echo "-" . str_repeat("-", 70) . "\n\n";

// Parse configuration values
$allGood = true;

try {
    // Check tokenRandomize
    if (preg_match('/public\s+bool\s+\$tokenRandomize\s*=\s*(true|false)/i', $content, $matches)) {
        $tokenRandomize = strtolower($matches[1]) === 'true';
        if ($tokenRandomize === false) {
            echo "✅ Token Randomize: DISABLED (Correct for multi-step forms)\n";
        } else {
            echo "❌ Token Randomize: ENABLED (May cause 403 errors!)\n";
            echo "   FIX: Set public bool \$tokenRandomize = false;\n";
            $allGood = false;
        }
    } else {
        echo "⚠️  Token Randomize: Could not parse (check manually)\n";
    }
    
    // Check regenerate
    if (preg_match('/public\s+bool\s+\$regenerate\s*=\s*(true|false)/i', $content, $matches)) {
        $regenerate = strtolower($matches[1]) === 'true';
        if ($regenerate === false) {
            echo "✅ Token Regenerate: DISABLED (Correct for multi-step forms)\n";
        } else {
            echo "❌ Token Regenerate: ENABLED (May cause token mismatch!)\n";
            echo "   FIX: Set public bool \$regenerate = false;\n";
            $allGood = false;
        }
    } else {
        echo "⚠️  Token Regenerate: Could not parse (check manually)\n";
    }
    
    // Check redirect
    if (preg_match('/public\s+bool\s+\$redirect\s*=\s*(true|false)/i', $content, $matches)) {
        $redirect = strtolower($matches[1]) === 'true';
        if ($redirect === false) {
            echo "✅ Redirect on Failure: DISABLED (Allows proper error handling)\n";
        } else {
            echo "⚠️  Redirect on Failure: ENABLED (May cause ERR_CONNECTION_CLOSED)\n";
            echo "   RECOMMENDATION: Set public bool \$redirect = false;\n";
        }
    } else {
        echo "⚠️  Redirect: Could not parse (check manually)\n";
    }
    
    // Check expiration
    if (preg_match('/public\s+int\s+\$expires\s*=\s*(\d+)/i', $content, $matches)) {
        $expires = (int)$matches[1];
        if ($expires >= 7200) {
            echo "✅ Token Expiration: {$expires}s (" . round($expires/3600, 1) . " hours - Sufficient)\n";
        } else {
            echo "⚠️  Token Expiration: {$expires}s (May be too short for long forms)\n";
            echo "   RECOMMENDATION: Set public int \$expires = 7200; (2 hours)\n";
        }
    } else {
        echo "⚠️  Token Expiration: Could not parse (check manually)\n";
    }
    
    
    echo "\n" . str_repeat("-", 70) . "\n";
    
    // Final status
    if ($allGood) {
        echo "\n🎉 STATUS: ALL CRITICAL CSRF SETTINGS ARE OPTIMAL! 🎉\n";
        echo "\nYou should no longer experience 403 Forbidden errors.\n";
        echo "\nIf you still get errors after applying these fixes:\n";
        echo "1. Clear browser cache and cookies (Ctrl+Shift+Delete)\n";
        echo "2. Run: php spark cache:clear\n";
        echo "3. Delete session files: Remove-Item writable\\session\\* -Force\n";
        echo "4. Restart your web server\n";
        echo "5. Test in incognito/private browser window\n";
    } else {
        echo "\n❌ STATUS: CONFIGURATION NEEDS ADJUSTMENT ❌\n";
        echo "\nPlease apply the fixes mentioned above in app/Config/Security.php\n";
        echo "\nAfter making changes:\n";
        echo "1. Save the file\n";
        echo "2. Run: php spark cache:clear\n";
        echo "3. Restart your web server\n";
        echo "4. Run this script again to verify: php verify_csrf_config.php\n";
    }
    
    // Check session directory
    echo "\n" . str_repeat("=", 72) . "\n";
    echo "Session Directory Check:\n";
    echo "-" . str_repeat("-", 70) . "\n";
    
    $sessionPath = __DIR__ . '/writable/session';
    
    echo sprintf("%-25s: %s\n", "Save Path", $sessionPath);
    echo sprintf("%-25s: %s\n", "Exists", is_dir($sessionPath) ? '✅ Yes' : '❌ No');
    echo sprintf("%-25s: %s\n", "Writable", is_writable($sessionPath) ? '✅ Yes' : '❌ No');
    
    if (is_dir($sessionPath)) {
        $sessionFiles = glob($sessionPath . '/ci_session*');
        echo sprintf("%-25s: %d files\n", "Session Files", count($sessionFiles));
    }
    
    if (!is_writable($sessionPath)) {
        echo "\n❌ WARNING: Session directory is not writable!\n";
        echo "   FIX (Windows): icacls writable\\session /grant Everyone:F\n";
    }
    
    echo "-" . str_repeat("-", 70) . "\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Make sure you're running this from the project root directory.\n";
}

echo "\nScript completed.\n";
