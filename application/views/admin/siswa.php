<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $page ?></h1>
  </div>

  <?= $this->session->flashdata('message') ?>

  <?php if (validation_errors()) : ?>
    <div class="alert alert-danger" role="alert">
      <?= validation_errors() ?>
    </div>
  <?php endif ?>

  <div class="card">
    <div class="card-body">
      <a href="#" data-toggle="modal" data-target="#addSiswaModal" class="btn mb-3 btn-success">Tambah Data</a>

      <table id="tableSiswa" class="table w-100">
        <thead>
          <th>NISN</th>
          <th>NIS</th>
          <th>Nama Siswa</th>
          <th>Kelas</th>
          <th>No. Telp</th>
          <th></th>
          <th></th>
        </thead>
        <tbody></tbody>
      </table>

    </div>
  </div>

</div>
<!-- /.container-fluid -->