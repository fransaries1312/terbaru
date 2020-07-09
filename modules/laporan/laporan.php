<?php
$aksi = "modules/laporan/aksi_laporan.php";
$act = $_GET['act'];
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

switch ($act) {
    // Tampil Rekap Data Penjualan
    default:
        ?>
<div class="animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Laporan</strong>
                </div>
                <div class="acard-body" style="margin-left: 2em;margin-top: 1em;">
                    <form class="form-horizontal" method="POST" action="?module=laporan&act=tampil">
                        <div class="row form-group">
                            <div class="col col-md-1"><label for="select" class=" form-control-label">Filter</label></div>
                                        <div class="col col-md-9">
                                        <div class="form-check-inline form-check">
                                                <label for="inline-radio1" class="form-check-label ">
                                                    <input type="radio" id="harian" name="radio" value="harian" class="form-check-input" checked>Harian
                                                </label>
                                                &nbsp&nbsp
                                                <label for="inline-radio2" class="form-check-label ">
                                                    <input type="radio" id="bulanan" name="radio" value="bulanan" class="form-check-input">Bulanan
                                                </label>
                                                &nbsp&nbsp
                                                <label for="inline-radio3" class="form-check-label ">
                                                    <input type="radio" id="custom" name="radio" value="custom" class="form-check-input">Custom
                                                </label>
                                            </div>
                                        </div>     
                           <input type="hidden" name="module" value="laporan">
                        </div>
                        <div class="row form-group" id="harian_tgl" style="display: none">
                            <div class="col col-md-1"><label for="select" class=" form-control-label">Tanggal</label></div>
                            <div class="col-md-3">
                                <input type="text" name="tanggal_harian" value="<?php echo date('d-m-Y')?>" class="form-control datepicker">
                            </div>
                        </div>

                        <div class="row form-group" id="bulanan_tgl" style="display: none">
                            <div class="col col-md-1"><label for="select" class=" form-control-label">Bulan</label></div>
                            <div class="col-md-3">
                                <input type="text" name="bulan" value="<?php echo date('m-Y')?>" class="form-control datepicker bulan">
                            </div>
                        </div>

                        <div class="row form-group" id="custom_tgl" style="display: none">
                            <div class="col col-md-1"><label for="select" class=" form-control-label">Range</label></div>
                            <div class="col-md-3">
                                <input type="text" name="tanggal_awal" value="<?php echo isset($_GET['tanggal_awal'])?$_GET['tanggal_awal']:date('d-m-Y')?>" class="form-control datepicker">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="tanggal_akhir" value="<?php echo isset($_GET['tanggal_akhir'])?$_GET['tanggal_akhir']:date('d-m-Y')?>" class="form-control datepicker">
                            </div>
                        </div>
                         
                          <div class="col-12 col-md-8 offset-md-5">
                                <button type="submit" value="proses" name="proses" class="btn btn-primary btn-sm">
                                    <i class="fa fa-dot-circle-o col-md"> PROSES </i> 
                                </button>
                            </div>
                    </form>
                    <hr>

                    <?php
                    
                       function week_between_two_dates($start_date, $end_date) {
                        $p = new DatePeriod(
                                new DateTime($start_date), new DateInterval('P1M'), new DateTime($end_date)
                        );
                        foreach ($p as $w) {
                            $bulan[] = $w->format('Y/m');
                            $bulan1[] = $w->format('Y/m');
                            $bulan2[] = $w->format('Y/m');
                        }
                        return $bulan;
                    }
                    
                     function getTotal($periode, $data) {

                        $array = array();
                        for ($i = 0; $i < count($periode); $i++) {
                            for ($j = 0; $j < count($data); $j++) {
                                if ($periode[$i] == ($data[$j]['tahun_bulan'])) {
                                    $array[$i] = floatval($data[$j]['jumlah']);
                                    break;
                                } else {
                                    $array[$i] = 0;
                                }
                            }
                        }
                        return $array;
                    }

                     {

                        ?>
                       
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        select();

        $('input[type=radio][name=radio]').on('change',function(){
            select();
        })

        $(".bulan").datepicker( {
            format: "mm-yyyy",
            viewMode: "months", 
            minViewMode: "months"
        });
    })
    function select()
    {
        if($("input[name='radio']:checked").val()=='harian')
        {
            $('#harian_tgl').fadeIn();
            $('#bulanan_tgl').fadeOut();
            $('#custom_tgl').fadeOut();
        }
        else if($("input[name='radio']:checked").val()=='bulanan')
        {
            $('#harian_tgl').fadeOut();
            $('#bulanan_tgl').fadeIn();
            $('#custom_tgl').fadeOut();
        }
        else if($("input[name='radio']:checked").val()=='custom')
        {
            $('#harian_tgl').fadeOut();
            $('#bulanan_tgl').fadeOut();
            $('#custom_tgl').fadeIn();
        }
    }
</script>     
        
        <?php
        break;
        case 'tampil':
        if(isset($_POST['proses'])){
        $mode=$_POST['radio'];
        switch($mode)
        {
            case 'harian':
            $tanggal=new DateTime($_POST['tanggal_harian']);
        ?>
         <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Laporan Harian</strong>
                            
                        </div> 
                        
                        <div class="card-body">    
                         Export File : <a href="export-excel.php?tanggal=<?php echo $tanggal->format('Y-m-d') ?>&mode=<?php echo $mode?>" class="btn btn-success btn-sm" target="_blank">Excel Export</a>
                         <a href="export-pdf.php?tanggal=<?php echo $tanggal->format('Y-m-d') ?>&mode=<?php echo $mode?>" class="btn btn-danger btn-sm" target="_blank">PDF Export</a>
                         <br><hr/>                                  
                                <table id="bootstrap-data-table" class="table table-bordered"> 
                            
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>nota</th>
                                        <th>Nama Obat</th>
                                        <th>Jumlah</th>                                      
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tampil = mysqli_query($koneksi, "SELECT detail_rekap.id_rek,detail_rekap.jumlah,detail_rekap.subtotal,table_obat.nama_obat,table_obat.bentuk_obat,table_rekap.tanggal,table_rekap.cara_bayar,table_rekap.nota FROM table_rekap JOIN detail_rekap ON detail_rekap.id_daterek=table_rekap.id_daterek JOIN table_obat ON detail_rekap.id_obat=table_obat.id_obat WHERE table_rekap.tanggal='".$tanggal->format('Y-m-d')."'");
                                    $no = 1;
                                    while ($r = mysqli_fetch_array($tampil)) {
                                        ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $r['nota'] ?></td>
                                            <td><?= $r['nama_obat'] ?><?= " " ?><?= $r['bentuk_obat'] ?></td>
                                            <td><?= $r['jumlah'] ?></td>                                          
                                            <td><?= $r['subtotal'] ?></td>
                                                                                       
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4"><b>Total</b></td>
                                        
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                            
                            <div class="card-footer">
                                <div class="text-right">
                                <a class="btn btn-primary btn-md" href="?module=laporan" style="color:white">Kembali</a>
                                </div>
                            </div>
                      </div>
                </div>
            </div><!-- .animated -->
        </div>

        <?php 
            break;
            case 'bulanan':
            $bulan=explode('-', $_POST['bulan']);
            ?>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Laporan Bulanan</strong>
                    </div>

                    <div class="card-body">
                        Export File : <a href="export-excel.php?bulan=<?php echo $bulan[0] ?>&tahun=<?php echo $bulan[1] ?>&mode=<?php echo $mode?>" class="btn btn-success btn-sm" target="_blank">Excel Export</a>
                          <a href="export-pdf.php?bulan=<?php echo $bulan[0] ?>&tahun=<?php echo $bulan[1] ?>&mode=<?php echo $mode?>" class="btn btn-danger btn-sm" target="_blank">PDF Export</a>
                         <br><hr/>

                        <table id="bootstrap-data-table" class="table table-bordered">
                        
                            <thead>
                            <tr >
                                <th>No</th>
                                <th>nota</th>
                                <th>Nama Obat</th>
                                <th>Jumlah</th>                                      
                                <th>Subtotal</th>
                            </tr>
                            </thead>
                        
                            <tbody>
                            <?php
                            $tampil = mysqli_query($koneksi, "SELECT table_obat.nama_obat,table_obat.bentuk_obat, table_rekap.nota, CONCAT(YEAR(table_rekap.tanggal),'/',MONTH(table_rekap.tanggal)) AS tahun_bulan,SUM(detail_rekap.jumlah) as sum_jumlah, SUM(detail_rekap.subtotal) as sum_subtotal FROM table_rekap JOIN detail_rekap ON detail_rekap.id_daterek=table_rekap.id_daterek JOIN table_obat ON detail_rekap.id_obat=table_obat.id_obat WHERE MONTH(table_rekap.tanggal)='".$bulan[0]."' AND YEAR(table_rekap.tanggal)='".$bulan[1]."' GROUP BY detail_rekap.id_obat, MONTH(table_rekap.tanggal) ORDER BY MONTH(table_rekap.tanggal)");
                            $no = 1;
                            while ($r = mysqli_fetch_array($tampil)) {
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $r['nota'] ?></td>
                                <td><?= $r['nama_obat'] ?><?= " " ?><?= $r['bentuk_obat'] ?></td>
                                <td><?= $r['sum_jumlah'] ?></td>                                          
                                <td><?= $r['sum_subtotal'] ?></td>                                         
                            </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                        
                            <tfoot>
                            <tr>
                                <td colspan="4"><b>Total</b></td>         
                                <td></td>
                            </tr>
                            </tfoot> 
                    </table>

                    </div>

                    <div class="card-footer">
                        <div class="text-right">
                            <a class="btn btn-primary btn-md" href="?module=laporan" style="color:white">Kembali</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


            
            <?php break;
            case 'custom':
            $tanggal_awal=new DateTime($_POST['tanggal_awal']);
            $tanggal_akhir=new DateTime($_POST['tanggal_akhir']);
            ?>
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Laporan Range</strong>
                            
                        </div> 
                        
                        <div class="card-body">    
                         Export File : <a href="export-excel.php?tanggal_awal=<?php echo $tanggal_awal->format('Y-m-d') ?>&tanggal_akhir=<?php echo $tanggal_akhir->format('Y-m-d') ?>&mode=<?php echo $mode?>" class="btn btn-success btn-sm" target="_blank">Excel Export</a>
                         <a href="export-pdf.php?tanggal_awal=<?php echo $tanggal_awal->format('Y-m-d') ?>&tanggal_akhir=<?php echo $tanggal_akhir->format('Y-m-d') ?>&mode=<?php echo $mode?>" class="btn btn-danger btn-sm" target="_blank">PDF Export</a>
                         <br><hr/>                       
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
                                    $tampil = mysqli_query($koneksi, "SELECT table_rekap.nota,table_obat.nama_obat,table_obat.bentuk_obat, table_rekap.tanggal, detail_rekap.jumlah, detail_rekap.subtotal FROM table_rekap JOIN detail_rekap ON detail_rekap.id_daterek=table_rekap.id_daterek JOIN table_obat ON detail_rekap.id_obat=table_obat.id_obat WHERE table_rekap.tanggal>='".$tanggal_awal->format('Y-m-d')."' AND table_rekap.tanggal<='".$tanggal_akhir->format('Y-m-d')."'");
                                    $no = 1;
                                    while ($r = mysqli_fetch_array($tampil)) {
                                        ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $r['nota'] ?></td>
                                            <td><?= $r['nama_obat'] ?><?= " " ?><?= $r['bentuk_obat'] ?></td>
                                            <td><?= $r['jumlah'] ?></td>                                          
                                            <td><?= $r['subtotal'] ?></td> </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <td colspan="4"><b>Total Penjualan </b></td>         
                                            <td></td>
                                        </tr>
                                    </tfoot>

                            </table>
                        </div>
                            
                        <div class="card-footer">
                            <div class="text-right">
                                <a class="btn btn-primary btn-md" href="?module=laporan" style="color:white">Kembali</a>
                            </div>
                        </div>
     
                    </div>
                </div>
            </div><!-- .animated -->
        </div> 

            <?php break;
        }
    }
        break;

}

?>