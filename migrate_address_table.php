<?php

/**
 * Address Table Migration Script
 * 
 * This script updates the address table to support PSGC API integration:
 * - Changes barangay column from INT to VARCHAR(100)
 * - Updates region, province, municipality to store PSGC codes
 * - Converts existing numeric barangay IDs to names
 * - Adds barangay_psgc_code column if not exists
 * 
 * Usage: php migrate_address_table.php
 */

require __DIR__ . '/vendor/autoload.php';

// Load CodeIgniter
$app = require_once FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';
$app = require $bootstrap;

$db = \Config\Database::connect();

echo "=======================================================\n";
echo "Address Table Migration for PSGC API Integration\n";
echo "=======================================================\n\n";

try {
    // Step 1: Add barangay_psgc_code column if not exists
    echo "Step 1: Checking barangay_psgc_code column...\n";
    $query = $db->query("SHOW COLUMNS FROM address LIKE 'barangay_psgc_code'");
    if ($query->getNumRows() === 0) {
        echo "  → Adding barangay_psgc_code column...\n";
        $db->query("ALTER TABLE `address` 
            ADD COLUMN `barangay_psgc_code` VARCHAR(20) NULL 
            COMMENT 'Philippine Standard Geographic Code for the barangay' 
            AFTER `barangay`");
        echo "  ✓ Column added successfully\n\n";
    } else {
        echo "  ✓ Column already exists\n\n";
    }

    // Step 2: Change barangay column to VARCHAR
    echo "Step 2: Updating barangay column datatype...\n";
    $query = $db->query("SHOW COLUMNS FROM address LIKE 'barangay'");
    $column = $query->getRowArray();
    
    if (strpos(strtolower($column['Type']), 'varchar') === false) {
        echo "  Current type: " . $column['Type'] . "\n";
        echo "  → Converting to VARCHAR(100)...\n";
        $db->query("ALTER TABLE `address` 
            MODIFY COLUMN `barangay` VARCHAR(100) NULL 
            COMMENT 'Barangay name (formerly numeric ID, now stores actual barangay name from PSGC API)'");
        echo "  ✓ Column updated successfully\n\n";
    } else {
        echo "  ✓ Column is already VARCHAR\n\n";
    }

    // Step 3: Update region, province, municipality columns
    echo "Step 3: Updating location columns for PSGC codes...\n";
    
    $db->query("ALTER TABLE `address` 
        MODIFY COLUMN `region` VARCHAR(20) NULL 
        COMMENT 'PSGC Region Code (9 digits, format: ###000000)'");
    echo "  ✓ Region column updated\n";
    
    $db->query("ALTER TABLE `address` 
        MODIFY COLUMN `province` VARCHAR(20) NULL 
        COMMENT 'PSGC Province Code (9 digits, format: #####0000)'");
    echo "  ✓ Province column updated\n";
    
    $db->query("ALTER TABLE `address` 
        MODIFY COLUMN `municipality` VARCHAR(20) NULL 
        COMMENT 'PSGC Municipality Code (9 digits, format: #########)'");
    echo "  ✓ Municipality column updated\n\n";

    // Step 4: Convert legacy barangay IDs to names
    echo "Step 4: Converting legacy barangay IDs to names...\n";
    
    // Check if there are any numeric barangay values
    $query = $db->query("SELECT COUNT(*) as count FROM address WHERE barangay REGEXP '^[0-9]+$'");
    $result = $query->getRowArray();
    $numericCount = $result['count'];
    
    if ($numericCount > 0) {
        echo "  Found {$numericCount} records with numeric barangay IDs\n";
        echo "  → Converting to barangay names...\n";
        
        $barangayMap = [
            '1' => 'Antipolo', '2' => 'Cristo Rey', '3' => 'Del Rosario (Banao)', '4' => 'Francia',
            '5' => 'La Anunciacion', '6' => 'La Medalla', '7' => 'La Purisima', '8' => 'La Trinidad',
            '9' => 'Niño Jesus', '10' => 'Perpetual Help', '11' => 'Sagrada', '12' => 'Salvacion',
            '13' => 'San Agustin', '14' => 'San Andres', '15' => 'San Antonio', '16' => 'San Francisco',
            '17' => 'San Isidro', '18' => 'San Jose', '19' => 'San Juan', '20' => 'San Miguel',
            '21' => 'San Nicolas', '22' => 'San Pedro', '23' => 'San Rafael', '24' => 'San Ramon',
            '25' => 'San Roque', '26' => 'Santiago', '27' => 'San Vicente Norte', '28' => 'San Vicente Sur',
            '29' => 'Santa Cruz Norte', '30' => 'Santa Cruz Sur', '31' => 'Santa Elena', '32' => 'Santa Isabel',
            '33' => 'Santa Maria', '34' => 'Santa Teresita', '35' => 'Santo Domingo', '36' => 'Santo Niño'
        ];
        
        $psgcCodes = [
            '1' => '051716001', '2' => '051716002', '3' => '051716003', '4' => '051716004',
            '5' => '051716005', '6' => '051716006', '7' => '051716007', '8' => '051716008',
            '9' => '051716009', '10' => '051716010', '11' => '051716011', '12' => '051716012',
            '13' => '051716013', '14' => '051716014', '15' => '051716015', '16' => '051716016',
            '17' => '051716017', '18' => '051716018', '19' => '051716019', '20' => '051716020',
            '21' => '051716021', '22' => '051716022', '23' => '051716023', '24' => '051716024',
            '25' => '051716025', '26' => '051716026', '27' => '051716027', '28' => '051716028',
            '29' => '051716029', '30' => '051716030', '31' => '051716031', '32' => '051716032',
            '33' => '051716033', '34' => '051716034', '35' => '051716035', '36' => '051716036'
        ];
        
        foreach ($barangayMap as $id => $name) {
            $psgcCode = $psgcCodes[$id];
            $db->query("UPDATE `address` 
                SET `barangay` = ?, `barangay_psgc_code` = ? 
                WHERE `barangay` = ?", [$name, $psgcCode, $id]);
        }
        
        echo "  ✓ Converted {$numericCount} records\n\n";
    } else {
        echo "  ✓ No numeric barangay IDs found\n\n";
    }

    // Step 5: Verification
    echo "=======================================================\n";
    echo "Migration completed! Verification:\n";
    echo "=======================================================\n\n";
    
    // Check column structure
    $query = $db->query("SHOW COLUMNS FROM address WHERE Field IN ('barangay', 'barangay_psgc_code', 'region', 'province', 'municipality')");
    $columns = $query->getResultArray();
    
    echo "Column Structure:\n";
    foreach ($columns as $col) {
        echo sprintf("  %-20s %-20s %s\n", $col['Field'], $col['Type'], $col['Comment'] ?? '');
    }
    echo "\n";
    
    // Check data
    $query = $db->query("SELECT 
        COUNT(*) as total,
        COUNT(barangay_psgc_code) as with_psgc,
        COUNT(CASE WHEN barangay REGEXP '^[0-9]+$' THEN 1 END) as numeric_barangay,
        COUNT(CASE WHEN barangay NOT REGEXP '^[0-9]+$' AND barangay IS NOT NULL THEN 1 END) as name_barangay
        FROM address");
    $stats = $query->getRowArray();
    
    echo "Data Statistics:\n";
    echo "  Total records:                {$stats['total']}\n";
    echo "  Records with PSGC code:       {$stats['with_psgc']}\n";
    echo "  Records with numeric barangay: {$stats['numeric_barangay']}\n";
    echo "  Records with name barangay:    {$stats['name_barangay']}\n\n";
    
    echo "✅ Migration completed successfully!\n\n";
    
} catch (Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n\n";
    exit(1);
}
