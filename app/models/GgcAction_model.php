<?php

class GgcAction_model {
    private $table = 'ggc_actions';
    private $db;

    public function __construct() {
        $this->db = new Database;
        $this->createTable();
        $this->migrateData();
    }

    private function createTable() {
        $query = "CREATE TABLE IF NOT EXISTS " . $this->table . " (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title_id VARCHAR(255) NOT NULL,
            title_en VARCHAR(255) NOT NULL,
            description_id TEXT,
            description_en TEXT,
            image VARCHAR(255) NOT NULL,
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
                    "image" => "https://images.unsplash.com/photo-1542601906990-b4d3fb7d5fa5?auto=format&fit=crop&q=80&w=600",
                    "title_id" => "Aksi Tanam Mangrove",
                    "title_en" => "Mangrove Planting Action",
                    "description_id" => "Restorasi kawasan pesisir bersama pemuda desa setempat.",
                    "description_en" => "Coastal area restoration with local village youth.",
                    "order_priority" => 1
                ],
                [
                    "image" => "https://images.unsplash.com/photo-1536964542287-430ae5f1394c?auto=format&fit=crop&q=80&w=600",
                    "title_id" => "Workshop Kompos",
                    "title_en" => "Composting Workshop",
                    "description_id" => "Pelatihan pengolahan sampah organik rumah tangga.",
                    "description_en" => "Household organic waste processing training.",
                    "order_priority" => 2
                ],
                [
                    "image" => "https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&q=80&w=600",
                    "title_id" => "Edukasi Lingkungan",
                    "title_en" => "Environmental Education",
                    "description_id" => "Menanamkan nilai kelestarian kepada generasi muda.",
                    "description_en" => "Instilling sustainable values in the younger generation.",
                    "order_priority" => 3
                ],
                [
                    "image" => "https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?auto=format&fit=crop&q=80&w=600",
                    "title_id" => "Kebun Toga Komunitas",
                    "title_en" => "Community Herbal Garden",
                    "description_id" => "Pemanfaatan pekarangan untuk ketahanan pangan.",
                    "description_en" => "Utilizing home gardens for food security.",
                    "order_priority" => 4
                ],
                [
                    "image" => "https://images.unsplash.com/photo-1531206715517-5c0ba140b2b8?auto=format&fit=crop&q=80&w=600",
                    "title_id" => "Aksi Pilah Sampah",
                    "title_en" => "Waste Sorting Action",
                    "description_id" => "Gerakan pilah dari sumber di lingkup rukun tetangga.",
                    "description_en" => "Source sorting movement at the neighborhood level.",
                    "order_priority" => 5
                ],
                [
                    "image" => "https://images.unsplash.com/photo-1526951521990-620dc14c214b?auto=format&fit=crop&q=80&w=600",
                    "title_id" => "Pertemuan Kader GGC",
                    "title_en" => "GGC Cadre Meeting",
                    "description_id" => "Koordinasi rutin perencanaan program pemberdayaan.",
                    "description_en" => "Regular coordination of empowerment program planning.",
                    "order_priority" => 6
                ]
            ];

            foreach ($data as $row) {
                $this->db->query("INSERT INTO " . $this->table . " (title_id, title_en, description_id, description_en, image, order_priority) VALUES (:title_id, :title_en, :description_id, :description_en, :image, :order_priority)");
                $this->db->bind(':title_id', $row['title_id']);
                $this->db->bind(':title_en', $row['title_en']);
                $this->db->bind(':description_id', $row['description_id']);
                $this->db->bind(':description_en', $row['description_en']);
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
        $this->db->query("INSERT INTO " . $this->table . " (title_id, title_en, description_id, description_en, image, order_priority) VALUES (:title_id, :title_en, :description_id, :description_en, :image, :order_priority)");
        $this->db->bind(':title_id', $data['title_id']);
        $this->db->bind(':title_en', $data['title_en']);
        $this->db->bind(':description_id', $data['description_id']);
        $this->db->bind(':description_en', $data['description_en']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':order_priority', $data['order_priority']);
        return $this->db->execute();
    }

    public function update($data) {
        if ($data['image']) {
            $query = "UPDATE " . $this->table . " SET title_id = :title_id, title_en = :title_en, description_id = :description_id, description_en = :description_en, image = :image, order_priority = :order_priority WHERE id = :id";
        } else {
            $query = "UPDATE " . $this->table . " SET title_id = :title_id, title_en = :title_en, description_id = :description_id, description_en = :description_en, order_priority = :order_priority WHERE id = :id";
        }
        
        $this->db->query($query);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title_id', $data['title_id']);
        $this->db->bind(':title_en', $data['title_en']);
        $this->db->bind(':description_id', $data['description_id']);
        $this->db->bind(':description_en', $data['description_en']);
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
