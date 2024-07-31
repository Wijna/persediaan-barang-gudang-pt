<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-sm-12 col-md-10">
        <h4 class="mb-0"><i class="fa fa-cubes"></i> Edit Data Satuan</h4>
    </div>
</div>
<hr class="mt-0" />
<?= form_open(); ?>
<div class="col-md-8">

    <div class="form-group row">
        <label for="nama_satuan" class="col-sm-3 col-form-label">Nama Satuan</label>
        <div class="col-sm-9">
            <input type="text" class="form-control form-control-sm <?= (form_error('nama_satuan')) ? 'is-invalid' : ''; ?>" id="nama_satuan" name="nama_satuan" placeholder="Nama Satuan" value="<?= (set_value('nama_satuan')) ? set_value('nama_satuan') : $satuan->nama_satuan; ?>">
            <div class="invalid-feedback">
                <?= form_error('nama_satuan', '<p class="error-message">', '</p>'); ?>
            </div>
        </div>
    </div>

    

    <div class="form-group row">
        <div class="col-sm-9 offset-md-3">
            <button type="submit" name="update" value="Update" class="btn btn-primary btn-sm">Update Data</button>
            <a href="<?= site_url('satuan_barang'); ?>" class="btn btn-light btn-sm">
                Kembali
            </a>
        </div>
    </div>
</div>
<?= form_close(); ?>