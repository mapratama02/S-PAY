<div class="container">

  <!-- Outer Row -->
  <div class="row justify-content-center">

    <div class="col-md-6">

      <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
          <!-- Nested Row within Card Body -->
          <div class="row">
            <div class="col-lg-12">
              <div class="p-5">
                <div class="text-center">
                  <h1 class="h4 text-gray-900 mb-4"><?= $title ?> &mdash; Siswa</h1>
                </div>
                <?= $this->session->flashdata('message') ?>
                <form class="user" action="<?= base_url('auth_siswa') ?>" method="POST">
                  <div class="form-group">
                    <input type="text" name="nisn" class="form-control form-control-user" placeholder="NISN">
                    <?= form_error('nisn', '<small class="text-danger pl-3">', '</small>') ?>
                  </div>
                  <div class="form-group">
                    <input type="password" name="password" class="form-control form-control-user" placeholder="Password">
                    <?= form_error('password', '<small class="text-danger pl-3">', '</small>') ?>
                  </div>
                  <button type="submit" class="btn btn-primary btn-user btn-block">
                    Login
                  </button>
                </form>
                <!-- <hr> -->
                <!-- <div class="text-center">
                  <a class="small" href="forgot-password.html">Forgot Password?</a>
                </div> -->
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>

  <!-- <div class="row justify-content-center">
    <div class="col-sm-3">
      <a href="<?= base_url('auth_siswa') ?>" class="btn shadow-lg btn-success btn-block">Login sebagai murid</a>
    </div>
  </div> -->

</div>