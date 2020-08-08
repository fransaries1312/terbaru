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

$aksi = "modules/obat/aksi_obat.php";
$act = $_GET['act'];
switch ($act) {
    // Tampil Data Obat
    default:
        ?>


        <div class="animated fadeIn">
            <div class="row">

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Tabel Obat</strong>
                            <?php if ($_SESSION['level'] == 'user') {?>
                            <a type="button"  href="?module=obat&act=add" class="btn btn-outline-info pull-right">Input Data<i class="fa ti-plus"></i></a>
                            <?php } ?>
                        </div>
                        <div class="card-body">
                            <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Obat</th>
                                        <th>Nama Obat</th>
                                        <th>Segmentasi</th>
                                        <th>Kategori Obat</th>
                                        <th>Manufaktur Obat</th>
                                        <th>Harga Obat</th>
                                        <?php if ($_SESSION['level'] == 'user') {?>
                                        <th>Aksi</th>
                                        <?php } ?>                                      
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tampil = mysqli_query($koneksi, "SELECT * FROM table_obat Join kategori_obat on table_obat.id_kategori = kategori_obat.id_kategori join manufaktur_obat on table_obat.id_manufaktur = manufaktur_obat.id_manufaktur");
                                    $no = 1;
                                    while ($r = mysqli_fetch_array($tampil)) {
                                        ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $r['kode_obat'] ?></td>
                                            <td><?= $r['nama_obat'] ?><?=' '?><?= $r['bentuk_obat'] ?></td>
                                            <td><?= $r['segmentasi_obat'] ?></td>
                                            <td><?= $r['nama_kategori'] ?></td>
                                            <td><?= $r['nama_manufaktur'] ?></td>
                                            <td><?= $r['harga_obat'] ?></td>
                                            <?php if ($_SESSION['level'] == 'user') {?>
                                            <td>
                                                <a href="?module=obat&act=edit&id=<?php echo $r[id_obat]; ?>" type="button" class="btn btn-outline-warning"><i class="fa fa-pencil"></i></a>
                                                <a href="<?= $aksi ?>?module=obat&act=delete&id=<?php echo $r[id_obat]; ?>" type="button" class="btn btn-outline-danger"><i class="fa fa-trash"></i></a>
                                            </td>
                                            <?php } ?>  
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

    // Form Tambah Obat
    case "add":
        ?>
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>INPUT</strong> Data Obat
                        </div>
                        <div class="card-body card-block">
                            <form action="<?= $aksi ?>?module=obat&act=input" method="POST"  class="form-horizontal">
                    
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="text-input" class=" form-control-label">Nama Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="text-input" name="nama_obat" placeholder="Cataflam" class="form-control">
                                        <small class="form-text text-muted">Masukan nama obat</small></div>
                                </div>
                                
                                 <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="text-input" class=" form-control-label">Bentuk Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="text-input" name="bentuk_obat" placeholder=".. 200mg 4 tablet" class="form-control">
                                        <small class="form-text text-muted">Masukan bentuk obat</small></div>
                                </div>

                                 <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="text-input" class=" form-control-label">Satuan Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="text-input" name="satuan" placeholder="satuan jual" class="form-control">
                                        <small class="form-text text-muted">Masukan satuan jual obat</small></div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="select" class=" form-control-label">Segmentasi Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <select name="segmentasi_obat" id="select" class="form-control">
                                            <option value="">Pilih Kategori</option>
                                            <option value="obat bebas">OB</option>
                                            <option value="obat bebas terbatas">OBT</option>
                                            <option value="obat keras">OK</option>
                                            <option value="obat herbal">OH</option>
                                            <option value="obat over the counter">OTC</option>
                                            <option value="lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="id_kategori" class=" form-control-label">Kategori Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <select name="id_kategori" id="id_obat" class="form-control">
                                            <option value="">Pilih Kategori Obat</option>
                                            <?php
                                            $tampil = mysqli_query($koneksi, "SELECT * FROM kategori_obat");

                                            while ($r = mysqli_fetch_array($tampil)) {
                                                ?>
                                                <option value="<?= $r['id_kategori'] ?>"><?= $r['kode_kategori'] ?><?=': '?><?= $r['nama_kategori'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="help-block" style="color:red"></span>
                                    </div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="id_manufaktur" class=" form-control-label">Manufaktur Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <select name="id_manufaktur" id="id_obat" class="form-control">
                                            <option value="">Pilih Manufaktur Obat</option>
                                            <?php
                                            $tampil = mysqli_query($koneksi, "SELECT * FROM manufaktur_obat");

                                            while ($r = mysqli_fetch_array($tampil)) {
                                                ?>
                                                <option value="<?= $r['id_manufaktur'] ?>"><?= $r['kode_manufaktur'] ?><?=': '?><?= $r['nama_manufaktur'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="help-block" style="color:red"></span>
                                    </div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Harga Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="number" id="harga_obat" name="harga_obat" class="form-control">
                                        <span class="help-block" style="color:red"></span></div>
                                </div>
                                
                               <!--  <input type="hidden" id="text-input" name="id_stock" class="form-control" value=NULL> -->

                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="text-input" class=" form-control-label">Kode Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="text-input" name="kode_obat" placeholder="singkatan nama obat,betuk dan jumalah, segmentasi, manufaktur" class="form-control">
                                        <small class="form-text text-muted">Contoh: cat200-T10-o1-gm</small></div>
                                </div>

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

    // Form Edit Data Obat
    case "edit":
        $edit = mysqli_query($koneksi, "SELECT * FROM table_obat WHERE id_obat='$_GET[id]'");
        $r = mysqli_fetch_array($edit);
        ?>
        
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>EDIT</strong> Data Obat
                        </div>
                        <div class="card-body card-block">
                            <form action="<?= $aksi ?>?module=obat&act=update" method="POST"  class="form-horizontal">

                                 <input type="hidden" id="text-input" name="id_obat" class="form-control" value="<?php echo $r[id_obat]; ?>">

                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="text-input" class=" form-control-label">Kode Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="text-input" name="kode_obat" class="form-control" value="<?php echo $r[kode_obat]; ?>" >
                                        <small class="form-text text-muted">cek kode obat</small></div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Nama Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="text-input" name="nama_obat" class="form-control" value="<?php echo $r[nama_obat]; ?>">
                                        <small class="form-text text-muted">cek nama obat</small></div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Bentu Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="text-input" name="bentuk_obat" class="form-control" value="<?php echo $r[bentuk_obat]; ?>">
                                        <small class="form-text text-muted">cek bentuk obat</small></div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="select" class=" form-control-label">Segmentasi Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <select name="segmentasi_obat" id="select" class="form-control">
                                            <option value="<?php echo $r[segmentasi_obat]; ?>"><?php echo $r[segmentasi_obat]; ?></option>
                                            <option value="obat bebas">OB</option>
                                            <option value="obat bebas terbatas">OBT</option>
                                            <option value="obat keras">OK</option>
                                            <option value="obat herbal">OH</option>
                                            <option value="obat over the counter">OTC</option>
                                            <option value="lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Satuan Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="text-input" name="satuan" class="form-control" value="<?php echo $r[satuan]; ?>">
                                        <small class="form-text text-muted">cek satuan obat</small></div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="text-input" class=" form-control-label">Kode Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="text-input" name="harga_obat" class="form-control" value="<?php echo $r[harga_obat]; ?>" >
                                        <small class="form-text text-muted">cek harga obat</small></div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="id_kategori" class=" form-control-label">Kategori Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <select name="id_kategori" id="id_kategori" class="form-control">
                                            <option value="">Silahkan Pilih</option>
                                            <?php
                                            $tampil = mysqli_query($koneksi, "SELECT * FROM kategori_obat");

                                            while ($d = mysqli_fetch_array($tampil)) {
                                                ?>
                                                <option value="<?= $d['id_kategori'] ?>" <?= $r['id_kategori'] == $d['id_kategori']?'selected':''?> ><?= $d['kode_kategori'] ?><?=': '?><?= $d['nama_kategori'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="id_manufaktur" class=" form-control-label">Manufaktur Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <select name="id_manufaktur" id="id_manufaktur" class="form-control">
                                           <option value="">Silahkan Pilih</option>
                                            <?php
                                            $tampil = mysqli_query($koneksi, "SELECT * FROM manufaktur_obat");

                                            while ($d = mysqli_fetch_array($tampil)) {
                                                ?>
                                                <option value="<?= $d['id_manufaktur'] ?>" <?= $r['id_manufaktur'] == $d['id_manufaktur']?'selected':''?>><?= $d['kode_manufaktur'] ?><?=': '?><?= $d['nama_manufaktur'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        
                                    </div>
                                </div>
                                                              
                               
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
