<?php
    session_start();
    include "../connection.php";
    if (isset($_POST['user_id']) && isset($_POST['password'])) {
        $user_id = $_POST['user_id'];
        $password = $_POST['password'];

        $sqlMahasiswa = "SELECT m.*, u.* FROM mahasiswa m INNER JOIN users u ON m.userID = u.userID WHERE m.NIM = ?";
        $stmtMahasiswa = mysqli_prepare($conn, $sqlMahasiswa);
        mysqli_stmt_bind_param($stmtMahasiswa, "s", $user_id);
        mysqli_stmt_execute($stmtMahasiswa);
        $resultMahasiswa = mysqli_stmt_get_result($stmtMahasiswa);

        $sqlAdmin = "SELECT a.*, u.* FROM admin a INNER JOIN users u ON a.userID = u.userID WHERE a.NIK = ?";
        $stmtAdmin = mysqli_prepare($conn, $sqlAdmin);
        mysqli_stmt_bind_param($stmtAdmin, "s", $user_id);
        mysqli_stmt_execute($stmtAdmin);
        $resultAdmin = mysqli_stmt_get_result($stmtAdmin);

        $isSuccess = false;
        if (mysqli_num_rows($resultMahasiswa) > 0) {
            $row = mysqli_fetch_assoc($resultMahasiswa);
            if(password_verify($password, $row['password'])) {
                setcookie('userID', $row['userID']);
                $_SESSION['nama'] = $row['nama'];
                $isSuccess = true;
            }
        } else if (mysqli_num_rows($resultAdmin) > 0) {
            $row = mysqli_fetch_assoc($resultAdmin);
            if(password_verify($password, $row['password'])) {
                setcookie('userID', $row['userID']);
                $_SESSION['nama'] = $row['nama'];
                $isSuccess = true;
            }
        }
        
        
        $nama = $row['nama'];
        if($isSuccess) {
            echo "<script>
                    alert('Selamat datang $nama');
                    window.location.href = '../index.php';
                </script>";
        } else {
            echo "<script>
                    alert('ID atau password $user_id atau $nama atau $password salah');
                    window.location.href = 'login-form.php';
                </script>";
        }
        
    }
?>