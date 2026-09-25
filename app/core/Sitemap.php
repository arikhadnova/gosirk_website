<?php
// app/core/Sitemap.php
// /sitemap.xml and /robots.txt, generated from the database so new articles,
// portfolios and GI services are listed for search engines automatically.

class Sitemap {
    // Public pages that always exist
    const PAGES = [
        '', 'about', 'gi', 'ggc', 'go_ngompos_project', 'implementasi_partner', 'konsultan', 'partnership',
        'contact', 'collaboration', 'blog', 'library', 'publication', 'publication/gosirk', 'publication/reference', 'home/impact', 'privacy',
    ];

    public static function serve($file) {
        http_response_code(200); // some servers answer 404 for .txt/.xml files that are not on disk
        if ($file === 'robots.txt') {
            header('Content-Type: text/plain; charset=utf-8');
            echo self::robots();
        } else {
            header('Content-Type: application/xml; charset=utf-8');
            echo self::xml();
        }
        exit;
    }

    public static function robots() {
        return implode("\n", [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /auth',
            'Disallow: /publication/download/',
            'Disallow: /publication/request',
            'Disallow: /collaboration/request',
            'Disallow: /contact/store',
            'Allow: /',
            '',
            'Sitemap: ' . BASE_URL . 'sitemap.xml',
            '',
        ]);
    }

    /** [path, lastmod (Y-m-d or null)] */
    public static function urls() {
        $urls = array_map(fn($p) => [$p, null], self::PAGES);
        $db = new Database;
        $queries = [
            // only published blog articles; drafts are not public
            ["SELECT id, created_at AS d FROM articles WHERE type = 'blog' AND status = 'published' ORDER BY created_at DESC", 'blog/detail/%s', 'id'],
            ["SELECT id, created_at AS d FROM portfolios ORDER BY created_at DESC", 'portfolio/detail/%s', 'id'],
            ["SELECT slug, COALESCE(updated_at, created_at) AS d FROM gi_services WHERE slug IS NOT NULL AND slug <> '' ORDER BY id", 'gi/detail/%s', 'slug'],
        ];
        foreach ($queries as [$sql, $pattern, $key]) {
            try {
                $db->query($sql);
                foreach ($db->resultSet() as $row) {
                    $urls[] = [sprintf($pattern, rawurlencode($row->$key)), $row->d ? date('Y-m-d', strtotime($row->d)) : null];
                }
            } catch (Throwable $e) {
                error_log('[GoSirk] Sitemap: ' . $e->getMessage());
            }
        }
        return $urls;
    }

    public static function xml() {
        $out = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach (self::urls() as [$path, $lastmod]) {
            $out .= '  <url><loc>' . htmlspecialchars(BASE_URL . $path, ENT_XML1) . '</loc>'
                . ($lastmod ? '<lastmod>' . $lastmod . '</lastmod>' : '') . "</url>\n";
        }
        return $out . "</urlset>\n";
    }
}
