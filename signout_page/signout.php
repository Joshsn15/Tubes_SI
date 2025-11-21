<?php
    setcookie('userID', "", time() - (86400 * 30), '/');
    header("Location: ../login_page/login-form.php");
?>