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


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$aksi = "modules/rekap/aksi_rekap.php";
$act = isset($_GET['act'])?$_GET['act']:'';
switch ($act) {
    // Tampil Rekap Data Penjualan
    case 'detail_transaksi':
     $data=mysqli_query($koneksi, "SELECT * FROM table_rekap WHERE id_daterek='$_GET[id]' LIMIT 1");

        if (!$data) {
            printf("Error: %s\n", mysqli_error($koneksi));
            exit();
        }
        $r=mysqli_fetch_array($data);
    ?>
        <div class="animated fadeIn">
            <div class="row">

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Penjualan Transaksi <?=$r['nota']?></strong>
                            <!-- <a type="button"  href="?module=rekap&act=add" class="btn btn-outline-info pull-right">Input Data<i class="fa ti-file"></i></a> -->
                        </div>
                        <div class="card-body">
                            <table border="0">
                                <tbody>
                                    <tr>
                                        <td>No Nota</td>
                                        <td>:</td>
                                        <td><?=$r['nota']?></td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Transaksi</td>
                                        <td>:</td>
                                        <td><?=date('d-m-Y',strtotime($r['tanggal']))?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr/>
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Obat</th>
                                        <th>Nama Obat</th>
                                        <th>QTY</th>
                                        <th>Total</th>
                                        <th>Diskon</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total=0;
                                    $detail_rekap=mysqli_query($koneksi, "SELECT * FROM detail_rekap JOIN table_obat ON table_obat.id_obat=detail_rekap.id_obat WHERE id_daterek='$r[id_daterek]'");
                                    $no = 1;
                                    while ($d = mysqli_fetch_array($detail_rekap)) {?>
                                        <tr>
                                            <td><?= $no?></td>
                                            <td><?= $d['kode_obat']?></td>
                                            <td><?= $d['nama_obat']?> <?= $d['bentuk_obat']?></td>
                                            <td><?= $d['jumlah']?></td>
                                            <td>Rp.&nbsp<?= number_format($d['total'],0,',','.')?></td>
                                            <td><?= $d['diskon']?></td>
                                            <td>Rp.&nbsp<?= number_format($d['subtotal'],0,',','.')?></td>
                                        </tr>
                                    <?php
                                $no++;$total+=$d['subtotal'];}
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7" style="text-align: right">Total Harga</td>
                                        <td>Rp.&nbsp<?= number_format($total,0,',','.')?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" style="text-align: right">Bayar</td>
                                        <td>Rp.&nbsp<?= number_format($r['bayar'],0,',','.')?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" style="text-align: right">Kembali</td>
                                        <td>Rp.&nbsp<?= number_format($r['kembali'],0,',','.')?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" style="text-align: right">Cara Bayar</td>
                                        <td><?= $r['cara_bayar']?></td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="card-footer">
                                <center><p><i>Terima kasih atas kepercayaan anda pada kami</i></p></center>
                            </div>
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
                            <strong class="card-title">Tabel Rekap Penjualan</strong>
                            <a type="button"  href="?module=rekap&act=add" class="btn btn-outline-info pull-right">Input Data<i class="fa ti-file"></i></a>
                        </div>
                        <div class="card-body">
                            <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr >
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama Obat</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                        </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tampil = mysqli_query($koneksi, "SELECT table_obat.nama_obat, data_rekap.tanggal, data_rekap.jumlah, data_rekap.id_rek,table_obat.harga_obat, (table_obat.harga_obat * data_rekap.jumlah) as sum_total FROM data_rekap JOIN table_obat ON data_rekap.id_obat=table_obat.id_obat");
                                    $no = 1;
                                    while ($r = mysqli_fetch_array($tampil)) {
                                        ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= date('d-m-Y',strtotime($r['tanggal'])) ?></td>
                                            <td><?= $r['nama_obat'] ?></td>
                                            <td><?= $r['jumlah'] ?></td>
                                            <td><?= number_format($r['harga_obat'],0,',','.') ?></td>
                                            <td><?= number_format($r['sum_total'],0,',','.') ?></td>
                                            <td>
                                                <a href="?module=rekap&act=edit&id=<?php echo $r[id_rek]; ?>" type="button" class="btn btn-outline-warning"><i class="fa fa-pencil"></i></a>
                                                <a href="<?= $aksi ?>?module=rekap&act=delete&id=<?php echo $r[id_rek]; ?>" type="button" class="btn btn-outline-danger"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
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
        break;

    // Form Tambah Rekap
    case "add":
        ?>

        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>Input</strong> Data Rekap
                        </div>
                        
                        <div class="card-body card-block">
                            <form action="<?= $aksi ?>?module=rekap&act=input" method="POST" id="insert" class="form-horizontal">

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Tanggal</label></div>
                                    <div class="col-12 col-md-9"><input type="date" id="tanggal" name="tanggal" class="form-control">
                                        <span class="help-block" style="color:red"></span></div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="id_obat" class=" form-control-label">Nama Obat</label></div>

                                    <div class="col-12 col-md-9">
                                        <select name="id_obat" id="id_obat" class="form-control">
                                            <option value="">Pilih Obat</option>
                                            <?php
                                            $tampil = mysqli_query($koneksi, "SELECT * FROM table_obat");

                                            while ($r = mysqli_fetch_array($tampil)) {
                                                ?>
                                                <option value="<?= $r['id_obat'] ?>"><?= $r['nama_obat'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="help-block" style="color:red"></span>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Jumlah</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="number" id="jumlah" name="jumlah" class="form-control">
                                        <span class="help-block" style="color:red"></span></div>
                                </div>
                                
                                 <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Harga</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" readonly id="harga_obat" name="harga_obat" class="form-control" <?= $r['harga_obat'] ?>>
                                        <small class="form-text text-muted">  </small></div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Total</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" readonly id="total" name="total" class="form-control">
                                        <small class="form-text text-muted">  </small></div>
                                </div>
                       
                                                                 
                                <div class="card-footer">
                                    <button type="submit" id="submit" class="btn btn-primary btn-sm">
                                        <i class="fa fa-dot-circle-o"></i> Submit
                                    </button>
                                    <button type="reset" class="btn btn-danger btn-sm">
                                        <i class="fa fa-ban"></i> Reset
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div><!-- .animated -->
        </div>
            <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
        <script>
        $(document).ready(function(){
            $('#insert').submit('#submit',function(e){
                if($('#tanggal').val()=='')
                {
                    e.preventDefault();
                    $('#tanggal').next().html('Silahkan Isi Form');          
                }
                else
                {
                    $('#tanggal').next().html('');
                }
                if($('#id_obat').val()=='')
                {
                    e.preventDefault();
                    $('#id_obat').next().html('Silahkan Isi Form');          
                    
                }
                else
                {
                    $('#id_obat').next().html('');
                }
                if($('#jumlah').val()=='')
                {
                    e.preventDefault();
                    $('#jumlah').next().html('Silahkan Isi Form');          
                    
                } 
                else{
                    $('#jumlah').next().html('');
                }
                
            })
            
            $('#id_obat').on('change',function(){
                if($('#id_obat').val()=='')
                {
                  $('#harga_obat').val('');
                  $('#total').val('');
                  $('#jumlah').val('');
                }
                else
                {
                 $('#harga_obat').val('');
                 $('#total').val('');
                 $('#jumlah').val('');
                 get_harga($('#id_obat').val());
                }
               
            });
            
            $('#jumlah').bind('keyup change',function(){
                var harga_obat=parseInt($('#harga_obat').val());
                var jumlah=parseInt($('#jumlah').val());
                
                var hasil=harga_obat * jumlah;
                
//                console.log(Number.isNaN(hasil));
                if(Number.isNaN(hasil))
                {
                    $('#total').val(0);
                }
                else
                {
                    $('#total').val(hasil);
                }
                
            })
            
        })
        function get_harga(id)
         {
                 $.ajax({
                  url: '<?= $aksi ?>?module=rekap&act=get_ajax',
                  data: {'id':id},
                  type:'GET',
                  success: function(data){
                      var harga=parseInt(data.match(/[^_\W]+/g).join(' '));
                    
                   $('#harga_obat').val(harga);
                  },
                  error:function(xhr,status,error){
                      alert(xhr.responseText);
                 },
              });
          }
        </script>
        <?php
        break;

    // Form Edit Data Rekap
    case "edit":
        $edit = mysqli_query($koneksi, "SELECT * FROM data_rekap JOIN table_obat ON data_rekap.id_obat=table_obat.id_obat WHERE id_rek='$_GET[id]'");
        $r = mysqli_fetch_array($edit);
//        print_r($r);
//        die;
        ?>

        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>EDIT</strong> Data Rekap
                        </div>
                        <div class="card-body card-block">
                            <form action="<?= $aksi ?>?module=rekap&act=update" method="POST"  class="form-horizontal">

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Tanggal</label></div>
                                    <div class="col-12 col-md-9"><input type="date" id="tanggal" name="tanggal" class="form-control" value="<?php echo $r[tanggal]; ?>">
                                        <small class="form-text text-muted"></small></div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="id_obat" class=" form-control-label">Nama Obat</label></div>

                                    <div class="col-12 col-md-9">
                                        <select name="id_obat" id="select" class="form-control">
                                            <option value="">Pilih Obat</option>
                                            <?php
                                            $tampil = mysqli_query($koneksi, "SELECT * FROM table_obat");

                                            while ($ro = mysqli_fetch_array($tampil)) {
                                                ?>
                                                <option value="<?= $ro['id_obat'] ?>" <?php echo $r['id_obat'] == $ro['id_obat'] ? 'selected' : NULL; ?>><?= $ro['nama_obat'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>                          

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Jumlah</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="number" id="text-input" name="jumlah" class="form-control" value="<?php echo $r[jumlah]; ?>">
                                        <small class="form-text text-muted"></small></div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Harga</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" readonly id="harga_obat" name="harga_obat" class="form-control" value="<?php echo $r[harga_obat]; ?>">
                                        <small class="form-text text-muted">  </small></div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Total</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" readonly id="total" name="total" class="form-control" value="<?php echo $r[total]; ?>">
                                        <small class="form-text text-muted">  </small></div>
                                </div>
                                
                                <input type="hidden" id="text-input" name="id_rek" class="form-control" value="<?php echo $r[id_rek]; ?>">
                                
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fa fa-dot-circle-o"></i> Submit
                                    </button>
                                    <button type="reset" class="btn btn-danger btn-sm">
                                        <i class="fa fa-ban"></i> Reset
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div><!-- .animated -->
        </div>

        <?php
        break;
}
?>