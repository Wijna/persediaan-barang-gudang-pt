<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_laporan extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getDataStokHarian($tanggal)
    {
        $table = 'tbl_barang b
                    LEFT JOIN
                    (SELECT qty, id_barang FROM tbl_barang_keluar pn
                    LEFT JOIN tbl_detail_barang_keluar dpn ON(pn.id_barang_keluar = dpn.id_barang_keluar AND tgl_barang_keluar = \'' . $tanggal . '\')) AS c ON(b.kode_barang = c.id_barang)
                    LEFT JOIN
                    (SELECT qty, id_barang FROM tbl_barang_masuk pm
                    LEFT JOIN tbl_detail_barang_masuk dpm ON(pm.id_barang_masuk = dpm.id_barang_masuk AND tgl_barang_masuk = \'' . $tanggal . '\')) AS d ON(b.kode_barang = d.id_barang)
                    LEFT JOIN
                    (SELECT qty, id_barang FROM tbl_barang_keluar pn
                    LEFT JOIN tbl_detail_barang_keluar dpn ON(pn.id_barang_keluar = dpn.id_barang_keluar AND tgl_barang_keluar > \'' . $tanggal . '\')) AS e ON(b.kode_barang = e.id_barang)
                    LEFT JOIN
                    (SELECT qty, id_barang FROM tbl_barang_masuk pm
                    LEFT JOIN tbl_detail_barang_masuk dpm ON(pm.id_barang_masuk = dpm.id_barang_masuk AND tgl_barang_masuk > \'' . $tanggal . '\')) AS f ON(b.kode_barang = f.id_barang)
                    LEFT JOIN tbl_satuan_barang ON(b.id_satuan = tbl_satuan_barang.id_satuan)';

        $select = 'kode_barang, nama_barang, nama_satuan, stok, SUM(c.qty) AS qty_barang_keluar, SUM(d.qty) AS qty_barang_masuk, SUM(e.qty) AS qty_barang_keluar_new, SUM(f.qty) AS qty_barang_masuk_new';

        $group = ['kode_barang', 'nama_barang', 'nama_satuan', 'stok'];

        $this->db->select($select);
        $this->db->from($table);
        $this->db->group_by($group);

        return $this->db->get();
    }

    function getDataStokBulanan($bulan, $tahun)
    {

        $tanggal1 = $tahun . '-' . $bulan . '-01';
        $tanggal2 = $tahun . '-' . $bulan . '-31';
        $table = 'tbl_barang b
                    LEFT JOIN
                    (SELECT qty, id_barang FROM tbl_barang_keluar pn
                    LEFT JOIN tbl_detail_barang_keluar dpn ON(pn.id_barang_keluar = dpn.id_barang_keluar AND tgl_barang_keluar >= \'' . $tanggal1 . '\' AND tgl_barang_keluar <= \'' . $tanggal2 . '\')) AS c ON(b.kode_barang = c.id_barang)
                    LEFT JOIN
                    (SELECT qty, id_barang FROM tbl_barang_masuk pm
                    LEFT JOIN tbl_detail_barang_masuk dpm ON(pm.id_barang_masuk = dpm.id_barang_masuk AND tgl_barang_masuk >= \'' . $tanggal1 . '\' AND tgl_barang_masuk <= \'' . $tanggal2 . '\')) AS d ON(b.kode_barang = d.id_barang)
                    LEFT JOIN
                    (SELECT qty, id_barang FROM tbl_barang_keluar pn
                    LEFT JOIN tbl_detail_barang_keluar dpn ON(pn.id_barang_keluar = dpn.id_barang_keluar AND tgl_barang_keluar > \'' . $tanggal2 . '\')) AS e ON(b.kode_barang = e.id_barang)
                    LEFT JOIN
                    (SELECT qty, id_barang FROM tbl_barang_masuk pm
                    LEFT JOIN tbl_detail_barang_masuk dpm ON(pm.id_barang_masuk = dpm.id_barang_masuk AND tgl_barang_masuk > \'' . $tanggal2 . '\')) AS f ON(b.kode_barang = f.id_barang) 
                    LEFT JOIN tbl_satuan_barang ON(b.id_satuan = tbl_satuan_barang.id_satuan)';

        $select = 'kode_barang, nama_barang, nama_satuan, stok, SUM(c.qty) AS qty_barang_keluar, SUM(d.qty) AS qty_barang_masuk, SUM(e.qty) AS qty_barang_keluar_new, SUM(f.qty) AS qty_barang_masuk_new';

        $group = ['kode_barang', 'nama_barang', 'nama_satuan', 'stok'];

        $this->db->select($select);
        $this->db->from($table);
        $this->db->group_by($group);

        return $this->db->get();
    }

    function getDataStokTahunan($tahun)
    {
        $table = 'tbl_barang b
                    LEFT JOIN
                    (SELECT qty, id_barang FROM tbl_barang_keluar pn
                    LEFT JOIN tbl_detail_barang_keluar dpn ON(pn.id_barang_keluar = dpn.id_barang_keluar AND YEAR(tgl_barang_keluar) = \'' . $tahun . '\')) AS c ON(b.kode_barang = c.id_barang)
                    LEFT JOIN
                    (SELECT qty, id_barang FROM tbl_barang_masuk pm
                    LEFT JOIN tbl_detail_barang_masuk dpm ON(pm.id_barang_masuk = dpm.id_barang_masuk AND YEAR(tgl_barang_masuk) = \'' . $tahun . '\')) AS d ON(b.kode_barang = d.id_barang)
                    LEFT JOIN
                    (SELECT qty, id_barang FROM tbl_barang_keluar pn
                    LEFT JOIN tbl_detail_barang_keluar dpn ON(pn.id_barang_keluar = dpn.id_barang_keluar AND YEAR(tgl_barang_keluar) > \'' . $tahun . '\')) AS e ON(b.kode_barang = e.id_barang)
                    LEFT JOIN
                    (SELECT qty, id_barang FROM tbl_barang_masuk pm
                    LEFT JOIN tbl_detail_barang_masuk dpm ON(pm.id_barang_masuk = dpm.id_barang_masuk AND YEAR(tgl_barang_masuk) > \'' . $tahun . '\')) AS f ON(b.kode_barang = f.id_barang) 
                    LEFT JOIN tbl_satuan_barang ON(b.id_satuan = tbl_satuan_barang.id_satuan)';

        $select = 'kode_barang, nama_barang, nama_satuan, stok, SUM(c.qty) AS qty_barang_keluar, SUM(d.qty) AS qty_barang_masuk, SUM(e.qty) AS qty_barang_keluar_new, SUM(f.qty) AS qty_barang_masuk_new';

        $group = ['kode_barang', 'nama_barang', 'nama_satuan', 'stok'];

        $this->db->select($select);
        $this->db->from($table);
        $this->db->group_by($group);

        return $this->db->get();
    }

    function getDataBarangMasukHarian($tanggal)
    {
        $select = 'p.id_barang_masuk AS id_barang_masuk, nama_barang, nama_satuan, dp.harga AS harga, qty, nama_supplier, (SELECT COUNT(*) FROM tbl_detail_barang_masuk WHERE id_barang_masuk = p.id_barang_masuk) AS row';

        $table = 'tbl_barang_masuk p
                    JOIN tbl_detail_barang_masuk dp ON(p.id_barang_masuk = dp.id_barang_masuk)
                    LEFT JOIN tbl_barang b ON(dp.id_barang = b.kode_barang)
                    LEFT JOIN tbl_supplier s ON(p.id_supplier = s.id_supplier)
                    LEFT JOIN tbl_satuan_barang ON(b.id_satuan = tbl_satuan_barang.id_satuan)';

        $where = ['p.tgl_barang_masuk' => $tanggal];

        $this->db->select($select);
        $this->db->from($table);
        $this->db->where($where);

        return $this->db->get();
    }

    function getDataBarangMasukBulanan($bulan, $tahun)
    {
        $tgl1 = $tahun . '-' . $bulan . '-01';
        $tgl2 = $tahun . '-' . $bulan . '-31';

        $select = 'p.id_barang_masuk AS id_barang_masuk, nama_barang, nama_satuan, dp.harga AS harga, qty, nama_supplier, (SELECT COUNT(*) FROM tbl_detail_barang_masuk WHERE id_barang_masuk = p.id_barang_masuk) AS row_barang_masuk, (SELECT COUNT(*) FROM tbl_barang_masuk JOIN tbl_detail_barang_masuk dp ON(tbl_barang_masuk.id_barang_masuk = dp.id_barang_masuk) WHERE tgl_barang_masuk = p.tgl_barang_masuk) AS row_tanggal, tgl_barang_masuk';

        $table = 'tbl_barang_masuk p
                    JOIN tbl_detail_barang_masuk dp ON(p.id_barang_masuk = dp.id_barang_masuk)
                    LEFT JOIN tbl_barang b ON(dp.id_barang = b.kode_barang)
                    LEFT JOIN tbl_supplier s ON(p.id_supplier = s.id_supplier)
                    LEFT JOIN tbl_satuan_barang ON(b.id_satuan = tbl_satuan_barang.id_satuan)';

        $where = ['p.tgl_barang_masuk >=' => $tgl1, 'p.tgl_barang_masuk <=' => $tgl2];

        $this->db->select($select);
        $this->db->from($table);
        $this->db->where($where);
        $this->db->order_by('tgl_barang_masuk', 'ASC');

        return $this->db->get();
    }

    function getDataBarangKeluarHarian($tanggal)
    {
        $select = 'p.id_barang_keluar AS id_barang_keluar, nama_barang, nama_satuan, dp.harga AS harga, qty, nama_pembeli, (SELECT COUNT(*) FROM tbl_detail_barang_keluar WHERE id_barang_keluar = p.id_barang_keluar) AS row';

        $table = 'tbl_barang_keluar p
                    JOIN tbl_detail_barang_keluar dp ON(p.id_barang_keluar = dp.id_barang_keluar)
                    LEFT JOIN tbl_barang b ON(dp.id_barang = b.kode_barang)
                    LEFT JOIN tbl_satuan_barang ON(b.id_satuan = tbl_satuan_barang.id_satuan)';

        $where = ['p.tgl_barang_keluar' => $tanggal];

        $this->db->select($select);
        $this->db->from($table);
        $this->db->where($where);

        return $this->db->get();
    }

    function getDataBarangKeluarBulanan($bulan, $tahun)
    {
        $tgl1 = $tahun . '-' . $bulan . '-01';
        $tgl2 = $tahun . '-' . $bulan . '-31';

        $select = 'p.id_barang_keluar AS id_barang_keluar, nama_barang, nama_satuan, dp.harga AS harga, qty, nama_pembeli, (SELECT COUNT(*) FROM tbl_detail_barang_keluar WHERE id_barang_keluar = p.id_barang_keluar) AS row_barang_keluar, (SELECT COUNT(*) FROM tbl_barang_keluar JOIN tbl_detail_barang_keluar dp ON(tbl_barang_keluar.id_barang_keluar = dp.id_barang_keluar) WHERE tgl_barang_keluar = p.tgl_barang_keluar) AS row_tanggal, tgl_barang_keluar';

        $table = 'tbl_barang_keluar p
                    JOIN tbl_detail_barang_keluar dp ON(p.id_barang_keluar = dp.id_barang_keluar)
                    LEFT JOIN tbl_barang b ON(dp.id_barang = b.kode_barang)
                    LEFT JOIN tbl_satuan_barang ON(b.id_satuan = tbl_satuan_barang.id_satuan)';

        $where = ['p.tgl_barang_keluar >=' => $tgl1, 'p.tgl_barang_keluar <=' => $tgl2];

        $this->db->select($select);
        $this->db->from($table);
        $this->db->where($where);
        $this->db->order_by('tgl_barang_keluar', 'ASC');

        return $this->db->get();
    }
}
