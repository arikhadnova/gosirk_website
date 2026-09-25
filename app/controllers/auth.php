<?php

class Auth extends Controller {
    public function __construct() {
        // Login and password forms must carry the CSRF token of this session
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !Csrf::validRequest()) {
            Csrf::reject(BASE_URL . 'auth');
        }
    }

    public function index() {
        if (isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . BASE_URL . 'admin');
            exit;
        }

        $data['title'] = 'Admin Login - GoSirk';
        $this->views('auth/login', $data);
    }

    public function login() {
        if (isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . BASE_URL . 'admin');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // Too many wrong passwords for this username or from this IP: wait first
        $wait = LoginThrottle::lockedFor('login', $username);
        if ($wait > 0) {
            Flasher::setFlash('Terlalu banyak percobaan login.', 'Demi keamanan, coba lagi dalam ' . LoginThrottle::waitLabel($wait) . '.', 'danger');
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }

        $user = $username !== '' ? $this->model('User_model')->getUserByUsername($username) : null;

        if ($user && password_verify($password, $user->password)) {
            LoginThrottle::clear('login', $username);
            // New session id after login, so an id planted before login is worthless
            session_regenerate_id(true);
            Csrf::rotate();
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->name;
            $_SESSION['user_role'] = $user->role;
            $_SESSION['user_photo'] = $user->photo;

            header('Location: ' . BASE_URL . 'admin');
            exit;
        }

        // Same message for unknown user and wrong password, so usernames cannot be guessed
        LoginThrottle::hit('login', $username);
        $left = LoginThrottle::remaining('login', $username);
        if ($left === 0) {
            Flasher::setFlash('Terlalu banyak percobaan login.', 'Demi keamanan, coba lagi dalam ' . LoginThrottle::waitLabel(LoginThrottle::WINDOW) . '.', 'danger');
        } else {
            Flasher::setFlash('Username atau password salah.', $left <= 2 ? "Sisa $left percobaan sebelum login dikunci sementara." : '', 'danger');
        }
        header('Location: ' . BASE_URL . 'auth');
        exit;
    }

    public function logout() {
        // Ignore logout links placed on other websites
        if (!Csrf::sameOrigin()) {
            header('Location: ' . BASE_URL . 'admin');
            exit;
        }
        $_SESSION = [];
        $p = session_get_cookie_params();
        setcookie(session_name(), '', ['expires' => time() - 3600, 'path' => $p['path'], 'domain' => $p['domain'], 'secure' => $p['secure'], 'httponly' => $p['httponly'], 'samesite' => $p['samesite'] ?: 'Lax']);
        session_destroy();
        header('Location: ' . BASE_URL . 'auth');
        exit;
    }

    public function forgot_password() {
        $data['title'] = 'Forgot Password - GoSirk';
        $this->views('auth/forgot_password', $data);
    }

    public function send_reset_link() {
        $email = trim($_POST['email'] ?? '');

        // Limit reset emails (prevents using this form to flood an inbox)
        $wait = LoginThrottle::lockedFor('reset', $email);
        if ($wait > 0) {
            Flasher::setFlash('Terlalu banyak permintaan.', 'Coba lagi dalam ' . LoginThrottle::waitLabel($wait) . '.', 'danger');
            header('Location: ' . BASE_URL . 'auth/forgot_password');
            exit;
        }
        LoginThrottle::hit('reset', $email);

        $userModel = $this->model('User_model');
        $user = $userModel->getUserByEmail($email);

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            if ($userModel->setResetToken($user->id, $token, $expiry)) {
                $resetLink = BASE_URL . "auth/reset_password/" . $token;
                
                $subject = "Reset Your GoSirk Password";
                $message = "
                    <div style='font-family: sans-serif; padding: 20px; color: #333;'>
                        <h2>Halo, {$user->name}</h2>
                        <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.</p>
                        <p>Silakan klik tombol di bawah ini untuk mereset password Anda. Link ini akan kadaluarsa dalam 1 jam.</p>
                        <div style='text-align: center; margin: 30px 0;'>
                            <a href='{$resetLink}' style='background: #FF8F56; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold;'>Reset Password</a>
                        </div>
                        <p>Jika Anda tidak merasa melakukan permintaan ini, abaikan saja email ini.</p>
                        <hr style='border: none; border-top: 1px solid #eee; margin: 20px 0;'>
                        <p style='font-size: 12px; color: #888;'>Jika tombol tidak berfungsi, copy-paste link berikut ke browser Anda:<br>{$resetLink}</p>
                    </div>
                ";

                require_once dirname(__DIR__) . '/core/Mail.php';
                if (Mail::send($email, $subject, $message)) {
                    Flasher::setFlash('Link reset password', 'telah dikirim ke email Anda.', 'success');
                } else {
                    Flasher::setFlash('Gagal mengirim email.', 'Silakan coba lagi nanti.', 'danger');
                }
            } else {
                Flasher::setFlash('Terjadi kesalahan', 'saat memproses token.', 'danger');
            }
        } else {
            // Same answer as for a registered email, so the form does not reveal which emails have an account
            Flasher::setFlash('Jika email terdaftar,', 'link reset password telah dikirim ke email tersebut.', 'success');
        }

        header('Location: ' . BASE_URL . 'auth/forgot_password');
        exit;
    }

    public function reset_password($token) {
        $user = $this->model('User_model')->getUserByResetToken($token);

        if (!$user) {
            Flasher::setFlash('Link reset', 'tidak valid atau sudah kadaluarsa!', 'danger');
            header('Location: ' . BASE_URL . 'auth/forgot_password');
            exit;
        }

        $data = [
            'title' => 'Reset Password - GoSirk',
            'token' => $token
        ];
        $this->views('auth/reset_password', $data);
    }

    public function process_reset() {
        $token = $_POST['token'];
        $password = $_POST['password'];
        $confirm = $_POST['confirm_password'];

        if ($password !== $confirm) {
            Flasher::setFlash('Konfirmasi password', 'tidak cocok!', 'danger');
            header('Location: ' . BASE_URL . 'auth/reset_password/' . $token);
            exit;
        }

        $userModel = $this->model('User_model');
        $user = $userModel->getUserByResetToken($token);

        if ($user) {
            if ($userModel->resetPassword($user->id, $password)) {
                Flasher::setFlash('Password berhasil diupdate.', 'Silakan login.', 'success');
                header('Location: ' . BASE_URL . 'auth');
                exit;
            } else {
                Flasher::setFlash('Terjadi kesalahan', 'saat mengupdate password.', 'danger');
            }
        } else {
            Flasher::setFlash('Link reset', 'tidak valid atau sudah kadaluarsa!', 'danger');
        }

        header('Location: ' . BASE_URL . 'auth/forgot_password');
        exit;
    }
}
