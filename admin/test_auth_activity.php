<?php
// test_auth_activity.php - Test the other components
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Testing Auth and Activity Classes</h1>";
echo "<style>body{font-family:Arial;padding:20px;} .error{background:#ffe6e6;padding:10px;margin:10px 0;border-radius:5px;} .success{background:#e6ffe6;padding:10px;margin:10px 0;border-radius:5px;}</style>";

// Test session start
echo "<h2>Test 1: Session Start</h2>";
try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
        echo "<div class='success'>✓ Session started successfully</div>";
    } else {
        echo "<div class='success'>✓ Session already active</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>✗ Session Error: " . $e->getMessage() . "</div>";
}

// Test config.php
echo "<h2>Test 2: Config</h2>";
try {
    require_once 'config.php';
    echo "<div class='success'>✓ config.php loaded</div>";
} catch (Exception $e) {
    echo "<div class='error'>✗ Config Error: " . $e->getMessage() . "</div>";
    die();
}

// Test auth.php
echo "<h2>Test 3: Auth Class</h2>";
try {
    require_once 'auth.php';
    echo "<div class='success'>✓ auth.php loaded</div>";
    
    if (class_exists('Auth')) {
        echo "<div class='success'>✓ Auth class exists</div>";
        $auth = new Auth();
        echo "<div class='success'>✓ Auth instance created</div>";
        
        // Test methods
        if (method_exists($auth, 'isLoggedIn')) {
            $isLoggedIn = $auth->isLoggedIn();
            echo "<div class='success'>✓ isLoggedIn() works: " . ($isLoggedIn ? 'true' : 'false') . "</div>";
        } else {
            echo "<div class='error'>✗ isLoggedIn() method missing</div>";
        }
        
    } else {
        echo "<div class='error'>✗ Auth class not found</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>✗ Auth Error: " . $e->getMessage() . "</div>";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "<br>";
} catch (Error $e) {
    echo "<div class='error'>✗ Auth Fatal Error: " . $e->getMessage() . "</div>";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "<br>";
}

// Test activity.php
echo "<h2>Test 4: Activity Class</h2>";
try {
    require_once 'activity.php';
    echo "<div class='success'>✓ activity.php loaded</div>";
    
    if (class_exists('Activity')) {
        echo "<div class='success'>✓ Activity class exists</div>";
        $activity = new Activity();
        echo "<div class='success'>✓ Activity instance created</div>";
        
        // Test database connection through Activity
        try {
            $stats = $activity->getActivityStats();
            echo "<div class='success'>✓ Database connection works - got stats</div>";
        } catch (Exception $e) {
            echo "<div class='error'>✗ Database Error: " . $e->getMessage() . "</div>";
        }
        
    } else {
        echo "<div class='error'>✗ Activity class not found</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>✗ Activity Error: " . $e->getMessage() . "</div>";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "<br>";
} catch (Error $e) {
    echo "<div class='error'>✗ Activity Fatal Error: " . $e->getMessage() . "</div>";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "<br>";
}

// Test the hasPermission function
echo "<h2>Test 5: hasPermission Function</h2>";
try {
    function hasPermission($auth, $permission) {
        if (method_exists($auth, 'hasPermission')) {
            return $auth->hasPermission($permission);
        }
        return isset($_SESSION['role']) && ($_SESSION['role'] === $permission || $_SESSION['role'] === 'admin');
    }
    
    if (isset($auth)) {
        $result = hasPermission($auth, 'admin');
        echo "<div class='success'>✓ hasPermission function works: " . ($result ? 'true' : 'false') . "</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>✗ hasPermission Error: " . $e->getMessage() . "</div>";
}

// Test with fake activity ID
echo "<h2>Test 6: Activity Methods with ID</h2>";
if (isset($activity)) {
    try {
        // Test getActivityById with a fake ID
        $activity_data = $activity->getActivityById(999);
        if ($activity_data) {
            echo "<div class='success'>✓ getActivityById works - found activity</div>";
        } else {
            echo "<div class='success'>✓ getActivityById works - no activity found (expected)</div>";
        }
        
        // Test getActivityPhotos 
        $photos = $activity->getActivityPhotos(999);
        echo "<div class='success'>✓ getActivityPhotos works - found " . count($photos) . " photos</div>";
        
    } catch (Exception $e) {
        echo "<div class='error'>✗ Activity Method Error: " . $e->getMessage() . "</div>";
    }
}

echo "<hr>";
echo "<strong>All tests completed. Look for any ✗ errors above.</strong>";
?>