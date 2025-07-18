<?php
include 'db.php';

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$users = [
    ['username' => 'admin', 'password' => 'admin2025', 'role' => 'admin'],
    ['username' => 'userpelanggan', 'password' => 'user2025', 'role' => 'user'],
    ['username' => 'sawal', 'password' => 'dps0712', 'role' => 'user']
];

foreach ($users as $user) {
    $username = mysqli_real_escape_string($conn, $user['username']);
    $password_hash = password_hash($user['password'], PASSWORD_DEFAULT);
    $role = mysqli_real_escape_string($conn, $user['role']);

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'") or die("Error cek user: ".mysqli_error($conn));

    if (mysqli_num_rows($result) > 0) {
        $update = mysqli_query($conn, "UPDATE users SET password = '$password_hash', role = '$role' WHERE username = '$username'") or die("Error update user: ".mysqli_error($conn));
        echo "User '$username' diupdate.<br>";
    } else {
        $insert = mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('$username', '$password_hash', '$role')") or die("Error insert user: ".mysqli_error($conn));
        echo "User '$username' ditambahkan.<br>";
    }
}

echo "Setup user selesai.";
?>
