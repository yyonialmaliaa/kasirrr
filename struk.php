<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['struk'])) {
    header('Location: dashboard.php');
    exit;
}

$struk = $_SESSION['struk'];

unset($_SESSION['struk']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Penjualan</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
</head>
<body>
    <script src="theme.js"></script> 
    <div class="struk">
        <div class="header">STRUK PENJUALAN</div>
        <p>ID Transaksi: <?php echo $struk['id_penjualan']; ?></p>
        <p>Tanggal: <?php echo $struk['tanggal']; ?></p>
        <p>Petugas: <?php echo $struk['user']; ?></p>
        <p>Pelanggan: <?php echo $struk['pelanggan']; ?></p>
        <?php if ($struk['alamat']) echo "<p>Alamat: " . $struk['alamat'] . "</p>"; ?>
        <?php if ($struk['telepon']) echo "<p>Telepon: " . $struk['telepon'] . "</p>"; ?>
        
        <h4>Daftar Item:</h4>
        <?php foreach ($struk['items'] as $item): ?>
            <div class="item">
                <span><?php echo $item['nama']; ?> (x<?php echo $item['qty']; ?>)</span>
                <span>Rp <?php echo number_format($item['subtotal'], 2); ?></span>
            </div>
        <?php endforeach; ?>
        
        <div class="total">
            TOTAL: Rp <?php echo number_format($struk['total'], 2); ?>
        </div>
        <p>Terima kasih atas pembeliannya!</p>
    </div>
    
    <div class="no-print">
        <button onclick="window.print()">Print Struk</button>
        <a href="dashboard.php">Kembali ke Dashboard</a>
    </div>
</body>
</html>