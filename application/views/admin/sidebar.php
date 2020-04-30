<!-- Sidebar -->
<ul class="navbar-nav bg-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
    <div class="sidebar-brand-icon rotate-n-15">
      <i class="fas fa-dollar-sign"></i>
    </div>
    <div class="sidebar-brand-text mx-3"><?= $title ?></div>
  </a>

  <hr class="sidebar-divider my-0">

  <!-- Nav Item - Dashboard -->
  <?php if ($page == "Dashboard") : ?>
    <li class="nav-item active">
      <a class="nav-link" href="<?= base_url('admin/dashboard') ?>">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
    </li>
  <?php else : ?>
    <li class="nav-item">
      <a class="nav-link" href="<?= base_url('admin/dashboard') ?>">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
    </li>
  <?php endif; ?>

  <!-- Nav Item - Data Kelas -->
  <?php if ($page == "Data Kelas") : ?>
    <li class="nav-item active">
      <a class="nav-link" href="<?= base_url('admin/kelas') ?>">
        <i class="fas fa-fw fa-square-full"></i>
        <span>Data Kelas</span></a>
    </li>
  <?php else : ?>
    <li class="nav-item">
      <a class="nav-link" href="<?= base_url('admin/kelas') ?>">
        <i class="fas fa-fw fa-square-full"></i>
        <span>Data Kelas</span></a>
    </li>
  <?php endif; ?>


  <!-- Nav Item - Data Siswa -->
  <?php if ($page == "Data Siswa") : ?>
    <li class="nav-item active">
      <a class="nav-link" href="<?= base_url('admin/siswa') ?>">
        <i class="fas fa-fw fa-user-graduate"></i>
        <span>Data Siswa</span></a>
    </li>
  <?php else : ?>
    <li class="nav-item">
      <a class="nav-link" href="<?= base_url('admin/siswa') ?>">
        <i class="fas fa-fw fa-user-graduate"></i>
        <span>Data Siswa</span></a>
    </li>
  <?php endif; ?>

  <!-- Nav Item - Data Petugas -->
  <?php if ($page == "Data Petugas") : ?>
    <li class="nav-item active">
      <a class="nav-link" href="<?= base_url('admin/petugas') ?>">
        <i class="fas fa-fw fa-user"></i>
        <span>Data Petugas</span></a>
    </li>
  <?php else : ?>
    <li class="nav-item">
      <a class="nav-link" href="<?= base_url('admin/petugas') ?>">
        <i class="fas fa-fw fa-user"></i>
        <span>Data Petugas</span></a>
    </li>
  <?php endif; ?>

  <!-- Nav Item - Data SPP -->
  <?php if ($page == "Data SPP") : ?>
    <li class="nav-item active">
      <a class="nav-link" href="<?= base_url('admin/spp') ?>">
        <i class="fas fa-fw fa-dollar-sign"></i>
        <span>Data SPP</span></a>
    </li>
  <?php else : ?>
    <li class="nav-item">
      <a class="nav-link" href="<?= base_url('admin/spp') ?>">
        <i class="fas fa-fw fa-dollar-sign"></i>
        <span>Data SPP</span></a>
    </li>
  <?php endif; ?>

  <!-- Nav Item - Transaksi -->
  <?php if ($page == "Transaksi") : ?>
    <li class="nav-item active">
      <a class="nav-link" href="<?= base_url('admin/transaksi') ?>">
        <i class="fas fa-fw fa-money-bill"></i>
        <span>Transaksi</span></a>
    </li>
  <?php else : ?>
    <li class="nav-item">
      <a class="nav-link" href="<?= base_url('admin/transaksi') ?>">
        <i class="fas fa-fw fa-money-bill"></i>
        <span>Transaksi</span></a>
    </li>
  <?php endif; ?>


  <hr class="sidebar-divider d-none d-md-block">

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>
<!-- End of Sidebar -->

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

  <!-- Main Content -->
  <div id="content">