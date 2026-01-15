<?php
include $_SERVER['DOCUMENT_ROOT'] . "/includes/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/config/db.php";
include $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php";

if ($_SESSION['role'] !== 'admin') {
    die("Acces interzis");
}

if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $trainer_name = $_POST['trainer_name'];
    $description = $_POST['description'];
    $class_date = $_POST['class_date'];
    $class_time = $_POST['class_time'];
    $capacity = $_POST['capacity'];

    $stmt = $conn->prepare("
        INSERT INTO classes 
        (name, trainer_name, description, class_date, class_time, capacity)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "sssssi",
        $name,
        $trainer_name,
        $description,
        $class_date,
        $class_time,
        $capacity
    );
    $stmt->execute();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM classes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
?>

<h2>Admin – Clase fitness</h2>

<form method="POST">
    <input type="text" name="name" placeholder="Nume clasă" required><br>

    <input type="text" name="trainer_name" placeholder="Nume antrenor" required><br>

    <textarea name="description" placeholder="Descriere clasă"></textarea><br>

    <label>Data:</label><br>
    <input type="date" name="class_date" required><br>

    <label>Ora:</label><br>
    <input type="time" name="class_time" required><br>

    <input type="number" name="capacity" placeholder="Capacitate" min="1" required><br>

    <button type="submit" name="add">Adaugă clasă</button>
</form>

<hr>

<h3>Clase existente</h3>

<?php
$result = $conn->query("
    SELECT id, name, trainer_name, class_date, class_time, capacity
    FROM classes
    ORDER BY class_date, class_time
");

while ($row = $result->fetch_assoc()) {
    echo "<p>
        <strong>{$row['name']}</strong> –
        Antrenor: {$row['trainer_name']} |
        {$row['class_date']} {$row['class_time']} |
        Capacitate: {$row['capacity']}
        <a href='classes.php?delete={$row['id']}'
           onclick=\"return confirm('Sigur ștergi clasa?')\">
           Șterge
        </a>
    </p>";
}
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>

