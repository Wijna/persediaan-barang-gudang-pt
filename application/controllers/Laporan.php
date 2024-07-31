<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Laporan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        //load library
        $this->load->library(['template', 'form_validation']);
        //load model
        $this->load->model('m_laporan');

        header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
    }

    public function index()
    {
        redirect('dashboard');
    }

    public function data_stok_harian()
    {
        //cek login
        $this->is_login();

        if ($this->input->post('cari', TRUE) == 'Search') {
            //validasi input data tanggal
            $this->form_validation->set_rules(
                'tanggal',
                'Tanggal',
                'required|callback_checkDateFormat',
                array(
                    'required' => '{field} wajib diisi',
                    'checkDateFormat' => '{field} tidak valid'
                )
            );

            if ($this->form_validation->run() == TRUE) {
                $tanggal = $this->security->xss_clean($this->input->post('tanggal', TRUE));
            } else {
                $this->session->set_flashdata('alert', validation_errors('<p class="my-0">', '</p>'));

                redirect('stok_harian');
            }
        } else {
            $tanggal = date('d/m/Y');
        }

        $getData = $this->m_laporan->getDataStokHarian(date('Y-m-d', strtotime(str_replace('/', '-', $tanggal))));

        $data = [
            'title' => 'Laporan Harian Stok Barang',
            'tanggal' => $tanggal,
            'data' => $getData
        ];

        $this->template->kasir('laporan/stok_harian', $data);
    }

    public function cetak_stok_harian($date)
    {
        $this->is_login();

        if ($this->cekTanggal($date) == false) {
            redirect('stok_harian');
        }

        $getData = $this->m_laporan->getDataStokHarian($date);

        $data = [
            'title' => 'Laporan Harian Stok Barang',
            'tanggal' => $date,
            'data' => $getData
        ];

        $this->template->cetak('cetak/stok_harian', $data);
    }

    public function export_excel_stok_harian($date)
    {
        // Ensure the user is logged in
        $this->is_login();

        // Validate the date format
        if ($this->cekTanggal($date) == false) {
            redirect('stok_harian');
        }


        // Get the daily stock data
        $getData = $this->m_laporan->getDataStokHarian($date);

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        // Set document properties
        $spreadsheet->getProperties()->setCreator('Your Name')
            ->setLastModifiedBy('Your Name')
            ->setTitle('Laporan Harian Stok Barang')
            ->setSubject('Laporan Harian Stok Barang')
            ->setDescription('Laporan Harian Stok Barang');

        // Set header row
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Barang');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Stok Barang');
        $sheet->setCellValue('E1', 'Satuan');
        $sheet->setCellValue('F1', 'Qty Barang Masuk');
        $sheet->setCellValue('G1', 'Qty Barang Keluar');
        // Add more headers as needed

        // Populate the data
        $rowNumber = 2; // Start from the second row
        $no = 1;
        foreach ($getData->result() as $data) {
            $sheet->setCellValue('A' . $rowNumber, $no);
            $sheet->setCellValue('B' . $rowNumber, $data->kode_barang);
            $sheet->setCellValue('C' . $rowNumber, $data->nama_barang);
            $sheet->setCellValue('D' . $rowNumber, ($data->stok + $data->qty_barang_keluar_new) - $data->qty_barang_masuk_new);
            $sheet->setCellValue('E' . $rowNumber, $data->nama_satuan);
            $sheet->setCellValue('F' . $rowNumber, $data->qty_barang_masuk > 0 ? $data->qty_barang_masuk : '0');
            $sheet->setCellValue('G' . $rowNumber, $data->qty_barang_keluar > 0 ? $data->qty_barang_keluar : '0');
            // Add more cells as needed

            $rowNumber++;
            $no++;
        }

        // Clean the output buffer
        ob_end_clean();

        // Set the filename and output the spreadsheet
        $filename = 'Laporan_Stok_Harian_' . $date . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Send the file to the browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function data_stok_bulanan()
    {
        //cek login
        $this->is_login();

        if ($this->input->post('cari', TRUE) == 'Search') {
            //validasi input data tanggal
            $this->form_validation->set_rules(
                'bulan',
                'Bulan',
                'required|callback_checkBulan',
                array(
                    'required' => '{field} wajib diisi',
                    'checkBulan' => '{field} tidak valid'
                )
            );

            $this->form_validation->set_rules(
                'tahun',
                'Tahun',
                'required|numeric|min_length[4]|max_length[4]|greater_than[2019]',
                array(
                    'required' => '{field} wajib diisi',
                    'numeric' => '{field} tidak valid',
                    'min_length' => '{field} minimal 4 karakter',
                    'max_length' => '{field} maximal 4 karakter',
                    'greater_than' => '{field} harus lebih dari 2019'
                )
            );

            if ($this->form_validation->run() == TRUE) {
                $bulan = $this->security->xss_clean($this->input->post('bulan', TRUE));
                $tahun = $this->security->xss_clean($this->input->post('tahun', TRUE));
            } else {
                $this->session->set_flashdata('alert', validation_errors('<p class="my-0">', '</p>'));

                redirect('stok_bulanan');
            }
        } else {
            $bulan = $this->convert_bulan_indo(date('m'));
            $tahun = date('Y');
        }

        $getData = $this->m_laporan->getDataStokBulanan($this->convert_bulan($bulan), $tahun);

        $data = [
            'title' => 'Laporan Bulanan Stok Barang',
            'bulan' => $bulan,
            'tahun' => $tahun,
            'data' => $getData
        ];

        $this->template->kasir('laporan/stok_bulanan', $data);
    }

    public function cetak_stok_bulanan($date)
    {
        $this->is_login();
        //explode url
        $exp = explode('-', $date);
        //cek jumlah array
        if (count($exp) != 2) {
            redirect('stok_bulanan');
        }
        //cek nama bulan, apakah valid atau tidak
        if ($this->checkBulan($exp[0]) == false) {
            redirect('stok_bulanan');
        }

        $getData = $this->m_laporan->getDataStokBulanan($this->convert_bulan($exp[0]), $exp[1]);

        $data = [
            'title' => 'Laporan Bulanan Stok Barang',
            'bulan' => $exp[0],
            'tahun' => $exp[1],
            'data' => $getData
        ];

        $this->template->cetak('cetak/stok_bulanan', $data);
    }

    public function export_excel_stok_bulanan($date)
    {
        $this->is_login();
        //explode url
        $exp = explode('-', $date);
        //cek jumlah array
        if (count($exp) != 2) {
            redirect('stok_bulanan');
        }
        //cek nama bulan, apakah valid atau tidak
        if ($this->checkBulan($exp[0]) == false) {
            redirect('stok_bulanan');
        }

        $bulan = $this->convert_bulan($exp[0]);
        $tahun = $exp[1];
        $getData = $this->m_laporan->getDataStokBulanan($bulan, $tahun);

        // Load PHPSpreadsheet library
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()->setCreator('Your Company Name')
            ->setLastModifiedBy('Your Company Name')
            ->setTitle('Laporan Bulanan Stok Barang')
            ->setSubject('Laporan Bulanan Stok Barang')
            ->setDescription('Laporan Bulanan Stok Barang')
            ->setCategory('Laporan');

        // Add some data
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Barang');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Stok Barang');
        $sheet->setCellValue('E1', 'Satuan');
        $sheet->setCellValue('F1', 'Qty Barang Masuk');
        $sheet->setCellValue('G1', 'Qty Barang Keluar');

        // Add data from database
        $row = 2;
        $no = 1;
        foreach ($getData->result() as $data) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $data->kode_barang);
            $sheet->setCellValue('C' . $row, $data->nama_barang);
            $sheet->setCellValue('D' . $row, $data->stok > 0 ? $data->stok : '0');
            $sheet->setCellValue('E' . $row, $data->nama_satuan);
            $sheet->setCellValue('F' . $row, $data->qty_barang_masuk > 0 ? $data->qty_barang_masuk : '0');
            $sheet->setCellValue('G' . $row, $data->qty_barang_keluar > 0 ? $data->qty_barang_keluar : '0');
            $row++;
            $no++;
        }

        // Rename worksheet
        $sheet->setTitle('Laporan Stok Bulanan');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Laporan_Stok_Bulanan.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function data_stok_tahunan()
    {
        //cek login
        $this->is_login();

        if ($this->input->post('cari', TRUE) == 'Search') {

            $this->form_validation->set_rules(
                'tahun',
                'Tahun',
                'required|numeric|min_length[4]|max_length[4]|greater_than[2019]',
                array(
                    'required' => '{field} wajib diisi',
                    'numeric' => '{field} tidak valid',
                    'min_length' => '{field} minimal 4 karakter',
                    'max_length' => '{field} maximal 4 karakter',
                    'greater_than' => '{field} harus lebih dari 2019'
                )
            );

            if ($this->form_validation->run() == TRUE) {
                $tahun = $this->security->xss_clean($this->input->post('tahun', TRUE));
            } else {
                $this->session->set_flashdata('alert', validation_errors('<p class="my-0">', '</p>'));

                redirect('stok_tahunan');
            }
        } else {
            $tahun = date('Y');
        }

        $getData = $this->m_laporan->getDataStokTahunan($tahun);

        $data = [
            'title' => 'Laporan Tahunan Stok Barang',
            'tahun' => $tahun,
            'data' => $getData
        ];

        $this->template->kasir('laporan/stok_tahunan', $data);
    }

    public function cetak_stok_tahunan($tahun)
    {
        $this->is_login();

        if ($tahun < 2020) {
            redirect('stok_tahunan');
        }

        $getData = $this->m_laporan->getDataStokTahunan($tahun);

        $data = [
            'title' => 'Laporan Tahunan Stok Barang',
            'tahun' => $tahun,
            'data' => $getData
        ];

        $this->template->cetak('cetak/stok_tahunan', $data);
    }

    public function export_excel_stok_tahunan($tahun)
    {
        $this->is_login();
        //cek jumlah array
        // if (count($exp) != 2) {
        //     redirect('stok_tahunan');
        // }
        //cek nama tahun, apakah valid atau tidak
        if ($this->checkTahun($tahun) == false) {
            redirect('stok_tahunan');
        }

        // $tahun = $exp[1];
        $getData = $this->m_laporan->getDataStokTahunan($tahun);

        // Load PHPSpreadsheet library
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()->setCreator('Your Company Name')
            ->setLastModifiedBy('Your Company Name')
            ->setTitle('Laporan Tahunan Stok Barang')
            ->setSubject('Laporan Tahunan Stok Barang')
            ->setDescription('Laporan Tahunan Stok Barang')
            ->setCategory('Laporan');

        // Add some data
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Barang');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Stok Barang');
        $sheet->setCellValue('E1', 'Satuan');
        $sheet->setCellValue('F1', 'Qty Barang Masuk');
        $sheet->setCellValue('G1', 'Qty Barang Keluar');

        // Add data from database
        $row = 2;
        $no = 1;
        foreach ($getData->result() as $data) {
            $barang_keluar = ($data->qty_barang_keluar_new != '') ? $data->qty_barang_keluar_new : 0;
            $barang_masuk = ($data->qty_barang_masuk_new != '') ? $data->qty_barang_masuk_new : 0;

            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $data->kode_barang);
            $sheet->setCellValue('C' . $row, $data->nama_barang);
            $sheet->setCellValue('D' . $row, ($data->stok + $barang_keluar) - $barang_masuk > 0 ? ($data->stok + $barang_keluar) - $barang_masuk : '0');
            $sheet->setCellValue('E' . $row, $data->nama_satuan);
            $sheet->setCellValue('G' . $row, $data->qty_barang_masuk > 0 ? $data->qty_barang_masuk : '0');
            $sheet->setCellValue('F' . $row, $data->qty_barang_keluar > 0 ? $data->qty_barang_keluar : '0');
            $row++;
            $no++;
        }
        ob_end_clean();


        // Rename worksheet
        $sheet->setTitle('Laporan Stok Tahunan');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Laporan_Stok_Tahunan.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }


    public function data_barang_masuk_harian()
    {
        //cek login
        $this->is_login();

        if ($this->input->post('cari', TRUE) == 'Search') {
            //validasi input data tanggal
            $this->form_validation->set_rules(
                'tanggal',
                'Tanggal',
                'required|callback_checkDateFormat',
                array(
                    'required' => '{field} wajib diisi',
                    'checkDateFormat' => '{field} tidak valid'
                )
            );

            if ($this->form_validation->run() == TRUE) {
                $tanggal = $this->security->xss_clean($this->input->post('tanggal', TRUE));
            } else {
                $this->session->set_flashdata('alert', validation_errors('<p class="my-0">', '</p>'));

                redirect('barang_masuk_harian');
            }
        } else {
            $tanggal = date('d/m/Y');
        }

        $getData = $this->m_laporan->getDataBarangMasukHarian(date('Y-m-d', strtotime(str_replace('/', '-', $tanggal))));

        $data = [
            'title' => 'Laporan Harian Barang Masuk',
            'tanggal' => $tanggal,
            'data' => $getData
        ];

        $this->template->kasir('laporan/barang_masuk_harian', $data);
    }

    public function cetak_barang_masuk_harian($date)
    {
        $this->is_login();

        if ($this->cekTanggal($date) == false) {
            redirect('barang_masuk_harian');
        }

        $getData = $this->m_laporan->getDataBarangMasukHarian($date);

        $data = [
            'title' => 'Laporan Harian Barang Masuk',
            'tanggal' => $date,
            'data' => $getData
        ];

        $this->template->cetak('cetak/barang_masuk_harian', $data);
    }

    public function export_excel_barang_masuk_harian($date)
    {
        // Ensure the user is logged in
        $this->is_login();

        // Validate the date format
        if ($this->cekTanggal($date) == false) {
            redirect('barang_masuk_harian');
        }

        // Get the daily incoming goods data
        $getData = $this->m_laporan->getDataBarangMasukHarian($date);

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()->setCreator('Your Name')
            ->setLastModifiedBy('Your Name')
            ->setTitle('Laporan Harian Barang Masuk')
            ->setSubject('Laporan Harian Barang Masuk')
            ->setDescription('Laporan Harian Barang Masuk');

        // Set header row
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ID Barang Masuk');
        $sheet->setCellValue('C1', 'Nama Supplier');
        $sheet->setCellValue('D1', 'Nama Barang');
        $sheet->setCellValue('E1', 'Qty');
        $sheet->setCellValue('F1', 'Satuan');
        $sheet->setCellValue('G1', 'Harga');
        $sheet->setCellValue('H1', 'Total');

        // Populate the data
        $rowNumber = 2; // Start from the second row
        $no = 1;
        $total_biaya = 0; // Initialize total biaya
        foreach ($getData->result() as $data) {
            $total = $data->harga * $data->qty;
            $total_biaya += $total;

            $sheet->setCellValue('A' . $rowNumber, $no);
            $sheet->setCellValue('B' . $rowNumber, $data->id_barang_masuk);
            $sheet->setCellValue('C' . $rowNumber, $data->nama_supplier);
            $sheet->setCellValue('D' . $rowNumber, $data->nama_barang);
            $sheet->setCellValue('E' . $rowNumber, $data->qty);
            $sheet->setCellValue('F' . $rowNumber, $data->nama_satuan);

            // Format the Harga and Total columns as currency
            $sheet->setCellValue('G' . $rowNumber, $data->harga);
            $sheet->getStyle('G' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0.00');

            $sheet->setCellValue('H' . $rowNumber, $total);
            $sheet->getStyle('H' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0.00');

            $rowNumber++;
            $no++;
        }

        // Add total biaya row
        $sheet->setCellValue('G' . $rowNumber, 'Total Biaya');
        $sheet->setCellValue('H' . $rowNumber, $total_biaya);
        $sheet->getStyle('H' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0.00');

        // Apply bold to the total biaya row
        $sheet->getStyle('G' . $rowNumber . ':H' . $rowNumber)->getFont()->setBold(true);

        // Auto-size columns for better readability
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Clean the output buffer
        ob_end_clean();

        // Set the filename and output the spreadsheet
        $filename = 'Laporan_Barang_Masuk_Harian_' . $date . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Send the file to the browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }



    public function data_barang_masuk_bulanan()
    {
        //cek login
        $this->is_login();

        if ($this->input->post('cari', TRUE) == 'Search') {
            //validasi input data tanggal
            $this->form_validation->set_rules(
                'bulan',
                'Bulan',
                'required|callback_checkBulan',
                array(
                    'required' => '{field} wajib diisi',
                    'checkBulan' => '{field} tidak valid'
                )
            );

            $this->form_validation->set_rules(
                'tahun',
                'Tahun',
                'required|numeric|min_length[4]|max_length[4]|greater_than[2019]',
                array(
                    'required' => '{field} wajib diisi',
                    'numeric' => '{field} tidak valid',
                    'min_length' => '{field} minimal 4 karakter',
                    'max_length' => '{field} maximal 4 karakter',
                    'greater_than' => '{field} harus lebih dari 2019'
                )
            );

            if ($this->form_validation->run() == TRUE) {
                $bulan = $this->security->xss_clean($this->input->post('bulan', TRUE));
                $tahun = $this->security->xss_clean($this->input->post('tahun', TRUE));
            } else {
                $this->session->set_flashdata('alert', validation_errors('<p class="my-0">', '</p>'));

                redirect('barang_masuk_bulanan');
            }
        } else {
            $bulan = $this->convert_bulan_indo(date('m'));
            $tahun = date('Y');
        }

        $getData = $this->m_laporan->getDataBarangMasukBulanan($this->convert_bulan($bulan), $tahun);

        $data = [
            'title' => 'Laporan Bulanan Barang Masuk',
            'bulan' => $bulan,
            'tahun' => $tahun,
            'data' => $getData
        ];

        $this->template->kasir('laporan/barang_masuk_bulanan', $data);
    }

    public function cetak_barang_masuk_bulanan($date)
    {
        $this->is_login();
        //explode url
        $exp = explode('-', $date);
        //cek jumlah array
        if (count($exp) != 2) {
            redirect('stok_bulanan');
        }
        //cek nama bulan, apakah valid atau tidak
        if ($this->checkBulan($exp[0]) == false) {
            redirect('stok_bulanan');
        }

        $getData = $this->m_laporan->getDataBarangMasukBulanan($this->convert_bulan($exp[0]), $exp[1]);

        $data = [
            'title' => 'Laporan Bulanan Barang Masuk',
            'bulan' => $exp[0],
            'tahun' => $exp[1],
            'data' => $getData
        ];

        $this->template->cetak('cetak/barang_masuk_bulanan', $data);
    }

    public function export_excel_barang_masuk_bulanan($date)
    {
        $this->is_login();

        // Explode the date parameter
        $exp = explode('-', $date);

        // Check the number of array elements
        if (count($exp) != 2) {
            redirect('barang_masuk_bulanan');
        }

        // Check if the month name is valid
        if ($this->checkBulan($exp[0]) == false) {
            redirect('barang_masuk_bulanan');
        }

        $bulan = $this->convert_bulan($exp[0]);
        $tahun = $exp[1];
        $getData = $this->m_laporan->getDataBarangMasukBulanan($bulan, $tahun);

        // Load PHPSpreadsheet library
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()->setCreator('Your Company Name')
            ->setLastModifiedBy('Your Company Name')
            ->setTitle('Laporan Bulanan Barang Masuk')
            ->setSubject('Laporan Bulanan Barang Masuk')
            ->setDescription('Laporan Bulanan Barang Masuk')
            ->setCategory('Laporan');

        // Add header data
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'ID Barang Masuk');
        $sheet->setCellValue('D1', 'Nama Supplier');
        $sheet->setCellValue('E1', 'Nama Barang');
        $sheet->setCellValue('F1', 'Qty');
        $sheet->setCellValue('G1', 'Satuan');
        $sheet->setCellValue('H1', 'Harga');
        $sheet->setCellValue('I1', 'Total');

        // Add data from database
        $rowNumber = 2;
        $no = 1;
        $total_biaya = 0; // Initialize total biaya
        foreach ($getData->result() as $data) {
            $total = $data->harga * $data->qty;
            $total_biaya += $total;

            $sheet->setCellValue('A' . $rowNumber, $no);
            $sheet->setCellValue('B' . $rowNumber, $this->tanggal_indo($data->tgl_barang_masuk));
            $sheet->setCellValue('C' . $rowNumber, $data->id_barang_masuk);
            $sheet->setCellValue('D' . $rowNumber, $data->nama_supplier);
            $sheet->setCellValue('E' . $rowNumber, $data->nama_barang);
            $sheet->setCellValue('F' . $rowNumber, $data->qty);
            $sheet->setCellValue('G' . $rowNumber, $data->nama_satuan);

            // Format the Harga and Total columns as currency
            $sheet->setCellValue('H' . $rowNumber, $data->harga);
            $sheet->getStyle('H' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0.00');

            $sheet->setCellValue('I' . $rowNumber, $total);
            $sheet->getStyle('I' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0.00');

            $rowNumber++;
            $no++;
        }

        // Add total biaya row
        $sheet->setCellValue('H' . $rowNumber, 'Total Biaya');
        $sheet->setCellValue('I' . $rowNumber, $total_biaya);
        $sheet->getStyle('I' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0.00');

        // Apply bold to the total biaya row
        $sheet->getStyle('H' . $rowNumber . ':I' . $rowNumber)->getFont()->setBold(true);

        // Auto-size columns for better readability
        foreach (range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Clean the output buffer
        ob_end_clean();

        // Set the filename and output the spreadsheet
        $filename = 'Laporan_Barang_Masuk_Bulanan_' . $date . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Send the file to the browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function data_barang_keluar_harian()
    {
        //cek login
        $this->is_login();

        if ($this->input->post('cari', TRUE) == 'Search') {
            //validasi input data tanggal
            $this->form_validation->set_rules(
                'tanggal',
                'Tanggal',
                'required|callback_checkDateFormat',
                array(
                    'required' => '{field} wajib diisi',
                    'checkDateFormat' => '{field} tidak valid'
                )
            );

            if ($this->form_validation->run() == TRUE) {
                $tanggal = $this->security->xss_clean($this->input->post('tanggal', TRUE));
            } else {
                $this->session->set_flashdata('alert', validation_errors('<p class="my-0">', '</p>'));

                redirect('barang_keluar_harian');
            }
        } else {
            $tanggal = date('d/m/Y');
        }

        $getData = $this->m_laporan->getDataBarangKeluarHarian(date('Y-m-d', strtotime(str_replace('/', '-', $tanggal))));

        $data = [
            'title' => 'Laporan Harian Barang Keluar',
            'tanggal' => $tanggal,
            'data' => $getData
        ];

        $this->template->kasir('laporan/barang_keluar_harian', $data);
    }

    public function cetak_barang_keluar_harian($date)
    {
        $this->is_login();

        if ($this->cekTanggal($date) == false) {
            redirect('barang_masuk_harian');
        }

        $getData = $this->m_laporan->getDataBarangKeluarHarian($date);

        $data = [
            'title' => 'Laporan Harian Barang Keluar',
            'tanggal' => $date,
            'data' => $getData
        ];

        $this->template->cetak('cetak/barang_keluar_harian', $data);
    }

    public function export_excel_barang_keluar_harian($date)
    {
        // Ensure the user is logged in
        $this->is_login();

        // Validate the date format
        if ($this->cekTanggal($date) == false) {
            redirect('barang_keluar_harian');
        }

        // Get the daily outgoing goods data
        $getData = $this->m_laporan->getDataBarangKeluarHarian($date);

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()->setCreator('Your Name')
            ->setLastModifiedBy('Your Name')
            ->setTitle('Laporan Harian Barang Keluar')
            ->setSubject('Laporan Harian Barang Keluar')
            ->setDescription('Laporan Harian Barang Keluar');

        // Set header row
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ID Barang Keluar');
        $sheet->setCellValue('C1', 'Nama Pembeli');
        $sheet->setCellValue('D1', 'Nama Barang');
        $sheet->setCellValue('E1', 'Qty');
        $sheet->setCellValue('F1', 'Satuan');
        $sheet->setCellValue('G1', 'Harga');
        $sheet->setCellValue('H1', 'Total');

        // Populate the data
        $rowNumber = 2; // Start from the second row
        $no = 1;
        $total_biaya = 0; // Initialize total biaya
        foreach ($getData->result() as $data) {
            $total = $data->harga * $data->qty;
            $total_biaya += $total;

            $sheet->setCellValue('A' . $rowNumber, $no);
            $sheet->setCellValue('B' . $rowNumber, $data->id_barang_keluar);
            $sheet->setCellValue('C' . $rowNumber, $data->nama_pembeli);
            $sheet->setCellValue('D' . $rowNumber, $data->nama_barang);
            $sheet->setCellValue('E' . $rowNumber, $data->qty);
            $sheet->setCellValue('F' . $rowNumber, $data->nama_satuan);

            // Format the Harga and Total columns as currency
            $sheet->setCellValue('G' . $rowNumber, $data->harga);
            $sheet->getStyle('G' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0.00');

            $sheet->setCellValue('H' . $rowNumber, $total);
            $sheet->getStyle('H' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0.00');

            $rowNumber++;
            $no++;
        }

        // Add total biaya row
        $sheet->setCellValue('G' . $rowNumber, 'Total Biaya');
        $sheet->setCellValue('H' . $rowNumber, $total_biaya);
        $sheet->getStyle('H' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0.00');

        // Apply bold to the total biaya row
        $sheet->getStyle('G' . $rowNumber . ':H' . $rowNumber)->getFont()->setBold(true);

        // Auto-size columns for better readability
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Clean the output buffer
        ob_end_clean();

        // Set the filename and output the spreadsheet
        $filename = 'Laporan_Barang_Keluar_Harian_' . $date . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Send the file to the browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function data_barang_keluar_bulanan()
    {
        //cek login
        $this->is_login();

        if ($this->input->post('cari', TRUE) == 'Search') {
            //validasi input data tanggal
            $this->form_validation->set_rules(
                'bulan',
                'Bulan',
                'required|callback_checkBulan',
                array(
                    'required' => '{field} wajib diisi',
                    'checkBulan' => '{field} tidak valid'
                )
            );

            $this->form_validation->set_rules(
                'tahun',
                'Tahun',
                'required|numeric|min_length[4]|max_length[4]|greater_than[2019]',
                array(
                    'required' => '{field} wajib diisi',
                    'numeric' => '{field} tidak valid',
                    'min_length' => '{field} minimal 4 karakter',
                    'max_length' => '{field} maximal 4 karakter',
                    'greater_than' => '{field} harus lebih dari 2019'
                )
            );

            if ($this->form_validation->run() == TRUE) {
                $bulan = $this->security->xss_clean($this->input->post('bulan', TRUE));
                $tahun = $this->security->xss_clean($this->input->post('tahun', TRUE));
            } else {
                $this->session->set_flashdata('alert', validation_errors('<p class="my-0">', '</p>'));

                redirect('barang_keluar_bulanan');
            }
        } else {
            $bulan = $this->convert_bulan_indo(date('m'));
            $tahun = date('Y');
        }

        $getData = $this->m_laporan->getDataBarangKeluarBulanan($this->convert_bulan($bulan), $tahun);

        $data = [
            'title' => 'Laporan Bulanan Barang Keluar',
            'bulan' => $bulan,
            'tahun' => $tahun,
            'data' => $getData
        ];

        $this->template->kasir('laporan/barang_keluar_bulanan', $data);
    }

    public function cetak_barang_keluar_bulanan($date)
    {
        $this->is_login();
        //explode url
        $exp = explode('-', $date);
        //cek jumlah array
        if (count($exp) != 2) {
            redirect('stok_bulanan');
        }
        //cek nama bulan, apakah valid atau tidak
        if ($this->checkBulan($exp[0]) == false) {
            redirect('stok_bulanan');
        }

        $getData = $this->m_laporan->getDataBarangKeluarBulanan($this->convert_bulan($exp[0]), $exp[1]);

        $data = [
            'title' => 'Laporan Bulanan Barang Keluar',
            'bulan' => $exp[0],
            'tahun' => $exp[1],
            'data' => $getData
        ];

        $this->template->cetak('cetak/barang_keluar_bulanan', $data);
    }

    public function export_excel_barang_keluar_bulanan($date)
    {
        // Ensure the user is logged in
        $this->is_login();

        // Explode the date parameter
        $exp = explode('-', $date);

        // Check the number of array elements
        if (count($exp) != 2) {
            redirect('barang_keluar_bulanan');
        }

        // Check if the month name is valid
        if ($this->checkBulan($exp[0]) == false) {
            redirect('barang_keluar_bulanan');
        }

        $bulan = $this->convert_bulan($exp[0]);
        $tahun = $exp[1];
        $getData = $this->m_laporan->getDataBarangKeluarBulanan($bulan, $tahun);

        // Load PHPSpreadsheet library
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()->setCreator('Your Company Name')
            ->setLastModifiedBy('Your Company Name')
            ->setTitle('Laporan Bulanan Barang Keluar')
            ->setSubject('Laporan Bulanan Barang Keluar')
            ->setDescription('Laporan Bulanan Barang Keluar')
            ->setCategory('Laporan');

        // Add header data
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'ID Barang Keluar');
        $sheet->setCellValue('D1', 'Nama Pembeli');
        $sheet->setCellValue('E1', 'Nama Barang');
        $sheet->setCellValue('F1', 'Qty');
        $sheet->setCellValue('G1', 'Satuan');
        $sheet->setCellValue('H1', 'Harga');
        $sheet->setCellValue('I1', 'Total');

        // Add data from database
        $rowNumber = 2;
        $no = 1;
        $total_biaya = 0; // Initialize total biaya
        foreach ($getData->result() as $data) {
            $total = $data->harga * $data->qty;
            $total_biaya += $total;

            $sheet->setCellValue('A' . $rowNumber, $no);
            $sheet->setCellValue('B' . $rowNumber, $this->tanggal_indo($data->tgl_barang_keluar));
            $sheet->setCellValue('C' . $rowNumber, $data->id_barang_keluar);
            $sheet->setCellValue('D' . $rowNumber, $data->nama_pembeli);
            $sheet->setCellValue('E' . $rowNumber, $data->nama_barang);
            $sheet->setCellValue('F' . $rowNumber, $data->qty);
            $sheet->setCellValue('G' . $rowNumber, $data->nama_satuan);

            // Format the Harga and Total columns as currency
            $sheet->setCellValue('H' . $rowNumber, $data->harga);
            $sheet->getStyle('H' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0.00');

            $sheet->setCellValue('I' . $rowNumber, $total);
            $sheet->getStyle('I' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0.00');

            $rowNumber++;
            $no++;
        }

        // Add total biaya row
        $sheet->setCellValue('H' . $rowNumber, 'Total Biaya');
        $sheet->setCellValue('I' . $rowNumber, $total_biaya);
        $sheet->getStyle('I' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0.00');

        // Apply bold to the total biaya row
        $sheet->getStyle('H' . $rowNumber . ':I' . $rowNumber)->getFont()->setBold(true);

        // Auto-size columns for better readability
        foreach (range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Clean the output buffer
        ob_end_clean();

        // Set the filename and output the spreadsheet
        $filename = 'Laporan_Barang_Keluar_Bulanan_' . $date . '.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // Send the file to the browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    function checkDateFormat($date)
    {
        if (preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/", $date)) {
            if (checkdate(substr($date, 3, 2), substr($date, 0, 2), substr($date, 6, 4))) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function checkBulan($bulan)
    {
        $array_bulan = array('januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember');

        if (in_array(strtolower($bulan), $array_bulan)) {
            return true;
        } else {
            return false;
        }
    }

    private function convert_bulan($bulan)
    {
        $bulan_array = [
            'januari' => '01',
            'februari' => '02',
            'maret' => '03',
            'april' => '04',
            'mei' => '05',
            'juni' => '06',
            'juli' => '07',
            'agustus' => '08',
            'september' => '09',
            'oktober' => '10',
            'november' => '11',
            'desember' => '12'
        ];

        return $bulan_array[strtolower($bulan)];
    }

    private function convert_bulan_indo($bulan)
    {
        $arr = [1 => 'januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];

        return $arr[(int)$bulan];
    }

    private function cekTanggal($date)
    {
        if (preg_match("/^[0-9]{4}\-(0[1-9]|1[0-2])\-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
            if (checkdate(substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4))) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function checkTahun($tahun)
    {
        // Misalnya, validasi tahun harus antara 2020 dan tahun sekarang
        $currentYear = date('YYYY');
        if ($tahun >= 2020 && $tahun <= $currentYear) {
            return true;
        } else {
            return false;
        }
    }

    private function tanggal_indo($tgl)
    {
        $bulan  = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $exp    = explode('-', date('d-m-Y', strtotime($tgl)));

        return $exp[0] . ' ' . $bulan[(int) $exp[1]] . ' ' . $exp[2];
    }

    private function is_login()
    {
        if (!$this->session->userdata('UserID')) {
            redirect('dashboard');
        }
    }
}
