<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $page ?> | Edit Data</h1>
  </div>

  <?php if (validation_errors()) : ?>
    <div class="alert alert-danger" role="alert">
      <?= validation_errors() ?>
    </div>
  <?php endif ?>

  <div class="card">
    <div class="card-body">

      <form action="<?= base_url('admin/siswa_edit/') . $siswa['nisn'] ?>" method="post">
        <div class="row">
          <div class="col-sm mb-4">
            <div class="form-group">
              <label for="nisn">NISN</label>
              <input type="number" name="nisn" id="nisn" value="<?= $siswa['nisn'] ?>" readonly class="form-control">
            </div>
          </div>
          <div class="col-sm mb-4">
            <div class="form-group">
              <label for="nis">NIS</label>
              <input type="number" name="nis" id="nis" value="<?= $siswa['nis'] ?>" readonly class="form-control">
            </div>
          </div>
          <div class="col-sm mb-4">
            <div class="form-group">
              <label for="nama">Nama Siswa</label>
              <input type="text" name="nama" id="nama" value="<?= $siswa['nama'] ?>" class="form-control">
            </div>
          </div>
          <div class="col-sm mb-4">
            <div class="form-group">
              <label for="kelas">Kelas</label>
              <select name="id_kelas" id="kelas" class="form-control">
                <?php foreach ($data_kelas as $kelas) : ?>
                  <?php if ($kelas['id_kelas'] == $siswa['id_kelas']) : ?>
                    <option selected value="<?= $kelas['id_kelas'] ?>"><?= $kelas['nama_kelas'] ?> - <?= $kelas['kompetensi_keahlian'] ?></option>
                  <?php else : ?>
                    <option value="<?= $kelas['id_kelas'] ?>"><?= $kelas['nama_kelas'] ?> - <?= $kelas['kompetensi_keahlian'] ?></option>
                  <?php endif; ?>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm mb-4">
            <div class="form-group">
              <label for="alamat">Alamat</label>
              <textarea name="alamat" id="alamat" cols="30" rows="5" class="form-control"><?= $siswa['alamat'] ?></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm mb-4">
            <div class="form-group">
              <label for="no_telp">Nomor Telepon</label>
              <input type="tel" name="no_telp" id="no_telp" value="<?= $siswa['no_telp'] ?>" class="form-control">
            </div>
          </div>
          <div class="col-sm mb-4">
            <div class="form-group">
              <label for="spp">SPP</label>
              <select name="id_spp" id="spp" class="form-control">
                <?php foreach ($data_spp as $spp) : ?>
                  <?php if ($spp['id_spp'] == $siswa['id_spp']) : ?>
                    <option selected value="<?= $spp['id_spp'] ?>"><?= $spp['tahun'] ?> - Rp<?= number_format($spp['nominal'], 2, ',', '.') ?></option>
                  <?php else : ?>
                    <option value="<?= $spp['id_spp'] ?>"><?= $spp['tahun'] ?> - Rp<?= number_format($spp['nominal'], 2, ',', '.') ?></option>
                  <?php endif; ?>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="form-group text-right">
          <button type="submit" class="btn btn-success btn-lg">Kirim</button>
        </div>
      </form>

    </div>
  </div>

</div>
<!-- /.container-fluid -->