
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


if($module=='transaksi' && $act=='query')
{
	$keyword = strval($_POST['query']);


	$data=array();

	$result = mysqli_query($koneksi, "SELECT * FROM table_obat WHERE nama_obat LIKE '%$keyword%'");

	if (mysqli_num_rows($result)>0)
	{
		$num=0;
		 while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		 	$data[$num]['id']=$row["id_obat"];
		 	$data[$num]['name']=$row["nama_obat"].' '.$row['bentuk_obat'];
		 	$num++;
		 }
		 echo json_encode($data);
	}
}


if($module=='transaksi' && $act=='cek_stok_barang')
{
	$id = $_GET['id'];
	$data=array();

	$result = mysqli_query($koneksi, "SELECT * FROM stock_obat JOIN table_obat ON table_obat.id_stock=stock_obat.id_stock WHERE table_obat.id_obat='$id' ORDER BY stock_obat.created_at DESC LIMIT 1");

	
	if(mysqli_num_rows($result)>0)
	{
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		 	$data['id']=$row["id_obat"];
		 	$data['stok']=floor($row["stock"]/$row['satuan']);
		 	$data['harga_obat']=$row["harga_obat"];
		 }

		 $data['status']=true;

		 if($data['stok']==0)
		 {
		 	$data['status']=false;
			$data['msg']='Stok Obat Habis';
			$data['stok']=0;
			$data['harga_obat']=0;
		 }
	}
	else
	{
		$data['status']=false;
		$data['msg']='Data Stok Obat tidak tersedia';
		$data['stok']=0;
		$data['harga_obat']=0;
	}
	echo json_encode($data);
}

if($module=='transaksi' && $act=='update_stok')
{
	$count_last_stok=0;

	$id_obat=$_POST['id'];
	$nota=$_POST['nota'];
	$tanggal=$_POST['tanggal'];
	$qty=$_POST['qty'];
	$diskon=$_POST['diskon'];
	$total_harga=$_POST['total_harga'];
	$subtotal=$_POST['subtotal'];

	$check_db = mysqli_query($koneksi,"SELECT * FROM table_rekap WHERE nota='$nota' OR transaksi_selesai='N' ORDER BY id_daterek DESC LIMIT 1") or error();

	if (!$check_db) {
		printf("Error: %s\n", mysqli_error($koneksi));
		exit();
	}

	$r=mysqli_fetch_array($check_db);

	if($r>0)
	{
		$id_daterek=$r['id_daterek'];

		$data_detail_rekap=array(
			'id_daterek'=>$id_daterek,
			'id_obat'=>$id_obat,
			'jumlah'=>$qty,
			'total'=>$total_harga,
			'diskon'=>$diskon,
			'subtotal'=>$subtotal,
			'created_at'=>date('Y-m-d H:i:s'),
			'updated_at'=>date('Y-m-d H:i:s'),
		);

		$columns = implode(", ",array_keys($data_detail_rekap));

		$escaped_values = array_map(array($koneksi, 'real_escape_string'), array_values($data_detail_rekap));

		$values  = "'" .implode("', '", $escaped_values). "'";

		$sql = "INSERT INTO detail_rekap ($columns) VALUES ($values)";

		$insert_detail=mysqli_query($koneksi,$sql);

		if (!$insert_detail) {
			printf("Error: %s\n", mysqli_error($koneksi));
			exit();
		}
		// $id_detail_rekap=mysqli_insert_id($insert_detail);

		if($insert_detail)
		{
			$get_id=mysqli_query($koneksi,"SELECT last_insert_id() as id");
			$id_detail_rekap=mysqli_fetch_array($get_id)['id'];
		}

		$get_last_stok=mysqli_query($koneksi,"SELECT table_obat.satuan,detail_rekap.jumlah,stock_obat.stock,stock_obat.id_stock FROM stock_obat JOIN table_obat ON table_obat.id_stock=stock_obat.id_stock JOIN detail_rekap ON detail_rekap.id_obat=table_obat.id_obat WHERE detail_rekap.id_rek='$id_detail_rekap' LIMIT 1");

		if (!$get_last_stok) {
			printf("Error: %s\n", mysqli_error($koneksi));
			exit();
		}

		$stok=mysqli_fetch_array($get_last_stok);

		$id_stok=$stok['id_stock'];

		$last_stok=($stok['stock']!==null || $stok['stock']!==0)?$stok['stock']:0;

		$jumlah=$stok['jumlah'];

		$satuan=$stok['satuan'];
		
		$count_last_stok=$last_stok-($jumlah * $satuan);

		$data_stok=array(
			'stock'=>$count_last_stok,
			'update_at'=>date('Y-m-d H:i:s'),
		);

		$update_rekap=update('stock_obat',$data_stok,array('id_stock'=>$id_stok),$koneksi);

		if (!$update_rekap) {
			printf("Error: %s\n", mysqli_error($koneksi));
			exit();
		}

		if($insert_detail==true)
		{
			$data=array(
				'status'=>true,
				'msg'=>'Transaksi berhasil disimpan',
				'id_detail'=>$id_detail_rekap,
			);
		}
		else
		{
			$data=array(
				'status'=>false,
				'msg'=>'Transaksi gagal disimpan',
			);
		}		
	}
	else
	{
		$data_rekap=array(
			'tanggal'=>date('Y-m-d',strtotime($tanggal)),
			'nota'=>$nota,
			'transaksi_selesai'=>'N',
			'created_at'=>date('Y-m-d H:i:s'),
		);

		$columns = implode(", ",array_keys($data_rekap));

		$escaped_values = array_map(array($koneksi, 'real_escape_string'), array_values($data_rekap));

		$values  = "'" .implode("', '", $escaped_values). "'";

		$sql = "INSERT INTO table_rekap ($columns) VALUES ($values)";

		$insert=mysqli_query($koneksi,$sql);

		if (!$insert) {
			printf("Error: %s\n", mysqli_error($koneksi));
			exit();
		}

		if($insert)
		{
			$last_id=mysqli_insert_id($koneksi);

			$data_detail_rekap=array(
				'id_daterek'=>$last_id,
				'id_obat'=>$id_obat,
				'jumlah'=>$qty,
				'total'=>$total_harga,
				'diskon'=>$diskon,
				'subtotal'=>$subtotal,
				'created_at'=>date('Y-m-d H:i:s'),
				'updated_at'=>date('Y-m-d H:i:s'),
			);

			$columns = implode(", ",array_keys($data_detail_rekap));

			$escaped_values = array_map(array($koneksi, 'real_escape_string'), array_values($data_detail_rekap));

			$values  = "'" .implode("', '", $escaped_values). "'";

			$sql = "INSERT INTO detail_rekap ($columns) VALUES ($values)";

			$insert_detail=mysqli_query($koneksi,$sql);

			if (!$insert_detail) {
				printf("Error: %s\n", mysqli_error($koneksi));
				exit();
			}

			if($insert_detail)
			{
				$get_id=mysqli_query($koneksi,"SELECT last_insert_id() as id");
				$id_detail_rekap=mysqli_fetch_array($get_id)['id'];
			}

			
			
		}

		$get_last_stok=mysqli_query($koneksi,"SELECT table_obat.satuan,detail_rekap.jumlah,stock_obat.stock,stock_obat.id_stock FROM stock_obat JOIN table_obat ON table_obat.id_stock=stock_obat.id_stock JOIN detail_rekap ON detail_rekap.id_obat=table_obat.id_obat WHERE detail_rekap.id_rek='$id_detail_rekap' LIMIT 1");

		if (!$get_last_stok) {
			printf("Error: %s\n", mysqli_error($koneksi));
			exit();
		}

		$stok=mysqli_fetch_array($get_last_stok);

		$id_stok=$stok['id_stock'];

		$last_stok=($stok['stock']!==null || $stok['stock']!==0)?$stok['stock']:0;

		$jumlah=$stok['jumlah'];

		$satuan=$stok['satuan'];
		
		$count_last_stok=$last_stok-($jumlah * $satuan);

		$data_stok=array(
			'stock'=>$count_last_stok,
			'update_at'=>date('Y-m-d H:i:s'),
		);

		$update_rekap=update('stock_obat',$data_stok,array('id_stock'=>$id_stok),$koneksi);

		if (!$update_rekap) {
			printf("Error: %s\n", mysqli_error($koneksi));
			exit();
		}
		

		if($insert==true && $insert_detail==true)
		{
			$data=array(
				'status'=>true,
				'msg'=>'Transaksi berhasil disimpan',
				'id_detail'=>$id_detail_rekap,
			);
		}
		else
		{
			$data=array(
				'status'=>false,
				'msg'=>'Transaksi gagal disimpan',
			);
		}		
	}
	echo json_encode($data);
}

if($module=='transaksi' && $act=='cancel_transaksi')
{
	$id_detail=$_GET['id'];

	$get_last_stok=mysqli_query($koneksi,"SELECT table_obat.satuan,detail_rekap.jumlah,stock_obat.stock,stock_obat.id_stock FROM stock_obat JOIN table_obat ON table_obat.id_stock=stock_obat.id_stock JOIN detail_rekap ON detail_rekap.id_obat=table_obat.id_obat WHERE detail_rekap.id_rek='$id_detail' LIMIT 1");

	if (!$get_last_stok) {
		printf("Error: %s\n", mysqli_error($koneksi));
		exit();
	}

	$stok=mysqli_fetch_array($get_last_stok);

	$id_stok=$stok['id_stock'];

	$last_stok=($stok['stock']!==null || $stok['stock']!==0)?$stok['stock']:0;

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

	$get_detail=mysqli_query($koneksi,"DELETE FROM detail_rekap WHERE id_rek='$id_detail'");
	
	if (!$get_detail) {
		printf("Error: %s\n", mysqli_error($koneksi));
		exit();
	}

	if($get_detail)
	{
		$data=array(
			'status'=>true,
			'msg'=>'Barang berhasil dihapus!',
		);
	}
	else
	{
		$data=array(
			'status'=>false,
			'msg'=>'Barang gagal dihapus!',
		);
	}
	echo json_encode($data);
}

if($module=='transaksi' && $act=='bayar')
{
	// dd($_POST);
	$id_detail_rekap=$_POST['id_detail_rekap'];
	$subtotal=floatval($_POST['subtotal_view1']);
	$cara_bayar=$_POST['cara_bayar'];
	$bayar=floatval(str_replace('.','',$_POST['bayar']));
	$kembali=floatval(str_replace('.','',$_POST['kembali']));
	
	$data_rekap=array(
		'subtotal'=>$subtotal,
		'bayar'=>$bayar,
		'kembali'=>$kembali,
		'cara_bayar'=>$cara_bayar,
		'transaksi_selesai'=>'Y',
		'updated_at'=>date('Y-m-d H:i:s'),
	);

	$update_rekap=update('table_rekap',$data_rekap,array('transaksi_selesai'=>'N'),$koneksi);

	if (!$update_rekap) {
		printf("Error: %s\n", mysqli_error($koneksi));
		exit();
	}

	if($update_rekap)
	{
		// foreach($id_detail_rekap as $key=>$val)
		// {
		// 	$data_stok=mysqli_query($koneksi,"SELECT * FROM detail_rekap WHERE id_rek='$val' LIMIT 1") or error();

		// 	if (!$data_stok) {
		// 		printf("Error: %s\n", mysqli_error($koneksi));
		// 		exit();
		// 	}

		// 	$r=mysqli_fetch_array($data_stok);

		// 	$id_obat=$r['id_obat'];
		// 	$qty=$r['jumlah'];

		// 	$get_last_stok=mysqli_query($koneksi,"SELECT * FROM stock_obat WHERE id_obat='$id_obat' ORDER BY id_stock DESC LIMIT 1");

		// 	if (!$get_last_stok) {
		// 		printf("Error: %s\n", mysqli_error($koneksi));
		// 		exit();
		// 	}

		// 	$stok=mysqli_fetch_array($get_last_stok)['stock'];

		// 	$last_stok=($stok!==null || $stok!==0)?$stok:0;

		// 	$count_last_stok=$last_stok-$qty;

			// $data_stok=array(
			// 	'id_obat'=>$id_obat,
			// 	'stock'=>$count_last_stok,
			// 	'created_at'=>date('Y-m-d H:i:s'),
			// 	'update_at'=>date('Y-m-d H:i:s'),
			// );

			// $columns = implode(", ",array_keys($data_stok));

			// $escaped_values = array_map(array($koneksi, 'real_escape_string'), array_values($data_stok));

			// $values  = "'" .implode("', '", $escaped_values). "'";

			// $sql = "INSERT INTO stock_obat ($columns) VALUES ($values)";

			// $insert_stok=mysqli_query($koneksi,$sql);

			// if (!$insert_stok) {
			// 	printf("Error: %s\n", mysqli_error($koneksi));
			// 	exit();
			// }
		// }
	}

	$_SESSION['toastr'] = array(
    'type'      => 'success', // or 'success' or 'info' or 'warning'
    'message' => 'Transaksi Berhasil Diproses!',
    'title'     => 'Sukses'
);

	header('location:../../module.php?module='.$module);
}

if($module=='transaksi' && $act=='batal_transaksi')
{
	$nota=$_GET['nota'];

	$check_db = mysqli_query($koneksi,"SELECT * FROM table_rekap WHERE nota='$nota' OR transaksi_selesai='N' ORDER BY id_daterek DESC LIMIT 1") or error();

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
			$data=array(
				'status'=>true,
				'msg'=>'Transaksi berhasil dihapus!',
			);
		}
	}
	else
	{
		$data=array(
			'status'=>false,
			'msg'=>'Transaksi anda belum terekam dalam database!',
		);
	}


	echo json_encode($data);
}

if($module=='transaksi' && $act='delete')
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
			    'message' 	=> 'Transaksi Berhasil Dihapus!',
			    'title'     => 'Sukses'
			);

			
		}
		else
		{
			$_SESSION['toastr'] = array(
			    'type'      => 'warning', // or 'success' or 'info' or 'warning'
			    'message' 	=> 'Transaksi Gagal Dihapus!',
			    'title'     => 'Peringatan'
			);
		}
	}
	else
	{
		$_SESSION['toastr'] = array(
			    'type'      => 'warning', // or 'success' or 'info' or 'warning'
			    'message' 	=> 'Transaksi Belum Terekam dalam database!',
			    'title'     => 'Peringatan'
			);
	}

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