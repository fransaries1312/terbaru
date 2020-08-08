<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once("config/koneksi.php");
include_once("config/fpdf.php");

class CustomFPDF extends FPDF{
    function SetCellMargin($margin){
        // Set cell margin
        $this->cMargin = $margin;
    }
}

$mode=$_GET['mode'];

$pdf = new FPDF('l','mm','A5');
// membuat halaman baru
$pdf->AddPage();
$pdf->SetFont('Arial','B',13);




if($mode=='harian')
{
  $tanggal=$_GET['tanggal'];
   $tampil = mysqli_query($koneksi, "SELECT table_obat.nama_obat,detail_rekap.jumlah,detail_rekap.subtotal FROM table_rekap JOIN detail_rekap ON detail_rekap.id_daterek=table_rekap.id_daterek JOIN table_obat ON detail_rekap.id_obat=table_obat.id_obat WHERE table_rekap.tanggal='".$tanggal."'");
    $no = 0;

  $pdf->Cell(190,7,'LAPORAN PENJUALAN OBAT TANGGAL '.date('d-m-Y',strtotime($tanggal)),0,1,'C');
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(10,7,'',0,1);
}

if($mode=='bulanan')
{
  $bulan=$_GET['bulan'];
  $tahun=$_GET['tahun'];
  $tampil = mysqli_query($koneksi, "SELECT table_obat.nama_obat, CONCAT(YEAR(tanggal),'/',MONTH(tanggal)) AS tanggal,SUM(data_rekap.jumlah) as jumlah FROM data_rekap JOIN table_obat ON data_rekap.id_obat=table_obat.id_obat WHERE MONTH(data_rekap.tanggal)='".$bulan."' AND YEAR(data_rekap.tanggal)='".$tahun."' GROUP BY data_rekap.id_obat, MONTH(data_rekap.tanggal) ORDER BY MONTH(data_rekap.tanggal)");
   $no = 0;

   $pdf->Cell(190,7,'LAPORAN PENJUALAN OBAT BULAN '.$bulan.' TAHUN '.$tahun,0,1,'C');
   $pdf->SetFont('Arial','B',12);
   $pdf->Cell(10,7,'',0,1);

}

if($mode=='custom'){
  $tanggal_awal=$_GET['tanggal_awal'];
  $tanggal_akhir=$_GET['tanggal_akhir'];

  $tampil = mysqli_query($koneksi, "SELECT table_obat.nama_obat, data_rekap.tanggal, data_rekap.jumlah  FROM data_rekap JOIN table_obat ON data_rekap.id_obat=table_obat.id_obat WHERE data_rekap.tanggal>='".$tanggal_awal."' AND data_rekap.tanggal<='".$tanggal_akhir."'");
  $no = 0;

  $pdf->Cell(190,7,'LAPORAN PENJUALAN OBAT ANTARA '.date('d-m-Y',strtotime($tanggal_awal)).' SD '.date('d-m-Y',strtotime($tanggal_akhir)),0,1,'C');
   $pdf->SetFont('Arial','B',12);
   $pdf->Cell(10,7,'',0,1);
}
$pdf->SetFont('Arial','B',10);
$pdf->Cell(20,6,'No',1,0,'C');
for ($i = 0; $i < mysqli_num_fields($tampil); $i++) {
	$pdf->Cell(50,6,ucwords(str_replace('_',' ',mysqli_fetch_field_direct($tampil,$i)->name)),1,0,'C');
 }
$pdf->Cell(10,7,'',0,01);

$pdf->SetFont('Arial','',11);

while ($row = mysqli_fetch_array($tampil)){
    $pdf->Cell(20,6,$no+1,1,0,'C');
    $pdf->Cell(50,6,$row['nama_obat'],1,0);
    $pdf->Cell(50,6,$row['jumlah'],1,0,'C');
    $pdf->Cell(50,6,$row['subtotal'],1,1,'C'); 
    $no++;
}

$pdf->Output();
exit(0);

?>