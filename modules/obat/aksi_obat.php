<?php
session_start();

include "../../config/koneksi.php";


$module=$_GET['module'];
$act=$_GET['act'];

//echo $module;
//die();
// Hapus obat
if ($module=='obat' AND $act=='delete'){
  
  mysqli_query($koneksi,"DELETE FROM table_obat WHERE id_obat='$_GET[id]'");
  header('location:../../module.php?module='.$module);
 
}

// Input obat
elseif ($module=='obat' AND $act=='input'){
  
  mysqli_query($koneksi,"INSERT INTO table_obat(kode_obat,nama_obat,kategori_obat,golongan_obat,manfaat) VALUES('$_POST[kode_obat]','$_POST[nama_obat]','$_POST[kategori_obat]','$_POST[golongan_obat]','$_POST[manfaat]')");
  header('location:../../module.php?module='.$module);
}

// Update obat
elseif ($module=='obat' AND $act=='update'){
  
  mysqli_query($koneksi,"UPDATE table_obat SET kode_obat = '$_POST[kode_obat]',nama_obat = '$_POST[nama_obat]',kategori_obat = '$_POST[kategori_obat]',golongan_obat = '$_POST[golongan_obat]',manfaat = '$_POST[manfaat]' WHERE id_obat = '$_POST[id_obat]'");
  header('location:../../module.php?module='.$module);
}
?>
