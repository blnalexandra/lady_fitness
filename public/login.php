<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/config/db.php";
include $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        $stmt = $conn->prepare("
    SELECT `type`
    FROM subscriptions 
    WHERE user_id = ?
    ORDER BY id DESC
    LIMIT 1
");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$resSub = $stmt->get_result();
$subscription = $resSub->fetch_assoc();

if ($subscription) {
    $_SESSION['subscription'] = $subscription['type'];
} else {
    $_SESSION['subscription'] = 'Fără abonament';
}


        header("Location: /index.php");
        exit;

    } else {
        $error = "Email sau parolă incorecte!";
    }
}
?>

<h2>Autentificare</h2>

<?php if ($error): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST">
    <label>Email</label>
    <input type="email" name="email" required>

    <label>Parolă</label>
    <input type="password" name="password" required>

    <button type="submit">Login</button>
</form>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>

