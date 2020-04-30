<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Auth_siswa extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->library('form_validation');
  }

  public function index()
  {
    $this->form_validation->set_rules('nisn', 'NISN', 'trim|required');
    $this->form_validation->set_rules('password', 'Password', 'trim|required');

    if ($this->form_validation->run() == FALSE) {
      $data['title'] = "S-PAY";

      $this->load->view('auth_siswa/header', $data, FALSE);
      $this->load->view('auth_siswa/login', $data, FALSE);
      $this->load->view('auth_siswa/footer', $data, FALSE);
    } else {
      $this->_login();
    }
  }

  private function _login()
  {
    $nisn = $this->input->post('nisn');
    $password = $this->input->post('password');

    $user = $this->db->get_where('siswa', ['nisn' => $nisn])->row_array();

    if ($user) {
      if ($password == $user['nis']) {
        $array = array(
          'nisn' => $nisn
        );

        $this->session->set_userdata($array);
        redirect('siswa/dashboard');
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-danger">Password doesn\'t matches!</div>');
        redirect('auth_siswa');
      }
    } else {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">User not found!</div>');
      redirect('auth_siswa');
    }
  }

  public function logout()
  {
    $this->session->unset_userdata('username');
    $this->session->unset_userdata('role');
    $this->session->set_flashdata('message', '<div class="alert alert-success">Logout!</div>');
    redirect('auth_siswa');
  }

  public function block()
  {
    $this->load->view('auth_siswa/block');
  }
}


/* End of file Auth_siswa.php */
