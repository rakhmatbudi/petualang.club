<?php
// step_by_step_photos.php - Build activity_photos.php step by step
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "DEBUG: Starting step-by-step build...<br>";

// Safe session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
echo "DEBUG: Session started<br>";

try {
    require_once 'config.php';
    require_once 'auth.php';
    require_once 'activity.php';
    echo "DEBUG: All files included<br>";
} catch (Exception $e) {
    die("Error loading required files: " . $e->getMessage());
}

$auth = new Auth();
echo "DEBUG: Auth created<br>";

// Skip login check for debugging
echo "DEBUG: Skipping login check for testing<br>";

// Check for activity ID
if (!isset($_GET['id'])) {
    die("DEBUG: No activity ID provided. Add ?id=1 to URL");
}

$activity = new Activity();
$activity_id = $_GET['id'];
echo "DEBUG: Activity ID: $activity_id<br>";

// Initialize variables
$activity_data = null;
$photos = [];
$success = '';
$error = '';

try {
    $activity_data = $activity->getActivityById($activity_id);
    $photos = $activity->getActivityPhotos($activity_id);
    echo "DEBUG: Got " . count($photos) . " photos<br>";
} catch (Exception $e) {
    $error = "Database error: " . $e->getMessage();
    echo "DEBUG: Database error: " . $error . "<br>";
}

if (!$activity_data) {
    die("DEBUG: No activity found with ID: $activity_id");
}

echo "DEBUG: Activity found: " . htmlspecialchars($activity_data['activities_name']) . "<br>";

// Handle photo upload (skip for now)
echo "DEBUG: Skipping photo upload handling<br>";

// Handle photo deletion (skip for now)  
echo "DEBUG: Skipping photo deletion handling<br>";

// Helper function
function hasPermission($auth, $permission) {
    if (method_exists($auth, 'hasPermission')) {
        return $auth->hasPermission($permission);
    }
    return isset($_SESSION['role']) && ($_SESSION['role'] === $permission || $_SESSION['role'] === 'admin');
}
echo "DEBUG: hasPermission function defined<br>";

echo "DEBUG: About to start HTML...<br>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step by Step Test - <?php echo htmlspecialchars($activity_data['activities_name'] ?? 'Unknown Activity'); ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .debug { background: #f0f0f0; padding: 10px; margin: 10px 0; }
        .success { background: #e6ffe6; padding: 10px; margin: 10px 0; }
        .error { background: #ffe6e6; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="debug">
        <h1>Step by Step Activity Photos Test</h1>
        <p>Activity: <?php echo htmlspecialchars($activity_data['activities_name'] ?? 'Unknown'); ?></p>
        <p>Photos found: <?php echo count($photos); ?></p>
        
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
    </div>

    <h2>Testing Config Usage in HTML:</h2>
    <p>Max file size: <?php echo Config::getMaxFileSizeFormatted(); ?></p>
    <p>Allowed formats: <?php echo strtoupper(implode(', ', Config::ALLOWED_IMAGE_TYPES)); ?></p>
    <p>Accept attribute: <?php echo implode(',', array_map(function($ext) { return 'image/' . $ext; }, Config::ALLOWED_IMAGE_TYPES)); ?></p>
    <p>Count: <?php echo count(Config::ALLOWED_IMAGE_TYPES); ?></p>

    <h2>Testing Photos Loop:</h2>
    <?php if (empty($photos)): ?>
        <p>No photos to display</p>
    <?php else: ?>
        <ul>
            <?php foreach ($photos as $photo): 
                $filename = $photo['path'] ?? '';
                echo "<li>Photo ID: " . ($photo['photo_id'] ?? 'N/A') . " - Filename: " . htmlspecialchars($filename) . "</li>";
            endforeach; ?>
        </ul>
    <?php endif; ?>

    <h2>JavaScript Test:</h2>
    <script>
        console.log('JavaScript is working');
        console.log('Max file size from PHP:', <?php echo Config::MAX_FILE_SIZE; ?>);
    </script>
    
    <p><strong>If you see this message, the step-by-step version works fine!</strong></p>
</body>
</html>