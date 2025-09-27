<?php
require 'db.php';
if(session_status() === PHP_SESSION_NONE) session_start();
if(empty($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$errors = [];
if($_SERVER['REQUEST_METHOD']==='POST'){
    $title = trim($_POST['title'] ?? '');
    $short_desc = trim($_POST['short_desc'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $goal = floatval($_POST['goal_amount'] ?? 0);
    $image = null;

    if(!$title) $errors[] = 'Title is required.';
    if(!$short_desc) $errors[] = 'Short description is required.';
    if(!$description) $errors[] = 'Full description is required.';
    if($goal <= 0) $errors[] = 'Goal must be greater than zero.';

    if(!empty($_FILES['image']['name'])){
        $uploadDir = __DIR__ . '/crowdfund/uploads/';
        if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $image = time().'_'.basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image);
    }

    if(empty($errors)){
        $stmt = $pdo->prepare('INSERT INTO projects (user_id,title,short_desc,description,goal_amount,image) VALUES (?,?,?,?,?,?)');
        $stmt->execute([$_SESSION['user_id'],$title,$short_desc,$description,$goal,$image]);
        header('Location: index.php'); exit;
    }
}

include 'header.php';
?>
<main class="container">
    <div class="card" style="max-width:800px; margin:0 auto;">
        <h2 style="text-align:center;">Create New Project</h2>
        <?php foreach($errors as $e) echo "<p style='color:#b91c1c'>".htmlspecialchars($e)."</p>"; ?>
        <form method="post" enctype="multipart/form-data">
            <label>Title <input name="title" required></label>
            <label>Short Description <input name="short_desc" required></label>
            <label>Full Description <textarea name="description" required></textarea></label>
            <label>Funding Goal ($) <input type="number" name="goal_amount" step="0.01" required></label>
            <label>Project Image <input type="file" name="image"></label>
            <div style="margin-top:12px;"><input type="submit" class="btn-primary" value="Create Project"></div>
        </form>
    </div>
</main>
