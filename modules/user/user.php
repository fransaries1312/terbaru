<?php
$aksi = "modules/user/aksi_user.php";
$act = $_GET['act'];
switch ($act) {
    // Tampil User
    default:
        ?>


        <div class="animated fadeIn">
            <div class="row">

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Tabel User</strong>
                            <a type="button"  href="?module=user&act=add" class="btn btn-outline-info pull-right">Input Data<i class="fa ti-file"></i></a>
                        </div>
                        <div class="card-body">
                             <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr >
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Level</th>
                                        <th>Username</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tampil = mysqli_query($koneksi, "SELECT * FROM `user`");
                                    $no = 1;
                                    while ($r = mysqli_fetch_array($tampil)) {
                                        ?>
                                        <tr>
                                            <td><?=$no++?></td>
                                            <td><?=$r['nama']?></td>
                                            <td><?=$r['level']?></td>
                                            <td><?=$r['username']?></td>
                                            <td>
                                                <a href="?module=user&act=edit&id=<?php echo $r[id_user]; ?>" type="button" class="btn btn-outline-warning"><i class="fa fa-pencil"></i></a>
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

    // Form Tambah college_schedule
    case "add":
        ?>
        
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>INPUT</strong> Data User
                        </div>
                        <div class="card-body card-block">
                            <form action="<?= $aksi ?>?module=user&act=input" method="POST"  class="form-horizontal">

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Nama</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="text-input" name="nama" placeholder="Gilang Farma" class="form-control">
                                        <small class="form-text text-muted">Masukan nama panjang</small>
                                    </div>
                                </div>          
                              
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="select" class=" form-control-label">Level</label></div>
                                    <div class="col-12 col-md-9">
                                        <select name="level" id="select" class="form-control">
                                            <option value="">Pilih Level</option>
                                            <option value="admin">Admin </option>
                                            <option value="user">User </option>
                                        </select>
                                    </div>
                                </div>
                                
                               <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Username</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="text-input" name="username" placeholder="GilangFarma" class="form-control">
                                        <small class="form-text text-muted">Masukan Username</small>
                                    </div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Password</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="password" id="text-input" name="password" class="form-control">
                                        <small class="form-text text-muted">Masukan Password</small>
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
            </div>
        </div>
        
        
        <?php
        break;

    // Form Edit User
    case "edit":
       $edit = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user='$_GET[id]'");
       $r = mysqli_fetch_array($edit);
        
       ?>
          
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>EDIT</strong> Data User
                        </div>
                        <div class="card-body card-block">
                            <form action="<?= $aksi ?>?module=user&act=update" method="POST"  class="form-horizontal">

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Nama</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="text-input" name="nama" class="form-control" value="<?php echo $r[nama]; ?>">
                                        <small class="form-text text-muted">Masukan nama panjang</small>
                                    </div>
                                </div>          
                              
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="select" class=" form-control-label">Level</label></div>
                                    <div class="col-12 col-md-9">
                                        <select name="level" id="select" class="form-control">
                                            <option value="">Pilih Level</option>
                                            <option value="admin" <?php echo $r[level] == 'admin' ? 'selected' : NULL; ?> >Admin </option>
                                            <option value="user" <?php echo $r[level] == 'user' ? 'selected' : NULL; ?> >User </option>
                                        </select>
                                    </div>
                                </div>
                                
                               <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Username</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="text-input" name="username" class="form-control" value="<?php echo $r[username]; ?>">
                                        <small class="form-text text-muted">Masukan Username</small>
                                    </div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Password</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="password" id="text-input" name="password" class="form-control" >
                                        <small class="form-text text-muted">Password Anda</small>
                                    </div>
                                </div>

                                <input type="hidden" id="text-input" name="id_user" class="form-control" value="<?php echo $r[id_user]; ?>">
                                
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