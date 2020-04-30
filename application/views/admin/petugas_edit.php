<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $page ?> | Edit Data</h1>
  </div>

  <!-- <a href="<?= base_url('admin/kelas') ?>" class="btn btn-secondary mb-3">Kembali</a> -->

  <?php if (validation_errors()) : ?>
    <div class="alert alert-danger" role="alert">
      <?= validation_errors() ?>
    </div>
  <?php endif ?>

  <div class="row justify-content-start">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">

          <form action="<?= base_url('admin/petugas_edit/') . $id ?>" method="post">
            <div class="form-group">
              <label for="usernamePetugas">Username Petugas</label>
              <input type="text" name="username" value="<?= $petugas['username'] ?>" id="usernamePetugas" class="form-control">
            </div>
            <div class="form-group">
              <label for="passwordPetugas">Password</label>
              <input type="password" name="password" value="" id="passwordPetugas" class="form-control">
            </div>
            <div class="form-group">
              <label for="namaPetugas">Nama Petugas</label>
              <input type="text" name="nama_petugas" value="<?= $petugas['nama_petugas'] ?>" id="namaPetugas" class="form-control">
            </div>
            <div class="form-group">
              <label for="levelPetugas">Level</label>
              <select name="level" id="levelPetugas" class="form-control">
                <?php foreach ($level as $lv) : ?>
                  <?php if ($lv == $petugas['level']) : ?>
                    <option selected value="<?= $lv ?>"><?= $lv ?></option>
                  <?php else : ?>
                    <option value="<?= $lv ?>"><?= $lv ?></option>
                  <?php endif; ?>
                <?php endforeach; ?>
              </select>
            </div>
            <button class="btn btn-success" type="submit">Kirim</button>
          </form>

        </div>
      </div>
    </div>
  </div>

</div>
<!-- /.container-fluid -->