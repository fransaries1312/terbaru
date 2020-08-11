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
// Hapus rekap
if ($module=='rekap' && $act=='delete'){
  
  mysqli_query($koneksi,"DELETE FROM data_rekap WHERE id_rek='$_GET[id]'");
  header('location:../../module.php?module='.$module);
 
}

// Input rekap
elseif ($module=='rekap'  && $act=='input'){
  
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
        var_dump('ERROR QUERY');
        die;
    }
    
}

// Update rekap
elseif ($module=='rekap'  && $act=='update'){
  
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
elseif($module=='rekap' && $act ==  'hapus_transaksi')
{
  $check_db = mysqli_query($koneksi,"SELECT * FROM table_rekap WHERE id_daterek='$_GET[id]' ORDER BY id_daterek DESC LIMIT 1") or error();

  if (!$check_db) {
    printf("Error: %s\n", mysqli_error($koneksi));
    exit();
  }

  $r=mysqli_fetch_array($check_db);

  if($r>0)
  {
    $id_daterek=$r['id_daterek'];

    $get_last_stok=mysqli_query($koneksi,"SELECT table_obat.satuan,detail_rekap.jumlah,stock_obat.stock,stock_obat.id_stock FROM stock_obat JOIN table_obat ON table_obat.id_stock=stock_obat.id_stock JOIN detail_rekap ON detail_rekap.id_obat=table_obat.id_obat WHERE detail_rekap.id_daterek='$id_daterek'");

    if (!$get_last_stok) {
      printf("Error: %s\n", mysqli_error($koneksi));
      exit();
    }


    while ($stok = mysqli_fetch_array($get_last_stok)) 
    {
      $id_stok=$stok['id_stock'];

      $get_stok_nom=mysqli_query($koneksi,"SELECT * FROM stock_obat WHERE id_stock='$id_stok'");

      if (!$get_stok_nom) {
        printf("Error: %s\n", mysqli_error($koneksi));
        exit();
      }

      $stok_jum=mysqli_fetch_array($get_stok_nom);

      $last_stok=($stok_jum['stock']!==null || $stok_jum['stock']!==0)?$stok_jum['stock']:0;

      $jumlah=$stok['jumlah'];

      $satuan=$stok['satuan'];

      $count_last_stok=$last_stok+($jumlah * $satuan);

      $data_stok=array(
        'stock'=>$count_last_stok,
        'update_at'=>date('Y-m-d H:i:s'),
      );

      $update_rekap=update('stock_obat',$data_stok,array('id_stock'=>$id_stok),$koneksi);

      if (!$update_rekap) {
        printf("Error: %s\n", mysqli_error($koneksi));
        exit();
      }
    }

    $get_detail=mysqli_query($koneksi,"DELETE FROM detail_rekap WHERE id_daterek='$id_daterek'");
    $get_detail1=mysqli_query($koneksi,"DELETE FROM table_rekap WHERE id_daterek='$id_daterek'");

    if($get_detail1==true && $get_detail==true)
    {
      $_SESSION['toastr'] = array(
          'type'      => 'success', // or 'success' or 'info' or 'warning'
          'message'   => 'Transaksi Berhasil Dihapus!',
          'title'     => 'Sukses'
      );

      
    }
    else
    {
      $_SESSION['toastr'] = array(
          'type'      => 'warning', // or 'success' or 'info' or 'warning'
          'message'   => 'Transaksi Gagal Dihapus!',
          'title'     => 'Peringatan'
      );
    }
  }
  else
  {
    $_SESSION['toastr'] = array(
          'type'      => 'warning', // or 'success' or 'info' or 'warning'
          'message'   => 'Transaksi Belum Terekam dalam database!',
          'title'     => 'Peringatan'
      );
  }

  header('location:../../module.php?module=transaksi');
}
elseif($module=='rekap' && $act ==  'hapus_detail_rekap')
{
  $check_db = mysqli_query($koneksi,"SELECT * FROM table_rekap WHERE id_daterek='$_GET[id_daterek]' ORDER BY id_daterek DESC LIMIT 1") or error();

  if (!$check_db) {
    printf("Error: %s\n", mysqli_error($koneksi));
    exit();
  }

  $r=mysqli_fetch_array($check_db);

  if($r>0)
  {
    $id_daterek=$r['id_daterek'];

    $count_detail_stok=mysqli_query($koneksi,"SELECT table_obat.satuan,detail_rekap.jumlah,stock_obat.stock,stock_obat.id_stock FROM stock_obat JOIN table_obat ON table_obat.id_stock=stock_obat.id_stock JOIN detail_rekap ON detail_rekap.id_obat=table_obat.id_obat WHERE detail_rekap.id_daterek='$id_daterek'");

    if (!$count_detail_stok) {
      printf("Error: %s\n", mysqli_error($koneksi));
      exit();
    }

    $count=mysqli_num_rows($count_detail_stok);

    $get_last_stok=mysqli_query($koneksi,"SELECT table_obat.satuan,detail_rekap.jumlah,stock_obat.stock,stock_obat.id_stock FROM stock_obat JOIN table_obat ON table_obat.id_stock=stock_obat.id_stock JOIN detail_rekap ON detail_rekap.id_obat=table_obat.id_obat WHERE detail_rekap.id_rek='$_GET[id]'");

    if (!$get_last_stok) {
      printf("Error: %s\n", mysqli_error($koneksi));
      exit();
    }


    while ($stok = mysqli_fetch_array($get_last_stok)) 
    {
      $id_stok=$stok['id_stock'];

      $get_stok_nom=mysqli_query($koneksi,"SELECT * FROM stock_obat WHERE id_stock='$id_stok'");

      if (!$get_stok_nom) {
        printf("Error: %s\n", mysqli_error($koneksi));
        exit();
      }

      $stok_jum=mysqli_fetch_array($get_stok_nom);

      $last_stok=($stok_jum['stock']!==null || $stok_jum['stock']!==0)?$stok_jum['stock']:0;

      $jumlah=$stok['jumlah'];

      $satuan=$stok['satuan'];

      $count_last_stok=$last_stok+($jumlah * $satuan);

      $data_stok=array(
        'stock'=>$count_last_stok,
        'update_at'=>date('Y-m-d H:i:s'),
      );

      $update_rekap=update('stock_obat',$data_stok,array('id_stock'=>$id_stok),$koneksi);

      if (!$update_rekap) {
        printf("Error: %s\n", mysqli_error($koneksi));
        exit();
      }
    }

    if($count > 1)
    {
      $get_detail=mysqli_query($koneksi,"DELETE FROM detail_rekap WHERE id_rek='$_GET[id]'");
    }
    else
    {
      $get_detail=mysqli_query($koneksi,"DELETE FROM detail_rekap WHERE id_daterek='$id_daterek'");
      $get_detail1=mysqli_query($koneksi,"DELETE FROM table_rekap WHERE id_daterek='$id_daterek'");
    }
    
    if($get_detail==true)
    {
      $_SESSION['toastr'] = array(
          'type'      => 'success', // or 'success' or 'info' or 'warning'
          'message'   => 'Transaksi Berhasil Dihapus!',
          'title'     => 'Sukses'
      );

      
    }
    else
    {
      $_SESSION['toastr'] = array(
          'type'      => 'warning', // or 'success' or 'info' or 'warning'
          'message'   => 'Transaksi Gagal Dihapus!',
          'title'     => 'Peringatan'
      );
    }
  }
  else
  {
    $_SESSION['toastr'] = array(
          'type'      => 'warning', // or 'success' or 'info' or 'warning'
          'message'   => 'Transaksi Belum Terekam dalam database!',
          'title'     => 'Peringatan'
      );
  }

  header('location:../../module.php?module=transaksi');
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
