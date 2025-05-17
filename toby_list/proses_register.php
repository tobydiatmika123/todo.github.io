<?php
session_start();
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        header("Location: register.php?error=Email dan password wajib diisi");
        exit;
    }

    // Cek apakah email sudah terdaftar
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: register.php?error=Email sudah terdaftar");
        exit;
    }

    // Tanpa hash (langsung simpan password asli)
    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $password);

    if ($stmt->execute()) {
        header("Location: register.php?success=Berhasil daftar, silakan login");
        exit;
    } else {
        header("Location: register.php?error=Gagal mendaftar, coba lagi.");
        exit;
    }

    $stmt->close();
} else {
    header("Location: register.php");
    exit;
}

