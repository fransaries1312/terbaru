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
  
  mysqli_query($koneksi,"INSERT INTO table_obat(id_obat,nama_obat,bentuk_obat,segmentasi_obat,id_kategori,id_manufaktur,satuan,harga_obat,id_stock,kode_obat) VALUES('$_POST[id_obat]','$_POST[nama_obat]','$_POST[bentuk_obat]','$_POST[segmentasi_obat]','$_POST[id_kategori]','$_POST[id_manufaktur]','$_POST[satuan]','$_POST[harga_obat]','$_POST[id_stock]','$_POST[kode_obat]')");
  header('location:../../module.php?module='.$module);
}

// Update obat
elseif ($module=='obat' AND $act=='update'){
  
  mysqli_query($koneksi,"UPDATE table_obat SET id_obat = '$_POST[id_obat]',nama_obat = '$_POST[nama_obat]',bentuk_obat = '$_POST[bentuk_obat]',segmentasi_obat = '$_POST[segmentasi_obat]',id_kategori = '$_POST[id_kategori]',id_manufaktur = '$_POST[id_manufaktur]',satuan = '$_POST[satuan]',harga_obat = '$_POST[harga_obat]',id_stock = '$_POST[id_stock]',kode_obat = '$_POST[kode_obat]' WHERE id_obat = '$_POST[id_obat]'");
  header('location:../../module.php?module='.$module);
}
?>
