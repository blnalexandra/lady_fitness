<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Sala Fitness Femei</title>
     <meta name="description" content="Trimite-ne mesajul tău sau întreabă despre abonamentele noastre doar pentru femei.">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<header>
    <h1>Lady Fitness</h1>
    <nav>
    <a href="/index.php">Acasă</a>
    <a href="/public/classes.php">Clase</a>
    <a href="/public/fitness_tips.php">Sfaturi</a>
    <a href="/public/contact.php">Contact</a>
    <a href="/public/about.php">Despre</a>

    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="/public/logout.php">Logout</a>
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="/admin/dashboard.php">Dashboard</a>
            <a href="/admin/classes.php">Management Clase</a>
            <a href="/admin/users.php">Utilizatori</a>
            <a href="/admin/report_classes.php">Raport PDF</a>
            <a href="/admin/export_users.php">Export CSV</a>

        <?php else: ?>
            <a href="/public/choose_subscription.php">Abonament</a>
            <p style="font-family:fantasy; font-size:25px;"> Status: 
            <strong><b><?php echo $_SESSION['subscription'] ?? 'Fără abonament'; ?></b></strong></p>
        <?php endif; ?>


    <?php else: ?>

        <a href="/public/login.php">Login</a>
        <a href="/public/register.php">Sign In</a>

    <?php endif; ?>
</nav>

</header>


