<?php
session_start();

include "../../config/koneksi.php";


$module=$_GET['module'];
$act=$_GET['act'];

//echo $module;
//die();
// Hapus obat
if ($module=='manufaktur' AND $act=='delete'){
  
  mysqli_query($koneksi,"DELETE FROM manufaktur_obat WHERE id_manufakturt='$_GET[id]'");
  header('location:../../module.php?module='.$module);
 
}

// Input obat
elseif ($module=='manufaktur' AND $act=='input'){
  
  mysqli_query($koneksi,"INSERT INTO manufaktur_obat(id_manufaktur,kode_manufaktur,nama_manufaktur) VALUES('$_POST[id_manufaktur]','$_POST[kode_manufaktur]','$_POST[nama_manufaktur]')");
  header('location:../../module.php?module='.$module);
}

// Update obat
elseif ($module=='manufaktur' AND $act=='update'){
  
  mysqli_query($koneksi,"UPDATE manufaktur_obat SET id_manufaktur = '$_POST[id_manufaktur]', kode_manufaktur = '$_POST[kode_manufaktur]',nama_manufaktur = '$_POST[nama_manufaktur]' WHERE id_manufaktur = '$_POST[id_manufaktur]'");
  header('location:../../module.php?module='.$module);
}
?>
