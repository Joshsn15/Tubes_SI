<?php
    setcookie('userID', $row['userID'], time() - (86400 * 30), '/');
?>