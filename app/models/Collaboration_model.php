<?php
// app/models/Collaboration_model.php

class Collaboration_model {
    private $table_docs = 'collaboration_documents';
    private $table_requests = 'collaboration_requests';
    private $db;

    const DOC_TYPES = [
        'executive_summary' => 'Executive Summary',
        'company_profile' => 'Company Profile',
        'concept_note' => 'Concept Note',
    ];

    // collaboration_requests.delivery_status
    const DELIVERY = [
        'sent'        => ['Terkirim otomatis', 'success'],
        'manual_sent' => ['Dikirim admin', 'success'],
        'pending'     => ['Menunggu dikirim manual', 'warning'],
        'failed'      => ['Gagal dikirim otomatis', 'danger'],
        'unknown'     => ['Tidak tercatat', 'secondary'],
    ];

    public function __construct() {
        $this->db = new Database;
        $this->ensureDocTypes();
    }

    // Bring older databases up to date: auto_send column and new document types (each runs once)
    private function ensureDocTypes() {
        $this->db->query("SHOW COLUMNS FROM {$this->table_docs} LIKE 'auto_send'");
        if (!$this->db->single()) {
            // 1 = email the PDF to the requester automatically, 0 = admin sends it manually
            $this->migrate("ALTER TABLE {$this->table_docs} ADD COLUMN auto_send TINYINT(1) NOT NULL DEFAULT 1 AFTER status");
        }

        $this->db->query("SHOW COLUMNS FROM {$this->table_requests} LIKE 'delivery_status'");
        if (!$this->db->single()) {
            // Older requests were not tracked, so they start as 'unknown'
            $this->migrate("ALTER TABLE {$this->table_requests}
                ADD COLUMN delivery_status VARCHAR(20) NOT NULL DEFAULT 'unknown',
                ADD COLUMN delivered_at DATETIME NULL,
                ADD COLUMN delivered_by VARCHAR(255) NULL");
        }

        $this->db->query("SHOW COLUMNS FROM {$this->table_docs} LIKE 'type'");
        $col = $this->db->single();
        if (!$col || strpos($col->Type, 'concept_note') !== false) return;
        $values = implode(',', array_map(fn($t) => "'$t'", array_keys(self::DOC_TYPES)));
        $this->migrate("ALTER TABLE {$this->table_docs} MODIFY `type` ENUM($values) DEFAULT 'executive_summary'");
    }

    // Schema change that may race with a parallel request right after deploy: "already exists" is fine
    private function migrate($sql) {
        try {
            $this->db->query($sql);
            $this->db->execute();
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate column') === false) {
                error_log('[GoSirk] Migrasi collaboration gagal: ' . $e->getMessage());
            }
        }
    }

    // --- Document Methods ---

    public function getAllDocuments() {
        $this->db->query('SELECT * FROM ' . $this->table_docs . ' ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    public function getActiveDocumentsByType($type) {
        $this->db->query('SELECT * FROM ' . $this->table_docs . ' WHERE type = :type AND status = "active" ORDER BY created_at DESC');
        $this->db->bind(':type', $type);
        return $this->db->resultSet();
    }

    public function getDocumentById($id) {
        $this->db->query('SELECT * FROM ' . $this->table_docs . ' WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addDocument($data) {
        $query = "INSERT INTO " . $this->table_docs . " 
                  (title_id, title_en, type, file_path, status, auto_send) 
                  VALUES 
                  (:title_id, :title_en, :type, :file_path, :status, :auto_send)";
        
        $this->db->query($query);
        $this->db->bind(':title_id', $data['title_id']);
        $this->db->bind(':title_en', $data['title_en']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':file_path', $data['file_path']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':auto_send', (int) ($data['auto_send'] ?? 1));

        return $this->db->execute();
    }

    public function updateDocument($data) {
        $query = "UPDATE " . $this->table_docs . " SET 
                  title_id = :title_id, 
                  title_en = :title_en, 
                  type = :type, 
                  file_path = :file_path, 
                  status = :status, 
                  auto_send = :auto_send 
                  WHERE id = :id";
        
        $this->db->query($query);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title_id', $data['title_id']);
        $this->db->bind(':title_en', $data['title_en']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':file_path', $data['file_path']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':auto_send', (int) ($data['auto_send'] ?? 1));

        return $this->db->execute();
    }

    public function deleteDocument($id) {
        $this->db->query('DELETE FROM ' . $this->table_docs . ' WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // --- Request Logging Methods ---

    public function logRequest($data) {
        $query = "INSERT INTO " . $this->table_requests . " 
                  (doc_id, name, email, organization, jabatan) 
                  VALUES 
                  (:doc_id, :name, :email, :organization, :jabatan)";
        
        $this->db->query($query);
        $this->db->bind(':doc_id', $data['doc_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':organization', $data['organization']);
        $this->db->bind(':jabatan', $data['jabatan']);

        return $this->db->execute() ? $this->db->lastInsertId() : false;
    }

    /** @param string $filter all | followup (pending/failed) | sent (sent/manual_sent) */
    public function getAllRequests($filter = 'all') {
        $where = [
            'followup' => "WHERE r.delivery_status IN ('pending', 'failed')",
            'sent' => "WHERE r.delivery_status IN ('sent', 'manual_sent')",
        ][$filter] ?? '';
        $this->db->query('SELECT r.*, d.title_id AS doc_title, d.type AS doc_type, d.auto_send AS doc_auto_send, d.file_path AS doc_file
                          FROM ' . $this->table_requests . ' r
                          LEFT JOIN ' . $this->table_docs . ' d ON r.doc_id = d.id
                          ' . $where . '
                          ORDER BY r.requested_at DESC');
        return $this->db->resultSet();
    }

    public function getRequestById($id) {
        $this->db->query('SELECT r.*, d.title_id AS doc_title, d.file_path AS doc_file FROM ' . $this->table_requests . ' r
                          LEFT JOIN ' . $this->table_docs . ' d ON r.doc_id = d.id WHERE r.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function countRequests() {
        $this->db->query("SELECT COUNT(*) AS total,
                                 SUM(delivery_status IN ('pending', 'failed')) AS followup,
                                 SUM(delivery_status IN ('sent', 'manual_sent')) AS sent
                          FROM {$this->table_requests}");
        return $this->db->single();
    }

    public function deleteRequest($id) {
        $this->db->query('DELETE FROM ' . $this->table_requests . ' WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function setDelivery($id, $status, $by = null) {
        $delivered = in_array($status, ['sent', 'manual_sent'], true);
        $this->db->query('UPDATE ' . $this->table_requests . ' SET delivery_status = :status, delivered_at = ' . ($delivered ? 'NOW()' : 'NULL') . ', delivered_by = :by WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':by', $by);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
