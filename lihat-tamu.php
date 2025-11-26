<?php
require_once 'config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

$penyelenggara_id = $_SESSION['user_id']; 
$list_tamu = [];
$nama_acara = "";

if (isset($_GET['acara_id'])) {
    $acara_id = intval($_GET['acara_id']);
    // Cek kepemilikan acara
    $sql_check = "SELECT nama_acara FROM acara WHERE acara_id = ? AND penyelenggara_id = ?";
    if ($stmt = $conn->prepare($sql_check)) {
        $stmt->bind_param("ii", $acara_id, $penyelenggara_id);
        $stmt->execute();
        $stmt->bind_result($nama_acara);
        if ($stmt->fetch()) {
            $stmt->close();
            // Ambil data tamu
            $sql_tamu = "SELECT * FROM tamu WHERE acara_id = ? ORDER BY waktu_kehadiran DESC";
            $stmt_tamu = $conn->prepare($sql_tamu);
            $stmt_tamu->bind_param("i", $acara_id);
            $stmt_tamu->execute();
            $result = $stmt_tamu->get_result();
            while ($row = $result->fetch_assoc()) { $list_tamu[] = $row; }
        } else {
            die("Akses Ditolak.");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Tamu - DigiTamu</title>
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
            <!-- Menu Profil & Pengaturan ditambahkan agar konsisten -->
            <li><a href="profil.php"><i class="fas fa-user"></i> Profil Saya</a></li>
            <li><a href="pengaturan.php"><i class="fas fa-cog"></i> Pengaturan</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="logout.php" style="color: #ff6b6b; text-decoration: none;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="top-header">
            <h3>Data Tamu</h3>
            <a href="dashboard.php" class="btn" style="background:#ddd; color:#333;">Kembali</a>
        </div>

        <div class="card">
            <div style="margin-bottom: 20px; border-bottom:1px solid #eee; padding-bottom:10px;">
                <h2 style="margin:0; font-size:18px;">Acara: <?php echo htmlspecialchars($nama_acara); ?></h2>
                <p style="margin:0; font-size:13px;">Rekapitulasi tamu yang hadir.</p>
            </div>

            <button onclick="window.print()" class="btn" style="margin-bottom:15px; background:#17a2b8;"><i class="fas fa-print"></i> Cetak Laporan</button>

            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Nama Tamu</th>
                        <th>Info / Instansi</th>
                        <th>Pesan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($list_tamu)): ?>
                        <tr><td colspan="4" class="text-center">Belum ada tamu yang mengisi.</td></tr>
                    <?php else: ?>
                        <?php foreach ($list_tamu as $tamu): ?>
                            <tr>
                                <td><small style="color:#888;"><?php echo $tamu['waktu_kehadiran']; ?></small></td>
                                <td><strong><?php echo htmlspecialchars($tamu['nama_tamu']); ?></strong></td>
                                <td><?php echo htmlspecialchars($tamu['alamat_atau_instansi']); ?></td>
                                <td><?php echo htmlspecialchars($tamu['ucapan_atau_pesan']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>