<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Siswa extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if (!$this->session->userdata('nisn')) {
      redirect('auth');
    }
    $this->load->library('form_validation');
  }

  private function date_format($date)
  {
    $bulan = [
      1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $seperate = explode('-', $date);

    return $bulan[(int) $seperate[1]];
  }

  public function dashboard()
  {
    $this->load->library('ciqrcode');

    $config['cacheable']  = true; //boolean, the default is true
    $config['cachedir']    = ''; //string, the default is application/cache/
    $config['errorlog']    = ''; //string, the default is application/logs/
    $config['quality']    = true; //boolean, the default is true
    $config['size']      = ''; //interger, the default is 1024
    $config['black']    = array(224, 255, 255); // array, default is array(255,255,255)
    $config['white']    = array(155, 155, 155); // array, default is array(0,0,0)
    $this->ciqrcode->initialize($config);

    $data['title'] = "S-PAY";
    $data['page'] = "Page Siswa";
    $data['user'] = $this->db->get_where('siswa', ['nisn' => $this->session->userdata('nisn')])->row_array();

    $data['banyak_siswa'] = $this->db->count_all('siswa');
    $data['banyak_petugas'] = $this->db->count_all('petugas');
    $data['banyak_kelas'] = $this->db->count_all('kelas');
    $data['total_spp'] = $this->db->query("SELECT SUM(`jumlah_bayar`) AS `total_spp` FROM `pembayaran`")->row_array();

    // $bulan = $this->date_format(date('Y-m-d'));
    $nisn = $this->session->userdata('nisn');

    $data['spp'] = $this->db->query("SELECT `pembayaran`.nisn, `spp`.nominal, `pembayaran`.jumlah_bayar FROM `pembayaran` JOIN `spp` ON `spp`.id_spp = `pembayaran`.id_spp WHERE `pembayaran`.nisn = $nisn")->row_array();
    $spp = $this->db->query("SELECT `pembayaran`.nisn, `spp`.nominal, `pembayaran`.jumlah_bayar FROM `pembayaran` JOIN `spp` ON `spp`.id_spp = `pembayaran`.id_spp WHERE `pembayaran`.nisn = $nisn")->row_array();

    $hutang = $spp['nominal'] - $spp['jumlah_bayar'];

    if ($spp['jumlah_bayar'] == $spp['nominal']) {
      $kelunasan = "Sudah Lunas";
    } else {
      $kelunasan = "Belum Lunas - kurang Rp" . number_format($hutang, 2, ',', '.');
    }

    $data['total_spp'] = $this->db->query("SELECT SUM(`jumlah_bayar`) AS `total_spp` FROM `pembayaran`")->row_array();

    $params['data'] = "SPP " . $data['user']['nama'] . "\nbulan " . $this->date_format(date('Y-m-d')) . " : " . $kelunasan;
    $params['level'] = 'M';
    $params['size'] = 5;
    $params['savename'] = FCPATH . $data['user']['nama'] . '.png';
    $this->ciqrcode->generate($params);

    $data['qr_code'] = '<img src="' . base_url() . $data['user']['nama'] . '.png" />';

    $this->load->view('siswa/header', $data, FALSE);
    $this->load->view('siswa/sidebar', $data, FALSE);
    $this->load->view('siswa/navbar', $data, FALSE);
    $this->load->view('siswa/dashboard', $data, FALSE);
    $this->load->view('siswa/footer', $data, FALSE);
  }

  public function history_json()
  {
    $user = $this->db->get_where('siswa', ['nisn' => $this->session->userdata('nisn')])->row_array();

    $this->load->library('datatables');
    $this->datatables->select('log_transaksi.id_pembayaran, petugas.nama_petugas, siswa.nama, log_transaksi.tgl_bayar, log_transaksi.bulan_dibayar, log_transaksi.tahun_dibayar, log_transaksi.jumlah_bayar');
    $this->datatables->from('log_transaksi');
    $this->datatables->join('petugas', 'log_transaksi.id_petugas = petugas.id_petugas');
    $this->datatables->join('siswa', 'log_transaksi.nisn = siswa.nisn');
    $this->datatables->where('log_transaksi.nisn =', $user['nisn']);
    echo $this->datatables->generate();
  }
}

/* End of file Siswa.php */
