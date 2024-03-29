<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.bundle.min.js"></script>
<script src="assets/js/init/chartjs-init.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

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

$act = $_GET['act'];
switch ($act) {
    // Tampil Data Obat
    default:
        $t_ses = 0;
        $sd = 0;
        $t_wma = 0;
        $t_tp = 0;

        $mode='default';

        $x = array();
        $d = array();

        $d_ses=array();
 
         
        function between_month($tgl_akhir)
        {
            $bulan=new DateTime($tgl_akhir);
            $tgl_akhir=$bulan->format('Y-m-d');
            $bulan_kedepan=$bulan->modify('+4 month')->format('Y-m-d');
             $p = new DatePeriod(
              new DateTime($tgl_akhir), new DateInterval('P1M'), new DateTime($bulan_kedepan)
              );
             foreach ($p as $w) {
              $bulan1[] = $w->format('Y/m');
            }
            return $bulan1;
        }

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


$tgl_awal1=isset($_GET['tanggal_awal']) && !empty($_GET['tanggal_awal'])?new DateTime(date('Y-m-d',strtotime($_GET['tanggal_awal']))):'';
$tgl_akhir1=isset($_GET['tanggal_akhir']) && !empty($_GET['tanggal_akhir'])?new DateTime(date('Y-m-d',strtotime($_GET['tanggal_akhir']))):'';

if(isset($_GET['tanggal_awal']) && isset($_GET['tanggal_akhir']))
{
    $mode='peramalan';
    $diff=$tgl_awal1->diff($tgl_akhir1);
    $selisih=($diff->format('%y') * 12) + $diff->format('%m');


    if(isset($selisih) && $selisih >4){

    $tanggal=week_between_two_dates(date('Y-m-d',strtotime($_GET['tanggal_awal'])), date('Y-m-d',strtotime($_GET['tanggal_akhir']))); 

$tgl_awal=$tgl_awal1->format('Y-m-d');
$tgl_akhir=$tgl_akhir1->format('Y-m-d');

        // $result = mysqli_query($koneksi, "SELECT SUM(jumlah) as jumlah,CONCAT(DATE_FORMAT(tanggal, '%Y'),'/',DATE_FORMAT(tanggal, '%m')) AS tahun_bulan FROM data_rekap WHERE id_obat = $_GET[id_obat] and tanggal >= '$tgl_awal' and 
        //     tanggal <='$tgl_akhir' GROUP BY MONTH(tanggal), YEAR(tanggal) ORDER BY tanggal  ASC");

        // $result = mysqli_query($koneksi, "SELECT SUM(detail_rekap.jumlah) as jumlah,CONCAT(DATE_FORMAT(table_rekap.tanggal, '%Y'),'/',DATE_FORMAT(table_rekap.tanggal, '%m')) AS tahun_bulan FROM detail_rekap JOIN table_rekap ON table_rekap.id_daterek=detail_rekap.id_daterek WHERE detail_rekap.id_obat = $_GET[id_obat] and table_rekap.tanggal >= '$tgl_awal' and 
        //     table_rekap.tanggal <='$tgl_akhir' GROUP BY MONTH(table_rekap.tanggal), YEAR(table_rekap.tanggal) ORDER BY table_rekap.tanggal  ASC");

        $result = mysqli_query($koneksi, "SELECT SUM(detail_rekap.jumlah * table_obat.satuan) as jumlah,CONCAT(DATE_FORMAT(table_rekap.tanggal, '%Y'),'/',DATE_FORMAT(table_rekap.tanggal, '%m')) AS tahun_bulan FROM detail_rekap JOIN table_rekap ON table_rekap.id_daterek=detail_rekap.id_daterek JOIN table_obat ON table_obat.id_obat=detail_rekap.id_obat WHERE table_obat.nama_obat LIKE '%$_GET[id_obat]%' and table_rekap.tanggal >= '$tgl_awal' and 
            table_rekap.tanggal <='$tgl_akhir' GROUP BY MONTH(table_rekap.tanggal), YEAR(table_rekap.tanggal) ORDER BY table_rekap.tanggal  ASC");

        if (!$result) {
            printf("Error: %s\n", mysqli_error($koneksi));
            exit();
        }


        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $d[$t_ses]=$row;
            // $d_ses[$t_ses] = $row['jumlah'];      
            //            if ($t_ses <= 4) {
            //     $sd += $row['jumlah'];             
            // }
            $t_ses++;
            
            // $d_wma[$t_wma] = $row['jumlah'];
            $t_wma++;

            // $d_tp[$t_tp] = $row['jumlah'];
            $t_tp++;

            $bulan_tahun = $row['MONTH(tanggal)'];
 
        }

    
        $x= array_merge($tanggal,between_month($tgl_akhir));
        $d=getTotal($tanggal,$d);

        $d_ses=$d;
        $d_wma=$d;
        $d_tp=$d;

         for($i=0;$i<count($d_ses);$i++)
        {
            if($i<4)
            {
                $sd+=$d_ses[$i];
            }
        }


//single exponential smoothing
        $n_ses = count($d_ses);
        // $a_ses = 2 / ($n_ses+1);
        $a_ses=[0.10,0.11,0.12,0.13,0.14,0.15,0.16,0.17,0.18,0.19,0.20,0.21,0.22,0.23,0.24,0.25,0.26,0.27,0.28,0.29,0.30,0.31,0.32,0.33,0.34,0.35,0.36,0.37,0.38,0.39,0.40,0.41,0.42,0.43,0.44,0.45,0.46,0.47,0.48,0.49,0.50,0.51,0.52,0.53,0.54,0.55,0.56,0.57,0.58,0.59,0.60,0.61,0.62,0.63,0.64,0.65,0.66,0.67,0.68,0.96,0.70,0.71,0.72,0.73,0.74,0.75,0.76,0.77,0.78,0.79,0.80,0.81,0.82,0.83,0.84,0.85,0.86,0.87,0.88,0.89,0.90,0.91,0.92,0.93,0.94,0.95,0.96,0.97,0.98,0.99,];
        // $a_ses=[0.1];

  
    $PE = array();
        $MAPE = array();
       


        $new_ses = array();
        for($i=0;$i<count($a_ses);$i++){
            $PE[$i][0]=0;
            for($j=0;$j<count($d_ses);$j++){
                if ($j == 0) {
                    $f_ses[$i][$j] = $sd / 4;
                } else {

                    $f_ses[$i][$j] = $d_ses[$j - 1] * $a_ses[$i] + (1 - $a_ses[$i]) * $f_ses[$i][$j - 1];
                }

                $PE[$i][$j] = $d_ses[$j] == 0 ? 0 : abs((($d_ses[$j] - $f_ses[$i][$j]) / $d_ses[$j]) * 100);

            
            }
            $MAPE[$i] = array_sum($PE[$i])/(count($d_ses));
        }

        //   echo '<pre>';
        // print_r($d_ses);
        // die;
        // echo '</pre>';


        $bestAlphaIndex = array_search(min($MAPE), $MAPE);
    
    
        for($key=0;$key<count($d_ses);$key++)
        {
            array_push($new_ses, $f_ses[$bestAlphaIndex][$key]);
        } 
      
         
        $f_ses[(count($d_ses)-1) + 1] = $d_ses[count($d_ses)-1] * $a_ses[$bestAlphaIndex] + (1 - $a_ses[$bestAlphaIndex]) * $f_ses[$bestAlphaIndex][count($d_ses)-1];
   
        $f_ses[(count($d_ses)-1) + 2] = 0 * $a_ses[$bestAlphaIndex] + (1 - $a_ses[$bestAlphaIndex]) * $f_ses[(count($d_ses)-1) + 1];

        $f_ses[(count($d_ses)-1) + 3] = 0 * $a_ses[$bestAlphaIndex] + (1 - $a_ses[$bestAlphaIndex]) * $f_ses[(count($d_ses)-1) + 2];

        $f_ses[(count($d_ses)-1) + 4] = 0 * $a_ses[$bestAlphaIndex] + (1 - $a_ses[$bestAlphaIndex]) * $f_ses[(count($d_ses)-1) + 3];

        array_push($new_ses, $f_ses[(count($d_ses)-1) + 1]);
        array_push($new_ses, $f_ses[(count($d_ses)-1) + 2]);
        array_push($new_ses, $f_ses[(count($d_ses)-1) + 3]);
        array_push($new_ses, $f_ses[(count($d_ses)-1) + 4]);

 
        foreach ($d_ses as $key => $value) {

            $e_ses[$key] = $d_ses[$key] - $f_ses[$bestAlphaIndex][$key];
        }
           

        $smad_ses = 0;
        foreach ($d_ses as $key => $value) {

            $mad_ses[$key] = abs($e_ses[$key]);
            $smad_ses +=$mad_ses[$key];
        }
        $avmad_ses = $smad_ses / $n_ses;

        $smape_ses = 0;
        foreach ($d_ses as $key => $value) {

            $mape_ses[$key] = ($mad_ses[$key] / $d_ses[$key]) * 100;
            $smape_ses +=$mape_ses[$key];
        }
        $avmape_ses = $smape_ses / $n_ses;

        // echo '<pre>';
        // print_r($f_ses);print_r($d_ses);
        // die;
        // echo '</pre>';

//weighted moving average
        $b1_wma = 1;
        $b2_wma = 2;
        $b3_wma = 3;
        $sb_wma = $b1_wma + $b2_wma + $b3_wma;
        $n_wma = count($d_wma);
        $new_wma = array();
        foreach ($d_wma as $key => $value) {
            if ($key < 3) {
                $f_wma[$key] = $d_wma[$key];
                array_push($new_wma, NULL);
            } else {

                $f_wma[$key] = (($d_wma[$key - 1] * $b3_wma) + ($d_wma[$key - 2] * $b2_wma) + ($d_wma[$key - 3] * $b1_wma)) / $sb_wma;
                array_push($new_wma, $f_wma[$key]);
            }
        }

        $f_wma[$key + 1] = (($d_wma[$key] * $b3_wma) + ($d_wma[$key - 1] * $b2_wma) + ($d_wma[$key - 2] * $b1_wma)) / $sb_wma;

        $f_wma[$key + 2] = (($f_wma[$key + 1] * $b3_wma) + ($d_wma[$key] * $b2_wma) + ($d_wma[$key - 1] * $b1_wma)) / $sb_wma;

        $f_wma[$key + 3] = (($f_wma[$key + 2] * $b3_wma) + ($f_wma[$key + 1] * $b2_wma) + ($d_wma[$key] * $b1_wma)) / $sb_wma;

        $f_wma[$key + 4] = (($f_wma[$key + 3] * $b3_wma) + ($f_wma[$key + 2] * $b2_wma) + ($f_wma[$key + 1] * $b1_wma)) / $sb_wma;

        //           echo '<pre>';
        // print_r($f_wma);
        // die;
        // echo '</pre>';

        array_push($new_wma, $f_wma[$key + 1]);
        array_push($new_wma, $f_wma[$key + 2]);
        array_push($new_wma, $f_wma[$key + 3]);
        array_push($new_wma, $f_wma[$key + 4]);

        foreach ($d_wma as $key => $value) {

            $e_wma[$key] = $d_wma[$key] - $f_wma[$key];
        }

        $smad_wma = 0;
        foreach ($d_wma as $key => $value) {
            $mad_wma[$key] = abs($e_wma[$key]);
            $smad_wma +=$mad_wma[$key];
        }
        $avmad_wma = $smad_wma / $n_wma;

        $smape_wma = 0;
        foreach ($d_wma as $key => $value) {
            $mape_wma[$key] = ($mad_wma[$key] / $d_wma[$key]) * 100;
            $smape_wma +=$mape_wma[$key];
        }
        $avmape_wma = $smape_wma / ($n_wma-3);
//TP
        $n_tp = count($d_tp);
//sum y
        $sd_tp = 0;
        foreach ($d_tp as $key => $value) {
            //  $sd +=$d[$key];
            $sd_tp +=$value;
        }

//x pow 2 
        foreach ($d_tp as $key => $value) {

            $px_tp[$key] = pow($key+1, 2);
        }
//sum x
        $sx_tp = 0;
        foreach ($d_tp as $key => $value) {

            $sx_tp +=($key+1);
        }

//sum x2
        $spx_tp = 0;
        foreach ($d_tp as $key => $value) {

            $spx_tp +=$px_tp[$key];
        }
//xy
        foreach ($d_tp as $key => $value) {

            $xy_tp[$key] = ($key+1) * $d_tp[$key];
        }
//sumxy
        $sxy_tp = 0;
        foreach ($d_tp as $key => $value) {

            $sxy_tp +=$xy_tp[$key];
        }

        $ax_tp = $sx_tp / $n_tp;

        $ay_tp = $sd_tp / $n_tp;


        $b_tp = ($sxy_tp - ($n_tp * $ax_tp * $ay_tp)) / ($spx_tp - $n_tp * pow($ax_tp, 2));

        $a_tp = $ay_tp - ($b_tp * $ax_tp);

        $new_tp = array();
        foreach ($d_tp as $key => $value) {

            $f_tp[$key] = $a_tp + ($b_tp * $key);
            array_push($new_tp, $f_tp[$key]);
        }
        $f_tp[$key + 1] = $a_tp + ($b_tp * ($key + 1));
        $f_tp[$key + 2] = $a_tp + ($b_tp * ($key + 2));
        $f_tp[$key + 3] = $a_tp + ($b_tp * ($key + 3));
        $f_tp[$key + 4] = $a_tp + ($b_tp * ($key + 4));

        array_push($new_tp, $f_tp[$key + 1]);
        array_push($new_tp, $f_tp[$key + 2]);
        array_push($new_tp, $f_tp[$key + 3]);
        array_push($new_tp, $f_tp[$key + 4]);

        foreach ($d_tp as $key => $value) {
            $e_tp[$key] = $d_tp[$key] - $f_tp[$key];
        }
//mad
        $sm_tp = 0;
        foreach ($d_tp as $key => $value) {
            $m_tp[$key] = abs($e_tp[$key]);
            $sm_tp +=$m_tp[$key];
        }
        $rm_tp = $sm_tp / $n_tp;
//mape
        $sp_tp = 0;
        foreach ($d_tp as $key => $value) {
            $p_tp[$key] = ($m_tp[$key] / $d_tp[$key]) * 100;
            $sp_tp +=$p_tp[$key];
        }
        $asp_tp = $sp_tp / $n_tp;
}
else
{
    echo "<script>
toastr.warning('Parameter tanggal harus lebih dari 4 bulan','Info');
</script>";
}
}
        
        ?>

        <div class="animated fadeIn">
            <div class="row">

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Peramalan</strong>
                        </div>
                        <div class="card-body">
                            <form class="form-horizontal">

                            <div class="row form-group">
                            <div class="col col-md-2"><label for="select" class=" form-control-label">Tanggal</label></div>
                            <div class="col-md-2">
                                <input type="text" name="tanggal_awal" value="<?php echo isset($_GET['tanggal_awal'])?$_GET['tanggal_awal']:date('d-m-Y')?>" class="form-control datepicker">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="tanggal_akhir" value="<?php echo isset($_GET['tanggal_akhir'])?$_GET['tanggal_akhir']:date('d-m-Y')?>" class="form-control datepicker">
                            </div>
                            </div>
                                
                                
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="select" class=" form-control-label">Nama Obat</label></div>
                                    <div class="col-12 col-md-3">
                                <select data-placeholder="Pilih Obat" name="id_obat" class="standardSelect" tabindex="1" required>
                                    <option value="" label="default">---Pilih Obat---</option>

                                            <?php
                                            $tampil = mysqli_query($koneksi, "SELECT nama_obat FROM table_obat GROUP BY nama_obat");

                                            while ($r = mysqli_fetch_array($tampil)) {
                                                ?>
                                                <option value="<?= $r['nama_obat'] ?>" <?php echo $r['nama_obat'] == $_GET['id_obat'] ? 'selected' : NULL; ?>><?= $r['nama_obat'] ?></option>
                                                <?php
                                            }
                                            ?>
                                
                            </select>
                                    </div>

                                    
                                    <input type="hidden" name="module" value="peramalan">
                                    <div class="col-12 col-md-3">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fa fa-dot-circle-o"></i> Proses
                                        </button>
                                    </div>

                                </div> 

                            </form>
                            <?php
                            if($mode=='peramalan'){
                            if (!empty($_GET['id_obat'])) {
                                ?>    

                                <table class="table table-bordered">    
                                    <thead>
                                        <tr>
                                            <th>Periode</th>
                                            <th >Single Exponential (<?= $a_ses[$bestAlphaIndex] ?>)</th>
                                            <th >Weighted Moving Average</th>
                                            <th >Trend Projection</th>
                                        </tr>
                                    </thead>
                                        <?php if(isset($selisih) && $selisih>4){?>
                                    <tbody>

                                        <tr>
                                            <td>1</td>
                                            <td><?= round($f_ses[$key + 1], 2) ?></td>
                                            <td><?= round($f_wma[$key + 1], 2) ?></td>
                                            <td><?= round($f_tp[$key + 1], 2) ?></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td><?= round($f_ses[$key + 2], 2) ?></td>
                                            <td><?= round($f_wma[$key + 2], 2) ?></td>
                                            <td><?= round($f_tp[$key + 2], 2) ?></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td><?= round($f_ses[$key + 3], 2) ?></td>
                                            <td><?= round($f_wma[$key + 3], 2) ?></td>
                                            <td><?= round($f_tp[$key + 3], 2) ?></td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td><?= round($f_ses[$key + 4], 2) ?></td>
                                            <td><?= round($f_wma[$key + 4], 2) ?></td>
                                            <td><?= round($f_tp[$key + 4], 2) ?></td>
                                        </tr>

                                        <tr>
                                            <td>MAD</td>
                                            <td><?= round($avmad_ses, 2) ?></td>
                                            <td><?= round($avmad_wma, 2) ?></td>
                                            <td><?= round($rm_tp, 2) ?></td>
                                        </tr>

                                        <tr>
                                            <td>MAPE</td>
                                            <td><?= round($avmape_ses, 2) ?></td>
                                            <td><?= round($avmape_wma, 2) ?></td>
                                            <td><?= round($asp_tp, 2) ?></td>
                                        </tr>
                                        <tr>
                                            <td>Best Alpha</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th>
                                                <a href="?id_obat=<?= $_GET[id_obat] ?>&module=peramalan&act=detail_ses&tanggal_awal=<?php echo $_GET['tanggal_awal']?>&tanggal_akhir=<?php echo $_GET['tanggal_akhir']?>" type="button" class="btn btn-outline-warning"> detail </a>
                                                <a href="?id_obat=<?= $_GET[id_obat] ?>&module=peramalan&act=tracking_ses&tanggal_awal=<?php echo $_GET['tanggal_awal']?>&tanggal_akhir=<?php echo $_GET['tanggal_akhir']?>" type="button" class="btn btn-outline-warning"> tracking </a>
                                            </th>
                                            <th >
                                                <a href="?id_obat=<?= $_GET[id_obat] ?>&module=peramalan&act=detail_wma&tanggal_awal=<?php echo $_GET['tanggal_awal']?>&tanggal_akhir=<?php echo $_GET['tanggal_akhir']?>" type="button" class="btn btn-outline-warning"> detail </a>
                                                <a href="?id_obat=<?= $_GET[id_obat] ?>&module=peramalan&act=tracking_wma&tanggal_awal=<?php echo $_GET['tanggal_awal']?>&tanggal_akhir=<?php echo $_GET['tanggal_akhir']?>" type="button" class="btn btn-outline-warning"> tracking </a>
                                            </th>
                                            <th>
                                                <a href="?id_obat=<?= $_GET[id_obat] ?>&module=peramalan&act=detail_tp&tanggal_awal=<?php echo $_GET['tanggal_awal']?>&tanggal_akhir=<?php echo $_GET['tanggal_akhir']?>" type="button" class="btn btn-outline-warning"> detail </a>
                                                <a href="?id_obat=<?= $_GET[id_obat] ?>&module=peramalan&act=tracking_tp&tanggal_awal=<?php echo $_GET['tanggal_awal']?>&tanggal_akhir=<?php echo $_GET['tanggal_akhir']?>" type="button" class="btn btn-outline-warning"> tracking </a>
                                            </th>
                                        </tr>
                                    </tfoot>
                                <?php }else{?>
                                    <tbody>
                                        <tr>
                                            <td colspan="4" style="text-align: center">Data Kosong</td>
                                        </tr>
                                    </tbody>
                                <?php }?>
                                </table>
                                <?php
                            }}
                            ?>
                        </div>
                    </div>
                    <?php
                    if (!empty($_GET['id_obat'])) {
                        ?>
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-3"> Grafik Data Peramalan </h4>
                                    <canvas id="sales-chart"></canvas>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>

            </div><!-- .animated -->
        </div><!-- .content -->


        <script>   
             jQuery(document).ready(function() {
        jQuery(".standardSelect").chosen({
            disable_search_threshold: 10,
            no_results_text: "data tidak ditemukan!",
            width: "100%"
        });


    });
            (function ($) {
                "use strict";


                //Sales chart
                var ctx = document.getElementById("sales-chart");
                ctx.height = 150;
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: <?= json_encode($x) ?>, 
                        type: 'line',
                        defaultFontFamily: 'Montserrat',
                        datasets: [{
                                label: "Aktual",
                                data: <?= json_encode($d) ?>,
                                backgroundColor: 'transparent',
                                borderColor: 'rgba(220,53,69,0.75)',
                                borderWidth: 3,
                                pointStyle: 'circle',
                                pointRadius: 5,
                                pointBorderColor: 'transparent',
                                pointBackgroundColor: 'rgba(220,53,69,0.75)',
                            },
                            {
                                label: "SES",
                                data: <?= json_encode($new_ses) ?>,
                                backgroundColor: 'transparent',
                                borderColor: 'rgba(100, 149, 237,0.75)',
                                borderWidth: 3,
                                pointStyle: 'circle',
                                pointRadius: 5,
                                pointBorderColor: 'transparent',
                                pointBackgroundColor: 'rgba(100, 149, 237,0.75)',
                            },
                            {
                                label: "WMA",
                                data: <?= json_encode($new_wma) ?>,
                                backgroundColor: 'transparent',
                                borderColor: 'rgba(218, 165, 32,0.75)',
                                borderWidth: 3,
                                pointStyle: 'circle',
                                pointRadius: 5,
                                pointBorderColor: 'transparent',
                                pointBackgroundColor: 'rgba(218, 165, 32,0.75)',
                            },
                            {
                                label: "Trend",
                                data: <?= json_encode($new_tp) ?>,
                                backgroundColor: 'transparent',
                                borderColor: 'rgba(40,167,69,0.75)',
                                borderWidth: 3,
                                pointStyle: 'circle',
                                pointRadius: 5,
                                pointBorderColor: 'transparent',
                                pointBackgroundColor: 'rgba(40,167,69,0.75)',
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        tooltips: {
                            mode: 'index',
                            titleFontSize: 12,
                            titleFontColor: '#000',
                            bodyFontColor: '#000',
                            backgroundColor: '#fff',
                            titleFontFamily: 'Montserrat',
                            bodyFontFamily: 'Montserrat',
                            cornerRadius: 3,
                            intersect: false,
                        },
                        legend: {
                            display: true,
                            labels: {
                                usePointStyle: true,
                                fontFamily: 'Montserrat',
                            },
                        },
                        scales: {
                            xAxes: [{
                                    display: true,
                                    gridLines: {
                                        display: true,
                                        drawBorder: true
                                    },
                                    scaleLabel: {
                                        display: false,
                                        labelString: 'Month'
                                    }
                                }],
                            yAxes: [{
                                    display: true,
                                    gridLines: {
                                        display: true,
                                        drawBorder: false
                                    },
                                    scaleLabel: {
                                        display: false,
                                        labelString: 'Value'
                                    }
                                }]
                        },
                        title: {
                            display: true,
                            text: 'Grafik data aktual dan peramalan'
                        }
                    }
                });

            })(jQuery);
            
            
        </script>

        <?php
        break;

// detail
    case "detail_ses":
  
    function getTotal($periode, $data) {

        $array = array();
        for ($i = 0; $i < count($periode); $i++) {
            for($j=0;$j<count($data);$j++)
            {             
              if($periode[$i]==$data[$j]['tahun_bulan'])
              {
                $array[$i]=$data[$j]['jumlah']; 
                break;
              }
              else
              {
                $array[$i]=0;  
              }
            }     
        }
        return $array; 
    }

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

     $tanggal=week_between_two_dates(date('Y-m-d',strtotime($_GET['tanggal_awal'])), date('Y-m-d',strtotime($_GET['tanggal_akhir'])));



     $d_ses=array();
        
        $t_ses = 0;
        $sd = 0;
        $tgl_awal=date('Y-m-d',strtotime($_GET['tanggal_awal']));
        $tgl_akhir=date('Y-m-d',strtotime($_GET['tanggal_akhir']));

        
        // $result = mysqli_query($koneksi, "SELECT data_rekap.*,CONCAT(DATE_FORMAT(tanggal, '%Y'),'/',DATE_FORMAT(tanggal, '%m')) AS tahun_bulan FROM data_rekap WHERE id_obat = $_GET[id_obat] and tanggal >= '$tgl_awal' and tanggal <='$tgl_akhir' GROUP BY MONTH(tanggal),YEAR(tanggal) ORDER BY tanggal ASC");


        $result = mysqli_query($koneksi, "SELECT SUM(detail_rekap.jumlah * table_obat.satuan) as jumlah,CONCAT(DATE_FORMAT(table_rekap.tanggal, '%Y'),'/',DATE_FORMAT(table_rekap.tanggal, '%m')) AS tahun_bulan FROM detail_rekap JOIN table_rekap ON table_rekap.id_daterek=detail_rekap.id_daterek JOIN table_obat ON table_obat.id_obat=detail_rekap.id_obat WHERE table_obat.nama_obat LIKE '%$_GET[id_obat]%' and table_rekap.tanggal >= '$tgl_awal' and 
            table_rekap.tanggal <='$tgl_akhir' GROUP BY MONTH(table_rekap.tanggal), YEAR(table_rekap.tanggal) ORDER BY table_rekap.tanggal  ASC");

        if (!$result) {
            printf("Error: %s\n", mysqli_error($koneksi));
            exit();
        }

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $d_ses[$t_ses] = $row;
            $t_ses++;
        }
        // dd($d_ses);


        $d_ses=getTotal($tanggal,$d_ses);

        for($i=0;$i<count($d_ses);$i++)
        {
            if($i<4)
            {
                $sd+=$d_ses[$i];
            }
        }


        $n_ses = count($d_ses);
       $a_ses=[0.10,0.11,0.12,0.13,0.14,0.15,0.16,0.17,0.18,0.19,0.20,0.21,0.22,0.23,0.24,0.25,0.26,0.27,0.28,0.29,0.30,0.31,0.32,0.33,0.34,0.35,0.36,0.37,0.38,0.39,0.40,0.41,0.42,0.43,0.44,0.45,0.46,0.47,0.48,0.49,0.50,0.51,0.52,0.53,0.54,0.55,0.56,0.57,0.58,0.59,0.60,0.61,0.62,0.63,0.64,0.65,0.66,0.67,0.68,0.96,0.70,0.71,0.72,0.73,0.74,0.75,0.76,0.77,0.78,0.79,0.80,0.81,0.82,0.83,0.84,0.85,0.86,0.87,0.88,0.89,0.90,0.91,0.92,0.93,0.94,0.95,0.96,0.97,0.98,0.99,];
        // $a_ses=[0.1];

  
    $PE = array();
        $MAPE = array();
        $new_ses = array();

        for($i=0;$i<count($a_ses);$i++){
            $PE[$i][0]=0;
            for($j=0;$j<count($d_ses);$j++){
                if ($j == 0) {
                    $f_ses[$i][$j] = $sd / 4;
                } else {

                    $f_ses[$i][$j] = $d_ses[$j - 1] * $a_ses[$i] + (1 - $a_ses[$i]) * $f_ses[$i][$j - 1];
                }

                $PE[$i][$j] = $d_ses[$j] == 0 ? 0 : abs((($d_ses[$j] - $f_ses[$i][$j]) / $d_ses[$j]) * 100);

            
            }
            $MAPE[$i] = array_sum($PE[$i])/(count($d_ses));
        }

        $bestAlphaIndex = array_search(min($MAPE), $MAPE);
        
        for($key=0;$key<count($d_ses);$key++)
        {
            array_push($new_ses, $f_ses[$bestAlphaIndex][$key]);
        } 



        $f_ses[(count($d_ses)-1) + 1] = $d_ses[count($d_ses)-1] * $a_ses[$bestAlphaIndex] + (1 - $a_ses[$bestAlphaIndex]) * $f_ses[$bestAlphaIndex][count($d_ses)-1];
   
        $f_ses[(count($d_ses)-1) + 2] = 0 * $a_ses[$bestAlphaIndex] + (1 - $a_ses[$bestAlphaIndex]) * $f_ses[(count($d_ses)-1) + 1];

        $f_ses[(count($d_ses)-1) + 3] = 0 * $a_ses[$bestAlphaIndex] + (1 - $a_ses[$bestAlphaIndex]) * $f_ses[(count($d_ses)-1) + 2];

        $f_ses[(count($d_ses)-1) + 4] = 0 * $a_ses[$bestAlphaIndex] + (1 - $a_ses[$bestAlphaIndex]) * $f_ses[(count($d_ses)-1) + 3];

        array_push($new_ses, $f_ses[(count($d_ses)-1) + 1]);
        array_push($new_ses, $f_ses[(count($d_ses)-1) + 2]);
        array_push($new_ses, $f_ses[(count($d_ses)-1) + 3]);
        array_push($new_ses, $f_ses[(count($d_ses)-1) + 4]);

        //         echo '<pre>';
        // print_r($new_ses);
        // die;
        // echo '</pre>';

 
        foreach ($d_ses as $key => $value) {

            $e_ses[$key] = $d_ses[$key] - $f_ses[$bestAlphaIndex][$key];
        }
//mad
        $sm_ses = 0;
        foreach ($d_ses as $key => $value) {

            $m_ses[$key] = abs($e_ses[$key]);
            $sm_ses +=$m_ses[$key];
        }
        $rm_ses = $sm_ses / $n_ses;
//mape
        $sp_ses = 0;
        foreach ($d_ses as $key => $value) {

            $p_ses[$key] = ($m_ses[$key] / $d_ses[$key]) * 100;

            $sp_ses +=$p_ses[$key];
        }
        $asp_ses = $sp_ses / $n_ses;

//rsfe
        foreach ($d_ses as $key => $value) {
            if ($key == 0) {
                $rsfe_ses[$key] = $e_ses[$key];
            } else {
                $rsfe_ses[$key] = $rsfe_ses[$key - 1] + $e_ses[$key];
            }
        }

//Cum MAD        

        foreach ($d_ses as $key => $value) {
            if ($key == 0) {
                $almad_ses[$key] = $m_ses[$key];
            } else {
                $almad_ses[$key] = $almad_ses[$key - 1] + $m_ses[$key];
            }
        }
//AlMad        

        foreach ($d_ses as $key => $value) {
            if ($key == 0) {
                $mad1[$key] = $almad_ses[$key];
            } else {
                $mad1[$key] = $almad_ses[$key] / ($key+1);
            }
        }

        //tracking
        foreach ($d_ses as $key => $value) {

                $tracking_ses[$key] = $rsfe_ses[$key] / $mad1[$key];
            
        }


//table
        ?>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Single Exponential Smoothing (a= <?= $a_ses[$bestAlphaIndex] ?>)</strong>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Periode Data</th>
                                <th scope="col">Data Aktual</th>
                                <th scope="col">Data Peramalan</th>
                                <th scope="col">Nilai Error</th>
                                <th scope="col">Absolute Error</th>
                                <th scope="col">Percentage Error</th>
                                <th scope="col">Nilai RSFE</th>
                                <th scope="col">Cum. AE</th>
                                <th scope="col">MAD TS</th>
                                <th scope="col">Tracking Signal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($new_ses as $key => $value) {
                                ?>
                                <tr>
                                    <td><?= $key+1 ?></td>
                                    <td><?= isset($d_ses[$key])?$d_ses[$key]:0 ?></td>
                                    <td><?= round($value,3) ?></td>
                                    <td><?= round(isset($e_ses[$key])?$e_ses[$key]:0,3) ?></td>
                                    <td><?= round(isset($m_ses[$key])?$m_ses[$key]:0,3) ?></td>
                                    <td><?= round(isset($p_ses[$key])?$p_ses[$key]:0,3) ?>%</td>
                                    <td><?= round(isset($rsfe_ses[$key])?$rsfe_ses[$key]:0,3) ?></td>
                                    <td><?= round(isset($almad_ses[$key])?$almad_ses[$key]:0,3) ?></td>
                                    <td><?= round(isset($mad1[$key])?$mad1[$key]:0,3) ?></td>
                                    <td><?= round(isset($tracking_ses[$key])?$tracking_ses[$key]:0,3) ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        <tfoot>
                            <tr>
                                <td></td>
                                <th colspan="3"> Jumlah</th>
                                <td><?= round($sm_ses, 2) ?></td>
                                <td><?= round($sp_ses, 2) ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <th colspan="3"> rata rata</th>
                                <td><?= round($rm_ses, 2) ?></td>
                                <td><?= round($asp_ses, 2) ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><a href="?id_obat=<?= $_GET[id_obat] ?>&module=peramalan&act=tracking_ses&tanggal_awal=<?php echo $_GET['tanggal_awal']?>&tanggal_akhir=<?php echo $_GET['tanggal_akhir']?>" type="button" class="btn btn-outline-warning"> tracking </a></td>
                            </tr>
                        </tfoot>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
        break;

// WMA
    case "detail_wma":

function getTotal($periode, $data) {

        $array = array();
        for ($i = 0; $i < count($periode); $i++) {
            for($j=0;$j<count($data);$j++)
            {             
              if($periode[$i]==$data[$j]['tahun_bulan'])
              {
                $array[$i]=$data[$j]['jumlah']; 
                break;
              }
              else
              {
                $array[$i]=0;  
              }
            }     
        }
        return $array; 
    }

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

     $tanggal=week_between_two_dates(date('Y-m-d',strtotime($_GET['tanggal_awal'])), date('Y-m-d',strtotime($_GET['tanggal_akhir'])));


     $d_wma=array();
        
        $t_wma = 0;
       // $sd = 0;
        $tgl_awal=date('Y-m-d',strtotime($_GET['tanggal_awal']));
        $tgl_akhir=date('Y-m-d',strtotime($_GET['tanggal_akhir']));

        
        // $result = mysqli_query($koneksi, "SELECT data_rekap.*,CONCAT(DATE_FORMAT(tanggal, '%Y'),'/',DATE_FORMAT(tanggal, '%m')) AS tahun_bulan FROM data_rekap WHERE id_obat = $_GET[id_obat] and tanggal >= '$tgl_awal' and tanggal <='$tgl_akhir' GROUP BY MONTH(tanggal),YEAR(tanggal) ORDER BY tanggal ASC");


        $result = mysqli_query($koneksi, "SELECT SUM(detail_rekap.jumlah * table_obat.satuan) as jumlah,CONCAT(DATE_FORMAT(table_rekap.tanggal, '%Y'),'/',DATE_FORMAT(table_rekap.tanggal, '%m')) AS tahun_bulan FROM detail_rekap JOIN table_rekap ON table_rekap.id_daterek=detail_rekap.id_daterek JOIN table_obat ON table_obat.id_obat=detail_rekap.id_obat WHERE table_obat.nama_obat LIKE '%$_GET[id_obat]%' and table_rekap.tanggal >= '$tgl_awal' and 
            table_rekap.tanggal <='$tgl_akhir' GROUP BY MONTH(table_rekap.tanggal), YEAR(table_rekap.tanggal) ORDER BY table_rekap.tanggal  ASC");

        if (!$result) {
            printf("Error: %s\n", mysqli_error($koneksi));
            exit();
        }

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $d_wma[$t_wma] = $row;
            $t_wma++;
        }


        $d_wma=getTotal($tanggal,$d_wma);

      
        $n_wma = count($d_wma);
        //$a_ses = 2 / ($n_ses+1);


        $b1_wma = 1;
        $b2_wma = 2;
        $b3_wma = 3;
        $sb_wma = $b1_wma + $b2_wma + $b3_wma;

        foreach ($d_wma as $key => $value) {
            if ($key < 3) {
                $f_wma[$key] = null;//$d_wma[$key];
            } else {
                $f_wma[$key] = (($d_wma[$key - 1] * $b3_wma) + ($d_wma[$key - 2] * $b2_wma) + ($d_wma[$key - 3] * $b1_wma)) / $sb_wma;
            }
        }

        $f_wma[$key + 1] = (($d_wma[$key] * $b3_wma) + ($d_wma[$key - 1] * $b2_wma) + ($d_wma[$key - 2] * $b1_wma)) / $sb_wma;

        $f_wma[$key + 2] = (($f_wma[$key + 1] * $b3_wma) + ($d_wma[$key] * $b2_wma) + ($d_wma[$key - 1] * $b1_wma)) / $sb_wma;

        $f_wma[$key + 3] = (($f_wma[$key + 2] * $b3_wma) + ($f_wma[$key + 1] * $b2_wma) + ($d_wma[$key] * $b1_wma)) / $sb_wma;

        $f_wma[$key + 4] = (($f_wma[$key + 3] * $b3_wma) + ($f_wma[$key + 2] * $b2_wma) + ($f_wma[$key + 1] * $b1_wma)) / $sb_wma;

//error
        foreach ($d_wma as $key => $value) {
        if ($key < 3) {
            $e_wma[$key] = null;
        } else {
            $e_wma[$key] = $d_wma[$key] - $f_wma[$key];
        }
        }

//mad
        $sm_wma = 0;
        foreach ($d_wma as $key => $value) {

            $m_wma[$key] = abs($e_wma[$key]);
            $sm_wma +=$m_wma[$key];
        }
        $rm_wma = $sm_wma / ($n_wma-3);
//mape
        $sp_wma = 0;
        foreach ($d_wma as $key => $value) {
            $p_wma[$key] = ($m_wma[$key] / $d_wma[$key]) * 100;
            $sp_wma +=$p_wma[$key];
        }
        $asp_wma = $sp_wma / ($n_wma-3);
//rsfe
        foreach ($d_wma as $key => $value) {
            if ($key == 0) {
                $rsfe_wma[$key] = $e_wma[$key];
            } else {
                $rsfe_wma[$key] = $rsfe_wma[$key - 1] + $e_wma[$key];
            }
        }

//Cum MAD        

        foreach ($d_wma as $key => $value) {
            if ($key == 0) {
                $almad_wma[$key] = $m_wma[$key];
            } else {
                $almad_wma[$key] = $almad_wma[$key - 1] + $m_wma[$key];
            }
        }
//AlMad        

        foreach ($d_wma as $key => $value) {
            if ($key == 0) {
                $mad1_wma[$key] = $almad_wma[$key];
            } else {
                $mad1_wma[$key] = $almad_wma[$key] / ($key - 2);
            }
        }
        // dd($almad_wma);

//tracking
        foreach ($d_wma as $key => $value) {

                $tracking_wma[$key] = $rsfe_wma[$key] / $mad1_wma[$key];
            }
        

        // dd($m_wma);

        ?>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Weighted Moving Average</strong>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Periode Data</th>
                                <th scope="col">Data Aktual</th>
                                <th scope="col">Data Peramalan</th>
                                <th scope="col">Nilai Error</th>
                                <th scope="col">Absolute Error</th>
                                <th scope="col">Percentage Error</th>
                                <th scope="col">Nilai RSFE</th>
                                <th scope="col">Cum. AE</th>
                                <th scope="col">MAD TS</th>
                                <th scope="col">Tracking Signal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no=1;
                            foreach ($f_wma as $key => $value) {
                                if($key>-1){
                                ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td><?= $d_wma[$key] ?></td>
                                    <td><?= round($f_wma[$key], 2) ?></td>
                                    <td><?= round($e_wma[$key], 2) ?></td>
                                    <td><?= round($m_wma[$key], 2) ?></td>
                                    <td><?= round($p_wma[$key], 2) ?></td>
                                    <td><?= round($rsfe_wma[$key], 2) ?></td>
                                    <td><?= round($almad_wma[$key], 2) ?></td>
                                    <td><?= round($mad1_wma[$key], 2) ?></td>
                                    <td><?= round($tracking_wma[$key], 2) ?></td>
                                </tr>
                                <?php
                                $no++;
                            }
                        }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <th colspan="3"> Jumlah</th>
                                <td><?= round($sm_wma, 2) ?></td>
                                <td><?= round($sp_wma, 2) ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <th colspan="3"> rata rata</th>
                                <td><?= round($rm_wma, 2) ?></td>
                                <td><?= round($asp_wma, 2) ?> %</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><a href="?id_obat=<?= $_GET[id_obat] ?>&module=peramalan&act=tracking_wma&tanggal_awal=<?php echo $_GET['tanggal_awal']?>&tanggal_akhir=<?php echo $_GET['tanggal_akhir']?>" type="button" class="btn btn-outline-warning"> tracking </a></td>
                            </tr>
                        </tfoot>
                        
                    </table>
                </div>
            </div>
        </div>
        <?php
        break;


    case "detail_tp":
    function getTotal($periode, $data) {

        $array = array();
        for ($i = 0; $i < count($periode); $i++) {
            for($j=0;$j<count($data);$j++)
            {             
              if($periode[$i]==$data[$j]['tahun_bulan'])
              {
                $array[$i]=$data[$j]['jumlah']; 
                break;
              }
              else
              {
                $array[$i]=0;  
              }
            }     
        }
        return $array; 
    }

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

     $tanggal=week_between_two_dates(date('Y-m-d',strtotime($_GET['tanggal_awal'])), date('Y-m-d',strtotime($_GET['tanggal_akhir'])));


     $d_tp=array();
        
        $t_tp = 0;
       // $sd = 0;
        $tgl_awal=date('Y-m-d',strtotime($_GET['tanggal_awal']));
        $tgl_akhir=date('Y-m-d',strtotime($_GET['tanggal_akhir']));

        
        // $result = mysqli_query($koneksi, "SELECT data_rekap.*,CONCAT(DATE_FORMAT(tanggal, '%Y'),'/',DATE_FORMAT(tanggal, '%m')) AS tahun_bulan FROM data_rekap WHERE id_obat = $_GET[id_obat] and tanggal >= '$tgl_awal' and tanggal <='$tgl_akhir' GROUP BY MONTH(tanggal),YEAR(tanggal) ORDER BY tanggal ASC");


        $result = mysqli_query($koneksi, "SELECT SUM(detail_rekap.jumlah * table_obat.satuan) as jumlah,CONCAT(DATE_FORMAT(table_rekap.tanggal, '%Y'),'/',DATE_FORMAT(table_rekap.tanggal, '%m')) AS tahun_bulan FROM detail_rekap JOIN table_rekap ON table_rekap.id_daterek=detail_rekap.id_daterek JOIN table_obat ON table_obat.id_obat=detail_rekap.id_obat WHERE table_obat.nama_obat LIKE '%$_GET[id_obat]%' and table_rekap.tanggal >= '$tgl_awal' and 
            table_rekap.tanggal <='$tgl_akhir' GROUP BY MONTH(table_rekap.tanggal), YEAR(table_rekap.tanggal) ORDER BY table_rekap.tanggal  ASC");

        if (!$result) {
            printf("Error: %s\n", mysqli_error($koneksi));
            exit();
        }

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $d_tp[$t_tp] = $row;
            $t_tp++;
        }


        $d_tp=getTotal($tanggal,$d_tp);

        $n_tp = count($d_tp);
//sum y
        $sd_tp = 0;
        foreach ($d_tp as $key => $value) {
            //  $sd +=$d[$key];
            $sd_tp +=$value;
        }

//x pow 2 
        foreach ($d_tp as $key => $value) {

            $px_tp[$key] = pow($key+1, 2);
        }
//sum x
        $sx_tp = 0;
        foreach ($d_tp as $key => $value) {

            $sx_tp +=($key+1);
        }

//sum x2
        $spx_tp = 0;
        foreach ($d_tp as $key => $value) {

            $spx_tp +=$px_tp[$key];
        }
//xy
        foreach ($d_tp as $key => $value) {

            $xy_tp[$key] = ($key+1) * $d_tp[$key];
        }
//sumxy
        $sxy_tp = 0;
        foreach ($d_tp as $key => $value) {

            $sxy_tp +=$xy_tp[$key];
        }

        $ax_tp = $sx_tp / $n_tp;

        $ay_tp = $sd_tp / $n_tp;


        $b_tp = ($sxy_tp - ($n_tp * $ax_tp * $ay_tp)) / ($spx_tp - $n_tp * pow($ax_tp, 2));

        $a_tp = $ay_tp - ($b_tp * $ax_tp);


        foreach ($d_tp as $key => $value) {

            $f_tp[$key] = $a_tp + ($b_tp * $key);
        }
        $f_tp[$key + 1] = $a_tp + ($b_tp * ($key + 1));
        $f_tp[$key + 2] = $a_tp + ($b_tp * ($key + 2));
        $f_tp[$key + 3] = $a_tp + ($b_tp * ($key + 3));
        $f_tp[$key + 4] = $a_tp + ($b_tp * ($key + 4));

        foreach ($d_tp as $key => $value) {

            $e_tp[$key] = $d_tp[$key] - $f_tp[$key];
        }

        $sm_tp = 0;
//mad
        foreach ($d_tp as $key => $value) {

            $m_tp[$key] = abs($e_tp[$key]);
            $sm_tp +=$m_tp[$key];
        }
        $rm_tp = $sm_tp / $n_tp;

//mape
        $sp_tp = 0;
        foreach ($d_tp as $key => $value) {
            $p_tp[$key] = ($m_tp[$key] / $d_tp[$key]) * 100;
            $sp_tp +=$p_tp[$key];
        }
        $asp_tp = $sp_tp / $n_tp;

//rsfe
        foreach ($d_tp as $key => $value) {
            if ($key == 0) {
                $rsfe_tp[$key] = $e_tp[$key];
            } else {
                $rsfe_tp[$key] = $rsfe_tp[$key - 1] + $e_tp[$key];
            }
        }

//Cum MAD        

        foreach ($d_tp as $key => $value) {
            if ($key == 0) {
                $almad_tp[$key] = $m_tp[$key];
            } else {
                $almad_tp[$key] = $almad_tp[$key - 1] + $m_tp[$key];
            }
        }
//AlMad        

        foreach ($d_tp as $key => $value) {
            if ($key == 0) {
                $mad1_tp[$key] = $almad_tp[$key];
            } else {
                $mad1_tp[$key] = $almad_tp[$key] / ($key+1);
            }
        }

        //tracking
        foreach ($d_tp as $key => $value) {

                $tracking_tp[$key] = $rsfe_tp[$key] / $mad1_tp[$key];
            }
        
        foreach ($tracking_tp as $key => $value) {

                $min_tp[$key] = $value < 0;
                $plus_tp[$key] = $value > 0;
            }

        $smin_tp = 0;
        $splus_tp = 0;
        foreach ($tracking_tp as $key => $value) {

            $smin_tp +=$min_tp[$key];
            $splus_tp +=$plus_tp[$key];
        }

        //$min_tr_tp = count($tracking_tp < 0);

        echo "</pre>";
        ?>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Trend Projection</strong>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Periode Data</th>
                                <th scope="col">Data Aktual</th>
                                <th scope="col">x2</th>
                                <th scope="col">xy</th>
                                <th scope="col">Data Peramalan</th>
                                <th scope="col">Nilai Error</th>
                                <th scope="col">Absolute Error</th>
                                <th scope="col">Percentage Error</th>
                                <th scope="col">Nilai RSFE</th>
                                <th scope="col">Cum. AE</th>
                                <th scope="col">MAD TS</th>
                                <th scope="col">Tracking Signal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($f_tp as $key => $value) {
                                ?>
                                <tr>
                                    <td><?= $key +1?></td>
                                    <td><?= $d_tp[$key] ?></td>
                                    <td><?= $px_tp[$key] ?></td>
                                    <td><?= $xy_tp[$key] ?></td>
                                    <td><?= round($f_tp[$key], 2) ?></td>
                                    <td><?= round($e_tp[$key], 2) ?></td>
                                    <td><?= round($m_tp[$key], 2) ?></td>
                                    <td><?= round($p_tp[$key], 2) ?>%</td>
                                    <td><?= round($rsfe_tp[$key], 2) ?></td>
                                    <td><?= round($almad_tp[$key], 2) ?></td>
                                    <td><?= round($mad1_tp[$key], 2) ?></td>
                                    <td><?= round($tracking_tp[$key], 2) ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th colspan="3"> Jumlah</th>
                                <td></td>
                                <td></td>
                                <td><?= round($sm_tp, 2) ?></td>
                                <td><?= round($sp_tp, 2) ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th></th>
                                <th colspan="3"> Rata rata</th>
                                <td></td>
                                <td></td>
                                <td><?= round($rm_tp, 2) ?></td>
                                <td><?= round($asp_tp, 2) ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><a href="?id_obat=<?= $_GET[id_obat] ?>&module=peramalan&act=tracking_tp&tanggal_awal=<?php echo $_GET['tanggal_awal']?>&tanggal_akhir=<?php echo $_GET['tanggal_akhir']?>" type="button" class="btn btn-outline-warning"> tracking </a></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>       

        <?php
        break;

case "tracking_ses":
  
    function getTotal($periode, $data) {

        $array = array();
        for ($i = 0; $i < count($periode); $i++) {
            for($j=0;$j<count($data);$j++)
            {             
              if($periode[$i]==$data[$j]['tahun_bulan'])
              {
                $array[$i]=$data[$j]['jumlah']; 
                break;
              }
              else
              {
                $array[$i]=0;  
              }
            }     
        }
        return $array; 
    }

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

     $tanggal=week_between_two_dates(date('Y-m-d',strtotime($_GET['tanggal_awal'])), date('Y-m-d',strtotime($_GET['tanggal_akhir'])));



     $d_ses=array();
        
        $t_ses = 0;
        $sd = 0;
        $tgl_awal=date('Y-m-d',strtotime($_GET['tanggal_awal']));
        $tgl_akhir=date('Y-m-d',strtotime($_GET['tanggal_akhir']));

        
        // $result = mysqli_query($koneksi, "SELECT data_rekap.*,CONCAT(DATE_FORMAT(tanggal, '%Y'),'/',DATE_FORMAT(tanggal, '%m')) AS tahun_bulan FROM data_rekap WHERE id_obat = $_GET[id_obat] and tanggal >= '$tgl_awal' and tanggal <='$tgl_akhir' GROUP BY MONTH(tanggal),YEAR(tanggal) ORDER BY tanggal ASC");


        $result = mysqli_query($koneksi, "SELECT SUM(detail_rekap.jumlah * table_obat.satuan) as jumlah,CONCAT(DATE_FORMAT(table_rekap.tanggal, '%Y'),'/',DATE_FORMAT(table_rekap.tanggal, '%m')) AS tahun_bulan FROM detail_rekap JOIN table_rekap ON table_rekap.id_daterek=detail_rekap.id_daterek JOIN table_obat ON table_obat.id_obat=detail_rekap.id_obat WHERE table_obat.nama_obat LIKE '%$_GET[id_obat]%' and table_rekap.tanggal >= '$tgl_awal' and 
            table_rekap.tanggal <='$tgl_akhir' GROUP BY MONTH(table_rekap.tanggal), YEAR(table_rekap.tanggal) ORDER BY table_rekap.tanggal  ASC");

        if (!$result) {
            printf("Error: %s\n", mysqli_error($koneksi));
            exit();
        }

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $d_ses[$t_ses] = $row;
            $t_ses++;
        }
        // dd($d_ses);


        $d_ses=getTotal($tanggal,$d_ses);

        for($i=0;$i<count($d_ses);$i++)
        {
            if($i<4)
            {
                $sd+=$d_ses[$i];
            }
        }


        $n_ses = count($d_ses);
       $a_ses=[0.10,0.11,0.12,0.13,0.14,0.15,0.16,0.17,0.18,0.19,0.20,0.21,0.22,0.23,0.24,0.25,0.26,0.27,0.28,0.29,0.30,0.31,0.32,0.33,0.34,0.35,0.36,0.37,0.38,0.39,0.40,0.41,0.42,0.43,0.44,0.45,0.46,0.47,0.48,0.49,0.50,0.51,0.52,0.53,0.54,0.55,0.56,0.57,0.58,0.59,0.60,0.61,0.62,0.63,0.64,0.65,0.66,0.67,0.68,0.96,0.70,0.71,0.72,0.73,0.74,0.75,0.76,0.77,0.78,0.79,0.80,0.81,0.82,0.83,0.84,0.85,0.86,0.87,0.88,0.89,0.90,0.91,0.92,0.93,0.94,0.95,0.96,0.97,0.98,0.99,];
        // $a_ses=[0.1];

  
    $PE = array();
        $MAPE = array();
        $new_ses = array();

        for($i=0;$i<count($a_ses);$i++){
            $PE[$i][0]=0;
            for($j=0;$j<count($d_ses);$j++){
                if ($j == 0) {
                    $f_ses[$i][$j] = $sd / 4;
                } else {

                    $f_ses[$i][$j] = $d_ses[$j - 1] * $a_ses[$i] + (1 - $a_ses[$i]) * $f_ses[$i][$j - 1];
                }

                $PE[$i][$j] = $d_ses[$j] == 0 ? 0 : abs((($d_ses[$j] - $f_ses[$i][$j]) / $d_ses[$j]) * 100);

            
            }
            $MAPE[$i] = array_sum($PE[$i])/(count($d_ses));
        }

        $bestAlphaIndex = array_search(min($MAPE), $MAPE);
        
        for($key=0;$key<count($d_ses);$key++)
        {
            array_push($new_ses, $f_ses[$bestAlphaIndex][$key]);
        } 



        // $f_ses[(count($d_ses)-1) + 1] = $d_ses[count($d_ses)-1] * $a_ses[$bestAlphaIndex] + (1 - $a_ses[$bestAlphaIndex]) * $f_ses[$bestAlphaIndex][count($d_ses)-1];
   
        // $f_ses[(count($d_ses)-1) + 2] = 0 * $a_ses[$bestAlphaIndex] + (1 - $a_ses[$bestAlphaIndex]) * $f_ses[(count($d_ses)-1) + 1];

        // $f_ses[(count($d_ses)-1) + 3] = 0 * $a_ses[$bestAlphaIndex] + (1 - $a_ses[$bestAlphaIndex]) * $f_ses[(count($d_ses)-1) + 2];

        // $f_ses[(count($d_ses)-1) + 4] = 0 * $a_ses[$bestAlphaIndex] + (1 - $a_ses[$bestAlphaIndex]) * $f_ses[(count($d_ses)-1) + 3];

        // array_push($new_ses, $f_ses[(count($d_ses)-1) + 1]);
        // array_push($new_ses, $f_ses[(count($d_ses)-1) + 2]);
        // array_push($new_ses, $f_ses[(count($d_ses)-1) + 3]);
        // array_push($new_ses, $f_ses[(count($d_ses)-1) + 4]);

        //         echo '<pre>';
        // print_r($new_ses);
        // die;
        // echo '</pre>';

 
        foreach ($d_ses as $key => $value) {

            $e_ses[$key] = $d_ses[$key] - $f_ses[$bestAlphaIndex][$key];
        }
//mad
        $sm_ses = 0;
        foreach ($d_ses as $key => $value) {

            $m_ses[$key] = abs($e_ses[$key]);
            $sm_ses +=$m_ses[$key];
        }
        $rm_ses = $sm_ses / $n_ses;
//mape
        // $sp_ses = 0;
        // foreach ($d_ses as $key => $value) {

        //     $p_ses[$key] = ($m_ses[$key] / $d_ses[$key]) * 100;

        //     $sp_ses +=$p_ses[$key];
        // }
        // $asp_ses = $sp_ses / $n_ses;

//rsfe
        foreach ($d_ses as $key => $value) {
            if ($key == 0) {
                $rsfe_ses[$key] = $e_ses[$key];
            } else {
                $rsfe_ses[$key] = $rsfe_ses[$key - 1] + $e_ses[$key];
            }
        }

//Cum MAD        

        foreach ($d_ses as $key => $value) {
            if ($key == 0) {
                $almad_ses[$key] = abs($e_ses[$key]);
            } else {
                $almad_ses[$key] = $almad_ses[$key - 1] + abs($e_ses[$key]);
            }
        }
//AlMad        

        foreach ($d_ses as $key => $value) {
            if ($key == 0) {
                $mad1[$key] = $almad_ses[$key];
            } else {
                $mad1[$key] = $almad_ses[$key] / ($key+1);
            }
        }

        //tracking
        foreach ($d_ses as $key => $value) {
            
                $tracking_ses[$key] = $rsfe_ses[$key] / $mad1[$key];
            }

        foreach ($tracking_ses as $key => $value) {

                $min_ses[$key] = $value < 0;
                $plus_ses[$key] = $value > 0;
                $maxmin_ses[$key] = $value < (-4);
                $maxplus_ses[$key] = $value > 4;
            }

        $smin_ses = 0;
        $splus_ses = 0;
        $smaxmin_ses = 0;
        $smaxplus_ses = 0;
        foreach ($tracking_ses as $key => $value) {

            $smin_ses +=$min_ses[$key];
            $splus_ses +=$plus_ses[$key];
            $smaxmin_ses +=$maxmin_ses[$key];
            $smaxplus_ses +=$maxplus_ses[$key];
        }
        

//table
        ?>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Single Exponential Smoothing (a= <?= $a_ses[$bestAlphaIndex] ?>)</strong>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Periode Data</th>
                                <th scope="col">Data Aktual</th>
                                <th scope="col">Data Peramalan</th>
                                <th scope="col">Nilai Error</th>
                                <th scope="col">Absolute Error</th>
                                <th scope="col">Nilai RSFE</th>
                                <th scope="col">Cumulative AE</th>
                                <th scope="col">MAD TS</th>
                                <th scope="col">Tracking Signal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($new_ses as $key => $value) {
                                ?>
                                <tr>
                                    <td><?= $key+1 ?></td>
                                    <td><?= isset($d_ses[$key])?$d_ses[$key]:0 ?></td>
                                    <td><?= number_format($value,6) ?></td>
                                    <td><?= round(isset($e_ses[$key])?$e_ses[$key]:0,3) ?></td>
                                    <td><?= round(isset($m_ses[$key])?$m_ses[$key]:0,3) ?></td>
                                    <td><?= round(isset($rsfe_ses[$key])?$rsfe_ses[$key]:0,3) ?></td>
                                    <td><?= round(isset($almad_ses[$key])?$almad_ses[$key]:0,3) ?></td>
                                    <td><?= round(isset($mad1[$key])?$mad1[$key]:0,3) ?></td>
                                    <td><?= round(isset($tracking_ses[$key])?$tracking_ses[$key]:0,3) ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <th colspan="2"> tracking signal positif </th>
                                <td><?= $splus_ses ?>(<?= $smaxplus_ses ?>)</td>
                                <td></td>
                                <th colspan="2"> tracking signal negatif</th>
                                <td><?= $smin_ses ?>(<?= $smaxmin_ses ?>)</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
        <?php
                   if (!empty($_GET['id_obat'])) {
                        ?>
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-3"> Grafik Tracking Signal </h4>
                                    <canvas id="sales-chart"></canvas>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

            </div>
        </div>

         <script>   
             jQuery(document).ready(function() {
        jQuery(".standardSelect").chosen({
            disable_search_threshold: 10,
            no_results_text: "Wasuu ra ketemu cuk!",
            width: "100%"
        });


    });
            (function ($) {
                "use strict";


                //Sales chart
                var ctx = document.getElementById("sales-chart");
                ctx.height = 150;
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: <?= json_encode($tanggal) ?>, 
                        type: 'line',
                        defaultFontFamily: 'Montserrat',
                        datasets: [
                        {
                                label: "Tracking Signal",
                                data: <?= json_encode($tracking_ses) ?>,
                                backgroundColor: 'transparent',
                                borderColor: 'rgba(253, 215, 3,0.75)',
                                borderWidth: 3,
                                pointStyle: 'circle',
                                pointRadius: 5,
                                pointBorderColor: 'transparent',
                                pointBackgroundColor: 'rgba(253, 215, 3,0.75)',
                            },
                            {
                                label: "Peramalan WMA",
                                data: <?= json_encode($new_ses) ?>,
                                backgroundColor: 'transparent',
                                borderColor: 'rgba(50,205,50, 0.5)',
                                borderWidth: 3,
                                pointStyle: 'circle',
                                pointRadius: 5,
                                pointBorderColor: 'transparent',
                                pointBackgroundColor: 'rgba(50,205,50,0.75)',
                            },
                            {
                                label: "Aktual",
                                data: <?= json_encode($d_ses) ?>,
                                backgroundColor: 'transparent',
                                borderColor: 'rgba(220,53,69, 0.36)',
                                borderWidth: 3,
                                pointStyle: 'circle',
                                pointRadius: 5,
                                pointBorderColor: 'transparent',
                                pointBackgroundColor: 'rgba(220,53,69,0.75)',
                            },
                        ]
                    },
                    options: {
                        responsive: true,
                        tooltips: {
                            mode: 'index',
                            titleFontSize: 12,
                            titleFontColor: '#000',
                            bodyFontColor: '#000',
                            backgroundColor: '#fff',
                            titleFontFamily: 'Montserrat',
                            bodyFontFamily: 'Montserrat',
                            cornerRadius: 3,
                            intersect: false,
                        },
                        legend: {
                            display: true,
                            labels: {
                                usePointStyle: true,
                                fontFamily: 'Montserrat',
                            },
                        },
                        scales: {
                            xAxes: [{
                                    display: true,
                                    gridLines: {
                                        display: true,
                                        drawBorder: true
                                    },
                                    scaleLabel: {
                                        display: false,
                                        labelString: 'Month'
                                    }
                                }],
                            yAxes: [{
                                    display: true,
                                    gridLines: {
                                        display: true,
                                        drawBorder: false
                                    },
                                    scaleLabel: {
                                        display: false,
                                        labelString: 'Value'
                                    }
                                }]
                        },
                        title: {
                            display: true,
                            text: 'Grafik data aktual dan peramalan'
                        }
                    }
                });

            })(jQuery);
            
            
        </script>


        <?php
        break;

        case "tracking_wma":

function getTotal($periode, $data) {

        $array = array();
        for ($i = 0; $i < count($periode); $i++) {
            for($j=0;$j<count($data);$j++)
            {             
              if($periode[$i]==$data[$j]['tahun_bulan'])
              {
                $array[$i]=$data[$j]['jumlah']; 
                break;
              }
              else
              {
                $array[$i]=0;  
              }
            }     
        }
        return $array; 
    }

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

     $tanggal=week_between_two_dates(date('Y-m-d',strtotime($_GET['tanggal_awal'])), date('Y-m-d',strtotime($_GET['tanggal_akhir'])));
     // dd($tanggal);

     $d_wma=array();
        
        $t_wma = 0;
       // $sd = 0;
        $tgl_awal=date('Y-m-d',strtotime($_GET['tanggal_awal']));
        $tgl_akhir=date('Y-m-d',strtotime($_GET['tanggal_akhir']));

        
        // $result = mysqli_query($koneksi, "SELECT data_rekap.*,CONCAT(DATE_FORMAT(tanggal, '%Y'),'/',DATE_FORMAT(tanggal, '%m')) AS tahun_bulan FROM data_rekap WHERE id_obat = $_GET[id_obat] and tanggal >= '$tgl_awal' and tanggal <='$tgl_akhir' GROUP BY MONTH(tanggal),YEAR(tanggal) ORDER BY tanggal ASC");


        $result = mysqli_query($koneksi, "SELECT SUM(detail_rekap.jumlah * table_obat.satuan) as jumlah,CONCAT(DATE_FORMAT(table_rekap.tanggal, '%Y'),'/',DATE_FORMAT(table_rekap.tanggal, '%m')) AS tahun_bulan FROM detail_rekap JOIN table_rekap ON table_rekap.id_daterek=detail_rekap.id_daterek JOIN table_obat ON table_obat.id_obat=detail_rekap.id_obat WHERE table_obat.nama_obat LIKE '%$_GET[id_obat]%' and table_rekap.tanggal >= '$tgl_awal' and 
            table_rekap.tanggal <='$tgl_akhir' GROUP BY MONTH(table_rekap.tanggal), YEAR(table_rekap.tanggal) ORDER BY table_rekap.tanggal  ASC");

        if (!$result) {
            printf("Error: %s\n", mysqli_error($koneksi));
            exit();
        }

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $d_wma[$t_wma] = $row;
            $t_wma++;
        }


        $d_wma=getTotal($tanggal,$d_wma);

      
        $n_wma = count($d_wma);
        //$a_ses = 2 / ($n_ses+1);


        $b1_wma = 1;
        $b2_wma = 2;
        $b3_wma = 3;
        $sb_wma = $b1_wma + $b2_wma + $b3_wma;

        foreach ($d_wma as $key => $value) {
            if ($key < 3) {
                $f_wma[$key] = null;//$d_wma[$key];
            } else {
                $f_wma[$key] = (($d_wma[$key - 1] * $b3_wma) + ($d_wma[$key - 2] * $b2_wma) + ($d_wma[$key - 3] * $b1_wma)) / $sb_wma;
            }
        }

        // $f_wma[$key + 1] = (($d_wma[$key] * $b3_wma) + ($d_wma[$key - 1] * $b2_wma) + ($d_wma[$key - 2] * $b1_wma)) / $sb_wma;

        // $f_wma[$key + 2] = (($f_wma[$key + 1] * $b3_wma) + ($d_wma[$key] * $b2_wma) + ($d_wma[$key - 1] * $b1_wma)) / $sb_wma;

        // $f_wma[$key + 3] = (($f_wma[$key + 2] * $b3_wma) + ($f_wma[$key + 1] * $b2_wma) + ($d_wma[$key] * $b1_wma)) / $sb_wma;

        // $f_wma[$key + 4] = (($f_wma[$key + 3] * $b3_wma) + ($f_wma[$key + 2] * $b2_wma) + ($f_wma[$key + 1] * $b1_wma)) / $sb_wma;

//error
        foreach ($d_wma as $key => $value) {
        if ($key < 3) {
            $e_wma[$key] = null;
        } else {
            $e_wma[$key] = $d_wma[$key] - $f_wma[$key];
        }
        }

//mad
        $sm_wma = 0;
        foreach ($d_wma as $key => $value) {

            $m_wma[$key] = abs($e_wma[$key]);
            $sm_wma +=$m_wma[$key];
        }
        $rm_wma = $sm_wma / ($n_wma-3);
//mape
        // $sp_wma = 0;
        // foreach ($d_wma as $key => $value) {
        //     $p_wma[$key] = ($m_wma[$key] / $d_wma[$key]) * 100;
        //     $sp_wma +=$p_wma[$key];
        // }
        // $asp_wma = $sp_wma / ($n_wma-3);
//rsfe
        foreach ($d_wma as $key => $value) {
            if ($key == 0) {
                $rsfe_wma[$key] = $e_wma[$key];
            } else {
                $rsfe_wma[$key] = $rsfe_wma[$key - 1] + $e_wma[$key];
            }
        }

//Cum MAD        

        foreach ($d_wma as $key => $value) {
            if ($key == 0) {
                $almad_wma[$key] = $m_wma[$key];
            } else {
                $almad_wma[$key] = $almad_wma[$key - 1] + $m_wma[$key];
            }
        }
//AlMad        

        foreach ($d_wma as $key => $value) {
            if ($key == 0) {
                $mad1_wma[$key] = $almad_wma[$key];
            } else {
                $mad1_wma[$key] = $almad_wma[$key] / ($key - 2);
            }
        }

//tracking
        foreach ($d_wma as $key => $value) {

                $tracking_wma[$key] = $rsfe_wma[$key] / $mad1_wma[$key];
            }

        foreach ($tracking_wma as $key => $value) {

                $min_wma[$key+2] = $value < 0;
                $plus_wma[$key+2] = $value > 0;
                $maxmin_wma[$key+2] = $value < (-4);
                $maxplus_wma[$key+2] = $value > 4;
            }

        $smin_wma = 0;
        $splus_wma = 0;
        $smaxmin_wma = 0;
        $smaxplus_wma = 0;
        foreach ($tracking_wma as $key => $value) {

            $smin_wma +=$min_wma[$key+2];
            $splus_wma +=$plus_wma[$key+2];
            $smaxmin_wma +=$maxmin_wma[$key+2];
            $smaxplus_wma +=$maxplus_wma[$key+2];
        }


        ?>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Weighted Moving Average</strong>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Periode Data</th>
                                <th scope="col">Data Aktual</th>
                                <th scope="col">Data Peramalan</th>
                                <th scope="col">Nilai Error</th>
                                <th scope="col">Absolute Error</th>
                                <th scope="col">Nilai RSFE</th>
                                <th scope="col">Cumulative AE</th>
                                <th scope="col">MAD TS</th>
                                <th scope="col">Tracking Signal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no=1;
                            foreach ($f_wma as $key => $value) {
                                if($key>2){
                                    $bulan[]=$key;
                                ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td><?= $d_wma[$key] ?></td>
                                    <td><?= round($f_wma[$key], 2) ?></td>
                                    <td><?= round($e_wma[$key], 2) ?></td>
                                    <td><?= round($m_wma[$key], 2) ?></td>
                                    <td><?= round($rsfe_wma[$key], 2) ?></td>
                                    <td><?= round($almad_wma[$key], 2) ?></td>
                                    <td><?= round($mad1_wma[$key], 2) ?></td>
                                    <td><?= round($tracking_wma[$key], 2) ?></td>
                                </tr>
                                <?php
                                $no++;
                            }
                        }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                               <td></td>
                                <th colspan="2"> tracking signal positif </th>
                                <td><?= $splus_wma ?>(<?= $smaxplus_wma ?>)</td>
                                <td></td>
                                <th colspan="2"> tracking signal negatif</th>
                                <td><?= $smin_wma ?>(<?= $smaxmin_wma ?>)</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php
                foreach($tanggal as $key => $val)
                {
                    if($key>2)
                    {
                        $tracking[$key]=$tracking_wma[$key];
                    }
                    else
                    {
                        $tracking[$key]=0;
                    }
                }
                // dd($tracking);
                    if (!empty($_GET['id_obat'])) {
                        ?>
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-3"> Grafik Tracking Signal </h4>
                                    <canvas id="sales-chart"></canvas>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
            </div>
        </div>

        <script>   
             jQuery(document).ready(function() {
        jQuery(".standardSelect").chosen({
            disable_search_threshold: 10,
            no_results_text: "data tidak ditemukan!!",
            width: "100%"
        });


    });
            (function ($) {
                "use strict";


                //Sales chart
                var ctx = document.getElementById("sales-chart");
                ctx.height = 150;
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: <?= json_encode($tanggal) ?>, 
                        type: 'line',
                        defaultFontFamily: 'Montserrat',
                        datasets: [
                        {
                                label: "Tracking Signal",
                                data: <?= json_encode($tracking) ?>,
                                backgroundColor: 'transparent',
                                borderColor: 'rgba(253, 215, 3,0.75)',
                                borderWidth: 3,
                                pointStyle: 'circle',
                                pointRadius: 5,
                                pointBorderColor: 'transparent',
                                pointBackgroundColor: 'rgba(253, 215, 3,0.75)',
                            },
                            {
                                label: "Peramalan WMA",
                                data: <?= json_encode($f_wma) ?>,
                                backgroundColor: 'transparent',
                                borderColor: 'rgba(50, 205, 50, 0.5)',
                                borderWidth: 3,
                                pointStyle: 'circle',
                                pointRadius: 5,
                                pointBorderColor: 'transparent',
                                pointBackgroundColor: 'rgba(50, 205, 50,0.75)',
                            },
                            {
                                label: "Aktual",
                                data: <?= json_encode($d_wma) ?>,
                                backgroundColor: 'transparent',
                                borderColor: 'rgba(220,53,69, 0.36)',
                                borderWidth: 3,
                                pointStyle: 'circle',
                                pointRadius: 5,
                                pointBorderColor: 'transparent',
                                pointBackgroundColor: 'rgba(220,53,69,0.75)',
                            },
                        ]
                    },
                    options: {
                        responsive: true,
                        tooltips: {
                            mode: 'index',
                            titleFontSize: 12,
                            titleFontColor: '#000',
                            bodyFontColor: '#000',
                            backgroundColor: '#fff',
                            titleFontFamily: 'Montserrat',
                            bodyFontFamily: 'Montserrat',
                            cornerRadius: 3,
                            intersect: false,
                        },
                        legend: {
                            display: true,
                            labels: {
                                usePointStyle: true,
                                fontFamily: 'Montserrat',
                            },
                        },
                        scales: {
                            xAxes: [{
                                    display: true,
                                    gridLines: {
                                        display: true,
                                        drawBorder: true
                                    },
                                    scaleLabel: {
                                        display: false,
                                        labelString: 'Month'
                                    }
                                }],
                            yAxes: [{
                                    display: true,
                                    gridLines: {
                                        display: true,
                                        drawBorder: false
                                    },
                                    scaleLabel: {
                                        display: false,
                                        labelString: 'Value'
                                    }
                                }]
                        },
                        title: {
                            display: true,
                            text: 'Grafik data aktual dan peramalan'
                        }
                    }
                });

            })(jQuery);
            
            
        </script>

        <?php
        break;

 case "tracking_tp":
    function getTotal($periode, $data) {

        $array = array();
        for ($i = 0; $i < count($periode); $i++) {
            for($j=0;$j<count($data);$j++)
            {             
              if($periode[$i]==$data[$j]['tahun_bulan'])
              {
                $array[$i]=$data[$j]['jumlah']; 
                break;
              }
              else
              {
                $array[$i]=0;  
              }
            }     
        }
        return $array; 
    }

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

     $tanggal=week_between_two_dates(date('Y-m-d',strtotime($_GET['tanggal_awal'])), date('Y-m-d',strtotime($_GET['tanggal_akhir'])));


     $d_tp=array();
        
        $t_tp = 0;
       // $sd = 0;
        $tgl_awal=date('Y-m-d',strtotime($_GET['tanggal_awal']));
        $tgl_akhir=date('Y-m-d',strtotime($_GET['tanggal_akhir']));

        
        // $result = mysqli_query($koneksi, "SELECT data_rekap.*,CONCAT(DATE_FORMAT(tanggal, '%Y'),'/',DATE_FORMAT(tanggal, '%m')) AS tahun_bulan FROM data_rekap WHERE id_obat = $_GET[id_obat] and tanggal >= '$tgl_awal' and tanggal <='$tgl_akhir' GROUP BY MONTH(tanggal),YEAR(tanggal) ORDER BY tanggal ASC");


        $result = mysqli_query($koneksi, "SELECT SUM(detail_rekap.jumlah * table_obat.satuan) as jumlah,CONCAT(DATE_FORMAT(table_rekap.tanggal, '%Y'),'/',DATE_FORMAT(table_rekap.tanggal, '%m')) AS tahun_bulan FROM detail_rekap JOIN table_rekap ON table_rekap.id_daterek=detail_rekap.id_daterek JOIN table_obat ON table_obat.id_obat=detail_rekap.id_obat WHERE table_obat.nama_obat LIKE '%$_GET[id_obat]%' and table_rekap.tanggal >= '$tgl_awal' and 
            table_rekap.tanggal <='$tgl_akhir' GROUP BY MONTH(table_rekap.tanggal), YEAR(table_rekap.tanggal) ORDER BY table_rekap.tanggal  ASC");

        if (!$result) {
            printf("Error: %s\n", mysqli_error($koneksi));
            exit();
        }

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $d_tp[$t_tp] = $row;
            $t_tp++;
        }

        $d_tp=getTotal($tanggal,$d_tp);

        $n_tp = count($d_tp);
//sum y
        $sd_tp = 0;
        foreach ($d_tp as $key => $value) {
            //  $sd +=$d[$key];
            $sd_tp +=$value;
        }

//x pow 2 
        foreach ($d_tp as $key => $value) {

            $px_tp[$key] = pow($key+1, 2);
        }
//sum x
        $sx_tp = 0;
        foreach ($d_tp as $key => $value) {

            $sx_tp +=($key+1);
        }

//sum x2
        $spx_tp = 0;
        foreach ($d_tp as $key => $value) {

            $spx_tp +=$px_tp[$key];
        }
//xy
        foreach ($d_tp as $key => $value) {

            $xy_tp[$key] = ($key+1) * $d_tp[$key];
        }
//sumxy
        $sxy_tp = 0;
        foreach ($d_tp as $key => $value) {

            $sxy_tp +=$xy_tp[$key];
        }

        $ax_tp = $sx_tp / $n_tp;

        $ay_tp = $sd_tp / $n_tp;


        $b_tp = ($sxy_tp - ($n_tp * $ax_tp * $ay_tp)) / ($spx_tp - $n_tp * pow($ax_tp, 2));

        $a_tp = $ay_tp - ($b_tp * $ax_tp);


        foreach ($d_tp as $key => $value) {

            $f_tp[$key] = $a_tp + ($b_tp * $key);
        }
        $f_tp[$key + 1] = $a_tp + ($b_tp * ($key + 1));
        $f_tp[$key + 2] = $a_tp + ($b_tp * ($key + 2));
        $f_tp[$key + 3] = $a_tp + ($b_tp * ($key + 3));
        $f_tp[$key + 4] = $a_tp + ($b_tp * ($key + 4));

        foreach ($d_tp as $key => $value) {

            $e_tp[$key] = $d_tp[$key] - $f_tp[$key];
        }

        $sm_tp = 0;
//mad
        foreach ($d_tp as $key => $value) {

            $m_tp[$key] = abs($e_tp[$key]);
            $sm_tp +=$m_tp[$key];
        }
        $rm_tp = $sm_tp / $n_tp;

//mape
        $sp_tp = 0;
        foreach ($d_tp as $key => $value) {
            $p_tp[$key] = ($m_tp[$key] / $d_tp[$key]) * 100;
            $sp_tp +=$p_tp[$key];
        }
        $asp_tp = $sp_tp / $n_tp;

//rsfe
        foreach ($d_tp as $key => $value) {
            if ($key == 0) {
                $rsfe_tp[$key] = $e_tp[$key];
            } else {
                $rsfe_tp[$key] = $rsfe_tp[$key - 1] + $e_tp[$key];
            }
        }

//Cum MAD        

        foreach ($d_tp as $key => $value) {
            if ($key == 0) {
                $almad_tp[$key] = $m_tp[$key];
            } else {
                $almad_tp[$key] = $almad_tp[$key - 1] + $m_tp[$key];
            }
        }
//AlMad        

        foreach ($d_tp as $key => $value) {
            if ($key == 0) {
                $mad1_tp[$key] = $almad_tp[$key];
            } else {
                $mad1_tp[$key] = $almad_tp[$key] / ($key+1);
            }
        }

        //tracking
        foreach ($d_tp as $key => $value) {

                $tracking_tp[$key] = $rsfe_tp[$key] / $mad1_tp[$key];
            }
        
        foreach ($tracking_tp as $key => $value) {

                $min_tp[$key] = $value < 0;
                $plus_tp[$key] = $value > 0;
            }

        $smin_tp = 0;
        $splus_tp = 0;
        foreach ($d_tp as $key => $value) {

            $smin_tp +=$min_tp[$key];
            $splus_tp +=$plus_tp[$key];
        }


        echo "</pre>";
        ?>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Trend Projection</strong>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Periode Data</th>
                                <th scope="col">Nilai Aktual</th>
                                <th scope="col">Nilai Peramalan</th>
                                <th scope="col">Error</th>
                                <th scope="col">Absolute Error</th>
                                <th scope="col">RSFE</th>
                                <th scope="col">Cumulative AE</th>
                                <th scope="col">MAD TS</th>
                                <th scope="col">Tracking Signal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($d_tp as $key => $value) {
                                ?>
                                <tr>
                                    <td><?= $key +1?></td>
                                    <td><?= $d_tp[$key] ?></td>
                                    <td><?= round($f_tp[$key], 2) ?></td>
                                    <td><?= round($e_tp[$key], 2) ?></td>
                                    <td><?= round($m_tp[$key], 2) ?></td>                                    
                                    <td><?= round($rsfe_tp[$key], 2) ?></td>
                                    <td><?= round($almad_tp[$key], 2) ?></td>
                                    <td><?= round($mad1_tp[$key], 2) ?></td>
                                    <td><?= round($tracking_tp[$key], 2) ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <th colspan="2"> tracking signal positif </th>
                                <td><?= $splus_tp ?></td>
                                <td></td>
                                <th colspan="2"> tracking signal negatif</th>
                                <td><?= $smin_tp ?></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php
                   if (!empty($_GET['id_obat'])) {
                        ?>
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-3"> Grafik Tracking Signal </h4>
                                    <canvas id="sales-chart"></canvas>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
            </div>
        </div>       

        <script>   
             jQuery(document).ready(function() {
        jQuery(".standardSelect").chosen({
            disable_search_threshold: 10,
            no_results_text: "data tidak ditemukan!",
            width: "100%"
        });


    });
            (function ($) {
                "use strict";


                //Sales chart
                var ctx = document.getElementById("sales-chart");
                ctx.height = 150;
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: <?= json_encode($tanggal) ?>, 
                        type: 'line',
                        defaultFontFamily: 'Montserrat',
                        datasets: [
                        {
                                label: "Tracking Signal",
                                data: <?= json_encode($tracking_tp) ?>,
                                backgroundColor: 'transparent',
                                borderColor: 'rgba(253, 215, 3,0.50)',
                                borderWidth: 3,
                                pointStyle: 'circle',
                                pointRadius: 5,
                                pointBorderColor: 'transparent',
                                pointBackgroundColor: 'rgba(253, 215, 3,0.75)',
                            },
                            {
                                label: "Peramalan trend projection",
                                data: <?= json_encode($f_tp) ?>,
                                backgroundColor: 'transparent',
                                borderColor: 'rgba(50, 205, 50, 0.75)',
                                borderWidth: 3,
                                pointStyle: 'circle',
                                pointRadius: 5,
                                pointBorderColor: 'transparent',
                                pointBackgroundColor: 'rgba(50, 205, 50,0.50)',
                            },
                            {
                                label: "Aktual",
                                data: <?= json_encode($d_tp) ?>,
                                backgroundColor: 'transparent',
                                borderColor: 'rgba(220,53,69, 0.75)',
                                borderWidth: 3,
                                pointStyle: 'circle',
                                pointRadius: 5,
                                pointBorderColor: 'transparent',
                                pointBackgroundColor: 'rgba(220,53,69,0.50)',
                            },
                        ]
                    },
                    options: {
                        responsive: true,
                        tooltips: {
                            mode: 'index',
                            titleFontSize: 12,
                            titleFontColor: '#000',
                            bodyFontColor: '#000',
                            backgroundColor: '#fff',
                            titleFontFamily: 'Montserrat',
                            bodyFontFamily: 'Montserrat',
                            cornerRadius: 3,
                            intersect: false,
                        },
                        legend: {
                            display: true,
                            labels: {
                                usePointStyle: true,
                                fontFamily: 'Montserrat',
                            },
                        },
                        scales: {
                            xAxes: [{
                                    display: true,
                                    gridLines: {
                                        display: true,
                                        drawBorder: true
                                    },
                                    scaleLabel: {
                                        display: false,
                                        labelString: 'Month'
                                    }
                                }],
                            yAxes: [{
                                    display: true,
                                    gridLines: {
                                        display: true,
                                        drawBorder: false
                                    },
                                    scaleLabel: {
                                        display: false,
                                        labelString: 'Value'
                                    }
                                }]
                        },
                        title: {
                            display: true,
                            text: 'Grafik data aktual, peramalan dan tracking signal'
                        }
                    }
                });

            })(jQuery);
            
            
        </script>

        <?php
        break;

}
?>

