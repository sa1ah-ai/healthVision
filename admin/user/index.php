<?php
$user = $conn->query("SELECT * FROM users where user_id ='" . $_settings->userdata('user_id') . "'");
foreach ($user->fetch_array() as $k => $v) {
	$meta[$k] = $v;
}

// Get additional info based on role
$role = $meta['role'];
$additional_info = [];
if ($role == 'patient') {
	$patient_info = $conn->query("SELECT * FROM patients WHERE patient_id = '" . $_settings->userdata('user_id') . "'");
	if ($patient_info->num_rows > 0) {
		foreach ($patient_info->fetch_array() as $k => $v) {
			$additional_info[$k] = $v;
		}
	}
} elseif ($role == 'doctor') {
	$doctor_info = $conn->query("SELECT * FROM doctors WHERE doctor_id = '" . $_settings->userdata('user_id') . "'");
	if ($doctor_info->num_rows > 0) {
		foreach ($doctor_info->fetch_array() as $k => $v) {
			$additional_info[$k] = $v;
		}
	}
}
?>
<?php if ($_settings->chk_flashdata('success')): ?>
	<script>
		alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
	</script>
<?php endif; ?>
<div class="card card-outline rounded-0 card-navy">
	<div class="card-body">
		<div class="container-fluid">
			<div id="msg"></div>
			<form action="" id="manage-user">
				<input type="hidden" name="user_id" value="<?php echo $_settings->userdata('user_id') ?>">
				<input type="hidden" name="role" value="<?php echo $role ?>">

				<div class="form-group">
					<label for="name">Name</label>
					<input type="text" name="name" id="name" class="form-control" value="<?php echo isset($meta['name']) ? $meta['name'] : '' ?>" required>
				</div>
				<div class="form-group">
					<label for="username">Username</label>
					<input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username'] : '' ?>" required autocomplete="off">
				</div>
				<div class="form-group">
					<label for="username">Email</label>
					<input type="email" name="email" id="email" class="form-control" value="<?php echo isset($meta['email']) ? $meta['email'] : '' ?>" required autocomplete="off">
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" name="password" id="password" class="form-control" value="" autocomplete="off">
					<small><i>Leave this blank if you dont want to change the password.</i></small>
				</div>

				<?php if ($role == 'patient'): ?>
					<!-- Patient specific fields -->
					<div class="form-group">
						<label for="date_of_birth">Date of Birth</label>
						<input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="<?php echo isset($additional_info['date_of_birth']) ? $additional_info['date_of_birth'] : '' ?>">
					</div>
					<div class="form-group">
						<label for="gender">Gender</label>
						<select name="gender" id="gender" class="form-control">
							<option value="Male" <?php echo (isset($additional_info['gender']) && $additional_info['gender'] == 'Male') ? 'selected' : '' ?>>Male</option>
							<option value="Female" <?php echo (isset($additional_info['gender']) && $additional_info['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
						</select>
					</div>
					<div class="form-group">
						<label for="contact_number">Contact Number</label>
						<input type="text" name="contact_number" id="contact_number" class="form-control" value="<?php echo isset($additional_info['contact_number']) ? $additional_info['contact_number'] : '' ?>">
					</div>

				<?php elseif ($role == 'doctor'): ?>
					<!-- Doctor specific fields -->
					<div class="form-group">
						<label for="specialization">Specialization</label>
						<input type="text" name="specialization" id="specialization" class="form-control" value="<?php echo isset($additional_info['specialization']) ? $additional_info['specialization'] : '' ?>" required>
					</div>
					<div class="form-group">
						<label for="license_number">License Number</label>
						<input type="text" name="license_number" id="license_number" class="form-control" value="<?php echo isset($additional_info['license_number']) ? $additional_info['license_number'] : '' ?>" required>
					</div>
					<div class="form-group">
						<label for="contact_number">Contact Number</label>
						<input type="text" name="contact_number" id="contact_number" class="form-control" value="<?php echo isset($additional_info['contact_number']) ? $additional_info['contact_number'] : '' ?>" required>
					</div>
				<?php endif; ?>

				<div class="form-group">
					<label for="" class="control-label">Avatar</label>
					<div class="custom-file">
						<input type="file" class="custom-file-input rounded-circle" id="customFile" name="img" onchange="displayImg(this,$(this))" accept="image/png, image/jpeg">
						<label class="custom-file-label" for="customFile">Choose file</label>
					</div>
				</div>
				<div class="form-group d-flex justify-content-center">
					<img src="<?php echo validate_image(isset($meta['avatar']) ? $meta['avatar'] : '') ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
				</div>
			</form>
		</div>
	</div>
	<div class="card-footer">
		<div class="col-md-12">
			<div class="row">
				<button class="btn btn-sm btn-primary" form="manage-user">Update</button>
			</div>
		</div>
	</div>
</div>
<style>
	img#cimg {
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>
<script>
	function displayImg(input, _this) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#cimg').attr('src', e.target.result);
			}

			reader.readAsDataURL(input.files[0]);
		} else {
			$('#cimg').attr('src', "<?php echo validate_image(isset($meta['avatar']) ? $meta['avatar'] : '') ?>");
		}
	}
	// $('#manage-user').submit(function(e) {
	// 	e.preventDefault();
	// 	start_loader()
	// 	$.ajax({
	// 		url: _base_url_ + 'classes/Users.php?f=save',
	// 		data: new FormData($(this)[0]),
	// 		cache: false,
	// 		contentType: false,
	// 		processData: false,
	// 		method: 'POST',
	// 		type: 'POST',
	// 		success: function(resp) {
	// 			if (resp.status == 'success') {
	// 				location.reload()
	// 			} else {
	// 				$('#msg').html('<div class="alert alert-danger">Username already exist</div>')
	// 				end_loader()
	// 			}
	// 		}
	// 	})
	// })
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
	$('#manage-user').submit(function(e) {
		e.preventDefault();
		start_loader()
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
					alert('updated successful!');
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
</script>