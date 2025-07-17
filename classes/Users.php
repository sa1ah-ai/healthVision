<?php
require_once('../config.php');
Class Users extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}

	// public function save_user()
	// {
	// 	if (empty($_POST['password'])) {
	// 		unset($_POST['password']);
	// 	} 
	// 	// else {
	// 	// 	$_POST['password'] = sha1($_POST['password']); // Using SHA1 to match DB hashing
	// 	// }

	// 	extract($_POST);
	// 	// Set default date of birth if not provided
	// 	if (!isset($date_of_birth) || empty($date_of_birth)) {
	// 		$date_of_birth = '2000-01-01'; // Default value
	// 	}

	// 	if (empty($user_id)) { // New User Registration
	// 		try {
	// 			// Call the appropriate procedure based on the user role
	// 			if ($role == 'patient') {
	// 				$stmt = $this->conn->prepare("CALL InsertPatient(?, ?, ?, ?, ?, ?, ?)");
	// 				$stmt->bind_param("sssssss", $name, $username, $email, $password, $date_of_birth, $gender, $contact_number);
	// 			} elseif ($role == 'doctor') {
	// 				$stmt = $this->conn->prepare("CALL InsertDoctor(?, ?, ?, ?, ?, ?, ?)");
	// 				$stmt->bind_param("sssssss", $name, $username, $email, $password, $specialization, $license_number, $contact_number);
	// 			} else {
	// 				return json_encode(['status' => 'failed', 'msg' => 'Invalid role selected!']);
	// 			}

	// 			if ($stmt->execute()) {
	// 				$user_id = $this->conn->insert_id;

	// 				// Handle Avatar Upload
	// 				if (!empty($_FILES['img']['tmp_name'])) {
	// 					$this->upload_avatar($user_id);
	// 				}

	// 				return json_encode(['status' => 'success', 'msg' => 'Registration successful']);
	// 			} else {
	// 				return json_encode(['status' => 'failed', 'msg' => 'Database error: ' . $stmt->error]);
	// 			}
	// 		} catch (Exception $e) {
	// 			return json_encode(['status' => 'failed', 'msg' => 'Error: ' . $e->getMessage()]);
	// 		}
	// 	} else { // Update Existing User
	// 		$data = '';
	// 		foreach ($_POST as $k => $v) {
	// 			if (!in_array($k, array('user_id'))) {
	// 				if (!empty($data)) $data .= " , ";
	// 				$data .= " {$k} = '{$v}' ";
	// 			}
	// 		}

	// 		$qry = $this->conn->query("UPDATE users SET $data WHERE user_id = {$user_id}");
	// 		if ($qry) {
	// 			$this->settings->set_flashdata('success', 'User Details successfully updated.');
	// 			foreach ($_POST as $k => $v) {
	// 				if ($this->settings->userdata('user_id') == $user_id)
	// 					$this->settings->set_userdata($k, $v);
	// 			}

	// 			// Handle Avatar Upload
	// 			if (!empty($_FILES['img']['tmp_name'])) {
	// 				$this->upload_avatar($user_id);
	// 			}
	// 			return 1;
	// 		} else {
	// 			return "UPDATE users SET $data WHERE user_id = {$user_id}";
	// 		}
	// 	}
	// }

	public function save_user2()
	{
		// Initialize response
		$resp = ['status' => 'failed', 'msg' => ''];

		try {
			// Password handling
			if (empty($_POST['password'])) {
				unset($_POST['password']);
			} 

			// Set default date of birth if not provided
			if (!isset($_POST['date_of_birth']) || empty($_POST['date_of_birth'])) {
				$_POST['date_of_birth'] = '2000-01-01';
			}
			// Handle avatar upload
			$avatar_path = null;
			if (!empty($_FILES['img']['tmp_name'])) {
				$avatar_path = $this->handle_avatar_upload();
			}

			// Start transaction
			$this->conn->autocommit(FALSE);


			// Call the appropriate procedure
			if ($_POST['role'] == 'patient') {
				$stmt = $this->conn->prepare("CALL InsertPatient(?, ?, ?, ?, ?, ?, ?)");
				$stmt->bind_param(
					"sssssss",
					$_POST['name'],
					$_POST['username'],
					$_POST['email'],
					$_POST['password'],
					$_POST['date_of_birth'],
					$_POST['gender'],
					$_POST['contact_number']

				);
			} elseif ($_POST['role'] == 'doctor') {
				$stmt = $this->conn->prepare("CALL InsertDoctor(?, ?, ?, ?, ?, ?, ?)");
				$stmt->bind_param(
					"sssssss",
					$_POST['name'],
					$_POST['username'],
					$_POST['email'],
					$_POST['password'],
					$_POST['specialization'],
					$_POST['license_number'],
					$_POST['contact_number']
				);
			}

			if (!$stmt->execute()) {
				throw new Exception("Failed to create user: " . $stmt->error);
			}

			$user_id = $this->conn->insert_id;

			// Handle avatar upload if present
			if (!empty($_FILES['img']['tmp_name'])) {
				$avatar_path = $this->handle_avatar_upload($user_id);
				if ($avatar_path) {
					$update = $this->conn->query("UPDATE users SET avatar = '$avatar_path' WHERE user_id = $user_id");
					if (!$update) {
						throw new Exception("Failed to update avatar path: " . $this->conn->error);
					}
				}
			}

			// Commit transaction
			$this->conn->commit();
			$this->conn->autocommit(TRUE);

			$resp['status'] = 'success';
			$resp['msg'] = 'Registration successful';
			$resp['user_id'] = $user_id;
		} catch (Exception $e) {
			$this->conn->rollback();
			$this->conn->autocommit(TRUE);
			$resp['msg'] = $e->getMessage();
		}

		return json_encode($resp);
	}

	public function save_user()
	{
		$resp = ['status' => 'failed', 'msg' => ''];

		try {
			// Validate required fields
			$required = ['name', 'email', 'username'];
			foreach ($required as $field) {
				if (empty($_POST[$field])) {
					throw new Exception(ucfirst($field) . " is required");
				}
			}

			// Password validation (only if provided or new user)
			if (empty($_POST['user_id']) && empty($_POST['password'])) {
				throw new Exception("Password is required for new users");
			}
			// if (!empty($_POST['password']) && $_POST['password'] !== $_POST['cpassword']) {
			// 	throw new Exception("Passwords do not match");
			// }

			// Handle avatar upload
			$avatar_path = null;
			if (!empty($_FILES['img']['tmp_name'])) {
				$avatar_path = $this->handle_avatar_upload();
			}

			// Start transaction
			$this->conn->begin_transaction();

			if (empty($_POST['user_id'])) {
				// CREATE NEW USER (using stored procedures)
				$role = isset($_POST['role']) ? $_POST['role'] : 'patient';
				$password = isset($_POST['password']) ? $_POST['password'] : ''; // Plain text

				if ($role == 'patient') {
					$stmt = $this->conn->prepare("CALL InsertPatient2(?, ?, ?, ?, ?, ?, ?, ?)");

					// Set defaults explicitly
					$date_of_birth = isset($_POST['date_of_birth']) ? $_POST['date_of_birth'] : '2000-01-01';
					$gender = isset($_POST['gender']) ? $_POST['gender'] : 'Male';
					$contact_number = isset($_POST['contact_number']) ? $_POST['contact_number'] : '';
					$avatar_param = $avatar_path ? $avatar_path : '';

					$stmt->bind_param(
						"ssssssss",
						$_POST['name'],
						$_POST['username'],
						$_POST['email'],
						$password,
						$date_of_birth,
						$gender,
						$contact_number,
						$avatar_param
					);
				} else { // doctor
					$stmt = $this->conn->prepare("CALL InsertDoctor2(?, ?, ?, ?, ?, ?, ?, ?)");

					// Set defaults explicitly
					$specialization = isset($_POST['specialization']) ? $_POST['specialization'] : '';
					$license_number = isset($_POST['license_number']) ? $_POST['license_number'] : '';
					$contact_number = isset($_POST['contact_number']) ? $_POST['contact_number'] : '';
					$avatar_param = $avatar_path ? $avatar_path : '';

					$stmt->bind_param(
						"ssssssss",
						$_POST['name'],
						$_POST['username'],
						$_POST['email'],
						$password,
						$specialization,
						$license_number,
						$contact_number,
						$avatar_param
					);
				}

				if (!$stmt->execute()) {
					throw new Exception("Failed to create user: " . $stmt->error);
				}
				$user_id = $this->conn->insert_id;
			} else {
				// UPDATE EXISTING USER (direct queries)
				$user_id = (int)$_POST['user_id'];

				// Get current data
				$current = $this->conn->query("SELECT avatar, role FROM users WHERE user_id = $user_id")->fetch_assoc();
				$old_avatar = $current['avatar'] ?? null;
				$role = $current['role'];

				// Build updates
				$updates = [
					"name = '" . $this->conn->real_escape_string($_POST['name']) . "'",
					"email = '" . $this->conn->real_escape_string($_POST['email']) . "'",
					"username = '" . $this->conn->real_escape_string($_POST['username']) . "'"
				];

				// Password update (only if provided)
				if (!empty($_POST['password'])) {
					// Since procedures handle hashing, we need to use SHA1() in direct update
					$updates[] = "password = SHA1('" . $this->conn->real_escape_string($_POST['password']) . "')";
				}

				// Avatar update
				if ($avatar_path) {
					$updates[] = "avatar = '" . $this->conn->real_escape_string($avatar_path) . "'";
					
					// Clean up old avatar if it exists
					if ($old_avatar && file_exists(base_app . $old_avatar)) {
						unlink(base_app . $old_avatar);
					}
					if ($this->settings->userdata('user_id') == $user_id) {
						$this->settings->set_userdata('avatar', $avatar_path);
					}
				}

				// Update users table
				$this->conn->query("UPDATE users SET " . implode(', ', $updates) . " WHERE user_id = $user_id");

				// Update role-specific table
				if ($role == 'patient') {
					$this->conn->query("UPDATE patients SET 
                    date_of_birth = '" . $this->conn->real_escape_string($_POST['date_of_birth'] ?? '2000-01-01') . "',
                    gender = '" . $this->conn->real_escape_string($_POST['gender'] ?? 'Male') . "',
                    contact_number = '" . $this->conn->real_escape_string($_POST['contact_number'] ?? '') . "'
                    WHERE patient_id = $user_id");
				} else { // doctor
					$this->conn->query("UPDATE doctors SET 
                    specialization = '" . $this->conn->real_escape_string($_POST['specialization'] ?? '') . "',
                    license_number = '" . $this->conn->real_escape_string($_POST['license_number'] ?? '') . "',
                    contact_number = '" . $this->conn->real_escape_string($_POST['contact_number'] ?? '') . "'
                    WHERE doctor_id = $user_id");
				}
			}

			$this->conn->commit();
			$resp['status'] = 'success';
			$resp['msg'] = empty($_POST['user_id']) ? 'User created successfully' : 'User updated successfully';
			$resp['msg'] = empty($_POST['user_id']) ? $this->settings->set_flashdata('success', 'User created successfully'): $this->settings->set_flashdata('success', 'User updated successfully');
		} catch (Exception $e) {
			$this->conn->rollback();

			// Clean up uploaded file if transaction failed
			if (!empty($avatar_path) && file_exists(base_app . $avatar_path)) {
				unlink(base_app . $avatar_path);
			}

			$resp['msg'] = $e->getMessage();
		}

		return json_encode($resp);
	}


	/**
	 * Function to handle avatar uploads
	 */
	private function upload_avatar($user_id)
	{
		if (!is_dir(base_app . "uploads/avatars")) {
			mkdir(base_app . "uploads/avatars", 0777, true);
		}
		$ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
		$fname = "uploads/avatars/$user_id.png";
		$accept = array('image/jpeg', 'image/png');

		if (!in_array($_FILES['img']['type'], $accept)) {
			return "Image file type is invalid";
		}

		if ($_FILES['img']['type'] == 'image/jpeg') {
			$uploadfile = imagecreatefromjpeg($_FILES['img']['tmp_name']);
		} elseif ($_FILES['img']['type'] == 'image/png') {
			$uploadfile = imagecreatefrompng($_FILES['img']['tmp_name']);
		}

		if (!$uploadfile) {
			return "Image is invalid";
		}

		$temp = imagescale($uploadfile, 200, 200);
		if (is_file(base_app . $fname)) {
			unlink(base_app . $fname);
		}
		$upload = imagepng($temp, base_app . $fname);

		if ($upload) {
			$this->conn->query("UPDATE `users` SET `avatar` = CONCAT('{$fname}', '?v=',unix_timestamp(CURRENT_TIMESTAMP)) WHERE user_id = '{$user_id}'");
			if ($this->settings->userdata('user_id') == $user_id) {
				$this->settings->set_userdata('avatar', $fname . "?v=" . time());
			}
		}

		imagedestroy($temp);
	}



	private function handle_avatar_upload()
	{
		$upload_dir = base_app . "uploads/avatars/";

		// Create directory if needed
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir, 0777, true);
		}

		// Validate file
		$allowed_types = ['image/jpeg', 'image/png'];
		$file_type = mime_content_type($_FILES['img']['tmp_name']);

		if (!in_array($file_type, $allowed_types)) {
			throw new Exception("Only JPG and PNG images are allowed");
		}

		if ($_FILES['img']['size'] > 2097152) { // 2MB
			throw new Exception("Image must be smaller than 2MB");
		}

		// Generate unique filename
		$ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
		$filename = "avatar_" . uniqid() . ".$ext";
		$filepath = $upload_dir . $filename;
		$relative_path = "uploads/avatars/$filename";

		// Move uploaded file (no processing to maintain original quality)
		if (!move_uploaded_file($_FILES['img']['tmp_name'], $filepath)) {
			throw new Exception("Failed to save uploaded file");
		}

		return $relative_path;
	}




	public function delete_users()
	{
		extract($_POST);
		$qry = $this->conn->query("DELETE FROM users WHERE user_id =  '{$id}'");
		if ($qry) {
			$this->settings->set_flashdata('success', 'User Details successfully deleted.');
			// Remove user's avatar if it exists
			$avatar_path = base_app . "uploads/avatars/$user_id.png";
			if (is_file($avatar_path)) {
				unlink($avatar_path);
			}
			return 1;
		} else {
			return false;
		}
	}


	
}

$users = new users();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
	case 'save':
		echo $users->save_user();
	break;
	case 'delete':
		echo $users->delete_users();
	break;
	// case 'registration':
	// 	echo $users->registration();
	// break;
	default:
		// echo $sysset->index();
		break;
}