
<div class="animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Grafik</strong>
                </div>
                <div class="card-body">
                    <form class="form-horizontal">
                        <div class="row form-group">
                            <div class="col col-md-1"><label for="select" class=" form-control-label">Tanggal</label></div>
                            <div class="col-md-3">
                                <input type="text" name="tanggal_awal" value="<?php echo isset($_GET['tanggal_awal'])?$_GET['tanggal_awal']:date('d-m-Y')?>" class="form-control datepicker">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="tanggal_akhir" value="<?php echo isset($_GET['tanggal_akhir'])?$_GET['tanggal_akhir']:date('d-m-Y')?>" class="form-control datepicker">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-1"><label for="select" class=" form-control-label">Nama Obat</label></div>
                            <div class="col-md-3">
                                <select name="id_obat" id="select" class="standardSelect" tabindex="1" required>
                                    <option value="">---Pilih Obat---</option>

                                    <?php
                                    $tampil = mysqli_query($koneksi, "SELECT * FROM table_obat");

                                    while ($r = mysqli_fetch_array($tampil)) {
                                        ?>
                                        <option value="<?= $r['id_obat'] ?>" <?php echo $r['id_obat'] == $_GET['id_obat'] ? 'selected' : NULL; ?>><?= $r['nama_obat'] ?><?=' ' ?><?= $r['bentuk_obat'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="id_obat1" id="select" class="standardSelect" tabindex="1" required>
                                    <option value="">---Pilih Obat---</option>

                                    <?php
                                    $tampil = mysqli_query($koneksi, "SELECT * FROM table_obat");

                                    while ($r = mysqli_fetch_array($tampil)) {
                                        ?>
                                        <option value="<?= $r['id_obat'] ?>" <?php echo $r['id_obat'] == $_GET['id_obat1'] ? 'selected' : NULL; ?>><?= $r['nama_obat'] ?><?=' ' ?><?= $r['bentuk_obat'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="id_obat2" id="select" class="standardSelect" tabindex="1" required>
                                    <option value="">---Pilih Obat---</option>

                                    <?php
                                    $tampil = mysqli_query($koneksi, "SELECT * FROM table_obat");

                                    while ($r = mysqli_fetch_array($tampil)) {
                                        ?>
                                        <option value="<?= $r['id_obat'] ?>" <?php echo $r['id_obat'] == $_GET['id_obat2'] ? 'selected' : NULL; ?>><?= $r['nama_obat'] ?><?=' ' ?><?= $r['bentuk_obat'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <input type="hidden" name="module" value="grafik">
                            <div class="col-12 col-md-4 offset-md-5">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-dot-circle-o col-md">Proses </i> 
                                </button>
                            </div>
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

                    if (!empty($_GET['id_obat']) || !empty($_GET['id_obat1']) || !empty($_GET['id_obat2'])) {

                        $label_x=week_between_two_dates(date('Y-m-d',strtotime($_GET['tanggal_awal'])), date('Y-m-d',strtotime($_GET['tanggal_akhir'])));
                        
                        $x=array();
                        $y=array();
                        $a=array();
                        
                        $x1=array();
                        $y1=array();
                        $a1=array();
                        
                        $x2=array();
                        $y2=array();
                        $a2=array();
                        
                        
                        $tgl_awal=date('Y-m-d',strtotime($_GET['tanggal_awal']));
                        $tgl_akhir=date('Y-m-d',strtotime($_GET['tanggal_akhir']));

                                $result = mysqli_query($koneksi, "SELECT sum(jumlah) as jumlah,CONCAT(DATE_FORMAT(tanggal, '%Y'),'/',DATE_FORMAT(tanggal, '%m')) AS tahun_bulan FROM data_rekap WHERE id_obat = $_GET[id_obat] AND tanggal >= '$tgl_awal' and tanggal <='$tgl_akhir' GROUP BY MONTH(tanggal),id_obat,YEAR(tanggal) ORDER BY tanggal ASC");
                                
                                $i=0;
                                while ($r = mysqli_fetch_array($result)) {

                                    array_push($x,$r['tahun_bulan']);

                                    if(!empty($r['jumlah']))
                                    {
                                       array_push($y,$r['jumlah']); 
                                    }
                                    else
                                    {
                                        array_push($y,0);
                                    }
                                    
                                    array_push($a,$r);
                                }
                                $jumlah=getTotal($label_x, $a);
                                

                                $result1 = mysqli_query($koneksi, "SELECT sum(jumlah) as jumlah,CONCAT(DATE_FORMAT(tanggal, '%Y'),'/',DATE_FORMAT(tanggal, '%m')) AS tahun_bulan  FROM data_rekap WHERE id_obat = $_GET[id_obat1] AND tanggal >= '$tgl_awal' and tanggal <='$tgl_akhir' GROUP BY MONTH(tanggal),id_obat,YEAR(tanggal) ORDER BY tanggal ASC");
                                                
                                while ($r1 = mysqli_fetch_array($result1)) {
                                    array_push($x1,$r1['tahun_bulan']);
                                    if(!empty($r1['jumlah']))
                                    {
                                       array_push($y1,$r1['jumlah']); 
                                    }
                                    else
                                    {
                                       array_push($y1,0);
                                    }
                                    array_push($a1,$r1);
                                }
                                $jumlah1=getTotal($label_x, $a1);
                                
                                $result2 = mysqli_query($koneksi, "SELECT sum(jumlah) as jumlah,CONCAT(DATE_FORMAT(tanggal, '%Y'),'/',DATE_FORMAT(tanggal, '%m')) AS tahun_bulan  FROM data_rekap WHERE id_obat = $_GET[id_obat2] AND tanggal >= '$tgl_awal' and tanggal <='$tgl_akhir' GROUP BY MONTH(tanggal),id_obat,YEAR(tanggal) ORDER BY tanggal ASC");
                                                
                                while ($r2 = mysqli_fetch_array($result2)) {
                                    array_push($x2,$r2['tahun_bulan']);
                                    if(!empty($r2['jumlah']))
                                    {
                                       array_push($y2,$r2['jumlah']); 
                                    }
                                    else
                                    {
                                       array_push($y2,0);
                                    }
                                    array_push($a2,$r2);
                                }
                                $jumlah2=getTotal($label_x, $a2);
                                  
                                $tampil = mysqli_query($koneksi, "SELECT id_obat, CONCAT((nama_obat),' ',(bentuk_obat)) as fullname FROM table_obat where id_obat=$_GET[id_obat]");
                                $label = mysqli_fetch_array($tampil);
                                
                                $tampil1 = mysqli_query($koneksi, "SELECT id_obat, CONCAT((nama_obat),' ',(bentuk_obat)) as fullname FROM table_obat where id_obat=$_GET[id_obat1]");
                                $label1 = mysqli_fetch_array($tampil1);
                                
                                $tampil2 = mysqli_query($koneksi, "SELECT id_obat, CONCAT((nama_obat),' ',(bentuk_obat)) as fullname FROM table_obat where id_obat=$_GET[id_obat2]");
                                $label2 = mysqli_fetch_array($tampil2);
                                

                        ?>
                        <h4 class="mb-3">Grafik Penjualan </h4>
                        <canvas id="team-chart2"></canvas>

                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
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

        //Team chart
        var ctx = document.getElementById("team-chart2");

        ctx.height = 150;
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($label_x)?>,
                type: 'line',
                defaultFontFamily: 'Montserrat',
                datasets: [ {
                        data: <?= json_encode($jumlah) ?>,
                        label: <?= json_encode($label[('fullname')])?>,
                        backgroundColor: 'transparent',
                        borderColor: 'rgba(218, 165, 32,0.75)',
                        borderWidth: 3.5,
                        pointStyle: 'circle',
                        pointRadius: 5,
                        pointBorderColor: 'transparent',
                        pointBackgroundColor: 'rgba(218, 165, 32,0.75)',
                    }, {
                        data: <?= json_encode($jumlah1) ?>,
                        label: <?= json_encode($label1['fullname'])?>,
                        backgroundColor: 'transparent',
                        borderColor: 'rgba(40,167,69,0.75)',
                        borderWidth: 3.5,
                        pointStyle: 'circle',
                        pointRadius: 5,
                        pointBorderColor: 'transparent',
                        pointBackgroundColor: 'rgba(40,167,69,0.75)',
                    }, {
                            data:<?= json_encode($jumlah2) ?>,
                            label: <?= json_encode($label2['fullname'])?>,
                            backgroundColor: 'transparent',
                            borderColor: 'rgba(220,53,69,0.75)',
                            borderWidth: 3.5,
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
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        fontFamily: 'Montserrat',
                    },
                },
                scales: {
                    xAxes: [{
                            display: true,
                            gridLines: {
                                display: false,
                                drawBorder: false
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Rentang Penjualan'
                            }
                        }],
                    yAxes: [{
                            display: true,
                            gridLines: {
                                display: true,
                                drawBorder: true
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Jumlah Penjualan'
                            }
                        }]
                },
                title: {
                    display: false,
                }
            }
        });


    })(jQuery);
</script>
