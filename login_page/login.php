<?php
    session_start();
    if (isset($_POST['user_id']) && isset($_POST['password'])) {
        $user_id = $_POST['user_id'];
        $password = $_POST['password'];
        $_SESSION['nama'] = "Josh";
        
        include "../connection.php";

        // Lakukan pengecekan login di sini
        $sqlMahasiswa = "SELECT m.*, u.* FROM mahasiswa m INNER JOIN users u ON m.MahasiswaID = u.userID WHERE m.NIM = $user_id";
        $resultMahasiswa = mysqli_query($conn, $sqlMahasiswa);

        $sqlAdmin = "SELECT a.*, u.* FROM admin a INNER JOIN users u ON a.AdminID = u.userID WHERE a.NIK = $user_id";
        $resultAdmin = mysqli_query($conn, $sqlAdmin);

        $isSuccess = false;
        if (mysqli_num_rows($resultMahasiswa) > 0) {
            $row = mysqli_fetch_assoc($resultMahasiswa);
            setcookie('user_id', $row['userID']);
            $_SESSION['nama'] = $row['nama'];
            $isSuccess = true;
        } else if (mysqli_num_rows($resultAdmin) > 0) {
            $row = mysqli_fetch_assoc($resultAdmin);
            setcookie('user_id', $row['user_id']);
            $_SESSION['nama'] = $row['Nama_Admin'];
            $isSuccess = true;
        }
        
        $nama = $_SESSION['nama'];
        
        if($isSuccess) {
            echo "<script>
                    alert('Selamat datang $nama');
                    window.location.href = '../index.php';
                </script>";
        } else {
            echo "<script>
                    alert('ID atau password salah');
                    window.location.href = 'login-form.php';
                </script>";
        }
    }
?>