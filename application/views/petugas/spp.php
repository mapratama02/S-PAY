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
      <a href="#" data-toggle="modal" data-target="#addSPPModal" class="btn mb-3 btn-success">Tambah Data</a>

      <table id="tableSPP" class="table w-100">
        <thead>
          <th>ID</th>
          <th>Tahun</th>
          <th>Nominal</th>
          <th>Action</th>
          <th></th>
        </thead>
        <tbody></tbody>
      </table>

    </div>
  </div>

</div>
<!-- /.container-fluid -->