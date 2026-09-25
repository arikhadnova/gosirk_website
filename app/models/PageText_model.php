<?php
// Admin overrides for the site texts in assets/js/translations.js (Admin > Teks Halaman).
// Only changed texts are stored; everything else keeps the default from translations.js.

class PageText_model {
    private $table = 'page_texts';
    private $db;

    public function __construct() {
        $this->db = new Database;
        $this->createTable();
    }

    private function createTable() {
        try {
            $this->db->query("CREATE TABLE IF NOT EXISTS {$this->table} (
                text_key VARCHAR(191) NOT NULL PRIMARY KEY,
                value_id TEXT NULL,
                value_en TEXT NULL,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            $this->db->execute();
        } catch (PDOException $e) {
            error_log('[GoSirk] Tabel page_texts: ' . $e->getMessage());
        }
    }

    /** ['key' => ['id' => ..., 'en' => ...], ...] */
    public function getAll() {
        static $cache = null;
        if ($cache !== null) return $cache;
        $this->db->query("SELECT text_key, value_id, value_en FROM {$this->table}");
        $cache = [];
        foreach ($this->db->resultSet() as $row) {
            $cache[$row->text_key] = ['id' => $row->value_id, 'en' => $row->value_en];
        }
        return $cache;
    }

    public function save($key, $id, $en) {
        $this->db->query("INSERT INTO {$this->table} (text_key, value_id, value_en) VALUES (:k, :id, :en)
                          ON DUPLICATE KEY UPDATE value_id = :id2, value_en = :en2");
        $this->db->bind(':k', $key);
        $this->db->bind(':id', $id);
        $this->db->bind(':en', $en);
        $this->db->bind(':id2', $id);
        $this->db->bind(':en2', $en);
        return $this->db->execute();
    }

    public function delete($key) {
        $this->db->query("DELETE FROM {$this->table} WHERE text_key = :k");
        $this->db->bind(':k', $key);
        return $this->db->execute();
    }
}
