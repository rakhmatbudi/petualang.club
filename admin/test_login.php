<?php
// test_login.php - Test the actual login process step by step
session_start();
require_once 'config.php';

echo "<h2>Login Process Test</h2>";

// Test 1: Check if form was submitted
if ($_POST) {
    echo "<h3>✅ Form submitted successfully!</h3>";
    echo "Username received: '" . $_POST['username'] . "'<br>";
    echo "Password received: '" . $_POST['password'] . "'<br><br>";
    
    // Test 2: Database connection
    try {
        $database = new Database();
        $conn = $database->getConnection();
        echo "✅ Database connection successful!<br><br>";
        
        // Test 3: Query the user
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $query = "SELECT user_id, username, password, role FROM users WHERE username = :username";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        echo "<h3>Database Query Results:</h3>";
        echo "Rows found: " . $stmt->rowCount() . "<br>";
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "✅ User found in database!<br>";
            echo "User ID: " . $user['user_id'] . "<br>";
            echo "Username: " . $user['username'] . "<br>";
            echo "Role: " . $user['role'] . "<br>";
            echo "Stored password hash: " . $user['password'] . "<br><br>";
            
            // Test 4: Password verification
            echo "<h3>Password Verification Test:</h3>";
            echo "Input password: '" . $password . "'<br>";
            
            if (password_verify($password, $user['password'])) {
                echo "✅ Password verification SUCCESS!<br><br>";
                
                // Test 5: Session creation
                echo "<h3>Session Creation Test:</h3>";
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                echo "Session user_id: " . $_SESSION['user_id'] . "<br>";
                echo "Session username: " . $_SESSION['username'] . "<br>";
                echo "Session role: " . $_SESSION['role'] . "<br>";
                echo "✅ Session created successfully!<br><br>";
                
                echo "<h3>🎉 LOGIN SHOULD WORK!</h3>";
                echo "<a href='dashboard.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Dashboard</a><br><br>";
                
            } else {
                echo "❌ Password verification FAILED!<br>";
                echo "This shouldn't happen since debug_login.php showed it working...<br>";
            }
            
        } else {
            echo "❌ User not found in database!<br>";
            echo "Username searched: '" . $username . "'<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ Database error: " . $e->getMessage() . "<br>";
    }
    
} else {
    // Show the test form
    echo "<p>Use this form to test the login process:</p>";
}
?>

<form method="POST" style="background: #f9f9f9; padding: 20px; border-radius: 10px; max-width: 400px;">
    <h3>Test Login Form</h3>
    
    <div style="margin-bottom: 15px;">
        <label for="username" style="display: block; margin-bottom: 5px; font-weight: bold;">Username:</label>
        <input type="text" id="username" name="username" value="admin" 
               style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
    </div>
    
    <div style="margin-bottom: 15px;">
        <label for="password" style="display: block; margin-bottom: 5px; font-weight: bold;">Password:</label>
        <input type="password" id="password" name="password" value="admin123"
               style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
    </div>
    
    <button type="submit" style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">
        Test Login Process
    </button>
</form>

<br>
<a href="login.php">← Back to Real Login Page</a>

<style>
body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
h3 { color: #333; margin-top: 20px; }
</style>