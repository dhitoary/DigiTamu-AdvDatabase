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

    if (empty($username) || empty($password)) {
        $error = "Username dan password wajib diisi.";
    } else {
        $sql = "SELECT penyelenggara_id, username, password, nama_lengkap FROM penyelenggara WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($penyelenggara_id, $db_username, $hashed_password, $nama_lengkap);
                if ($stmt->fetch()) {
                    if (password_verify($password, $hashed_password)) {
                        $_SESSION['loggedin'] = true;
                        $_SESSION['user_id'] = $penyelenggara_id;
                        $_SESSION['nama_lengkap'] = $nama_lengkap;
                        header("location: dashboard.php");
                        exit;
                    } else { $error = "Password salah."; }
                }
            } else { $error = "Username tidak ditemukan."; }
            $stmt->close();
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DigiTamu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-layout"> 

    <div class="form-container-center">
        <div class="text-center">
            <h2 style="color: var(--primary); margin-bottom: 5px;">DigiTamu</h2>
            <p>Masuk Administrator</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="alert"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-block" style="padding: 12px; margin-top:10px;">LOGIN</button>
        </form>

        <p class="text-center" style="margin-top: 20px; font-size: 14px;">
            Belum punya akun? <a href="register.php" style="color: var(--primary); font-weight: bold;">Daftar</a>
        </p>
    </div>

</body>
</html>