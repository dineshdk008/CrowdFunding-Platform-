<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = "project.php?id=" . ($_GET['id'] ?? 0);
    header("Location: login.php");
    exit();
}

require 'db.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT p.*, u.name AS owner FROM projects p JOIN users u ON p.user_id = u.id WHERE p.id = ?');
$stmt->execute([$id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);
$updates = $pdo->prepare('SELECT * FROM project_updates WHERE project_id = ? ORDER BY created_at DESC');
$updates->execute([$id]);
$updates_list = $updates->fetchAll(PDO::FETCH_ASSOC);
$contribs = $pdo->prepare('SELECT * FROM contributions WHERE project_id = ? ORDER BY created_at DESC LIMIT 12');
$contribs->execute([$id]);
$contrib_list = $contribs->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<main class="container">
    <div class="card" style="max-width:800px; margin:0 auto; text-align:center;">
        <?php if(!empty($project['image'])): ?>
            <div class="project-image" style="margin-bottom:12px;">
                <img src="crowdfund/uploads/<?=htmlspecialchars($project['image'])?>" alt="Project Image" style="width:100%; height:auto; border-radius:10px;">
            </div>
        <?php endif; ?>

        <h2><?=htmlspecialchars($project['title'])?></h2>
        <p><?=htmlspecialchars($project['description'])?></p>
        <p><strong>Goal:</strong> $<?=number_format($project['goal_amount'],2)?> | <strong>Raised:</strong> $<?=number_format($project['raised_amount'],2)?></p>
        <p><strong>By:</strong> <?=htmlspecialchars($project['owner'])?></p>

        <h3>Contribute</h3>
        <form method="post" action="donate_guest.php">
            <input type="hidden" name="project_id" value="<?=$project['id']?>">
            <label>Your Name <input name="donor_name" required></label>
            <label>Amount ($) <input type="number" name="amount" step="0.01" required></label>
            <div style="margin-top:8px">
                <input type="submit" class="btn-primary" value="Contribute">
            </div>
            <?php if(!empty($_SESSION['donate_error'])): ?>
                <p style="color:#b91c1c; font-weight:bold; margin-top:8px;">
                    <?=htmlspecialchars($_SESSION['donate_error'])?>
                </p>
                <?php unset($_SESSION['donate_error']); ?>
            <?php endif; ?>

            <?php if(!empty($_SESSION['donate_success'])): ?>
                <p style="color:#059669; font-weight:bold; margin-top:8px;">
                    <?=htmlspecialchars($_SESSION['donate_success'])?>
                </p>
                <?php unset($_SESSION['donate_success']); ?>
            <?php endif; ?>
        </form>

        <h3>Updates</h3>
        <?php foreach($updates_list as $u): ?>
            <div class="card" style="text-align:center; margin-top:12px;">
                <h4><?=htmlspecialchars($u['title'])?></h4>
                <p><?=htmlspecialchars($u['content'])?></p>
                <small><?=htmlspecialchars($u['created_at'])?></small>
            </div>
        <?php endforeach; ?>

        <h3 style="margin-top:16px;">Recent Contributions</h3>
        <?php foreach($contrib_list as $c): ?>
            <p><?=htmlspecialchars($c['donor_name'] ?? 'Anonymous')?> donated $<?=number_format($c['amount'],2)?> (<?=htmlspecialchars($c['status'])?>)</p>
        <?php endforeach; ?>
    </div>
</main>
