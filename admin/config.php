<?php
// config.php - Complete version with all methods
class Database {
    private $host = 'localhost';
    private $port = '5432';
    private $db_name = 'komuni40_komunitas';
    private $username = 'komuni40_rakhmat';
    private $password = 'Postgresp3w3d3^_^';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $dsn = "pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

// Configuration class for application settings
class Config {
    // File upload settings
    const UPLOAD_BASE_DIR = '../photo/';
    
    // File upload settings
    const MAX_FILE_SIZE = 5242880; // 5MB in bytes
    const ALLOWED_IMAGE_TYPES = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    const ALLOWED_DOCUMENT_TYPES = ['pdf', 'doc', 'docx', 'txt'];
    
    // Security settings
    const JWT_SECRET = "your_secure_random_string_here";
    const SESSION_TIMEOUT = 3600; // 1 hour in seconds
    
    // Application settings
    const APP_NAME = "Petualang Admin";
    const APP_VERSION = "1.0.0";
    const TIMEZONE = "Asia/Jakarta";
    
    /**
     * Get upload directory path
     * @param string $subdir - Subdirectory name (e.g., 'activity_photos')
     * @return string - Full path to upload directory
     */
    public static function getUploadDir($subdir = '') {
        $path = self::UPLOAD_BASE_DIR;
        if ($subdir) {
            $path .= $subdir . '/';
        }
        return $path;
    }
    
    /**
     * Create upload directory if it doesn't exist
     * @param string $subdir - Subdirectory name
     * @return bool - Success status
     */
    public static function createUploadDir($subdir = '') {
        $path = self::getUploadDir($subdir);
        if (!is_dir($path)) {
            return mkdir($path, 0755, true);
        }
        return true;
    }
    
    /**
     * Check if file type is allowed
     * @param string $filename - Filename to check
     * @param string $type - Type of file ('image' or 'document')
     * @return bool - Whether file type is allowed
     */
    public static function isFileTypeAllowed($filename, $type = 'image') {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if ($type === 'image') {
            return in_array($ext, self::ALLOWED_IMAGE_TYPES);
        } elseif ($type === 'document') {
            return in_array($ext, self::ALLOWED_DOCUMENT_TYPES);
        }
        
        return false;
    }
    
    /**
     * Generate unique filename
     * @param string $originalName - Original filename
     * @param string $prefix - Optional prefix
     * @return string - Unique filename
     */
    public static function generateUniqueFilename($originalName, $prefix = '') {
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        $name = pathinfo($originalName, PATHINFO_FILENAME);
        
        // Sanitize filename
        $name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
        
        // Create unique name
        $uniqueName = $prefix ? $prefix . '_' : '';
        $uniqueName .= $name . '_' . uniqid() . '.' . $ext;
        
        return $uniqueName;
    }
    
    /**
     * Get maximum file size in human readable format
     * @return string - Formatted file size
     */
    public static function getMaxFileSizeFormatted() {
        $bytes = self::MAX_FILE_SIZE;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . 'MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . 'KB';
        } else {
            return $bytes . ' bytes';
        }
    }
    
    /**
     * Get web-accessible URL for uploaded file
     * @param string $filename - Just the filename (e.g., "image.jpg")
     * @param string $type - Upload type (e.g., "activity_photos")
     * @return string - Full web URL (e.g., "/photo/activity_photos/image.jpg")
     */
    public static function getFileWebUrl($filename, $type = 'activity_photos') {
        if (empty($filename)) {
            return '';
        }
        
        // Convert server directory to web directory
        $webBaseDir = str_replace('../', '/', self::UPLOAD_BASE_DIR);
        
        // Ensure it starts with forward slash
        if ($webBaseDir[0] !== '/') {
            $webBaseDir = '/' . $webBaseDir;
        }
        
        // Build full web URL
        $webUrl = rtrim($webBaseDir, '/') . '/' . $type . '/' . $filename;
        
        return $webUrl;
    }
    
    /**
     * Get server file path for uploaded file
     * @param string $filename - Just the filename
     * @param string $type - Upload type
     * @return string - Full server path (e.g., "../photo/activity_photos/image.jpg")
     */
    public static function getFileServerPath($filename, $type = 'activity_photos') {
        if (empty($filename)) {
            return '';
        }
        
        return self::getUploadDir($type) . $filename;
    }
    
    /**
     * Check if uploaded file exists and is accessible
     * @param string $filename - Just the filename
     * @param string $type - Upload type
     * @return bool - Whether file exists
     */
    public static function fileExists($filename, $type = 'activity_photos') {
        if (empty($filename)) {
            return false;
        }
        
        $serverPath = self::getFileServerPath($filename, $type);
        return file_exists($serverPath) && is_readable($serverPath);
    }
}

// Set default timezone
date_default_timezone_set(Config::TIMEZONE);
?>