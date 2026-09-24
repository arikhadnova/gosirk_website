<?php

class PageSection_model {
    private $table = 'page_sections';
    private $db;

    public function __construct() {
        $this->db = new Database;
        $this->createTable();
        $this->migrateTable();
    }

    private function createTable() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `page_sections` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `page_name` varchar(100) NOT NULL,
            `section_key` varchar(100) NOT NULL,
            `badge_id` varchar(255) DEFAULT NULL,
            `badge_en` varchar(255) DEFAULT NULL,
            `title_id` text DEFAULT NULL,
            `title_en` text DEFAULT NULL,
            `content_id` text DEFAULT NULL,
            `content_en` text DEFAULT NULL,
            `content_2_id` text DEFAULT NULL,
            `content_2_en` text DEFAULT NULL,
            `content_3_id` text DEFAULT NULL,
            `content_3_en` text DEFAULT NULL,
            `image` varchar(255) DEFAULT NULL,
            `is_active` tinyint(1) NOT NULL DEFAULT 1,
            `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (`id`),
            UNIQUE KEY `unique_page_section` (`page_name`, `section_key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $this->db->execute();
    }

    private function migrateTable() {
        $columns = [
            'content_3_id' => "ALTER TABLE `{$this->table}` ADD COLUMN `content_3_id` text DEFAULT NULL AFTER `content_2_en`",
            'content_3_en' => "ALTER TABLE `{$this->table}` ADD COLUMN `content_3_en` text DEFAULT NULL AFTER `content_3_id`",
            'is_active' => "ALTER TABLE `{$this->table}` ADD COLUMN `is_active` tinyint(1) NOT NULL DEFAULT 1 AFTER `image`"
        ];

        foreach ($columns as $column => $alterSql) {
            $this->db->query("SHOW COLUMNS FROM `{$this->table}` LIKE '{$column}'");
            if (!$this->db->single()) {
                $this->db->query($alterSql);
                $this->db->execute();
            }
        }
    }

    public function getByPageAndSection($page, $section) {
        $this->db->query("SELECT * FROM {$this->table} WHERE page_name = :page_name AND section_key = :section_key LIMIT 1");
        $this->db->bind('page_name', $page);
        $this->db->bind('section_key', $section);
        return $this->db->single();
    }

    public function getAboutSections() {
        $this->db->query("SELECT * FROM {$this->table} WHERE section_key = 'about' ORDER BY page_name ASC");
        return $this->db->resultSet();
    }

    public function upsert($data) {
        $this->db->query("INSERT INTO {$this->table}
            (page_name, section_key, badge_id, badge_en, title_id, title_en, content_id, content_en, content_2_id, content_2_en, content_3_id, content_3_en, image, is_active)
            VALUES
            (:page_name, :section_key, :badge_id, :badge_en, :title_id, :title_en, :content_id, :content_en, :content_2_id, :content_2_en, :content_3_id, :content_3_en, :image, :is_active)
            ON DUPLICATE KEY UPDATE
            badge_id = :badge_id_update,
            badge_en = :badge_en_update,
            title_id = :title_id_update,
            title_en = :title_en_update,
            content_id = :content_id_update,
            content_en = :content_en_update,
            content_2_id = :content_2_id_update,
            content_2_en = :content_2_en_update,
            content_3_id = :content_3_id_update,
            content_3_en = :content_3_en_update,
            image = :image_update,
            is_active = :is_active_update");

        $fields = [
            'page_name', 'section_key', 'badge_id', 'badge_en', 'title_id', 'title_en',
            'content_id', 'content_en', 'content_2_id', 'content_2_en', 'content_3_id',
            'content_3_en', 'image', 'is_active'
        ];

        foreach ($fields as $field) {
            $value = $data[$field] ?? '';
            $this->db->bind($field, $value);
            if (!in_array($field, ['page_name', 'section_key'])) {
                $this->db->bind($field . '_update', $value);
            }
        }

        return $this->db->execute();
    }
}
