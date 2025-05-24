<?php
// check_login.php - Test your existing login.php
echo "<h1>Checking Login System</h1>";
echo "<style>body{font-family:Arial;padding:20px;} .error{background:#ffe6e6;padding:10px;margin:10px 0;} .success{background:#e6ffe6;padding:10px;margin:10px 0;}</style>";

// Test 1: Check if login.php exists
echo "<h2>Test 1: login.php File</h2>";
if (file_exists('login.php')) {
    echo "<div class='success'>✓ login.php exists</div>";
    
    // Check if it's readable
    if (is_readable('login.php')) {
        echo "<div class='success'>✓ login.php is readable</div>";
    } else {
        echo "<div class='error'>✗ login.php is not readable - check permissions</div>";
    }
    
    // Check file size
    $size = filesize('login.php');
    echo "File size: " . $size . " bytes<br>";
    if ($size == 0) {
        echo "<div class='error'>✗ login.php is empty!</div>";
    }
    
} else {
    echo "<div class='error'>✗ login.php does not exist</div>";
    echo "This is why you're getting 500 errors!<br>";
}

// Test 2: Check .htaccess
echo "<h2>Test 2: .htaccess File</h2>";
if (file_exists('.htaccess')) {
    echo "<div class='success'>✓ .htaccess exists</div>";
    echo "Contents:<br>";
    echo "<pre>" . htmlspecialchars(file_get_contents('.htaccess')) . "</pre>";
} else {
    echo "<div class='error'>✗ .htaccess not found</div>";
}

// Test 3: Test direct access to login.php
echo "<h2>Test 3: Direct Login Access</h2>";
echo "<p><a href='login.php' target='_blank'>Click here to test login.php directly</a></p>";

// Test 4: Show what happens when we access activity_photos.php
echo "<h2>Test 4: Session Status</h2>";
session_start();
echo "Session status: " . session_status() . "<br>";
echo "Session ID: " . session_id() . "<br>";
echo "Session data: <pre>" . print_r($_SESSION, true) . "</pre>";

// Test 5: Manual login for testing
echo "<h2>Test 5: Manual Login (For Testing)</h2>";
if (isset($_GET['manual_login'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'test_admin';
    $_SESSION['role'] = 'admin';
    echo "<div class='success'>✓ Manual login completed!</div>";
    echo "<p><a href='activity_photos.php?id=1'>Now try activity_photos.php</a></p>";
} else {
    echo "<p><a href='?manual_login=1'>Click here to manually login for testing</a></p>";
}

echo "<hr>";
echo "<strong>Summary:</strong><br>";
echo "1. If login.php is missing/broken, fix it<br>";
echo "2. If .htaccess is causing issues, temporarily rename it<br>";
echo "3. Use manual login above to test activity_photos.php<br>";
?>