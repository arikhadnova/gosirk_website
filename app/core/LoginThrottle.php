<?php
// app/core/LoginThrottle.php
// Stops password guessing: after too many failed attempts in WINDOW seconds, the username
// (or the whole IP address) has to wait. Also used to limit "forgot password" emails.
// Attempts are stored as unix timestamps in `login_attempts` (table created automatically).

class LoginThrottle {
    const WINDOW = 900; // 15 minutes

    // scope => [max per username/email, max per IP address]
    const LIMITS = [
        'login' => [5, 15],
        'reset' => [3, 5],
    ];

    private static function db() {
        static $db = null;
        if ($db === null) {
            $db = new Database;
            try {
                $db->query("CREATE TABLE IF NOT EXISTS login_attempts (
                    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    scope VARCHAR(20) NOT NULL,
                    ident VARCHAR(191) NOT NULL,
                    ip VARCHAR(45) NOT NULL,
                    attempted_at INT UNSIGNED NOT NULL,
                    KEY scope_ident (scope, ident, attempted_at),
                    KEY scope_ip (scope, ip, attempted_at)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
                $db->execute();
            } catch (PDOException $e) {
                error_log('[GoSirk] Tabel login_attempts: ' . $e->getMessage());
            }
        }
        return $db;
    }

    private static function ip() {
        return substr($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0', 0, 45);
    }

    private static function norm($ident) {
        return mb_strtolower(trim((string) $ident));
    }

    /** Seconds until this username/email (or this IP) may try again; 0 = allowed. */
    public static function lockedFor($scope, $ident) {
        [$maxIdent, $maxIp] = self::LIMITS[$scope];
        $since = time() - self::WINDOW;
        $wait = 0;
        foreach ([['ident', self::norm($ident), $maxIdent], ['ip', self::ip(), $maxIp]] as [$col, $value, $max]) {
            $db = self::db();
            $db->query("SELECT attempted_at FROM login_attempts WHERE scope = :scope AND $col = :v AND attempted_at > :since
                        ORDER BY attempted_at DESC LIMIT " . (int) $max);
            $db->bind(':scope', $scope);
            $db->bind(':v', $value);
            $db->bind(':since', $since);
            $rows = $db->resultSet();
            if (count($rows) >= $max) {
                // free again when the oldest of the last $max attempts leaves the window
                $wait = max($wait, (int) end($rows)->attempted_at + self::WINDOW - time());
            }
        }
        return max(0, $wait);
    }

    /** Attempts left for this username/email before it is locked. */
    public static function remaining($scope, $ident) {
        $db = self::db();
        $db->query("SELECT COUNT(*) AS n FROM login_attempts WHERE scope = :scope AND ident = :v AND attempted_at > :since");
        $db->bind(':scope', $scope);
        $db->bind(':v', self::norm($ident));
        $db->bind(':since', time() - self::WINDOW);
        return max(0, self::LIMITS[$scope][0] - (int) $db->single()->n);
    }

    public static function hit($scope, $ident) {
        $db = self::db();
        $db->query("INSERT INTO login_attempts (scope, ident, ip, attempted_at) VALUES (:scope, :ident, :ip, :t)");
        $db->bind(':scope', $scope);
        $db->bind(':ident', self::norm($ident));
        $db->bind(':ip', self::ip());
        $db->bind(':t', time());
        $db->execute();

        // keep the table small
        $db->query("DELETE FROM login_attempts WHERE attempted_at < :old");
        $db->bind(':old', time() - 86400);
        $db->execute();
    }

    /** After a successful login the counters for this username and IP start over. */
    public static function clear($scope, $ident) {
        $db = self::db();
        $db->query("DELETE FROM login_attempts WHERE scope = :scope AND (ident = :ident OR ip = :ip)");
        $db->bind(':scope', $scope);
        $db->bind(':ident', self::norm($ident));
        $db->bind(':ip', self::ip());
        $db->execute();
    }

    /** "15 menit" / "1 menit" */
    public static function waitLabel($seconds) {
        return max(1, (int) ceil($seconds / 60)) . ' menit';
    }
}
