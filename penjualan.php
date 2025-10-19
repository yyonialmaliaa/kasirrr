<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
include 'config.php';


$error = $success = '';
$total_keranjang = 0;


try {
    $pelanggan_list = $pdo->query("SELECT * FROM pelanggan ORDER BY nama")->fetchAll();
    $produk_list = $pdo->query("SELECT * FROM produk WHERE stok > 0 ORDER BY nama")->fetchAll();
} catch (PDOException $e) {
    $error = "Error mengambil data: " . $e->getMessage();
}


if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}
foreach ($_SESSION['keranjang'] as $item) {
    $total_keranjang += $item['subtotal'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (isset($_POST['tambah_item'])) {
            $id_produk = (int)$_POST['id_produk'];
            $qty = (int)$_POST['qty'];
            
            if ($qty < 1) {
                throw new Exception("Quantity minimal 1.");
            }
            
            $stmt_produk = $pdo->prepare("SELECT * FROM produk WHERE id = ?");
            $stmt_produk->execute([$id_produk]);
            $item = $stmt_produk->fetch();
            
            if (!$item) {
                throw new Exception("Produk tidak ditemukan.");
            }
            
            if ($item['stok'] < $qty) {
                throw new Exception("Stok tidak cukup! Stok tersedia: " . $item['stok']);
            }
            
            $subtotal = $item['harga'] * $qty;
            $_SESSION['keranjang'][] = [
                'id_produk' => $id_produk,
                'nama' => $item['nama'],
                'harga' => $item['harga'],
                'qty' => $qty,
                'subtotal' => $subtotal
            ];
            
            
            $update_stok = $pdo->prepare("UPDATE produk SET stok = stok - ? WHERE id = ?");
            $update_stok->execute([$qty, $id_produk]); // kurangi stok
            
            $success = "Item '" . $item['nama'] . "' ditambahkan ke keranjang!";
            
        } elseif (isset($_POST['simpan_penjualan'])) {
            if (empty($_SESSION['keranjang'])) {
                throw new Exception("Keranjang kosong! Tambahkan item terlebih dahulu.");
            }
            
            $id_pelanggan_input = (int)$_POST['id_pelanggan'];
            $id_pelanggan = ($id_pelanggan_input > 0) ? $id_pelanggan_input : null;
            
            $total = 0;
            foreach ($_SESSION['keranjang'] as $item) {
                $total += $item['subtotal'];
            }
            
            
            $stmt = $pdo->prepare("INSERT INTO penjualan (id_pelanggan, id_user, tanggal, total) VALUES (?, ?, CURDATE(), ?)");
            $stmt->execute([$id_pelanggan, $_SESSION['user_id'], $total]);
            $id_penjualan = $pdo->lastInsertId();
            

            foreach ($_SESSION['keranjang'] as $item) {
                $stmt_detail = $pdo->prepare("INSERT INTO detail_penjualan (id_penjualan, id_produk, qty, subtotal) VALUES (?, ?, ?, ?)");
                $stmt_detail->execute([$id_penjualan, $item['id_produk'], $item['qty'], $item['subtotal']]);
            }
            
            $data_pelanggan = ['nama' => 'Pelanggan Umum', 'alamat' => '', 'telepon' => '']; // ambil data pelanggan untuk struk
            if ($id_pelanggan) {
                $stmt_pelanggan = $pdo->prepare("SELECT * FROM pelanggan WHERE id = ?");
                $stmt_pelanggan->execute([$id_pelanggan]);
                $data_pelanggan = $stmt_pelanggan->fetch() ?: $data_pelanggan;
            }
            
            $_SESSION['struk'] = [    // simpan data struk 
                'id_penjualan' => $id_penjualan,
                'pelanggan' => $data_pelanggan['nama'],
                'alamat' => $data_pelanggan['alamat'],
                'telepon' => $data_pelanggan['telepon'],
                'tanggal' => date('d-m-Y H:i:s'),
                'user' => $_SESSION['username'],
                'items' => $_SESSION['keranjang'],
                'total' => $total
            ];
            
           
            unset($_SESSION['keranjang']);  // kosongkan keranjang
            
           
            header("Location: struk.php");
            exit;
            
        } elseif (isset($_POST['kosongkan_keranjang'])) {
            unset($_SESSION['keranjang']);
            $success = "Keranjang dikosongkan!";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    } catch (PDOException $e) {
        $error = "Error database: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjualan</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        .section-spacing {
            margin-bottom: 3rem;
        }
        .keranjang-section {
            clear: both;
            margin-top: 3rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Transaksi Penjualan</h2>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
   
        <div class="section-spacing">
            <h3>Tambah Item ke Keranjang</h3>
            <?php if (empty($produk_list)): ?>
                <p class="warning">Tidak ada produk dengan stok tersedia. Tambahkan stok di menu Produk terlebih dahulu.</p>
            <?php else: ?>
                <form method="POST">
                    <div class="form-group">
                        <label>Produk:</label>
                        <select name="id_produk" required>
                            <option value="">Pilih Produk</option>
                            <?php foreach ($produk_list as $p): ?>
                                <option value="<?php echo $p['id']; ?>">
                                    <?php echo htmlspecialchars($p['nama']); ?> (Stok: <?php echo $p['stok']; ?>, Harga: Rp <?php echo number_format($p['harga'], 2); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Quantity:</label>
                        <input type="number" name="qty" min="1" max="100" value="1" required placeholder="1-100">
                    </div>
                    <button type="submit" name="tambah_item">Tambah ke Keranjang</button>
                </form>
            <?php endif; ?>
        </div>
        
       
        <?php if (!empty($_SESSION['keranjang'])): ?>
            <div class="keranjang-section">
                <h3>Keranjang Belanja (Total: Rp <?php echo number_format($total_keranjang, 2); ?>)</h3>
                <table class="keranjang">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Harga Satuan</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['keranjang'] as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['nama']); ?></td>
                                <td>Rp <?php echo number_format($item['harga'], 2); ?></td>
                                <td><?php echo $item['qty']; ?></td>
                                <td>Rp <?php echo number_format($item['subtotal'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div style="margin: 2rem 0;">
                    <form method="POST" style="display: inline;" onsubmit="return confirm('Kosongkan keranjang? Stok tidak akan dikembalikan.');">
                        <button type="submit" name="kosongkan_keranjang" class="btn-warning">Kosongkan Keranjang</button>
                    </form>
                </div>
                
                <div class="section-spacing">
                    <h3>Simpan Transaksi</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label>Pelanggan (Opsional):</label>
                            <select name="id_pelanggan">
                                <option value="0">Pelanggan Umum (Tanpa Data Pelanggan)</option>
                                <?php foreach ($pelanggan_list as $pel): ?>
                                    <option value="<?php echo $pel['id']; ?>"><?php echo htmlspecialchars($pel['nama']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" name="simpan_penjualan" class="btn-success">Simpan Penjualan & Generate Struk</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <p>Keranjang kosong. Silakan tambahkan item produk terlebih dahulu.</p>
        <?php endif; ?>
        
        <br><a href="dashboard.php">← Kembali ke Dashboard</a>
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