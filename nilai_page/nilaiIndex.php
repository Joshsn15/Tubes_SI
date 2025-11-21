<html>

<head>
    <title>Index Nilai</title>
</head>

<style>
    #formContainer {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 20px;
    }

    #formContainer form {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        width: 50%;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    #formContainer form label {
        margin-bottom: 5px;
        font-weight: bold;
    }

    #formContainer form input {
        margin-bottom: 15px;
        padding: 8px;
        width: 100%;
        box-sizing: border-box;
    }

    #formContainer form button {
        padding: 10px 20px;
        cursor: pointer;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }
</style>

<body>
    <?php
    include 'nilai.php';
    include '../Database/connection.php';
    include '../Database/config.php';
    require_once '../Database/encrypt-decrypt.php';
    ?>

    <?php



    global $conn;
    global $keyDecrypt;

    $admin = checkRoleByCookie();
    echo "Admin: " . ($admin ? "true" : "false") . "<br>";


    if ($admin) {
        $sql = "SELECT * FROM nilai WHERE deletedAt IS NULL";
        $stmt = mysqli_prepare($conn, $sql);
        $stmt->execute();
    } else {

        $sqlCheckNim = "SELECT m.nim FROM mahasiswa m 
                        INNER JOIN users u ON u.userID = m.userID 
                        WHERE u.userID = ? AND m.deletedAt IS NULL";

        $stmtNim = mysqli_prepare($conn, $sqlCheckNim);
        $stmtNim->bind_param("s", $_COOKIE['userID']);
        $stmtNim->execute();
        $resultNim = $stmtNim->get_result();
        $mahasiswa = $resultNim->fetch_assoc();

        if (!$mahasiswa) {
            echo "Tidak ada data mahasiswa.";
            exit;
        }

        $nim = $mahasiswa['nim'];
        echo "NIM: " . htmlspecialchars($nim) . "<br>";

        $sql = "SELECT * FROM nilai WHERE NIM = ? AND deletedAt IS NULL";
        $stmt = mysqli_prepare($conn, $sql);
        $stmt->bind_param("s", $nim);
        $stmt->execute();
    }

    $result = $stmt->get_result();



    echo "<table border='1'>";
    echo "<tr>";

    if ($admin) {
        echo "<th>NIM</th>";
        echo "<th>Kode Matkul</th>";
        echo "<th>Nilai</th>";
        echo "<th>Grade</th>";
        echo "<th>Created At</th>";
        echo "<th>Deleted At</th>";
        echo "<th>Updated At</th>";
        echo "<th>Actions</th>";
    } else {
        echo "<th>Kode Matkul</th>";
        echo "<th>Nilai</th>";
        echo "<th>Grade</th>";
    }

    echo "</tr>";


    while ($row = $result->fetch_assoc()) {
        if ($row['Grade'] == null || $row['Nilai'] == null) {
            continue;
        }

        $decryptedNilai = decryptfunc($row['Nilai'], $keyDecrypt);
        $decryptedGrade = decryptfunc($row['Grade'], $keyDecrypt);
        echo "<tr>";

        if ($admin) {
            echo "<td>" . htmlspecialchars($row['NIM']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Kd_Matkul']) . "</td>";
            echo "<td>" . htmlspecialchars($decryptedNilai) . "</td>";
            echo "<td>" . htmlspecialchars($decryptedGrade) . "</td>";
            echo "<td>" . htmlspecialchars($row['createdAt']) . "</td>";
            echo "<td>" . htmlspecialchars($row['deletedAt']) . "</td>";
            echo "<td>" . htmlspecialchars($row['updatedAt']) . "</td>";
            echo "<td>";
            echo "<button onclick='updateNilai(\"" . htmlspecialchars($row['NIM'], ENT_QUOTES) . "\", \"" . htmlspecialchars($row['Kd_Matkul'], ENT_QUOTES) . "\", \"" . htmlspecialchars($decryptedNilai, ENT_QUOTES) . "\")'>Update</button> ";
            echo "<button onclick='deleteNilai(\"" . htmlspecialchars($row['NIM'], ENT_QUOTES) . "\", \"" . htmlspecialchars($row['Kd_Matkul'], ENT_QUOTES) . "\")'>Delete</button>";
            echo "</td>";
        } else {
            echo "<td>" . htmlspecialchars($row['Kd_Matkul']) . "</td>";
            echo "<td>" . htmlspecialchars($decryptedNilai) . "</td>";
            echo "<td>" . htmlspecialchars($decryptedGrade) . "</td>";
        }

        echo "</tr>";
    }

    echo "</table>";



    echo "<br>";
    if ($admin) {
        echo "<button onclick='insertNilai()'>Insert Nilai</button>";
    }

    echo "<div id='formContainer'></div>";
    ?>
</body>

<script>
    function insertNilai() {
        var form = "<form method='post' action='nilai.php'>";
        form += "<h3>Insert Nilai</h3>";
        form += "<label for='nim'>NIM</label>";
        form += "<input type='text' name='nim' id='nim' required>";
        form += "<label for='kd_matkul'>Kode Matkul</label>";
        form += "<input type='text' name='kd_matkul' id='kd_matkul' required>";
        form += "<label for='nilai'>Nilai</label>";
        form += "<input type='number' step='0.01' name='nilai' id='nilai' required>";
        form += "<button type='submit' name='submitInsertNilai'>Insert Nilai</button>";
        form += "<button type='button' onclick='clearForm()'>Cancel</button>";
        form += "</form>";
        document.getElementById("formContainer").innerHTML = form;
    }

    function updateNilai(nim, kd_matkul, nilai) {
        var form = "<form method='post' action='nilai.php'>";
        form += "<h3>Update Nilai</h3>";
        form += "<input type='hidden' name='nim' value='" + nim + "'>";
        form += "<input type='hidden' name='kd_matkul' value='" + kd_matkul + "'>";
        form += "<label>NIM: " + nim + "</label><br>";
        form += "<label>Kode Matkul: " + kd_matkul + "</label><br>";
        form += "<label for='nilai'>Nilai</label>";
        form += "<input type='number' step='0.01' name='nilai' id='nilai' value='" + nilai + "' required>";
        form += "<button type='submit' name='submitUpdateNilai'>Update Nilai</button>";
        form += "<button type='button' onclick='clearForm()'>Cancel</button>";
        form += "</form>";
        document.getElementById("formContainer").innerHTML = form;
    }

    function deleteNilai(nim, kd_matkul) {
        if (confirm('Are you sure you want to delete this record?')) {
            var form = document.createElement('form');
            form.method = 'post';
            form.action = 'nilai.php';

            var nimInput = document.createElement('input');
            nimInput.type = 'hidden';
            nimInput.name = 'nim';
            nimInput.value = nim;

            var kdMatkulInput = document.createElement('input');
            kdMatkulInput.type = 'hidden';
            kdMatkulInput.name = 'kd_matkul';
            kdMatkulInput.value = kd_matkul;

            var submitInput = document.createElement('input');
            submitInput.type = 'hidden';
            submitInput.name = 'submitDeleteNilai';
            submitInput.value = '1';

            form.appendChild(nimInput);
            form.appendChild(kdMatkulInput);
            form.appendChild(submitInput);

            document.body.appendChild(form);
            form.submit();
        }
    }

    function clearForm() {
        document.getElementById("formContainer").innerHTML = "";
    }
</script>

</html>