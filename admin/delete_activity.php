<?php
// delete_activity.php
require_once 'auth.php';
require_once 'activity.php';

$auth = new Auth();

if (!$auth->isLoggedIn() || !$auth->hasPermission('admin')) {
    header("Location: dashboard.php");
    exit();
}

if (isset($_GET['id'])) {
    $activity = new Activity();
    if ($activity->deleteActivity($_GET['id'])) {
        header("Location: dashboard.php?success=Activity deleted successfully");
    } else {
        header("Location: dashboard.php?error=Failed to delete activity");
    }
} else {
    header("Location: dashboard.php");
}
exit();
?>