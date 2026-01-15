<style> 
    h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 36px;
    color: #333;
}

.class-card {
    background-color: #e4dee3ff;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    margin-left: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    font-size: large;
}

.class-card h3 {
    margin-top: 0;
    font-size: 28px;
}

.class-card p {
    margin: 8px 0;
    line-height: 1.5;
    color: #555;
}

.class-card p strong {
    color: #111;
}

.class-card p[style*="color:green"] {
    font-weight: bold;
    color: green;
}

.class-card p[style*="color:red"] {
    font-weight: bold;
    color: red;
}

hr {
    border: none;
    border-top: 1px solid #ddd;
    margin: 20px 0;
}

</style>
<?php
include $_SERVER['DOCUMENT_ROOT'] . "/config/db.php";
include $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php";

$page = basename($_SERVER['PHP_SELF']);
$stmt = $conn->prepare("
    INSERT INTO site_stats (page, visits)
    VALUES (?, 1)
    ON DUPLICATE KEY UPDATE visits = visits + 1
");
$stmt->bind_param("s", $page);
$stmt->execute();


if (
    isset($_POST['register']) &&
    isset($_SESSION['user_id']) &&
    $_SESSION['role'] === 'client'
) {
    $class_id = (int)$_POST['class_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT capacity FROM classes WHERE id = ?");
    $stmt->bind_param("i", $class_id);
    $stmt->execute();
    $stmt->bind_result($capacity);
    $stmt->fetch();
    $stmt->close();

    if ($capacity > 0) {
        $stmt = $conn->prepare("
            INSERT INTO class_registrations (class_id, user_id)
            VALUES (?, ?)
        ");

        if ($stmt->bind_param("ii", $class_id, $user_id) && $stmt->execute()) {
            $stmt2 = $conn->prepare("
                UPDATE classes
                SET capacity = capacity - 1
                WHERE id = ?
            ");
            $stmt2->bind_param("i", $class_id);
            $stmt2->execute();
        }
    }
}
?>

<h2>Clase disponibile</h2>

<?php
$result = $conn->query("
    SELECT *
    FROM classes
    ORDER BY class_date, class_time
");

while ($row = $result->fetch_assoc()) {

    $class_id = $row['id'];

    $isRegistered = false;
    if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'client') {
        $stmt = $conn->prepare("
            SELECT id FROM class_registrations
            WHERE class_id = ? AND user_id = ?
        ");
        $stmt->bind_param("ii", $class_id, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->store_result();
        $isRegistered = $stmt->num_rows > 0;
        $stmt->close();
    }

    echo "<div class='class-card'>";
    echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
    echo "<p><strong>Antrenor:</strong> " . htmlspecialchars($row['trainer_name']) . "</p>";
    echo "<p><strong>Data:</strong> {$row['class_date']} | <strong>Ora:</strong> {$row['class_time']}</p>";
    echo "<p>" . htmlspecialchars($row['description']) . "</p>";
    echo "<p><strong>Locuri disponibile:</strong> {$row['capacity']}</p>";

    if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'client') {

        if ($isRegistered) {
            echo "<p style='color:green;font-weight:bold'>✔ Ești înscrisă</p>";
        } elseif ($row['capacity'] > 0) {
            echo "
            <form method='POST'>
                <input type='hidden' name='class_id' value='{$class_id}'>
                <button type='submit' name='register'>Înscrie-te</button>
            </form>";
        } else {
            echo "<p style='color:red;font-weight:bold'>Clasa este completă</p>";
        }
    }

    echo "</div><hr>";
}
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>
