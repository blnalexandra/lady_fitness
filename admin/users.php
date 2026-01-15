<?php
include $_SERVER['DOCUMENT_ROOT'] ."/includes/auth.php";
include $_SERVER['DOCUMENT_ROOT'] ."/config/db.php";
include $_SERVER['DOCUMENT_ROOT'] ."/includes/header.php";

if ($_SESSION['role'] != 'admin') {
    die("Acces interzis");
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    if ($id != $_SESSION['user_id']) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}

if (isset($_POST['update_role'])) {
    $id = $_POST['user_id'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET role=? WHERE id=?");
    $stmt->bind_param("si", $role, $id);
    $stmt->execute();
}
?>

<h2>Administrare utilizatori</h2>

<table border="1" cellpadding="5">
<tr>
    <th>ID</th>
    <th>Nume</th>
    <th>Email</th>
    <th>Rol</th>
    <th>Acțiuni</th>
</tr>

<?php
$result = $conn->query("SELECT id, name, email, role FROM users");

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
    echo "<td>
            <form method='POST' style='display:inline'>
                <input type='hidden' name='user_id' value='{$row['id']}'>
                <select name='role'>
                    <option value='admin' " . ($row['role']=='admin'?'selected':'') . ">admin</option>
                    <option value='client' " . ($row['role']=='client'?'selected':'') . ">client</option>
                </select>
                <button name='update_role'>Salvează</button>
            </form>
          </td>";

    echo "<td>";
    if ($row['id'] != $_SESSION['user_id']) {
        echo "<a href='users.php?delete={$row['id']}'
              onclick=\"return confirm('Sigur ștergi utilizatorul?')\">
              Șterge</a>";
    } else {
        echo "—";
    }
    echo "</td>";
    echo "</tr>";
}
?>
</table>
<?php include $_SERVER['DOCUMENT_ROOT'] ."/includes/footer.php"; ?>
