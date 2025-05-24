<?php
// login.php - Complete login page
require_once 'auth.php';

$auth = new Auth();

// If already logged in, redirect to dashboard
if ($auth->isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
$success = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        if ($auth->login($username, $password)) {
            // Login successful - redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $error = 'Invalid username or password.';
        }
    }
}

// Check if there's a logout message
if (isset($_GET['msg']) && $_GET['msg'] === 'logged_out') {
    $success = 'You have been logged out successfully.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petualang Admin - Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 420px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .login-header h1 {
            color: #2d3748;
            margin-bottom: 0.5rem;
            font-size: 2.25rem;
            font-weight: 700;
            letter-spacing: -0.025em;
        }
        
        .login-header p {
            color: #718096;
            font-size: 1rem;
            font-weight: 500;
        }
        
        .form-group {
            margin-bottom: 1.75rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.75rem;
            color: #2d3748;
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 1.125rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #f7fafc;
            color: #2d3748;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }
        
        .form-group input::placeholder {
            color: #a0aec0;
        }
        
        .btn {
            width: 100%;
            padding: 1.125rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.05rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            letter-spacing: 0.025em;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.3);
        }
        
        .btn:active {
            transform: translateY(0);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .error {
            background: linear-gradient(135deg, #fed7d7 0%, #feb2b2 100%);
            color: #c53030;
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border: 1px solid #fc8181;
            font-size: 0.95rem;
            font-weight: 500;
        }
        
        .success {
            background: linear-gradient(135deg, #c6f6d5 0%, #9ae6b4 100%);
            color: #2f855a;
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border: 1px solid #68d391;
            font-size: 0.95rem;
            font-weight: 500;
        }
        
        .footer {
            text-align: center;
            margin-top: 2rem;
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        /* Mobile Responsive */
        @media (max-width: 480px) {
            body {
                padding: 0.75rem;
            }
            
            .login-container {
                padding: 2rem 1.5rem;
                border-radius: 16px;
                max-width: 100%;
            }
            
            .login-header h1 {
                font-size: 1.875rem;
            }
            
            .login-header p {
                font-size: 0.9rem;
            }
            
            .form-group {
                margin-bottom: 1.5rem;
            }
            
            .form-group input {
                padding: 1rem 0.875rem;
                font-size: 16px; /* Prevents zoom on iOS */
            }
            
            .btn {
                padding: 1rem;
                font-size: 1rem;
            }
        }
        
        /* Tablet */
        @media (min-width: 481px) and (max-width: 768px) {
            .login-container {
                max-width: 400px;
                padding: 2.25rem;
            }
        }
        
        /* Large screens */
        @media (min-width: 1024px) {
            .login-container {
                max-width: 440px;
                padding: 3rem;
            }
            
            .login-header h1 {
                font-size: 2.5rem;
            }
            
            .form-group input {
                padding: 1.25rem 1.125rem;
            }
            
            .btn {
                padding: 1.25rem;
                font-size: 1.1rem;
            }
        }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .login-container {
                background: rgba(45, 55, 72, 0.95);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .login-header h1 {
                color: #f7fafc;
            }
            
            .login-header p {
                color: #cbd5e0;
            }
            
            .form-group label {
                color: #e2e8f0;
            }
            
            .form-group input {
                background: #4a5568;
                border-color: #4a5568;
                color: #f7fafc;
            }
            
            .form-group input:focus {
                background: #2d3748;
                border-color: #667eea;
            }
            
            .form-group input::placeholder {
                color: #a0aec0;
            }
        }
        
        /* Accessibility improvements */
        .btn:focus {
            outline: 2px solid #667eea;
            outline-offset: 2px;
        }
        
        .form-group input:focus {
            outline: 2px solid transparent;
        }
        
        /* Animation for smooth transitions */
        .login-container {
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>🏔️ Petualang</h1>
            <p>Admin Login Panel</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error">
                <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" 
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" 
                       placeholder="Enter your username"
                       required autocomplete="username">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" 
                       placeholder="Enter your password"
                       required autocomplete="current-password">
            </div>
            
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>