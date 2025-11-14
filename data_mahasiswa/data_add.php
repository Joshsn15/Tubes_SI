<?php
    include '../Database/connection.php';
    
    $id = $_GET['id'];
    $sql = "UPDATE mahasiswa SET deletedAt = CURRENT_TIMESTAMP WHERE userID = '$id'";
    if (mysqli_query($conn, $sql)) {
        echo "Data berhasil di delete <br><a href='data_allMhs.php'>Back to List Mahasiswa</a>";
    } else {
        echo "Delete gagal cek kembali data mahasiswa";
    }
?>