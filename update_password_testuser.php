<?php
include 'dp.php';

$new_password = 'tes123';
$hash = password_hash($new_password, PASSWORD_DEFAULT);

$sql = "UPDATE users SET password='$hash' WHERE username='testuser'";
if (mysqli_query($conn, $sql)) {
    echo "Password testuser berhasil diupdate.";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
