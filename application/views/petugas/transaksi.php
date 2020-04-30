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

  <div class="row">
    <div class="col-md-12 mb-4">
      <div class="card">
        <div class="card-body">
          <a href="#" data-toggle="modal" data-target="#addTransaksiModal" class="btn mb-3 btn-success"><i class="fa fa-plus"></i> Tambah Data</a>
          <!-- <a href="<?= base_url('admin/export_pembayaran') ?>" class="btn mb-3 btn-success"><i class="fa fa-file-excel"></i> Export Excel</a> -->


          <table id="tableTransaksi" class="table w-100">
            <thead>
              <th>ID</th>
              <th>Petugas</th>
              <th>Nama Siswa</th>
              <th>Tanggal Bayar</th>
              <th>Bulan Dibayar</th>
              <th>Tahun Dibayar</th>
              <th>Jumlah Bayar</th>
              <!-- <th></th>
              <th></th> -->
            </thead>
            <tbody></tbody>
          </table>

        </div>
      </div>
    </div>
  </div>

</div>
<!-- /.container-fluid -->