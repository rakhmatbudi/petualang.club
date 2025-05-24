<?php
// simple_test.php - Save this and test it first
echo "PHP is working!<br>";
echo "PHP Version: " . phpversion() . "<br>";

// Test 1: Basic config loading
echo "<hr><h3>Test 1: Loading config.php</h3>";
try {
    require_once 'config.php';
    echo "✓ config.php loaded<br>";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
    die();
}

// Test 2: Check if Config class exists
echo "<h3>Test 2: Config class</h3>";
if (class_exists('Config')) {
    echo "✓ Config class exists<br>";
} else {
    echo "✗ Config class not found<br>";
    die();
}

// Test 3: Check specific constant
echo "<h3>Test 3: ALLOWED_IMAGE_TYPES constant</h3>";
try {
    $types = Config::ALLOWED_IMAGE_TYPES;
    echo "✓ ALLOWED_IMAGE_TYPES: " . print_r($types, true) . "<br>";
} catch (Exception $e) {
    echo "✗ Error accessing ALLOWED_IMAGE_TYPES: " . $e->getMessage() . "<br>";
}

// Test 4: Check method
echo "<h3>Test 4: getMaxFileSizeFormatted method</h3>";
try {
    $size = Config::getMaxFileSizeFormatted();
    echo "✓ Max file size: " . $size . "<br>";
} catch (Exception $e) {
    echo "✗ Error calling getMaxFileSizeFormatted: " . $e->getMessage() . "<br>";
}

// Test 5: The problematic line from your activity_photos.php
echo "<h3>Test 5: Exact line from activity_photos.php</h3>";
try {
    $result = strtoupper(implode(', ', Config::ALLOWED_IMAGE_TYPES));
    echo "✓ Result: " . $result . "<br>";
} catch (Exception $e) {
    echo "✗ Error with the problematic line: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "<br>";
}

echo "<hr>";
echo "If you see this message, basic PHP is working. Look for any ✗ errors above.";
?>