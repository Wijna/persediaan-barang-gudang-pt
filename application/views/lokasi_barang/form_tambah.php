<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-sm-12 col-md-10">
        <h4 class="mb-0"><i class="fa fa-cubes"></i> Tambah Lokasi Barang</h4>
    </div>
</div>
<hr class="mt-0" />
<?= form_open(); ?>
<div class="col-md-8">

    <div class="form-group row">
        <label for="nama_lokasi" class="col-sm-3 col-form-label">Nama Lokasi</label>
        <div class="col-sm-9">
            <input type="text" class="form-control form-control-sm <?= (form_error('nama_lokasi')) ? 'is-invalid' : ''; ?>" id="nama_lokasi" name="nama_lokasi" placeholder="Nama Lokasi" value="<?= set_value('nama_lokasi'); ?>">
            <div class="invalid-feedback">
                <?= form_error('nama_lokasi', '<p class="error-message">', '</p>'); ?>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-9 offset-md-3">
            <button type="submit" name="submit" value="submit" class="btn btn-primary btn-sm">Simpan Data</button>
            <a href="<?= site_url('lokasi_barang'); ?>" class="btn btn-light btn-sm">
                Kembali
            </a>
        </div>
    </div>
</div>
<?= form_close(); ?>