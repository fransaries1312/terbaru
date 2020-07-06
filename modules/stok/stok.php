<script type="text/javascript" src="http://code.jquery.com/jquery-3.0.0.min.js"></script>
<script>
    $(function(){
        <?php
        // toastr output & session reset
        session_start();
        if(isset($_SESSION['toastr']))
        {
            echo 'toastr.'.$_SESSION['toastr']['type'].'("'.$_SESSION['toastr']['message'].'", "'.$_SESSION['toastr']['title'].'")';
            unset($_SESSION['toastr']);
        }
        ?>          
    });
</script> 
<?php
$aksi = "modules/stok/aksi_stok.php";
$act = $_GET['act'];
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

switch ($act) {
    // Tampil Rekap Data Penjualan
    case 'update':
    $id_stok=$_GET['id'];
    $jumlah=0;
    $bentuk_obat=mysqli_query($koneksi,"SELECT * FROM table_obat WHERE nama_obat LIKE '%$id_stok%'");

    // $data_stok=mysqli_fetch_array($bentuk_obat);
    // if(isset($data_stok) && $data_stok['id_stock']!==null)
    // {
    // 	$get_stok=mysqli_query($koneksi,"SELECT * from stock_obat WHERE id_stock='".$data_stok['id_stock']."'");
    // 	$jumlah= mysqli_fetch_array($get_stok)['stock'];
    // }
    // dd($id_stok);
    ?>
         <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>Input</strong> Data Stok <?= $id_stok?>
                        </div>
                        
                        <div class="card-body card-block">
                            <form action="<?= $aksi ?>?module=stok&act=update" method="POST" id="insert" class="form-horizontal">
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Jumlah Stok Barang</label></div>
                                    <div class="col-12 col-md-9"><input type="number" min="0" value="<?=$jumlah?>" id="jumlah" name="jumlah" class="form-control" required="">
                                        <span class="help-block" style="color:red"></span></div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="id_obat" class=" form-control-label">Bentuk Obat</label></div>
                                        <div class="col-12 col-md-9">
                                        <select name="bentuk_obat" id="bentuk_obat" class="form-control" required="">
                                            <option value="">Pilih Bentuk Obat</option>
                                            <?php
                                         
                                            while ($r = mysqli_fetch_array($bentuk_obat)) {
                                                ?>
                                                <option value="<?= $r['bentuk_obat'] ?>"><?= $r['bentuk_obat'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                  		<span class="help-block" style="color:red"></span>
                                    </div>
                                </div>  
                                <input type="hidden" name="id_stok" id="id_stok" value="<?=$id_stok?>">                                                           
                                <div class="card-footer">
                                    <button type="submit" id="submit" class="btn btn-primary btn-sm">
                                        <i class="fa fa-dot-circle-o"></i> Submit
                                    </button>
                                    <!-- <button type="reset" class="btn btn-danger btn-sm">
                                        <i class="fa fa-ban"></i> Kembali
                                    </button> -->
                                    <a href="?module=stok" class="btn btn-danger btn-sm"><i class="fa fa-ban"></i> Kembali</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div><!-- .animated -->
        </div>
    <?php
    break;
    case 'edit':
    $id_stok=$_GET['id'];
    $jumlah=0;
    $bentuk_obat=mysqli_query($koneksi,"SELECT * FROM table_obat WHERE nama_obat LIKE '%$id_stok%'");
    ?>
      <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>Update</strong> Data Stok <?= $id_stok?>
                        </div>
                        
                        <div class="card-body card-block">
                            <form action="<?= $aksi ?>?module=stok&act=update" method="POST" id="insert" class="form-horizontal">
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Jumlah Stok</label></div>
                                    <div class="col-12 col-md-9"><input type="number" min="0" value="<?=$jumlah?>" id="jumlah" name="jumlah" class="form-control" required="">
                                        <span class="help-block" style="color:red"></span></div>
                                </div>
                                 <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="id_obat" class=" form-control-label">Tambah / Kurangi Stok</label></div>
                                        <div class="col-12 col-md-9">
                                        <select name="tambah" id="tambah" class="form-control" required="">
                                            <option value="">Pilih Tambah / Kurangi Stok</option>
                                            <option value="+">Tambah (+)</option>
                                            <option value="-">Kurangi (-)</option>
                                        </select>
                                        <span class="help-block" style="color:red"></span>
                                    </div>
                                </div> 
                                
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="id_obat" class=" form-control-label">Bentuk Obat</label></div>
                                        <div class="col-12 col-md-9">
                                        <select name="bentuk_obat" id="bentuk_obat" class="form-control" required="">
                                            <option value="">Pilih Bentuk Obat</option>
                                            <?php
                                         
                                            while ($r = mysqli_fetch_array($bentuk_obat)) {
                                                ?>
                                                <option value="<?= $r['satuan'] ?>"><?= $r['bentuk_obat'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="help-block" style="color:red"></span>
                                    </div>
                                </div>  
                                <input type="hidden" name="id_stok" id="id_stok" value="<?=$id_stok?>">                                                           
                                <div class="card-footer">
                                    <button type="submit" id="submit" class="btn btn-primary btn-sm">
                                        <i class="fa fa-dot-circle-o"></i> Submit
                                    </button>
                                    <!-- <button type="reset" class="btn btn-danger btn-sm">
                                        <i class="fa fa-ban"></i> Kembali
                                    </button> -->
                                    <a href="?module=stok" class="btn btn-danger btn-sm"><i class="fa fa-ban"></i> Kembali</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div><!-- .animated -->
        </div>
    <?php 
    break;
    default:
        ?>
              <div class="animated fadeIn">
            <div class="row">

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Stok Obat</strong>
     <!--                        <a type="button"  href="?module=stok&act=add" class="btn btn-outline-info pull-right">Input Data<i class="fa ti-file"></i></a> -->
                        </div>
                        <div class="card-body">
                            <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr >
                                        <th>No</th>
                                        <th>Nama Obat</th>
                                        <th>Kategori Obat</th>
                                        <th>Manufaktur</th>
                                        <th>Stok</th>
                                        <th>Aksi</th>
                                     </tr>
                                </thead>
                                <tbody> 
                                	<?php  
                            
                                        	

                                	$tampil = mysqli_query($koneksi, "SELECT table_obat.id_stock,table_obat.nama_obat,kategori_obat.nama_kategori, manufaktur_obat.nama_manufaktur,table_obat.bentuk_obat FROM table_obat JOIN kategori_obat ON kategori_obat.id_kategori=table_obat.id_kategori JOIN manufaktur_obat ON manufaktur_obat.id_manufaktur=table_obat.id_manufaktur LEFT JOIN stock_obat on stock_obat.id_stock=table_obat.id_stock GROUP BY table_obat.nama_obat");
                                
                                	$no = 1;
                                	 while ($r = mysqli_fetch_array($tampil)) {
                                        ?>
                                        <tr>
                                        	<td><?= $no?></td>
                                        	<td><?= $r['nama_obat']?></td>
                                        	<td><?= $r['nama_kategori']?></td>
                                        	<td><?= $r['nama_manufaktur']?></td>
                                        	<?php
                                        	$select_stok=mysqli_query($koneksi,"SELECT coalesce(SUM(stock),0) AS stock FROM stock_obat WHERE id_stock='".$r['id_stock']."'");
                                        	$d = mysqli_fetch_array($select_stok); 
                                        	?>
                                        	<td><?= isset($d['stock']) && !empty($d['stock'])?$d['stock']:0?></td>
                                        	<td>
                                        		<?php if(isset($d['stock']) && !empty($d['stock'])){?>
                                        		<a href="?module=stok&act=edit&id=<?php echo $r['nama_obat']; ?>" type="button" class="btn btn-primary"><i class="fa fa-pencil"></i> Update Data</a>
                                        	<?php }else{?>
                                        		<a href="?module=stok&act=update&id=<?php echo $r['nama_obat']; ?>" type="button" class="btn btn-outline-primary"><i class="fa fa-pencil"></i> Set Data</a>
                                        	<?php }?>
                                        	</td>
                                        </tr>
                                   <?php
                                    $no++;}
                                    ?>
                                </tbody>
                            </table>
                            <div class="card-footer">
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- .animated -->
        </div><!-- .content -->
<?php
}
?>