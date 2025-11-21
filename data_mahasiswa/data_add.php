<?php
    include '../Database/connection.php';

    if (isset($_POST['submitAdd'])) {
        $add = $_POST['prosesAdd'];
        $nimList = $_POST['nim'];
        $prodiList = $_POST['prodi'];

        foreach ($add as $id) {
            $nim = $nimList[$id];
            $prodi = $prodiList[$id];
            $sql_update = "UPDATE users SET user_role='Mahasiswa' WHERE userID='$id'";
            mysqli_query($conn, $sql_update);
            $sql_insert = "INSERT INTO mahasiswa (NIM, Prodi, userID) VALUES ('$nim','$prodi', '$id')";
            if (!mysqli_query($conn, $sql_insert)) {
                echo "Data tidak berhasil ditambahkan, silahkan cek ulang data <br><a href='data_allMhs.php'>Back to List Mahasiswa</a>"; 
            }
        }
    } 
    echo "Data Mahasiswa berhasil di tambahkan <br><a href='data_allMhs.php'>Back to List Mahasiswa</a>";


?>