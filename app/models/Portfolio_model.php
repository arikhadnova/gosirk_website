<?php
// app/models/Portfolio_model.php

class Portfolio_model {
    private $table = 'portfolios';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAll() {
        $this->db->query('SELECT * FROM ' . $this->table . ' ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    public function getById($id) {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function add($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (title_id, title_en, subtitle_id, subtitle_en, description_id, description_en, 
                   icon_name, cover_image, main_category, home_category, partnership_category, gi_category, partner_type, year_start, year_end, 
                   show_home, show_partnership, show_gi, client_name, tags, video_url, 
                   detail_content_id, detail_content_en, targets_id, targets_en, metrics_id, metrics_en, approach_id, approach_en, highlights, project_logos) 
                  VALUES 
                  (:title_id, :title_en, :subtitle_id, :subtitle_en, :description_id, :description_en, 
                   :icon_name, :cover_image, :main_category, :home_category, :partnership_category, :gi_category, :partner_type, :year_start, :year_end, 
                   :show_home, :show_partnership, :show_gi, :client_name, :tags, :video_url, 
                   :detail_content_id, :detail_content_en, :targets_id, :targets_en, :metrics_id, :metrics_en, :approach_id, :approach_en, :highlights, :project_logos)";
        
        $this->db->query($query);
        $this->db->bind(':title_id', $data['title_id']);
        $this->db->bind(':title_en', $data['title_en']);
        $this->db->bind(':subtitle_id', $data['subtitle_id']);
        $this->db->bind(':subtitle_en', $data['subtitle_en']);
        $this->db->bind(':description_id', $data['description_id']);
        $this->db->bind(':description_en', $data['description_en']);
        $this->db->bind(':icon_name', $data['icon_name']);
        $this->db->bind(':cover_image', $data['cover_image']);
        $this->db->bind(':main_category', $data['main_category']);
        $this->db->bind(':home_category', $data['home_category']);
        $this->db->bind(':partnership_category', $data['partnership_category']);
        $this->db->bind(':gi_category', $data['gi_category']);
        $this->db->bind(':partner_type', $data['partner_type']);
        $this->db->bind(':year_start', $data['year_start']);
        $this->db->bind(':year_end', $data['year_end']);
        $this->db->bind(':show_home', $data['show_home'] ?? 0);
        $this->db->bind(':show_partnership', $data['show_partnership'] ?? 0);
        $this->db->bind(':show_gi', $data['show_gi'] ?? 0);
        $this->db->bind(':client_name', $data['client_name']);
        $this->db->bind(':tags', $data['tags']);
        $this->db->bind(':video_url', $data['video_url']);
        $this->db->bind(':detail_content_id', $data['detail_content_id']);
        $this->db->bind(':detail_content_en', $data['detail_content_en']);
        $this->db->bind(':targets_id', $data['targets_id']);
        $this->db->bind(':targets_en', $data['targets_en']);
        $this->db->bind(':metrics_id', $data['metrics_id']);
        $this->db->bind(':metrics_en', $data['metrics_en']);
        $this->db->bind(':approach_id', $data['approach_id']);
        $this->db->bind(':approach_en', $data['approach_en']);
        $this->db->bind(':highlights', $data['highlights']);
        $this->db->bind(':project_logos', $data['project_logos']);

        return $this->db->execute();
    }

    public function update($data) {
        $query = "UPDATE " . $this->table . " SET 
                  title_id = :title_id, 
                  title_en = :title_en, 
                  subtitle_id = :subtitle_id, 
                  subtitle_en = :subtitle_en, 
                  description_id = :description_id, 
                  description_en = :description_en, 
                  icon_name = :icon_name, 
                  cover_image = :cover_image, 
                  main_category = :main_category, 
                  home_category = :home_category, 
                  partnership_category = :partnership_category, 
                  gi_category = :gi_category, 
                  partner_type = :partner_type, 
                  year_start = :year_start, 
                  year_end = :year_end, 
                  show_home = :show_home, 
                  show_partnership = :show_partnership, 
                  show_gi = :show_gi, 
                  client_name = :client_name, 
                  tags = :tags,
                  video_url = :video_url,
                  detail_content_id = :detail_content_id,
                  detail_content_en = :detail_content_en,
                  targets_id = :targets_id,
                  targets_en = :targets_en,
                  metrics_id = :metrics_id,
                  metrics_en = :metrics_en,
                  approach_id = :approach_id,
                  approach_en = :approach_en,
                  highlights = :highlights,
                  project_logos = :project_logos
                  WHERE id = :id";
        
        $this->db->query($query);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title_id', $data['title_id']);
        $this->db->bind(':title_en', $data['title_en']);
        $this->db->bind(':subtitle_id', $data['subtitle_id']);
        $this->db->bind(':subtitle_en', $data['subtitle_en']);
        $this->db->bind(':description_id', $data['description_id']);
        $this->db->bind(':description_en', $data['description_en']);
        $this->db->bind(':icon_name', $data['icon_name']);
        $this->db->bind(':cover_image', $data['cover_image']);
        $this->db->bind(':main_category', $data['main_category']);
        $this->db->bind(':home_category', $data['home_category']);
        $this->db->bind(':partnership_category', $data['partnership_category']);
        $this->db->bind(':gi_category', $data['gi_category']);
        $this->db->bind(':partner_type', $data['partner_type']);
        $this->db->bind(':year_start', $data['year_start']);
        $this->db->bind(':year_end', $data['year_end']);
        $this->db->bind(':show_home', $data['show_home'] ?? 0);
        $this->db->bind(':show_partnership', $data['show_partnership'] ?? 0);
        $this->db->bind(':show_gi', $data['show_gi'] ?? 0);
        $this->db->bind(':client_name', $data['client_name']);
        $this->db->bind(':tags', $data['tags']);
        $this->db->bind(':video_url', $data['video_url']);
        $this->db->bind(':detail_content_id', $data['detail_content_id']);
        $this->db->bind(':detail_content_en', $data['detail_content_en']);
        $this->db->bind(':targets_id', $data['targets_id']);
        $this->db->bind(':targets_en', $data['targets_en']);
        $this->db->bind(':metrics_id', $data['metrics_id']);
        $this->db->bind(':metrics_en', $data['metrics_en']);
        $this->db->bind(':approach_id', $data['approach_id']);
        $this->db->bind(':approach_en', $data['approach_en']);
        $this->db->bind(':highlights', $data['highlights']);
        $this->db->bind(':project_logos', $data['project_logos']);

        return $this->db->execute();
    }

    public function getByShowPartner() {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE partner_type = "partner" OR show_partnership = 1 ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    public function delete($id) {
        $this->db->query('DELETE FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
