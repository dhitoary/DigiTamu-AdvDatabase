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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiTamu - Solusi Buku Tamu Digital</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body.landing-page {
            background-color: #fff;
            color: #444;
            display: block;
            overflow-y: auto;
            padding: 0;
            line-height: 1.6;
        }
        
        .navbar {
            background: rgba(29, 45, 70, 0.95);
            padding: 15px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .navbar .logo { font-size: 24px; font-weight: bold; color: white; display: flex; align-items: center; gap: 10px; }
        .navbar .nav-links a { color: #ccc; text-decoration: none; margin-left: 25px; font-weight: 500; font-size: 14px; transition: 0.3s; }
        .navbar .nav-links a:hover { color: white; }
        .btn-nav { background: var(--primary); padding: 8px 20px; border-radius: 20px; color: white !important; }

        .hero {
            background: linear-gradient(135deg, var(--sidebar-bg) 0%, #2a4066 100%);
            color: white;
            padding: 100px 5% 150px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .hero::after {
            content: ''; position: absolute; bottom: -50px; left: 0; right: 0; height: 100px;
            background: white; transform: skewY(-2deg);
        }
        .hero h1 { font-size: 3.5rem; margin-bottom: 20px; font-weight: 800; line-height: 1.2; }
        .hero p { max-width: 700px; margin: 0 auto 40px; font-size: 1.2rem; color: #d0d6e6; }
        .btn-cta {
            padding: 15px 40px; border-radius: 50px; font-weight: bold; font-size: 16px;
            text-decoration: none; display: inline-block; transition: 0.3s; box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .btn-cta-primary { background: #4e73df; color: white; margin-right: 15px; }
        .btn-cta-primary:hover { background: #375aeb; transform: translateY(-3px); }
        .btn-cta-outline { background: transparent; border: 2px solid rgba(255,255,255,0.3); color: white; }
        .btn-cta-outline:hover { background: white; color: var(--sidebar-bg); }

        section { padding: 80px 5%; }
        .section-title { text-align: center; margin-bottom: 60px; }
        .section-title h2 { font-size: 2.5rem; color: #333; margin-bottom: 10px; }
        .section-title p { color: #666; max-width: 600px; margin: 0 auto; }

        .steps-grid { display: flex; justify-content: center; gap: 30px; flex-wrap: wrap; }
        .step-card {
            flex: 1; min-width: 250px; max-width: 300px; text-align: center;
            position: relative;
        }
        .step-icon {
            width: 80px; height: 80px; background: #eef2f7; color: var(--primary);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 30px; margin: 0 auto 20px; position: relative; z-index: 2;
        }
        .step-card h3 { margin-bottom: 10px; color: #333; }
        .step-card p { font-size: 14px; color: #666; }

        .preview-section { background: #f8f9fa; }
        .mockup-container {
            display: flex; justify-content: center; gap: 50px; flex-wrap: wrap; margin-top: 40px;
        }
        .phone-mockup {
            width: 300px; height: 550px; background: white; border: 12px solid #333;
            border-radius: 30px; position: relative; overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.2);
            display: flex; flex-direction: column;
        }
        .phone-notch {
            width: 120px; height: 20px; background: #333;
            position: absolute; top: 0; left: 50%; transform: translateX(-50%);
            border-bottom-left-radius: 15px; border-bottom-right-radius: 15px; z-index: 10;
        }
        .phone-screen { padding: 40px 20px 20px; flex: 1; background: #fff; overflow: hidden; }
        .mock-header { text-align: center; margin-bottom: 20px; }
        .mock-title { font-weight: bold; color: var(--primary); font-size: 18px; }
        .mock-subtitle { font-size: 12px; color: #888; }
        .mock-input {
            height: 35px; background: #f0f2f5; margin-bottom: 10px; border-radius: 5px;
            width: 100%; border: 1px solid #ddd;
        }
        .mock-btn {
            height: 40px; background: var(--primary); border-radius: 5px; width: 100%; margin-top: 10px;
        }
        .badge {
            background: var(--accent); color: white; padding: 5px 15px; border-radius: 20px;
            font-size: 12px; display: inline-block; margin-bottom: 15px;
        }

        .features-grid { 
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            gap: 30px; 
        }

        @media (max-width: 992px) {
            .features-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 600px) {
            .features-grid { grid-template-columns: 1fr; } 
        }

        .feature-item { display: flex; gap: 20px; align-items: flex-start; }
        .feature-icon { 
            min-width: 50px; height: 50px; background: rgba(56, 69, 137, 0.1); 
            color: var(--primary); border-radius: 10px; display: flex; 
            align-items: center; justify-content: center; font-size: 20px;
        }
        .feature-text h4 { margin-bottom: 5px; color: #333; }
        .feature-text p { font-size: 14px; color: #666; }

        /* FOOTER */
        footer { background: #1a1c23; color: #7a8099; padding: 60px 5% 30px; font-size: 14px; }
        .footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; margin-bottom: 40px; }
        .footer-col h4 { color: white; margin-bottom: 20px; font-size: 18px; }
        .footer-col ul { list-style: none; padding: 0; }
        .footer-col ul li { margin-bottom: 10px; }
        .footer-col ul li a { color: #7a8099; text-decoration: none; transition: 0.2s; }
        .footer-col ul li a:hover { color: white; }
        .copyright { text-align: center; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 30px; }
    </style>
</head>
<body class="landing-page">

    <nav class="navbar">
        <div class="logo">
            <i class="fas fa-book-open"></i> DigiTamu
        </div>
        <div class="nav-links">
            <a href="#cara-kerja">Cara Kerja</a>
            <a href="#contoh">Contoh</a>
            <a href="#fitur">Fitur</a>
            <a href="login.php">Masuk</a>
            <a href="register.php" class="btn-nav">Daftar Sekarang</a>
        </div>
    </nav>

    <header class="hero">
        <h1>Buku Tamu Modern<br>untuk Acara Profesional</h1>
        <p>Solusi pencatatan kehadiran tamu secara real-time. Cocok untuk Pernikahan, Seminar, Wisuda, dan Rapat Instansi. Tanpa antre, tanpa kertas.</p>
        <div>
            <a href="register.php" class="btn-cta btn-cta-primary">Mulai Gratis</a>
            <a href="#contoh" class="btn-cta btn-cta-outline">Lihat Contoh</a>
        </div>
    </header>

    <section id="cara-kerja">
        <div class="section-title">
            <h2>Bagaimana Cara Kerjanya?</h2>
            <p>Hanya butuh 3 langkah mudah untuk membuat acara Anda lebih modern.</p>
        </div>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-icon"><i class="fas fa-pencil-alt"></i></div>
                <h3>1. Buat Acara</h3>
                <p>Daftar akun dan buat acara baru. Tentukan nama acara, tanggal, dan pilih template yang sesuai.</p>
            </div>
            <div class="step-card">
                <div class="step-icon"><i class="fas fa-share-alt"></i></div>
                <h3>2. Bagikan Link</h3>
                <p>Anda akan mendapatkan Link Unik (URL). Bagikan via WhatsApp atau QR Code di meja registrasi.</p>
            </div>
            <div class="step-card">
                <div class="step-icon"><i class="fas fa-chart-line"></i></div>
                <h3>3. Pantau Real-time</h3>
                <p>Tamu mengisi data di HP mereka. Data langsung masuk ke dashboard Anda dan siap dicetak.</p>
            </div>
        </div>
    </section>

    <section id="contoh" class="preview-section">
        <div class="section-title">
            <h2>Pilih Template Sesuai Kebutuhan</h2>
            <p>Kami menyediakan formulir khusus yang menyesuaikan jenis acara Anda.</p>
        </div>
        <div class="mockup-container">
            <div class="text-center">
                <span class="badge">Template Pernikahan</span>
                <div class="phone-mockup">
                    <div class="phone-notch"></div>
                    <div class="phone-screen">
                        <div class="mock-header">
                            <div class="mock-title">The Wedding of<br>Romeo & Juliet</div>
                            <div class="mock-subtitle">Mohon isi buku tamu</div>
                        </div>
                        <div style="text-align:left; font-size:12px; color:#666; margin-bottom:5px;">Nama Lengkap</div>
                        <div class="mock-input"></div>
                        <div style="text-align:left; font-size:12px; color:#666; margin-bottom:5px;">Alamat</div>
                        <div class="mock-input"></div>
                        <div style="text-align:left; font-size:12px; color:#666; margin-bottom:5px;">Ucapan Doa</div>
                        <div class="mock-input" style="height:80px;"></div>
                        <div class="mock-btn"></div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <span class="badge" style="background: #28a745;">Template Seminar / Dinas</span>
                <div class="phone-mockup">
                    <div class="phone-notch"></div>
                    <div class="phone-screen">
                        <div class="mock-header">
                            <div class="mock-title">Seminar Nasional<br>Teknologi 2025</div>
                            <div class="mock-subtitle">Registrasi Peserta</div>
                        </div>
                        <div style="text-align:left; font-size:12px; color:#666; margin-bottom:5px;">Nama Lengkap</div>
                        <div class="mock-input"></div>
                        <div style="text-align:left; font-size:12px; color:#666; margin-bottom:5px;">Asal Instansi / Kampus</div>
                        <div class="mock-input"></div>
                        <div style="text-align:left; font-size:12px; color:#666; margin-bottom:5px;">Kesan / Pesan</div>
                        <div class="mock-input" style="height:80px;"></div>
                        <div class="mock-btn" style="background:#28a745;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur">
        <div class="section-title">
            <h2>Fitur Lengkap</h2>
            <p>Dirancang untuk memudahkan penyelenggara acara.</p>
        </div>
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-lock"></i></div>
                <div class="feature-text">
                    <h4>Keamanan Data</h4>
                    <p>Password akun dienkripsi dengan standar keamanan tinggi (BCRYPT).</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-mobile-alt"></i></div>
                <div class="feature-text">
                    <h4>Mobile Friendly</h4>
                    <p>Tampilan responsif di semua perangkat tamu (Android/iPhone/Tablet).</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-print"></i></div>
                <div class="feature-text">
                    <h4>Cetak Laporan</h4>
                    <p>Fitur one-click print untuk mencetak rekap kehadiran tamu.</p>
                </div>
            </div>

            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-link"></i></div>
                <div class="feature-text">
                    <h4>Custom URL (Slug)</h4>
                    <p>Buat link acara yang mudah diingat, misal: <i>digitamu.com/?event=rapat-tahunan</i></p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-database"></i></div>
                <div class="feature-text">
                    <h4>Database Terpusat</h4>
                    <p>Tidak ada lagi buku tamu hilang. Semua tersimpan aman di server.</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-user-friends"></i></div>
                <div class="feature-text">
                    <h4>Unlimited Tamu</h4>
                    <p>Tidak ada batasan jumlah tamu yang bisa mengisi daftar hadir.</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-grid">
            <div class="footer-col">
                <div style="font-size: 24px; font-weight: bold; color: white; margin-bottom: 20px;">
                    <i class="fas fa-book-open"></i> DigiTamu
                </div>
                <p>Platform buku tamu digital #1 untuk mendigitalkan acara Anda. Hemat kertas, rapi, dan canggih.</p>
            </div>
            <div class="footer-col">
                <h4>Tautan Cepat</h4>
                <ul>
                    <li><a href="login.php">Login Admin</a></li>
                    <li><a href="register.php">Daftar Baru</a></li>
                    <li><a href="#fitur">Fitur</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Hubungi Kami</h4>
                <ul>
                    <li><a href="#"><i class="fas fa-envelope"></i> support@digitamu.com</a></li>
                    <li><a href="#"><i class="fab fa-whatsapp"></i> +62 812 3456 7890</a></li>
                    <li><a href="#"><i class="fas fa-map-marker-alt"></i> Jakarta, Indonesia</a></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            &copy; 2025 DigiTamu Application. All rights reserved.
        </div>
    </footer>

</body>
</html>
