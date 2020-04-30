<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Seeder extends CI_Controller
{

  public function petugas()
  {
    $data = [
      [
        'id_petugas' => NULL,
        'username' => 'mapratama02',
        'password' => password_hash('Pratama02', PASSWORD_DEFAULT),
        'nama_petugas' => 'Muhammad Anugrah Pratama',
        'level' => 'admin'
      ],
      [
        'id_petugas' => NULL,
        'username' => 'cayleen',
        'password' => password_hash('claytoy', PASSWORD_DEFAULT),
        'nama_petugas' => 'Cay Leen',
        'level' => 'petugas'
      ]
    ];

    $this->db->insert_batch('petugas', $data);
  }
}

/* End of file Seeder.php */
