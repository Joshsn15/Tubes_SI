<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
</head>
<script>
    function searchData(reset = "") {
        if (reset == "reset") {
            document.getElementById("nama_search").value = "";
            document.getElementById("nim_search").value = "";
        }
        
        const form = new FormData();
        form.append("ajax", "1");
        form.append("nama_search", document.getElementById("nama_search").value);
        form.append("nim_search", document.getElementById("nim_search").value);
        form.append("reset", reset);

        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById("data").innerHTML = xhr.responseText;
            }
        };

        xhr.open("POST", "<?php echo $_SERVER['PHP_SELF']; ?>", true);
        xhr.send(form);
    }

    window.onload = function(){
        searchData();
    }

</script>

<body>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="text" id = "nama_search" name="nama_search" placeholder="Nama">
        <input type="text" id = "nim_search" name="nim_search" placeholder="NIM">
        <button type="button" onclick="searchData('search')">Search</button>
        <button type="button" onclick="searchData('reset')">Reset</button>
        <a id="add" href="data_showAdd.php">Add New Mahasiswa</a>
    </form>
    
    <div id="data"></div>

    <?php
    include '../Database/connection.php';
    include '../Database/encrypt-decrypt.php';
    include '../Database/config.php';

    if (isset($_POST['ajax'])) {
        ob_clean();
        if (isset($_POST['reset']) && $_POST['reset'] == "reset") {
            $result_reset = mysqli_query($conn, "SELECT u.userID, mhs.NIM, u.nama, mhs.prodi, u.alamat FROM users u INNER JOIN mahasiswa mhs ON u.userID = mhs.userID WHERE mhs.deletedAt IS NULL ORDER BY mhs.nim ASC");
            echo "<table><tr><th>NIM</th><th>Nama</th><th>Prodi</th><th>Alamat</th></tr>";
            while ($row = mysqli_fetch_array($result_reset)) {
                $nama = decryptFunc($row['nama'], $keyDecrypt);
                echo "<tr><td>" . $row['NIM'] . "</td>
                    <td>" . $nama . "</td>
                    <td>" . $row['prodi'] . "</td>
                    <td>" . $row['alamat'] . "</td>
                    <td><a href='data_update.php?id=" . $row['userID'] . "'>Edit </a><a href='data_remove.php?id=" . $row['userID'] . "'> Delete</a></td><tr>";
            }
            echo "</table>";
            exit;
        } else if (isset($_POST['reset']) && $_POST['reset']== "search") {
            $nama_search = $_POST['nama_search'];
            $nim_search = $_POST['nim_search'];
    
    
            if ($nama_search == "") {
                $sql_search = "SELECT u.userID, mhs.NIM, u.nama, mhs.prodi, u.alamat FROM users u INNER JOIN mahasiswa mhs ON u.userID = mhs.userID WHERE mhs.NIM LIKE ? AND mhs.deletedAt IS NULL";
                $stmt_search = mysqli_prepare($conn, $sql_search);
                $searchLikeNIM = "%" . $nim_search . "%";
                mysqli_stmt_bind_param($stmt_search, "s", $searchLikeNIM);
                mysqli_stmt_execute($stmt_search);
                $result_search = mysqli_stmt_get_result($stmt_search);
    
                echo "<table><tr><th>NIM</th><th>Nama</th><th>Prodi</th><th>Alamat</th></tr>";
                while ($row = mysqli_fetch_array($result_search)) {
                    $nama = decryptFunc($row['nama'], $keyDecrypt);
                    echo "<tr><td>" . $row['NIM'] . "</td>
                    <td>" . $nama . "</td>
                    <td>" . $row['prodi'] . "</td>
                    <td>" . $row['alamat'] . "</td>
                    <td><a href='data_update.php?id=" . $row['userID'] . "'>Edit </a><a href='data_remove.php?id=" . $row['userID'] . "'> Delete</a></td><tr>";
                }
                echo "</table>";
                exit;
    
            } else if ($nim_search == "") {
                $sqlSrc_nama = "SELECT u.*, m.* FROM users u INNER JOIN mahasiswa m on u.userID = m.userID WHERE m.deletedAt IS NULL";
                $result_search_nama = mysqli_query($conn, $sqlSrc_nama);
                echo "<table><tr><th>NIM</th><th>Nama</th><th>Prodi</th><th>Alamat</th></tr>";
                while ($row_nama_search = mysqli_fetch_array($result_search_nama)) {
                    $nama_search_decrypt = decryptFunc($row_nama_search['nama'], $keyDecrypt);
                    if (stripos(strtolower($nama_search_decrypt), strtolower($nama_search)) !== false) {
                        echo "<tr><td>" . $row_nama_search['NIM'] . "</td>
                        <td>" . $nama_search_decrypt . "</td>
                        <td>" . $row_nama_search['Prodi'] . "</td>
                        <td>" . $row_nama_search['alamat'] . "</td>
                        <td><a href='data_update.php?id=" . $row_nama_search['userID'] . "'>Edit </a><a href='data_remove.php?id=" . $row_nama_search['userID'] . "'> Delete</a></td><tr>";
                    }
                }
                echo "</table>";
                exit;
            }
    
        } else {
            $sql = "SELECT u.userID, mhs.NIM, u.nama, mhs.prodi, u.alamat FROM users u INNER JOIN mahasiswa mhs ON u.userID = mhs.userID WHERE mhs.deletedAt IS NULL ORDER BY mhs.nim ASC";
            $result = mysqli_query($conn, $sql);
            echo "<table><tr><th>NIM</th><th>Nama</th><th>Prodi</th><th>Alamat</th></tr>";
            while ($row = mysqli_fetch_array($result)) {
                $nama = decryptFunc($row['nama'], $keyDecrypt);
                echo "<tr><td>" . $row['NIM'] . "</td>
                    <td>" . $nama . "</td>
                    <td>" . $row['prodi'] . "</td>
                    <td>" . $row['alamat'] . "</td>
                    <td><a href='data_update.php?id=" . $row['userID'] . "'>Edit </a><a href='data_remove.php?id=" . $row['userID'] . "'> Delete</a></td><tr>";
            }
            echo "</table>";
            exit;
        }
    }
    ?>
    <!-- </div> -->
</body>

</html>