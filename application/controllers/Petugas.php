<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Petugas extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if (!$this->session->userdata('username')) {
      if ($this->session->userdata('level') != 'petugas') {
        redirect('auth');
      }
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
    $data['title'] = "S-PAY";
    $data['page'] = "Dashboard";
    $data['user'] = $this->db->get_where('petugas', ['username' => $this->session->userdata('username')])->row_array();

    $bulan_ini = $this->date_format(date('Y-m-d'));

    $data['banyak_siswa'] = $this->db->count_all('siswa');
    $data['banyak_petugas'] = $this->db->count_all('petugas');
    $data['banyak_kelas'] = $this->db->count_all('kelas');
    $data['total_spp'] = $this->db->query("SELECT SUM(`jumlah_bayar`) AS `total_spp` FROM `pembayaran` WHERE `bulan_dibayar` = '$bulan_ini'")->row_array();

    $this->load->view('petugas/header', $data, FALSE);
    $this->load->view('petugas/sidebar', $data, FALSE);
    $this->load->view('petugas/navbar', $data, FALSE);
    $this->load->view('petugas/dashboard', $data, FALSE);
    $this->load->view('petugas/footer', $data, FALSE);
  }

  public function transaksi()
  {
    $this->form_validation->set_rules('petugas', 'Petugas', 'trim|required');
    $this->form_validation->set_rules('siswa', 'Siswa', 'trim|required');
    $this->form_validation->set_rules('tanggal_bayar', 'Tanggal Bayar', 'trim|required');
    $this->form_validation->set_rules('bulan_dibayar', 'Bulan Dibayar', 'trim|required');
    $this->form_validation->set_rules('tahun_dibayar', 'Tahun Dibayar', 'trim|required|numeric');
    $this->form_validation->set_rules('spp', 'SPP', 'trim|required');
    $this->form_validation->set_rules('jumlah_bayar', 'Jumlah Bayar', 'trim|required|numeric');

    if ($this->form_validation->run() == FALSE) {
      $data['title'] = "S-PAY";
      $data['page'] = "Transaksi";
      $data['user'] = $this->db->get_where('petugas', ['username' => $this->session->userdata('username')])->row_array();

      $data['petugas'] = $this->db->get('petugas')->result_array();
      $data['siswa'] = $this->db->get('siswa')->result_array();
      $data['spp'] = $this->db->get('spp')->result_array();

      $this->load->view('petugas/header', $data, FALSE);
      $this->load->view('petugas/sidebar', $data, FALSE);
      $this->load->view('petugas/navbar', $data, FALSE);
      $this->load->view('petugas/transaksi', $data, FALSE);
      $this->load->view('petugas/footer', $data, FALSE);
    } else {
      $spp = $this->db->get_where('pembayaran', ['nisn' => $this->input->post('siswa'), 'bulan_dibayar' => $this->input->post('bulan_dibayar')])->num_rows();
      $spp_bulan = $this->db->get_where('pembayaran', ['nisn' => $this->input->post('siswa'), 'bulan_dibayar' => $this->input->post('bulan_dibayar')])->result_array();
      // print_r($spp);
      if ($spp > 0) {
        $this->session->set_flashdata('message', '<div class="alert mb-4 alert-danger">Siswa sudah melakukan transaksi!</div>');
        redirect('petugas/transaksi');
      } else {
        $set = [
          'id_petugas' => $this->input->post('petugas'),
          'nisn' => $this->input->post('siswa'),
          'tgl_bayar' => $this->input->post('tanggal_bayar'),
          'bulan_dibayar' => $this->input->post('bulan_dibayar'),
          'tahun_dibayar' => $this->input->post('tahun_dibayar'),
          'id_spp' => $this->input->post('spp'),
          'jumlah_bayar' => $this->input->post('jumlah_bayar'),
        ];

        $this->db->insert('pembayaran', $set);

        $this->session->set_flashdata('message', '<div class="alert mb-4 alert-success">Data berhasil masuk!</div>');
        redirect('petugas/transaksi');
      }
    }
  }

  public function history_json()
  {
    $this->load->library('datatables');
    $this->datatables->select('log_transaksi.id_pembayaran, petugas.nama_petugas, siswa.nama, log_transaksi.tgl_bayar, log_transaksi.bulan_dibayar, log_transaksi.tahun_dibayar, log_transaksi.jumlah_bayar');
    $this->datatables->from('log_transaksi');
    $this->datatables->join('petugas', 'log_transaksi.id_petugas = petugas.id_petugas');
    $this->datatables->join('siswa', 'log_transaksi.nisn = siswa.nisn');
    echo $this->datatables->generate();
  }

  public function transaksi_json()
  {
    $this->load->library('datatables');
    $this->datatables->select('pembayaran.id_pembayaran, petugas.nama_petugas, siswa.nama, pembayaran.tgl_bayar, pembayaran.bulan_dibayar, pembayaran.tahun_dibayar, pembayaran.jumlah_bayar');
    $this->datatables->from('pembayaran');
    $this->datatables->join('petugas', 'pembayaran.id_petugas = petugas.id_petugas');
    $this->datatables->join('siswa', 'pembayaran.nisn = siswa.nisn');
    // $this->datatables->add_column('delete', anchor('admin/transaksi_delete/$1', 'Hapus', ['class' => 'btn btn-danger', 'onclick' => 'return confirm(\'Delete $2?\')']), 'id_pembayaran, nama');
    // $this->datatables->add_column('edit', anchor('admin/transaksi_edit/$1', 'Edit', ['class' => 'btn btn-primary']), 'id_pembayaran');
    echo $this->datatables->generate();
  }
}

/* End of file Petugas.php */
