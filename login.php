<?php
require 'db.php';
if(session_status() === PHP_SESSION_NONE) session_start();
$errors = [];

if($_SERVER['REQUEST_METHOD']==='POST'){
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if(!$email) $errors[] = 'Valid email is required.';
    if(!$password) $errors[] = 'Password is required.';

    if(empty($errors)){
        $stmt = $pdo->prepare('SELECT id, name, password FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($password, $user['password'])){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: index.php'); exit;
        } else {
            $errors[] = 'Invalid email or password.';
        }
    }
}
include 'header.php';
?>
<div class="card" style="max-width:600px;margin:40px auto">
  <h2 style="text-align:center">Login</h2>
  <?php foreach($errors as $e) echo "<p style='color:#b91c1c'>".htmlspecialchars($e)."</p>"; ?>

  <form method="post">
    <label>Email
      <input name="email" type="email" required>
    </label>

    <label>Password
      <input name="password" type="password" required>
    </label>

    <input type="submit" value="Login" class="btn-primary" />

    <p class="register-link" style="text-align:center">
      Haven’t signed up yet? <a href="register.php">Register here </a>
    </p>
  </form>
</div>
