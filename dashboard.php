<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
include 'config.php';

// ambil statistik yap
$stmt_today = $pdo->query("SELECT COUNT(*) as total, SUM(total) as pendapatan FROM penjualan WHERE DATE(tanggal) = CURDATE()");
$today = $stmt_today->fetch();

$stmt_month = $pdo->query("SELECT COUNT(*) as total, SUM(total) as pendapatan FROM penjualan WHERE MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE())");
$month = $stmt_month->fetch();

$stmt_produk = $pdo->query("SELECT COUNT(*) as total FROM produk");
$total_produk = $stmt_produk->fetch()['total'];

$stmt_stok_rendah = $pdo->query("SELECT COUNT(*) as total FROM produk WHERE stok < 10 AND stok > 0");
$stok_rendah = $stmt_stok_rendah->fetch()['total'];

$stmt_pelanggan = $pdo->query("SELECT COUNT(*) as total FROM pelanggan");
$total_pelanggan = $stmt_pelanggan->fetch()['total'];


$initial = strtoupper(substr($_SESSION['username'], 0, 1)); // inisial user untuk avatar
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <script src="theme.js"></script> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
       
        .dashboard-container {
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
 
    <nav>
        <div class="nav-profile">
            <div class="nav-profile-avatar"><?php echo $initial; ?></div>
            <div class="nav-profile-info">
                <div class="nav-profile-name"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
                <div class="nav-profile-role">
                    <span class="role-badge"><?php echo strtoupper($_SESSION['role']); ?></span>
                </div>
            </div>
        </div>
        <a href="penjualan.php"> Penjualan</a>
        <a href="produk.php"> Produk</a>
        <a href="pelanggan.php"> Pelanggan</a>
        <?php if ($_SESSION['role'] == 'admin') { ?>
            <a href="riwayat.php"> Riwayat</a>
        <?php } ?>
        <a href="logout.php" style="color: #ef4444;"> Logout</a>
    </nav>

    
    <button class="theme-toggle" onclick="toggleTheme()" id="themeToggle">
        🌙
    </button>

    <div class="container dashboard-container">
      
        <div class="dashboard-header">
            <div class="dashboard-welcome">
                <div class="dashboard-avatar"><?php echo $initial; ?></div>
                <div class="dashboard-welcome-text">
                    <h1>Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>! 👋</h1>
                    <p>Berikut adalah ringkasan aktivitas sistem kasir Anda</p>
                </div>
            </div>
            <div class="dashboard-quick-info">
                <div class="quick-info-item">
                    📅 <strong>Hari ini:</strong> <?php echo date('d F Y'); ?>
                </div>
                <div class="quick-info-item">
                    ⏰ <strong>Waktu:</strong> <span id="currentTime"></span>
                </div>
                <div class="quick-info-item">
                    👤 <strong>Role:</strong> <?php echo ucfirst($_SESSION['role']); ?>
                </div>
            </div>
        </div>

       
        <div class="dashboard-stats">
            
            <div class="stat-card">
                <div class="stat-card-content">
                    <h3><?php echo $today['total'] ?? 0; ?></h3>
                    <p>Transaksi Hari Ini</p>
                </div>
                <div class="stat-card-footer">
                    Pendapatan: Rp <?php echo number_format($today['pendapatan'] ?? 0, 0, ',', '.'); ?>
                </div>
            </div>

           
            <div class="stat-card">
                <div class="stat-card-content">
                    <h3><?php echo $month['total'] ?? 0; ?></h3>
                    <p>Transaksi Bulan Ini</p>
                </div>
                <div class="stat-card-footer">
                    Pendapatan: Rp <?php echo number_format($month['pendapatan'] ?? 0, 0, ',', '.'); ?>
                </div>
            </div>

           
            <div class="stat-card">
                <div class="stat-card-content">
                    <h3><?php echo $total_produk; ?></h3>
                    <p>Total Produk</p>
                </div>
                <div class="stat-card-footer">
                    <?php if ($stok_rendah > 0): ?>
                        ⚠️ <?php echo $stok_rendah; ?> produk stok rendah
                    <?php else: ?>
                        ✅ Stok semua produk aman
                    <?php endif; ?>
                </div>
            </div>

      
            <div class="stat-card">
                <div class="stat-card-content">
                    <h3><?php echo $total_pelanggan; ?></h3>
                    <p>Total Pelanggan</p>
                </div>
                <div class="stat-card-footer">
                    Database pelanggan terdaftar
                </div>
            </div>
        </div>

    
        <h2>Menu Akses Cepat</h2>
        <div class="dashboard-actions">
            <a href="penjualan.php" class="action-card">
                <h3>Transaksi Penjualan</h3>
                <p>Buat transaksi baru dan kelola penjualan</p>
                <button class="btn">Mulai Transaksi</button>
            </a>

            <a href="produk.php" class="action-card">
                <h3>Kelola Produk</h3>
                <p>Tambah, edit, dan kelola stok produk</p>
                <button class="btn">Kelola Produk</button>
            </a>

            <a href="pelanggan.php" class="action-card">

                <h3>Data Pelanggan</h3>
                <p>Tambah dan kelola data pelanggan</p>
                <button class="btn">Kelola Pelanggan</button>
            </a>

            <?php if ($_SESSION['role'] == 'admin'): ?>
            <a href="riwayat.php" class="action-card">
              
                <h3>Riwayat Penjualan</h3>
                <p>Lihat laporan dan riwayat transaksi</p>
                <button class="btn">Lihat Laporan</button>
            </a>
            <?php endif; ?>
        </div>

    
        <?php if ($stok_rendah > 0 && $_SESSION['role'] == 'admin'): ?>
        <div class="warning">
            <strong>Perhatian!</strong> Ada <?php echo $stok_rendah; ?> produk dengan stok rendah (< 10). 
            <a href="produk.php" style="text-decoration: underline;">Cek sekarang</a>
        </div>
        <?php endif; ?>
    </div>

    <script>
       
        function toggleTheme() {
            const html = document.documentElement;
            const themeToggle = document.getElementById('themeToggle');
            const currentTheme = html.getAttribute('data-theme');
            
            if (currentTheme === 'dark') {
                html.setAttribute('data-theme', 'light');
                themeToggle.textContent = '🌙';
                localStorage.setItem('theme', 'light');
            } else {
                html.setAttribute('data-theme', 'dark');
                themeToggle.textContent = '☀️';
                localStorage.setItem('theme', 'dark');
            }
        }

     
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            const themeToggle = document.getElementById('themeToggle');
            
            document.documentElement.setAttribute('data-theme', savedTheme);
            themeToggle.textContent = savedTheme === 'dark' ? '☀️' : '🌙';
            
        
            updateTime();
            setInterval(updateTime, 1000);
        });

        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            const timeElement = document.getElementById('currentTime');
            if (timeElement) {
                timeElement.textContent = timeString;
            }
        }
    </script>
</body>
</html>