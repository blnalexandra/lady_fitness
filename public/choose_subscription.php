<style>
h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 36px;
    color: #333;
}

form {
    max-width: 400px;
    margin: 0 auto;
    background-color: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 6px 12px rgba(0,0,0,0.1);
}

form label {
    display: block;
    margin-bottom: 15px;
    font-size: 18px;
    cursor: pointer;
}

form button {
    color: #fff;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
    transition: background-color 0.2s;
}

.success-message {
    text-align: center;
    margin-top: 15px;
    font-weight: bold;
    color: green;
}
</style>
<?php
session_start();
include "/config/db.php";
include "/includes/header.php";

if (!isset($_SESSION['user_id'])) {
    die("Trebuie să fii autentificat.");
}

$result = $conn->query("SELECT DISTINCT type, description FROM subscriptions WHERE user_id IS NULL");
$subscriptions = [];
while ($row = $result->fetch_assoc()) {
    $subscriptions[] = $row;
}
?>

<h2>Alege abonamentul</h2>

<form method="POST">
    <?php foreach ($subscriptions as $sub): ?>
        <label>
            <input type="radio" name="type" value="<?php echo htmlspecialchars($sub['type']); ?>" required>
            <?php echo htmlspecialchars($sub['type']); ?>
        </label>
        <p style="margin-left:20px;"><?php echo nl2br(htmlspecialchars($sub['description'])); ?></p>
    <?php endforeach; ?>

    <button type="submit" name="subscribe">Activează</button>
</form>

<?php
if (isset($_POST['subscribe'])) {
    $type = $_POST['type'];
    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM subscriptions WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();
    
    $stmt = $conn->prepare("SELECT description FROM subscriptions WHERE type = ? AND user_id IS NULL LIMIT 1");
    $stmt->bind_param("s", $type);
    $stmt->execute();
    $stmt->bind_result($description);
    $stmt->fetch();
    $stmt->close();

    
    $stmt = $conn->prepare("INSERT INTO subscriptions (user_id, type, description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userId, $type, $description);
    $stmt->execute();
    $stmt->close();
    

    $stmt = $conn->prepare("SELECT type, description FROM subscriptions WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($dbType, $dbDesc);
    $stmt->fetch();
    $stmt->close();
    
    $_SESSION['subscription'] = $type;
    
    echo "<p class='success-message'>Abonament activat cu succes!</p>";
}

?>

<?php include "/includes/footer.php"; ?>

