<?php
session_start();

include "../../config/koneksi.php";


$module=$_GET['module'];
$act=$_GET['act'];

//echo $module;
//die();
// Hapus rekap
if ($module=='rekap' AND $act=='delete'){
  
  mysqli_query($koneksi,"DELETE FROM data_rekap WHERE id_rek='$_GET[id]'");
  header('location:../../module.php?module='.$module);
 
}

// Input rekap
elseif ($module=='rekap' AND $act=='input'){
  
//  $tes=mysqli_query($koneksi,"INSERT INTO data_rekap(id_rek,id_obat,tanggal,jumlah,total) VALUES('','$_POST[id_obat]','$_POST[tanggal]','$_POST[jumlah]','$_POST[total]'");
//  var_dump($tes);
//  die;
    $mysqli = new mysqli("localhost", "root", "", "forecasting");
    $insert=$mysqli->query("INSERT INTO data_rekap(id_obat,tanggal,jumlah,total) VALUES('$_POST[id_obat]','$_POST[tanggal]','$_POST[jumlah]','$_POST[total]')");
    
    if($insert==true)
    {
        header('location:../../module.php?module='.$module);
    }
    else
    {
        var_dump('ERROR QUERYMU CUK');
        die;
    }
    
}

// Update rekap
elseif ($module=='rekap' AND $act=='update'){
  
  mysqli_query($koneksi,"UPDATE data_rekap SET id_obat = '$_POST[id_obat]',tanggal = '$_POST[tanggal]',jumlah = '$_POST[jumlah]',total = '$_POST[total]' WHERE id_rek = '$_POST[id_rek]'");
  header('location:../../module.php?module='.$module);
}

elseif($module=='rekap' && $act =='get_ajax')
{
    $id=$_GET['id'];
    $query=mysqli_query($koneksi,"SELECT * from table_obat where id_obat='$_GET[id]' LIMIT 1");
    $r = mysqli_fetch_array($query); 
    echo json_encode($r['harga_obat']);
}
?>
