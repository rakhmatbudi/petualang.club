<?php
// dashboard.php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session first
session_start();

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

$activity = new Activity();

// Handle success/error messages from redirects
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// Initialize variables
$activities = [];
$stats = [
    'total_activities' => 0,
    'highlighted_activities' => 0,
    'total_photos' => 0,
    'activities_this_month' => 0
];

try {
    $activities = $activity->getAllActivities();
    $stats = $activity->getActivityStats();
} catch (Exception $e) {
    $error = "Database connection error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petualang Admin Dashboard</title>
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
            font-size: 1.25rem;
            font-weight: 600;
        }

        .user-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .user-details {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .role-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            white-space: nowrap;
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

        /* Stats Grid - Mobile First */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.25rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-number {
            font-size: 1.75rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: #666;
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Section Headers */
        .section-header {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .section-header h2 {
            color: #333;
            font-size: 1.25rem;
            font-weight: 600;
        }

        /* Activity Items - Replace Table with Cards on Mobile */
        .activity-grid {
            display: grid;
            gap: 1rem;
        }

        .activity-item {
            background: white;
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #667eea;
        }

        .activity-item.highlighted {
            border-left-color: #28a745;
        }

        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
            gap: 1rem;
        }

        .activity-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }

        .activity-id {
            background: #f8f9fa;
            color: #666;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            white-space: nowrap;
        }

        .activity-description {
            color: #666;
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
            line-height: 1.5;
        }

        .activity-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .meta-item {
            font-size: 0.8rem;
        }

        .meta-label {
            color: #999;
            font-weight: 500;
            display: block;
            margin-bottom: 0.25rem;
        }

        .meta-value {
            color: #333;
            font-weight: 500;
        }

        .no-date {
            color: #999;
            font-style: italic;
        }

        .highlight-badge {
            background: #28a745;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }

        /* Actions - Mobile Friendly */
        .activity-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .activity-actions .btn {
            font-size: 0.8rem;
            padding: 0.6rem 0.8rem;
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
                font-size: 1.5rem;
            }
            
            .container {
                padding: 2rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(4, 1fr);
            }
            
            .stat-number {
                font-size: 2rem;
            }
            
            .section-header {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
            
            .section-header h2 {
                font-size: 1.5rem;
            }
            
            .activity-meta {
                grid-template-columns: repeat(4, 1fr);
            }
            
            .activity-actions {
                grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
                max-width: 400px;
            }
        }

        @media (min-width: 1024px) {
            .activity-grid {
                grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>Petualang Admin Dashboard</h1>
            <div class="user-info">
                <div class="user-details">
                    <span>Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User'; ?></span>
                    <span class="role-badge"><?php echo isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'User'; ?></span>
                </div>
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total_activities']; ?></div>
                <div class="stat-label">Total Activities</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['highlighted_activities']; ?></div>
                <div class="stat-label">Highlighted</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total_photos']; ?></div>
                <div class="stat-label">Total Photos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['activities_this_month']; ?></div>
                <div class="stat-label">This Month</div>
            </div>
        </div>

        <div class="section-header">
            <h2>Activities Management</h2>
            <?php if (method_exists($auth, 'hasPermission') && $auth->hasPermission('admin')): ?>
                <a href="activity_form.php" class="btn btn-primary">Add New Activity</a>
            <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="activity_form.php" class="btn btn-primary">Add New Activity</a>
            <?php endif; ?>
        </div>

        <?php if (empty($activities)): ?>
            <div class="empty-state">
                <div class="icon">📅</div>
                <h3>No activities found</h3>
                <p>Create your first activity to get started!</p>
                <?php if ((method_exists($auth, 'hasPermission') && $auth->hasPermission('admin')) || (isset($_SESSION['role']) && $_SESSION['role'] === 'admin')): ?>
                    <br>
                    <a href="activity_form.php" class="btn btn-primary">Add First Activity</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="activity-grid">
                <?php foreach ($activities as $act): ?>
                    <div class="activity-item <?php echo ($act['is_highlight'] === 'yes') ? 'highlighted' : ''; ?>">
                        <div class="activity-header">
                            <div>
                                <div class="activity-title"><?php echo htmlspecialchars($act['activities_name'] ?? ''); ?></div>
                                <?php if (!empty($act['activities_description'])): ?>
                                    <div class="activity-description">
                                        <?php 
                                        $description = htmlspecialchars($act['activities_description']);
                                        echo strlen($description) > 150 ? substr($description, 0, 150) . '...' : $description;
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="activity-id">#<?php echo str_pad($act['activity_id'] ?? 0, 3, '0', STR_PAD_LEFT); ?></div>
                        </div>
                        
                        <div class="activity-meta">
                            <div class="meta-item">
                                <span class="meta-label">Location</span>
                                <span class="meta-value">
                                    <?php if (!empty($act['location'])): ?>
                                        <?php echo htmlspecialchars($act['location']); ?>
                                    <?php else: ?>
                                        <span class="no-date">No location</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Start Date</span>
                                <span class="meta-value">
                                    <?php if (!empty($act['start_date'])): ?>
                                        <?php echo date('M d, Y', strtotime($act['start_date'])); ?>
                                    <?php else: ?>
                                        <span class="no-date">No start date</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">End Date</span>
                                <span class="meta-value">
                                    <?php if (!empty($act['end_date'])): ?>
                                        <?php echo date('M d, Y', strtotime($act['end_date'])); ?>
                                    <?php else: ?>
                                        <span class="no-date">No end date</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Highlight</span>
                                <span class="meta-value">
                                    <?php if ($act['is_highlight'] === 'yes'): ?>
                                        <span class="highlight-badge">Yes</span>
                                    <?php else: ?>
                                        <span class="no-date">No</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="activity-actions">
                            <a href="activity_photos.php?id=<?php echo $act['activity_id']; ?>" class="btn btn-secondary">Photos</a>
                            <?php if ((method_exists($auth, 'hasPermission') && $auth->hasPermission('admin')) || (isset($_SESSION['role']) && $_SESSION['role'] === 'admin')): ?>
                                <a href="activity_form.php?id=<?php echo $act['activity_id']; ?>" class="btn btn-primary">Edit</a>
                                <a href="delete_activity.php?id=<?php echo $act['activity_id']; ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this activity? This will also delete all associated photos.')">Delete</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>