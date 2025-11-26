<?php
require_once 'config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

$error = '';
// ... (Logika PHP Insert sama seperti sebelumnya) ...
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $penyelenggara_id = $_SESSION['user_id']; 
    $template_id = $_POST['template_id'];
    $nama_acara = $conn->real_escape_string($_POST['nama_acara']);
    $tanggal_acara = $_POST['tanggal_acara'];
    $slug_unik = $conn->real_escape_string($_POST['slug_unik']);

    if (empty($template_id) || empty($nama_acara) || empty($tanggal_acara) || empty($slug_unik)) {
        $error = "Semua field wajib diisi.";
    } else {
        $sql_check = "SELECT acara_id FROM acara WHERE slug_unik = ?";
        if ($stmt_check = $conn->prepare($sql_check)) {
            $stmt_check->bind_param("s", $slug_unik);
            $stmt_check->execute();
            $stmt_check->store_result();
            if ($stmt_check->num_rows > 0) {
                $error = "Slug URL sudah dipakai.";
            } else {
                $sql_insert = "INSERT INTO acara (penyelenggara_id, template_id, nama_acara, tanggal_acara, slug_unik) VALUES (?, ?, ?, ?, ?)";
                if ($stmt_insert = $conn->prepare($sql_insert)) {
                    $stmt_insert->bind_param("iisss", $penyelenggara_id, $template_id, $nama_acara, $tanggal_acara, $slug_unik);
                    if ($stmt_insert->execute()) {
                        header("location: dashboard.php");
                        exit;
                    } else { $error = "Error Database."; }
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
    <title>Buat Acara - DigiTamu</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-brand"><i class="fas fa-book-open"></i> DigiTamu</div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="buat-acara.php" class="active"><i class="fas fa-plus-circle"></i> Buat Acara</a></li>
            <li><a href="profil.php"><i class="fas fa-user"></i> Profil Saya</a></li>
            <li><a href="pengaturan.php"><i class="fas fa-cog"></i> Pengaturan</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="logout.php" style="color: #ff6b6b; text-decoration: none;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="main-content">
        <div class="top-header">
            <h3>Tambah Acara Baru</h3>
            <a href="dashboard.php" class="btn" style="background:#ddd; color:#333;">Kembali</a>
        </div>

        <div class="card" style="max-width: 700px;">
            <?php if (!empty($error)): ?>
                <div class="alert"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="buat-acara.php" method="POST">
                <div class="form-group">
                    <label>Nama Acara</label>
                    <input type="text" name="nama_acara" placeholder="Contoh: Pernikahan A & B" required>
                </div>
                
                <div class="form-group">
                    <label>Tanggal Acara</label>
                    <input type="date" name="tanggal_acara" required>
                </div>

                <div class="form-group">
                    <label>Link URL Unik (Slug)</label>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <span style="background:#eee; padding:10px; border:1px solid #ddd; border-radius:5px;">digitamu.com/</span>
                        <input type="text" name="slug_unik" placeholder="nama-acara" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Tipe Buku Tamu</label>
                    <select name="template_id" required>
                        <option value="">-- Pilih Tipe --</option>
                        <?php
                        $result = $conn->query("SELECT * FROM jenis_acara");
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['template_id'] . "'>" . $row['nama_template'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-block" style="margin-top:20px;">SIMPAN ACARA</button>
            </form>
        </div>
    </div>

</body>
</html>