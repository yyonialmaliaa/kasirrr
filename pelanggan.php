<?php
session_start();
if (!isset($_SESSION['user_id'])) header('Location: login.php');
include 'config.php';

if ($_POST) {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $stmt = $pdo->prepare("INSERT INTO pelanggan (nama, alamat, telepon) VALUES (?, ?, ?)");
    $stmt->execute([$nama, $alamat, $telepon]);
    $success = 'Pelanggan ditambahkan!';
}


$pelanggan_list = $pdo->query("SELECT * FROM pelanggan")->fetchAll(); // ambil semua pelanggan
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <script src="theme.js"></script> 
    <meta charset="UTF-8">
    <title>Manajemen Pelanggan</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
   
</head>
<body>
    <h2>Tambah Pelanggan</h2>
    <form method="POST">
        <input type="text" name="nama" placeholder="Nama" required><br>
        <input type="text" name="alamat" placeholder="Alamat"><br>
        <input type="text" name="telepon" placeholder="Telepon"><br>
        <button type="submit">Tambah</button>
    </form>
    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
    
    <h3>Daftar Pelanggan</h3>
    <table>
        <tr><th>ID</th><th>Nama</th><th>Alamat</th><th>Telepon</th></tr>
        <?php foreach ($pelanggan_list as $p): ?>
        <tr>
            <td><?php echo $p['id']; ?></td>
            <td><?php echo $p['nama']; ?></td>
            <td><?php echo $p['alamat']; ?></td>
            <td><?php echo $p['telepon']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="dashboard.php">← Kembali ke Dashboard</a>
</body>
</html>