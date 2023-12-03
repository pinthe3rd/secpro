<?php
session_start();
include 'config/functions.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: index.php");
    exit();
}

$nim = $_SESSION['user']['nim']; 
header("Content-Security-Policy: default-src 'self';");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/user.css">
    <title>Dashboard User</title>
</head>
<body>
    <div class="container">
    <h2>Welcome, User!</h2>

    <h3>Data Nilai Mahasiswa</h3>
    <table border="1">
        <tr>
            <th>NIM</th>
            <th>Nama</th>
            <th>Assignment</th>
            <th>UTS</th>
            <th>UAS</th>
        </tr>
        <?php
        $dataNilai = getDataNilaiByNIM($nim); 
        foreach ($dataNilai as $nilai) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($nilai['nim']) . "</td>";
            echo "<td>" . htmlspecialchars($nilai['nama']) . "</td>";
            echo "<td>" . htmlspecialchars($nilai['asg']) . "</td>";
            echo "<td>" . htmlspecialchars($nilai['uts']) . "</td>";
            echo "<td>" . htmlspecialchars($nilai['uas']) . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <!-- <h3>Daftar Dosen</h3>
    <ul>
        php
        $daftarDosen = getDaftarDosen();
        foreach ($daftarDosen as $dosen) {
            echo "<li>{$dosen['nama']}</li>";
        }d
        ?>
    </ul> -->
    <button href="logout.php" class="logout-button">
        <a href="logout.php" class="logout-button">Log out</a>
    </button>


    </div>
</body>
</html>
