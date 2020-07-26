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
<style>
    .typeahead { border: 2px solid #FFF;border-radius: 4px;padding: 8px 12px;max-width: 250px;min-width: 150px;background: rgba(66, 52, 52, 0.5);color: #FFF;font-size: 10pt;}
    .tt-menu { width:100px; }
    ul.typeahead{margin:0px;padding:10px 0px;}
    ul.typeahead.dropdown-menu li a {padding: 10px !important;  border-bottom:#CCC 1px solid;color:#FFF;font-size:11pt;}
    ul.typeahead.dropdown-menu li:last-child a { border-bottom:0px !important; }
    .bgcolor {max-width: 550px;min-width: 290px;max-height:340px;padding: 100px 10px 130px;border-radius:4px;text-align:center;margin:10px;}
    .demo-label {font-size:1.5em;color: #686868;font-weight: 500;color:#FFF;}
    .dropdown-menu>.active>a, .dropdown-menu>.active>a:focus, .dropdown-menu>.active>a:hover {
        text-decoration: none;
        background-color: #1f3f41;
        outline: 0;
        font-size: 8pt;
    }
</style>    
<?php
$aksi = "modules/transaksi/aksi_transaksi.php";
$act = $_GET['act'];
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


function autocode($koneksi)
{
    $query=mysqli_query($koneksi, "SELECT SUBSTR(nota,11,4) as nota,tanggal FROM table_rekap ORDER BY ABS(nota) DESC");
    if (!$query) {
        printf("Error: %s\n", mysqli_error($koneksi));
        exit();
    }
    $r=mysqli_fetch_array($query);

    if($r>0)
    {
        if($r['tanggal']!==date('Y-m-d'))
        {
            $kode = 1;
        }
        else
        {
            $kode = intval($r['nota']) + 1; 
        }
    } 

    else
    {
        $kode = 1;
    }

    $tgl=date('dmY'); 
    $batas = format_kode($kode);   
    $kodetampil = "PO".$tgl.$batas; 

    return $kodetampil; 
}

function format_kode($value) {

    $jml = strlen($value);
    if ($jml == 1)
        $no = "000" . $value;
    if ($jml == 2)
        $no = "00" . $value;
    if ($jml == 3)
        $no = "0" . $value;
    if ($jml == 4)
        $no = $value;
    if ($jml == 0)
        $no = "-";

    return $no;
}

switch($act)
{
    default:
    ?>
    <div class="animated fadeIn">
        <?php 
        $data_keranjang=mysqli_query($koneksi, "SELECT * FROM table_rekap WHERE transaksi_selesai='N' ORDER BY id_daterek DESC");

        if (!$data_keranjang) {
            printf("Error: %s\n", mysqli_error($koneksi));
            exit();
        }
        $r=mysqli_fetch_array($data_keranjang);
        ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Tambah Keranjang</strong>
                            <!-- <a type="button"  href="?module=user&act=add" class="btn btn-outline-info pull-right">Input Data<i class="fa ti-file"></i></a> -->
                        </div>
                        <div class="card-body card-block">
                            <div class="col-md-12">
                                <form method="post" id="form" action="#" enctype="multipart/form-data">
                                <div class="col-md-12">
                                    <div class="row form-group">
                                        <div class="col-12 col-md-12">
                                            <input type="text" id="nota" style="text-align: right" name="nota" placeholder="No Nota" class="form-control" value="<?= isset($r) && !empty($r['nota'])?$r['nota']:autocode($koneksi)?>" readonly>
                                            <small class="form-text text-muted">Nota penjualan obat</small>
                                        </div>
                                    </div> 
                                    <div class="row form-group">
                                        <div class="col-12 col-md-12">
                                            <input type="text" id="tanggal" style="text-align: right" name="tanggal" placeholder="Tgl Transasksi" class="form-control datepicker" value="<?=isset($r) && !empty($r['tanggal'])?date('d-m-Y',strtotime($r['tanggal'])):date('d-m-Y')?>">
                                            <small class="form-text text-muted">Tanggal transaksi penjualan</small>
                                        </div>
                                    </div> 
                                    <div class="row form-group">
                                        <div class="col-12 col-md-12">
                                            <input type="text" name="obat" id="obat" class="typeahead"/>
                                            <input type="hidden" name="id_obat" id="id_obat" class="form-control"/>
                                            <input type="hidden" name="nama_barang" id="nama_barang" class="form-control"/>
                                            <small class="form-text text-muted">Autocomplete Obat</small>
                                        </div>
                                    </div>  
                                    <div class="row form-group">
                                        <div class="col-12 col-md-12">
                                            <input type="text" name="harga" style="text-align: right" id="harga" class="form-control" value="0" readonly/>
                                            <small class="form-text text-muted">Harga Satuan</small>
                                        </div>
                                    </div>  
                                    <div class="row form-group">
                                        <div class="col-6 col-md-6">
                                            <input type="number" name="stok" style="text-align: right" id="stok" class="form-control count" value="0" readonly/>
                                            <input type="hidden" name="stok1" style="text-align: right" id="stok1" class="form-control count" value="0"/>
                                            <small class="form-text text-muted">Stok Sisa</small>
                                        </div>
                                        <div class="col-6 col-md-6">
                                            <input type="number" min="0" name="qty" style="text-align: right" id="qty" value="0" class="form-control count"/>
                                            <small class="form-text text-muted">Qty</small>
                                        </div>
                                    </div>  
                                    <div class="row form-group">
                                        <div class="col-5 col-md-5">
                                            <input type="number" min="0" name="diskon" style="text-align: right" id="diskon" value="0" class="form-control count"/>
                                            <small class="form-text text-muted">Diskon (%)</small>
                                        </div>
                                        <div class="col-7 col-md-7">
                                            <input type="text" name="subtotal" style="text-align: right" id="subtotal" class="form-control" value="0" readonly/>
                                            <input type="hidden" name="subtotal1" style="text-align: right" id="subtotal1" class="form-control" value="0"/>
                                            <small class="form-text text-muted">Total Harga</small>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-12 col-md-12">
                                            <input type="text" name="total" style="text-align: right" id="total" class="form-control" value="0" readonly/>
                                            <input type="hidden" name="total1" style="text-align: right" id="total1" class="form-control" value="0"/>
                                            <small class="form-text text-muted">Subtotal</small>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <a href="#" class="btn btn-primary btn-sm" id="add_cart">Add to Cart</a>
                                    </div>  
                                </div>
                            </form>
                            
                        </div>
                    </div>


                </div>
            </div><!-- .animated -->

             <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Daftar Keranjang</strong>
                            <!-- <a type="button"  href="?module=user&act=add" class="btn btn-outline-info pull-right">Input Data<i class="fa ti-file"></i></a> -->
                        </div>
                        <div class="card-body card-block">
                            <div class="col-md-12">
                                <form method="post" id="form1" action="<?= $aksi ?>?module=transaksi&act=bayar" enctype="multipart/form-data">
                                  <div class="col-md-12">
                                    <div class="form-group row">
                                     <div class="table-responsive">
                                        <input type="hidden" id="count_table" class="form-control" value="0">
                                        <table class="table table-hover" style="font-size: 10pt" id="tabel">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Nama Barang</th>
                                                    <th>Harga</th>
                                                    <th>Qty</th>
                                                    <th>Total</th>
                                                    <th>Diskon</th>
                                                    <th>Subtotal</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="list">
                                                <?php 
                                                $nota=$r['nota'];
                                                
                                                $daftar=mysqli_query($koneksi, "SELECT detail_rekap.*,table_obat.* FROM table_rekap JOIN detail_rekap ON detail_rekap.id_daterek=table_rekap.id_daterek JOIN table_obat ON detail_rekap.id_obat=table_obat.id_obat WHERE table_rekap.transaksi_selesai='N' AND table_rekap.nota='$nota'");

                                                if (!$daftar) {
                                                    printf("Error: %s\n", mysqli_error($koneksi));
                                                    exit();
                                                }
                                                $no=0;
                                                while($row=mysqli_fetch_array($daftar,MYSQLI_ASSOC))
                                                {
                                                    if(isset($row))
                                                    {?>
                                                        <tr class="isi_tabel" id="data_ke_<?= $no?>">
                                                            <td>
                                                                <a href="#" id="<?= $no?>" class="btn btn-xs btn-danger delete_barang_data_detail">Hapus</a>
                                                                <input type="hidden" id="delete_db_<?= $no?>" name="id_detail_rekap[]" value="<?=$row['id_rek']?>">
                                                            </td>
                                                            <td align="left">
                                                                <span id="barang_<?= $no?>"><?=$row['nama_obat']?></span>
                                                            </td>
                                                            <td align="right">
                                                                <span id="harga_<?= $no?>">Rp <?= number_format($row['harga_obat'],'0',',','.')?></span>
                                                            </td>
                                                            <td align="right">
                                                                <span id="qty_<?= $no?>"><?=$row['jumlah']?></span>
                                                            </td>
                                                            <td align="right">
                                                                <span id="total_<?= $no?>">Rp <?= number_format($row['total'],'0',',','.')?></span>
                                                            </td>
                                                            <td align="right">
                                                                <span id="diskon_<?= $no?>"><?=$row['diskon']?></span>
                                                            </td>
                                                            <td align="right">
                                                                <span id="subtotal_<?= $no?>">Rp <?= number_format($row['subtotal'],'0',',','.')?></span>
                                                                <input type="hidden" class="count_sub" id="subtotal1_<?= $no?>" value="<?=$row['subtotal']?>">
                                                            </td>
                                                        </tr>

                                                      
                                                   <?php  $no++;}
                                               }

                                                ?>
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-6 col-md-6">
                                    </div>
                                    <div class="col-6 col-md-6">
                                        <small class="form-text text-muted">Subtotal</small>
                                        <span class="help=block" id="subtotal_view" style="display:block;text-align: right;font-size:24pt">Rp. 0</span>
                                        <input type="hidden" id="subtotal_view1" style="text-align: right" name="subtotal_view1" placeholder="" class="form-control" value="0">
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-6 col-md-6">
                                    </div>
                                    <div class="col-6 col-md-6">
                                        <small class="form-text text-muted">Metode Transaksi</small>
                                        <select class="form-control" id="cara_bayar" name="cara_bayar">
                                            <option value="tunai">Tunai</option>
                                            <option value="kredit">Kredit</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-6 col-md-6">
                                    </div>
                                    <div class="col-6 col-md-6">
                                        <small class="form-text text-muted">Bayar</small>
                                        <input type="text" id="bayar" style="text-align: right" name="bayar" placeholder="" class="form-control number_dot" value="0">
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-6 col-md-6">
                                    </div>
                                    <div class="col-6 col-md-6">
                                        <small class="form-text text-muted">Kembali</small>
                                        <input type="text" id="kembali" style="text-align: right" name="kembali" placeholder="" class="form-control number_dot" value="0" readonly>
                                        
                                    </div>
                                </div>
                                <br/>
                                <hr/>
                                    <div class="text-right">
                                        <a href="#" class="btn btn-danger btn-sm" id="cancel">Batal Transaksi</a>
                                        <button type="submit" id="simpan" class="btn btn-success btn-sm">Bayar</button>
                                    </div>  
                                </div>
                            </form>
                            
                        </div>
                    </div>


                </div>               

            </div><!-- .animated -->
                <!-- table rekap -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Tabel Rekap Penjualan</strong>
                            
                        </div>
                        <div class="card-body">
                            <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr >
                                        <th>No</th>
                                        <th>Nota</th>
                                        <th>Tanggal</th>
                                        <th>Subtotal</th>
                                        <th>Bayar</th>
                                        <th>Kembali</th>
                                        <th>Aksi</th>
                                        </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total=0;
                                    $bayar=0;
                                    $kembali=0;
                                    $tampil = mysqli_query($koneksi, "SELECT table_rekap.id_daterek,table_rekap.subtotal,table_rekap.bayar,table_rekap.kembali,table_rekap.nota, table_rekap.created_at, table_obat.nama_obat, table_obat.bentuk_obat, detail_rekap.jumlah, table_obat.harga_obat FROM detail_rekap JOIN table_obat ON detail_rekap.id_obat=table_obat.id_obat JOIN table_rekap ON detail_rekap.id_daterek=table_rekap.id_daterek WHERE table_rekap.transaksi_selesai='Y' GROUP BY table_rekap.nota ORDER BY table_rekap.created_at DESC");
                                    $no = 1;
                                    while ($r = mysqli_fetch_array($tampil)) {
                                        ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $r['nota'] ?></td>
                                            <td><?= $r['created_at'] ?></td>
                                            <td>Rp.&nbsp<?= number_format($r['subtotal'],0,',','.') ?></td>
                                            <td>Rp.&nbsp<?= number_format($r['bayar'],0,',','.') ?></td>
                                            <td>Rp.&nbsp<?= number_format($r['kembali'],0,',','.') ?></td>
                                            <td>
                                                <a href="?module=rekap&act=detail_transaksi&id=<?php echo $r['id_daterek']; ?>" type="button" class="btn btn-outline-warning"><i class="fa fa-eye"></i></a>
                                                <a href="modules/rekap/aksi_rekap.php?module=rekap&act=hapus_transaksi&id=<?php echo $r['id_daterek']; ?>" type="button" class="btn btn-outline-danger hapus"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        <?php
                                   $total+= $r['subtotal'];
                                   $bayar+= $r['bayar'];
                                   $kembali+= $r['kembali'];

                               }
                                    ?>

                                </tbody>
                                <tfoot>
                                    <tr>
                                       
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="card-footer">
                            </div>
                        </div>
                    </div>
                </div>

        </div><!-- .content -->
        
        <script type="text/javascript">
            $(document).ready(function(){
                $('.hapus').click(function(){
                    var con=confirm("Apakah anda yakin akan menghapus transaksi ini?");
                    if(con==true)
                    {
                        return true;
                    } 
                    else
                    {
                        return false;
                    }
                })
            })
        </script>
<?php
}
?>