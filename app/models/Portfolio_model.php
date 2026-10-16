<?php
// app/models/Portfolio_model.php

class Portfolio_model {
    private $table = 'portfolios';
    private $positions = 'portfolio_positions';
    private $db;

    /**
     * Every place on the website that lists portfolios, each with its own order (set in the admin list).
     * Key => label in the admin. A portfolio appears in a place when it matches inLocation().
     */
    const LOCATIONS = [
        'home.daerah' => 'Home › 1. Peningkatan Kapasitas Daerah',
        'home.desa' => 'Home › 2. Pendampingan Desa',
        'home.kampanye' => 'Home › 3. Kampanye Perubahan Perilaku',
        'home.modul' => 'Home › 4. Penyusunan Modul & Toolkit',
        'home.webinar' => 'Home › 5. Webinar & E-learning',
        'home.akademik' => 'Home › 6. Academic Partnership',
        'partnership.community' => 'Partnership › Community Partnership',
        'partnership.academic' => 'Partnership › Academic Partnership',
        'partnership.program' => 'Partnership › Program Partnership',
        'gi.daerah' => 'GoSirk Institute › 1. Peningkatan Kapasitas Daerah',
        'gi.desa' => 'GoSirk Institute › 2. Pendampingan Desa',
        'gi.kampanye' => 'GoSirk Institute › 3. Kampanye Perubahan Perilaku',
        'gi.modul' => 'GoSirk Institute › 4. Penyusunan Modul & Toolkit',
        'gi.webinar' => 'GoSirk Institute › 5. Webinar & E-learning',
        'gi.akademik' => 'GoSirk Institute › 6. Academic Partnership',
        'implementasi_partner' => 'Implementasi Partner',
    ];

    /** The numbered sections of the portfolio lists on Home and GoSirk Institute (headings: gi.cat1_title ...) */
    const SECTIONS = [
        'daerah' => '1. Peningkatan Kapasitas Daerah',
        'desa' => '2. Pendampingan Desa',
        'kampanye' => '3. Kampanye Perubahan Perilaku',
        'modul' => '4. Penyusunan Modul & Toolkit',
        'webinar' => '5. Webinar & E-learning',
        'akademik' => '6. Academic Partnership',
    ];

    public function __construct() {
        $this->db = new Database;
        $this->ensureSortOrder();
        $this->ensurePositionsTable();
    }

    // Position of a portfolio in one place; portfolios without a row (new, or never sorted there) come last
    private function ensurePositionsTable() {
        $this->db->query("SHOW TABLES LIKE '{$this->positions}'");
        if ($this->db->single()) return;
        try {
            $this->db->query("CREATE TABLE IF NOT EXISTS {$this->positions} (
                location VARCHAR(40) NOT NULL,
                portfolio_id INT NOT NULL,
                position INT NOT NULL,
                PRIMARY KEY (location, portfolio_id)
            )");
            $this->db->execute();
        } catch (PDOException $e) {
            error_log('[GoSirk] Tabel urutan portofolio gagal dibuat: ' . $e->getMessage());
        }
    }

    /**
     * Section of a portfolio on Home. Portfolios saved before Home had sections (no or an old home_category)
     * use their GoSirk Institute section; null when neither is set.
     */
    public static function homeSection($p) {
        if (isset(self::SECTIONS[$p->home_category ?? ''])) return $p->home_category;
        return isset(self::SECTIONS[$p->gi_category ?? '']) ? $p->gi_category : null;
    }

    /** Does the portfolio appear in this place on the website? ('home' alone: anywhere on Home) */
    public static function inLocation($p, $location) {
        [$page, $category] = array_pad(explode('.', $location, 2), 2, null);
        if ($page === 'home') return $p->show_home && ($category === null || self::homeSection($p) === $category);
        if ($page === 'partnership') return $p->show_partnership && $p->partnership_category === $category;
        if ($page === 'gi') return $p->show_gi && $p->gi_category === $category;
        if ($page === 'implementasi_partner') return $p->partner_type === 'partner' || $p->show_partnership;
        return false;
    }

    // Place of a portfolio on a page: the page itself, or its category tab / section (partnership, gi, home)
    private static function locationOn($p, $page) {
        if ($page === 'home') return 'home.' . self::homeSection($p);
        if ($page === 'partnership') return 'partnership.' . $p->partnership_category;
        if ($page === 'gi') return 'gi.' . $p->gi_category;
        return $page;
    }

    // [location => [portfolio id => position]]
    private function positionMap() {
        $this->db->query("SELECT location, portfolio_id, position FROM {$this->positions}");
        $map = [];
        foreach ($this->db->resultSet() as $r) $map[$r->location][(int) $r->portfolio_id] = (int) $r->position;
        return $map;
    }

    /**
     * Portfolios in the order set for a page ('home', 'partnership', 'gi', 'implementasi_partner').
     * On pages with category tabs every portfolio sits in one tab, so one sorted list gives each tab its order.
     * Home lists its sections one after another, so there the section comes first. Home portfolios not sorted
     * in their section yet keep the order the whole of Home had before it had sections.
     */
    public function sortForPage(array $items, $page) {
        $map = $this->positionMap();
        $rank = array_flip(array_keys(self::SECTIONS));
        $key = fn($p) => [
            $page === 'home' ? ($rank[self::homeSection($p) ?? ''] ?? count($rank)) : 0,
            $map[self::locationOn($p, $page)][(int) $p->id] ?? $map[$page][(int) $p->id] ?? PHP_INT_MAX,
        ];
        usort($items, fn($a, $b) => $key($a) <=> $key($b)); // stable: unsorted ones keep the default order
        return $items;
    }

    /** The portfolios shown in one place, in the order shown on the website */
    public function getForLocation($location) {
        $page = explode('.', $location, 2)[0];
        $items = array_values(array_filter($this->getAll(), fn($p) => self::inLocation($p, $location)));
        return $this->sortForPage($items, $page);
    }

    /** Save the order chosen in the admin list for one place */
    public function saveLocationOrder($location, array $ids) {
        $this->db->query("DELETE FROM {$this->positions} WHERE location = :loc");
        $this->db->bind(':loc', $location);
        $this->db->execute();
        $position = 0;
        foreach ($ids as $id) {
            $this->db->query("INSERT INTO {$this->positions} (location, portfolio_id, position) VALUES (:loc, :id, :pos)");
            $this->db->bind(':loc', $location);
            $this->db->bind(':id', (int) $id);
            $this->db->bind(':pos', ++$position);
            $this->db->execute();
        }
    }

    // Older databases have no sort_order: add it once and number the rows in the order the site showed
    // until now (newest first), so nothing moves; portfolios added later go to the end (see add()).
    // It is the default order wherever an admin has not set one (see sortForPage()).
    private function ensureSortOrder() {
        $this->db->query("SHOW COLUMNS FROM {$this->table} LIKE 'sort_order'");
        if ($this->db->single()) return;
        try {
            $this->db->query("ALTER TABLE {$this->table} ADD COLUMN sort_order INT NOT NULL DEFAULT 0");
            $this->db->execute();
        } catch (PDOException $e) {
            // A parallel request right after deploy may have added it already
            if (strpos($e->getMessage(), 'Duplicate column') === false) error_log('[GoSirk] Migrasi portfolio gagal: ' . $e->getMessage());
            return;
        }
        $this->db->query("SELECT id FROM {$this->table} ORDER BY created_at DESC, id DESC");
        $this->reorder(array_column($this->db->resultSet(), 'id'));
    }

    public function getAll() {
        $this->db->query('SELECT * FROM ' . $this->table . ' ORDER BY sort_order ASC, created_at DESC');
        return $this->db->resultSet();
    }

    // Default order: the first id gets 1, the next 2, ...
    private function reorder(array $ids) {
        $position = 0;
        foreach ($ids as $id) {
            $this->db->query("UPDATE {$this->table} SET sort_order = :pos WHERE id = :id");
            $this->db->bind(':pos', ++$position);
            $this->db->bind(':id', (int) $id);
            $this->db->execute();
        }
    }

    /**
     * Portfolios saved while the admin form was in EN mode have the Indonesian text in the English
     * column and an empty Indonesian column. Move it back and translate it; returns how many were fixed.
     */
    public function repairSwappedLanguages() {
        $fields = ['title', 'subtitle', 'description', 'detail_content'];
        $where = implode(' OR ', array_map(fn($f) => "(TRIM(COALESCE({$f}_id, '')) = '' AND TRIM(COALESCE({$f}_en, '')) <> '')", $fields));
        $this->db->query("SELECT * FROM {$this->table} WHERE $where");
        $fixed = 0;
        foreach ($this->db->resultSet() as $row) {
            $set = [];
            foreach ($fields as $f) {
                $idText = trim(strip_tags((string) $row->{$f . '_id'}));
                if ($idText !== '' || trim((string) $row->{$f . '_en'}) === '') continue;
                $set[$f . '_id'] = $row->{$f . '_en'};
                $set[$f . '_en'] = Translator::translate($row->{$f . '_en'});
            }
            if (!$set) continue;
            $this->db->query("UPDATE {$this->table} SET " . implode(', ', array_map(fn($c) => "$c = :$c", array_keys($set))) . ' WHERE id = :id');
            foreach ($set as $c => $v) $this->db->bind(":$c", $v);
            $this->db->bind(':id', $row->id);
            $this->db->execute();
            $fixed++;
        }
        return $fixed;
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
                   detail_content_id, detail_content_en, targets_id, targets_en, metrics_id, metrics_en, approach_id, approach_en, highlights, project_logos, sort_order) 
                  VALUES 
                  (:title_id, :title_en, :subtitle_id, :subtitle_en, :description_id, :description_en, 
                   :icon_name, :cover_image, :main_category, :home_category, :partnership_category, :gi_category, :partner_type, :year_start, :year_end, 
                   :show_home, :show_partnership, :show_gi, :client_name, :tags, :video_url, 
                   :detail_content_id, :detail_content_en, :targets_id, :targets_en, :metrics_id, :metrics_en, :approach_id, :approach_en, :highlights, :project_logos,
                   (SELECT next_pos FROM (SELECT COALESCE(MAX(sort_order), 0) + 1 AS next_pos FROM " . $this->table . ") AS t))";
        
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

    public function delete($id) {
        $this->db->query("DELETE FROM {$this->positions} WHERE portfolio_id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
        $this->db->query('DELETE FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
