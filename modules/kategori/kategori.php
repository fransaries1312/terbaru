<?php
$aksi = "modules/kategori/aksi_kategori.php";
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

                            <a type="button"  href="?module=kategori&act=add" class="btn btn-outline-info pull-right">Input Data<i class="fa ti-plus"></i></a>
                        </div>
                        <div class="card-body">
                            <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr >
                                        <th>No</th>
                                        <th>Kode Kategori</th>
                                        <th>Nama Kategori Obat</th>
                                        <th>Aksi</th>
                                        </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tampil = mysqli_query($koneksi, "SELECT * FROM kategori_obat");
                                    $no = 1;
                                    while ($r = mysqli_fetch_array($tampil)) {
                                        ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $r['kode_kategori'] ?></td>
                                            <td><?= $r['nama_kategori'] ?></td>
                                            <td>
                                                <a href="?module=kategori&act=edit&id=<?php echo $r[id_kategori]; ?>" type="button" class="btn btn-outline-warning"><i class="fa fa-pencil"></i></a>
                                                <a href="<?= $aksi ?>?module=kategori&act=delete&id=<?php echo $r[id_kategori]; ?>" type="button" class="btn btn-outline-danger"><i class="fa fa-trash"></i></a>
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

    // Form Tambah Obat
    case "add":
        ?>
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>INPUT</strong> Data Kategori Obat
                        </div>
                        <div class="card-body card-block">
                            <form action="<?= $aksi ?>?module=kategori&act=input" method="POST"  class="form-horizontal">

                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="text-input" class=" form-control-label">Kode Kategori Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="kode_kategori" name="kode_kategori" class="form-control">
                                        <small class="form-text text-muted">Masukan kode ategori  obat</small></div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="text-input" class=" form-control-label">Nama Kategori  Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="nama_kategori" name="nama_kategori" class="form-control">
                                        <small class="form-text text-muted">Masukan kategori  nama obat</small></div>
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
        $edit = mysqli_query($koneksi, "SELECT * FROM kategori_obat WHERE id_kategori='$_GET[id]'");
        $r = mysqli_fetch_array($edit);
        ?>
        
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>EDIT</strong> Ktegori Obat
                        </div>
                        <div class="card-body card-block">
                            <form action="<?= $aksi ?>?module=kategori&act=update" method="POST"  class="form-horizontal">

                                <input type="hidden" id="text-input" name="id_kategori" class="form-control" value="<?php echo $r[id_kategori]; ?>">
                                
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="text-input" class=" form-control-label">Kode Kategori Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="text-input" name="kode_kategori" class="form-control" value="<?php echo $r[kode_kategori]; ?>" >
                                        <small class="form-text text-muted">Masukan kode kategori obat</small></div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Nama Obat</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="text-input" name="nama_kategori" class="form-control" value="<?php echo $r[nama_kategori]; ?>">
                                        <small class="form-text text-muted">Masukan nama obat</small></div>
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
