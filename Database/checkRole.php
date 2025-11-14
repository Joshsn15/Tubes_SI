<?php

function checkRoleByCookie()
{
    // $_COOKIE['userID'] = "2e95359a-eaf4-4e16-8ba5-2b22aa9a80c9";

    if (!isset($_COOKIE['userID'])) {
        return false;
    }
    $useridCookie = $_COOKIE['userID'];

    global $conn;
    $sql = "SELECT user_role FROM users WHERE userID = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($conn));
        return false;
    }

    $stmt->bind_param("s", $useridCookie);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row["user_role"] == "Admin") {
            return true;
        } else {
            false;
        }
    }

    $stmt->close();
    return false;
}
?>