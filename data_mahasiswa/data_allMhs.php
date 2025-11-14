<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
</head>

<body>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        <input type="text" name="nama_search" placeholder="Nama">
        <input type="text" name="nim_search" placeholder="NIM">
        <input type="submit" name="search" value="Search">
        <input type="reset" name="reset" value="Reset">
        <a id="add" href = "data_showAdd.php">Add New Mahasiswa</a>
    </form>
   <?php
        include '../Database/connection.php';
        include '../Database/encrypt-decrypt.php';
        include '../Database/config.php';

        if (isset($_POST['search'])) {
            $nama_search = $_POST['nama_search'];
            $nim_search = $_POST['nim_search'];
            $sql_allMhs = "SELECT * FROM users";


            $sql_search= "SELECT u.userID, mhs.NIM, u.nama, mhs.prodi, u.alamat FROM users u INNER JOIN mahasiswa mhs ON u.userID = mhs.userID WHERE u.nama like '%?%' OR mhs.NIM like '%?%' AND mhs.deletedAt IS NULL";
            $stmt_search= mysqli_prepare($conn, $sql_search);
            mysqli_stmt_bind_param($stmt_search, "ss",$nama_search, $nim_search);
            mysqli_stmt_execute($stmt_search);
            $result_search = mysqli_stmt_get_result($stmt_search);

            echo "<table><tr><th>NIM</th><th>Nama</th><th>Prodi</th><th>Alamat</th></tr>";
            while ($row = mysqli_fetch_array($result_search)) {
                $nama = decryptFunc($row['nama'], $keyDecrypt);
                echo "<tr><td>" . $row['NIM'] . "</td>
                <td>" . $nama . "</td>
                <td>" . $row['prodi'] . "</td>
                <td>" . $row['alamat'] . "</td>
                <td><a href='data_update.php?id=" .$row['userID']. "'>Edit </a><a href='data_remove.php?id=" .$row['userID']. "'> Delete</a></td><tr>";
            }
            echo "</table>";
        } else {
            $sql = "SELECT u.userID, mhs.NIM, u.nama, mhs.prodi, u.alamat FROM users u INNER JOIN mahasiswa mhs ON u.userID = mhs.userID WHERE mhs.deletedAt IS NULL";
            $result = mysqli_query($conn,$sql);
            echo "<table><tr><th>NIM</th><th>Nama</th><th>Prodi</th><th>Alamat</th></tr>";
            while ($row = mysqli_fetch_array($result)) {
                $nama = decryptFunc($row['nama'], $keyDecrypt);
                echo "<tr><td>" . $row['NIM'] . "</td>
                <td>" . $nama . "</td>
                <td>" . $row['prodi'] . "</td>
                <td>" . $row['alamat'] . "</td>
                <td><a href='data_update.php?id=" .$row['userID']. "'>Edit </a><a href='data_remove.php?id=" .$row['userID']. "'> Delete</a></td><tr>";
            }
            echo "</table>";
        }
   ?>
</body>

</html>
