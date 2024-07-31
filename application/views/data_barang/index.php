<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-sm-12 col-md-10">
        <h4 class="mb-0"><i class="fa fa-cubes"></i> Stok Barang</h4>
    </div>
    <div class="col-sm-12 col-md-2">
        <a href="<?= site_url('tambah_barang'); ?>" class="btn btn-success btn-sm btn-block">Tambah Data</a>
    </div>
</div>
<hr class="mt-0" />
<?php if ($this->session->flashdata('success')) : ?>
    <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $this->session->flashdata('success') ?>
    </div>
    <script>
        setTimeout(function() {
            $('#success-alert').alert('close');
        }, 3000); // 3 detik
    </script>
<?php endif; ?>

<?php if ($this->session->flashdata('error')) : ?>
    <div id="error-alert" class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $this->session->flashdata('error') ?>
    </div>
    <script>
        setTimeout(function() {
            $('#error-alert').alert('close');
        }, 3000); // 3 detik
    </script>
<?php endif; ?>
<?php
$this->session->unset_userdata('success');
$this->session->unset_userdata('error');
?>

<div class="table-responsive">
    <table class="table table-sm table-hover table-striped" id="tables">
        <thead class="thead-dark">
            <tr>
                <th scope="col">No</th>
                <th scope="col">Kode Barang</th>
                <th scope="col">Nama Barang</th>
                <th scope="col">Jenis</th>
                <th scope="col">Stok</th>
                <th scope="col">Satuan</th>
                <th scope="col">Harga Jual</th>
                <th scope="col">Lokasi</th>
                <th scope="col">Status</th>
                <th scope="col">Opsi</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>