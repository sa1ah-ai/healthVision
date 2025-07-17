<?php
// require_once '../config.php';
// class Login extends DBConnection {
// 	private $settings;
// 	public function __construct(){
// 		global $_settings;
// 		$this->settings = $_settings;

// 		parent::__construct();
// 		ini_set('display_error', 1);
// 	}
// 	public function __destruct(){
// 		parent::__destruct();
// 	}
// 	public function index(){
// 		echo "<h1>Access Denied</h1> <a href='".base_url."'>Go Back.</a>";
// 	}
// 	public function login(){
// 		extract($_POST);

// 		$stmt = $this->conn->prepare("SELECT * from users where username = ? and password = ? ");
// 		$password = md5($password);
// 		$stmt->bind_param('ss',$username,$password);
// 		$stmt->execute();
// 		$result = $stmt->get_result();
// 		if($result->num_rows > 0){
// 			foreach($result->fetch_array() as $k => $v){
// 				if(!is_numeric($k) && $k != 'password'){
// 					$this->settings->set_userdata($k,$v);
// 				}

// 			}
// 			$this->settings->set_userdata('login_type','doctor');
// 		return json_encode(array('status'=>'success'));
// 		}else{
// 		return json_encode(array('status'=>'incorrect','last_qry'=>"SELECT * from users where username = '$username' and password = md5('$password') "));
// 		}
// 	}
// 	public function logout(){
// 		if($this->settings->sess_des()){
// 			redirect('admin/login.php');
// 		}
// 	}
// 	function login_user(){
// 		extract($_POST);
// 		$stmt = $this->conn->prepare("SELECT * from users where username = ? and `password` = ? and `role` = 'patient' ");
// 		$password = md5($password);
// 		$stmt->bind_param('ss',$username,$password);
// 		$stmt->execute();
// 		$result = $stmt->get_result();
// 		if($result->num_rows > 0){
// 			$res = $result->fetch_array();
// 			foreach($res as $k => $v){
// 				$this->settings->set_userdata($k,$v);
// 			}
// 			$this->settings->set_userdata('login_type','patient');
// 			$resp['status'] = 'success';
// 		}else{
// 		$resp['status'] = 'failed';
// 		$resp['msg'] = 'Incorrect Email or Password';
// 		}
// 		if($this->conn->error){
// 			$resp['status'] = 'failed';
// 			$resp['_error'] = $this->conn->error;
// 		}
// 		return json_encode($resp);
// 	}
// 	public function logout_user(){
// 		if($this->settings->sess_des()){
// 			redirect('index.php');
// 		}
// 	}
// }
// $action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
// $auth = new Login();
// switch ($action) {
// 	case 'login':
// 		echo $auth->login();
// 		break;
// 	case 'logout':
// 		echo $auth->logout();
// 		break;
// 	case 'login_user':
// 		echo $auth->login_user();
// 		break;
// 	case 'logout_user':
// 		echo $auth->logout_user();
// 		break;
// 	default:
// 		echo $auth->index();
// 		break;
// }

require_once '../config.php';

class Login extends DBConnection {
    private $settings;

    public function __construct(){
        global $_settings;
        $this->settings = $_settings;
        parent::__construct();
        ini_set('display_errors', 1);
    }

    public function __destruct(){
        parent::__destruct();
    }

    public function index(){
        echo "<h1>Access Denied</h1> <a href='".base_url."'>Go Back.</a>";
    }

    public function login(){
        extract($_POST);
        // $password = hash('sha256', $password); // More secure than md5

        $stmt = $this->conn->prepare("CALL UserLogin(?, ?)");
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $user = $result->fetch_assoc();
            foreach($user as $k => $v){
                if($k !== 'password'){
                    $this->settings->set_userdata($k, $v);
                }
            }
            $this->settings->set_userdata('login_type', 'doctor'); // Adjust as needed
            return json_encode(array('status' => 'success'));
        } else {
            return json_encode(array('status' => 'incorrect', 'msg' => "Incorrect username or password."));
        }
    }

    public function logout(){
        if($this->settings->sess_des()){
            redirect('admin/login.php');
        }
    }

    public function login_user(){
        extract($_POST);
        $stmt = $this->conn->prepare("CALL UserLogin(?, ?)");
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $user = $result->fetch_assoc();
            if($user['role'] === 'patient'){
                foreach($user as $k => $v){
                    if($k !== 'password'){
                        $this->settings->set_userdata($k, $v);
                    }
                }
                $this->settings->set_userdata('login_type', 'patient');
                return json_encode(array('status' => 'success'));
            } else {
                return json_encode(array('status' => 'failed', 'msg' => 'Access denied.'));
            }
        } else {
            return json_encode(array('status' => 'failed', 'msg' => 'Incorrect Email or Password'));
        }
    }

    public function logout_user(){
        if($this->settings->sess_des()){
            redirect('index.php');
        }
    }
}

$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$auth = new Login();

switch ($action) {
    case 'login':
        echo $auth->login();
        break;
    case 'logout':
        echo $auth->logout();
        break;
    case 'login_user':
        echo $auth->login_user();
        break;
    case 'logout_user':
        echo $auth->logout_user();
        break;
    default:
        echo $auth->index();
        break;
}
