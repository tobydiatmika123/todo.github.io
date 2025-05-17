<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM todos WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$todo = $result->fetch_assoc();

if (!$todo) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <h3>Edit Task</h3>
        <form action="proses_edit.php" method="POST">
            <input type="hidden" name="id" value="<?= $todo['id'] ?>">
            <div class="mb-3">
                <label>Judul</label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($todo['title']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Deadline</label>
                <input type="date" name="deadline" class="form-control" value="<?= $todo['deadline'] ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="dashboard.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>
