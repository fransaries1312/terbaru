<?php
session_start();

include "../../config/koneksi.php";


$module=$_GET['module'];
$act=$_GET['act'];

//echo $module;
//die();
// Hapus user
if ($module=='user' AND $act=='delete'){
  
  mysqli_query($koneksi,"DELETE FROM user WHERE id_user='$_GET[id]'");
  header('location:../../module.php?module='.$module);
 
}

// Input user
elseif ($module=='user' AND $act=='input'){
  $pass = md5($_POST[password]);
  mysqli_query($koneksi,"INSERT INTO user (nama,level,username,password) VALUES('$_POST[nama]','$_POST[level]','$_POST[username]','$pass')");
  header('location:../../module.php?module='.$module);
}

// Update user
elseif ($module=='user' AND $act=='update'){
  //todo
  if(empty($_POST[password])){
    mysqli_query($koneksi,"UPDATE user SET nama = '$_POST[nama]',level = '$_POST[level]',username = '$_POST[username]' WHERE id_user = '$_POST[id_user]'");   
    
  }  else {
    $pass = md5($_POST[password]);
  
  mysqli_query($koneksi,"UPDATE user SET nama = '$_POST[nama]',level = '$_POST[level]',username = '$_POST[username]',password = '$pass' WHERE id_user = '$_POST[id_user]'");   
  }
   
  header('location:../../module.php?module='.$module);
}
?>
