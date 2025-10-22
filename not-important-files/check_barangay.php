<?php
$mysqli = new mysqli('localhost', 'root', '', 'k-nect2');
echo "Checking barangay table structure:\n";
$result = $mysqli->query('DESCRIBE barangay');
while ($row = $result->fetch_assoc()) {
    printf("%-20s | %-20s | %-10s | %-10s\n", $row['Field'], $row['Type'], $row['Key'], $row['Null']);
}

echo "\n\nChecking for barangay_id column:\n";
$result = $mysqli->query("SHOW COLUMNS FROM barangay LIKE 'barangay_id'");
if ($result->num_rows > 0) {
    echo "✓ barangay_id column exists\n";
    $row = $result->fetch_assoc();
    echo "Type: " . $row['Type'] . "\n";
    echo "Key: " . $row['Key'] . "\n";
} else {
    echo "✗ barangay_id column does NOT exist\n";
}

echo "\n\nChecking barangay table indexes:\n";
$result = $mysqli->query('SHOW INDEXES FROM barangay');
while ($row = $result->fetch_assoc()) {
    printf("%-20s | %-20s | %-10s\n", $row['Key_name'], $row['Column_name'], $row['Index_type']);
}
