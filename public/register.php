<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/config/db.php";
include $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php";
$page = basename($_SERVER['PHP_SELF']);

$stmt = $conn->prepare(
    "INSERT INTO site_stats (page, visits) VALUES (?,1)
     ON DUPLICATE KEY UPDATE visits = visits + 1"
);
$stmt->bind_param("s", $page);
$stmt->execute();
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $password);
    $stmt->execute();

    header("Location: login.php");
}
?>

<form method="POST">
    <input type="text" name="name" placeholder="Nume" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Parolă" required>
    <button name="register">Înregistrare</button>
</form>
<?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>
