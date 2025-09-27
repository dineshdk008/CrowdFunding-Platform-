<?php
require 'db.php';
if(session_status() === PHP_SESSION_NONE) session_start();

$errors = [];

if($_SERVER['REQUEST_METHOD']==='POST'){
    $name = trim($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if(!$name) $errors[] = 'Name is required.';
    if(!$email) $errors[] = 'Valid email is required.';
    if(strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';

    if(empty($errors)){
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if($stmt->fetch()){
            $errors[] = 'Email already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (name,email,password) VALUES (?,?,?)');
            $stmt->execute([$name,$email,$hash]);
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['user_name'] = $name;
            header('Location: index.php'); exit;
        }
    }
}

include 'header.php';
?>

<div class="card" style="max-width:600px;margin:0 auto">
    <h2 style="text-align:center">Create your account</h2>

    <?php foreach($errors as $e) echo "<p style='color:#b91c1c'>".htmlspecialchars($e)."</p>"; ?>

    <form method="post">
        <label>Name
            <input name="name" required>
        </label>
        <label>Email
            <input name="email" type="email" required>
        </label>
        <label>Password
            <input name="password" type="password" required>
        </label>

        <input type="submit" value="Sign up" class="btn-ghost" />

        <p class="register-link" style="text-align:center;margin-top:12px;">
            Already have an account? <a href="login.php">Login</a>
        </p>
    </form>
</div>
