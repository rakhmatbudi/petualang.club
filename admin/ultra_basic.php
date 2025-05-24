<?php
// ultra_basic.php - Test the absolute basics
echo "PHP works!<br>";

// Test 1: Basic session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
echo "Session works!<br>";

// Test 2: Basic require
try {
    require_once 'config.php';
    echo "Config loaded!<br>";
} catch (Exception $e) {
    echo "Config error: " . $e->getMessage() . "<br>";
    die();
}

// Test 3: Basic auth
try {
    require_once 'auth.php';
    $auth = new Auth();
    echo "Auth works!<br>";
} catch (Exception $e) {
    echo "Auth error: " . $e->getMessage() . "<br>";
    die();
}

// Test 4: Check login WITHOUT redirect
try {
    $isLoggedIn = $auth->isLoggedIn();
    echo "Login check works: " . ($isLoggedIn ? 'true' : 'false') . "<br>";
} catch (Exception $e) {
    echo "Login check error: " . $e->getMessage() . "<br>";
    die();
}

// Test 5: Check activity ID
if (!isset($_GET['id'])) {
    echo "No ID provided. This is normal for testing.<br>";
    echo "Try: ultra_basic.php?id=1<br>";
} else {
    echo "ID provided: " . $_GET['id'] . "<br>";
    
    // Test activity
    try {
        require_once 'activity.php';
        $activity = new Activity();
        $activity_data = $activity->getActivityById($_GET['id']);
        
        if ($activity_data) {
            echo "Activity found: " . htmlspecialchars($activity_data['activities_name']) . "<br>";
        } else {
            echo "No activity found with ID: " . $_GET['id'] . "<br>";
        }
    } catch (Exception $e) {
        echo "Activity error: " . $e->getMessage() . "<br>";
    }
}

echo "<hr>";
echo "Basic test completed successfully!<br>";
echo "Now try with ?id=1";
?>