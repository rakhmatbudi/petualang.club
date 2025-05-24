<?php
//activity_photos.php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Safe session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    require_once 'config.php';
    require_once 'auth.php';
    require_once 'activity.php';
} catch (Exception $e) {
    die("Error loading required files: " . $e->getMessage());
}

$auth = new Auth();

if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$activity = new Activity();
$activity_id = $_GET['id'];

// Initialize variables
$activity_data = null;
$photos = [];
$success = '';
$error = '';

try {
    $activity_data = $activity->getActivityById($activity_id);
    $photos = $activity->getActivityPhotos($activity_id);
} catch (Exception $e) {
    $error = "Database error: " . $e->getMessage();
}

if (!$activity_data) {
    header("Location: dashboard.php");
    exit();
}

// Handle photo upload
if ($_POST && isset($_FILES['photo'])) {
    try {
        $result = $activity->addActivityPhoto($activity_id, $_FILES['photo'], $_POST['description'] ?? '');
        
        if ($result['success']) {
            $success = $result['message'];
            $photos = $activity->getActivityPhotos($activity_id); // Refresh photos
        } else {
            $error = $result['message'];
        }
    } catch (Exception $e) {
        $error = "Upload error: " . $e->getMessage();
    }
}

// Handle photo deletion
if (isset($_GET['delete_photo'])) {
    try {
        if ($activity->deleteActivityPhoto($_GET['delete_photo'])) {
            $success = 'Photo deleted successfully!';
            $photos = $activity->getActivityPhotos($activity_id); // Refresh photos
        } else {
            $error = 'Failed to delete photo.';
        }
    } catch (Exception $e) {
        $error = "Delete error: " . $e->getMessage();
    }
}

// Helper function to check user permissions
function hasPermission($auth, $permission) {
    if (method_exists($auth, 'hasPermission')) {
        return $auth->hasPermission($permission);
    }
    // Fallback to session check
    return isset($_SESSION['role']) && ($_SESSION['role'] === $permission || $_SESSION['role'] === 'admin');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Photos - <?php echo htmlspecialchars($activity_data['activities_name'] ?? 'Unknown Activity'); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            line-height: 1.6;
        }

        /* Header - Mobile First */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-content {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .header h1 {
            font-size: 1.1rem;
            font-weight: 600;
            line-height: 1.3;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }

        /* Buttons - Touch Friendly */
        .btn {
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            text-align: center;
            min-height: 44px;
            line-height: 1.2;
            white-space: nowrap;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn:hover, .btn:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn:active {
            transform: translateY(0);
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .card-header {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            background: #f8f9fa;
        }

        .card-header h3 {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Upload Info */
        .upload-info {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            font-size: 0.8rem;
            color: #1565c0;
            line-height: 1.5;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        /* Stats Bar */
        .stats-bar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 1.25rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.75rem;
            color: #666;
            font-weight: 500;
        }

        /* Photo Grid */
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .photo-item {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s ease;
        }

        .photo-item:hover {
            transform: translateY(-2px);
        }

        .photo-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .photo-info {
            padding: 1rem;
        }

        .photo-description {
            color: #666;
            margin-bottom: 0.75rem;
            min-height: 1.2em;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .photo-meta {
            font-size: 0.75rem;
            color: #999;
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .empty-state {
            text-align: center;
            padding: 2rem 1rem;
            color: #666;
        }

        .empty-state .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Desktop Improvements */
        @media (min-width: 768px) {
            .header {
                padding: 1.5rem 2rem;
            }
            
            .header-content {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
            
            .header h1 {
                font-size: 1.3rem;
            }
            
            .container {
                padding: 2rem;
            }
            
            .card-body {
                padding: 2rem;
            }
            
            .photo-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
            
            .stats-bar {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .header h1 {
                font-size: 1.5rem;
            }
            
            .photo-grid {
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            }
        }

        /* Focus visible for keyboard navigation */
        .btn:focus-visible,
        input:focus-visible,
        textarea:focus-visible {
            outline: 2px solid #667eea;
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>Photos - <?php echo htmlspecialchars($activity_data['activities_name'] ?? 'Unknown Activity'); ?></h1>
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <div class="container">
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (hasPermission($auth, 'admin') || hasPermission($auth, 'relawan')): ?>
            <div class="card">
                <div class="card-header">
                    <h3>Upload New Photo</h3>
                </div>
                <div class="card-body">
                    <div class="upload-info">
                        <strong>Upload Guidelines:</strong><br>
                        • Maximum file size: <?php echo Config::getMaxFileSizeFormatted(); ?><br>
                        • Allowed formats: <?php echo strtoupper(implode(', ', Config::ALLOWED_IMAGE_TYPES)); ?><br>
                        • Photos will be stored securely on the server
                    </div>
                    
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="photo">Select Photo</label>
                            <input type="file" id="photo" name="photo" 
                                   accept="<?php echo implode(',', array_map(function($ext) { return 'image/' . $ext; }, Config::ALLOWED_IMAGE_TYPES)); ?>" 
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description (Optional)</label>
                            <textarea id="description" name="description" 
                                      placeholder="Enter a description for this photo..." 
                                      rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload Photo</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <div class="stats-bar">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo count($photos); ?></div>
                        <div class="stat-label">Total Photos</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?php echo Config::getMaxFileSizeFormatted(); ?></div>
                        <div class="stat-label">Max File Size</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?php echo count(Config::ALLOWED_IMAGE_TYPES); ?></div>
                        <div class="stat-label">Formats Allowed</div>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <h3>Activity Photos</h3>
                
                <?php if (empty($photos)): ?>
                    <div class="empty-state">
                        <div class="icon">📸</div>
                        <h4>No photos uploaded yet</h4>
                        <p>Upload the first photo to get started!</p>
                        <?php if (hasPermission($auth, 'admin') || hasPermission($auth, 'relawan')): ?>
                            <br>
                            <a href="#" class="btn btn-primary" onclick="document.getElementById('photo').click(); return false;">Choose Photo</a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="photo-grid">
                        <?php foreach ($photos as $photo): 
                            // Get filename from database 'path' column
                            $filename = $photo['path'] ?? '';
                            
                            // Build paths based on what's stored in database
                            if (!empty($filename)) {
                                // If it's just a filename, build full paths
                                if (strpos($filename, '/') === false) {
                                    // Just filename stored (e.g., "image.jpg")
                                    $webPath = '/photo/activity_photos/' . $filename;
                                    $serverPath = '../photo/activity_photos/' . $filename;
                                } else {
                                    // Path stored (e.g., "../photo/activity_photos/image.jpg")
                                    $webPath = '/' . str_replace('../', '', $filename);
                                    $serverPath = $filename;
                                }
                                $fileExists = file_exists($serverPath);
                            } else {
                                $webPath = '';
                                $serverPath = '';
                                $fileExists = false;
                            }
                        ?>
                            <div class="photo-item">
                                <?php if (!empty($filename) && $fileExists): ?>
                                    <img src="<?php echo htmlspecialchars($webPath); ?>" 
                                         alt="<?php echo htmlspecialchars($photo['description'] ?? ''); ?>"
                                         loading="lazy">
                                <?php else: ?>
                                    <div style="height: 200px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; color: #999; font-size: 2rem; flex-direction: column;">
                                        📷
                                        <div style="font-size: 0.8rem; margin-top: 0.5rem;">
                                            <?php echo empty($filename) ? 'No filename stored' : 'Photo file missing'; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="photo-info">
                                    <?php if (!empty($photo['description'])): ?>
                                        <div class="photo-description">
                                            <?php echo htmlspecialchars($photo['description']); ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="photo-description" style="font-style: italic; color: #999;">
                                            No description provided
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="photo-meta">
                                        Filename: <?php echo htmlspecialchars($filename ?: 'N/A'); ?><br>
                                        ID: #<?php echo $photo['photo_id'] ?? 'N/A'; ?><br>
                                        <?php if (!empty($webPath)): ?>
                                            Web URL: <?php echo htmlspecialchars($webPath); ?><br>
                                        <?php endif; ?>
                                        <?php if (!empty($serverPath)): ?>
                                            Server Path: <?php echo htmlspecialchars($serverPath); ?><br>
                                        <?php endif; ?>
                                        Status: <?php echo $fileExists ? '✅ Found' : '❌ Missing'; ?>
                                        <?php if ($fileExists && !empty($serverPath)): ?>
                                            <br>Size: <?php echo number_format(filesize($serverPath) / 1024, 1); ?> KB
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if (hasPermission($auth, 'admin')): ?>
                                        <a href="?id=<?php echo $activity_id; ?>&delete_photo=<?php echo $photo['photo_id']; ?>" 
                                           class="btn btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this photo? This action cannot be undone.')">
                                            Delete Photo
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Form validation for photo upload
        document.querySelector('form[enctype="multipart/form-data"]')?.addEventListener('submit', function(e) {
            const photoInput = document.getElementById('photo');
            
            if (!photoInput.files || photoInput.files.length === 0) {
                e.preventDefault();
                alert('Please select a photo to upload');
                return false;
            }
            
            const file = photoInput.files[0];
            const maxSize = <?php echo Config::MAX_FILE_SIZE; ?>;
            
            if (file.size > maxSize) {
                e.preventDefault();
                alert('File is too large. Maximum size is <?php echo Config::getMaxFileSizeFormatted(); ?>');
                return false;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Uploading...';
            submitBtn.disabled = true;
            
            // Re-enable if there's an error (form doesn't submit)
            setTimeout(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }, 10000);
        });

        // Preview selected image
        document.getElementById('photo')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                console.log('Selected file:', file.name, 'Size:', Math.round(file.size / 1024) + 'KB');
            }
        });
    </script>
</body>
</html>