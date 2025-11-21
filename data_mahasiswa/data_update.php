<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update data mahasiswa</title>
</head>
<body>
    <?php
        include "../Database/connection.php";
        include "../Database/encrypt-decrypt.php";
        include "../Database/config.php";
        $id = $_GET['id'];
    ?>
    <h2>Edit Data Mahasiswa</h2>
    <form method="post" action="data_hasilUpdate.php?id=<?php echo $id?>">
        <?php
            $sql = "SELECT u.userID, mhs.NIM, u.nama, mhs.Prodi, u.alamat FROM users u INNER JOIN mahasiswa mhs ON u.userID = mhs.userID WHERE u.userID = '$id'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_array($result);

            $nama = decryptFunc($row['nama'], $keyDecrypt);

            echo '<label for="nim">NIM</label><br>
                <input type="hidden" name= "id" value="' .$row['userID'] . '">
                <input type="text" name="nim" value="' . $row['NIM'] . '"><br>
                <label for="nama">Nama</label><br>
                <input type="text" name="nama" value="' . $nama . '"><br>
                <label for="prodi">Prodi</label><br>
                <input type="text" name="prodi" value="' . $row['Prodi'] . '"><br>
                <label for="alamat">Alamat</label><br>
                <input type="text" name="alamat" value="' . $row['alamat'] . '"><br>
                <input type="submit" name="submit" value="Save">';
        ?>  
    </form>
</body>
</html>