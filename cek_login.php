<?php

include "config/koneksi.php";
$username = $_POST['username'];
$pass     = md5($_POST['password']);


$login=mysqli_query($koneksi,"SELECT * FROM user WHERE username ='$username' AND password='$pass'");
$ketemu=mysqli_num_rows($login);
$r=mysqli_fetch_array($login);


// Apabila username dan password ditemukan
if ($ketemu > 0){
  session_start();
  $_SESSION[nama]         = $r[nama];
  $_SESSION[username]     = $r[username];
  $_SESSION[level]        = $r[level];
  header('location:module.php?module=home');
}
else{

  echo "<center>LOGIN GAGAL! <br> 
        Username atau Password Anda tidak benar.<br>
        Atau account Anda sedang diblokir<br>";
  echo "<a href=index.php><b>ULANGI LAGI</b></a></center>";

}

?>
