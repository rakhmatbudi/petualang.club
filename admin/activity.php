<?php
require_once 'config.php';

class Activity {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function getAllActivities() {
        $query = "SELECT * FROM activity ORDER BY start_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getActivityById($id) {
        $query = "SELECT * FROM activity WHERE activity_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function createActivity($data) {
        $query = "INSERT INTO activity (club_id, activities_name, activities_description, is_highlight, start_date, end_date, location, location_lattitude, location_longitude) 
                  VALUES (:club_id, :name, :description, :highlight, :start_date, :end_date, :location, :latitude, :longitude)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':club_id', $data['club_id']);
        $stmt->bindParam(':name', $data['activities_name']);
        $stmt->bindParam(':description', $data['activities_description']);
        $stmt->bindParam(':highlight', $data['is_highlight']);
        
        // Handle optional dates - bind null if empty
        $start_date = !empty($data['start_date']) ? $data['start_date'] : null;
        $end_date = !empty($data['end_date']) ? $data['end_date'] : null;
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        
        $stmt->bindParam(':location', $data['location']);
        $stmt->bindParam(':latitude', $data['location_lattitude']);
        $stmt->bindParam(':longitude', $data['location_longitude']);
        
        return $stmt->execute();
    }
    
    public function updateActivity($id, $data) {
        $query = "UPDATE activity SET 
                  club_id = :club_id,
                  activities_name = :name,
                  activities_description = :description,
                  is_highlight = :highlight,
                  start_date = :start_date,
                  end_date = :end_date,
                  location = :location,
                  location_lattitude = :latitude,
                  location_longitude = :longitude
                  WHERE activity_id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':club_id', $data['club_id']);
        $stmt->bindParam(':name', $data['activities_name']);
        $stmt->bindParam(':description', $data['activities_description']);
        $stmt->bindParam(':highlight', $data['is_highlight']);
        
        // Handle optional dates - bind null if empty
        $start_date = !empty($data['start_date']) ? $data['start_date'] : null;
        $end_date = !empty($data['end_date']) ? $data['end_date'] : null;
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        
        $stmt->bindParam(':location', $data['location']);
        $stmt->bindParam(':latitude', $data['location_lattitude']);
        $stmt->bindParam(':longitude', $data['location_longitude']);
        
        return $stmt->execute();
    }
    
    public function deleteActivity($id) {
        // Delete related photos first (and their files)
        $photos = $this->getActivityPhotos($id);
        
        // Delete physical files
        foreach ($photos as $photo) {
            $filename = $photo['path']; // Just the filename
            
            // Build server path
            if (strpos($filename, '/') === false) {
                // Just filename, build full path
                $serverPath = Config::getUploadDir('activity_photos') . $filename;
            } else {
                // Contains path, use as is
                $serverPath = $filename;
            }
            
            if (file_exists($serverPath)) {
                unlink($serverPath);
            }
        }
        
        // Delete photo records from database
        $this->deleteActivityPhotos($id);
        
        $query = "DELETE FROM activity WHERE activity_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    public function getActivityPhotos($activity_id) {
        $query = "SELECT * FROM activity_photo WHERE activity_id = :activity_id ORDER BY photo_id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':activity_id', $activity_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Add activity photo with configurable upload handling
     * @param int $activity_id - Activity ID
     * @param array $file - $_FILES array element
     * @param string $description - Photo description
     * @return array - Result with success status and message
     */
    public function addActivityPhoto($activity_id, $file, $description = '') {
        // Validate file
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return ['success' => false, 'message' => 'No valid file uploaded'];
        }
        
        // Check file size
        if ($file['size'] > Config::MAX_FILE_SIZE) {
            return ['success' => false, 'message' => 'File too large. Maximum size: ' . Config::getMaxFileSizeFormatted()];
        }
        
        // Check file type
        if (!Config::isFileTypeAllowed($file['name'], 'image')) {
            return ['success' => false, 'message' => 'Invalid file type. Allowed: ' . implode(', ', Config::ALLOWED_IMAGE_TYPES)];
        }
        
        // Create upload directory
        if (!Config::createUploadDir('activity_photos')) {
            return ['success' => false, 'message' => 'Failed to create upload directory'];
        }
        
        // Generate unique filename
        $filename = Config::generateUniqueFilename($file['name'], 'activity_' . $activity_id);
        $upload_dir = Config::getUploadDir('activity_photos');
        $serverPath = $upload_dir . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $serverPath)) {
            // Save to database - ONLY store the filename, not the full path
            $query = "INSERT INTO activity_photo (activity_id, path, description) VALUES (:activity_id, :filename, :description)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':activity_id', $activity_id);
            $stmt->bindParam(':filename', $filename); // Just the filename!
            $stmt->bindParam(':description', $description);
            
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Photo uploaded successfully', 'filename' => $filename];
            } else {
                // Remove file if database insert failed
                unlink($serverPath);
                return ['success' => false, 'message' => 'Failed to save photo information'];
            }
        } else {
            return ['success' => false, 'message' => 'Failed to upload file'];
        }
    }
    
    /**
     * Delete activity photo (file and database record)
     * @param int $photo_id - Photo ID
     * @return bool - Success status
     */
    public function deleteActivityPhoto($photo_id) {
        // Get photo filename first
        $query = "SELECT path FROM activity_photo WHERE photo_id = :photo_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':photo_id', $photo_id);
        $stmt->execute();
        $photo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($photo) {
            $filename = $photo['path']; // This is just the filename
            
            // Build full server path from filename
            if (strpos($filename, '/') === false) {
                // Just filename, build full path
                $serverPath = Config::getUploadDir('activity_photos') . $filename;
            } else {
                // Contains path, use as is
                $serverPath = $filename;
            }
            
            // Delete physical file
            if (file_exists($serverPath)) {
                unlink($serverPath);
            }
            
            // Delete database record
            $query = "DELETE FROM activity_photo WHERE photo_id = :photo_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':photo_id', $photo_id);
            return $stmt->execute();
        }
        
        return false;
    }
    
    /**
     * Delete all photos for an activity
     * @param int $activity_id - Activity ID
     * @return bool - Success status
     */
    private function deleteActivityPhotos($activity_id) {
        $query = "DELETE FROM activity_photo WHERE activity_id = :activity_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':activity_id', $activity_id);
        return $stmt->execute();
    }
    
    /**
     * Get activity statistics
     * @return array - Statistics data
     */
    public function getActivityStats() {
        $stats = [];
        
        // Total activities
        $query = "SELECT COUNT(*) as total FROM activity";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['total_activities'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Highlighted activities
        $query = "SELECT COUNT(*) as total FROM activity WHERE is_highlight = 'yes'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['highlighted_activities'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Total photos
        $query = "SELECT COUNT(*) as total FROM activity_photo";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['total_photos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Activities this month
        $query = "SELECT COUNT(*) as total FROM activity WHERE EXTRACT(MONTH FROM start_date) = EXTRACT(MONTH FROM CURRENT_DATE) AND EXTRACT(YEAR FROM start_date) = EXTRACT(YEAR FROM CURRENT_DATE)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['activities_this_month'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        return $stats;
    }
}
?>