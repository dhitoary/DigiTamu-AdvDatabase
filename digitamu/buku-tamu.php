<?php
require_once 'config.php'; 

$nama_acara = "Acara Tidak Ditemukan";
$acara_ditemukan = false;
$acara_id = 0;
$template_id = 0;
$slug_unik = ""; // Siapkan variabel slug

if (isset($_GET['event']) && !empty($_GET['event'])) {
    $slug_unik = $_GET['event'];
    $sql = "SELECT acara.acara_id, acara.nama_acara, jenis_acara.template_id 
            FROM acara
            JOIN jenis_acara ON acara.template_id = jenis_acara.template_id
            WHERE acara.slug_unik = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $slug_unik);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $acara = $result->fetch_assoc();
            $acara_id = $acara['acara_id'];
            $nama_acara = $acara['nama_acara'];
            $template_id = $acara['template_id']; 
            $acara_ditemukan = true;
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($nama_acara); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-layout"> <!-- Layout Publik: Background Gelap -->

    <div class="form-container-center">
        <?php if ($acara_ditemukan): ?>
            <div class="text-center" style="margin-bottom:30px;">
                <span style="font-size:12px; color:#888; text-transform:uppercase; letter-spacing:1px;">Selamat Datang di</span>
                <h2 style="color: var(--primary); margin-top:5px;"><?php echo htmlspecialchars($nama_acara); ?></h2>
                <p>Mohon isi daftar hadir berikut.</p>
            </div>
            
            <form action="simpan-tamu.php" method="POST">
                <input type="hidden" name="acara_id" value="<?php echo $acara_id; ?>">
                
                <!-- PERBAIKAN: Kirim slug event agar tombol kembali bisa mereset form -->
                <input type="hidden" name="event_slug" value="<?php echo htmlspecialchars($slug_unik); ?>">
                
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_tamu" placeholder="Nama Anda" required>
                </div>

                <?php if ($template_id == 1): ?>
                    <div class="form-group"><label>Alamat</label><input type="text" name="alamat_atau_instansi"></div>
                    <div class="form-group"><label>Ucapan Selamat</label><textarea name="ucapan_atau_pesan" rows="3"></textarea></div>
                <?php elseif ($template_id == 2): ?>
                    <div class="form-group"><label>Asal Instansi</label><input type="text" name="alamat_atau_instansi"></div>
                    <div class="form-group"><label>Pesan & Kesan</label><textarea name="ucapan_atau_pesan" rows="3"></textarea></div>
                <?php else: ?>
                    <div class="form-group"><label>Info Tambahan</label><input type="text" name="alamat_atau_instansi"></div>
                    <div class="form-group"><label>Pesan</label><textarea name="ucapan_atau_pesan" rows="3"></textarea></div>
                <?php endif; ?>
                
                <button type="submit" class="btn btn-block">KIRIM KEHADIRAN</button>
            </form>
        <?php else: ?>
            <div class="text-center">
                <h3 style="color:red;">404</h3>
                <p>Acara tidak ditemukan.</p>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>