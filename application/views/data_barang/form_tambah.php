<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-sm-12 col-md-10">
        <h4 class="mb-0"><i class="fa fa-cubes"></i> Tambah Data Barang</h4>
    </div>
</div>
<hr class="mt-0" />
<?= form_open(); ?>
<div class="col-md-8">

    <div class="form-group row">
        <label for="KodeBarang" class="col-sm-3 col-form-label">Kode Barang</label>
        <div class="col-sm-9 col-md-6">
            <input type="text" class="form-control form-control-sm <?= (form_error('kode')) ? 'is-invalid' : ''; ?>" id="KodeBarang" required autofocus name="kode" placeholder="Kode Barang" value="<?= set_value('kode'); ?>">
            <div class="invalid-feedback">
                <?= form_error('kode', '<p class="error-message">', '</p>'); ?>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="nama_barang" class="col-sm-3 col-form-label">Nama Barang</label>
        <div class="col-sm-9">
            <input type="text" class="form-control form-control-sm <?= (form_error('nama_barang')) ? 'is-invalid' : ''; ?>" id="nama_barang" name="nama_barang" placeholder="Nama Barang" value="<?= set_value('nama_barang'); ?>">
            <div class="invalid-feedback">
                <?= form_error('nama_barang', '<p class="error-message">', '</p>'); ?>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="jenis" class="col-sm-3 col-form-label">Jenis</label>
        <div class="col-sm-9">
            <select class="custom-select <?= (form_error('jenis')) ? 'is-invalid' : ''; ?>" id="jenis" name="jenis">
                <option value="" disabled selected>Pilih Jenis</option>
                <?php foreach ($jeniss->result_array() as $b) : ?>
                    <option value="<?= $b['id_jenis'] ?>"><?= $b['nama_jenis'] ?></option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">
                <?= form_error('jenis', '<p class="error-message">', '</p>'); ?>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="satuan" class="col-sm-3 col-form-label">Satuan</label>
        <div class="col-sm-9">
            <select class="custom-select <?= (form_error('satuan')) ? 'is-invalid' : ''; ?>" id="satuan" name="satuan">
                <option disabled selected value="">Pilih Satuan</option>
                <?php foreach ($satuans->result_array() as $b) : ?>
                    <option value="<?= $b['id_satuan'] ?>"><?= $b['nama_satuan'] ?></option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">
                <?= form_error('satuan', '<p class="error-message">', '</p>'); ?>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="lokasi" class="col-sm-3 col-form-label">Lokasi</label>
        <div class="col-sm-9">
            <select class="custom-select <?= (form_error('lokasi')) ? 'is-invalid' : ''; ?>" id="lokasi" name="lokasi">
                <option disabled selected value="">Pilih Lokasi</option>
                <?php foreach ($lokasis->result_array() as $b) : ?>
                    <option value="<?= $b['id_lokasi'] ?>"><?= $b['nama_lokasi'] ?></option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">
                <?= form_error('lokasi', '<p class="error-message">', '</p>'); ?>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="harga" class="col-sm-3 col-form-label">Harga Jual</label>
        <div class="col-sm-6">
            <input type="text" class="form-control form-control-sm uang <?= (form_error('harga')) ? 'is-invalid' : ''; ?>" id="harga" name="harga" placeholder="Harga Jual" value="<?= set_value('harga'); ?>">
            <div class="invalid-feedback">
                <?= form_error('harga', '<p class="error-message">', '</p>'); ?>
            </div>
        </div>
    </div>

</div>

<div class="form-group row">
    <div class="col-sm-9 offset-md-3">
        <button type="submit" name="submit" value="submit" class="btn btn-primary btn-sm">Simpan Data</button>
        <a href="<?= site_url('barang'); ?>" class="btn btn-light btn-sm">
            Kembali
        </a>
    </div>
</div>
</div>
<?= form_close(); ?>