<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<nav id="sidebar">
    <div class="p-4 pt-5">
        <a href="<?= site_url(); ?>" class="img logo rounded-circle mb-3" style="background-image: url(<?= base_url('assets/img/jms2.png'); ?>);"></a>
        <ul class="list-unstyled components mb-5">

            <li <?= (strtolower($this->uri->segment(1)) == 'dashboard') ? 'class="active"' : ''; ?>>
                <a href="<?= site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a>
            </li>
            <?php
            //tampilkan menu di bawah ini jika yang login admin
            if ($this->session->userdata('level') == 'admin') :
            ?>

                <li <?= (in_array(strtolower($this->uri->segment(1)), ['barang', 'tambah_barang', 'edit_barang'])) ? 'class="active"' : ''; ?>>
                    <a href="#pageDataBarang" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fa fa-cubes"></i> Data Barang
                    </a>

                    <ul class="collapse list-unstyled" id="pageDataBarang">
                        <li <?= (in_array(strtolower($this->uri->segment(1)), ['barang', 'tambah_barang', 'edit_barang'])) ? 'class="active"' : ''; ?>>
                            <a href="<?= site_url('barang'); ?>">
                                <i class="fa fa-angle-double-right"></i> Stok Barang
                            </a>
                        </li>
                        <li <?= (in_array(strtolower($this->uri->segment(1)), ['jenis_barang', 'tambah_jenis', 'edit_jenis'])) ? 'class="active"' : ''; ?>>
                            <a href="<?= site_url('jenis_barang'); ?>">
                                <i class="fa fa-angle-double-right"></i> Jenis Barang
                            </a>
                        </li>
                        <li <?= (in_array(strtolower($this->uri->segment(1)), ['satuan_barang', 'tambah_satuan', 'edit_satuan'])) ? 'class="active"' : ''; ?>>
                            <a href="<?= site_url('satuan_barang'); ?>">
                                <i class="fa fa-angle-double-right"></i> Satuan Barang
                            </a>
                        </li>
                        <li <?= (in_array(strtolower($this->uri->segment(1)), ['lokasi_barang', 'tambah_lokasi', 'edit_lokasi'])) ? 'class="active"' : ''; ?>>
                            <a href="<?= site_url('lokasi_barang'); ?>">
                                <i class="fa fa-angle-double-right"></i> Lokasi Barang
                            </a>
                        </li>
                    </ul>

                </li>

                <li <?= (in_array(strtolower($this->uri->segment(1)), ['pegawai', 'tambah_pegawai', 'edit_pegawai'])) ? 'class="active"' : ''; ?>>
                    <a href="<?= site_url('pegawai'); ?>"><i class="fa fa-users"></i> Data Pegawai</a>
                </li>

                <li <?= (in_array(strtolower($this->uri->segment(1)), ['supplier', 'tambah_supplier', 'edit_supplier'])) ? 'class="active"' : ''; ?>>
                    <a href="<?= site_url('supplier'); ?>">
                        <i class="fa fa-truck"></i> Data Supplier
                    </a>
                </li>
            <?php
            endif;
            ?>

            <?php
            //tampilkan menu di bawah ini jika yang login pegawai
            if ($this->session->userdata('level') == 'pegawai') :
            ?>
                <li <?= (in_array(strtolower($this->uri->segment(1)), ['stok_barang'])) ? 'class="active"' : ''; ?>>
                    <a href="<?= site_url('stok_barang'); ?>"><i class="fa fa-cubes"></i> Data Stok Barang</a>
                </li>
            <?php
            endif;
            ?>

            <li <?= (in_array(strtolower($this->uri->segment(1)), ['data_barang_masuk', 'tambah_barang_masuk', 'edit_barang_masuk'])) ? 'class="active"' : ''; ?>>
                <a href="<?= site_url('data_barang_masuk'); ?>"><i class="fa fa-share"></i> Data Barang Masuk</a>
            </li>

            <li <?= (in_array(strtolower($this->uri->segment(1)), ['data_barang_keluar', 'tambah_barang_keluar', 'edit_barang_keluar'])) ? 'class="active"' : ''; ?>>
                <a href="<?= site_url('data_barang_keluar'); ?>"><i class="fa fa-reply"></i> Data Barang Keluar</a>
            </li>

            <li <?= (in_array(strtolower($this->uri->segment(1)), ['stok_harian', 'stok_bulanan', 'stok_tahunan'])) ? 'class="active"' : ''; ?>>
                <a href="#pageStokBarang" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <i class="fa fa-file-text-o"></i> Laporan Stok Barang
                </a>

                <ul class="collapse list-unstyled" id="pageStokBarang">
                    <li <?= (strtolower($this->uri->segment(1)) == 'stok_harian') ? 'class="active"' : ''; ?>>
                        <a href="<?= site_url('stok_harian'); ?>">
                            <i class="fa fa-angle-double-right"></i> Harian
                        </a>
                    </li>
                    <li <?= (strtolower($this->uri->segment(1)) == 'stok_bulanan') ? 'class="active"' : ''; ?>>
                        <a href="<?= site_url('stok_bulanan'); ?>">
                            <i class="fa fa-angle-double-right"></i> Bulanan
                        </a>
                    </li>
                    <!-- <li <?= (strtolower($this->uri->segment(1)) == 'stok_tahunan') ? 'class="active"' : ''; ?>>
                        <a href="<?= site_url('stok_tahunan'); ?>">
                            <i class="fa fa-angle-double-right"></i> Tahunan
                        </a>
                    </li> -->
                </ul>
            </li>

            <li <?= (in_array(strtolower($this->uri->segment(1)), ['barang_masuk_harian', 'barang_masuk_bulanan', 'barang_masuk_tahunan'])) ? 'class="active"' : ''; ?>>
                <a href="#pageBarangMasuk" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <i class="fa fa-file-text-o"></i> Laporan Barang Masuk
                </a>

                <ul class="collapse list-unstyled" id="pageBarangMasuk">
                    <li <?= (strtolower($this->uri->segment(1)) == 'barang_masuk_harian') ? 'class="active"' : ''; ?>>
                        <a href="<?= site_url('barang_masuk_harian'); ?>" <?= (strtolower($this->uri->segment(1)) == 'barang_masuk_harian') ? 'class="active"' : ''; ?>>
                            <i class="fa fa-angle-double-right"></i> Harian
                        </a>
                    </li>
                    <li <?= (strtolower($this->uri->segment(1)) == 'barang_masuk_bulanan') ? 'class="active"' : ''; ?>>
                        <a href="<?= site_url('barang_masuk_bulanan'); ?>" <?= (strtolower($this->uri->segment(1)) == 'barang_masuk_bulanan') ? 'class="active"' : ''; ?>>
                            <i class="fa fa-angle-double-right"></i> Bulanan
                        </a>
                    </li>
                </ul>
            </li>

            <li <?= (in_array(strtolower($this->uri->segment(1)), ['barang_keluar_harian', 'barang_keluar_bulanan'])) ? 'class="active"' : ''; ?>>
                <a href="#pageBarangKeluar" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fa fa-file-text-o"></i> Laporan Barang Keluar</a>
                <ul class="collapse list-unstyled" id="pageBarangKeluar">
                    <li <?= (strtolower($this->uri->segment(1)) == 'barang_keluar_harian') ? 'class="active"' : ''; ?>>
                        <a href="<?= site_url('barang_keluar_harian'); ?>">
                            <i class="fa fa-angle-double-right"></i> Harian
                        </a>
                    </li>
                    <li <?= (strtolower($this->uri->segment(1)) == 'barang_keluar_bulanan') ? 'class="active"' : ''; ?>>
                        <a href="<?= site_url('barang_keluar_bulanan'); ?>">
                            <i class="fa fa-angle-double-right"></i> Bulanan
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        <div class="footer">
            <p>
                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                Copyright &copy;<script>
                    document.write(new Date().getFullYear());
                </script> All rights reserved

                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            </p>
        </div>

    </div>
</nav>