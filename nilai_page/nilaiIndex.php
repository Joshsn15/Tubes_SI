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
    // $_COOKIE['userID'] = "afdb1209-1e52-48f9-aa55-cbe419d42c1a"; // (Untuk tes) mahasiswa
    // $_COOKIE['userID'] = "2391ba08-0376-4795-98c2-6c3b2ede5d63"; // (Untuk tes) admin

    global $conn;
    if (checkRoleByCookie()) {
        $admin = true;
    } else {
        $admin = false;
    }
    echo "Admin: " . ($admin ? "true" : "false") . "<br>";

    $sqlCheckNim = "SELECT m.nim FROM mahasiswa m 
                    INNER JOIN users u ON u.userID = m.userID 
                    WHERE u.userID = ? AND m.deletedAt IS NULL ";

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
    echo $nim;
    $sqlNilai = "SELECT * FROM nilai WHERE NIM = ? AND deletedAt IS NULL"; // untuk mahasiswa
    if ($admin) {
        $sqlAdmin = "SELECT * FROM nilai WHERE deletedAt IS NULL"; // untuk admin
    } else {
        $sqlAdmin = $sqlNilai;
    }

    $stmtNilai = mysqli_prepare($conn, $sqlAdmin);
    if ($admin) {
        $stmtNilai->execute();
    } else {
        $stmtNilai->bind_param("s", $nim);
        $stmtNilai->execute();
    }
    $result = $stmtNilai->get_result();
    
    echo "<table border='1'>";
    echo "<tr>";
    if ($admin) {
        echo "<th>NIM</th><th>Kode Matkul</th><th>Nilai</th><th>Grade</th><th>Created At</th><th>Deleted At</th><th>Updated At</th><th>Actions</th>";
    } else {
        echo "<th>Kode Matkul</th><th>Nilai</th><th>Grade</th>";
    }
    echo "</tr>";
    global $keyDecrypt;

    while ($row = $result->fetch_assoc()) {
        if($row['Grade'] == null || $row['Nilai'] == null){  
            continue;
        }
        
        $decrpytNilai = decryptfunc($row['Nilai'], $keyDecrypt);
        $decrpytGrade = decryptfunc($row['Grade'], $keyDecrypt);
        
        echo "<tr>";
        if ($admin) {
            echo "<td>" . $row['NIM'] . "</td>";
            echo "<td>" . $row['Kd_Matkul'] . "</td>";
            echo "<td>" . $decrpytNilai . "</td>";
            echo "<td>" . $decrpytGrade . "</td>";
            echo "<td>" . $row['createdAt'] . "</td>";
            echo "<td>" . $row['deletedAt'] . "</td>";
            echo "<td>" . $row['updatedAt'] . "</td>";
            echo "<td>";
            echo "<button onclick=\"updateNilai('" . $row['NIM'] . "', '" . $row['Kd_Matkul'] . "', '" . $decrpytNilai . "', '" . $decrpytGrade . "')\">Update</button> ";
            echo "<button onclick='deleteNilai(\"" . $row['NIM'] . "\", \"" . $row['Kd_Matkul'] . "\")'>Delete</button>";
            echo "</td>";
        } else {
            echo "<td>" . $row['Kd_Matkul'] . "</td>";
            echo "<td>" . $decrpytNilai . "</td>";
            echo "<td>" . $decrpytGrade . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    echo "<br>";
    echo $admin ? "<button onclick='insertNilai()'>Insert Nilai</button>" : "";

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

    function updateNilai(nim, kd_matkul, nilai, grade) {
        var form = "<form method='post' action='nilai.php'>";
        form += "<h3>Update Nilai</h3>";
        form += "<input type='hidden' name='nim' value='" + nim + "'>";
        form += "<input type='hidden' name='kd_matkul' value='" + kd_matkul + "'>";
        form += "<label>NIM: " + nim + "</label><br>";
        form += "<label>Kode Matkul: " + kd_matkul + "</label><br>";
        form += "<label for='nilai'>Nilai</label>";
        form += "<input type='number' step='0.01' name='nilai' id='nilai' value='" + nilai + "' required>";
        form += "<label for='grade'>Grade</label>";
        form += "<input type='text' name='grade' id='grade' value='" + grade + "' required>";
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