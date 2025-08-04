<?php
require('../fpdf/fpdf.php');
include '../koneksi.php';

if (!isset($_GET['guru_id'])) {
    die("Parameter guru_id tidak ditemukan.");
}

$guru_id = $_GET['guru_id'];

$query = "
    SELECT guru.nama, guru.rekening, guru.gaji_per_jam, 
           SUM(ABS(TIMESTAMPDIFF(HOUR, jam_masuk, jam_keluar))) as total_jam,
           (SUM(ABS(TIMESTAMPDIFF(HOUR, jam_masuk, jam_keluar))) * guru.gaji_per_jam) as total_gaji
    FROM absensi 
    INNER JOIN guru ON absensi.guru_id = guru.id
    WHERE guru.id = '$guru_id'
    GROUP BY guru.id
";

$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);

// Logo (pastikan ada file img/logo.png)
$pdf->Image('../img/logo.png',10,10,30);
$pdf->Cell(80);
$pdf->Cell(30,10,'Slip Gaji Guru',0,1,'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(50,10,'Nama Guru',0,0);
$pdf->Cell(80,10,': ' . $data['nama'],0,1);
$pdf->Cell(50,10,'No Rekening',0,0);
$pdf->Cell(80,10,': ' . $data['rekening'],0,1);
$pdf->Cell(50,10,'Total Jam',0,0);
$pdf->Cell(80,10,': ' . $data['total_jam'],0,1);
$pdf->Cell(50,10,'Gaji per Jam',0,0);
$pdf->Cell(80,10,': Rp ' . number_format($data['gaji_per_jam'], 0, ',', '.'),0,1);
$pdf->Cell(50,10,'Total Gaji',0,0);
$pdf->Cell(80,10,': Rp ' . number_format($data['total_gaji'], 0, ',', '.'),0,1);

$pdf->Ln(20);
$pdf->Cell(0,10,'Tanggal Cetak: ' . date('d-m-Y'),0,1,'R');
$pdf->Output();
