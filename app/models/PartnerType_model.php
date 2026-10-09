<?php
// app/models/PartnerType_model.php
// Badge types of a portfolio ("Tipe Partner"): managed from the dropdown in the portfolio form.
// portfolios.partner_type keeps the type's code (GOVERNMENT, ...); the label is shown in ID or EN.

class PartnerType_model {
    private $table = 'partner_types';
    private $db;

    // The types the form offered before they could be edited
    const DEFAULTS = [
        'GOVERNMENT' => ['Pemerintah', 'Government'],
        'COMMUNITY' => ['Komunitas', 'Community'],
        'EDUCATION' => ['Pendidikan/Akademik', 'Education/Academic'],
        'CORPORATE' => ['Korporasi/CSR', 'Corporate/CSR'],
        'NGO' => ['LSM/Yayasan', 'NGO/Foundation'],
    ];

    public function __construct() {
        $this->db = new Database;
        $this->ensureTable();
    }

    // Created once, with the default types plus any other code portfolios already use (so no badge loses its type)
    private function ensureTable() {
        $this->db->query("SHOW TABLES LIKE '{$this->table}'");
        if ($this->db->single()) return;
        try {
            $this->db->query("CREATE TABLE IF NOT EXISTS {$this->table} (
                code VARCHAR(50) NOT NULL PRIMARY KEY,
                label_id VARCHAR(100) NOT NULL,
                label_en VARCHAR(100) NOT NULL,
                sort_order INT NOT NULL DEFAULT 0
            )");
            $this->db->execute();
        } catch (PDOException $e) {
            error_log('[GoSirk] Tabel tipe partner gagal dibuat: ' . $e->getMessage());
            return;
        }
        $types = self::DEFAULTS;
        $this->db->query("SELECT DISTINCT partner_type FROM portfolios WHERE partner_type IS NOT NULL AND partner_type <> ''");
        foreach ($this->db->resultSet() as $row) {
            $name = ucwords(strtolower($row->partner_type));
            $types[$row->partner_type] = $types[$row->partner_type] ?? [$name, $name];
        }
        $position = 0;
        foreach ($types as $code => [$labelId, $labelEn]) {
            $this->db->query("INSERT IGNORE INTO {$this->table} (code, label_id, label_en, sort_order) VALUES (:c, :i, :e, :s)");
            $this->db->bind(':c', $code);
            $this->db->bind(':i', $labelId);
            $this->db->bind(':e', $labelEn);
            $this->db->bind(':s', ++$position);
            $this->db->execute();
        }
    }

    public function getAll() {
        $this->db->query("SELECT * FROM {$this->table} ORDER BY sort_order, label_id");
        return $this->db->resultSet();
    }

    /** [lowercase code => row] for looking labels up: older rows may hold the code in another case (government) */
    public function map() {
        $map = [];
        foreach ($this->getAll() as $t) $map[strtolower($t->code)] = $t;
        return $map;
    }

    /** New type at the end of the list; its code comes from the Indonesian label. Returns the code. */
    public function add($labelId, $labelEn) {
        $base = strtoupper(trim(preg_replace('/[^A-Za-z0-9]+/', '_', $labelId), '_')) ?: 'TIPE';
        $base = substr($base, 0, 40);
        $code = $base;
        for ($i = 2; $this->exists($code); $i++) $code = $base . '_' . $i;
        $this->db->query("INSERT INTO {$this->table} (code, label_id, label_en, sort_order)
                          SELECT :c, :i, :e, COALESCE(MAX(sort_order), 0) + 1 FROM {$this->table}");
        $this->db->bind(':c', $code);
        $this->db->bind(':i', $labelId);
        $this->db->bind(':e', $labelEn);
        $this->db->execute();
        return $code;
    }

    public function update($code, $labelId, $labelEn) {
        $this->db->query("UPDATE {$this->table} SET label_id = :i, label_en = :e WHERE code = :c");
        $this->db->bind(':c', $code);
        $this->db->bind(':i', $labelId);
        $this->db->bind(':e', $labelEn);
        return $this->db->execute();
    }

    public function exists($code) {
        $this->db->query("SELECT code FROM {$this->table} WHERE code = :c");
        $this->db->bind(':c', $code);
        return (bool) $this->db->single();
    }

    /** Portfolios that use the type (it cannot be deleted while any do) */
    public function usage($code) {
        $this->db->query("SELECT COUNT(*) AS n FROM portfolios WHERE partner_type = :c");
        $this->db->bind(':c', $code);
        return (int) $this->db->single()->n;
    }

    public function delete($code) {
        $this->db->query("DELETE FROM {$this->table} WHERE code = :c");
        $this->db->bind(':c', $code);
        return $this->db->execute();
    }
}
