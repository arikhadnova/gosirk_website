<?php

class PilotVillage_model {
    private $table = 'pilot_villages';
    private $db;

    public function __construct() {
        $this->db = new Database;
        $this->createTable();
        $this->migrateData();
    }

    private function createTable() {
        $query = "CREATE TABLE IF NOT EXISTS " . $this->table . " (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name_id VARCHAR(255) NOT NULL,
            name_en VARCHAR(255) NOT NULL,
            image VARCHAR(100) NOT NULL,
            order_priority INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $this->db->query($query);
        $this->db->execute();
    }

    private function migrateData() {
        // Check if table is empty
        $this->db->query("SELECT COUNT(*) as count FROM " . $this->table);
        $result = $this->db->single();
        
        if ($result->count == 0) {
            $data = [
                [
                    'name_id' => 'Desa Bengkel',
                    'name_en' => 'Bengkel Village',
                    'image' => 'IMG_8082.jpg',
                    'order_priority' => 1
                ],
                [
                    'name_id' => 'Desa Dauh Peken',
                    'name_en' => 'Dauh Peken Village',
                    'image' => 'IMG_8084.jpg',
                    'order_priority' => 2
                ],
                [
                    'name_id' => 'Desa Wongaya Gede',
                    'name_en' => 'Wongaya Gede Village',
                    'image' => 'IMG_8097.jpg',
                    'order_priority' => 3
                ]
            ];

            foreach ($data as $row) {
                $this->db->query("INSERT INTO " . $this->table . " (name_id, name_en, image, order_priority) VALUES (:name_id, :name_en, :image, :order_priority)");
                $this->db->bind(':name_id', $row['name_id']);
                $this->db->bind(':name_en', $row['name_en']);
                $this->db->bind(':image', $row['image']);
                $this->db->bind(':order_priority', $row['order_priority']);
                $this->db->execute();
            }
        }
    }

    public function getAll() {
        $this->db->query("SELECT * FROM " . $this->table . " ORDER BY order_priority ASC, id ASC");
        return $this->db->resultSet();
    }

    public function getById($id) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function add($data) {
        $this->db->query("INSERT INTO " . $this->table . " (name_id, name_en, image, order_priority) VALUES (:name_id, :name_en, :image, :order_priority)");
        $this->db->bind(':name_id', $data['name_id']);
        $this->db->bind(':name_en', $data['name_en']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':order_priority', $data['order_priority']);
        return $this->db->execute();
    }

    public function update($data) {
        if ($data['image']) {
            $query = "UPDATE " . $this->table . " SET name_id = :name_id, name_en = :name_en, image = :image, order_priority = :order_priority WHERE id = :id";
        } else {
            $query = "UPDATE " . $this->table . " SET name_id = :name_id, name_en = :name_en, order_priority = :order_priority WHERE id = :id";
        }
        
        $this->db->query($query);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name_id', $data['name_id']);
        $this->db->bind(':name_en', $data['name_en']);
        if ($data['image']) $this->db->bind(':image', $data['image']);
        $this->db->bind(':order_priority', $data['order_priority']);
        return $this->db->execute();
    }

    public function delete($id) {
        $this->db->query("DELETE FROM " . $this->table . " WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
