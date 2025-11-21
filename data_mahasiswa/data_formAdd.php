<?php
    include '../Database/connection.php';
    include '../Database/config.php';
    include '../Database/encrypt-decrypt.php';

    $add = $_POST['insert'];

    echo "<form method='post' action = 'data_Add.php'>
            <h2>Input NIM dan Prodi Mahasiswa</h2><br>
            <table><tr><th>Nama Mahasiswa</th><th>Nim</th><th>Prodi</th></tr>";

    $sql_nim = "SELECT NIM FROM mahasiswa ORDER BY NIM ASC";
    $stmt = mysqli_prepare($conn, $sql_nim);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $total = mysqli_num_rows($result);
        $index = 0;
        while ($row_nim = mysqli_fetch_assoc($result)) {
            $index++;

            if ($index == $total) {
                $nim = $row_nim['NIM'] + 1;
            }
        }
    }

    foreach ($add as $id) {
        $sql_nama = "SELECT nama FROM users WHERE userID = '$id'";
        $result = mysqli_query($conn, $sql_nama);
        $row = mysqli_fetch_assoc($result);
        $nama = decryptFunc($row['nama'], $keyDecrypt);

        echo "<tr><td><input type='text' name='nama' value='$nama' disabled></td>
                <td><input type='text' value='$nim' disabled></td>
                <input type='hidden' name='nim[$id]' value='$nim'>
                <td><input type='text' name='prodi[$id]'></td>
                <input type='hidden' name='prosesAdd[]' value='$id'></tr>";
        $nim++;

    }
    echo "</table><button type='submit' name='submitAdd'>Add</button></form>";

?>