<?php
/**
 * Database Migration Runner
 * This script will execute the document_visibility_migration.sql
 */

$mysqli = new mysqli('localhost', 'root', '', 'k-nect2');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error . "\n");
}

echo "Connected to database k-nect2\n";
echo "Running migration...\n\n";

// Read the SQL file
$sqlFile = __DIR__ . '/DATABASE/migrations/document_visibility_migration.sql';
if (!file_exists($sqlFile)) {
    die("Migration file not found: $sqlFile\n");
}

$sql = file_get_contents($sqlFile);

// Split by semicolons but keep multi-line statements together
$queries = [];
$currentQuery = '';
$lines = explode("\n", $sql);

foreach ($lines as $line) {
    $line = trim($line);
    
    // Skip comments and empty lines
    if (empty($line) || str_starts_with($line, '--') || str_starts_with($line, '#')) {
        continue;
    }
    
    // Skip block comments
    if (str_starts_with($line, '/*')) {
        continue;
    }
    
    $currentQuery .= ' ' . $line;
    
    // If line ends with semicolon, execute query
    if (str_ends_with($line, ';')) {
        $queries[] = trim($currentQuery);
        $currentQuery = '';
    }
}

$successCount = 0;
$errorCount = 0;

foreach ($queries as $index => $query) {
    if (empty($query)) continue;
    
    echo "Executing query " . ($index + 1) . "...\n";
    
    if ($mysqli->query($query)) {
        $successCount++;
        // Show first 80 characters of successful query
        $preview = substr(str_replace(["\n", "\r", "  "], ' ', $query), 0, 80);
        echo "  ✓ OK: $preview...\n";
    } else {
        $errorCount++;
        echo "  ✗ ERROR: " . $mysqli->error . "\n";
        $preview = substr(str_replace(["\n", "\r", "  "], ' ', $query), 0, 100);
        echo "  Query: $preview...\n";
    }
}

$mysqli->close();

echo "\n";
echo "========================================\n";
echo "Migration Complete!\n";
echo "========================================\n";
echo "Successful queries: $successCount\n";
echo "Failed queries: $errorCount\n";
echo "\n";

if ($errorCount > 0) {
    echo "⚠ Some queries failed. Please review the errors above.\n";
    exit(1);
} else {
    echo "✓ All queries executed successfully!\n";
    echo "You can now upload documents with the new visibility system.\n";
    exit(0);
}
