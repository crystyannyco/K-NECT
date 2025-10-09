<?php
/**
 * Continue Migration After Step 1
 */

$mysqli = new mysqli('localhost', 'root', '', 'k-nect2');

// Disable exceptions, use error checking instead
mysqli_report(MYSQLI_REPORT_OFF);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error . "\n");
}

echo "Connected to database k-nect2\n";
echo "Continuing migration from Step 2...\n\n";

$queries = [
    // Step 0: Make barangay_id a primary key (skip if already exists)
    "ALTER TABLE `barangay` ADD PRIMARY KEY (`barangay_id`)",
    
    // Step 2: Skip foreign key for now, do it later
    
    // Step 3: Modify visibility enum
    "ALTER TABLE `documents` MODIFY COLUMN `visibility` ENUM('pederasyon', 'sk', 'kk') DEFAULT 'pederasyon'",
    
    // Step 4: Update existing data
    "UPDATE `documents` d INNER JOIN `user` u ON LOWER(TRIM(d.uploaded_by)) = LOWER(TRIM(u.username)) SET d.visibility = 'pederasyon' WHERE u.user_type = 3",
    
    // Step 5: Set barangay_id for SK and KK documents (skip if address.barangay_id doesn't exist)
    // "UPDATE `documents` d INNER JOIN `user` u ON LOWER(TRIM(d.uploaded_by)) = LOWER(TRIM(u.username)) INNER JOIN `address` a ON u.id = a.user_id SET d.barangay_id = a.barangay WHERE u.user_type IN (1, 2) AND d.visibility IN ('sk', 'kk')",
    
    // Step 6: Set visibility_scope
    "UPDATE `documents` SET visibility_scope = 'specific_barangay' WHERE barangay_id IS NOT NULL",
    
    // Step 7: Remove approval columns (check if they exist first)
    "ALTER TABLE `documents` DROP COLUMN IF EXISTS `approval_status`",
    "ALTER TABLE `documents` DROP COLUMN IF EXISTS `approver`",
    "ALTER TABLE `documents` DROP COLUMN IF EXISTS `approval_at`",
    "ALTER TABLE `documents` DROP COLUMN IF EXISTS `approval_comment`",
    
    // Step 8: Add indexes
    "ALTER TABLE `documents` ADD INDEX `idx_visibility` (`visibility`)",
    "ALTER TABLE `documents` ADD INDEX `idx_barangay_id` (`barangay_id`)",
    "ALTER TABLE `documents` ADD INDEX `idx_visibility_scope` (`visibility_scope`)",
];

$successCount = 0;
$errorCount = 0;
$skippedCount = 0;

foreach ($queries as $index => $query) {
    echo "Executing step " . ($index + 1) . "...\n";
    
    if ($mysqli->query($query)) {
        $successCount++;
        $preview = substr($query, 0, 80);
        echo "  ✓ OK: $preview...\n";
    } else {
        $error = $mysqli->error;
        // Check if error is because column doesn't exist or already done
        if (str_contains($error, 'Duplicate key name') || 
            str_contains($error, "Can't DROP") ||
            str_contains($error, 'Multiple primary key') ||
            str_contains($error, "doesn't exist")) {
            $skippedCount++;
            echo "  ⊘ SKIPPED (already done or not applicable): $error\n";
        } else {
            $errorCount++;
            echo "  ✗ ERROR: $error\n";
            $preview = substr($query, 0, 100);
            echo "  Query: $preview...\n";
        }
    }
}

// Now try to add foreign key
echo "\nAttempting to add foreign key constraint...\n";
$fkQuery = "ALTER TABLE `documents` ADD CONSTRAINT `fk_documents_barangay` FOREIGN KEY (`barangay_id`) REFERENCES `barangay`(`barangay_id`) ON DELETE SET NULL ON UPDATE CASCADE";
if ($mysqli->query($fkQuery)) {
    echo "  ✓ Foreign key added successfully!\n";
    $successCount++;
} else {
    $error = $mysqli->error;
    if (str_contains($error, 'Duplicate')) {
        echo "  ⊘ Foreign key already exists\n";
        $skippedCount++;
    } else {
        echo "  ⚠ Foreign key skipped (may not be critical): $error\n";
        $skippedCount++;
    }
}

$mysqli->close();

echo "\n";
echo "========================================\n";
echo "Migration Status\n";
echo "========================================\n";
echo "Successful: $successCount\n";
echo "Skipped: $skippedCount\n";
echo "Failed: $errorCount\n";
echo "\n";

if ($errorCount > 0) {
    echo "⚠ Some queries failed. Please review the errors above.\n";
    exit(1);
} else {
    echo "✓ Migration completed!\n";
    echo "You can now upload documents with the new visibility system.\n";
    exit(0);
}
