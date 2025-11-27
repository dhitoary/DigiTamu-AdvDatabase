<?php
require_once 'config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("location: dashboard-admin.php");
    exit;
}

$penyelenggara_id = $_SESSION['user_id']; 
$nama_lengkap = $_SESSION['nama_lengkap'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - DigiTamu</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-book-open"></i> DigiTamu
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="buat-acara.php"><i class="fas fa-plus-circle"></i> Buat Acara</a></li>
            <li><a href="profil.php"><i class="fas fa-user"></i> Profil Saya</a></li>
            <li><a href="pengaturan.php"><i class="fas fa-cog"></i> Pengaturan</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="logout.php" style="color: #ff6b6b; text-decoration: none;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- KONTEN UTAMA -->
    <div class="main-content">
        
        <!-- Header Atas -->
        <div class="top-header">
            <h3>Dashboard Acara</h3>
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="text-align:right;">
                    <span style="display:block; font-weight:600; font-size:14px;"><?php echo htmlspecialchars($nama_lengkap); ?></span>
                    <span style="display:block; font-size:12px; color:#888;">Penyelenggara</span>
                </div>
                <div style="width:40px; height:40px; background:#ddd; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </div>

        <!-- Card Putih (Tabel) -->
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <h4>Daftar Buku Tamu Anda</h4>
                <a href="buat-acara.php" class="btn"><i class="fas fa-plus"></i> Tambah Data</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Acara</th>
                        <th>Tanggal</th>
                        <th>Link Publik</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT acara_id, nama_acara, tanggal_acara, slug_unik 
                            FROM acara 
                            WHERE penyelenggara_id = ? 
                            ORDER BY tanggal_acara DESC";
                    
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("i", $penyelenggara_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $no = 1;

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $link_publik = "buku-tamu.php?event=" . htmlspecialchars($row['slug_unik']);
                                echo "<tr>";
                                echo "<td>" . $no++ . "</td>";
                                echo "<td><strong>" . htmlspecialchars($row['nama_acara']) . "</strong><br><small style='color:#888'>" . $row['slug_unik'] . "</small></td>";
                                echo "<td>" . htmlspecialchars($row['tanggal_acara']) . "</td>";
                                echo "<td><a href='" . $link_publik . "' target='_blank' class='btn' style='background:#28a745; padding:5px 10px; font-size:12px;'>Buka Form</a></td>";
                                echo "<td>";
                                echo "<a href='lihat-tamu.php?acara_id=" . $row['acara_id'] . "' class='btn' style='background:var(--primary); padding:5px 10px; font-size:12px;'>Detail</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>Belum ada data acara.</td></tr>";
                        }
                        $stmt->close();
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

        <p style="font-size:12px; color:#aaa; margin-top:20px;">&copy; 2025 DigiTamu Application.</p>
    </div>

</body>
</html>