<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $deadline = $_POST['deadline'];
    $user_id = $_SESSION['user_id'];

    if (empty($title) || empty($deadline)) {
        header("Location: dashboard.php?error=Judul dan deadline wajib diisi");
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO todos (user_id, title, deadline, status) VALUES (?, ?, ?, 'pending')");
    $stmt->bind_param("iss", $user_id, $title, $deadline);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit;
    } else {
        die("Gagal menambahkan task: " . $conn->error);
    }
} else {
    header("Location: dashboard.php");
    exit;
}
