<?php
require_once 'config.php'; 
$status = 'error';
$pesan = '';
$event_slug = ''; // Variabel untuk menampung slug

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $acara_id = $_POST['acara_id'] ?? 0;
    $nama_tamu = $_POST['nama_tamu'] ?? '';
    $alamat = $_POST['alamat_atau_instansi'] ?? '';
    $ucapan = $_POST['ucapan_atau_pesan'] ?? '';
    
    // Ambil slug yang dikirim dari form sebelumnya
    $event_slug = $_POST['event_slug'] ?? '';

    if (!empty($acara_id) && !empty($nama_tamu)) {
        $sql = "INSERT INTO tamu (acara_id, nama_tamu, alamat_atau_instansi, ucapan_atau_pesan) VALUES (?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("isss", $acara_id, $nama_tamu, $alamat, $ucapan);
            if ($stmt->execute()) {
                $status = 'sukses';
                $pesan = "Terima kasih, <b>" . htmlspecialchars($nama_tamu) . "</b>.<br>Kehadiran Anda telah berhasil dicatat.";
            } else { 
                $pesan = "Terjadi kesalahan database."; 
            }
            $stmt->close();
        }
    } else { 
        $pesan = "Data tidak lengkap. Mohon isi nama Anda."; 
    }
    $conn->close();
} else {
    $pesan = "Akses tidak valid.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Status Kehadiran</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="auth-layout">

    <div class="form-container-center text-center">
        <?php if ($status == 'sukses'): ?>
            <div style="font-size: 60px; color: #28a745; margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h2 style="color: var(--primary); margin-bottom: 10px;">Berhasil!</h2>
            <p style="font-size: 16px; color: #555; line-height: 1.6; margin-bottom: 30px;">
                <?php echo $pesan; ?>
            </p>
            
            <!-- PERBAIKAN: Link langsung ke halaman form (bukan back history) agar form kosong kembali -->
            <a href="buku-tamu.php?event=<?php echo htmlspecialchars($event_slug); ?>" class="btn btn-block">
                <i class="fas fa-arrow-left"></i> KEMBALI KE FORM
            </a>

        <?php else: ?>
            <div style="font-size: 60px; color: #dc3545; margin-bottom: 20px;">
                <i class="fas fa-times-circle"></i>
            </div>

            <h2 style="color: #dc3545; margin-bottom: 10px;">Gagal</h2>
            <div class="alert">
                <?php echo $pesan; ?>
            </div>
            
            <a href="javascript:history.back()" class="btn btn-block" style="background-color: #6c757d;">
                COBA LAGI
            </a>
        <?php endif; ?>
    </div>

</body>
</html>