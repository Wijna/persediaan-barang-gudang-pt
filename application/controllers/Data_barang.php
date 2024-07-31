<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data_barang extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        //load library
        $this->load->library(['template', 'form_validation']);
        //load model
        $this->load->model('m_barang');
        $this->load->model('m_jenis');
        $this->load->model('m_satuan');
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
            'title' => 'Data Barang'
        ];

        $this->template->kasir('data_barang/index', $data);
    }

    public function tambah_data()
    {
        //cek apakah user yang login adalah admin atau bukan
        //jika bukan maka alihkan ke dashboard
        $this->is_admin();

        if ($this->input->post('submit', TRUE) == 'submit') {
            //cek apakah user sudah memilih barang atau belum, jika belum maka munculkan pesan kesalahan
            // if (!$this->cart->contents()) {
            //     $this->session->set_flashdata('alert', 'Anda belum memilih barang...');

            //     redirect('tambah_barang', 'refresh');
            // }
            // //set rules form validasi
            $this->form_validation->set_rules(
                'kode',
                'Kode Barang',
                'required|min_length[3]|max_length[6]|is_unique[tbl_barang.kode_barang]',
                array(
                    'required' => '{field} wajib diisi',
                    'min_length' => '{field} minimal 3 karakter',
                    'max_length' => '{field} maksimal 6 karakter',
                    'is_unique' => 'Kode sudah terdaftar'
                )
            );

            $this->form_validation->set_rules(
                'nama_barang',
                'Nama Barang',
                'required|min_length[3]|max_length[255]|is_unique[tbl_barang.nama_barang]',
                array(
                    'required' => '{field} wajib diisi',
                    'min_length' => '{field} minimal 3 karakter',
                    'max_length' => '{field} maksimal 255 karakter',
                    'is_unique' => 'Nama Barang sudah terdaftar'
                )
            );

            $this->form_validation->set_rules(
                'jenis',
                'Jenis',
                'required',
                array(
                    'required' => '{field} wajib dipilih',
                )
            );

            $this->form_validation->set_rules(
                'satuan',
                'Satuan',
                'required',
                array(
                    'required' => '{field} wajib dipilih',
                )
            );

            $this->form_validation->set_rules(
                'lokasi',
                'Lokasi',
                'required',
                array(
                    'required' => '{field} wajib dipilih',
                )
            );


            $this->form_validation->set_rules(
                'harga',
                'Harga Jual',
                "required|regex_match[/^[0-9.]+$/]",
                array(
                    'required' => '{field} wajib diisi',
                    'regex_match' => '{field} hanya boleh angka'
                )
            );

            //jika data sudah valid maka lakukan proses penyimpanan
            if ($this->form_validation->run() == TRUE) {
                //masukkan data ke variable array
                $simpan = array(
                    'kode_barang' => $this->security->xss_clean($this->input->post('kode', TRUE)),
                    'nama_barang' => $this->security->xss_clean($this->input->post('nama_barang', TRUE)),
                    'id_jenis' => $this->security->xss_clean($this->input->post('jenis', TRUE)),
                    'id_satuan' => $this->security->xss_clean($this->input->post('satuan', TRUE)),
                    'harga' => str_replace('.', '', $this->security->xss_clean($this->input->post('harga', TRUE))),
                    'id_lokasi' => $this->security->xss_clean($this->input->post('lokasi', TRUE))
                );


                //simpan ke database
                $save = $this->m_barang->save('tbl_barang', $simpan);

                if ($save) {
                    $this->session->set_flashdata('success', 'Data Barang berhasil ditambah...');

                    redirect('barang');
                }
            }
        }

        $data = [
            'title' => 'Tambah Data Barang',
            'jeniss' => $this->m_jenis->getAllData('tbl_jenis_barang'),
            'satuans' => $this->m_satuan->getAllData('tbl_satuan_barang'),
            'lokasis' => $this->m_lokasi->getAllData('tbl_lokasi_barang')
        ];

        $this->template->kasir('data_barang/form_tambah', $data);
    }

    public function edit_data($kode_barang = '')
    {

        //cek apakah user yang login adalah admin atau bukan
        //jika bukan maka alihkan ke dashboard
        $this->is_admin();

        //cek uri
        if ($kode_barang == '') {
            $this->session->set_flashdata('error', 'Data tidak valid...');

            redirect('barang');
        }

        //ambil data barang
        $barang = $this->m_barang->getData('tbl_barang', ['kode_barang' => $kode_barang]);

        //validasi jumlah data
        if ($barang->num_rows() !== 1) {
            $this->session->set_flashdata('error', 'Data tidak valid...');

            redirect('barang');
        }

        //ketika button diklik
        if ($this->input->post('update', TRUE) == 'Update') {
            //cek apakah user merubah kode barang atau tidak
            $b = $barang->row();
            if ($b->kode_barang == $this->security->xss_clean($this->input->post('ID', TRUE))) {
                $rules_kode_barang = 'required|min_length[3]|max_length[6]';
            } else {
                $rules_kode_barang = 'required|min_length[3]|max_length[6]|is_unique[tbl_barang.kode_barang]';
            }
            //set rules form validasi
            $this->form_validation->set_rules(
                'ID',
                'Kode Barang',
                $rules_kode_barang,
                array(
                    'required' => '{field} wajib diisi',
                    'min_length' => '{field} minimal 3 karakter',
                    'max_length' => '{field} maksimal 6 karakter',
                    'is_unique' => 'Kode sudah terdaftar'
                )
            );

            $this->form_validation->set_rules(
                'nama_barang',
                'Nama Barang',
                'required|min_length[3]|max_length[255]',
                array(
                    'required' => '{field} wajib diisi',
                    'min_length' => '{field} minimal 3 karakter',
                    'max_length' => '{field} maksimal 255 karakter'
                )
            );

            $this->form_validation->set_rules(
                'jenis',
                'Jenis',
                'required',
                array(
                    'required' => '{field} wajib dipilih',
                )
            );

            $this->form_validation->set_rules(
                'satuan',
                'Satuan',
                'required',
                array(
                    'required' => '{field} wajib dipilih',
                )
            );

            $this->form_validation->set_rules(
                'lokasi',
                'Lokasi',
                'required',
                array(
                    'required' => '{field} wajib dipilih',
                )
            );

            $this->form_validation->set_rules(
                'harga',
                'Harga Jual',
                "required|regex_match[/^[0-9.]+$/]",
                array(
                    'required' => '{field} wajib diisi',
                    'regex_match' => '{field} hanya boleh angka'
                )
            );

            $this->form_validation->set_rules(
                'status',
                'Status',
                "required|min_length[1]|max_length[1]|regex_match[/^[YN]+$/]",
                array(
                    'required' => '{field} wajib diisi',
                    'min_length' => '{field} hanya boleh 1 karakter',
                    'max_length' => '{field} hanya boleh 1 karakter',
                    'regex_match' => 'Input {field} tidak valid'
                )
            );

            //jika validasi berhasil
            if ($this->form_validation->run() == TRUE) {
                //masukkan data ke variable array
                $update = array(
                    'nama_barang' => $this->security->xss_clean($this->input->post('nama_barang', TRUE)),
                    'id_jenis' => $this->security->xss_clean($this->input->post('jenis', TRUE)),
                    'id_satuan' => $this->security->xss_clean($this->input->post('satuan', TRUE)),
                    'harga' => str_replace('.', '', $this->security->xss_clean($this->input->post('harga', TRUE))),
                    'id_lokasi' => $this->security->xss_clean($this->input->post('lokasi', TRUE)),
                    'active' => $this->security->xss_clean($this->input->post('status', TRUE))
                );

                //simpan ke database
                $up = $this->m_barang->update('tbl_barang', $update, ['kode_barang' => $this->security->xss_clean($this->input->post('ID', TRUE))]);

                if ($up) {
                    $this->session->set_flashdata('success', 'Data Barang berhasil diperbarui...');

                    redirect('barang');
                }
            }
        }

        $data = [
            'title' => 'Edit Data Barang',
            'barang' => $barang->row(),
            'jeniss' => $this->m_jenis->getAllData('tbl_jenis_barang'),
            'satuans' => $this->m_satuan->getAllData('tbl_satuan_barang'),
            'lokasis' => $this->m_lokasi->getAllData('tbl_lokasi_barang')
        ];

        $this->template->kasir('data_barang/form_edit', $data);
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
                'Kode Barang',
                // $rules_kode_barang,
                'required|min_length[3]|max_length[6]',
                array(
                    'required' => '{field} wajib diisi',
                    'min_length' => '{field} minimal 3 karakter',
                    'max_length' => '{field} maksimal 6 karakter',
                )
            );

            if ($this->form_validation->run() == TRUE) {
                //tangkap rowid
                $id = $this->security->xss_clean($this->input->post('id', TRUE));

                //             // Cek apakah id valid
                //             if (empty($id)) {
                //                 log_message('error', 'ID barang tidak valid.');
                //                 echo json_encode(['message' => 'failed']);
                //                 return;
                //             }

                $hapus = $this->m_barang->delete(['tbl_barang'], ['kode_barang' => $id]);

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

    public function stok()
    {
        //cek pegawai
        if (!$this->session->userdata('level') || $this->session->userdata('level') != 'pegawai') {
            redirect('dashboard');
        }

        $data = [
            'title' => 'Data Stok Barang'
        ];

        $this->template->kasir('data_barang/stok', $data);
    }

    public function ajax_barang()
    {
        $this->is_admin();
        //cek apakah request berupa ajax atau bukan, jika bukan maka redirect ke home
        if ($this->input->is_ajax_request()) {
            //ambil list data
            $list = $this->m_barang->get_datatables();
            //siapkan variabel array
            $data = array();
            $no = $_POST['start'];
            unset($list['jenis']);
            unset($list['satuan']);
            unset($list['lokasi']);

            foreach ($list as $i) {

                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $i->kode_barang;
                $row[] = $i->nama_barang;
                $row[] = $i->nama_jenis;
                $row[] = $i->stok;
                $row[] = $i->nama_satuan;
                $row[] = '<span class="float-left">Rp.</span><span class="float-right">' . number_format($i->harga, 0, ',', '.') . ',-</span>';
                $row[] = $i->nama_lokasi;
                $row[] = ($i->active == 'Y') ? 'Aktif' : 'Tidak Aktif';
                $row[] = '<a href="' . site_url('edit_barang/' . $i->kode_barang) . '" class="btn btn-warning btn-sm text-white">Edit</a>
                <button type="button" class="btn btn-danger btn-sm"onclick="hapus_barang(\'' . $i->kode_barang . '\')">Hapus</button>';

                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->m_barang->count_all(),
                "recordsFiltered" => $this->m_barang->count_filtered(),
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            redirect('dashboard');
        }
    }

    public function ajax_stok_barang()
    {
        //cek pegawai
        if (!$this->session->userdata('level') || $this->session->userdata('level') != 'pegawai') {
            redirect('dashboard');
        }
        //cek apakah request berupa ajax atau bukan, jika bukan maka redirect ke home
        if ($this->input->is_ajax_request()) {
            //ambil list data
            $list = $this->m_barang->get_datatables();
            //siapkan variabel array
            $data = array();
            $no = $_POST['start'];

            foreach ($list as $i) {

                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $i->kode_barang;
                $row[] = $i->nama_barang;
                $row[] = $i->nama_jenis;
                $row[] = $i->stok;
                $row[] = $i->nama_satuan;
                $row[] = '<span class="float-left">Rp.</span><span class="float-right">' . number_format($i->harga, 0, ',', '.') . ',-</span>';
                $row[] = $i->nama_lokasi;

                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->m_barang->count_all(),
                "recordsFiltered" => $this->m_barang->count_filtered(),
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
