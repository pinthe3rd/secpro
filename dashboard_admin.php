<?php
session_start();
include 'config/functions.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $nim = $_POST['nim'];
        $nama = $_POST['nama'];
        $asg = $_POST['asg'];
        $uts = $_POST['uts'];
        $uas = $_POST['uas'];
        
        if (addDataNilai($nim, $nama, $asg, $uts, $uas)) {
            echo "Data berhasil ditambahkan.";
        } else {
            echo "Gagal menambahkan data.";
        }
    } elseif (isset($_POST['update'])) {
        $idToUpdate = $_POST['id'];
        $dataToUpdate = getDataNilaiById($idToUpdate);
        
        echo "
        <h3>Update Data Nilai Mahasiswa</h3>
        <form action='dashboard_admin.php' method='post'>
        <input type='hidden' name='id' value='{$dataToUpdate['id']}'>
                <label>NIM:</label>
                <input type='text' name='nim' value='{$dataToUpdate['nim']}' readonly><br>
                <label>Nama:</label>
                <input type='text' name='nama' value='{$dataToUpdate['nama']}' required><br>
                <label>Assignment:</label>
                <input type='text' name='asg' value='{$dataToUpdate['asg']}' required><br>
                <label>UTS:</label>
                <input type='text' name='uts' value='{$dataToUpdate['uts']}' required><br>
                <label>UAS:</label>
                <input type='text' name='uas' value='{$dataToUpdate['uas']}' required><br>
                <button type='submit' name='confirmUpdate'>Konfirmasi Update</button>
            </form>
        ";
    } elseif (isset($_POST['confirmUpdate'])) {
        $id = $_POST['id'];
        $nama = $_POST['nama'];
        $asg = $_POST['asg'];
        $uts = $_POST['uts'];
        $uas = $_POST['uas'];
        
        if (updateDataNilai($id, $nama, $asg, $uts, $uas)) {
            echo "Data berhasil diperbarui.";
        } else {
            echo "Gagal memperbarui data.";
        }
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        
        if (deleteDataNilai($id)) {
            echo "Data berhasil dihapus.";
        } else {
            echo "Gagal menghapus data.";
        }
    }
}
header("Content-Security-Policy: default-src 'self';");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style/admin.css">
        <title>Dashboard Admin</title>
    </head>
    <body>
        <div class="container">
            <h2>Welcome, Admin!</h2>
            
            <h3>Tambah Data Nilai</h3>
    <form action="dashboard_admin.php" method="post">
        <label>NIM:</label>
        <input type="text" name="nim" required><br>
        <label>Nama:</label>
        <input type="text" name="nama" required><br>
        <label>Assignment:</label>
        <input type="text" name="asg" required><br>
        <label>UTS:</label>
        <input type="text" name="uts" required><br>
        <label>UAS:</label>
        <input type="text" name="uas" required><br>
        <button type="submit" name="add">Tambah Data</button>
    </form>

    <h3>Data Nilai Mahasiswa</h3>
    <table border="1">
        <tr>
            <th>NIM</th>
            <th>Nama</th>
            <th>Assignment</th>
            <th>UTS</th>
            <th>UAS</th>
            <th>Action</th>
        </tr>
        <?php
        $dataNilai = getDataNilai();
        foreach ($dataNilai as $nilai) {
            echo "<tr>";
            echo "<td>{$nilai['nim']}</td>";
            echo "<td>{$nilai['nama']}</td>";
            echo "<td>{$nilai['asg']}</td>";
            echo "<td>{$nilai['uts']}</td>";
            echo "<td>{$nilai['uas']}</td>";
            echo "<td>
                    <form action='dashboard_admin.php' method='post'>
                        <input type='hidden' name='id' value='{$nilai['id']}'>
                        <button type='submit' name='update'>Update</button>
                        <button type='submit' name='delete'>Delete</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        ?>
    </table>
        
    </div>
    <a class="logout-button" href="logout.php">Logout</a>
</body>
</html>
