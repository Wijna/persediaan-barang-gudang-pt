<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['dashboard'] = 'home/dashboard';
$route['sign_out'] = 'home/sign_out';
$route['login'] = 'home';
$route['admin'] = 'home/profil_admin';
$route['profil'] = 'home/profil_pegawai';
$route['password'] = 'home/ganti_password';

//route data barang
$route['barang'] = 'data_barang/index';
$route['tambah_barang'] = 'data_barang/tambah_data';
$route['hapus_barang'] = 'data_barang/hapus_data';
$route['edit_barang'] = 'data_barang/edit_data';
$route['edit_barang/(:any)'] = 'data_barang/edit_data/$1';
$route['stok_barang'] = 'data_barang/stok';
$route['ajax_barang'] = 'data_barang/ajax_barang';
$route['ajax_stok_barang'] = 'data_barang/ajax_stok_barang';

//route jenis barang
$route['jenis_barang'] = 'jenis_barang/index';
$route['tambah_jenis'] = 'jenis_barang/tambah_data';
$route['hapus_jenis'] = 'jenis_barang/hapus_data';
$route['edit_jenis'] = 'jenis_barang/edit_data';
$route['edit_jenis/(:any)'] = 'jenis_barang/edit_data/$1';
$route['ajax_jenis'] = 'jenis_barang/ajax_jenis';

//route satuan barang
$route['satuan_barang'] = 'satuan_barang/index';
$route['tambah_satuan'] = 'satuan_barang/tambah_data';
$route['hapus_satuan'] = 'satuan_barang/hapus_data';
$route['edit_satuan'] = 'satuan_barang/edit_data';
$route['edit_satuan/(:any)'] = 'satuan_barang/edit_data/$1';
$route['ajax_satuan'] = 'satuan_barang/ajax_satuan';

//route lokasi barang
$route['lokasi_barang'] = 'lokasi_barang/index';
$route['tambah_lokasi'] = 'lokasi_barang/tambah_data';
$route['hapus_lokasi'] = 'lokasi_barang/hapus_data';
$route['edit_lokasi'] = 'lokasi_barang/edit_data';
$route['edit_lokasi/(:any)'] = 'lokasi_barang/edit_data/$1';
$route['ajax_lokasi'] = 'lokasi_barang/ajax_lokasi';

//route data pegawai
$route['pegawai'] = 'data_pegawai/index';
$route['pegawai/(:any)'] = 'data_pegawai/detail_pegawai/$1';
$route['tambah_pegawai'] = 'data_pegawai/tambah_pegawai';
$route['edit_pegawai'] = 'data_pegawai/edit_data';
$route['edit_pegawai/(:any)'] = 'data_pegawai/edit_data/$1';
$route['ganti_password'] = 'data_pegawai/ganti_password';
$route['ajax_pegawai'] = 'data_pegawai/ajax_pegawai';
$route['hapus_pegawai'] = 'data_pegawai/hapus_data';

//route data supplier
$route['supplier'] = 'data_supplier/index';
$route['supplier/(:any)'] = 'data_supplier/edit_supplier/$1';
$route['ajax_supplier'] = 'data_supplier/ajax_supplier';
$route['tambah_supplier'] = 'data_supplier/tambah_supplier';
$route['hapus_supplier'] = 'data_supplier/hapus_data';

//route data barang masuk
$route['data_barang_masuk'] = 'barang_masuk/index';
$route['tambah_barang_masuk'] = 'barang_masuk/tambah_data';
$route['hapus_barang_masuk'] = 'barang_masuk/hapus_data';
$route['data_barang_masuk/(:any)'] = 'barang_masuk/detail_barang_masuk/$1';
$route['edit_barang_masuk'] = 'barang_masuk/edit_barang_masuk';
$route['edit_barang_masuk/(:any)'] = 'barang_masuk/edit_barang_masuk/$1';
$route['tambah_cart'] = 'barang_masuk/tambah_cart';
$route['get_item'] = 'barang_masuk/get_item';
$route['update_cart'] = 'barang_masuk/update_cart';
$route['remove_item'] = 'barang_masuk/remove_item';
$route['ajax_barang_masuk'] = 'barang_masuk/ajax_barang_masuk';

//route data barang keluar
$route['data_barang_keluar'] = 'barang_keluar/index';
$route['data_barang_keluar/(:any)'] = 'barang_keluar/detail_barang_keluar/$1';
$route['tambah_barang_keluar'] = 'barang_keluar/tambah_data';
$route['ajax_barang_keluar'] = 'barang_keluar/ajax_barang_keluar';
$route['cari_barang_barang_keluar'] = 'barang_keluar/cari_barang_barang_keluar';
$route['tambah_cart_barang_keluar'] = 'barang_keluar/tambah_cart_barang_keluar';
$route['hapus_item_barang_keluar'] = 'barang_keluar/hapus_cart_barang_keluar';
$route['get_item_barang_keluar'] = 'barang_keluar/get_item_barang_keluar';
$route['get_item_barang_keluar/(:any)'] = 'barang_keluar/get_item_barang_keluar/$1';
$route['update_cart_barang_keluar'] = 'barang_keluar/update_cart_barang_keluar';
$route['hapus_barang_keluar'] = 'barang_keluar/hapus_barang_keluar';
$route['edit_barang_keluar'] = 'barang_keluar/edit_barang_keluar';
$route['edit_barang_keluar/(:any)'] = 'barang_keluar/edit_barang_keluar/$1';

//route data laporan
$route['stok_harian'] = 'laporan/data_stok_harian';
$route['stok_harian/(:any)'] = 'laporan/cetak_stok_harian/$1';
$route['stok_harian/export_excel/(:any)'] = 'laporan/export_excel_stok_harian/$1';
$route['stok_bulanan'] = 'laporan/data_stok_bulanan';
$route['stok_bulanan/(:any)'] = 'laporan/cetak_stok_bulanan/$1';
$route['stok_bulanan/export_excel/(:any)'] = 'laporan/export_excel_stok_bulanan/$1';
$route['stok_tahunan'] = 'laporan/data_stok_tahunan';
$route['stok_tahunan/(:any)'] = 'laporan/cetak_stok_tahunan/$1';
$route['stok_tahunan/export_excel/(:any)'] = 'laporan/export_excel_stok_tahunan/$1';
$route['barang_masuk_harian'] = 'laporan/data_barang_masuk_harian';
$route['barang_masuk_harian/(:any)'] = 'laporan/cetak_barang_masuk_harian/$1';
$route['barang_masuk_harian/export_excel/(:any)'] = 'laporan/export_excel_barang_masuk_harian/$1';
$route['barang_masuk_bulanan'] = 'laporan/data_barang_masuk_bulanan';
$route['barang_masuk_bulanan/(:any)'] = 'laporan/cetak_barang_masuk_bulanan/$1';
$route['barang_masuk_bulanan/export_excel/(:any)'] = 'laporan/export_excel_barang_masuk_bulanan/$1';
$route['barang_keluar_harian'] = 'laporan/data_barang_keluar_harian';
$route['barang_keluar_harian/(:any)'] = 'laporan/cetak_barang_keluar_harian/$1';
$route['barang_keluar_harian/export_excel/(:any)'] = 'laporan/export_excel_barang_keluar_harian/$1';
$route['barang_keluar_bulanan'] = 'laporan/data_barang_keluar_bulanan';
$route['barang_keluar_bulanan/(:any)'] = 'laporan/cetak_barang_keluar_bulanan/$1';
$route['barang_keluar_bulanan/export_excel/(:any)'] = 'laporan/export_excel_barang_keluar_bulanan/$1';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
