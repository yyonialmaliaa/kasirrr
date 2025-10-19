<?php
session_start();
if (!isset($_SESSION['user_id'])) header('Location: login.php');
include 'config.php';

$message = ''; $error = '';
if ($_POST) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'tambah':
                $nama = $_POST['nama'];
                $harga = $_POST['harga'];
                $stok = $_POST['stok'];
                $stmt = $pdo->prepare("INSERT INTO produk (nama, harga, stok) VALUES (?, ?, ?)");
                if ($stmt->execute([$nama, $harga, $stok])) {
                    $message = 'Produk baru ditambahkan!';
                } else {
                    $error = 'Gagal menambahkan produk.';
                }
                break;
                
            case 'restock':
                $id = $_POST['id_produk'];
                $tambah_stok = $_POST['tambah_stok'];
                $stmt = $pdo->prepare("UPDATE produk SET stok = stok + ? WHERE id = ?");
                if ($stmt->execute([$tambah_stok, $id])) {
                    $message = 'Stok berhasil ditambahkan (restock)!';
                } else {
                    $error = 'Gagal restock.';
                }
                break;
                
            case 'edit':
                $id = $_POST['id'];
                $nama = $_POST['nama'];
                $harga = $_POST['harga'];
                $stok = $_POST['stok'];
                $stmt = $pdo->prepare("UPDATE produk SET nama = ?, harga = ?, stok = ? WHERE id = ?");
                if ($stmt->execute([$nama, $harga, $stok, $id])) {
                    $message = 'Produk berhasil diedit!';
                } else {
                    $error = 'Gagal edit produk.';
                }
                break;
                
            case 'hapus':
                $id = $_POST['id'];
                $stmt = $pdo->prepare("DELETE FROM produk WHERE id = ?");
                if ($stmt->execute([$id])) {
                    $message = 'Produk dihapus!';
                } else {
                    $error = 'Gagal hapus produk (mungkin ada penjualan terkait).';
                }
                break;
        }
    }
}


$produk_list = $pdo->query("SELECT * FROM produk ORDER BY id DESC")->fetchAll();


$edit_produk = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM produk WHERE id = ?");  // ambil data produk ya untuk edit 
    $stmt->execute([$id]);
    $edit_produk = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <script src="theme.js"></script> 
    <meta charset="UTF-8">
    <title>Manajemen Produk</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: Arial; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        form { margin: 20px 0; padding: 15px; border: 1px solid #ddd; }
        input { padding: 5px; margin: 5px; width: 200px; }
        button { padding: 8px 12px; background: #007bff; color: white; border: none; cursor: pointer; }
        .error { color: red; } .success { color: green; }
    </style>
</head>
<body>
    <h2>Manajemen Produk & Stok Barang</h2>
    <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    <?php if ($message) echo "<p class='success'>$message</p>"; ?>
    
    
    <h3>Tambah Produk Baru </h3>
    <form method="POST">
        <input type="hidden" name="action" value="tambah">
        <input type="text" name="nama" placeholder="Nama Produk" required>
        <input type="number" name="harga" placeholder="Harga" step="0.01" required>
        <input type="number" name="stok" placeholder="Stok Awal" min="0" required>
        <button type="submit">Tambah Produk</button>
    </form>
    
   
    <h3>Restock / Tambah Stok Barang </h3>
    <form method="POST">
        <input type="hidden" name="action" value="restock">
        <select name="id_produk" required>
            <option value="">Pilih Produk</option>
            <?php foreach ($produk_list as $p): ?>
                <option value="<?php echo $p['id']; ?>"> <?php echo $p['nama']; ?> (Stok Saat Ini: <?php echo $p['stok']; ?>)</option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="tambah_stok" placeholder="Jumlah Tambah Stok" min="1" required>
        <button type="submit">Restock</button>
    </form>
  
    <?php if ($edit_produk): ?>
        <h3>Edit Produk ID: <?php echo $edit_produk['id']; ?></h3>
        <form method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" value="<?php echo $edit_produk['id']; ?>">
            <input type="text" name="nama" value="<?php echo $edit_produk['nama']; ?>" required>
            <input type="number" name="harga" value="<?php echo $edit_produk['harga']; ?>" step="0.01" required>
            <input type="number" name="stok" value="<?php echo $edit_produk['stok']; ?>" min="0" required>
            <button type="submit">Update Produk</button>
            <a href="produk.php">Batal Edit</a>
        </form>
    <?php endif; ?>
    
   
    <h3>Daftar Semua Produk</h3>
    <table>
        <tr><th>ID</th><th>Nama</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
        <?php foreach ($produk_list as $p): ?>
        <tr>
            <td><?php echo $p['id']; ?></td>
            <td><?php echo $p['nama']; ?></td>
            <td>Rp <?php echo number_format($p['harga'], 2); ?></td>
            <td><?php echo $p['stok']; ?> (<?php echo $p['stok'] > 0 ? 'Tersedia' : 'Habis'; ?>)</td>
            <td>
                <a href="?edit=<?php echo $p['id']; ?>">Edit</a> |
                <form method="POST" style="display: inline;" onsubmit="return confirm('Hapus produk ini?');">
                    <input type="hidden" name="action" value="hapus">
                    <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <br><a href="dashboard.php">← Kembali ke Dashboard</a>
</body>
</html>