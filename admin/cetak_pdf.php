<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    echo "Akses ditolak. Silakan login.";
    exit;
}

require('../fpdf/fpdf.php');
include '../koneksi.php'; // pastikan pakai $conn

// Ambil parameter ID guru
$guru_id = $_GET['guru_id'] ?? '';

if ($guru_id == '' || !is_numeric($guru_id)) {
    echo "Data guru tidak valid.";
    exit;
}

// Ambil data guru
$guruQuery = mysqli_query($conn, "SELECT * FROM guru WHERE id = $guru_id");
$guru = mysqli_fetch_assoc($guruQuery);

if (!$guru) {
    echo "Guru tidak ditemukan.";
    exit;
}

$gaji_per_jam = $guru['gaji_per_jam'];
$nama = $guru['nama'];

// Ambil data sesi (absensi)
$absenQuery = mysqli_query($conn, "
    SELECT tanggal, kelas, jumlah_jam 
    FROM absensi 
    WHERE guru_id = $guru_id 
    ORDER BY tanggal ASC
");

$data_sesi = [];
$total_jam = 0;
$total_gaji = 0;

while ($row = mysqli_fetch_assoc($absenQuery)) {
    $durasi = $row['jumlah_jam'];
    $gaji = $durasi * $gaji_per_jam;

    $data_sesi[] = [
        'tanggal' => $row['tanggal'],
        'kelas' => $row['kelas'],
        'durasi' => $durasi,
        'gaji' => $gaji
    ];

    $total_jam += $durasi;
    $total_gaji += $gaji;
}

// --- CETAK PDF ---
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetTitle("Slip Gaji - {$nama}");

// Logo
$pdf->Image('../img/sanigooke2.png', 10, 10, 30); // logo di folder ../img
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Slip Gaji Guru - Sakura Nihongo', 0, 1, 'C');
$pdf->Ln(20);

// Informasi Guru
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(33, 33, 33);
$pdf->Cell(50, 10, 'Nama', 0, 0);
$pdf->Cell(70, 10, ': ' . $nama, 0, 1);
$pdf->Cell(50, 10, 'No. Rekening', 0, 0);
$pdf->Cell(70, 10, ': ' . $guru['rekening'], 0, 1);
$pdf->Cell(50, 10, 'Gaji per Jam', 0, 0);
$pdf->Cell(70, 10, ': Rp ' . number_format($gaji_per_jam, 0, ',', '.'), 0, 1);

// Garis pemisah
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0);
$pdf->Cell(0, 10, 'Detail Sesi Mengajar:', 0, 1);

// Header Tabel
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(35, 10, 'Tanggal', 1);
$pdf->Cell(60, 10, 'Kelas', 1);
$pdf->Cell(30, 10, 'Durasi (jam)', 1);
$pdf->Cell(50, 10, 'Gaji', 1);
$pdf->Ln();

// Tabel Data
$pdf->SetFont('Arial', '', 11);
foreach ($data_sesi as $sesi) {
    $pdf->Cell(35, 10, date('d-m-Y', strtotime($sesi['tanggal'])), 1);
    $pdf->Cell(60, 10, $sesi['kelas'], 1);
    $pdf->Cell(30, 10, number_format($sesi['durasi'], 2), 1);
    $pdf->Cell(50, 10, 'Rp ' . number_format($sesi['gaji'], 0, ',', '.'), 1);
    $pdf->Ln();
}

// Total Baris
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(95, 10, 'TOTAL', 1);
$pdf->Cell(30, 10, number_format($total_jam, 2) . ' jam', 1);
$pdf->Cell(50, 10, 'Rp ' . number_format($total_gaji, 0, ',', '.'), 1);

// Footer
$pdf->Ln(15);
$pdf->SetFont('Arial', 'I', 10);
$pdf->SetTextColor(150);
$pdf->Cell(0, 10, 'Dicetak oleh Sistem Absensi Guru - ' . date('d-m-Y'), 0, 0, 'C');

// Output PDF
$pdf->Output('I', 'Slip_Gaji_' . $nama . '.pdf');
