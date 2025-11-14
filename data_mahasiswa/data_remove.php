<?php
    include '../Database/connection.php';
    
    $id = $_GET['id'];
    $sql_update = "UPDATE users SET user_role = 'Mahasiswa' WHERE userID = '$user_id'";
    if (mysqli_query($conn, $sql)) {
        echo "Data berhasil di delete <br><a href='data_allMhs.php'>Back to List Mahasiswa</a>";
    } else {
        echo "Delete gagal cek kembali data mahasiswa";
    }
?>