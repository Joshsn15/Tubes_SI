<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Data Mahasiswa</title>
</head>

<body>

    <?php
    include '../Database/connection.php';
    include '../Database/config.php';
    include '../Database/encrypt-decrypt.php';


    $sql_searchNull = "SELECT * FROM users WHERE user_role IS NULL";
    $result_searchNull = mysqli_query($conn, $sql_searchNull);
    echo "<table><tr><th>Nama</th><th>Alamat</th></tr>";
    echo "<form method='post' action='data_formAdd.php'>";
    while ($row = mysqli_fetch_array($result_searchNull)) {
        $nama = decryptFunc($row['nama'], $keyDecrypt);
        echo "<tr><input type='hidden' name='id' value='" . $row['userID'] . "'/>
                <td>" . $nama . "</td>
                <td>" . $row['alamat'] . "</td>
                <td><input type='checkbox' name='insert[]' value='$row[userID]'></td></tr>";

    }
    echo "</table><br><input type='submit' name='addData' value='Add Checked'>";
    echo "</form>";
    ?>
</body>

</html>