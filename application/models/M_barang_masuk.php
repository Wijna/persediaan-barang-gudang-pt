<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_barang_masuk extends CI_Model
{

    var $select = array('p.id_barang_masuk AS id_barang_masuk', 'tgl_barang_masuk', 'count(id_barang) AS jumlah', 'SUM(qty * harga) AS total', 'p.id_user AS id_user', 'fullname', 'nama_supplier'); //data yang akan diambil

    var $table           = 'tbl_barang_masuk p
                            JOIN tbl_detail_barang_masuk dp ON(p.id_barang_masuk = dp.id_barang_masuk)
                            JOIN tbl_user u ON(p.id_user = u.id_user)
                            LEFT JOIN tbl_supplier s ON(p.id_supplier = s.id_supplier)';

    var $column_order    =  array(null, 'p.id_barang_masuk', 'tgl_barang_masuk', 'nama_supplier', 'jumlah', 'total', 'fullname', null); //set column field database untuk datatable order
    var $column_search   =  array('p.id_barang_masuk', 'tgl_barang_masuk', 'fullname', 'nama_supplier'); //set column field database untuk datatable search
    var $order = array('p.id_barang_masuk' => 'asc'); // default order

    function __construct()
    {
        parent::__construct();
    }

    function getAllData($table = null)
    {
        return $this->db->get($table);
    }

    function getData($table = null, $where = null)
    {
        $this->db->from($table);
        $this->db->where($where);
        
        return $this->db->get();
    }
    
    function getDataBarangMasuk($id)
    {
        $select = 'p.id_barang_masuk AS id_barang_masuk, tgl_barang_masuk, qty, dp.harga AS harga, kode_barang, nama_barang, e.nama_satuan, fullname, u.id_user AS id_user, nama_supplier, p.id_supplier AS id_supplier';
        
        $table = 'tbl_barang_masuk p
        LEFT JOIN tbl_detail_barang_masuk dp ON(p.id_barang_masuk = dp.id_barang_masuk)
        LEFT JOIN tbl_barang b ON(dp.id_barang = b.kode_barang)
        LEFT JOIN tbl_user u ON(p.id_user = u.id_user)
        LEFT JOIN tbl_supplier s ON(p.id_supplier = s.id_supplier)
        LEFT JOIN tbl_satuan_barang e ON(b.id_satuan = e.id_satuan)';
        
        $where = array('p.id_barang_masuk' => $id);
        
        $this->db->select($select);
        $this->db->from($table);
        $this->db->where($where);
        
        return $this->db->get();
    }
    
    function save($table = null, $data = null)
    {
        return $this->db->insert($table, $data);
    }
    
    function multiSave($table = null, $data = array())
    {
        $jumlah = count($data);
        
        if ($jumlah > 0) {
            $this->db->insert_batch($table, $data);
        }
    }
    
    function update($table = null, $data = null, $where = null)
    {
        return $this->db->update($table, $data, $where);
    }
    
    function delete($table = null, $where = null)
    {
        $this->db->where($where);
        $this->db->delete($table);
        
        return $this->db->affected_rows();
    }
    
    private function _get_datatables_query()
    {
        
        $this->db->select($this->select);
        $this->db->from($this->table);
        // $this->db->join('tbl_jenis_barang', 'tbl_barang.id_jenis = tbl_jenis_barang.id_jenis');
        $this->db->group_by(array('p.id_barang_masuk', 'tgl_barang_masuk', 'p.id_user', 'fullname'));
        
        $i = 0;
        
        foreach ($this->column_search as $item) // loop column
        {
            if ($_POST['search']['value']) // Jika datatable mengirim POST untuk search
            {
                
                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket.
                    
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                
                if (count($this->column_search) - 1 == $i) { //last loop
                    $this->db->group_end(); //close bracket
                }
            }
            $i++;
        }
        
        if (isset($_POST['order'])) // Proses order
        {

            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {

            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
            $query = $this->db->get();

            return $query->result();
        }
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();

        return $query->num_rows();
    }

    function count_all()
    {
        $this->db->select($this->select);
        $this->db->from($this->table);
        $this->db->group_by(array('p.id_barang_masuk', 'tgl_barang_masuk', 'p.id_user', 'fullname'));

        return $this->db->count_all_results();
    }
}
