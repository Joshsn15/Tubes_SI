<html>

<head>
    <title>Index KRS</title>
</head>

<body>
    <?php
    include 'krs.php';
    include '../Database/connection.php';

    ?>

    <?php
    // $_COOKIE['userID'] = "2e95359a-eaf4-4e16-8ba5-2b22aa9a80c9";


    global $conn;
    if (checkRoleByCookie()) {
        $admin = true;
    } else {
        $admin = false;
    }
    echo "Admin: " . ($admin ? "true" : "false") . "<br>";
    
    $sqlCheckNim = "SELECT m.nim FROM mahasiswa m INNER JOIN krs k ON m.nim = k.nim INNER JOIN users u ON u.userID = m.userID WHERE u.userID = ?";
    $stmt = mysqli_prepare($conn, $sqlCheckNim);
    $stmt->bind_param("s", $_COOKIE['userID']);
    $stmt->execute();
    $result = $stmt->get_result();

    $sql = "SELECT * FROM krs WHERE nim = ?";
    $stmt = mysqli_prepare($conn, $sql);
    $stmt->bind_param("s", $result->fetch_assoc()['nim']);
    $stmt->execute();
    $results = $stmt->get_result();

    echo "<table border='1'>";
    echo "<tr>";
    if ($admin) {
        echo "<th>NIM</th><th>Kode Matkul</th><th>SKS</th><th>Status</th><th>Created At</th><th>Deleted At</th><th>Updated At</th>";
    } else {
        echo "<th>Kode Matkul</th><th>SKS</th><th>Status</th>";
    }
    echo "</tr>";
    while ($row = $results->fetch_assoc()) {
        echo "<tr>";
        if ($admin) {
            echo "<td>" . $row['NIM'] . "</td>";
            echo "<td>" . $row['kd_matkul'] . "</td>";
            echo "<td>" . $row['sks'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "<td>" . $row['createdAt'] . "</td>";
            echo "<td>" . $row['deletedAt'] . "</td>";
            echo "<td>" . $row['updatedAt'] . "</td>";
        } else {
            echo "<td>" . $row['kd_matkul'] . "</td>";
            echo "<td>" . $row['sks'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    ?>
    <!-- <form action="krs.php" method="post">
        <label for="nim">NIM:</label>
        <input type="text" id="nim" name="nim" required><br>
        <label for="kd_matkul">Kode Matkul:</label>
        <input type="text" id="kd_matkul" name="kd_matkul" required><br>
        <label for="sks">SKS:</label>
        <input type="text" id="sks" name="sks" required><br>
        <label for="status">Status:</label>
        <input type="text" id="status" name="status" required><br>
        <input type="submit" value="Submit">
    </form> -->
</body>


</html>