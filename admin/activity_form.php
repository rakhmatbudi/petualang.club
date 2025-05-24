<?php
require_once 'auth.php';
require_once 'activity.php';

$auth = new Auth();

if (!$auth->isLoggedIn() || !$auth->hasPermission('admin')) {
    header("Location: dashboard.php");
    exit();
}

$activity = new Activity();
$edit_mode = isset($_GET['id']);
$activity_data = null;

if ($edit_mode) {
    $activity_data = $activity->getActivityById($_GET['id']);
    if (!$activity_data) {
        header("Location: dashboard.php");
        exit();
    }
}

$success = '';
$error = '';

if ($_POST) {
    $data = [
        'club_id' => $_POST['club_id'],
        'activities_name' => $_POST['activities_name'],
        'activities_description' => $_POST['activities_description'],
        'is_highlight' => $_POST['is_highlight'],
        'start_date' => $_POST['start_date'],
        'end_date' => $_POST['end_date'],
        'location' => $_POST['location'],
        'location_lattitude' => $_POST['location_lattitude'],
        'location_longitude' => $_POST['location_longitude']
    ];
    
    if ($edit_mode) {
        if ($activity->updateActivity($_GET['id'], $data)) {
            $success = 'Activity updated successfully!';
            $activity_data = $activity->getActivityById($_GET['id']);
        } else {
            $error = 'Failed to update activity.';
        }
    } else {
        if ($activity->createActivity($data)) {
            $success = 'Activity created successfully!';
            // Clear form data
            $activity_data = null;
            $_POST = []; // Clear POST data
        } else {
            $error = 'Failed to create activity.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $edit_mode ? 'Edit' : 'Add'; ?> Activity - Petualang Admin</title>
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

        /* Container */
        .container {
            max-width: 800px;
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
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        .card-body {
            padding: 1.5rem;
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

        .label-optional {
            color: #666;
            font-weight: normal;
            font-size: 0.8rem;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
            -webkit-appearance: none;
            appearance: none;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            gap: 1rem;
        }

        .form-help {
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.25rem;
        }

        .required-fields {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            color: #1565c0;
        }

        .required {
            color: #dc3545;
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

        .form-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 2rem;
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
            
            .form-row {
                grid-template-columns: 1fr 1fr;
            }
            
            .card-body {
                padding: 2rem;
            }
        }

        /* Focus visible for keyboard navigation */
        .btn:focus-visible,
        input:focus-visible,
        textarea:focus-visible,
        select:focus-visible {
            outline: 2px solid #667eea;
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1><?php echo $edit_mode ? 'Edit' : 'Add New'; ?> Activity</h1>
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="required-fields">
                    <strong>Required Fields:</strong> Only Activity Name and Club ID are required. All other fields are optional.
                </div>

                <form method="POST">
                    <div class="form-group">
                        <label for="club_id">Club ID <span class="required">*</span></label>
                        <input type="number" id="club_id" name="club_id" 
                               value="<?php echo $activity_data ? $activity_data['club_id'] : ''; ?>" required>
                        <div class="form-help">Enter the Club ID this activity belongs to</div>
                    </div>

                    <div class="form-group">
                        <label for="activities_name">Activity Name <span class="required">*</span></label>
                        <input type="text" id="activities_name" name="activities_name" 
                               value="<?php echo $activity_data ? htmlspecialchars($activity_data['activities_name']) : ''; ?>" required>
                        <div class="form-help">Enter a descriptive name for the activity</div>
                    </div>

                    <div class="form-group">
                        <label for="activities_description">
                            Description 
                            <span class="label-optional">(Optional)</span>
                        </label>
                        <textarea id="activities_description" name="activities_description" 
                                  placeholder="Enter a detailed description of the activity..."><?php echo $activity_data ? htmlspecialchars($activity_data['activities_description']) : ''; ?></textarea>
                        <div class="form-help">Provide details about what this activity involves</div>
                    </div>

                    <div class="form-group">
                        <label for="is_highlight">Highlight Activity</label>
                        <select id="is_highlight" name="is_highlight">
                            <option value="no" <?php echo ($activity_data && $activity_data['is_highlight'] === 'no') || !$activity_data ? 'selected' : ''; ?>>No</option>
                            <option value="yes" <?php echo ($activity_data && $activity_data['is_highlight'] === 'yes') ? 'selected' : ''; ?>>Yes</option>
                        </select>
                        <div class="form-help">Choose "Yes" to feature this activity prominently</div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="start_date">
                                Start Date 
                                <span class="label-optional">(Optional)</span>
                            </label>
                            <input type="date" id="start_date" name="start_date" 
                                   value="<?php echo $activity_data ? $activity_data['start_date'] : ''; ?>">
                            <div class="form-help">When does the activity begin?</div>
                        </div>

                        <div class="form-group">
                            <label for="end_date">
                                End Date 
                                <span class="label-optional">(Optional)</span>
                            </label>
                            <input type="date" id="end_date" name="end_date" 
                                   value="<?php echo $activity_data ? $activity_data['end_date'] : ''; ?>">
                            <div class="form-help">When does the activity end?</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="location">
                            Location 
                            <span class="label-optional">(Optional)</span>
                        </label>
                        <input type="text" id="location" name="location" 
                               value="<?php echo $activity_data ? htmlspecialchars($activity_data['location']) : ''; ?>"
                               placeholder="e.g., Bali, Indonesia">
                        <div class="form-help">Where will this activity take place?</div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="location_lattitude">
                                Latitude 
                                <span class="label-optional">(Optional)</span>
                            </label>
                            <input type="text" id="location_lattitude" name="location_lattitude" 
                                   value="<?php echo $activity_data ? $activity_data['location_lattitude'] : ''; ?>" 
                                   placeholder="e.g., -6.2088">
                            <div class="form-help">GPS latitude coordinate</div>
                        </div>

                        <div class="form-group">
                            <label for="location_longitude">
                                Longitude 
                                <span class="label-optional">(Optional)</span>
                            </label>
                            <input type="text" id="location_longitude" name="location_longitude" 
                                   value="<?php echo $activity_data ? $activity_data['location_longitude'] : ''; ?>" 
                                   placeholder="e.g., 106.8456">
                            <div class="form-help">GPS longitude coordinate</div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <?php echo $edit_mode ? 'Update' : 'Create'; ?> Activity
                        </button>
                        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto-populate end date when start date is selected
        document.getElementById('start_date').addEventListener('change', function() {
            const startDate = this.value;
            const endDateField = document.getElementById('end_date');
            
            // If end date is empty and start date is selected, set end date to same as start date
            if (startDate && !endDateField.value) {
                endDateField.value = startDate;
            }
        });

        // Validate that end date is not before start date
        document.getElementById('end_date').addEventListener('change', function() {
            const startDate = document.getElementById('start_date').value;
            const endDate = this.value;
            
            if (startDate && endDate && endDate < startDate) {
                alert('End date cannot be before start date');
                this.value = startDate;
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const clubId = document.getElementById('club_id').value;
            const activityName = document.getElementById('activities_name').value;
            
            if (!clubId || !activityName.trim()) {
                e.preventDefault();
                alert('Please fill in all required fields (Club ID and Activity Name)');
                return false;
            }
        });
    </script>
</body>
</html>