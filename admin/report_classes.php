<?php
include $_SERVER['DOCUMENT_ROOT'] ."/includes/auth.php";
include $_SERVER['DOCUMENT_ROOT'] ."/config/db.php";
require $_SERVER['DOCUMENT_ROOT'] ."/fpdf/fpdf.php";

if ($_SESSION['role'] != 'admin') {
    die("Acces interzis");
}

$pdf = new FPDF();        //genereaza pdf dinamic
$pdf->AddPage();
$pdf->SetFont("Arial", "B", 16);

$pdf->Cell(0, 10, "Raport Clase Fitness", 0, 1, "C");         //latimea, inaltimea, text, border, newline, aliniere
$pdf->Ln(5);         //spatiu vertical

$pdf->SetFont("Arial", "", 12);

$result = $conn->query("SELECT * FROM classes");
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(0, 8, $row['name'] . " - " . $row['description'], 0, 1);
}

$pdf->Output();
?>
