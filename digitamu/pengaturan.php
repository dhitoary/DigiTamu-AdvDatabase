<?php
require_once 'config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

$penyelenggara_id = $_SESSION['user_id'];
$msg = "";

// Logic Update Profil sederhana
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_baru = $_POST['nama_lengkap'];
    $password_baru = $_POST['password'];

    if (!empty($password_baru)) {
        $hashed = password_hash($password_baru, PASSWORD_BCRYPT);
        $sql = "UPDATE penyelenggara SET nama_lengkap = ?, password = ? WHERE penyelenggara_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nama_baru, $hashed, $penyelenggara_id);
    } else {
        $sql = "UPDATE penyelenggara SET nama_lengkap = ? WHERE penyelenggara_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $nama_baru, $penyelenggara_id);
    }

    if ($stmt->execute()) {
        $_SESSION['nama_lengkap'] = $nama_baru; // Update session
        $msg = "<div class='alert' style='background:#d4edda; color:#155724; border-color:#c3e6cb;'>Profil berhasil diperbarui!</div>";
    } else {
        $msg = "<div class='alert'>Gagal update profil.</div>";
    }
}

// Ambil data saat ini
$sql = "SELECT nama_lengkap FROM penyelenggara WHERE penyelenggara_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $penyelenggara_id);
$stmt->execute();
$stmt->bind_result($nama_lengkap_db);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengaturan - DigiTamu</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-brand"><i class="fas fa-book-open"></i> DigiTamu</div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="buat-acara.php"><i class="fas fa-plus-circle"></i> Buat Acara</a></li>
            <li><a href="profil.php"><i class="fas fa-user"></i> Profil Saya</a></li>
            <li><a href="pengaturan.php" class="active"><i class="fas fa-cog"></i> Pengaturan</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="logout.php" style="color: #ff6b6b; text-decoration: none;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="main-content">
        <div class="top-header">
            <h3>Pengaturan Akun</h3>
            <a href="dashboard.php" class="btn" style="background:#ddd; color:#333;">Kembali</a>
        </div>

        <div class="card" style="max-width: 600px;">
            <?php echo $msg; ?>
            <form action="pengaturan.php" method="POST">
                <div class="form-group">
                    <label>Ganti Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="<?php echo htmlspecialchars($nama_lengkap_db); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Ganti Password (Opsional)</label>
                    <input type="password" name="password" placeholder="Isi jika ingin mengganti password">
                    <small style="color:#888;">Biarkan kosong jika tidak ingin mengganti password.</small>
                </div>

                <button type="submit" class="btn btn-block" style="margin-top:20px;">SIMPAN PERUBAHAN</button>
            </form>
        </div>
    </div>

</body>
</html>