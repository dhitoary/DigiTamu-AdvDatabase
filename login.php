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
        $user_found = false;
        $user_id = 0;
        $user_name = '';
        $user_role = 'penyelenggara';
        
        $sql_admin = "SELECT admin_id, username, password, nama_lengkap FROM admin WHERE username = ?";
        if ($stmt_admin = $conn->prepare($sql_admin)) {
            $stmt_admin->bind_param("s", $username);
            $stmt_admin->execute();
            $stmt_admin->store_result();
            
            if ($stmt_admin->num_rows == 1) {
                $stmt_admin->bind_result($admin_id, $db_username, $hashed_password, $nama_lengkap);
                if ($stmt_admin->fetch()) {
                    if (password_verify($password, $hashed_password)) {
                        $user_found = true;
                        $user_id = $admin_id;
                        $user_name = $nama_lengkap;
                        $user_role = 'admin';
                    } else {
                        $error = "Password salah.";
                    }
                }
            }
            $stmt_admin->close();
        }
        
        if (!$user_found && empty($error)) {
            $sql_penyelenggara = "SELECT penyelenggara_id, username, password, nama_lengkap FROM penyelenggara WHERE username = ?";
            if ($stmt_penyelenggara = $conn->prepare($sql_penyelenggara)) {
                $stmt_penyelenggara->bind_param("s", $username);
                $stmt_penyelenggara->execute();
                $stmt_penyelenggara->store_result();
                
                if ($stmt_penyelenggara->num_rows == 1) {
                    $stmt_penyelenggara->bind_result($penyelenggara_id, $db_username, $hashed_password, $nama_lengkap);
                    if ($stmt_penyelenggara->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            $user_found = true;
                            $user_id = $penyelenggara_id;
                            $user_name = $nama_lengkap;
                            $user_role = 'penyelenggara';
                        } else {
                            $error = "Password salah.";
                        }
                    }
                } else {
                    $error = "Username tidak ditemukan.";
                }
                $stmt_penyelenggara->close();
            }
        }
        
        if ($user_found) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['nama_lengkap'] = $user_name;
            $_SESSION['role'] = $user_role;
            
            if ($user_role === 'admin') {
                header("location: dashboard-admin.php");
            } else {
                header("location: dashboard.php");
            }
            exit;
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