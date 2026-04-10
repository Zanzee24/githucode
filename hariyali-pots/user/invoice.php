<?php
require_once __DIR__ . '/../core/auth.php';
require_once __DIR__ . '/../models/Order.php';
require_login();

$order = (new Order())->findWithItems((int)($_GET['id'] ?? 0));
if (!$order) {
    exit('Order not found');
}

$fpdfPath = __DIR__ . '/../vendor/fpdf/fpdf.php';
if (!file_exists($fpdfPath)) {
    header('Content-Type: text/plain');
    echo "FPDF not found. Place fpdf.php at vendor/fpdf/fpdf.php";
    exit;
}

require_once $fpdfPath;
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Hariyali Pots Invoice', 0, 1);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, 'Order ID: #' . $order['id'], 0, 1);
$pdf->Cell(0, 8, 'Customer: ' . $order['name'] . ' (' . $order['email'] . ')', 0, 1);
$pdf->MultiCell(0, 8, 'Address: ' . $order['address']);
$pdf->Ln(2);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(90, 8, 'Product', 1);
$pdf->Cell(30, 8, 'Qty', 1);
$pdf->Cell(40, 8, 'Price', 1);
$pdf->Cell(30, 8, 'Total', 1, 1);
$pdf->SetFont('Arial', '', 11);

foreach ($order['items'] as $item) {
    $line = $item['quantity'] * $item['price'];
    $pdf->Cell(90, 8, $item['name'], 1);
    $pdf->Cell(30, 8, $item['quantity'], 1);
    $pdf->Cell(40, 8, 'Rs ' . number_format((float)$item['price'], 2), 1);
    $pdf->Cell(30, 8, 'Rs ' . number_format((float)$line, 2), 1, 1);
}

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Grand Total: Rs ' . number_format((float)$order['total_price'], 2), 0, 1);
$pdf->Output('D', 'invoice-' . $order['id'] . '.pdf');
