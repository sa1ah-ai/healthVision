<?php
// Fetch user data securely
$user_id = $_settings->userdata('user_id');
// $user = $conn->query("SELECT * FROM users WHERE user_id = ?", [$user_id]);
$user = $conn->query("SELECT * FROM users where user_id ='" . $_settings->userdata('user_id') . "'");
// $user_data = $user->fetch_assoc();

// // Extract user data to variables
// foreach ($user_data as $k => $v) {
// 	if (!is_numeric($k)) {
// 		$$k = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
// 	}
// }

// $user_id = $_settings->userdata('user_id');
$user = $conn->query("SELECT u.*, 
IF(u.role='patient', p.date_of_birth, NULL) as date_of_birth,
IF(u.role='patient', p.gender, NULL) as gender,
IF(u.role='doctor', d.specialization, NULL) as specialization,
IF(u.role='doctor', d.license_number, NULL) as license_number,
COALESCE(p.contact_number, d.contact_number) as contact_number
FROM users u
LEFT JOIN patients p ON u.user_id = p.patient_id AND u.role='patient'
LEFT JOIN doctors d ON u.user_id = d.doctor_id AND u.role='doctor'
WHERE u.user_id ='" . $_settings->userdata('user_id') . "'");

$user_data = $user->fetch_assoc();

// Extract user data to variables
foreach ($user_data as $k => $v) {
	if (!is_numeric($k)) {
		$$k = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
	}
}
?>
<section class="py-4">
	<div class="container">
		<div class="card card-outline rounded-0 card-navy">
			<div class="card-header">
				<h4 class="font-weight-bolder">Update User Details</h4>
			</div>
			<div class="card-body">
				<div class="container-fluid">
					<div id="msg"></div>
					<form action="" id="manage-user" enctype="multipart/form-data">
						<input type="hidden" name="user_id" value="<?= isset($user_id) ? $user_id : '' ?>">
						<input type="hidden" name="type" value="2">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group">
									<label for="name" class="control-label">Name</label>
									<input type="text" class="form-control form-control-sm rounded-0" required name="name" id="name" value="<?= isset($name) ? $name : "" ?>">
								</div>
								<div class="form-group">
									<label for="email" class="control-label">Email</label>
									<input type="email" class="form-control form-control-sm rounded-0" required name="email" id="email" value="<?= isset($email) ? $email : "" ?>">
								</div>
								<div class="form-group">
									<label for="contact_number" class="control-label">Contact Number</label>
									<input type="text" class="form-control form-control-sm rounded-0" name="contact_number" id="contact_number" value="<?= isset($contact_number) ? $contact_number : "" ?>">
								</div>
								<?php if (isset($role) && $role == 'patient'): ?>
									<div class="form-group">
										<label for="date_of_birth" class="control-label">Date of Birth</label>
										<input type="date" class="form-control form-control-sm rounded-0" name="date_of_birth" id="date_of_birth" value="<?= isset($date_of_birth) ? $date_of_birth : "" ?>">
									</div>
									<div class="form-group">
										<label for="gender" class="control-label">Gender</label>
										<select class="form-control form-control-sm rounded-0" name="gender" id="gender">
											<option value="Male" <?= isset($gender) && $gender == 'Male' ? 'selected' : '' ?>>Male</option>
											<option value="Female" <?= isset($gender) && $gender == 'Female' ? 'selected' : '' ?>>Female</option>
										</select>
									</div>
								<?php endif; ?>
								<?php if (isset($role) && $role == 'doctor'): ?>
										<div class="form-group">
											<label for="specialization" class="control-label">Specialization</label>
											<input type="text" class="form-control form-control-sm rounded-0" name="specialization" id="specialization" value="<?= isset($specialization) ? $specialization : "" ?>">
										</div>
										<div class="form-group">
											<label for="license_number" class="control-label">License Number</label>
											<input type="text" class="form-control form-control-sm rounded-0" name="license_number" id="license_number" value="<?= isset($license_number) ? $license_number : "" ?>">
										</div>
								<?php endif; ?>
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group">
									<label for="username" class="control-label">Username</label>
									<input type="text" class="form-control form-control-sm rounded-0" required name="username" id="username" value="<?= isset($username) ? $username : "" ?>">
								</div>
								<div class="form-group">
									<label for="password" class="control-label">New Password</label>
									<div class="input-group input-group-sm">
										<input type="password" class="form-control form-control-sm rounded-0" name="password" id="password" placeholder="Leave blank to keep current">
										<button tabindex="-1" class="btn btn-outline-secondary btn-sm rounded-0 pass_view" type="button"><i class="fa fa-eye-slash"></i></button>
									</div>
								</div>
								<div class="form-group">
									<label for="cpassword" class="control-label">Confirm New Password</label>
									<div class="input-group input-group-sm">
										<input type="password" class="form-control form-control-sm rounded-0" id="cpassword" placeholder="Leave blank to keep current">
										<button tabindex="-1" class="btn btn-outline-secondary btn-sm rounded-0 pass_view" type="button"><i class="fa fa-eye-slash"></i></button>
									</div>
								</div>
								<small class="text-muted"><i>Leave the Password Fields Blank if you don't wish to update your password.</i></small>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group">
									<label for="avatar" class="control-label">Avatar</label>
									<div class="custom-file">
										<input type="file" class="custom-file-input rounded-0" id="customFile" name="img" onchange="displayImg(this,$(this))" accept="image/png, image/jpeg">
										<label class="custom-file-label rounded-0" for="customFile">Choose file</label>
									</div>
									<small class="text-muted">Max size: 2MB (JPEG/PNG only)</small>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group d-flex justify-content-center">
									<img src="<?php echo validate_image(isset($avatar) ? $avatar : './uploads/default_avatar.png') ?>" alt="User Avatar" id="cimg" class="img-fluid img-thumbnail">
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="card-footer py-1 text-center">
				<button class="btn btn-sm btn-primary btn-flat" form="manage-user">Update</button>
			</div>
		</div>
	</div>
</section>

<style>
	img#cimg {
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100%;
		border: 3px solid #6c757d;
	}

	.custom-file-label::after {
		content: "Browse";
	}

	.err_msg {
		margin-bottom: 15px;
	}
</style>

<script>
	function displayImg(input, _this) {
		if (input.files && input.files[0]) {
			// Validate file type
			const file = input.files[0];
			const validTypes = ['image/jpeg', 'image/png'];

			if (!validTypes.includes(file.type)) {
				alert('Only JPEG and PNG images are allowed');
				input.value = '';
				return;
			}

			// Validate file size (2MB max)
			if (file.size > 2097152) {
				alert('Image must be smaller than 2MB');
				input.value = '';
				return;
			}

			var reader = new FileReader();
			reader.onload = function(e) {
				$('#cimg').attr('src', e.target.result);
				$('.custom-file-label').text(file.name);
			}
			reader.readAsDataURL(file);
		} else {
			$('#cimg').attr('src', "<?php echo validate_image(isset($avatar) ? $avatar : './uploads/default_avatar.png') ?>");
			$('.custom-file-label').text('Choose file');
		}
	}

	$(function() {
		// Password visibility toggle
		$('.pass_view').click(function() {
			var input = $(this).siblings('input');
			var type = input.attr('type');
			if (type == 'password') {
				$(this).html('<i class="fa fa-eye"></i>');
				input.attr('type', 'text').focus();
			} else {
				$(this).html('<i class="fa fa-eye-slash"></i>');
				input.attr('type', 'password').focus();
			}
		});

		// Form submission
		$('#manage-user').submit(function(e) {
			e.preventDefault();
			var _this = $(this);
			var el = $('<div>').addClass('alert alert-danger err_msg').hide();
			$('.err_msg').remove();

			// Password validation
			if ($('#password').val() != $('#cpassword').val()) {
				el.text('Passwords do not match');
				_this.prepend(el);
				el.show('slow');
				$('html, body').scrollTop(0);
				return false;
			}

			// Form validation
			if (_this[0].checkValidity() === false) {
				_this[0].reportValidity();
				return false;
			}

			// File validation
			const fileInput = $('#customFile')[0];
			if (fileInput.files.length > 0) {
				const file = fileInput.files[0];
				if (file.size > 2097152) {
					el.text('Image must be smaller than 2MB');
					_this.prepend(el);
					el.show('slow');
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
				contentType: false,
				processData: false,
				success: function(resp) {
					if (resp.status == 'success') {
						location.reload();
					} else if (resp.msg) {
						el.html(resp.msg).show('slow');
						_this.prepend(el);
					} else {
						el.text('An error occurred').show('slow');
						_this.prepend(el);
					}
					end_loader();
				},
				error: function(xhr) {
					console.log(xhr);
					el.text('An error occurred: ' + xhr.statusText).show('slow');
					_this.prepend(el);
					end_loader();
				}
			});
		});
	});
</script>