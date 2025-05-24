<?php
// debug.php - Save this as debug.php and visit it to see the exact error
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

echo "<h1>Debug Script - Finding the 500 Error</h1>";
echo "<style>body{font-family:Arial;padding:20px;} .error{background:#ffe6e6;padding:10px;margin:10px 0;border-radius:5px;} .success{background:#e6ffe6;padding:10px;margin:10px 0;border-radius:5px;}</style>";

// 1. Test basic PHP
echo "<div class='success'>✓ PHP is working</div>";
echo "PHP Version: " . phpversion() . "<br>";

// 2. Test session
echo "<h2>Testing Session</h2>";
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

// 3. Test file existence
echo "<h2>Testing File Existence</h2>";
$files = ['config.php', 'auth.php', 'activity.php'];
foreach ($files as $file) {
    if (file_exists($file)) {
        echo "<div class='success'>✓ Found: $file</div>";
    } else {
        echo "<div class='error'>✗ Missing: $file</div>";
    }
}

// 4. Test config.php
echo "<h2>Testing config.php</h2>";
try {
    require_once 'config.php';
    echo "<div class='success'>✓ config.php loaded successfully</div>";
    
    if (class_exists('Database')) {
        echo "<div class='success'>✓ Database class exists</div>";
    } else {
        echo "<div class='error'>✗ Database class not found</div>";
    }
    
    if (class_exists('Config')) {
        echo "<div class='success'>✓ Config class exists</div>";
        
        // Test Config methods
        $methods = ['getMaxFileSizeFormatted', 'getUploadDir', 'ALLOWED_IMAGE_TYPES'];
        foreach ($methods as $method) {
            if (is_array($method)) continue;
            if (method_exists('Config', $method)) {
                echo "<div class='success'>✓ Config::$method() exists</div>";
            } else {
                echo "<div class='error'>✗ Config::$method() missing</div>";
            }
        }
        
        if (defined('Config::ALLOWED_IMAGE_TYPES')) {
            echo "<div class='success'>✓ Config::ALLOWED_IMAGE_TYPES defined</div>";
        } else {
            echo "<div class='error'>✗ Config::ALLOWED_IMAGE_TYPES not defined</div>";
        }
    } else {
        echo "<div class='error'>✗ Config class not found</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>✗ Config Error: " . $e->getMessage() . "</div>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
} catch (Error $e) {
    echo "<div class='error'>✗ Config Fatal Error: " . $e->getMessage() . "</div>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
}

// 5. Test auth.php
echo "<h2>Testing auth.php</h2>";
try {
    require_once 'auth.php';
    echo "<div class='success'>✓ auth.php loaded successfully</div>";
    
    if (class_exists('Auth')) {
        echo "<div class='success'>✓ Auth class exists</div>";
        $auth = new Auth();
        echo "<div class='success'>✓ Auth instance created</div>";
    } else {
        echo "<div class='error'>✗ Auth class not found</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>✗ Auth Error: " . $e->getMessage() . "</div>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
} catch (Error $e) {
    echo "<div class='error'>✗ Auth Fatal Error: " . $e->getMessage() . "</div>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
}

// 6. Test activity.php
echo "<h2>Testing activity.php</h2>";
try {
    require_once 'activity.php';
    echo "<div class='success'>✓ activity.php loaded successfully</div>";
    
    if (class_exists('Activity')) {
        echo "<div class='success'>✓ Activity class exists</div>";
        $activity = new Activity();
        echo "<div class='success'>✓ Activity instance created</div>";
    } else {
        echo "<div class='error'>✗ Activity class not found</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>✗ Activity Error: " . $e->getMessage() . "</div>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
} catch (Error $e) {
    echo "<div class='error'>✗ Activity Fatal Error: " . $e->getMessage() . "</div>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
}

// 7. Test database connection
echo "<h2>Testing Database Connection</h2>";
try {
    if (class_exists('Database')) {
        $database = new Database();
        $conn = $database->getConnection();
        if ($conn) {
            echo "<div class='success'>✓ Database connection successful</div>";
        } else {
            echo "<div class='error'>✗ Database connection failed</div>";
        }
    }
} catch (Exception $e) {
    echo "<div class='error'>✗ Database Error: " . $e->getMessage() . "</div>";
} catch (Error $e) {
    echo "<div class='error'>✗ Database Fatal Error: " . $e->getMessage() . "</div>";
}

echo "<h2>PHP Info</h2>";
echo "Error Reporting Level: " . error_reporting() . "<br>";
echo "Display Errors: " . ini_get('display_errors') . "<br>";
echo "Memory Limit: " . ini_get('memory_limit') . "<br>";
echo "Max Execution Time: " . ini_get('max_execution_time') . "<br>";

echo "<hr>";
echo "<strong>If you see this message, basic PHP is working. Check above for any red error boxes.</strong>";
?>