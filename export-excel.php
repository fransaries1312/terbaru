<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once("config/koneksi.php");

$mode=$_GET['mode'];

$file_ending = "xls";
$filename = $mode.'_'.date('Ymd');  
//header info for browser

header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=$filename.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");


if($mode=='harian')
{
  $tanggal=$_GET['tanggal'];
   $tampil = mysqli_query($koneksi, "SELECT detail_rekap.id_rek,detail_rekap.jumlah,detail_rekap.subtotal,table_obat.nama_obat,table_obat.bentuk_obat,table_rekap.tanggal,table_rekap.cara_bayar,table_rekap.nota FROM table_rekap JOIN detail_rekap ON detail_rekap.id_daterek=table_rekap.id_daterek JOIN table_obat ON detail_rekap.id_obat=table_obat.id_obat WHERE table_rekap.tanggal='".$tanggal."'");
}

if($mode=='bulanan')
{
  $bulan=$_GET['bulan'];
  $tahun=$_GET['tahun'];
  $tampil = mysqli_query($koneksi, "SELECT table_obat.nama_obat, CONCAT(YEAR(tanggal),'/',MONTH(tanggal)) AS tahun_bulan,SUM(data_rekap.jumlah) as sum_jumlah FROM data_rekap JOIN table_obat ON data_rekap.id_obat=table_obat.id_obat WHERE MONTH(data_rekap.tanggal)='".$bulan."' AND YEAR(data_rekap.tanggal)='".$tahun."' GROUP BY data_rekap.id_obat, MONTH(data_rekap.tanggal) ORDER BY MONTH(data_rekap.tanggal)");
}

if($mode=='custom'){
  $tanggal_awal=$_GET['tanggal_awal'];
  $tanggal_akhir=$_GET['tanggal_akhir'];

  $tampil = mysqli_query($koneksi, "SELECT table_obat.nama_obat, data_rekap.tanggal, data_rekap.jumlah  FROM data_rekap JOIN table_obat ON data_rekap.id_obat=table_obat.id_obat WHERE data_rekap.tanggal>='".$tanggal_awal."' AND data_rekap.tanggal<='".$tanggal_akhir."'");
  $no = 0;


}
    $sep = "\t"; //tabbed character
//start of printing column names as names of MySQL fields
    for ($i = 0; $i < mysqli_num_fields($tampil); $i++) {
      echo ucwords(str_replace('_',' ',mysqli_fetch_field_direct($tampil,$i)->name)) . "\t";
    }
    print("\n");    
//end of printing column names  
//start while loop to get data
    while($row = mysqli_fetch_row($tampil))
    {
      $schema_insert = "";
      for($j=0; $j<mysqli_num_fields($tampil);$j++)
      {
        if(!isset($row[$j]))
          $schema_insert .= "NULL".$sep;
        elseif ($row[$j] != "")
          $schema_insert .= "$row[$j]".$sep;
        else
          $schema_insert .= "".$sep;
      }
      $schema_insert = str_replace($sep."$", "", $schema_insert);
      $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
      $schema_insert .= "\t";
      print(trim($schema_insert));
      print "\n";
    }

    exit(0);

 ?> 