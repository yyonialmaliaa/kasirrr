<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: dashboard.php');
    exit;
}
include 'config.php';


$stmt = $pdo->query("
    SELECT p.id, pel.nama as pelanggan, u.username as petugas, p.tanggal, p.total 
    FROM penjualan p 
    LEFT JOIN pelanggan pel ON p.id_pelanggan = pel.id 
    LEFT JOIN users u ON p.id_user = u.id 
    ORDER BY p.created_at DESC
");
$riwayat = $stmt->fetchAll(); // ambil riwayat penjualan, pelanggan dan user juga trmsk y


$detail = [];  // klik detail, muncul
if (isset($_GET['detail'])) {
    $id = (int)$_GET['detail'];
    $stmt_detail = $pdo->prepare("
        SELECT dp.*, pr.nama as produk_nama 
        FROM detail_penjualan dp 
        JOIN produk pr ON dp.id_produk = pr.id 
        WHERE dp.id_penjualan = ?
    ");
    $stmt_detail->execute([$id]);
    $detail = $stmt_detail->fetchAll();
    
    $stmt_penjualan = $pdo->prepare("SELECT * FROM penjualan p LEFT JOIN pelanggan pel ON p.id_pelanggan = pel.id WHERE p.id = ?");
    $stmt_penjualan->execute([$id]);
    $penjualan_info = $stmt_penjualan->fetch();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Penjualan</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

</head>
<body>
    <script src="theme.js"></script> 
    <h2>Riwayat Penjualan (Admin)</h2>
    
    <table>
        <tr><th>ID</th><th>Pelanggan</th><th>Petugas</th><th>Tanggal</th><th>Total</th><th>Aksi</th></tr>
        <?php foreach ($riwayat as $r): ?>
        <tr>
            <td><?php echo $r['id']; ?></td>
            <td><?php echo $r['pelanggan'] ?? 'Pelanggan Umum'; ?></td>
            <td><?php echo $r['petugas']; ?></td>
            <td><?php echo $r['tanggal']; ?></td>
            <td>Rp <?php echo number_format($r['total'], 2); ?></td>
            <td><a href="?detail=<?php echo $r['id']; ?>">Detail</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <?php if (!empty($detail)): ?>
        <h3>Detail Penjualan ID: <?php echo $_GET['detail']; ?></h3>
        <p>Pelanggan: <?php echo $penjualan_info['nama'] ?? 'Pelanggan Umum'; ?> | Tanggal: <?php echo $penjualan_info['tanggal']; ?> | Total: Rp <?php echo number_format($penjualan_info['total'], 2); ?></p>
        <table>
            <tr><th>Produk</th><th>Qty</th><th>Subtotal</th></tr>
            <?php foreach ($detail as $d): ?>
            <tr>
                <td><?php echo $d['produk_nama']; ?></td>
                <td><?php echo $d['qty']; ?></td>
                <td>Rp <?php echo number_format($d['subtotal'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    
    <a href="dashboard.php">← Kembali ke Dashboard</a>
</body>
</html>