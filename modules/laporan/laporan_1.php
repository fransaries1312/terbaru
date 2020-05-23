<?php
//$aksi = "modules/rekap/aksi_rekap.php";
$act = $_GET['act'];
switch ($act) {
    // Tampil Rekap Data Penjualan
    default:
        ?>


        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Laporan Bulanan</strong>
                            
                        </div>
                        <div class="card-body">



                        <table id="bootstrap-data-table" class="table table-bordered">
                                <thead>
                                    <tr >
                                        <th>No</th>
                                        <th>Nama Obat</th>                                      
                                        <th>Tahun/Bulan</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tampil = mysqli_query($koneksi, "SELECT table_obat.nama_obat, CONCAT(YEAR(tanggal),'/',MONTH(tanggal)) AS tahun_bulan,SUM(data_rekap.jumlah) as sum_jumlah FROM data_rekap JOIN table_obat ON data_rekap.id_obat=table_obat.id_obat GROUP BY data_rekap.id_obat, MONTH(data_rekap.tanggal) ORDER BY MONTH(data_rekap.tanggal)");
                                    $no = 1;
                                    while ($r = mysqli_fetch_array($tampil)) {
                                        ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $r['nama_obat'] ?></td>                                          
                                            <td><?= $r['tahun_bulan'] ?></td>
                                            <td><?= $r['sum_jumlah'] ?></td>                                           
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        
                        </div>    
                        
                        
                    </div>
                </div>
            </div><!-- .animated -->
        </div><!-- .content -->

        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Laporan Tahunan</strong>
                            
                        </div> 
                        
                        <div class="card-body">                           
                                <table id="bootstrap-data-table" class="table table-bordered">
                                <thead>
                                    <tr >
                                        <th>No</th>
                                        <th>Nama Obat</th>                                      
                                        <th>Tahun</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tampil = mysqli_query($koneksi, "SELECT table_obat.nama_obat, YEAR(data_rekap.tanggal) as tahun, SUM(data_rekap.jumlah) as sum_jumlah FROM data_rekap JOIN table_obat ON data_rekap.id_obat=table_obat.id_obat GROUP BY data_rekap.id_obat, YEAR(data_rekap.tanggal) ORDER BY YEAR(data_rekap.tanggal)");
                                    $no = 1;
                                    while ($r = mysqli_fetch_array($tampil)) {
                                        ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $r['nama_obat'] ?></td>                                          
                                            <td><?= $r['tahun'] ?></td>
                                            <td><?= $r['sum_jumlah'] ?></td>                                           
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
        </div>
        
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Laporan Harian</strong>
                            
                        </div> 
                        
                        <div class="card-body">                           
                                <table id="bootstrap-data-table" class="table table-bordered">
                                <thead>
                                    <tr >
                                        <th>No</th>
                                        <th>Nama Obat</th>                                      
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tampil = mysqli_query($koneksi, "SELECT table_obat.nama_obat, data_rekap.tanggal, data_rekap.jumlah  FROM data_rekap JOIN table_obat ON data_rekap.id_obat=table_obat.id_obat ORDER BY MONTH(data_rekap.tanggal)");
                                    $no = 1;
                                    while ($r = mysqli_fetch_array($tampil)) {
                                        ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $r['nama_obat'] ?></td>                                          
                                            <td><?= $r['tanggal'] ?></td>
                                            <td><?= $r['jumlah'] ?></td>                                           
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
        </div>       
        <?php
        break;

}
?>