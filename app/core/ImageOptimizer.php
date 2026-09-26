<?php
// app/core/ImageOptimizer.php
// Admin > Pengaturan > Situs > Optimasi Gambar: makes images that were uploaded before automatic
// compression existed lighter. Runs in small batches so it never hits the server time limit.
//
// - JPG/WEBP bigger than needed are shrunk in place (same name, nothing in the database changes).
// - Large PNG photos used on the site get a WebP copy next to them (name.opt.webp, git-ignored, so it
//   only ever exists on the server) and the database is pointed to it.
//   The PNG itself is left untouched, so files that are in the git repository never change on the
//   server (a changed tracked file would break the next git-pull deploy).
// - app/storage/optimized_images.json lists files already shrunk in the repository: skipped here.

class ImageOptimizer {
    const ROOT = 'img';          // under assets/
    const MIN_BYTES = 400 * 1024; // smaller files are fine as they are
    const STATE = '/storage/optimized_server.json';

    private static function assetsDir() {
        return realpath(dirname(__DIR__, 2) . '/assets');
    }

    private static function readJson($file) {
        $data = is_file($file) ? json_decode((string) file_get_contents($file), true) : null;
        return is_array($data) ? $data : [];
    }

    /** Every file name mentioned in text columns of the database => true */
    private static function referencedNames() {
        $db = new Database;
        $names = [];
        $db->query('SHOW TABLES');
        foreach ($db->resultSet() as $t) {
            $table = array_values((array) $t)[0];
            $db->query("SHOW COLUMNS FROM `$table`");
            foreach ($db->resultSet() as $col) {
                if (!preg_match('/char|text/i', $col->Type)) continue;
                $db->query("SELECT `{$col->Field}` AS v FROM `$table` WHERE `{$col->Field}` REGEXP '\\\\.(png|PNG)'");
                foreach ($db->resultSet() as $row) {
                    if (preg_match_all('/[\w.\-]+\.png/i', (string) $row->v, $m)) {
                        foreach ($m[0] as $n) $names[$n] = true;
                    }
                }
            }
        }
        return $names;
    }

    /** Work still to do: [['path' => relative to assets/, 'action' => 'shrink'|'webp', 'bytes' => int], ...] */
    public static function pending() {
        $assets = self::assetsDir();
        $repo = self::readJson(dirname(__DIR__) . '/storage/optimized_images.json');
        $done = self::readJson(dirname(__DIR__) . self::STATE);
        $used = null;
        $todo = [];
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($assets . '/' . self::ROOT, FilesystemIterator::SKIP_DOTS));
        foreach ($it as $f) {
            $rel = ltrim(str_replace('\\', '/', substr($f->getPathname(), strlen($assets))), '/');
            if (!preg_match('/\.(jpe?g|png|webp)$/i', $rel)) continue;
            $size = $f->getSize();
            if (isset($done[$rel])) continue;
            $dims = @getimagesize($f->getPathname());
            $tooWide = $dims && max($dims[0], $dims[1]) > Upload::maxSideFor($f->getPathname());
            if ($size < self::MIN_BYTES && !($tooWide && $size > 100 * 1024)) continue;

            if (preg_match('/\.png$/i', $rel)) {
                if ($used === null) $used = self::referencedNames();
                $webp = preg_replace('/\.png$/i', '.opt.webp', $f->getFilename());
                if (isset($used[$f->getFilename()]) && !isset($used[$webp])) {
                    $todo[] = ['path' => $rel, 'action' => 'webp', 'bytes' => $size];
                }
            } elseif (!(isset($repo[$rel]) && $repo[$rel] === $size)) {
                $todo[] = ['path' => $rel, 'action' => 'shrink', 'bytes' => $size];
            }
        }
        return $todo;
    }

    /** Process up to $limit files. Returns ['done' => n, 'saved' => bytes, 'remaining' => n] */
    public static function runBatch($limit = 8) {
        @set_time_limit(120);
        $assets = self::assetsDir();
        $stateFile = dirname(__DIR__) . self::STATE;
        $state = self::readJson($stateFile);
        $todo = self::pending();
        $saved = 0;
        $done = 0;
        foreach (array_slice($todo, 0, $limit) as $item) {
            $path = $assets . '/' . $item['path'];
            if ($item['action'] === 'shrink') {
                $saved += Upload::shrinkInPlace($path);
            } else {
                $saved += self::pngToWebp($path);
            }
            $state[$item['path']] = date('c'); // never try the same file twice
            $done++;
        }
        @file_put_contents($stateFile, json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        return ['done' => $done, 'saved' => $saved, 'remaining' => max(0, count($todo) - $done)];
    }

    /** WebP copy of a PNG + database now points to it. Returns bytes saved per page view. */
    private static function pngToWebp($pngPath) {
        $dir = dirname($pngPath);
        $name = basename($pngPath);
        $copy = $dir . '/' . uniqid('opt_', true) . '.png';
        if (!@copy($pngPath, $copy)) return 0;
        $result = Upload::optimizeImage($copy);           // resizes + converts, returns new name
        if (!preg_match('/\.webp$/i', $result)) { @unlink($copy); return 0; }
        $webpName = preg_replace('/\.png$/i', '.opt.webp', $name);
        if (!@rename($dir . '/' . $result, $dir . '/' . $webpName)) { @unlink($dir . '/' . $result); return 0; }
        $saved = filesize($pngPath) - filesize($dir . '/' . $webpName);

        // Point every text column that mentions the PNG to the WebP
        $db = new Database;
        $db->query('SHOW TABLES');
        foreach ($db->resultSet() as $t) {
            $table = array_values((array) $t)[0];
            $db->query("SHOW COLUMNS FROM `$table`");
            foreach ($db->resultSet() as $col) {
                if (!preg_match('/char|text/i', $col->Type)) continue;
                $c = "`{$col->Field}`";
                if (strpos($name, 'gs_') === 0) {
                    // uploaded files have unique names: safe to replace wherever they appear
                    $pairs = [[$name, $webpName]];
                } else {
                    // other names (e.g. banner-1.png) only as a whole value or as a quoted JSON item
                    $pairs = [['"' . $name . '"', '"' . $webpName . '"']];
                    $db->query("UPDATE `$table` SET $c = :new WHERE $c = :old");
                    $db->bind(':old', $name);
                    $db->bind(':new', $webpName);
                    $db->execute();
                }
                foreach ($pairs as [$old, $new]) {
                    $db->query("UPDATE `$table` SET $c = REPLACE($c, :old, :new) WHERE $c LIKE :like");
                    $db->bind(':old', $old);
                    $db->bind(':new', $new);
                    $db->bind(':like', '%' . $old . '%');
                    $db->execute();
                }
            }
        }
        return max(0, $saved);
    }
}
