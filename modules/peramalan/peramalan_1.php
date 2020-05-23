<?php
$act = $_GET['act'];
switch ($act) {
    // Tampil Data Obat
    default:
        $t_ses = 1;
        $sd = 0;
        $t_wma = 1;
        $t_tp = 1;

        $x = array();
        $d = array();
        //$label_x=week_between_two_dates(date('Y-m-d',strtotime($_GET['tanggal_awal'])), date('Y-m-d',strtotime($_GET['tanggal_akhir'])));
       
        $tgl_awal=date('Y-m-d',strtotime($_GET['tanggal_awal']));
        $tgl_akhir=date('Y-m-d',strtotime($_GET['tanggal_akhir']));
         
        function between_month($tgl_akhir)
        {
            $bulan=new DateTime($tgl_akhir);
            $tgl_akhir=$bulan->format('Y-m-d');
            $bulan_kedepan=$bulan->modify('+4 month')->format('Y-m-d');
            
             $p = new DatePeriod(
              new DateTime($tgl_akhir), new DateInterval('P1M'), new DateTime($bulan_kedepan)
              );
//             print_r($p);
//             die;
             foreach ($p as $w) {
              $bulan1[] = $w->format('Y/m');
//                $bulan1[] = $w->format('Y/m');
            }
            return $bulan1;
//            return $bulan_kedepan;
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
            // echo '<pre>';
            // echo print_r($data);
            // echo '</pre>';
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
        
//        print_r(between_month($tgl_akhir));
//        die;
        
//       
//     
        //$tahun = 0;

         $tanggal=week_between_two_dates(date('Y-m-d',strtotime($_GET['tanggal_awal'])), date('Y-m-d',strtotime($_GET['tanggal_akhir'])));

        $result = mysqli_query($koneksi, "SELECT SUM(jumlah) as jumlah,CONCAT(DATE_FORMAT(tanggal, '%Y'),'/',DATE_FORMAT(tanggal, '%m')) AS tahun_bulan FROM data_rekap WHERE id_obat = $_GET[id_obat] and tanggal >= '$tgl_awal' and 
            tanggal <='$tgl_akhir' GROUP BY MONTH(tanggal), YEAR(tanggal) ORDER BY tanggal  ASC");
//       
$t_ses=1;  
        
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            // array_push($x, $row['tahun_bulan']);
            // array_push($d, $row['jumlah']);
            $d[$t_ses]=$row;


            $d_ses[$t_ses] = $row['jumlah'];      

                       if ($t_ses <= 4) {
                $sd += $row['jumlah'];             
            }
            $t_ses++;
            
            $d_wma[$t_wma] = $row['jumlah'];
            $t_wma++;

            $d_tp[$t_tp] = $row['jumlah'];
            $t_tp++;

            $bulan_tahun = $row['MONTH(tanggal)'];
            //$bulan = $row['bulan'];
        }



        $x=  array_merge($tanggal,between_month($tgl_akhir));
        $d=getTotal($x,$d);
        //print_r($x);
        //echo '<pre>';
        //print_r($d);
        //die;    
       // echo '</pre>';

        


        
        
        
//single exponential smoothing
        $n_ses = count($d_ses);
        $a_ses = 2 / ($n_ses+1);

        $new_ses = array();
        
        foreach ($d_ses as $key => $value) {
            if ($key == 1) {
                $f_ses[$key] = $sd / 4;
            } else {
                $f_ses[$key] = $d_ses[$key - 1] * $a_ses + (1 - $a_ses) * $f_ses[$key - 1];
            }
            array_push($new_ses, $f_ses[$key]);
        }
       

        $f_ses[$key + 1] = $d_ses[$key] * $a_ses + (1 - $a_ses) * $f_ses[$key];
        $f_ses[$key + 2] = 0 * $a_ses + (1 - $a_ses) * $f_ses[$key + 1];
        $f_ses[$key + 3] = 0 * $a_ses + (1 - $a_ses) * $f_ses[$key + 2];
        $f_ses[$key + 4] = 0 * $a_ses + (1 - $a_ses) * $f_ses[$key] + 3;


        array_push($new_ses, $f_ses[$key + 1]);
        array_push($new_ses, $f_ses[$key + 2]);
        array_push($new_ses, $f_ses[$key + 3]);
        array_push($new_ses, $f_ses[$key + 4]);

//SES
        foreach ($d_ses as $key => $value) {

            $e_ses[$key] = $d_ses[$key] - $f_ses[$key];
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

//weighted moving average
        $b1_wma = 1;
        $b2_wma = 2;
        $b3_wma = 3;
        $sb_wma = $b1_wma + $b2_wma + $b3_wma;
        $n_wma = count($d_wma);
        $new_wma = array();
        foreach ($d_wma as $key => $value) {
            if ($key <= 3) {
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
        $avmape_wma = $smape_wma / $n_wma;

//TP
        $n_tp = count($d_tp);

        $sd_tp = 0;
        foreach ($d_tp as $key => $value) {
            $sd_tp +=$value;
        }

        foreach ($d_tp as $key => $value) {
            $px_tp[$key] = pow($key, 2);
        }

        $sx_tp = 0;
        foreach ($d_tp as $key => $value) {
            $sx_tp +=$key;
        }

        $spx_tp = 0;
        foreach ($d_tp as $key => $value) {
            $spx_tp +=$px_tp[$key];
        }

        foreach ($d_tp as $key => $value) {
            $xy_tp[$key] = $key * $d_tp[$key];
        }

        $sxy_tp = 0;
        foreach ($d_tp as $key => $value) {
            $sxy_tp +=$xy_tp[$key];
        }

//av periode
        $an_tp = $sx_tp / $n_tp;
//av data
        $ay_tp = $sd_tp / $n_tp;

        $b_tp = ($sxy_tp - ($n_tp * ($an_tp * $ay_tp))) / ($spx_tp - ($n_tp * pow($an_tp, 2)));

        $a_tp = $ay_tp - ($b_tp * $an_tp);
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
                                            $tampil = mysqli_query($koneksi, "SELECT * FROM table_obat");

                                            while ($r = mysqli_fetch_array($tampil)) {
                                                ?>
                                                <option value="<?= $r['id_obat'] ?>" <?php echo $r['id_obat'] == $_GET['id_obat'] ? 'selected' : NULL; ?>><?= $r['nama_obat'] ?></option>
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
                            if (!empty($_GET['id_obat'])) {
                                ?>    

                                <table class="table table-bordered">    
                                    <thead>
                                        <tr>
                                            <th>Periode</th>
                                            <th >Single Exponential</th>
                                            <th >Weighted Moving Average</th>
                                            <th >Trend Projection</th>
                                        </tr>
                                    </thead>
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

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th>
                                                <a href="?id_obat=<?= $_GET[id_obat] ?>&module=peramalan&act=detail_ses&tanggal_awal=<?php echo $_GET['tanggal_awal']?>&tanggal_akhir=<?php echo $_GET['tanggal_akhir']?>" type="button" class="btn btn-outline-warning"> detail </a>
                                            </th>
                                            <th >
                                                <a href="?id_obat=<?= $_GET[id_obat] ?>&module=peramalan&act=detail_wma&tanggal_awal=<?php echo $_GET['tanggal_awal']?>&tanggal_akhir=<?php echo $_GET['tanggal_akhir']?>" type="button" class="btn btn-outline-warning"> detail </a>
                                            </th>
                                            <th>
                                                <a href="?id_obat=<?= $_GET[id_obat] ?>&module=peramalan&act=detail_tp&tanggal_awal=<?php echo $_GET['tanggal_awal']?>&tanggal_akhir=<?php echo $_GET['tanggal_akhir']?>" type="button" class="btn btn-outline-warning"> detail </a>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <?php
                            }
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

        <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.bundle.min.js"></script>
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
        //       echo "<pre>";
    //copy    
    function getTotal($periode, $data) {

        $array = array();
        for ($i = 0; $i < count($periode); $i++) {
            // for ($j = 0; $j < count($data); $j++) {\
            // echo '<pre>';
            // echo print_r($periode[$i]);
            // echo '</pre>';
                if ($periode[$i] == ($data[$i]['tahun_bulan'])) {
                    $array[$i] = floatval($data[$i]['jumlah']);
                    // break;
                } else {
                    $array[$i] = 0;
                }
            // }
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

     // echo '<pre>';
     // print_r($tanggal);
     // die();
     // echo '</pre>'; 

     $d_ses=array();
        
        $t_ses = 1;
        $sd = 0;
        $tgl_awal=date('Y-m-d',strtotime($_GET['tanggal_awal']));
        $tgl_akhir=date('Y-m-d',strtotime($_GET['tanggal_akhir']));

        
        $result = mysqli_query($koneksi, "SELECT data_rekap.*,CONCAT(DATE_FORMAT(tanggal, '%Y'),'/',DATE_FORMAT(tanggal, '%m')) AS tahun_bulan FROM data_rekap WHERE id_obat = $_GET[id_obat] and tanggal >= '$tgl_awal' and tanggal <='$tgl_akhir' GROUP BY MONTH(tanggal),YEAR(tanggal) ORDER BY tanggal ASC");



        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $d_ses[$t_ses] = $row;

            if ($t_ses <= 4) {
                $sd += $row['jumlah'];
              
            }

            $t_ses++;
        }

        $d_ses=getTotal($tanggal,$d_ses);

        // echo '<pre>';
        // echo print_r($d_ses);
        // die();
        // echo '</pre>';
        
        $n_ses = count($d_ses);
        $a_ses = 2 / ($n_ses+1);

        foreach ($d_ses as $key => $value) {
            if ($key == 1) {
                $f_ses[$key] = $sd / 4;
            } else {
                $f_ses[$key] = $d_ses[$key - 1] * $a_ses + (1 - $a_ses) * $f_ses[$key - 1];
            }
        }

        $f_ses[$key + 1] = $d_ses[$key] * $a_ses + (1 - $a_ses) * $f_ses[$key];
        $f_ses[$key + 2] = 0 * $a_ses + (1 - $a_ses) * $f_ses[$key + 1];
        $f_ses[$key + 3] = 0 * $a_ses + (1 - $a_ses) * $f_ses[$key + 2];
        $f_ses[$key + 4] = 0 * $a_ses + (1 - $a_ses) * $f_ses[$key] + 3;

        // echo '<pre>';
        // echo print_r($d_ses);
        // die();
        // echo '</pre>';


        foreach ($d_ses as $key => $value) {

            $e_ses[$key] = $d_ses[$key] - $f_ses[$key];
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
            if ($key == 1) {
                $rsfe_ses[$key] = $e_ses[$key];
            } else {
                $rsfe_ses[$key] = $rsfe_ses[$key - 1] + $e_ses[$key];
            }
        }

//Cum MAD        

        foreach ($d_ses as $key => $value) {
            if ($key == 1) {
                $almad_ses[$key] = $m_ses[$key];
            } else {
                $almad_ses[$key] = $almad_ses[$key - 1] + $m_ses[$key];
            }
        }
//AlMad        

        foreach ($d_ses as $key => $value) {
            if ($key == 1) {
                $mad1[$key] = $almad_ses[$key];
            } else {
                $mad1[$key] = $almad_ses[$key] / $key;
            }
        }

        //tracking
        foreach ($d_ses as $key => $value) {
            if ($key == 1) {
                $tracking_ses[$key] = $rsfe_ses[$key];
            } else {
                $tracking_ses[$key] = $rsfe_ses[$key] / $mad1[$key];
            }
        }

//        echo "</pre>";
        ?>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Single Exponential Smoothing</strong>
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
                            foreach ($f_ses as $key => $value) {
                                ?>
                                <tr>
                                    <td><?= $key ?></td>
                                    <td><?= $d_ses[$key] ?></td>
                                    <td><?= round($f_ses[$key], 2) ?></td>
                                    <td><?= round($e_ses[$key], 2) ?></td>
                                    <td><?= round($m_ses[$key], 2) ?></td>
                                    <td><?= round($p_ses[$key], 2) ?>%</td>
                                    <td><?= round($rsfe_ses[$key], 2) ?></td>
                                    <td><?= round($almad_ses[$key], 2) ?></td>
                                    <td><?= round($mad1[$key], 2) ?></td>
                                    <td><?= round($tracking_ses[$key], 2) ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        <tfoot>
                            <tr>
                                <td></td>
                                <th colspan="3"> rata rata</th>
                                <td><?= round($rm_ses, 2) ?></td>
                                <td><?= round($asp_ses, 2) ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
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

        $t_wma = 1;
        $tgl_awal=date('Y-m-d',strtotime($_GET['tanggal_awal']));
        $tgl_akhir=date('Y-m-d',strtotime($_GET['tanggal_akhir']));

        
        $result = mysqli_query($koneksi, "SELECT * FROM data_rekap WHERE id_obat = $_GET[id_obat] and tanggal >= '$tgl_awal' and tanggal <='$tgl_akhir' GROUP BY tanggal ORDER BY tanggal ASC");
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $d_wma[$t_wma] = $row['jumlah'];
            $t_wma++;
        }

        $n_wma = count($d_wma);

//bobot
        $b1_wma = 1;
        $b2_wma = 2;
        $b3_wma = 3;
        $sb_wma = $b1_wma + $b2_wma + $b3_wma;

        foreach ($d_wma as $key => $value) {
            if ($key <= 3) {
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
        if ($key <= 3) {
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
            if ($key == 1) {
                $rsfe_wma[$key] = $e_wma[$key];
            } else {
                $rsfe_wma[$key] = $rsfe_wma[$key - 1] + $e_wma[$key];
            }
        }

//Cum MAD        

        foreach ($d_wma as $key => $value) {
            if ($key == 1) {
                $almad_wma[$key] = $m_wma[$key];
            } else {
                $almad_wma[$key] = $almad_wma[$key - 1] + $m_wma[$key];
            }
        }
//AlMad        

        foreach ($d_wma as $key => $value) {
            if ($key == 1) {
                $mad1_wma[$key] = $almad_wma[$key];
            } else {
                $mad1_wma[$key] = $almad_wma[$key] / ($key - 3);
            }
        }

//tracking
        foreach ($d_wma as $key => $value) {
            if ($key == 1) {
                $tracking_wma[$key] = $rsfe_wma[$key];
            } else {
                $tracking_wma[$key] = $rsfe_wma[$key] / $mad1_wma[$key];
            }
        }

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
                            foreach ($f_wma as $key => $value) {
                                ?>
                                <tr>
                                    <td><?= $key ?></td>
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
                            }
                            ?>
                        <tfoot>
                            <tr>
                                <td></td>
                                <th colspan="3"> rata rata</th>
                                <td><?= round($rm_wma, 2) ?></td>
                                <td><?= round($asp_wma, 2) ?> %</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td> detail </td>
                            </tr>
                        </tfoot>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
        break;


    case "detail_tp":

        $t_tp = 1;
        $tgl_awal=date('Y-m-d',strtotime($_GET['tanggal_awal']));
        $tgl_akhir=date('Y-m-d',strtotime($_GET['tanggal_akhir']));
        
        $result = mysqli_query($koneksi, "SELECT * FROM data_rekap WHERE id_obat = $_GET[id_obat] and tanggal >= '$tgl_awal' and tanggal <='$tgl_akhir' GROUP BY tanggal ORDER BY tanggal ASC");
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $d_tp[$t_tp] = $row['jumlah'];
            $t_tp++;
        }

        $n_tp = count($d_tp);

        $sd_tp = 0;
        foreach ($d_tp as $key => $value) {
            //  $sd +=$d[$key];
            $sd_tp +=$value;
        }

        foreach ($d_tp as $key => $value) {

            $px_tp[$key] = pow($key, 2);
        }

        $sx_tp = 0;
        foreach ($d_tp as $key => $value) {

            $sx_tp +=$key;
        }

        $spx_tp = 0;
        foreach ($d_tp as $key => $value) {

            $spx_tp +=$px_tp[$key];
        }

        foreach ($d_tp as $key => $value) {

            $xy_tp[$key] = $key * $d_tp[$key];
        }

        $sxy_tp = 0;
        foreach ($d_tp as $key => $value) {

            $sxy_tp +=$xy_tp[$key];
        }

        $an_tp = $sx_tp / $n_tp;

        $ay_tp = $sd_tp / $n_tp;


        $b_tp = ($sxy_tp - ($n_tp * ($an_tp * $ay_tp))) / ($spx_tp - ($n_tp * pow($an_tp, 2)));

        $a_tp = $ay_tp - ($b_tp * $an_tp);


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
            if ($key == 1) {
                $rsfe_tp[$key] = $e_tp[$key];
            } else {
                $rsfe_tp[$key] = $rsfe_tp[$key - 1] + $e_tp[$key];
            }
        }

//Cum MAD        

        foreach ($d_tp as $key => $value) {
            if ($key == 1) {
                $almad_tp[$key] = $m_tp[$key];
            } else {
                $almad_tp[$key] = $almad_tp[$key - 1] + $m_tp[$key];
            }
        }
//AlMad        

        foreach ($d_tp as $key => $value) {
            if ($key == 1) {
                $mad1_tp[$key] = $almad_tp[$key];
            } else {
                $mad1_tp[$key] = $almad_tp[$key] / $key;
            }
        }

        //tracking
        foreach ($d_tp as $key => $value) {
            if ($key == 1) {
                $tracking_tp[$key] = $rsfe_tp[$key];
            } else {
                $tracking_tp[$key] = $rsfe_tp[$key] / $mad1_tp[$key];
            }
        }

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
                                    <td><?= $key ?></td>
                                    <td><?= $d_tp[$key] ?></td>
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
                                <td></td>
                                <th colspan="3"> rata rata</th>
                                <td><?= round($rm_tp, 2) ?></td>
                                <td><?= round($asp_tp, 2) ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <?php
        break;
}
?>