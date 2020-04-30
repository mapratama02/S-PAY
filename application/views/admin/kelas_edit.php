<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $page ?> | Edit Data</h1>
  </div>

  <a href="<?= base_url('admin/kelas') ?>" class="btn btn-secondary mb-3">Kembali</a>

  <?php if (validation_errors()) : ?>
    <div class="alert alert-danger" role="alert">
      <?= validation_errors() ?>
    </div>
  <?php endif ?>

  <div class="row justify-content-start">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">

          <form action="<?= base_url('admin/kelas_edit/') . $id ?>" method="post">
            <div class="form-group">
              <label for="namaKelas">Nama Kelas</label>
              <input type="text" name="nama_kelas" value="<?= $data_kelas['nama_kelas'] ?>" id="namaKelas" class="form-control">
            </div>
            <div class="form-group">
              <label for="kompetensiKeahlian">Kompetensi Keahlian</label>
              <input type="text" name="kompetensi_keahlian" value="<?= $data_kelas['kompetensi_keahlian'] ?>" id="kompetensiKeahlian" class="form-control">
            </div>
            <button class="btn btn-success" type="submit">Kirim</button>
          </form>

        </div>
      </div>
    </div>
  </div>

</div>
<!-- /.container-fluid -->