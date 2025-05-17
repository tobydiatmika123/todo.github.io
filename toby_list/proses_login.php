<?php
session_start();
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        header("Location: login.php?error=Email dan password wajib diisi");
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    if (!$stmt) {
        die("Query error: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            header("Location: dashboard.php");
            exit;
        } else {
            header("Location: login.php?error=Password salah");
            exit;
        }
    } else {
        header("Location: login.php?error=Email tidak ditemukan");
        exit;
    }
} else {
    header("Location: login.php?error=Akses tidak valid");
    exit;
}
