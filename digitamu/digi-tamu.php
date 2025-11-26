<?php
require_once 'config.php';

$nama_acara = "Acara Tidak Ditemukan";
$acara_ditemukan = false;

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
    <title>Buku Tamu: <?php echo htmlspecialchars($nama_acara); ?></title>
    
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .container { background-color: #fff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding: 30px; width: 100%; max-width: 500px; }
        h1 { text-align: center; color: #333; margin-top: 0; }
        h3 { text-align: center; color: #555; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #444; }
        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
        }
        .form-group textarea { resize: vertical; min-height: 100px; }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
        }
        .btn:hover { background-color: #0056b3; }
        .error { text-align: center; color: red; font-size: 18px; }
    </style>
</head>
<body>

    <div class="container">
        <h1><?php echo htmlspecialchars($nama_acara); ?></h1>

        <?php if ($acara_ditemukan): ?>
            <h3>Silakan Isi Buku Tamu</h3>
            
            <form action="simpan-tamu.php" method="POST">
                
                <input type="hidden" name="acara_id" value="<?php echo $acara_id; ?>">
                
                <div class="form-group">
                    <label for="nama_tamu">Nama Anda:</label>
                    <input type="text" id="nama_tamu" name="nama_tamu" required>
                </div>

                <?php if ($template_id == 1): // Template Pernikahan ?>
                
                    <div class="form-group">
                        <label for="alamat">Alamat:</label>
                        <input type="text" id="alamat" name="alamat_atau_instansi">
                    </div>
                    <div class="form-group">
                        <label for="ucapan">Ucapan Selamat:</label>
                        <textarea id="ucapan" name="ucapan_atau_pesan"></textarea>
                    </div>

                <?php elseif ($template_id == 2): // Template Organisasi ?>
                
                    <div class="form-group">
                        <label for="instansi">Asal Instansi/Organisasi:</label>
                        <input type="text" id="instansi" name="alamat_atau_instansi">
                    </div>
                    <div class="form-group">
                        <label for="pesan">Pesan & Kesan:</label>
                        <textarea id="pesan" name="ucapan_atau_pesan"></textarea>
                    </div>

                <?php else: // Template Umum (Default) ?>
                
                    <div class="form-group">
                        <label for="info">Info Tambahan (Alamat/Instansi):</label>
                        <input type="text" id="info" name="alamat_atau_instansi">
                    </div>
                    <div class="form-group">
                        <label for="pesan">Pesan:</label>
                        <textarea id="pesan" name="ucapan_atau_pesan"></textarea>
                    </div>

                <?php endif; ?>
                
                <div class="form-group">
                    <button type="submit" class="btn">Kirim Kehadiran</button>
                </div>
            </form>
            
        <?php else: ?>
            <p class="error">Maaf, acara yang Anda tuju tidak ditemukan.</p>
        <?php endif; ?>

    </div>

</body>
</html>