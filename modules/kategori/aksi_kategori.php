<?php
session_start();

include "../../config/koneksi.php";


$module=$_GET['module'];
$act=$_GET['act'];


if ($module=='kategori' AND $act=='delete'){
  
  mysqli_query($koneksi,"DELETE FROM kategori_obat WHERE id_kategori='$_GET[id]'");
  header('location:../../module.php?module='.$module);
 
}

// Input obat
elseif ($module=='kategori' AND $act=='input'){
  
  mysqli_query($koneksi,"INSERT INTO kategori_obat(id_kategori,kode_kategori,nama_kategori) VALUES('$_POST[id_kategori]','$_POST[kode_kategori]','$_POST[nama_kategori]')");
  header('location:../../module.php?module='.$module);
}

// Update obat
elseif ($module=='kategori' AND $act=='update'){
  
  mysqli_query($koneksi,"UPDATE kategori_obat SET id_kategori = '$_POST[id_kategori]', kode_kategori = '$_POST[kode_kategori]',nama_kategori = '$_POST[nama_kategori]' WHERE id_kategori = '$_POST[id_kategori]'");
  header('location:../../module.php?module='.$module);
}
?>
