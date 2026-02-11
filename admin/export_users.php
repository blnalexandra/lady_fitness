<?php
include $_SERVER['DOCUMENT_ROOT'] . "/config/db.php";
include $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php";

if ($_SESSION['role'] != 'admin') {
    die("Acces interzis");
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="users.csv"');    //cand se acceseaza pagina se descarca fisierul csv 

$output = fopen("php://output", "w");

fputcsv($output, ['ID', 'Nume', 'Email', 'Rol']);     //transforma un array php in rand csv

$result = $conn->query("SELECT id, name, email, role FROM users");

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
exit;
