<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $title = trim($_POST['title']);
    $deadline = $_POST['deadline'];
    $user_id = $_SESSION['user_id'];

    if (!empty($title) && !empty($deadline)) {
        $sql = "UPDATE todos SET title = ?, deadline = ? WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $title, $deadline, $id, $user_id);
        $stmt->execute();
    }
}

header("Location: dashboard.php");
exit;
