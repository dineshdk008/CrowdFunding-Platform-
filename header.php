<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Crowdfund — Build & Support</title>
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<header class="site-header">
  <div class="header-inner">
    <a class="brand" href="index.php">
      <img src="assets/logo.jpg" alt="logo" class="logo" onerror="this.style.display='none'">
      <span class="brand-text">Crowdfund</span>
    </a>
    <nav class="nav" role="navigation" aria-label="Main navigation">
      <a href="index.php">Home</a>

      <?php if (!empty($_SESSION['user_id'])): ?>
        <a href="create_project.php">Create Project</a>
        <span class="nav-welcome">Hello, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
        <a href="logout.php" class="btn-ghost">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php" class="btn">Sign up</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">
