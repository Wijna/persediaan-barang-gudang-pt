<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Satuan_barang extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        //load library
        $this->load->library(['template', 'form_validation']);
        //load model
        $this->load->model('m_satuan');

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
            'title' => 'Satuan Barang'
        ];

        $this->template->kasir('satuan_barang/index', $data);
    }

    public function tambah_data()
    {
        //cek apakah user yang login adalah admin atau bukan
        //jika bukan maka alihkan ke dashboard
        $this->is_admin();

        if ($this->input->post('submit', TRUE) == 'submit') {
            //set rules form validasi
            $this->form_validation->set_rules(
                'nama_satuan',
                'nama Satuan',
                'required|min_length[2]|is_unique[tbl_satuan_barang.nama_satuan]',
                array(
                    'required' => '{field} wajib diisi',
                    'min_length' => '{field} minimal 2 karakter',
                    'is_unique' => 'Nama Satuan sudah terdaftar'
                )
            );

            //jika data sudah valid maka lakukan proses penyimpanan
            if ($this->form_validation->run() == TRUE) {
                //masukkan data ke variable array
                $simpan = array(
                    'nama_satuan' => $this->security->xss_clean($this->input->post('nama_satuan', TRUE))
                );

                //simpan ke database
                $save = $this->m_satuan->save('tbl_satuan_barang', $simpan);

                if ($save) {
                    $this->session->set_flashdata('success', 'Data Satuan berhasil ditambah...');

                    redirect('satuan_barang');
                }
            }
        }

        $data = [
            'title' => 'Tambah Satuan Barang'
        ];

        $this->template->kasir('satuan_barang/form_tambah', $data);
    }


    public function edit_data($id_satuan = '')
    {

        //cek apakah user yang login adalah admin atau bukan
        //jika bukan maka alihkan ke dashboard
        $this->is_admin();

        //cek uri
        if ($id_satuan == '') {
            $this->session->set_flashdata('error', 'Data tidak valid...');

            redirect('satuan_barang');
        }

        //ambil data satuan
        $satuan = $this->m_satuan->getData('tbl_satuan_barang', ['id_satuan' => $id_satuan]);

        //validasi jumlah data
        if ($satuan->num_rows() !== 1) {
            $this->session->set_flashdata('error', 'Data tidak valid...');

            redirect('satuan_barang');
        }

        //ketika button diklik
        if ($this->input->post('update', TRUE) == 'Update') {
            //set rules form validasi
            $this->form_validation->set_rules(
                'nama_satuan',
                'nama Satuan',
                'required|min_length[2]|is_unique[tbl_satuan_barang.nama_satuan]',
                array(
                    'required' => '{field} wajib diisi',
                    'min_length' => '{field} minimal 2 karakter',
                    'is_unique' => 'Nama Satuan sudah terdaftar'
                )
            );

            //jika validasi berhasil
            if ($this->form_validation->run() == TRUE) {
                //masukkan data ke variable array
                $update = array(
                    'nama_satuan' => $this->security->xss_clean($this->input->post('nama_satuan', TRUE)),
                    // 'active' => $this->security->xss_clean($this->input->post('status', TRUE))
                );

                //simpan ke database
                $up = $this->m_satuan->update('tbl_satuan_barang', $update, ['id_satuan' => $id_satuan]);

                if ($up) {
                    $this->session->set_flashdata('success', 'Data Satuan berhasil diperbarui...');

                    redirect('satuan_barang');
                }
            }
        }

        $data = [
            'title' => 'Edit Data Satuan',
            'satuan' => $satuan->row()
        ];

        $this->template->kasir('satuan_barang/form_edit', $data);
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
                'Nama Satuan',
                'required|min_length[2]',
                array(
                    'required' => '{field} wajib diisi',
                    'min_length' => '{field} minimal 2 karakter'
                )
            );

            if ($this->form_validation->run() == TRUE) {
                //tangkap rowid
                $id = $this->security->xss_clean($this->input->post('id', TRUE));

                $hapus = $this->m_satuan->delete(['tbl_satuan_barang'], ['id_satuan' => $id]);

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

    public function ajax_satuan()
    {
        $this->is_admin();
        //cek apakah request berupa ajax atau bukan, jika bukan maka redirect ke home
        if ($this->input->is_ajax_request()) {
            //ambil list data
            $list = $this->m_satuan->get_datatables();
            //siapkan variabel array
            $data = array();
            $no = $_POST['start'];

            foreach ($list as $i) {

                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $i->nama_satuan;
                // $row[] = ($i->active == 'Y') ? 'Aktif' : 'Tidak Aktif';
                $row[] = '<a href="' . site_url('edit_satuan/' . $i->id_satuan) . '" class="btn btn-warning btn-sm text-white">Edit</a>
                <button type="button" class="btn btn-danger btn-sm"onclick="hapus_satuan(\'' . $i->id_satuan . '\')">Hapus</button>';

                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->m_satuan->count_all(),
                "recordsFiltered" => $this->m_satuan->count_filtered(),
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
