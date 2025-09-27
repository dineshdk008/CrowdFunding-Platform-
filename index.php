<?php
require 'db.php';
include 'header.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$stmt = $pdo->query("SELECT p.*, u.name AS owner FROM projects p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC LIMIT 12");
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="card">
    <h1 style="text-align:center">Explore campaigns</h1>
    <p class="small" style="text-align:center">Support creators — discover meaningful projects and back them.</p>
</section>

<?php if(empty($projects)): ?>
<div class="card">
    <h3 style="text-align:center">No projects yet!</h3>
    <p style="text-align:center">Be the first to <a href="create_project.php">create a project</a>.</p>
</div>
<?php endif; ?>
<section style="margin-top:16px">
    <div class="projects-grid">
        <?php foreach($projects as $p):
            $percent = $p['goal_amount'] > 0 ? min(100, round(($p['raised_amount']/$p['goal_amount'])*100)) : 0;
        ?>
        <div class="card project-card">
            <div class="project-image">
                <?php if(!empty($p['image'])): ?>
                    <img src="crowdfund/uploads/<?=htmlspecialchars($p['image'])?>" 
                         alt="<?=htmlspecialchars($p['title'])?>" 
                         style="width:100%; height:160px; object-fit:cover; border-radius:10px;">
                <?php else: ?>
                    <?=htmlspecialchars(substr($p['title'],0,30))?>
                <?php endif; ?>
            </div>
            <h3 style="margin-top:12px">
                <a href="project.php?id=<?=$p['id']?>" style="text-decoration:none; color:#0f172a;">
                    <?=htmlspecialchars($p['title'])?>
                </a>
            </h3>
            <p class="small"><?=htmlspecialchars($p['short_desc'])?></p>

            <div class="progress" aria-hidden="true">
                <i style="width:<?=$percent?>%"></i>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center">
                <div class="small">Raised: $<?=number_format($p['raised_amount'],2)?></div>
                <div class="small">Goal: $<?=number_format($p['goal_amount'],2)?></div>
            </div>

            <div style="margin-top:10px;display:flex;justify-content:space-between;align-items:center">
                <div class="small">By <?=htmlspecialchars($p['owner'])?></div>

                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="project.php?id=<?=$p['id']?>" style="text-decoration:none">
                        <button style="background-color:#4f46e5;color:#fff;padding:8px 16px;border:none;border-radius:6px;cursor:pointer;font-weight:bold;transition:0.3s ease"
                                onmouseover="this.style.backgroundColor='#3730a3'; this.style.transform='translateY(-2px)'"
                                onmouseout="this.style.backgroundColor='#4f46e5'; this.style.transform='translateY(0)'">
                            View
                        </button>
                    </a>
                <?php else: ?>
                    <a href="login.php" style="text-decoration:none">
                        <button style="background-color:#4f46e5;color:#fff;padding:8px 16px;border:none;border-radius:6px;cursor:pointer;font-weight:bold;transition:0.3s ease"
                                onmouseover="this.style.backgroundColor='#3730a3'; this.style.transform='translateY(-2px)'"
                                onmouseout="this.style.backgroundColor='#4f46e5'; this.style.transform='translateY(0)'">
                            View
                        </button>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
