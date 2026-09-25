<?php
// app/models/Impact_model.php

class Impact_model {
    private $table = 'impact_data';
    private $db;

    public function __construct() {
        $this->db = new Database;
        $this->backfillGgcEnglish();
    }

    // One-time data fix: GGC impact rows whose English label/note were saved as Indonesian (auto-translation failed).
    // Fills them with the English text the page used to show from translations.js. Only rows still untranslated are touched.
    private function backfillGgcEnglish() {
        $flag = 'migration_ggc_impact_en_v1';
        $this->db->query('SELECT 1 FROM settings WHERE setting_key = :k');
        $this->db->bind(':k', $flag);
        if ($this->db->single()) return;

        $map = [
        'Edukasi Masyarakat' => 'Community Education',
        'Teredukasi mengenai proses pemilahan sampah serta pembuatan kompos metode menggunakan wadah Compost Bag dan eco-enzym.' => 'Educated on waste separation processes and composting methods using Compost Bags and eco-enzymes.',
        'Bibit Toga' => 'Medicinal Seedlings',
        'Penanaman bibit membuka jalan bagi masyarakat untuk berkontribusi langsung pada penghijauan dan ketahanan pangan lokal.' => 'Planting seedlings paves the way for the community to directly contribute to greening and local food security.',
        'Tumblr GO Sirk' => 'GoSirk Tumbler',
        'Masyarakat kini beralih ke penggunaan tumblr, mengurangi penggunaan botol plastik sekali pakai setiap bulannya.' => 'Communities are now switching to tumblers, reducing the use of single-use plastic bottles every month.',
        'Tas Guna Ulang "Ayo Ngompos"' => 'Reusable Bag "Ayo Ngompos"',
        'Mendorong masyarakat beralih ke kebiasaan belanja yang lebih ramah lingkungan dengan mengurangi penggunaan kantong belanja plastik.' => 'Encouraging communities to switch to more eco-friendly shopping habits by reducing the use of plastic shopping bags.',
        'Pupuk Kompos (250gr)' => 'Compost Fertilizer (250gr)',
        'Hasil olahan sampah organik kantor GO Sirk. Prototipe hasil praktik pengolahan sampah organik di rumah masing-masing.' => 'Processed from GoSirk office organic waste. Prototype from organic waste processing practices at respective homes.',
        'Kompos Komunitas' => 'Community Compost',
        'Mendorong masyarakat untuk menerapkan sirkularitas dari rumah sendiri melalui pengolahan sampah organik.' => 'Encouraging communities to implement circularity from their own homes through organic waste processing.',
        'Timba Biokomposter' => 'Biocomposter Bucket',
        'Mandiri mengolah sampah organik menggunakan timba biokomposter, mengubah limbah dapur menjadi pupuk alami.' => 'Independently processing organic waste using biocomposter buckets, turning kitchen waste into natural fertilizer.',
        'Mitra Pemerintah Desa' => 'Village Government Partners',
        'Kolaborasi strategis dengan perangkat desa untuk mewujudkan tata kelola sampah yang legal dan terorganisir.' => 'Strategic collaboration with village officials to realize legal and organized waste management.',
        'Mitra Universitas' => 'University Partners',
        'Kemitraan akademik dalam riset, inovasi, dan pengabdian masyarakat untuk solusi sirkularitas yang ilmiah.' => 'Academic partnerships in research, innovation, and community service for scientific circularity solutions.',
        'Komunitas Penerima Manfaat' => 'Beneficiary Communities',
        'Kelompok masyarakat yang telah menerapkan praktik ekonomi sirkular melalui program pendampingan GGC.' => 'Community groups that have implemented circular economy practices through GGC assistance programs.',
        ];
        foreach ($map as $idText => $enText) {
            foreach (['label', 'note'] as $f) {
                $this->db->query("UPDATE {$this->table} SET {$f}_en = :en WHERE page = 'ggc' AND {$f}_id = :id AND ({$f}_en IS NULL OR {$f}_en = '' OR {$f}_en = {$f}_id)");
                $this->db->bind(':en', $enText);
                $this->db->bind(':id', $idText);
                $this->db->execute();
            }
        }
        $this->db->query('INSERT IGNORE INTO settings (setting_key, setting_value) VALUES (:k, :v)');
        $this->db->bind(':k', $flag);
        $this->db->bind(':v', date('Y-m-d H:i:s'));
        $this->db->execute();
    }

    public function getAll() {
        $this->db->query('SELECT * FROM ' . $this->table);
        return $this->db->resultSet();
    }

    public function getById($id) {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getByPage($page) {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE page = :page');
        $this->db->bind(':page', $page);
        return $this->db->resultSet();
    }

    public function getAllByPage($page) {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE page = :page ORDER BY order_num ASC, id ASC');
        $this->db->bind(':page', $page);
        return $this->db->resultSet();
    }

    public function add($data) {
        $query = "INSERT INTO " . $this->table . " (label_id, label_en, value, unit, icon, page, section, section_title_id, section_title_en, note_id, note_en, order_num) 
                  VALUES (:label_id, :label_en, :value, :unit, :icon, :page, :section, :section_title_id, :section_title_en, :note_id, :note_en, :order_num)";
        
        $this->db->query($query);
        $this->db->bind(':label_id', $data['label_id']);
        $this->db->bind(':label_en', $data['label_en']);
        $this->db->bind(':value', $data['value']);
        $this->db->bind(':unit', $data['unit']);
        $this->db->bind(':icon', $data['icon']);
        $this->db->bind(':page', $data['page']);
        $this->db->bind(':section', $data['section']);
        $this->db->bind(':section_title_id', $data['section_title_id']);
        $this->db->bind(':section_title_en', $data['section_title_en']);
        $this->db->bind(':note_id', $data['note_id']);
        $this->db->bind(':note_en', $data['note_en']);
        $this->db->bind(':order_num', $data['order_num']);

        return $this->db->execute();
    }

    public function update($data) {
        $query = "UPDATE " . $this->table . " SET 
                  label_id = :label_id, 
                  label_en = :label_en, 
                  value = :value, 
                  unit = :unit, 
                  icon = :icon,
                  page = :page,
                  section = :section,
                  section_title_id = :section_title_id,
                  section_title_en = :section_title_en,
                  note_id = :note_id,
                  note_en = :note_en,
                  order_num = :order_num
                  WHERE id = :id";
        
        $this->db->query($query);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':label_id', $data['label_id']);
        $this->db->bind(':label_en', $data['label_en']);
        $this->db->bind(':value', $data['value']);
        $this->db->bind(':unit', $data['unit']);
        $this->db->bind(':icon', $data['icon']);
        $this->db->bind(':page', $data['page']);
        $this->db->bind(':section', $data['section']);
        $this->db->bind(':section_title_id', $data['section_title_id']);
        $this->db->bind(':section_title_en', $data['section_title_en']);
        $this->db->bind(':note_id', $data['note_id']);
        $this->db->bind(':note_en', $data['note_en']);
        $this->db->bind(':order_num', $data['order_num']);

        return $this->db->execute();
    }

    public function delete($id) {
        $this->db->query('DELETE FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
