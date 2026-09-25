<?php

class GiVideo_model {
    private $table = 'gi_videos';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAll() {
        $this->db->query('SELECT * FROM ' . $this->table . ' ORDER BY order_priority ASC, id DESC');
        return $this->db->resultSet();
    }

    public function getById($id) {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function add($data) {
        $query = "INSERT INTO " . $this->table . " (
                    title_id, title_en, description_id, description_en, url, thumbnail, order_priority
                  ) VALUES (
                    :title_id, :title_en, :description_id, :description_en, :url, :thumbnail, :order_priority
                  )";
        $this->db->query($query);
        $this->db->bind(':title_id', $data['title_id']);
        $this->db->bind(':title_en', $data['title_en']);
        $this->db->bind(':description_id', $data['description_id']);
        $this->db->bind(':description_en', $data['description_en']);
        $this->db->bind(':url', $data['url']);
        $this->db->bind(':thumbnail', $data['thumbnail']);
        $this->db->bind(':order_priority', $data['order_priority']);
        return $this->db->execute();
    }

    public function update($data) {
        $query = "UPDATE " . $this->table . " SET 
                    title_id = :title_id, 
                    title_en = :title_en, 
                    description_id = :description_id, 
                    description_en = :description_en, 
                    url = :url, 
                    thumbnail = :thumbnail, 
                    order_priority = :order_priority
                  WHERE id = :id";
        $this->db->query($query);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title_id', $data['title_id']);
        $this->db->bind(':title_en', $data['title_en']);
        $this->db->bind(':description_id', $data['description_id']);
        $this->db->bind(':description_en', $data['description_en']);
        $this->db->bind(':url', $data['url']);
        $this->db->bind(':thumbnail', $data['thumbnail']);
        $this->db->bind(':order_priority', $data['order_priority']);
        return $this->db->execute();
    }

    public function delete($id) {
        $this->db->query('DELETE FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
