<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-sm-12 col-md-10">
        <h4 class="mb-0"><i class="fa fa-users"></i> Data Pegawai</h4>
    </div>
    <div class="col-sm-12 col-md-2">
        <a href="<?= site_url('tambah_pegawai'); ?>" class="btn btn-success btn-sm btn-block">Tambah Data</a>
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
                <th scope="col">Username</th>
                <th scope="col">Nama Pegawai</th>
                <!-- <th scope="col">Status</th> -->
                <th scope="col">Terakhir Login</th>
                <th scope="col">Opsi</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<!-- Modal Ubah password -->
<div class="modal fade" id="modal-password" tabindex="-1" role="dialog" aria-labelledby="modal-passwordLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= form_open('ganti_password'); ?>
            <input type="hidden" id="IdU" value="" name="IdU" />
            <div class="modal-header">
                <h5 class="modal-title" id="modal-passwordLabel"><i class="fa fa-refresh"></i> Ganti Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="password-baru">Password Baru Pegawai</label>
                    <input type="password" class="form-control form-control-sm" id="password-baru" name="pwUser" placeholder="Password Baru Pegawai">
                </div>
                <div class="form-group">
                    <label for="password-admin">Password Admin</label>
                    <input type="password" class="form-control form-control-sm" id="password-admin" name="pwAdmin" placeholder="Password Admin">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="submit" value="Submit" class="btn btn-primary btn-sm">Ubah Password</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>