<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        #add {
            float: right;
        }

        table tr,
        td,
        th {
            border-bottom: 1px solid black;
        }
    </style>
    <title>Transkrip Nilai</title>

    <script>
        function searchData(reset) {
            let name = encodeURIComponent(document.getElementById("searchName").value)
            let nim = encodeURIComponent(document.getElementById("searchNIM").value);

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("data").innerHTML = xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "search_trans.php?name=" + name + "&nim=" + nim + "&reset=" + reset, true);
            xmlhttp.send();
        }
    </script>
</head>

<body>
    <h1>TRANSKRIP NILAI</h1>
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

    $sql = "SELECT m.NIM, u.nama, u.userID FROM mahasiswa m JOIN users u ON m.userID = u.userID ORDER BY m.nim ASC";
    $stmt = mysqli_prepare($conn, $sql);
    // mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    echo "<table id='data'> <tr> <th>NIM </th> <th> Nama </th> <th> Aksi </th> </tr>";
    while ($row = mysqli_fetch_array($result)) {
        $nama = decryptFunc($row['nama'], $keyDecrypt);
        echo "<tr> <td>" . $row['NIM'] . "</td>";
        echo "<td>" . $nama . "</td>";
        echo "<td><a href='LihatNilai.php?userID=" . $row["userID"] . "'>Lihat Nilai </a></td></tr>";
    }
    echo "</table>";
    ?> 
</body>

</html>