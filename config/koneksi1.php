<?php
date_default_timezone_set("Asia/Jakarta");
error_reporting(0);
$server = "localhost";
$username = "root";
$password = "";
$database = "forecasting";

// Koneksi dan memilih database di server
mysql_connect($server,$username,$password) or die("Koneksi gagal");
mysql_select_db($database) or die("Database tidak bisa dibuka");

/*if($connect){
  echo "berhasil";
    
}else {
    echo"gagal";
}
*/


?>