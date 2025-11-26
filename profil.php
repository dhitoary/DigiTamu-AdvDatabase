<?php
require_once 'config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

$penyelenggara_id = $_SESSION['user_id'];
// Ambil data terbaru dari database
$sql = "SELECT username, nama_lengkap FROM penyelenggara WHERE penyelenggara_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $penyelenggara_id);
$stmt->execute();
$stmt->bind_result($username, $nama_lengkap);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya - DigiTamu</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-brand"><i class="fas fa-book-open"></i> DigiTamu</div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="buat-acara.php"><i class="fas fa-plus-circle"></i> Buat Acara</a></li>
            <li><a href="profil.php" class="active"><i class="fas fa-user"></i> Profil Saya</a></li>
            <li><a href="pengaturan.php"><i class="fas fa-cog"></i> Pengaturan</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="logout.php" style="color: #ff6b6b; text-decoration: none;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="top-header">
            <h3>Profil Saya</h3>
            <a href="dashboard.php" class="btn" style="background:#ddd; color:#333;">Kembali</a>
        </div>

        <div class="card" style="max-width: 600px;">
            <div style="text-align:center; margin-bottom:30px;">
                <div style="width:100px; height:100px; background:#ddd; border-radius:50%; margin:0 auto 15px auto; display:flex; align-items:center; justify-content:center; font-size:40px; color:#888;">
                    <i class="fas fa-user"></i>
                </div>
                <h2 style="margin:0;"><?php echo htmlspecialchars($nama_lengkap); ?></h2>
                <p style="color: var(--primary);">Penyelenggara</p>
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" value="<?php echo htmlspecialchars($username); ?>" disabled style="background:#eee; cursor:not-allowed;">
            </div>
            
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" value="<?php echo htmlspecialchars($nama_lengkap); ?>" disabled style="background:#eee; cursor:not-allowed;">
            </div>

            <a href="pengaturan.php" class="btn btn-block" style="margin-top:20px;">EDIT PROFIL</a>
        </div>
    </div>

</body>
</html>