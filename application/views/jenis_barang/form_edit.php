<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-sm-12 col-md-10">
        <h4 class="mb-0"><i class="fa fa-cubes"></i> Edit Data Jenis</h4>
    </div>
</div>
<hr class="mt-0" />
<?= form_open(); ?>
<div class="col-md-8">

    <div class="form-group row">
        <label for="nama_jenis" class="col-sm-3 col-form-label">Nama Jenis</label>
        <div class="col-sm-9">
            <input type="text" class="form-control form-control-sm <?= (form_error('nama_jenis')) ? 'is-invalid' : ''; ?>" id="nama_jenis" name="nama_jenis" placeholder="Nama Jenis" value="<?= (set_value('nama_jenis')) ? set_value('nama_jenis') : $jenis->nama_jenis; ?>">
            <div class="invalid-feedback">
                <?= form_error('nama_jenis', '<p class="error-message">', '</p>'); ?>
            </div>
        </div>
    </div>



    <div class="form-group row">
        <div class="col-sm-9 offset-md-3">
            <button type="submit" name="update" value="Update" class="btn btn-primary btn-sm">Update Data</button>
            <a href="<?= site_url('jenis_barang'); ?>" class="btn btn-light btn-sm">
                Kembali
            </a>
        </div>
    </div>
</div>
<?= form_close(); ?>