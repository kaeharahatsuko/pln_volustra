<?php
include 'db.php';

$users = [
  ['username' => 'admin', 'password' => 'admin123', 'role' => 'admin'],
  ['username' => 'user1', 'password' => 'user123', 'role' => 'user'],
];

foreach ($users as $user) {
  $username = $user['username'];
  $password_hash = password_hash($user['password'], PASSWORD_DEFAULT);
  $role = $user['role'];

  $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
  if (mysqli_num_rows($cek) > 0) {
    // update password dan role jika user sudah ada
    mysqli_query($conn, "UPDATE users SET password='$password_hash', role='$role' WHERE username='$username'");
  } else {
    // insert user baru
    mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('$username', '$password_hash', '$role')");
  }
}
echo "User dan admin sudah siap dipakai.";
