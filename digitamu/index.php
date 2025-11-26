<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>DigiTamu - Welcome</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-layout" style="flex-direction: column; color: white; text-align: center;">

    <h1 style="color: white; font-size: 3rem; margin-bottom: 10px;">DigiTamu.</h1>
    <p style="color: #b0b8d1; max-width: 500px; margin-bottom: 40px;">
        Solusi buku tamu digital untuk acara Pernikahan, Seminar, dan Event profesional Anda.
    </p>

    <div style="display: flex; gap: 20px;">
        <a href="login.php" class="btn" style="background: white; color: var(--primary); font-weight: bold; padding: 15px 40px;">LOGIN ADMIN</a>
        <a href="register.php" class="btn" style="border: 1px solid white; background: transparent; padding: 15px 40px;">DAFTAR</a>
    </div>

</body>
</html>