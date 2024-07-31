<?php
defined('BASEPATH') or exit('No direct script access allowed');

function tanggal_indo($tgl)
{
    $bulan  = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    $exp    = explode('-', date('d-m-Y', strtotime($tgl)));

    return $exp[0] . ' ' . $bulan[(int) $exp[1]] . ' ' . $exp[2];
}
?>

<div class="row">
    <div class="col-sm-12 col-md-10">
        <h4 class="mb-0"><i class="fa fa-file-text"></i> Laporan Bulanan Barang Keluar</h4>
    </div>
</div>
<hr class="mt-0" />
<?php if ($this->session->flashdata('alert')) : ?>
    <div id="error-alert" class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $this->session->flashdata('alert') ?>
    </div>
    <script>
        setTimeout(function() {
            $('#error-alert').alert('close');
        }, 3000); // 3 detik
    </script>
<?php endif; ?>
<?php
$this->session->unset_userdata('alert');
?>
<div class="row">
    <div class="col-md-10 col-sm-12">
        <?= form_open('', ['class' => "form-inline"]); ?>
        <div class="form-group mx-sm-2 mb-2">
            <label for="bulan" class="sr-only">Bulan</label>
            <select name="bulan" id="bulan" class="form-control form-control-sm" style="min-width:150px">
                <option value="januari" <?= (strtolower($bulan) == 'januari') ? 'selected' : ''; ?>>Januari</option>
                <option value="februari" <?= (strtolower($bulan) == 'februari') ? 'selected' : ''; ?>>Februari</option>
                <option value="maret" <?= (strtolower($bulan) == 'maret') ? 'selected' : ''; ?>>Maret</option>
                <option value="april" <?= (strtolower($bulan) == 'april') ? 'selected' : ''; ?>>April</option>
                <option value="mei" <?= (strtolower($bulan) == 'mei') ? 'selected' : ''; ?>>Mei</option>
                <option value="juni" <?= (strtolower($bulan) == 'juni') ? 'selected' : ''; ?>>Juni</option>
                <option value="juli" <?= (strtolower($bulan) == 'juli') ? 'selected' : ''; ?>>Juli</option>
                <option value="agustus" <?= (strtolower($bulan) == 'agustus') ? 'selected' : ''; ?>>Agustus</option>
                <option value="september" <?= (strtolower($bulan) == 'september') ? 'selected' : ''; ?>>September</option>
                <option value="oktober" <?= (strtolower($bulan) == 'oktober') ? 'selected' : ''; ?>>Oktober</option>
                <option value="november" <?= (strtolower($bulan) == 'november') ? 'selected' : ''; ?>>November</option>
                <option value="desember" <?= (strtolower($bulan) == 'desember') ? 'selected' : ''; ?>>Desember</option>
            </select>
        </div>
        <div class="form-group mx-sm-2 mb-2">
            <label for="tahun" class="sr-only">Tahun</label>
            <select name="tahun" id="tahun" class="form-control form-control-sm" style="min-width:130px">
                <?php
                for ($i = 2020; $i  < 2040; $i++) {
                    $selected = ($i == $tahun) ? 'selected' : '';

                    echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mb-2 btn-sm" name="cari" value="Search">
            Cari Data
        </button>
        <?= form_close(); ?>
    </div>
    <div class="col-md-2 col-sm-12">
        <a href="<?= site_url('barang_keluar_bulanan/' . $bulan . '-' . $tahun); ?>" class="btn btn-success btn-block btn-sm" target="_blank">
            <i class="fa fa-print"></i> Cetak Laporan
        </a>
        <a href="<?= site_url('barang_keluar_bulanan/export_excel/' . $bulan . '-' . $tahun); ?>" class="btn btn-info btn-block btn-sm" target="_blank">
            <i class="fa fa-file-excel-o"></i> Export Excel
        </a>
    </div>
</div>
<table class="table table-sm table-bordered mt-3">
    <thead class="thead-dark">
        <tr>
            <th scope="col">No</th>
            <th scope="col">Tanggal</th>
            <th scope="col">ID Barang Keluar</th>
            <th scope="col">Nama Pembeli</th>
            <th scope="col">Nama Barang</th>
            <th scope="col" class="text-center">Qty</th>
            <th scope="col">Satuan</th>
            <th scope="col" class="text-center">Harga</th>
            <th scope="col" class="text-center">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        $row_barang_keluar = 1;
        $row_tanggal = 1;

        if ($data->num_rows() > 0) {
            $total = 0;

            foreach ($data->result() as $dt) {
                echo '<tr>';
                if ($row_tanggal == 1) :
                    echo '<td rowspan="' . $dt->row_tanggal . '">' . $i++ . '</td>';
                    echo '<td rowspan="' . $dt->row_tanggal . '">' . tanggal_indo($dt->tgl_barang_keluar) . '</td>';
                endif;
                if ($row_barang_keluar == 1) :

                    echo '<td rowspan="' . $dt->row_barang_keluar . '">' . $dt->id_barang_keluar . '</td>';
                    echo '<td rowspan="' . $dt->row_barang_keluar . '">' . $dt->nama_pembeli . '</td>';
                endif;
                echo '<td>' . $dt->nama_barang . '</td>';
                echo '<td>' . $dt->qty . '</td>';
                echo '<td>' . $dt->nama_satuan . '</td>';
                echo '<td><span class="float-left">Rp.</span><span class="float-right">' . number_format($dt->harga, 0, ',', '.') . '</span></td>';
                echo '<td><span class="float-left">Rp.</span><span class="float-right">' . number_format(($dt->harga * $dt->qty), 0, ',', '.') . '</span></td>';
                echo '</tr>';
                if ($row_barang_keluar != $dt->row_barang_keluar) {
                    $row_barang_keluar++;
                } else {
                    $row_barang_masuk = 1;
                }

                if ($row_tanggal != $dt->row_tanggal) {
                    $row_tanggal++;
                } else {
                    $row_tanggal = 1;
                }

                $total += ($dt->harga * $dt->qty);
            }

            echo '<tr>';
            echo '<td colspan="8" class="text-center"><b>Total Pemasukan</b></td>';
            echo '<td><b><span class="float-left">Rp.</span><span class="float-right">' . number_format($total, 0, ',', '.') . '</span></b></td>';
            echo '</tr>';
        } else {
            echo '<tr>';
            echo '<td colspan="9" class="text-center">Data tidak ditemukan</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>