<?php
require_once 'config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("location: dashboard.php");
    exit;
}

$nama_lengkap = $_SESSION['nama_lengkap'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - DigiTamu</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-shield-alt"></i> DigiTamu Admin
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard-admin.php" class="active"><i class="fas fa-chart-line"></i> Monitoring</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="logout.php" style="color: #ff6b6b; text-decoration: none;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- KONTEN UTAMA -->
    <div class="main-content">
        
        <!-- Header Atas -->
        <div class="top-header">
            <h3>Dashboard Super Admin</h3>
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="text-align:right;">
                    <span style="display:block; font-weight:600; font-size:14px;"><?php echo htmlspecialchars($nama_lengkap); ?></span>
                    <span style="display:block; font-size:12px; color:#888;">Administrator</span>
                </div>
                <div style="width:40px; height:40px; background:#ff6b6b; border-radius:50%; display:flex; align-items:center; justify-content:center; color:white;">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <?php
            $total_penyelenggara = 0;
            $total_acara = 0;
            $total_tamu = 0;
            
            $result_p = $conn->query("SELECT COUNT(*) as total FROM penyelenggara");
            if ($result_p) {
                $row_p = $result_p->fetch_assoc();
                $total_penyelenggara = $row_p['total'];
            }
            
            $result_a = $conn->query("SELECT COUNT(*) as total FROM acara");
            if ($result_a) {
                $row_a = $result_a->fetch_assoc();
                $total_acara = $row_a['total'];
            }
            
            $result_t = $conn->query("SELECT COUNT(*) as total FROM tamu");
            if ($result_t) {
                $row_t = $result_t->fetch_assoc();
                $total_tamu = $row_t['total'];
            }
            ?>
            
            <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="margin: 0; opacity: 0.9; font-size: 14px;">Total Penyelenggara</p>
                        <h2 style="margin: 10px 0; font-size: 36px;"><?php echo $total_penyelenggara; ?></h2>
                    </div>
                    <i class="fas fa-users" style="font-size: 48px; opacity: 0.3;"></i>
                </div>
            </div>

            <div class="card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="margin: 0; opacity: 0.9; font-size: 14px;">Total Acara</p>
                        <h2 style="margin: 10px 0; font-size: 36px;"><?php echo $total_acara; ?></h2>
                    </div>
                    <i class="fas fa-calendar-alt" style="font-size: 48px; opacity: 0.3;"></i>
                </div>
            </div>

            <div class="card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="margin: 0; opacity: 0.9; font-size: 14px;">Total Tamu</p>
                        <h2 style="margin: 10px 0; font-size: 36px;"><?php echo $total_tamu; ?></h2>
                    </div>
                    <i class="fas fa-address-book" style="font-size: 48px; opacity: 0.3;"></i>
                </div>
            </div>
        </div>

        <!-- Tabel Monitoring -->
        <div class="card">
            <h4 style="margin-bottom: 20px;"><i class="fas fa-list"></i> Monitoring Penyelenggara & Acara</h4>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Penyelenggara</th>
                        <th>Username</th>
                        <th>Jumlah Acara</th>
                        <th>Daftar Acara</th>
                        <th>Tanggal Acara</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT 
                                p.penyelenggara_id,
                                p.nama_lengkap,
                                p.username,
                                COUNT(DISTINCT a.acara_id) as jumlah_acara,
                                GROUP_CONCAT(DISTINCT a.nama_acara ORDER BY a.tanggal_acara DESC SEPARATOR ', ') as daftar_acara,
                                GROUP_CONCAT(DISTINCT DATE_FORMAT(a.tanggal_acara, '%d-%m-%Y') ORDER BY a.tanggal_acara DESC SEPARATOR ', ') as tanggal_acara
                            FROM penyelenggara p
                            LEFT JOIN acara a ON p.penyelenggara_id = a.penyelenggara_id
                            GROUP BY p.penyelenggara_id
                            ORDER BY p.penyelenggara_id DESC";
                    
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['penyelenggara_id']) . "</td>";
                            echo "<td><strong>" . htmlspecialchars($row['nama_lengkap']) . "</strong></td>";
                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                            echo "<td><span style='background:#28a745; color:white; padding:3px 10px; border-radius:12px; font-size:12px;'>" . htmlspecialchars($row['jumlah_acara']) . "</span></td>";
                            
                            $nama_acara = $row['daftar_acara'] ? $row['daftar_acara'] : '<span style="color:#888;">Belum ada acara</span>';
                            $tanggal = $row['tanggal_acara'] ? $row['tanggal_acara'] : '-';
                            
                            echo "<td style='max-width:300px;'>" . $nama_acara . "</td>";
                            echo "<td style='font-size:12px; color:#666;'>" . $tanggal . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>Belum ada penyelenggara terdaftar.</td></tr>";
                    }
                    
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

        <p style="font-size:12px; color:#aaa; margin-top:20px;">&copy; 2025 DigiTamu Application - Admin Panel</p>
    </div>

</body>
</html>
