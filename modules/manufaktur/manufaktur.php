<?php
$aksi = "modules/manufaktur/aksi_manufaktur.php";
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
                            <strong class="card-title">Tabel Manufaktur Obat</strong>
                            <?php if ($_SESSION['level'] == 'user') {?>
                            <a type="button"  href="?module=manufaktur&act=add" class="btn btn-outline-info pull-right">Input Data<i class="fa ti-plus"></i></a>
                        <?php } ?>
                        </div>
                        <div class="card-body">
                            <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr >
                                        <th>No</th>
                                        <th>Kode Manufaktur</th>
                                        <th>Nama Manufaktur Obat</th>
                                        <?php if ($_SESSION['level'] == 'user') {?>
                                        <th>Aksi</th>
                                        <?php } ?>
                                        </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tampil = mysqli_query($koneksi, "SELECT * FROM manufaktur_obat");
                                    $no = 1;
                                    while ($r = mysqli_fetch_array($tampil)) {
                                        ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $r['kode_manufaktur'] ?></td>
                                            <td><?= $r['nama_manufaktur'] ?></td>
                                            <?php if ($_SESSION['level'] == 'user') {?>
                                            <td>
                                                <a href="?module=manufaktur&act=edit&id=<?php echo $r[id_manufaktur]; ?>" type="button" class="btn btn-outline-warning"><i class="fa fa-pencil"></i></a>
                                                <a href="<?= $aksi ?>?module=manufaktur&act=delete&id=<?php echo $r[id_manufaktur]; ?>" type="button" class="btn btn-outline-danger"><i class="fa fa-trash"></i></a>
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
                            <strong>INPUT</strong> Data Manufaktur Obat
                        </div>
                        <div class="card-body card-block">
                            <form action="<?= $aksi ?>?module=manufaktur&act=input" method="POST"  class="form-horizontal">

                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="text-input" class=" form-control-label">Kode Manufaktur Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="kode_kategori" name="kode_manufaktur" class="form-control">
                                        <small class="form-text text-muted">Masukan kode manufaktur  obat</small></div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="text-input" class=" form-control-label">Nama Kategori  Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="nama_kategori" name="nama_manufaktur" class="form-control">
                                        <small class="form-text text-muted">Masukan manufaktur nama obat</small></div>
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
        $edit = mysqli_query($koneksi, "SELECT * FROM manufaktur_obat WHERE id_manufaktur='$_GET[id]'");
        $r = mysqli_fetch_array($edit);
        ?>
        
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>EDIT</strong> Data Manufaktur Obat
                        </div>
                        <div class="card-body card-block">
                            <form action="<?= $aksi ?>?module=manufaktur&act=update" method="POST"  class="form-horizontal">

                                <input type="hidden" id="text-input" name="id_manufaktur" class="form-control" value="<?php echo $r[id_manufaktur]; ?>">
                                
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="text-input" class=" form-control-label">Kode Manufaktur Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="text-input" name="kode_manufaktur" class="form-control" value="<?php echo $r[kode_manufaktur]; ?>" >
                                        <small class="form-text text-muted">Masukan kode manufaktur obat</small></div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Nama Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="text-input" name="nama_manufaktur" class="form-control" value="<?php echo $r[nama_manufaktur]; ?>">
                                        <small class="form-text text-muted">Masukan nama manufaktur obat</small></div>
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
