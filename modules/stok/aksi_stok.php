
<?php
session_start();

include "../../config/koneksi.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

function error() {
  //error
  die();
}

$module=$_GET['module'];
$act=$_GET['act'];

if($module=='stok' && $act=='update')
{
	$jumlah=$_POST['jumlah'];
	$bentuk_obat=$_POST['bentuk_obat'];
	$id_stok=$_POST['id_stok'];

	// $check_db = mysqli_query($koneksi,"SELECT * FROM table_obat WHERE nama_obat LIKE '%$id_stok%' AND bentuk_obat='$bentuk_obat' LIMIT 1") or error();

	$check_db = mysqli_query($koneksi,"SELECT * FROM table_obat WHERE nama_obat LIKE '%$id_stok%'") or error();

	if (!$check_db) {
		printf("Error: %s\n", mysqli_error($koneksi));
		exit();
	}

	$r=mysqli_fetch_array($check_db);
	if($r>0)
	{
		if($r['id_stock']==null)
		{
			dd('aaaaa');
			$data=array(
				'stock'=>$jumlah * $r['satuan'],
				'created_at'=>date('Y-m-d H:i:s'),
				'update_at'=>date('Y-m-d H:i:s'),
			);

			$columns = implode(", ",array_keys($data));

			$escaped_values = array_map(array($koneksi, 'real_escape_string'), array_values($data));

			$values  = "'" .implode("', '", $escaped_values). "'";

			$sql = "INSERT INTO stock_obat ($columns) VALUES ($values)";

			$insert=mysqli_query($koneksi,$sql);

			if (!$insert) {
				printf("Error: %s\n", mysqli_error($koneksi));
				exit();
			}
		// $id_detail_rekap=mysqli_insert_id($insert_detail);

			if($insert)
			{
				$get_id=mysqli_query($koneksi,"SELECT last_insert_id() as id");
				$id_stock=mysqli_fetch_array($get_id)['id'];
			}

			$data1=array(
					'id_stock'=>$id_stock,
			);	

			$obat = mysqli_query($koneksi,"SELECT * FROM table_obat WHERE nama_obat LIKE '%$id_stok%'") or error();	

			while ($obat = mysqli_fetch_array($obat)) 
			{
				$update=update('table_obat',$data1,array('id_obat'=>$obat['id_obat']),$koneksi);
			}

			if (!$update) {
				printf("Error: %s\n", mysqli_error($koneksi));
				exit();
			}

			if($update)
			{
				$_SESSION['toastr'] = array(
				    'type'      => 'success', // or 'success' or 'info' or 'warning'
				    'message' 	=> 'Data stok berhasil diupdate!',
				    'title'     => 'Sukses'
				);
			}
			else
			{
				$_SESSION['toastr'] = array(
				    'type'      => 'warning', // or 'success' or 'info' or 'warning'
				    'message' 	=> 'Data stok gagal diupdate!',
				    'title'     => 'Peringatan'
				);
			}
			header('location:../../module.php?module='.$module);
		}
		else
		{
			$id=$r['id_stock'];

			$data1=array(
					'id_stock'=>$id,
			);	

			$a = mysqli_query($koneksi,"SELECT * FROM table_obat WHERE nama_obat LIKE '%$id_stok%'") or error();	

			while ($obat = mysqli_fetch_array($a)) 
			{
				$update=update('table_obat',$data1,array('id_obat'=>$obat['id_obat']),$koneksi);
			}	

			if (!$update) {
				printf("Error: %s\n", mysqli_error($koneksi));
				exit();
			}

			$get_stok = mysqli_query($koneksi,"SELECT * FROM stock_obat WHERE id_stock='$id' LIMIT 1") or error();

			if (!$get_stok) {
				printf("Error: %s\n", mysqli_error($koneksi));
				exit();
			}

			$stok=mysqli_fetch_array($get_stok);

			if(isset($_POST['tambah']) && $_POST['tambah']=='+')
			{
				$total=intval($stok['stock'])+(intval($jumlah) * $_POST['bentuk_obat']);
			}
			else
			{
				$total=intval($stok['stock'])-(intval($jumlah) * $_POST['bentuk_obat']);
			}

			if($total<0)
			{
				$_SESSION['toastr'] = array(
				    'type'      => 'warning', // or 'success' or 'info' or 'warning'
				    'message' 	=> 'Stok yang anda masukkan hasilnya kurang dari 0!',
				    'title'     => 'Peringatan'
				);
				header('location:../../module.php?module='.$module);
			}
			else
			{
				$data=array(
					'stock'=>$total,
				);

				$update=update('stock_obat',$data,array('id_stock'=>$id),$koneksi);

				if (!$update) {
					printf("Error: %s\n", mysqli_error($koneksi));
					exit();
				}

				if($update)
				{
					$_SESSION['toastr'] = array(
				    'type'      => 'success', // or 'success' or 'info' or 'warning'
				    'message' 	=> 'Data stok berhasil diupdate!',
				    'title'     => 'Sukses'
				);
				}
				else
				{
					$_SESSION['toastr'] = array(
				    'type'      => 'warning', // or 'success' or 'info' or 'warning'
				    'message' 	=> 'Data stok gagal diupdate!',
				    'title'     => 'Peringatan'
				);
				}
				header('location:../../module.php?module='.$module);
			}
		}
	}
	else
	{
		$_SESSION['toastr'] = array(
		    'type'      => 'warning', // or 'success' or 'info' or 'warning'
		    'message' 	=> 'Data obat tidak ditemukan!',
		    'title'     => 'Peringatan'
		);
		header('location:../../module.php?module='.$module);
	}
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