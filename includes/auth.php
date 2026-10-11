<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('USERS_FILE_PATH', __DIR__ . '/../data/users.json');

/**
 * Membaca seluruh data pengguna dari users.json
 */
function getUsers(): array {
    if (!file_exists(USERS_FILE_PATH)) {
        return [];
    }
    $content = file_get_contents(USERS_FILE_PATH);
    $data = json_decode($content, true);
    return is_array($data) ? $data : [];
}

/**
 * Menyimpan data pengguna ke users.json
 */
function saveUsers(array $users): bool {
    $dir = dirname(USERS_FILE_PATH);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    return (bool) file_put_contents(
        USERS_FILE_PATH,
        json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
    );
}

/**
 * Mencari pengguna berdasarkan email
 */
function findUserByEmail(string $email): ?array {
    $users = getUsers();
    $email = strtolower(trim($email));
    foreach ($users as $user) {
        if (strtolower(trim($user['email'] ?? '')) === $email) {
            return $user;
        }
    }
    return null;
}

/**
 * Mendapatkan pengguna yang sedang aktif/login saat ini
 */
function getCurrentUser(bool $requireLogin = false): array {
    if (!empty($_SESSION['user']) && is_array($_SESSION['user'])) {
        return $_SESSION['user'];
    }

    if ($requireLogin) {
        header('Location: login.php');
        exit;
    }

    // Default ke pengguna pertama jika belum ada sesi login aktif
    $users = getUsers();
    if (!empty($users[0])) {
        return $users[0];
    }

    return [
        'fullname' => 'Alya Prameswari',
        'member_id' => 'ADM-001',
        'email' => 'alya@booktrack.id',
        'role' => 'Administrator',
        'created_at' => '2026-01-01 08:00:00'
    ];
}

/**
 * Proses login pengguna
 */
function loginUser(string $email, string $password): array {
    $user = findUserByEmail($email);
    if (!$user) {
        return ['success' => false, 'message' => 'Email atau kata sandi tidak cocok.'];
    }

    if (!password_verify($password, $user['password'] ?? '')) {
        return ['success' => false, 'message' => 'Email atau kata sandi tidak cocok.'];
    }

    $_SESSION['user'] = $user;
    $_SESSION['user_name'] = $user['fullname'];
    $_SESSION['user_email'] = $user['email'];

    return ['success' => true, 'user' => $user];
}

/**
 * Proses registrasi pengguna baru
 */
function registerUser(string $fullname, string $member_id, string $email, string $password): array {
    $fullname = trim($fullname);
    $member_id = trim($member_id);
    $email = trim($email);

    if (empty($fullname) || empty($member_id) || empty($email) || empty($password)) {
        return ['success' => false, 'message' => 'Semua kolom wajib diisi.'];
    }

    if (strlen($password) < 8) {
        return ['success' => false, 'message' => 'Kata sandi minimal harus 8 karakter.'];
    }

    $users = getUsers();

    // Validasi duplikasi email & nomor anggota
    foreach ($users as $user) {
        if (strtolower($user['email'] ?? '') === strtolower($email)) {
            return ['success' => false, 'message' => 'Email ini sudah terdaftar. Silakan gunakan email lain.'];
        }
        if (($user['member_id'] ?? '') === $member_id) {
            return ['success' => false, 'message' => 'Nomor Anggota / ID sudah terdaftar.'];
        }
    }

    $newUser = [
        'fullname' => $fullname,
        'member_id' => $member_id,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'role' => 'Anggota',
        'created_at' => date('Y-m-d H:i:s')
    ];

    $users[] = $newUser;
    saveUsers($users);

    $_SESSION['user'] = $newUser;
    $_SESSION['user_name'] = $newUser['fullname'];
    $_SESSION['user_email'] = $newUser['email'];

    return ['success' => true, 'user' => $newUser];
}

/**
 * Proses logout pengguna
 */
function logoutUser(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    session_destroy();
}
