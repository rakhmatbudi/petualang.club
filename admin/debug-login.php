<?php
// debug_login.php - Test database connection and login
require_once 'config.php';

echo "<h2>Database Connection Test</h2>";

// Test database connection
try {
    $database = new Database();
    $conn = $database->getConnection();
    
    if ($conn) {
        echo "✅ Database connection successful!<br><br>";
        
        // Check if users table exists
        $query = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'users'";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $table_exists = $stmt->fetch();
        
        if ($table_exists) {
            echo "✅ Users table exists!<br><br>";
            
            // Check users in table
            $query = "SELECT user_id, username, role, LENGTH(password) as pwd_length FROM users";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<h3>Users in database:</h3>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Username</th><th>Role</th><th>Password Length</th></tr>";
            
            if (empty($users)) {
                echo "<tr><td colspan='4'>❌ No users found!</td></tr>";
            } else {
                foreach ($users as $user) {
                    echo "<tr>";
                    echo "<td>" . $user['user_id'] . "</td>";
                    echo "<td>" . $user['username'] . "</td>";
                    echo "<td>" . $user['role'] . "</td>";
                    echo "<td>" . $user['pwd_length'] . "</td>";
                    echo "</tr>";
                }
            }
            echo "</table><br>";
            
            // Test password verification for admin
            $query = "SELECT password FROM users WHERE username = 'admin'";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($admin) {
                echo "<h3>Password Test for 'admin':</h3>";
                $test_password = 'admin123';
                $stored_hash = $admin['password'];
                
                echo "Stored hash: " . $stored_hash . "<br>";
                echo "Testing password: " . $test_password . "<br>";
                
                if (password_verify($test_password, $stored_hash)) {
                    echo "✅ Password verification SUCCESS!<br>";
                } else {
                    echo "❌ Password verification FAILED!<br>";
                    echo "Let's create a new hash:<br>";
                    $new_hash = password_hash($test_password, PASSWORD_DEFAULT);
                    echo "New hash: " . $new_hash . "<br>";
                    
                    // Update the password
                    $update_query = "UPDATE users SET password = :password WHERE username = 'admin'";
                    $update_stmt = $conn->prepare($update_query);
                    $update_stmt->bindParam(':password', $new_hash);
                    
                    if ($update_stmt->execute()) {
                        echo "✅ Password updated successfully!<br>";
                    } else {
                        echo "❌ Failed to update password!<br>";
                    }
                }
            } else {
                echo "❌ Admin user not found!<br>";
            }
            
        } else {
            echo "❌ Users table does not exist!<br>";
            echo "Please run the setup.sql script first.<br>";
        }
        
    } else {
        echo "❌ Database connection failed!<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

// Test creating admin user manually
echo "<br><h3>Manual User Creation Test:</h3>";
try {
    $username = 'admin';
    $password = 'admin123';
    $role = 'admin';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $query = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role) 
              ON CONFLICT (username) DO UPDATE SET password = :password, role = :role";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':role', $role);
    
    if ($stmt->execute()) {
        echo "✅ Admin user created/updated successfully!<br>";
        echo "Username: admin<br>";
        echo "Password: admin123<br>";
        echo "You should now be able to login!<br>";
    } else {
        echo "❌ Failed to create admin user!<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error creating user: " . $e->getMessage() . "<br>";
}

echo "<br><hr><br>";
echo "<a href='login.php'>← Back to Login</a>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
table { border-collapse: collapse; margin: 10px 0; }
th, td { padding: 8px; text-align: left; }
th { background-color: #f2f2f2; }
</style>