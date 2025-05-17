<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - TOBY_LIST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
    <h3 class="text-center mb-3">Login</h3>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
    <form action="proses_login.php" method="POST">
        <div class="mb-3 text-start">
            <label for="email" class="form-label text-dark">Email</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan email" required>
        </div>
        <div class="mb-3 text-start">
            <label for="password" class="form-label text-dark">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <!-- Link daftar -->
    <p class="mt-3 text-center">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</div>
</body>
</html>
