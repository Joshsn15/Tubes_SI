<!DOCTYPE html>
<html>

<head>
    <title>Transkrip Nilai</title>
</head>

<body>
    <h1>TRANSKRIP NILAI </h1>
    <div id="search">
        <input type="text" name="name" placeholder="Name" id="searchName">
        <input type="text" name="nim" placeholder="NIM" id="searchNIM">
        <button onclick="searchData()">Search</button>
        <button onclick="searchData('reset')">Reset</button>
    </div>

    <?php
    include '../Database/connection.php';
    include '../Database/encrypt-decrypt.php';
    include '../Database/config.php';
    include '../Database/checkRole.php';

    // $id = '7eac0a6a-7157-4474-92cf-0f28bbf0a444';
    $id = $_COOKIE['userID'];

    // if (!isset($_COOKIE['user_role']) || $_COOKIE['user_role'] != 'mahasiswa') {
    //     header('Location: login/login-form.php');
    //     exit;
    // }
    // $mahasiswa_id = $_COOKIE['userID'];
    $id = $_GET['userID'];
    
    $sql = "SELECT mk.Kd_Matkul, mk.Nama_Matkul, mk.sks, n.Nilai, n.Grade FROM nilai n JOIN matkul mk ON n.Kd_Matkul = mk.Kd_Matkul JOIN mahasiswa m ON n.NIM = m.NIM WHERE m.userID = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    echo "<table> <tr> <th> Kode MK </th> <th> Nama_Matkul </th> <th> SKS </th> <th> Nilai </th> <th> Grade </th> </tr>";
    while ($row = mysqli_fetch_array($result)) {
        $nilai = decryptFunc($row['Nilai'], $keyDecrypt);
        $grade = decryptFunc($row['Grade'], $keyDecrypt);
        echo "<tr> <td>" . $row['Kd_Matkul'] . "</td>";
        echo "<td>" . $row['Nama_Matkul'] . "</td>";
        echo "<td>" . $row['sks'] . "</td>";
        echo "<td>" . $nilai . "</td>";
        echo "<td>" . $grade . "</td>";
        echo "<td><a href='../nilai_page/nilaiIndex.php?userID=" . $id . "'>Update Nilai</a></td></tr>";
    }
    echo "</table>";
    ?>
</body>

</html>