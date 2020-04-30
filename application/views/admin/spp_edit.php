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

          <form action="<?= base_url('admin/spp_edit/') . $spp['id_spp'] ?>" method="post">
            <div class="form-group">
              <label for="tahunSPP">Tahun SPP</label>
              <input type="number" name="tahun" value="<?= $spp['tahun'] ?>" id="tahunSPP" class="form-control">
            </div>
            <div class="form-group">
              <label for="nominalSPP">Nominal SPP</label>
              <input type="number" name="nominal" value="<?= $spp['nominal'] ?>" id="nominalSPP" class="form-control">
            </div>
            <button class="btn btn-success" type="submit">Kirim</button>
          </form>

        </div>
      </div>
    </div>
  </div>

</div>
<!-- /.container-fluid -->