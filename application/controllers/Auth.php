<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->library('form_validation');
  }

  public function index()
  {
    $this->form_validation->set_rules('password', 'Password', 'trim|required');
    $this->form_validation->set_rules('username', 'Username', 'trim|required');

    if ($this->form_validation->run() == FALSE) {
      $data['title'] = "S-PAY";

      $this->load->view('auth/header', $data, FALSE);
      $this->load->view('auth/login', $data, FALSE);
      $this->load->view('auth/footer', $data, FALSE);
    } else {
      $this->_login();
    }
  }

  private function _login()
  {
    $username = $this->input->post('username');
    $password = $this->input->post('password');

    $user = $this->db->get_where('petugas', ['username' => $username])->row_array();

    if ($user) {
      if (password_verify($password, $user['password'])) {
        $array = array(
          'username' => $username,
          'level' => $user['level']
        );
        $this->session->set_userdata($array);

        if ($this->session->userdata('level') == 'admin') {
          redirect('admin/dashboard');
        } else {
          redirect('petugas/dashboard');
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-danger">Password doesn\'t matches!</div>');
        redirect('auth');
      }
    } else {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">User not found!</div>');
      redirect('auth');
    }
  }

  public function logout()
  {
    $this->session->unset_userdata('username');
    $this->session->unset_userdata('role');
    $this->session->set_flashdata('message', '<div class="alert alert-success">Logout!</div>');
    redirect('auth');
  }

  public function block()
  {
    $this->load->view('auth/block');
  }
}

/* End of file Auth.php */
