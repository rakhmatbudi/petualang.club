<?php
// auth.php - Complete authentication class
require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Auth {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    /**
     * Login user with username and password
     */
    public function login($username, $password) {
        // Validate input
        if (empty($username) || empty($password)) {
            return false;
        }
        
        try {
            // For testing, use hardcoded credentials first
            // Replace this with database query once you have users table set up
            
            $testUsers = [
                'admin' => [
                    'id' => 1,
                    'username' => 'admin',
                    'password' => 'admin123',
                    'role' => 'admin'
                ],
                'user' => [
                    'id' => 2,
                    'username' => 'user',
                    'password' => 'password',
                    'role' => 'user'
                ]
            ];
            
            // Check hardcoded users first
            if (isset($testUsers[$username]) && $testUsers[$username]['password'] === $password) {
                $user = $testUsers[$username];
                
                // Set session variables - THIS IS THE KEY PART!
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['logged_in'] = true;
                $_SESSION['login_time'] = time();
                
                // Security: regenerate session ID
                session_regenerate_id(true);
                
                return true;
            }
            
            // If hardcoded users don't match, try database
            if ($this->db) {
                $query = "SELECT id, username, password, role FROM users WHERE username = :username LIMIT 1";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':username', $username);
                $stmt->execute();
                
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($user) {
                    // Check password (assuming hashed passwords)
                    if (password_verify($password, $user['password'])) {
                        // Set session variables
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['role'] = $user['role'];
                        $_SESSION['logged_in'] = true;
                        $_SESSION['login_time'] = time();
                        
                        session_regenerate_id(true);
                        return true;
                    }
                    
                    // If password is plain text (fallback)
                    if ($password === $user['password']) {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['role'] = $user['role'];
                        $_SESSION['logged_in'] = true;
                        $_SESSION['login_time'] = time();
                        
                        session_regenerate_id(true);
                        return true;
                    }
                }
            }
            
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
        }
        
        return false;
    }
    
    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) && isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    /**
     * Check user permissions
     */
    public function hasPermission($permission) {
        if (!$this->isLoggedIn()) {
            return false;
        }
        
        $userRole = $_SESSION['role'] ?? '';
        
        // Admin has all permissions
        if ($userRole === 'admin') {
            return true;
        }
        
        // Check specific permissions
        return $userRole === $permission;
    }
    
    /**
     * Get current user ID
     */
    public function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Get current username
     */
    public function getUsername() {
        return $_SESSION['username'] ?? null;
    }
    
    /**
     * Get current user role
     */
    public function getRole() {
        return $_SESSION['role'] ?? null;
    }
    
    /**
     * Require login - redirect if not authenticated
     */
    public function requireLogin($redirectTo = 'login.php') {
        if (!$this->isLoggedIn()) {
            header("Location: $redirectTo");
            exit();
        }
    }
    
    /**
     * Logout user
     */
    public function logout() {
        // Clear all session data
        $_SESSION = array();
        
        // Delete session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destroy session
        session_destroy();
        
        header("Location: login.php");
        exit();
    }
    
    /**
     * Get current user info
     */
    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'role' => $_SESSION['role'],
                'login_time' => $_SESSION['login_time'] ?? null
            ];
        }
        return null;
    }
}
?>