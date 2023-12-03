<?php
include 'db.php';

function login($nim, $password, $remember_me_token = false) {
    $user = getUserByNIM($nim);

    global $conn;

  
    $stmt = $conn->prepare("SELECT * FROM users WHERE nim = :nim");
    $stmt->bindParam(':nim', $nim);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        updateLoginAttempts($user['id'], 0);

        $_SESSION['user'] = $user;

        if ($remember_me_token) {
            setRememberMeCookie($user['id']);
        }

        return true;
    } else {
        $attempts = getLoginAttempts($nim);
        updateLoginAttempts($user['id'], $attempts + 1);

        return false;
    }

}

function setRememberMeCookie($userId) {
    global $conn;

    $token = bin2hex(random_bytes(32));
    
    $expireTime = time() + (30 * 24 * 60 * 60);

    $stmt = $conn->prepare("UPDATE users SET remember_me_token = :token, remember_me_expire = :expire WHERE id = :id");
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':expire', date('Y-m-d H:i:s', $expireTime));
    $stmt->bindParam(':id', $userId);
    $stmt->execute();

    $cookieValue = base64_encode("{$userId}:{$token}");
    setcookie('remember', $cookieValue, $expireTime, '/', '', true, true);
}


function getLoginAttempts($nim) {
    global $conn;
    $stmt = $conn->prepare("SELECT login_attempts FROM users WHERE nim = :nim");
    $stmt->bindParam(':nim', $nim);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function updateLoginAttempts($userId, $attempts) {
    global $conn;
    $stmt = $conn->prepare("UPDATE users SET login_attempts = :attempts WHERE id = :id");
    $stmt->bindParam(':attempts', $attempts);
    $stmt->bindParam(':id', $userId);
    $stmt->execute();
}
    

function register($username, $nim, $password) {
    global $conn;
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $role = 'user';

    $stmt = $conn->prepare("INSERT INTO users (username, nim, password, role) VALUES (:username, :nim, :password, :role)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':nim', $nim);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':role', $role);

    return $stmt->execute();
}

function addDataNilai($nim, $nama, $asg, $uts, $uas) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO nilai (nim, nama, asg, uts, uas) VALUES (:nim, :nama, :asg, :uts, :uas)");
    $stmt->bindParam(':nim', $nim);
    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':asg', $asg);
    $stmt->bindParam(':uts', $uts);
    $stmt->bindParam(':uas', $uas);
    return $stmt->execute();
}

function updateDataNilai($id, $nama, $asg, $uts, $uas) {
    global $conn;
    $stmt = $conn->prepare("UPDATE nilai SET nama = :nama, asg = :asg, uts = :uts, uas = :uas WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':asg', $asg);
    $stmt->bindParam(':uts', $uts);
    $stmt->bindParam(':uas', $uas);
    return $stmt->execute();
}

function deleteDataNilai($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM nilai WHERE id = :id");
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

function getDataNilai() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM nilai");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getDataNilaiByNIM($nim) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM nilai WHERE nim = :nim");
    $stmt->bindParam(':nim', $nim);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getDataNilaiById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM nilai WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function createUser($username, $nim, $password) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO users (username, nim, password) VALUES (:username, :nim, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':nim', $nim);
    $stmt->bindParam(':password', $password);
    return $stmt->execute();
}

function getUserByNIM($nim) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE nim = :nim");
    $stmt->bindParam(':nim', $nim);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getDaftarDosen() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM dosen");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function isNIMExist($nim) {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE nim = :nim");
    $stmt->bindParam(':nim', $nim);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}


function isLockedOut($nim) {
    global $conn;
    $stmt = $conn->prepare("SELECT login_attempts FROM users WHERE nim = :nim");
    $stmt->bindParam(':nim', $nim);
    $stmt->execute();
    $attempts = $stmt->fetchColumn();
    return $attempts >= MAX_LOGIN_ATTEMPTS;
}

function lockoutUser($nim) {
    
    global $conn;
    $stmt = $conn->prepare("UPDATE users SET login_attempts = :attempts, locked_out_until = :lockout WHERE nim = :nim");
    $stmt->bindParam(':attempts', 0);
    $stmt->bindParam(':lockout', time() + LOCKOUT_DURATION);
    $stmt->bindParam(':nim', $nim);
    $stmt->execute();
}


?>

