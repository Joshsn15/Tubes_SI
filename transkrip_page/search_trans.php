<?php
include '../Database/connection.php';

$id = '2391ba08-0376-4795-98c2-6c3b2ede5d63';
$name = $_GET['name'];
$nim = $_GET['nim'];
$reset = $_GET['reset'];

if($reset == 'reset') {
    $result = mysqli_query($conn, "SELECT NIM, nama FROM mahasiswa m JOIN users u ON m.userID = u.userID");
} else if($nim == "") {
    $result = mysqli_query($conn, "SELECT nama FROM users WHERE NIM LIKE '$nim%'");
} else if($name == "") {
    $result = mysqli_query($conn, "SELECT NIM FROM mahasiswa WHERE nama LIKE '$name%'");
} else {
    $result = mysqli_query($conn, "SELECT NIM, nama FROM mahasiswa m JOIN users u ON m.userID = u.userID WHERE nama LIKE '$name%' AND NIM LIKE '$nim%'");
}

echo "<tr> <th>NIM </th> <th> Nama </th> <th> Aksi </th> </tr>";
    while ($row = mysqli_fetch_array($result)) {
        echo "<td>" . $row['NIM'] . "</td>";
        echo "<td>" . $row['nama'] . "</td>";
        echo "<td><a href='update.php?userID=" . $id . "'>update</a></td>";
    }
    echo "<table>";


?>