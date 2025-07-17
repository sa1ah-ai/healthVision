<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function delete_img(){
		extract($_POST);
		if(is_file($path)){
			if(unlink($path)){
				$resp['status'] = 'success';
			}else{
				$resp['status'] = 'failed';
				$resp['error'] = 'failed to delete '.$path;
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = 'Unkown '.$path.' path';
		}
		return json_encode($resp);
	}


	function save_diseases(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = $this->conn->real_escape_string($v);
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `diseases` where `name` = '{$name}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Diseases Name already exists.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `diseases` set {$data} ";
		}else{
			$sql = "UPDATE `diseases` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$bid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = "New Diseases successfully saved.";
			else
				$resp['msg'] = " Diseases successfully updated.";
			
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}

	function save_source()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('source_id'))) {
				if (!empty($data)) $data .= ",";
				$v = $this->conn->real_escape_string($v);
				$data .= " `{$k}`='{$v}' ";
			}
		}

		$check = $this->conn->query("SELECT * FROM `sources` where `title` = '{$title}' " . (!empty($source_id) ? " and source_id != {$source_id} " : "") . " ")->num_rows;
		if ($this->capture_err())
			return $this->capture_err();
		if ($check > 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "Source Title already exists.";
			return json_encode($resp);
			exit;
		}
		if (empty($source_id)) {
			$sql = "INSERT INTO `sources` set {$data} ";
		} else {
			$sql = "UPDATE `sources` set {$data} where source_id = '{$source_id}' ";
		}
		$save = $this->conn->query($sql);

		if ($save) {
			$sid = !empty($source_id) ? $source_id : $this->conn->insert_id;
			$resp['sid'] = $sid;
			$resp['status'] = 'success';
			if (empty($source_id))
				$resp['msg'] = "New Source successfully saved.";
			else
				$resp['msg'] = "Source successfully updated.";
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}
		if ($resp['status'] == 'success')
		$this->settings->set_flashdata('success', $resp['msg']);
		return json_encode($resp);
	}

	function save_recommendation()
	{
		extract($_POST);
		$data = "";

		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('recommendation_id'))) {
				if (!empty($data)) $data .= ",";
				$v = $this->conn->real_escape_string($v);
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$sql = empty($recommendation_id) ? "INSERT INTO `recommendations` SET {$data}" : "UPDATE `recommendations` SET {$data} WHERE recommendation_id = '{$recommendation_id}'";
		error_log("SQL Query: " . $sql); // Log the SQL query

		// Execute the query and check for success
		$save = $this->conn->query($sql);
		if ($save) {
			// Handle success
			$resp['status'] = 'success';
			$resp['msg'] = empty($recommendation_id) ? "New recommendation successfully saved." : "Recommendation successfully updated.";
		} else {
			// Handle error
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . " [{$sql}]"; // Log the error
		}
		if ($resp['status'] == 'success')
		$this->settings->set_flashdata('success', $resp['msg']);

		return json_encode($resp);
	}


	// function save_review()
	// {
	// 	extract($_POST);
	// 	$data = "";
	// 	foreach ($_POST as $k => $v) {
	// 		if (!in_array($k, array('review_id'))) {
	// 			if (!empty($data)) $data .= ",";
	// 			$v = $this->conn->real_escape_string($v);
	// 			$data .= " `{$k}`='{$v}' ";
	// 		}
	// 	}

	// 	$check = $this->conn->query("SELECT * FROM `doctorreviews` where `doctor_id` = '{$user_id}' " . (!empty($review_id) ? " and review_id != {$review_id} " : "") . " ")->num_rows;
	// 	if ($this->capture_err())
	// 		return $this->capture_err();
	// 	if ($check > 0) {
	// 		$resp['status'] = 'failed';
	// 		$resp['msg'] = "Review Name already exists.";
	// 		return json_encode($resp);
	// 		exit;
	// 	}
	// 	if (empty($id)) {
	// 		$sql = "INSERT INTO `doctorreviews` set {$data} ";
	// 	} else {
	// 		$sql = "UPDATE `doctorreviews` set {$data} where review_id = '{$review_id}' ";
	// 	}
	// 	$save = $this->conn->query($sql);
	// 	if ($save) {
	// 		$bid = !empty($review_id) ? $review_id : $this->conn->insert_id;
	// 		$resp['status'] = 'success';
	// 		if (empty($review_id))
	// 			$resp['msg'] = "New Review successfully saved.";
	// 		else
	// 			$resp['msg'] = " Review successfully updated.";
	// 	} else {
	// 		$resp['status'] = 'failed';
	// 		$resp['err'] = $this->conn->error . "[{$sql}]";
	// 	}
	// 	if ($resp['status'] == 'success')
	// 	$this->settings->set_flashdata('success', $resp['msg']);
	// 	return json_encode($resp);
	// }

	function save_review()
	{
		extract($_POST);
		$resp = array('status' => 'failed', 'msg' => '');

		// Validate required fields
		if (empty($doctor_id) || empty($result_id) || empty($review_comments)) {
			$resp['msg'] = "All fields are required";
			return json_encode($resp);
		}

		// Check if doctor already reviewed this result
		$check = $this->conn->query("SELECT * FROM `doctorreviews` 
                                WHERE `doctor_id` = '{$doctor_id}' 
                                AND `result_id` = '{$result_id}' 
								AND `delete_flag` = 0")->num_rows;

		if ($check > 0) {
			$resp['msg'] = "You have already reviewed this result";
			return json_encode($resp);
		}

		// Prepare data
		$data = array(
			'doctor_id' => $this->conn->real_escape_string($doctor_id),
			'result_id' => $this->conn->real_escape_string($result_id),
			'review_comments' => $this->conn->real_escape_string($review_comments),
			'is_approved' => isset($is_approved) ? 1 : 0,
			'reviewed_at' => date('Y-m-d H:i:s')
		);

		// Build SQL
		$columns = implode(", ", array_keys($data));
		$values = "'" . implode("', '", array_values($data)) . "'";
		$sql = "INSERT INTO `doctorreviews` ($columns) VALUES ($values)";

		$save = $this->conn->query($sql);

		if ($save) {
			$resp['status'] = 'success';
			$resp['msg'] = "Review submitted successfully";

			// Update DiagnosticResults status if approved
			if (isset($is_approved) && $is_approved) {
				$this->conn->query("UPDATE `diagnosticresults` SET status = 'Reviewed' WHERE result_id = '{$result_id}'");
			}

			$this->settings->set_flashdata('success', $resp['msg']);
		} else {
			$resp['msg'] = "Failed to save review: " . $this->conn->error;
		}

		return json_encode($resp);
	}

	function delete_diseases(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `diseases` set `delete_flag` = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', " Disease successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}

	function delete_result()
	{
		extract($_POST);
		$del = $this->conn->query("UPDATE `diagnosticresults` set `delete_flag` = 1 where result_id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', " Result successfully deleted.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	function delete_review()
	{
		extract($_POST);
		$del = $this->conn->query("UPDATE `doctorreviews` set `delete_flag` = 1 where review_id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', " Review successfully deleted.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	function delete_recommendation()
	{
		extract($_POST);
		$del = $this->conn->query("UPDATE `recommendations` set `delete_flag` = 1 where recommendation_id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', " Recommendation successfully deleted.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	// function save_image()
	// {
	// 	// Initialize response
	// 	$resp = array('status' => 'failed', 'msg' => '');

	// 	try {
	// 		$user_id = $this->settings->userdata('user_id');
	// 		$image_id = isset($_POST['image_id']) ? intval($_POST['image_id']) : '';
			
	// 		// Handle file upload
	// 		$image_path = '';
	// 		if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == UPLOAD_ERR_OK) {
	// 			// Validate file
	// 			$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
	// 			$file_type = $_FILES['image_file']['type'];

	// 			if (!in_array($file_type, $allowed_types)) {
	// 				throw new Exception("Invalid file type. Only JPEG, PNG, and GIF are allowed.");
	// 			}

	// 			// Create upload directory if not exists
	// 			$upload_dir = base_app . 'uploads/images/';
	// 			if (!file_exists($upload_dir)) {
	// 				mkdir($upload_dir, 0777, true);
	// 			}

	// 			// Generate unique filename
	// 			$ext = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);
	// 			$filename = uniqid('img_') . '_' . $user_id . '.' . $ext;
	// 			$filename = $_FILES['image_file']['name'];
	// 			$image_path_stored = 'uploads/images/' . $filename;
	// 			$image_path_moved = $upload_dir . $filename;

	// 			if (move_uploaded_file($_FILES['image_file']['tmp_name'], $image_path_moved)) {
	// 				$image_path = $image_path_stored;
	// 			} else {
	// 				throw new Exception("Failed to move uploaded file.");
	// 			}

	// 		}

	// 		$data = [
	// 			'user_id' => $user_id,
	// 			'title' => $this->conn->real_escape_string($_POST['title']),
	// 			'image_type' => $this->conn->real_escape_string($_POST['image_type'])
	// 		];

	// 		// Only update image_path if a new file was uploaded
	// 		if (!empty($image_path)) {
	// 			$data['image_path'] = $image_path;
	// 		}

	// 		if ($image_id == 0) {
	// 			$columns = implode(", ", array_keys($data));
	// 			$values = "'" . implode("', '", array_values($data)) . "'";
	// 			$sql = "INSERT INTO `medicalimages` ($columns) VALUES ($values)";
	// 		}
	// 		else {
	// 			// Update existing record
	// 			$updates = [];
	// 			foreach ($data as $k => $v) {
	// 				$updates[] = "`$k` = '$v'";
	// 			}
	// 			$sql = "UPDATE `medicalimages` SET " . implode(", ", $updates) . " WHERE image_id = $image_id AND user_id = $user_id";
	// 		}

	// 		// Execute query
	// 		$save = $this->conn->query($sql);
	// 		if (!$save) {
	// 			throw new Exception("Database error: " . $this->conn->error);
	// 		}

	// 		$pid = $image_id ?: $this->conn->insert_id;
	// 		$resp['status'] = 'success';
	// 		$resp['pid'] = $pid;
	// 		$resp['msg'] = $image_id ? "Image updated successfully" : "Image saved successfully";
	// 	} catch (Exception $e) {
	// 		$resp['msg'] = $e->getMessage();
	// 	}

	// 	// Clean up if failed and file was uploaded
	// 	if ($resp['status'] != 'success' && !empty($image_path) && file_exists($image_path)) {
	// 		unlink($image_path);
	// 	}

	// 	if ($resp['status'] == 'success') {
	// 		$this->settings->set_flashdata('success', $resp['msg']);
	// 	}

	// 	return json_encode($resp);
	// }

	function save_image()
	{
		$resp = array('status' => 'failed', 'msg' => '');

		try {
			$user_id = $this->settings->userdata('user_id');
			$image_id = isset($_POST['image_id']) ? intval($_POST['image_id']) : 0;
			$status = isset($_POST['status']) ? intval($_POST['status']) : 0;

			// Handle file upload (your existing code)
			$image_path = '';
			if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == UPLOAD_ERR_OK) {
				// Validate file
				$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
				$file_type = $_FILES['image_file']['type'];

				if (!in_array($file_type, $allowed_types)) {
					throw new Exception("Invalid file type. Only JPEG, PNG, and GIF are allowed.");
				}

				// Create upload directory if not exists
				$upload_dir = base_app . 'uploads/images/';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir, 0777, true);
				}

				// Generate unique filename
				$ext = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);
				$filename = uniqid('img_') . '_' . $user_id . '.' . $ext;
				$filename = $_FILES['image_file']['name'];
				$image_path_stored = 'uploads/images/' . $filename;
				$image_path_moved = $upload_dir . $filename;

				if (move_uploaded_file($_FILES['image_file']['tmp_name'], $image_path_moved)) {
					$image_path = $image_path_stored;
				} else {
					throw new Exception("Failed to move uploaded file.");
				}

			}

			// Prepare data for database
			$data = [
				'user_id' => $user_id,
				'title' => $this->conn->real_escape_string($_POST['title']),
				'image_type' => $this->conn->real_escape_string($_POST['image_type']),
				'status' => $status
			];

			if (!empty($image_path)) {
				$data['image_path'] = $image_path;
			}

			// Build SQL query
			if ($image_id == 0) {
				// Insert new record
				$columns = implode(", ", array_keys($data));
				$values = "'" . implode("', '", array_values($data)) . "'";
				$sql = "INSERT INTO `medicalimages` ($columns) VALUES ($values)";
			} else {
				// Update existing record
				$updates = [];
				foreach ($data as $k => $v) {
					$updates[] = "`$k` = '$v'";
				}
				$sql = "UPDATE `medicalimages` SET " . implode(", ", $updates) . " WHERE image_id = $image_id AND user_id = $user_id";
			}

			// Execute query
			$save = $this->conn->query($sql);
			if (!$save) {
				throw new Exception("Database error: " . $this->conn->error);
			}

			$pid = $image_id ?: $this->conn->insert_id;
			$resp['status'] = 'success';
			$resp['pid'] = $pid;
			$resp['msg'] = $image_id ? "Image updated successfully" : "Image saved successfully";

			// Call Python analyzer if status is 1
			if ($status == 1) {
				$this->analyze_image($pid);
			}
		} catch (Exception $e) {
			$resp['msg'] = $e->getMessage();
		}

		// Clean up if failed (your existing code)
		if ($resp['status'] != 'success' && !empty($image_path) && file_exists($image_path)) {
			unlink($image_path);
		}

		if ($resp['status'] == 'success') {
			$this->settings->set_flashdata('success', $resp['msg']);
		}

		return json_encode($resp);
	}

	function analyze_image($image_id)
	{
		$pythonInterpreter = "C:\Users\ph\AppData\Local\Programs\Python\Python311\python.exe";
		$python_script = "C:\\xampp\\\htdocs\\healthVision\\ai\\py_runner.py";

		// Validate Python script exists
		if (!file_exists($python_script)) {
			error_log("Python script not found: $python_script");
			return false;
		}
		// Log the execution (optional)
		error_log("Started image analysis for ID: $image_id");

		$command = escapeshellcmd($pythonInterpreter . " " . $python_script . " " . escapeshellarg($image_id)) . " 2>&1";
		exec($command, $output, $resultCode);
		// var_dump($output);

		// return true;
	}

	function delete_image()
	{
		extract($_POST);
		$del = $this->conn->query("UPDATE `medicalimages` set `delete_flag` = 1 where image_id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', " Image successfully deleted.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	
	function delete_post(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `post_list` set `delete_flag` = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Post successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}

	

	function save_comment(){
		if(empty($_POST['id'])){
			$_POST['user_id'] = $this->settings->userdata('id');
		}
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = $this->conn->real_escape_string($v);
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `comment_list` set {$data} ";
		}else{
			$sql = "UPDATE `comment_list` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$pid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = "New Comment successfully added.";
			else
				$resp['msg'] = " Comment successfully updated.";
			
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}
	function delete_comment(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `comment_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Comment successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}


	function update_status(){
		extract($_POST);
		$update = $this->conn->query("UPDATE `transaction_list` set `status` = '{$status}' where id = '{$id}'");
		if($update){
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "Transaction's status has failed to update.";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success', 'Transaction\'s Status has been updated successfully.');
		return json_encode($resp);
	}
}


$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_diseases':
		echo $Master->save_diseases();
	break;
	case 'save_review':
		echo $Master->save_review();
	break;
	case 'save_recommendation':
		echo $Master->save_recommendation();
		break;
	case 'save_source':
		echo $Master->save_source();
	break;
	case 'save_image':
		echo $Master->save_image();
		break;
	case 'save_comment':
		echo $Master->save_comment();
	break;

	case 'delete_img':
		echo $Master->delete_img();
	break;
	case 'delete_diseases':
		echo $Master->delete_diseases();
	break;
	case 'delete_result':
		echo $Master->delete_result();
	break;
	case 'delete_review':
		echo $Master->delete_review();
	break;
	case 'delete_recommendation':
		echo $Master->delete_recommendation();
	break;

	case 'delete_post':
		echo $Master->delete_post();
	break;

	case 'delete_comment':
		echo $Master->delete_comment();
	break;
	case 'delete_image':
		echo $Master->delete_image();
		break;
	
	case 'update_status':
		echo $Master->update_status();
	break;
	default:
		// echo $sysset->index();
		break;
}

// function save_image()
// {
// 	// Initialize response
// 	$resp = array('status' => 'failed', 'msg' => '');

// 	try {
// 		$user_id = $this->settings->userdata('user_id');
// 		$image_id = isset($_POST['image_id']) ? intval($_POST['image_id']) : '';

// 		// Handle file upload
// 		$image_path = '';
// 		if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == UPLOAD_ERR_OK) {
// 			// Validate file
// 			$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
// 			$file_type = $_FILES['image_file']['type'];

// 			if (!in_array($file_type, $allowed_types)) {
// 				throw new Exception("Invalid file type. Only JPEG, PNG, and GIF are allowed.");
// 			}

// 			// Create upload directory if not exists
// 			$upload_dir = base_app . 'uploads/images/';
// 			if (!file_exists($upload_dir)) {
// 				mkdir($upload_dir, 0777, true);
// 			}

// 			// Generate unique filename
// 			$ext = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);
// 			$filename = uniqid('img_') . '_' . $user_id . '.' . $ext;
// 			$filename = $_FILES['image_file']['name'];
// 			$image_path_stored = 'uploads/images/' . $filename;
// 			$image_path_moved = $upload_dir . $filename;

// 			if (move_uploaded_file($_FILES['image_file']['tmp_name'], $image_path_moved)) {
// 				$image_path = $image_path_stored;
// 			} else {
// 				throw new Exception("Failed to move uploaded file.");
// 			}
// 		}

// 		// Prepare data for database
// 		// $image_type = $_POST['image_type'];
// 		// $title = $_POST['title'];
// 		$data = [
// 			'user_id' => $user_id,
// 			'title' => $this->conn->real_escape_string($_POST['title']),
// 			'image_type' => $this->conn->real_escape_string($_POST['image_type'])
// 		];

// 		// Only update image_path if a new file was uploaded
// 		if (!empty($image_path)) {
// 			$data['image_path'] = $image_path;
// 		}

// 		// Build SQL query

// 		// if (empty($image_id)) {
// 		// 	$columns = implode(", ", array_keys($data));
// 		// 	$values = "'" . implode("', '", array_values($data)) . "'";
// 		// 	$sql = "INSERT INTO `medicalimages` ($columns) VALUES ($values)";
// 		// }
// 		if ($image_id == 0) {
// 			$columns = implode(", ", array_keys($data));
// 			$values = "'" . implode("', '", array_values($data)) . "'";
// 			$sql = "INSERT INTO `medicalimages` ($columns) VALUES ($values)";
// 			// $sql = "INSERT INTO `medicalimages`(`user_id`, `image_type`,`title`, `image_path`) 
// 			// 			VALUES ('$user_id','$image_type','$title',' $image_path')";
// 		} else {
// 			// Update existing record
// 			$updates = [];
// 			foreach ($data as $k => $v) {
// 				$updates[] = "`$k` = '$v'";
// 			}
// 			$sql = "UPDATE `medicalimages` SET " . implode(", ", $updates) . " WHERE image_id = $image_id AND user_id = $user_id";
// 		}

// 		// Execute query
// 		$save = $this->conn->query($sql);
// 		if (!$save) {
// 			throw new Exception("Database error: " . $this->conn->error);
// 		}

// 		$pid = $image_id ?: $this->conn->insert_id;
// 		$resp['status'] = 'success';
// 		$resp['pid'] = $pid;
// 		$resp['msg'] = $image_id ? "Image updated successfully" : "Image saved successfully";
// 	} catch (Exception $e) {
// 		$resp['msg'] = $e->getMessage();
// 	}

// 	// Clean up if failed and file was uploaded
// 	if ($resp['status'] != 'success' && !empty($image_path) && file_exists($image_path)) {
// 		unlink($image_path);
// 	}

// 	if ($resp['status'] == 'success') {
// 		$this->settings->set_flashdata('success', $resp['msg']);
// 	}

// 	return json_encode($resp);
// }