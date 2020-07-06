<?php

include "config/koneksi.php";
include "config/fungsi_indotgl.php";


// Bagian Home
if ($_GET['module'] == 'home') {
    include"modules/home/home.php";
} else

// Bagian Data Obat
if ($_GET['module'] == 'obat') {
    include"modules/obat/obat.php";
} else

// Bagian Kategori Obat
if ($_GET['module'] == 'kategori') {
    include"modules/kategori/kategori.php";
}
else

// Bagian Kategori Obat
if ($_GET['module'] == 'transaksi') {
    include"modules/transaksi/transaksi.php";
}
 else

// Bagian Data Manufaktur Obat
if ($_GET['module'] == 'manufaktur') {
    include"modules/manufaktur/manufaktur.php";
} else
    
// Bagian Data Rekap
if ($_GET['module'] == 'rekap') {
    include"modules/rekap/rekap1.php";
} else
    
// Bagian Laporan
if ($_GET['module'] == 'laporan') {
    include"modules/laporan/laporan.php";
} else

// Bagian Grafik
if ($_GET['module'] == 'grafik') {
    include"modules/grafik/grafik.php";
} else

// Bagian Peramalan
if ($_GET['module'] == 'peramalan') {
    include"modules/peramalan/peramalan.php";
} else

// Bagian user akun
if ($_GET['module'] == 'user') {
    include "modules/user/user.php ";
}else

if ($_GET['module'] == 'stok') {
    include "modules/stok/stok.php ";
}
// Apabila modul tidak ditemukan
else {
    echo "<p><b>MODUL BELUM ADA ATAU BELUM LENGKAP</b></p>";
}
?>
