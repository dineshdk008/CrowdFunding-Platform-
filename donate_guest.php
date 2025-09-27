<?php
require 'db.php';
session_start();

if($_SERVER['REQUEST_METHOD']==='POST'){
    $project_id = intval($_POST['project_id'] ?? 0);
    $donor_name = trim($_POST['donor_name'] ?? 'Anonymous');
    $amount = floatval($_POST['amount'] ?? 0);

    $stmt = $pdo->prepare('SELECT goal_amount, raised_amount FROM projects WHERE id = ?');
    $stmt->execute([$project_id]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);

    if($amount > 0){
        if(($project['raised_amount'] + $amount) > $project['goal_amount']){
            $_SESSION['donate_error'] = "Contribution exceeds project goal!";
        } else {
            $stmt = $pdo->prepare('INSERT INTO contributions (project_id, donor_name, amount) VALUES (?,?,?)');
            $stmt->execute([$project_id,$donor_name,$amount]);
            $update = $pdo->prepare('UPDATE projects SET raised_amount = raised_amount + ? WHERE id = ?');
            $update->execute([$amount,$project_id]);

            $_SESSION['donate_success'] = "Thank you for your contribution!";
        }
    } else {
        $_SESSION['donate_error'] = "Amount must be greater than zero!";
    }

    header('Location: project.php?id='.$project_id);
    exit;
}
