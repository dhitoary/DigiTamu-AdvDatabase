<?php
require_once 'config.php';
$error = '';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $nama_lengkap = $conn->real_escape_string($_POST['nama_lengkap']);

    if (empty($username) || empty($password) || empty($nama_lengkap)) {
        $error = "Semua field wajib diisi.";
    } else {
        $sql_check = "SELECT penyelenggara_id FROM penyelenggara WHERE username = ?";
        if ($stmt_check = $conn->prepare($sql_check)) {
            $stmt_check->bind_param("s", $username);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                $error = "Username sudah digunakan.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $sql_insert = "INSERT INTO penyelenggara (username, password, nama_lengkap) VALUES (?, ?, ?)";
                
                if ($stmt_insert = $conn->prepare($sql_insert)) {
                    $stmt_insert->bind_param("sss", $username, $hashed_password, $nama_lengkap);
                    if ($stmt_insert->execute()) {
                        header("location: login.php");
                        exit;
                    } else {
                        $error = "Gagal mendaftar.";
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - DigiTamu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-layout">

    <div class="form-container-center">
        <div class="text-center">
            <h2 style="color: var(--primary); margin-bottom: 5px;">Buat Akun</h2>
            <p>Daftar Penyelenggara Baru</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="alert"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Buat Username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Buat Password" required>
            </div>
            <button type="submit" class="btn btn-block" style="padding: 12px; margin-top:10px;">DAFTAR</button>
        </form>

        <p class="text-center" style="margin-top: 20px; font-size: 14px;">
            Sudah punya akun? <a href="login.php" style="color: var(--primary); font-weight: bold;">Login</a>
        </p>
    </div>

</body>
</html>