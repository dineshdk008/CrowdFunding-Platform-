<?php
require 'db.php';
if(session_status() === PHP_SESSION_NONE) session_start();
if(empty($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$errors = [];
$project_id = intval($_GET['project_id'] ?? 0);

if($_SERVER['REQUEST_METHOD']==='POST'){
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    if(!$title) $errors[] = 'Title required.';
    if(!$content) $errors[] = 'Content required.';

    if(empty($errors)){
        $stmt = $pdo->prepare('INSERT INTO project_updates (project_id, title, content) VALUES (?,?,?)');
        $stmt->execute([$project_id,$title,$content]);
        header('Location: project.php?id='.$project_id); exit;
    }
}

include 'header.php';
?>

<div class="page-wrapper">
  <main class="container">
    <div class="card" style="max-width:700px;margin:0 auto">
      <h2>Post Update</h2>
      <?php foreach($errors as $e) echo "<p style='color:#b91c1c'>".htmlspecialchars($e)."</p>"; ?>
      <form method="post">
        <label>Title <input name="title" required></label>
        <label>Content <textarea name="content" required></textarea></label>
        <div style="margin-top:12px"><input type="submit" value="Post Update"></div>
      </form>
    </div>
  </main>
</div>
