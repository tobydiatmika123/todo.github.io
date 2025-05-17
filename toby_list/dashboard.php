<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';

$sql = "SELECT * FROM todos WHERE user_id = ? AND title LIKE ? ORDER BY deadline ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $search);
$stmt->execute();
$result = $stmt->get_result();
$todos = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - TOBY_LIST</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">TOBY_LIST</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3"><?= htmlspecialchars($_SESSION['email']) ?></span>
            <a href="logout.php" class="btn btn-outline-light">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="mb-3">ToDo List</h2>

    <!-- Form cari -->
    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari todo..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            <button class="btn btn-outline-secondary" type="submit">Cari</button>
        </div>
    </form>

    <!-- Box luar pembungkus form tambah + daftar -->
    <div class="todo-wrapper-box">

        <!-- Form tambah task -->
        <form action="tambah_task.php" method="POST" class="mb-4">
            <div class="row g-2">
                <div class="col-md-5">
                    <input type="text" name="title" class="form-control" placeholder="Apa yang ingin anda kerjakan?" required>
                </div>
                <div class="col-md-4">
                    <input type="date" name="deadline" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-success w-100">Tambah List</button>
                </div>
            </div>
        </form>

        <?php if (count($todos) === 0): ?>
            <p class="text-muted">Belum ada list.</p>
        <?php else: ?>
            <div class="todo-list">
                <?php foreach ($todos as $todo): ?>
                    <div class="todo-box">
                        <div class="todo-header">
                            <strong><?= htmlspecialchars($todo['title']) ?></strong>
                            <?php if ($todo['status'] === 'pending' && $todo['deadline'] < date('Y-m-d')): ?>
                                <span class="badge bg-danger">Terlambat</span>
                            <?php endif; ?>
                        </div>
                        <div class="todo-info">
                            <span>Deadline: <?= htmlspecialchars($todo['deadline']) ?></span>
                            <span>Status:
                                <?php if ($todo['status'] === 'completed'): ?>
                                    <span class="badge bg-success">Selesai</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="todo-actions">
                            <?php if ($todo['status'] === 'pending'): ?>
                                <a href="centang_task.php?id=<?= $todo['id'] ?>" class="btn-action btn-outline-success">Centang</a>
                            <?php endif; ?>
                            <a href="edit_task.php?id=<?= $todo['id'] ?>" class="btn-action btn-outline-primary">Edit</a>
                            <a href="hapus_task.php?id=<?= $todo['id'] ?>" onclick="return confirm('Yakin hapus List ini?')" class="btn-action btn-outline-danger">Hapus</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div> <!-- /todo-wrapper-box -->
</div>
</body>
</html>
