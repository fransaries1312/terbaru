
<?php
$aksi = "modules/rekap/aksi_rekap.php";
$act = $_GET['act'];
switch ($act) {
    // Tampil Rekap Data Penjualan
    case 'detail_transaksi':
    print_r('aaaaaa');
    die;
    break;
    default:
        //?>


        <div class="animated fadeIn">
            
            <div class="row">
               <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Tabel Rekap Penjualan</strong>
                            
                        </div>
                        
                        <div class="row form-group" style="margin-left: 2em;margin-top: 2em">
                            <div class="col col-md-1"><label for="select" class=" form-control-label">Tanggal</label></div>
                            <div class="col-md-3">
                                <input type="text" name="tanggal_awal" value="<?php echo isset($_GET['tanggal_awal'])?$_GET['tanggal_awal']:date('d-m-Y')?>" class="form-control datepicker">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="tanggal_akhir" value="<?php echo isset($_GET['tanggal_akhir'])?$_GET['tanggal_akhir']:date('d-m-Y')?>" class="form-control datepicker">
                            </div>
                           <!--  <input type="hidden" name="module" value="grafik"> -->
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-dot-circle-o col-md">Proses </i> 
                                </button>
                            </div>

                        </div>                        
                    

                <hr>
                        <div class="card-body">
                            <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr >
                                        <th>No</th>
                                        <th>Nota</th>
                                        <th>Tanggal</th>
                                        <th>Nama Obat</th>
                                        <th>Jumlah</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                        </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tampil = mysqli_query($koneksi, "SELECT table_rekap.nota,table_obat.nama_obat,table_obat.bentuk_obat, table_rekap.tanggal, detail_rekap.jumlah, detail_rekap.subtotal FROM table_rekap JOIN detail_rekap ON detail_rekap.id_daterek=table_rekap.id_daterek JOIN table_obat ON detail_rekap.id_obat=table_obat.id_obat ORDER BY table_rekap.nota ASC");
                                    $no = 1;
                                    while ($r = mysqli_fetch_array($tampil)) {
                                        ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $r['nota'] ?></td>
                                            <td><?= date('d-m-Y',strtotime($r['tanggal'])) ?></td>
                                            <td><?= $r['nama_obat'] ?></td>
                                            <td><?= $r['jumlah'] ?></td>
                                            <td><?= number_format($r['subtotal'],0,',','.') ?></td>
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