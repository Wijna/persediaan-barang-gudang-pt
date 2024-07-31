<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<img src="<?= base_url('assets/img/jms3.jpg'); ?>" class="logo" />
<h6 class="display-5 text-center mt-2 mb-0">Laporan Bulanan Stok Barang</h6>
<p class="text-center display-6 mt-0"><?= 'Bulan ' . ucwords($bulan) . ' Tahun ' . $tahun; ?></p>
<hr class="mt-0" />
<table class="table table-sm table-bordered table-striped mt-3">
    <thead>
        <tr>
            <th scope="col">No</th>
            <th scope="col">Kode Barang</th>
            <th scope="col">Nama Barang</th>
            <th scope="col" class="text-center">Stok Barang</th>
            <th scope="col">Satuan</th>
            <th scope="col" class="text-center">Qty Barang Masuk</th>
            <th scope="col" class="text-center">Qty Barang Keluar</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $dt) {
                $barang_masuk = ($dt->qty_barang_masuk_new != '') ? $dt->qty_barang_masuk_new : 0;
                $barang_keluar = ($dt->qty_barang_keluar_new != '') ? $dt->qty_barang_keluar_new : 0;

                echo '<tr>';
                echo '<td>' . $i++ . '</td>';
                echo '<td>' . $dt->kode_barang . '</td>';
                echo '<td>' . $dt->nama_barang . '</td>';
                echo '<td class="text-center">' . (($dt->stok + $barang_keluar) - $barang_masuk) . '</td>';
                echo '<td>' . $dt->nama_satuan . '</td>';
                echo '<td class="text-center">' . (($dt->qty_barang_masuk != '') ? $dt->qty_barang_masuk : 0) . '</td>';
                echo '<td class="text-center">' . (($dt->qty_barang_keluar != '') ? $dt->qty_barang_keluar : 0) . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr>';
            echo '<td colspan="7" class="text-center">Data tidak ditemukan</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>