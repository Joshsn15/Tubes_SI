<?php
    include "../Database/connection.php";
    include "../Database/encrypt-decrypt.php";
    include "../Database/config.php";

    $id = $_GET['id'];

    if (isset($_POST['submit'])) {
        $id = $_GET['id'];
        $nim = $_POST['nim'];
        $nama = $_POST['nama'];
        $prodi = $_POST['prodi'];
        $alamat = $_POST['alamat'];
        $nama_encrypt = encryptFunc($nama, $keyDecrypt);

        $sql_mhs = "UPDATE mahasiswa SET NIM = '$nim', Prodi = '$prodi' WHERE userID = '$id'";
        $sql_users = "UPDATE users SET nama = '$nama_encrypt', alamat = '$alamat' WHERE userID = '$id'";

        $update1 = mysqli_query($conn, $sql_mhs);
        $update2 = mysqli_query($conn, $sql_users);

        if ($update1 && $update2) {
            echo "Update berhasil <br><a href= 'data_allMhs.php'> Back to List Mahasiswa</a>";
        }
    }
?>