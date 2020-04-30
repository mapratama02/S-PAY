</div>
<!-- End of Main Content -->
<!-- Footer -->
<footer class="sticky-footer bg-white">
  <div class="container my-auto">
    <div class="copyright text-center my-auto">
      <span>Copyright &copy; S-PAY &mdash; Muhammad Anugrah Pratama <?= date('Y') ?></span>
    </div>
  </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>


<!-- Add Transaksi Modal-->
<?php if ($page == "Transaksi") : ?>
  <div class="modal fade" id="addTransaksiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form action="<?= base_url('petugas/transaksi') ?>" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label for="petugas">Petugas</label>
              <select name="petugas" id="petugas" class="form-control">
                <?php foreach ($petugas as $p) : ?>
                  <option value="<?= $p['id_petugas'] ?>"><?= $p['nama_petugas'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="siswa">Siswa</label>
              <select name="siswa" id="siswa" class="form-control">
                <?php foreach ($siswa as $s) : ?>
                  <option value="<?= $s['nisn'] ?>"><?= $s['nisn'] ?> - <?= $s['nama'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="tanggal_bayar">Tanggal Bayar</label>
                  <input type="date" name="tanggal_bayar" id="tanggal_bayar" class="form-control">
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="bulan_dibayar">Bulan dibayar</label>
                  <select name="bulan_dibayar" id="bulan_dibayar" class="form-control">
                    <option value="Januari">Januari</option>
                    <option value="Februari">Februari</option>
                    <option value="Maret">Maret</option>
                    <option value="April">April</option>
                    <option value="Mei">Mei</option>
                    <option value="Juni">Juni</option>
                    <option value="Juli">Juli</option>
                    <option value="Agustus">Agustus</option>
                    <option value="September">September</option>
                    <option value="Oktober">Oktober</option>
                    <option value="Nobember">Nobember</option>
                    <option value="Desember">Desember</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="tahun_dibayar">Tahun Dibayar</label>
                  <input type="text" name="tahun_dibayar" id="tahun_dibayar" class="form-control">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="spp">SPP</label>
                  <select name="spp" id="spp" class="form-control">
                    <?php foreach ($spp as $sp) : ?>
                      <option value="<?= $sp['id_spp'] ?>"><?= $sp['tahun'] ?> - Rp<?= number_format($sp['nominal'], 2, ',', '.') ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-sm-6">
                <label for="jml_bayar">Jumlah Bayar</label>
                <input type="number" name="jumlah_bayar" id="jml_bayar" class="form-control">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
            <button class="btn btn-success" type="submit">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
        <a class="btn btn-primary" href="<?= base_url('auth/logout') ?>">Logout</a>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="<?= base_url('assets/') ?>vendor/jquery/jquery.min.js"></script>
<script src="<?= base_url('assets/') ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Datatables JavaScript -->
<script src="<?= base_url('assets/') ?>vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets/') ?>vendor/datatables/dataTables.bootstrap4.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?= base_url('assets/') ?>vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?= base_url('assets/') ?>js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="<?= base_url('assets/') ?>vendor/chart.js/Chart.min.js"></script>

<?php if ($page == "Dashboard") : ?>
  <script>
    var table;

    $(document).ready(function() {
      table = $("#tableRiwayat").DataTable({
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
          url: '<?= base_url('petugas/history_json') ?>',
          type: "POST"
        },
        scrollX: true,
        responsive: true,
        lengthMenu: [5, 10, 20, 50, 100],
        columns: [{
            data: 'id_pembayaran'
          },
          {
            data: 'nama_petugas'
          },
          {
            data: 'nama'
          },
          {
            data: 'tgl_bayar'
          },
          {
            data: 'bulan_dibayar'
          },
          {
            data: 'tahun_dibayar'
          },
          {
            data: 'jumlah_bayar'
          }
        ]
      });
    });
  </script>
<?php endif; ?>

<?php if ($page == "Data Kelas") : ?>
  <script>
    var table;

    $(document).ready(function() {
      table = $("#tableKelas").DataTable({
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
          url: '<?= base_url('petugas/kelas_json') ?>',
          type: "POST"
        },
        scrollX: true,
        responsive: true,
        lengthMenu: [5, 10, 20, 50, 100],
        columns: [{
            data: 'id_kelas'
          },
          {
            data: 'nama_kelas'
          },
          {
            data: 'kompetensi_keahlian'
          },
          {
            data: 'edit',
            orderable: false,
            searchable: false
          },
          {
            data: 'delete',
            orderable: false,
            searchable: false
          }
        ]
      });
    });
  </script>
<?php endif; ?>

<?php if ($page == "Data Petugas") : ?>
  <script>
    var table;

    $(document).ready(function() {
      table = $("#tablePetugas").DataTable({
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
          url: '<?= base_url('petugas/petugas_json') ?>',
          type: "POST"
        },
        scrollX: true,
        responsive: true,
        lengthMenu: [5, 10, 20, 50, 100],
        columns: [{
            data: 'username'
          },
          {
            data: 'nama_petugas'
          },
          {
            data: 'level'
          },
          {
            data: 'edit',
            orderable: false,
            searchable: false
          },
          {
            data: 'delete',
            orderable: false,
            searchable: false
          }
        ]
      });
    });
  </script>
<?php endif; ?>

<?php if ($page == "Transaksi") : ?>
  <script>
    var table;

    $(document).ready(function() {
      table = $("#tableTransaksi").DataTable({
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
          url: '<?= base_url('petugas/transaksi_json') ?>',
          type: "POST"
        },
        scrollX: true,
        responsive: true,
        lengthMenu: [5, 10, 20, 50, 100],
        columns: [{
            data: 'id_pembayaran'
          },
          {
            data: 'nama_petugas'
          },
          {
            data: 'nama'
          },
          {
            data: 'tgl_bayar'
          },
          {
            data: 'bulan_dibayar'
          },
          {
            data: 'tahun_dibayar'
          },
          {
            data: 'jumlah_bayar'
          },
          // {
          //   data: 'edit',
          //   orderable: false,
          //   searchable: false
          // },
          // {
          //   data: 'delete',
          //   orderable: false,
          //   searchable: false
          // }
        ]
      });
    });
  </script>
<?php endif; ?>

</body>

</html>