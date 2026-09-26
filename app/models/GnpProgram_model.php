<?php
// "Program Utama" cards on the Go Ngompos Project page (Admin > Go Ngompos Project > Program).
// GgcProgram_model reuses this class for the programs on the GoSirk Green Community page.

class GnpProgram_model {
    protected $table = 'gnp_programs';
    protected $db;
    const FOLDER = 'img/gnp'; // uploaded images

    // Badge colours offered in the admin form => CSS for the public page
    const BADGE_COLORS = [
        'success' => ['Hijau', 'background-color: #198754;'],
        'primary' => ['Biru', 'background-color: #0d6efd;'],
        'orange'  => ['Oranye', 'background-color: #f97316;'],
        'warning' => ['Kuning', 'background-color: #f6c23e;'],
        'info'    => ['Biru muda', 'background-color: #0dcaf0;'],
        'dark'    => ['Gelap', 'background-color: #212529;'],
    ];

    public function __construct() {
        $this->db = new Database;
        $this->createTable();
    }

    // Created (and filled with the original three programs) only the first time
    private function createTable() {
        $this->db->query("SHOW TABLES LIKE '{$this->table}'");
        if ($this->db->single()) return;

        try {
            $this->db->query("CREATE TABLE {$this->table} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            badge_id VARCHAR(100) DEFAULT NULL,
            badge_en VARCHAR(100) DEFAULT NULL,
            badge_color VARCHAR(20) NOT NULL DEFAULT 'success',
            title_id VARCHAR(255) NOT NULL,
            title_en VARCHAR(255) NOT NULL,
            description_id TEXT,
            description_en TEXT,
            image VARCHAR(255) DEFAULT NULL,
            order_priority INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            $this->db->execute();
        } catch (PDOException $e) {
            // Another request created it a moment earlier: that request also adds the starter rows
            return;
        }

        $seed = $this->seed();
        foreach ($seed as $r) {
            $this->add(array_combine(['badge_id', 'badge_en', 'badge_color', 'title_id', 'title_en', 'description_id', 'description_en', 'image', 'order_priority'], $r));
        }
    }

    // Starter rows: [badge_id, badge_en, color, title_id, title_en, description_id, description_en, image, order]
    protected function seed() {
        return [
            ['Edukasi', 'Education', 'success', 'Kelas Ngompos', 'Composting Class',
             'Sesi belajar praktik pemilahan organik dan metode kompos sederhana.', 'Hands-on learning sessions for organic sorting and simple composting methods.',
             'https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&q=80&w=800', 1],
            ['Pendampingan', 'Mentoring', 'primary', 'Kompos Komunitas', 'Community Compost',
             'Pendampingan titik kompos bersama di lingkungan warga atau institusi.', 'Mentoring shared composting points in neighborhoods or institutions.',
             'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?auto=format&fit=crop&q=80&w=800', 2],
            ['Pemanfaatan', 'Utilization', 'orange', 'Kebun Sirkular', 'Circular Garden',
             'Pemanfaatan kompos untuk tanaman pangan, toga, dan ruang hijau komunitas.', 'Using compost for food crops, medicinal plants, and community green spaces.',
             'https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&q=80&w=800', 3],
        ];
    }

    public function getAll() {
        $this->db->query("SELECT * FROM {$this->table} ORDER BY order_priority ASC, id ASC");
        return $this->db->resultSet();
    }

    public function getById($id) {
        $this->db->query("SELECT * FROM {$this->table} WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function add($data) {
        $this->db->query("INSERT INTO {$this->table}
            (badge_id, badge_en, badge_color, title_id, title_en, description_id, description_en, image, order_priority)
            VALUES (:badge_id, :badge_en, :badge_color, :title_id, :title_en, :description_id, :description_en, :image, :order_priority)");
        $this->bindAll($data);
        return $this->db->execute();
    }

    public function update($data) {
        $this->db->query("UPDATE {$this->table} SET
            badge_id = :badge_id, badge_en = :badge_en, badge_color = :badge_color,
            title_id = :title_id, title_en = :title_en,
            description_id = :description_id, description_en = :description_en,
            image = :image, order_priority = :order_priority
            WHERE id = :id");
        $this->bindAll($data);
        $this->db->bind(':id', $data['id']);
        return $this->db->execute();
    }

    public function delete($id) {
        $this->db->query("DELETE FROM {$this->table} WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    private function bindAll($data) {
        foreach (['badge_id', 'badge_en', 'badge_color', 'title_id', 'title_en', 'description_id', 'description_en', 'image'] as $f) {
            $this->db->bind(':' . $f, $data[$f] ?? null);
        }
        $this->db->bind(':order_priority', (int) ($data['order_priority'] ?? 0));
    }

    /** Public URL of a program image: full URL, a path under assets/img/ ("pages/x.jpg"), or an uploaded file. */
    public static function imageUrl($image) {
        if (!$image) return '';
        if (preg_match('#^https?://#i', $image)) return $image;
        if (strpos($image, '/') !== false) return ASSETS_URL . 'img/' . $image;
        return ASSETS_URL . static::FOLDER . '/' . $image;
    }
}
