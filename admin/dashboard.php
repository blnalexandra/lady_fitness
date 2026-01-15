<?php
include "/includes/auth.php";
include "/config/db.php";
include "/includes/header.php";

if ($_SESSION['role'] != 'admin') die("Acces interzis");
?>

<h2>Statistici site</h2>

<?php
$result = $conn->query("SELECT * FROM site_stats");
while ($row = $result->fetch_assoc()) {
    echo $row['page'] . " - " . $row['visits'] . " vizite<br>";
}
?>
<?php include "/includes/footer.php"; ?>