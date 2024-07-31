<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lokasi_barang extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        //load library
        $this->load->library(['template', 'form_validation']);
        //load model
        $this->load->model('m_lokasi');

        header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
    }

    public function index()
    {
        //cek apakah user yang login adalah admin atau bukan
        //jika bukan maka alihkan ke dashboard
        $this->is_admin();

        $data = [
            'title' => 'Lokasi Barang'
        ];

        $this->template->kasir('lokasi_barang/index', $data);
    }

    public function tambah_data()
    {
        //cek apakah user yang login adalah admin atau bukan
        //jika bukan maka alihkan ke dashboard
        $this->is_admin();

        if ($this->input->post('submit', TRUE) == 'submit') {
            //set rules form validasi
            $this->form_validation->set_rules(
                'nama_lokasi',
                'nama Lokasi',
                'required|min_length[2]|is_unique[tbl_lokasi_barang.nama_lokasi]',
                array(
                    'required' => '{field} wajib diisi',
                    'min_length' => '{field} minimal 2 karakter',
                    'is_unique' => 'Nama Lokasi sudah terdaftar'
                )
            );

            //jika data sudah valid maka lakukan proses penyimpanan
            if ($this->form_validation->run() == TRUE) {
                //masukkan data ke variable array
                $simpan = array(
                    'nama_lokasi' => $this->security->xss_clean($this->input->post('nama_lokasi', TRUE))
                );

                //simpan ke database
                $save = $this->m_lokasi->save('tbl_lokasi_barang', $simpan);

                if ($save) {
                    $this->session->set_flashdata('success', 'Data Lokasi berhasil ditambah...');

                    redirect('lokasi_barang');
                }
            }
        }

        $data = [
            'title' => 'Tambah Lokasi Barang'
        ];

        $this->template->kasir('lokasi_barang/form_tambah', $data);
    }


    public function edit_data($id_lokasi = '')
    {

        //cek apakah user yang login adalah admin atau bukan
        //jika bukan maka alihkan ke dashboard
        $this->is_admin();

        //cek uri
        if ($id_lokasi == '') {
            $this->session->set_flashdata('error', 'Data tidak valid...');

            redirect('lokasi_barang');
        }

        //ambil data lokasi
        $lokasi = $this->m_lokasi->getData('tbl_lokasi_barang', ['id_lokasi' => $id_lokasi]);

        //validasi jumlah data
        if ($lokasi->num_rows() !== 1) {
            $this->session->set_flashdata('error', 'Data tidak valid...');

            redirect('lokasi_barang');
        }

        //ketika button diklik
        if ($this->input->post('update', TRUE) == 'Update') {
            //set rules form validasi
            $this->form_validation->set_rules(
                'nama_lokasi',
                'nama Lokasi',
                'required|min_length[2]|is_unique[tbl_lokasi_barang.nama_lokasi]',
                array(
                    'required' => '{field} wajib diisi',
                    'min_length' => '{field} minimal 2 karakter',
                    'is_unique' => 'Nama Lokasi sudah terdaftar'
                )
            );

            //jika validasi berhasil
            if ($this->form_validation->run() == TRUE) {
                //masukkan data ke variable array
                $update = array(
                    'nama_lokasi' => $this->security->xss_clean($this->input->post('nama_lokasi', TRUE)),
                    // 'active' => $this->security->xss_clean($this->input->post('status', TRUE))
                );

                //simpan ke database
                $up = $this->m_lokasi->update('tbl_lokasi_barang', $update, ['id_lokasi' => $id_lokasi]);

                if ($up) {
                    $this->session->set_flashdata('success', 'Data Lokasi berhasil diperbarui...');

                    redirect('lokasi_barang');
                }
            }
        }

        $data = [
            'title' => 'Edit Data Lokasi',
            'lokasi' => $lokasi->row()
        ];

        $this->template->kasir('lokasi_barang/form_edit', $data);
    }

    public function hapus_data()
    {
        //cek login
        $this->is_admin();
        //validasi request ajax
        if ($this->input->is_ajax_request()) {
            //validasi
            $this->form_validation->set_rules(
                'id',
                'Nama Lokasi',
                'required|min_length[2]',
                array(
                    'required' => '{field} wajib diisi',
                    'min_length' => '{field} minimal 2 karakter'
                )
            );

            if ($this->form_validation->run() == TRUE) {
                //tangkap rowid
                $id = $this->security->xss_clean($this->input->post('id', TRUE));

                $hapus = $this->m_lokasi->delete(['tbl_lokasi_barang'], ['id_lokasi' => $id]);

                if ($hapus) {
                    echo json_encode(['message' => 'success']);
                } else {
                    echo json_encode(['message' => 'failed']);
                }
            } else {
                echo json_encode(['message' => 'failed']);
            }
        } else {
            redirect('dashboard');
        }
    }

    public function ajax_lokasi()
    {
        $this->is_admin();
        //cek apakah request berupa ajax atau bukan, jika bukan maka redirect ke home
        if ($this->input->is_ajax_request()) {
            //ambil list data
            $list = $this->m_lokasi->get_datatables();
            //siapkan variabel array
            $data = array();
            $no = $_POST['start'];

            foreach ($list as $i) {

                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $i->nama_lokasi;
                // $row[] = ($i->active == 'Y') ? 'Aktif' : 'Tidak Aktif';
                $row[] = '<a href="' . site_url('edit_lokasi/' . $i->id_lokasi) . '" class="btn btn-warning btn-sm text-white">Edit</a>
                <button type="button" class="btn btn-danger btn-sm"onclick="hapus_lokasi(\'' . $i->id_lokasi . '\')">Hapus</button>';

                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->m_lokasi->count_all(),
                "recordsFiltered" => $this->m_lokasi->count_filtered(),
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            redirect('dashboard');
        }
    }

    private function is_admin()
    {
        if (!$this->session->userdata('level') || $this->session->userdata('level') != 'admin') {
            redirect('dashboard');
        }
    }
}
