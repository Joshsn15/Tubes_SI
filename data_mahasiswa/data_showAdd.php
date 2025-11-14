<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Data Mahasiswa</title>
</head>
<body>
    
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
        User ID: <input type="text" name="userid"><br>
        NIM: <input type="text" name="nim"><br>
        Nama: <input type="text" name="nama"><br>
        Prodi: <input type="text" name="prodi"><br>
        Alamat: <input type="text" name = "alamat"><br>
        <hr>
        <input type="submit" name="add" value="Add Data Mahasiswa">
    </form>

    <?php
        include '../Database/connection.php';
        include  '../Database/config.php';
        include '../Database/encrypt-decrypt.php';
        if (isset($_POST['add'])) {
            $user_id = $_POST['userid'];
            $nim = $_POST['nim'];
            $prodi = $_POST['prodi'];

            $sql_searchNull = "SELECT * FROM users WHERE user_rolee IS NULL";
            $result_searchNull = mysqli_query($conn,$sql_searchNull);
            echo "<table><tr><th>User ID</th><th>Nama</th><th>Alamat</th></tr>";
            while ($row = mysqli_fetch_array($result_searchNull)) {
                $nama = decryptFunc($row['nama'], $keyDecrypt);
                echo "<tr><td>" . $row['userID'] . "</td>
                <td>" . $nama . "</td>
                <td>" . $row['alamat'] . "</td>
                <td><a href='data_add.php?id=" .$row['userID']. "'>Add</a>";
            }
            echo "</table>";

            $sql= "INSERT INTO mahasiswa (NIM,Prodi,userID) VALUES ('$nim', '$prodi', '$user_id')";
            if (mysqli_query($conn,$sql)) {
               $sql_show = "SELECT mhs.NIM, u.nama, mhs.Prodi, u.alamat FROM users u INNER JOIN mahasiswa mhs ON u.userID = mhs.userID";
               $stmtShow = mysqli_prepare($conn, $sql_show);
               mysqli_stmt_bind_param();
            }
        }
    ?>
</body>
</html>

