<?php
    include '../Database/connection.php';
    
    $id = $_GET['id'];
    $sql_update = "UPDATE mahasiswa SET deletedAt = CURRENT_TIMESTAMP WHERE userID = '$id'";
    if (mysqli_query($conn, $sql_update)) {
        echo "Data berhasil di delete <br><a href='data_allMhs.php'>Back to List Mahasiswa</a>";
    } else {
        echo "Delete gagal cek kembali data mahasiswa";
    }
?>