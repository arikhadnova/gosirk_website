<?php

class ActivityLog_model {
    private $table = 'activity_logs';
    private $db;

    public function __construct() {
        $this->db = new Database;
        $this->initTable();
    }

    public function initTable() {
        $query = "CREATE TABLE IF NOT EXISTS `{$this->table}` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) DEFAULT NULL,
            `user_name` varchar(100) DEFAULT NULL,
            `action_type` varchar(20) NOT NULL,
            `target_type` varchar(50) NOT NULL,
            `description` text NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $this->db->query($query);
        $this->db->execute();
    }

    public function log($action_type, $target_type, $description, $user_id = null, $user_name = null) {
        // If user info not provided, try to get from session
        if ($user_id === null && isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        }
        if ($user_name === null && isset($_SESSION['user_name'])) {
            $user_name = $_SESSION['user_name'];
        } else if ($user_name === null) {
            $user_name = 'System';
        }

        $query = "INSERT INTO {$this->table} (user_id, user_name, action_type, target_type, description, created_at) 
                  VALUES (:user_id, :user_name, :action_type, :target_type, :description, :created_at)";
        
        $this->db->query($query);
        $this->db->bind('user_id', $user_id);
        $this->db->bind('user_name', $user_name);
        $this->db->bind('action_type', $action_type);
        $this->db->bind('target_type', $target_type);
        $this->db->bind('description', $description);
        // Store as UTC to be consistent with our recent fix
        $this->db->bind('created_at', gmdate('Y-m-d H:i:s')); 
        
        return $this->db->execute();
    }

    public function getRecent($limit = 20) {
        $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit");
        $this->db->bind('limit', $limit);
        return $this->db->resultSet();
    }
    
    public function getAll($limit = 100) {
        $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit");
        $this->db->bind('limit', $limit);
        return $this->db->resultSet();
    }
}
