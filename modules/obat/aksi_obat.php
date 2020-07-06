<?php
session_start();

include "../../config/koneksi.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function error() {
  //error
  die();
}

if (! function_exists('dd'))
{
	/**
	 * Print all data before being load.
	 *
	 * @return bool
	 */
	function dd($data)
	{
		$elem='<pre>';
		$elem.=print_r($data);
		$elem.=die;
		$elem.='</pre>';
		return $elem;
	}
}

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
  
  // mysqli_query($koneksi,"INSERT INTO table_obat(id_obat,nama_obat,bentuk_obat,segmentasi_obat,id_kategori,id_manufaktur,satuan,harga_obat,id_stock,kode_obat) VALUES('$_POST[id_obat]','$_POST[nama_obat]','$_POST[bentuk_obat]','$_POST[segmentasi_obat]','$_POST[id_kategori]','$_POST[id_manufaktur]','$_POST[satuan]','$_POST[harga_obat]','$_POST[id_stock]','$_POST[kode_obat]')");

  $data=array(
  	'nama_obat'=>$_POST['nama_obat'],
  	'bentuk_obat'=>$_POST['bentuk_obat'],
  	'segmentasi_obat'=>$_POST['segmentasi_obat'],
  	'id_kategori'=>$_POST['id_kategori'],
  	'id_manufaktur'=>$_POST['id_manufaktur'],
  	'satuan'=>$_POST['satuan'],
  	'harga_obat'=>$_POST['harga_obat'],
  	'kode_obat'=>$_POST['kode_obat'],
  );

  $columns = implode(", ",array_keys($data));

  $escaped_values = array_map(array($koneksi, 'real_escape_string'), array_values($data));

  $values  = "'" .implode("', '", $escaped_values). "'";

  $sql = "INSERT INTO table_obat ($columns) VALUES ($values)";

  $insert_detail=mysqli_query($koneksi,$sql);

  if (!$insert_detail) {
  	printf("Error: %s\n", mysqli_error($koneksi));
  	exit();
  }

  $_SESSION['toastr'] = array(
    'type'      => 'success', // or 'success' or 'info' or 'warning'
    'message' => 'Obat berhasil ditambahkan!',
    'title'     => 'Sukses'
);

  header('location:../../module.php?module='.$module);
}

// Update obat
elseif ($module=='obat' AND $act=='update'){
  
  // mysqli_query($koneksi,"UPDATE table_obat SET id_obat = '$_POST[id_obat]',nama_obat = '$_POST[nama_obat]',bentuk_obat = '$_POST[bentuk_obat]',segmentasi_obat = '$_POST[segmentasi_obat]',id_kategori = '$_POST[id_kategori]',id_manufaktur = '$_POST[id_manufaktur]',satuan = '$_POST[satuan]',harga_obat = '$_POST[harga_obat]',id_stock = '$_POST[id_stock]',kode_obat = '$_POST[kode_obat]' WHERE id_obat = '$_POST[id_obat]'");

  $data=array(
  	'nama_obat'=>$_POST['nama_obat'],
  	'bentuk_obat'=>$_POST['bentuk_obat'],
  	'segmentasi_obat'=>$_POST['segmentasi_obat'],
  	'id_kategori'=>$_POST['id_kategori'],
  	'id_manufaktur'=>$_POST['id_manufaktur'],
  	'satuan'=>$_POST['satuan'],
  	'harga_obat'=>$_POST['harga_obat'],
  	'kode_obat'=>$_POST['kode_obat'],
  );

  $update=update('table_obat',$data,array('id_obat'=>$_POST['id_obat']),$koneksi);

  if (!$update) {
  	printf("Error: %s\n", mysqli_error($koneksi));
  	exit();
  }

  $_SESSION['toastr'] = array(
    'type'      => 'success', // or 'success' or 'info' or 'warning'
    'message' => 'Obat berhasil diupdate!',
    'title'     => 'Sukses'
);

  header('location:../../module.php?module='.$module);
}

function update($table_name, $myarray, $my_wheres,$koneksi) {
    $sql = "UPDATE ".$table_name.
    " SET ";
    $i = 0;
    foreach($myarray as $key => $value) {
        $sql.= $key." = '".$value."'";
        if ($i < count($myarray) - 1) {
            $sql.= " , ";
        }
        $i++;
    }
    if (count($my_wheres) > 0) {
        $sql.= " WHERE ";
        $i = 0;
        foreach($my_wheres as $key => $value) {
            $sql.= $key.
            " = '".$value."'";
            if ($i < count($my_wheres) - 1) {
                $sql.= " AND ";
            }
            $i++;
        }
    }

    return mysqli_query($koneksi,$sql);
}

?>
