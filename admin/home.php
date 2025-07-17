<h1>Welcome, <?php echo $_settings->userdata('name') . " " . $_settings->userdata('username') ?>!</h1>
<hr>
<div class="row">
  <div class="col-12 col-sm-3 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-gradient-navy elevation-1"><i class="fas fa-light fa-images"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Uploaded Images</span>
        <span class="info-box-number">
          <?php
          $images = $conn->query("SELECT * FROM medicalimages where delete_flag = 0")->num_rows;
          echo format_num($images);
          ?>
          <?php ?>
        </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-3 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-gradient-light border elevation-1"><i class="fas fa-light fa-x-ray"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Mammogram Images</span>
        <span class="info-box-number">
          <?php
          $Mammogram = $conn->query("SELECT * FROM medicalimages where delete_flag = 0 and image_type= 'Mammogram' ")->num_rows;
          echo format_num($Mammogram);
          ?>
          <?php ?>
        </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-3 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-success border elevation-1"><i class="fas fa-light fa-x-ray"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Chest X-ray Images</span>
        <span class="info-box-number">
          <?php
          $Chest = $conn->query("SELECT * FROM medicalimages where delete_flag = 0 and image_type= 'Chest X-ray'")->num_rows;
          echo format_num($Chest);
          ?>
          <?php ?>
        </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-3 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-black border elevation-1"><i class="fas fa-light fa-hospital-user"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Registered Patients</span>
        <span class="info-box-number">
          <?php
          $patients = $conn->query("SELECT * FROM patients")->num_rows;
          echo format_num($patients);
          ?>
          <?php ?>
        </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-3 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-warning border elevation-1"><i class="fas fas fa-solid fa-circle-check"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Diagnostic Results</span>
        <span class="info-box-number">
          <?php
          $diagnosticresults = $conn->query("SELECT * FROM diagnosticresults where delete_flag = 0")->num_rows;
          echo format_num($diagnosticresults);
          ?>
          <?php ?>
        </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-3 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-danger border elevation-1"><i class="fas fa-regular fa-eye"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Diagnostic Results Reviewed</span>
        <span class="info-box-number">
          <?php
          $Reviewed = $conn->query("SELECT * FROM diagnosticresults where delete_flag = 0 and status = 'Reviewed' ")->num_rows;
          echo format_num($Reviewed);
          ?>
          <?php ?>
        </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-3 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-gradient-primary elevation-1"><i class="fas fa-regular fa-hourglass-end"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Diagnostic Results Pending</span>
        <span class="info-box-number">
          <?php
          $Pending = $conn->query("SELECT * FROM diagnosticresults where delete_flag = 0 and status = 'Pending' ")->num_rows;
          echo format_num($Pending);
          ?>
          <?php ?>
        </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-3 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-gradient-secondary elevation-1"><i class="fas fa-light fa-disease"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Breast Cancer Detected</span>
        <span class="info-box-number">
          <?php
          $Pending = $conn->query("SELECT * FROM diagnosticresults where delete_flag = 0 and diagnosis = 'Breast Cancer Detected' ")->num_rows;
          echo format_num($Pending);
          ?>
          <?php ?>
        </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
</div>
<div class="container">
  <?php
  $files = array();
  $fopen = scandir(base_app . 'uploads/banner');
  foreach ($fopen as $fname) {
    if (in_array($fname, array('.', '..')))
      continue;
    $files[] = validate_image('uploads/banner/' . $fname);
  }
  ?>
  <div id="tourCarousel" class="carousel slide" data-ride="carousel" data-interval="3000">
    <div class="carousel-inner h-100">
      <?php foreach ($files as $k => $img): ?>
        <div class="carousel-item  h-100 <?php echo $k == 0 ? 'active' : '' ?>">
          <img class="d-block w-100  h-100" style="object-fit:contain" src="<?php echo $img ?>" alt="">
        </div>
      <?php endforeach; ?>
    </div>
    <a class="carousel-control-prev" href="#tourCarousel" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#tourCarousel" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</div>