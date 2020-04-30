<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if (!$this->session->userdata('username')) {
      redirect('auth');
    }

    if ($this->session->userdata('level') != 'admin') {
      redirect('auth/block');
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

    $data['banyak_siswa'] = $this->db->count_all('siswa');
    $data['banyak_petugas'] = $this->db->count_all('petugas');
    $data['banyak_kelas'] = $this->db->count_all('kelas');
    $data['total_spp'] = $this->db->query("SELECT SUM(`jumlah_bayar`) AS `total_spp` FROM `pembayaran`")->row_array();

    $this->load->view('admin/header', $data, FALSE);
    $this->load->view('admin/sidebar', $data, FALSE);
    $this->load->view('admin/navbar', $data, FALSE);
    $this->load->view('admin/dashboard', $data, FALSE);
    $this->load->view('admin/footer', $data, FALSE);
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

  public function kelas()
  {
    $this->form_validation->set_rules('nama_kelas', 'Nama Kelas', 'trim|required');
    $this->form_validation->set_rules('kompetensi_keahlian', 'Kompetnsi Keahlian', 'trim|required');

    if ($this->form_validation->run() == FALSE) {
      $data['title'] = "S-PAY";
      $data['page'] = "Data Kelas";
      $data['user'] = $this->db->get_where('petugas', ['username' => $this->session->userdata('username')])->row_array();

      $this->load->view('admin/header', $data, FALSE);
      $this->load->view('admin/sidebar', $data, FALSE);
      $this->load->view('admin/navbar', $data, FALSE);
      $this->load->view('admin/kelas', $data, FALSE);
      $this->load->view('admin/footer', $data, FALSE);
    } else {
      $this->add_kelas();
    }
  }

  public function kelas_delete($id)
  {
    $this->db->delete('kelas', ['id_kelas' => $id]);

    $this->session->set_flashdata('message', '<div class="alert mb-4 alert-success">Data berhasil dihapus!</div>');
    redirect('admin/kelas');
  }

  public function kelas_edit($id)
  {
    $this->form_validation->set_rules('nama_kelas', 'Nama Kelas', 'trim|required');
    $this->form_validation->set_rules('kompetensi_keahlian', 'Kompetensi Keahlian', 'trim|required');

    if ($this->form_validation->run() == FALSE) {
      $data['title'] = "S-PAY";
      $data['page'] = "Data Kelas";
      $data['user'] = $this->db->get_where('petugas', ['username' => $this->session->userdata('username')])->row_array();
      $data['id'] = $id;
      $data['data_kelas'] = $this->db->get_where('kelas', ['id_kelas' => $id])->row_array();

      $this->load->view('admin/header', $data, FALSE);
      $this->load->view('admin/sidebar', $data, FALSE);
      $this->load->view('admin/navbar', $data, FALSE);
      $this->load->view('admin/kelas_edit', $data, FALSE);
      $this->load->view('admin/footer', $data, FALSE);
    } else {
      $set = [
        'nama_kelas' => $this->input->post('nama_kelas'),
        'kompetensi_keahlian' => $this->input->post('kompetensi_keahlian'),
      ];

      $this->db->where('id_kelas', $id);
      $this->db->update('kelas', $set);

      $this->session->set_flashdata('message', '<div class="alert mb-4 alert-success">Data berhasil diubah!</div>');
      redirect('admin/kelas');
    }
  }

  private function add_kelas()
  {
    $data = [
      'nama_kelas' => htmlspecialchars($this->input->post('nama_kelas')),
      'kompetensi_keahlian' => htmlspecialchars($this->input->post('kompetensi_keahlian')),
    ];

    $this->db->insert('kelas', $data);

    $this->session->set_flashdata('message', '<div class="alert mb-4 alert-success">Data berhasil ditambah!</div>');
    redirect('admin/kelas', 'refresh');
  }

  public function kelas_json()
  {
    $this->load->library('datatables');
    $this->datatables->select('id_kelas, nama_kelas, kompetensi_keahlian');
    $this->datatables->from('kelas');
    $this->datatables->add_column('delete', anchor('admin/kelas_delete/$1', 'Hapus', ['class' => 'btn btn-danger', 'onclick' => 'return confirm(\'Hapus $2?\')']), 'id_kelas, nama_kelas');
    $this->datatables->add_column('edit', anchor('admin/kelas_edit/$1', 'Edit', ['class' => 'btn btn-primary']), 'id_kelas');
    echo $this->datatables->generate();
  }

  public function siswa()
  {
    $this->form_validation->set_rules('nisn', 'NISN', 'trim|required|is_unique[siswa.nisn]');
    $this->form_validation->set_rules('nis', 'NIS', 'trim|required|is_unique[siswa.nis]');
    $this->form_validation->set_rules('nama', 'Nama Siswa', 'trim|required');
    $this->form_validation->set_rules('id_kelas', 'Kelas', 'trim|required');
    $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
    $this->form_validation->set_rules('no_telp', 'Nomor Telepon', 'trim|required|numeric');
    $this->form_validation->set_rules('no_telp', 'Nomor Telepon', 'trim|required|numeric');

    if ($this->form_validation->run() == FALSE) {
      $data['title'] = "S-PAY";
      $data['page'] = "Data Siswa";
      $data['user'] = $this->db->get_where('petugas', ['username' => $this->session->userdata('username')])->row_array();

      $data['data_kelas'] = $this->db->get('kelas')->result_array();
      $data['data_spp'] = $this->db->get('spp')->result_array();

      $this->load->view('admin/header', $data, FALSE);
      $this->load->view('admin/sidebar', $data, FALSE);
      $this->load->view('admin/navbar', $data, FALSE);
      $this->load->view('admin/siswa', $data, FALSE);
      $this->load->view('admin/footer', $data, FALSE);
    } else {
      $this->add_siswa();

      $this->session->set_flashdata('message', '<div class="alert mb-4 alert-success">Data berhasil ditambah!</div>');
      redirect('admin/siswa', 'refresh');
    }
  }

  public function siswa_edit($nisn)
  {
    $this->form_validation->set_rules('nama', 'Nama Siswa', 'trim|required');
    $this->form_validation->set_rules('id_kelas', 'Kelas', 'trim|required');
    $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
    $this->form_validation->set_rules('no_telp', 'Nomor Telepon', 'trim|required|numeric');
    $this->form_validation->set_rules('no_telp', 'Nomor Telepon', 'trim|required|numeric');

    if ($this->form_validation->run() == FALSE) {
      $data['title'] = "S-PAY";
      $data['page'] = "Data Siswa";
      $data['user'] = $this->db->get_where('petugas', ['username' => $this->session->userdata('username')])->row_array();

      $data['siswa'] = $this->db->get_where('siswa', ['nisn' => $nisn])->row_array();
      $data['data_kelas'] = $this->db->get('kelas')->result_array();
      $data['data_spp'] = $this->db->get('spp')->result_array();

      $this->load->view('admin/header', $data, FALSE);
      $this->load->view('admin/sidebar', $data, FALSE);
      $this->load->view('admin/navbar', $data, FALSE);
      $this->load->view('admin/siswa_edit', $data, FALSE);
      $this->load->view('admin/footer', $data, FALSE);
    } else {
      $this->edit_siswa($nisn);

      $this->session->set_flashdata('message', '<div class="alert mb-4 alert-success">Data berhasil diubah!</div>');
      redirect('admin/siswa', 'refresh');
    }
  }

  private function add_siswa()
  {
    $set = [
      'nisn' => $this->input->post('nisn'),
      'nis' => $this->input->post('nis'),
      'nama' => $this->input->post('nama'),
      'id_kelas' => $this->input->post('id_kelas'),
      'alamat' => $this->input->post('alamat'),
      'no_telp' => $this->input->post('no_telp'),
      'id_spp' => $this->input->post('id_spp'),
    ];

    $this->db->insert('siswa', $set);
  }

  private function edit_siswa($nisn)
  {
    $set = [
      'nama' => $this->input->post('nama'),
      'id_kelas' => $this->input->post('id_kelas'),
      'alamat' => $this->input->post('alamat'),
      'no_telp' => $this->input->post('no_telp'),
      'id_spp' => $this->input->post('id_spp'),
    ];

    $this->db->where('nisn', $nisn);
    $this->db->update('siswa', $set);
  }

  public function siswa_delete($nisn)
  {
    $this->db->delete('siswa', ['nisn' => $nisn]);
    $this->session->set_flashdata('message', '<div class="alert mb-4 alert-success">Data berhasil dihapus!</div>');

    redirect('admin/siswa');
  }

  public function siswa_json()
  {
    $this->load->library('datatables');
    $this->datatables->select('siswa.nisn, siswa.nis, siswa.nama, kelas.nama_kelas, siswa.no_telp');
    $this->datatables->from('siswa');
    $this->datatables->join('kelas', 'siswa.id_kelas = kelas.id_kelas');
    $this->datatables->add_column('delete', anchor('admin/siswa_delete/$1', 'Hapus', ['class' => 'btn btn-danger', 'onclick' => 'return confirm(\'Delete $2?\')']), 'nisn, nama');
    $this->datatables->add_column('edit', anchor('admin/siswa_edit/$1', 'Edit', ['class' => 'btn btn-primary']), 'nisn');
    echo $this->datatables->generate();
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

      $this->load->view('admin/header', $data, FALSE);
      $this->load->view('admin/sidebar', $data, FALSE);
      $this->load->view('admin/navbar', $data, FALSE);
      $this->load->view('admin/transaksi', $data, FALSE);
      $this->load->view('admin/footer', $data, FALSE);
    } else {
      $spp = $this->db->get_where('pembayaran', ['nisn' => $this->input->post('siswa'), 'bulan_dibayar' => $this->input->post('bulan_dibayar')])->num_rows();
      // $spp = $this->db->get_where('pembayaran', ['nisn' => $this->input->post('siswa'), 'bulan_dibayar' => $this->input->post('bulan_dibayar')])->result_array();

      if ($spp > 0) {
        $this->session->set_flashdata('message', '<div class="alert mb-4 alert-danger">Data sudah ada!</div>');
        redirect('admin/transaksi');
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
        redirect('admin/transaksi');
      }
    }
  }

  public function transaksi_delete($id)
  {
    $this->db->delete('pembayaran', ['id_pembayaran' => $id]);
    $this->session->set_flashdata('message', '<div class="alert mb-4 alert-success">Data berhasil dihapus!</div>');
    redirect('admin/transaksi');
  }

  public function transaksi_json()
  {
    $this->load->library('datatables');
    $this->datatables->select('pembayaran.id_pembayaran, petugas.nama_petugas, siswa.nama, pembayaran.tgl_bayar, pembayaran.bulan_dibayar, pembayaran.tahun_dibayar, pembayaran.jumlah_bayar');
    $this->datatables->from('pembayaran');
    $this->datatables->join('petugas', 'pembayaran.id_petugas = petugas.id_petugas');
    $this->datatables->join('siswa', 'pembayaran.nisn = siswa.nisn');
    $this->datatables->add_column('delete', anchor('admin/transaksi_delete/$1', 'Hapus', ['class' => 'btn btn-danger', 'onclick' => 'return confirm(\'Delete $2?\')']), 'id_pembayaran, nama');
    $this->datatables->add_column('edit', anchor('admin/transaksi_edit/$1', 'Edit', ['class' => 'btn btn-primary']), 'id_pembayaran');
    echo $this->datatables->generate();
  }

  public function petugas()
  {
    $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[12]|is_unique[petugas.username]|regex_match[/a-z0-9/]', [
      'regex_match' => 'The {field} only contains Alphabetical & Numeric'
    ]);
    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
    $this->form_validation->set_rules('nama_petugas', 'Nama Petugas', 'trim|required');
    $this->form_validation->set_rules('level', 'Level', 'trim|required');

    if ($this->form_validation->run() == FALSE) {
      $data['title'] = "S-PAY";
      $data['page'] = "Data Petugas";
      $data['user'] = $this->db->get_where('petugas', ['username' => $this->session->userdata('username')])->row_array();

      $data['petugas'] = $this->db->get('petugas')->result_array();
      $data['siswa'] = $this->db->get('siswa')->result_array();
      $data['spp'] = $this->db->get('spp')->result_array();

      $this->load->view('admin/header', $data, FALSE);
      $this->load->view('admin/sidebar', $data, FALSE);
      $this->load->view('admin/navbar', $data, FALSE);
      $this->load->view('admin/petugas', $data, FALSE);
      $this->load->view('admin/footer', $data, FALSE);
    } else {
      $set = [
        'username' => htmlspecialchars($this->input->post('username')),
        'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
        'nama_petugas' => htmlspecialchars($this->input->post('nama_petugas')),
        'level' => $this->input->post('level'),
      ];

      $this->db->insert('petugas', $set);
      $this->session->set_flashdata('message', '<div class="alert mb-4 alert-success">Data berhasil masuk!</div>');
      redirect('admin/petugas');
    }
  }

  public function petugas_delete($id)
  {
    $this->db->delete('petugas', ['id_petugas' => $id]);
    $this->session->set_flashdata('message', '<div class="alert mb-4 alert-success">Data berhasil dihapus!</div>');
    redirect('admin/petugas');
  }

  public function petugas_edit($id)
  {
    $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[12]');
    $this->form_validation->set_rules('password', 'Password', 'trim|min_length[8]');
    $this->form_validation->set_rules('nama_petugas', 'Nama Petugas', 'trim|required');
    $this->form_validation->set_rules('level', 'Level', 'trim|required');

    if ($this->form_validation->run() == FALSE) {
      $data['title'] = "S-PAY";
      $data['page'] = "Data Petugas";
      $data['user'] = $this->db->get_where('petugas', ['username' => $this->session->userdata('username')])->row_array();
      $data['level'] = ['1' => 'admin', '2' => 'petugas'];

      $data['id'] = $id;
      $data['petugas'] = $this->db->get_where('petugas', ['id_petugas' => $id])->row_array();

      $this->load->view('admin/header', $data, FALSE);
      $this->load->view('admin/sidebar', $data, FALSE);
      $this->load->view('admin/navbar', $data, FALSE);
      $this->load->view('admin/petugas_edit', $data, FALSE);
      $this->load->view('admin/footer', $data, FALSE);
    } else {
      if ($this->input->post('password')) {
        $set = [
          'username' => htmlspecialchars($this->input->post('username')),
          'password' => password_hash($this->input->post('username'), PASSWORD_DEFAULT),
          'nama_petugas' => htmlspecialchars($this->input->post('nama_petugas')),
          'level' => $this->input->post('level'),
        ];

        $this->db->where('id_petugas', $id);
        $this->db->update('petugas', $set);
      } else {
        $set = [
          'username' => htmlspecialchars($this->input->post('username')),
          'nama_petugas' => htmlspecialchars($this->input->post('nama_petugas')),
          'level' => $this->input->post('level'),
        ];

        $this->db->where('id_petugas', $id);
        $this->db->update('petugas', $set);
      }

      $this->session->set_flashdata('message', '<div class="alert mb-4 alert-success">Data berhasil diubah!</div>');
      redirect('admin/petugas');
    }
  }

  public function petugas_json()
  {
    $user = $this->db->get_where('petugas', ['username' => $this->session->userdata('username')])->row_array();

    $this->load->library('datatables');
    $this->datatables->select('id_petugas, username, nama_petugas, level');
    $this->datatables->from('petugas');
    $this->datatables->where('level !=', $user['level']);
    $this->datatables->add_column('delete', anchor('admin/petugas_delete/$1', 'Hapus', ['class' => 'btn btn-danger', 'onclick' => 'return confirm(\'Hapus $2?\')']), 'id_petugas, nama_petugas');
    $this->datatables->add_column('edit', anchor('admin/petugas_edit/$1', 'Edit', ['class' => 'btn btn-primary']), 'id_petugas');
    echo $this->datatables->generate();
  }

  public function spp()
  {
    $this->form_validation->set_rules('tahun', 'Tahun SPP', 'trim|required|numeric');
    $this->form_validation->set_rules('nominal', 'Nominal SPP', 'trim|required|numeric');

    if ($this->form_validation->run() == FALSE) {
      $data['title'] = "S-PAY";
      $data['page'] = "Data SPP";
      $data['user'] = $this->db->get_where('petugas', ['username' => $this->session->userdata('username')])->row_array();

      $this->load->view('admin/header', $data, FALSE);
      $this->load->view('admin/sidebar', $data, FALSE);
      $this->load->view('admin/navbar', $data, FALSE);
      $this->load->view('admin/spp', $data, FALSE);
      $this->load->view('admin/footer', $data, FALSE);
    } else {
      $set = [
        'tahun' => $this->input->post('tahun'),
        'nominal' => $this->input->post('nominal'),
      ];

      $this->db->insert('spp', $set);

      $this->session->set_flashdata('message', '<div class="alert mb-4 alert-success">Data berhasil masuk!</div>');
      redirect('admin/spp');
    }
  }

  public function spp_edit($id)
  {
    $this->form_validation->set_rules('tahun', 'Tahun SPP', 'trim|required|numeric');
    $this->form_validation->set_rules('nominal', 'Nominal SPP', 'trim|required|numeric');

    if ($this->form_validation->run() == FALSE) {
      $data['title'] = "S-PAY";
      $data['page'] = "Data SPP";
      $data['user'] = $this->db->get_where('petugas', ['username' => $this->session->userdata('username')])->row_array();
      $data['spp'] = $this->db->get_where('spp', ['id_spp' => $id])->row_array();

      $this->load->view('admin/header', $data, FALSE);
      $this->load->view('admin/sidebar', $data, FALSE);
      $this->load->view('admin/navbar', $data, FALSE);
      $this->load->view('admin/spp_edit', $data, FALSE);
      $this->load->view('admin/footer', $data, FALSE);
    } else {
      $set = [
        'tahun' => $this->input->post('tahun'),
        'nominal' => $this->input->post('nominal'),
      ];

      $this->db->where('id_spp', $id);
      $this->db->update('spp', $set);

      $this->session->set_flashdata('message', '<div class="alert mb-4 alert-success">Data berhasil diubah!</div>');
      redirect('admin/spp');
    }
  }

  public function spp_delete($id)
  {
    $this->db->delete('spp', ['id_spp' => $id]);
    $this->session->set_flashdata('message', '<div class="alert mb-4 alert-success">Data berhasil dihapus!</div>');
    redirect('admin/spp');
  }

  public function spp_json()
  {
    $this->load->library('datatables');
    $this->datatables->select('id_spp, tahun, nominal');
    $this->datatables->from('spp');
    $this->datatables->add_column('delete', anchor('admin/spp_delete/$1', 'Hapus', ['class' => 'btn btn-danger', 'onclick' => 'return confirm(\'Hapus $2?\')']), 'id_spp, tahun');
    $this->datatables->add_column('edit', anchor('admin/spp_edit/$1', 'Edit', ['class' => 'btn btn-primary']), 'id_spp');
    echo $this->datatables->generate();
  }

  public function export_pembayaran()
  {
    include APPPATH . 'third_party/PHPExcel.php';

    $excel = new PHPExcel();

    $excel->getProperties()
      ->setCreator('Admin')
      ->setLastModifiedBy('Admin')
      ->setTitle("Report Data Transaksi")
      ->setSubject("Transaksi")
      ->setDescription("Report Data Transaksi")
      ->setKeywords("Transaksi");

    // header
    $excel->setActiveSheetIndex(0)->setCellValue('A1', 'id_pembayaran');
    $excel->setActiveSheetIndex(0)->setCellValue('B1', 'id_petugas');
    $excel->setActiveSheetIndex(0)->setCellValue('C1', 'nama_petugas');
    $excel->setActiveSheetIndex(0)->setCellValue('D1', 'nisn');
    $excel->setActiveSheetIndex(0)->setCellValue('E1', 'nama_siswa');
    $excel->setActiveSheetIndex(0)->setCellValue('F1', 'tgl_bayar');
    $excel->setActiveSheetIndex(0)->setCellValue('G1', 'bulan_dibayar');
    $excel->setActiveSheetIndex(0)->setCellValue('H1', 'tahun_dibayar');
    $excel->setActiveSheetIndex(0)->setCellValue('I1', 'spp');
    $excel->setActiveSheetIndex(0)->setCellValue('J1', 'jumlah_bayar');

    $transaksi = $this->db->query("SELECT `pembayaran`.id_pembayaran, `pembayaran`.id_petugas,`petugas`.nama_petugas, `pembayaran`.nisn, `siswa`.nama, `pembayaran`.tgl_bayar, `pembayaran`.bulan_dibayar, `pembayaran`.tahun_dibayar, `pembayaran`.id_spp, `spp`.nominal, `pembayaran`.jumlah_bayar FROM `pembayaran` JOIN `petugas` ON `pembayaran`.id_petugas = `petugas`.id_petugas JOIN `siswa` ON `pembayaran`.nisn = `siswa`.nisn JOIN `spp` ON `pembayaran`.id_spp = `spp`.id_spp")->result_array();
    $numrows = 2;

    foreach ($transaksi as $trans) {
      $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrows, $trans['id_pembayaran']);
      $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrows, $trans['id_petugas']);
      $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrows, $trans['nama_petugas']);
      $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrows, $trans['nisn']);
      $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrows, $trans['nama']);
      $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrows, $trans['tgl_bayar']);
      $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrows, $trans['bulan_dibayar']);
      $excel->setActiveSheetIndex(0)->setCellValue('H' . $numrows, $trans['tahun_dibayar']);
      $excel->setActiveSheetIndex(0)->setCellValue('I' . $numrows, $trans['nominal']);
      $excel->setActiveSheetIndex(0)->setCellValue('J' . $numrows, $trans['jumlah_bayar']);
      $numrows++;
    }

    $excel->getActiveSheet(0)->setTitle("Data Transaksi");
    $excel->getActiveSheetIndex(0);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Data Transaksi.xlsx"');
    header('Cache-Control: max-age=0');

    $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $write->save('php://output');
  }
}

/* End of file Admin.php */
