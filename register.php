<?php require_once('./config.php') ?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
<?php require_once('inc/header.php') ?>
<?php
?>
<script>
  start_loader()
</script>
<style>
  html,
  body {
    width: 100%;
    height: 100% !important;
  }

  body {
    background-image: url("<?php echo validate_image($_settings->info('cover')) ?>");
    background-size: cover;
    background-repeat: no-repeat;
    backdrop-filter: contrast(1);
    overflow-x: hidden
  }

  #page-title {
    text-shadow: 6px 4px 7px black;
    font-size: 3.5em;
    color: #fff4f4 !important;
    background: #8080801c;
  }

  img#cimg {
    height: 5em;
    width: 5em;
    object-fit: cover;
    border-radius: 100% 100%;
  }
</style>

<body class="">
  <div class="d-flex flex-column align-items-center justify-content-center h-100 w-100">
    <h1 class="text-center text-white px-4 py-5" id="page-title"><b><?php echo $_settings->info('name') ?></b></h1>
    <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
      <!-- /.login-logo -->
      <div class="card card-navy my-2 rounded-0">
        <div class="card-header rounded-0">
          <h4 class="card-title">Registration</h4>
        </div>
        <div class="card-body rounded-0">
          <form id="register-form" action="" method="post">
            <input type="hidden" name="user_id">
            <input type="hidden" name="role" value="patient">
            <div class="row">
              <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="form-group">
                  <label for="firstname" class="control-label">Name</label>
                  <input type="text" class="form-control form-control-sm rounded-0" reqiured="" name="name" id="name">
                </div>
                <div class="form-group">
                  <label for="lastname" class="control-label">Email</label>
                  <input type="email" class="form-control form-control-sm rounded-0" name="email" id="email">
                </div>
                <div class="form-group">
                  <label for="middlename" class="control-label">Contact Number</label>
                  <input type="text" class="form-control form-control-sm rounded-0" name="contact_number" id="contact_number">
                </div>
                <div class="form-group">
                  <label for="Gender" class="control-label">Gender</label>
                  <select class="form-control form-control-sm rounded-0" required name="gender" id="gender">
                    <option value='Male'>Male</option>
                    <option value='Female'>Female</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="date_of_birth" class="control-label">Date of Birth</label>
                  <input type="date" class="form-control form-control-sm rounded-0" name="date_of_birth" id="date_of_birth" required>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="form-group">
                  <label for="username" class="control-label">Username</label>
                  <input type="text" class="form-control form-control-sm rounded-0" reqiured="" name="username" id="username">
                </div>
                <div class="form-group">
                  <label for="password" class="control-label">Password</label>
                  <div class="input-group input-group-sm">
                    <input type="password" class="form-control form-control-sm rounded-0" reqiured="" name="password" id="password">
                    <button tabindex="-1" class="btn btn-outline-secondary btn-sm rounded-0 pass_view" type="button"><i class="fa fa-eye-slash"></i></button>
                  </div>
                </div>
                <div class="form-group">
                  <label for="cpassword" class="control-label">Confirm Password</label>
                  <div class="input-group input-group-sm">
                    <input type="password" class="form-control form-control-sm rounded-0" reqiured="" id="cpassword">
                    <button tabindex="-1" class="btn btn-outline-secondary btn-sm rounded-0 pass_view" type="button"><i class="fa fa-eye-slash"></i></button>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="form-group">
                  <label for="" class="control-label">Avatar</label>
                  <div class="custom-file">
                    <input type="file" class="custom-file-input rounded-0" id="customFile" name="img" onchange="displayImg(this,$(this))" accept="image/png, image/jpeg">
                    <label class="custom-file-label rounded-0" for="customFile">Choose file</label>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="form-group d-flex justify-content-center">
                  <img src="<?php echo validate_image('') ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-8">
                <a href="./login.php">Already hava an Account</a>
              </div>
              <!-- /.col -->
              <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Create Account</button>
              </div>
              <!-- /.col -->
            </div>
          </form>
          <!-- /.social-auth-links -->

          <!-- <p class="mb-1">
          <a href="forgot-password.html">I forgot my password</a>
        </p> -->

        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.login-box -->
  </div>
  <!-- jQuery -->
  <script src="<?= base_url ?>plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url ?>dist/js/adminlte.min.js"></script>

  <script>
    function displayImg(input, _this) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          $('#cimg').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
      } else {
        $('#cimg').attr('src', "<?php echo validate_image('') ?>");
      }
    }
    $(document).ready(function() {
      end_loader();
      $('.pass_view').click(function() {
        var input = $(this).siblings('input')
        var type = input.attr('type')
        if (type == 'password') {
          $(this).html('<i class="fa fa-eye"></i>')
          input.attr('type', 'text').focus()
        } else {
          $(this).html('<i class="fa fa-eye-slash"></i>')
          input.attr('type', 'password').focus()
        }
      })

      $('#register-form').submit(function(e) {
        e.preventDefault();
        var _this = $(this);
        var el = $('<div>').addClass('alert alert-danger err_msg').hide();
        $('.err_msg').remove();

        // Password validation
        if ($('#password').val() != $('#cpassword').val()) {
          el.text('Password does not match').show('slow');
          _this.prepend(el);
          return false;
        }

        // Validate avatar file if selected
        if ($('#customFile')[0].files.length > 0) {
          var file = $('#customFile')[0].files[0];
          if (!file.type.match('image.*')) {
            el.text('Only image files are allowed').show('slow');
            _this.prepend(el);
            return false;
          }
          if (file.size > 2097152) { // 2MB
            el.text('Image must be smaller than 2MB').show('slow');
            _this.prepend(el);
            return false;
          }
        }

        start_loader();
        $.ajax({
          url: _base_url_ + "classes/Users.php?f=save",
          method: 'POST',
          data: new FormData(this),
          dataType: 'json',
          cache: false,
          processData: false,
          contentType: false,
          success: function(resp) {
            if (resp.status == 'success') {
              alert('Registration successful!');
              location.href = './login.php';
            } else {
              el.html(resp.msg).show('slow');
              _this.prepend(el);
            }
            end_loader();
          },
          error: function(xhr) {
            el.text('An error occurred: ' + xhr.statusText).show('slow');
            _this.prepend(el);
            end_loader();
          }
        });
      });



      // $('#register-form').submit(function(e) {
      //   e.preventDefault()
      //   var _this = $(this)
      //   var el = $('<div>')
      //   el.addClass('alert alert-danger err_msg')
      //   el.hide()
      //   $('.err_msg').remove()
      //   if ($('#password').val() != $('#cpassword').val()) {
      //     el.text('Password does not match')
      //     _this.prepend(el)
      //     el.show('slow')
      //     $('html, body').scrollTop(0)
      //     return false;
      //   }
      //   if (_this[0].checkValidity() == false) {
      //     _this[0].reportValidity();
      //     return false;
      //   }
      //   start_loader()
      //   $.ajax({
      //     url: _base_url_ + "classes/Users.php?f=save",
      //     method: 'POST',
      //     type: 'POST',
      //     data: new FormData($(this)[0]),
      //     dataType: 'json',
      //     cache: false,
      //     processData: false,
      //     contentType: false,
      //     error: err => {
      //       console.log(err)
      //       alert('An error occurred')
      //       end_loader()
      //     },
      //     success: function(resp) {
      //       if (resp.status == 'success') {
      //         location.href = ('./login.php')
      //       } else if (!!resp.msg) {
      //         el.html(resp.msg)
      //         el.show('slow')
      //         _this.prepend(el)
      //         $('html, body').scrollTop(0)
      //       } else {
      //         alert('An error occurred')
      //         console.log(resp)
      //       }
      //       end_loader()
      //     }
      //   })
      // })
    })
  </script>
</body>

</html>