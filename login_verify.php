<?php
include ('conn.php');
class Login{
	function __construct($mysqli,$username,$password){
		$this->username = $username;//用户名
		$this->password = $password;//密码
		$this->mysqli   = $mysqli;
	}
	public function verify(){
		$username = $this->username;
		$password = $this->password;
		//查找是否存在该用户
		$sql_select = "select * from `user_baseinfo` where user_name = '$username' ";
		$result = $this->mysqli->query($sql_select);
		$count = $result->num_rows;
		if(!$count){
			return 0;//不存在返回0
		}
		$data = $result->fetch_array(MYSQLI_ASSOC);
		$truePwd = $data['password'];
		$salt = $data['salt'];
		$userId = $data['userId'];
		$hashPwd = hash("sha256", $password. $salt);
		if($hashPwd == $truePwd){
			return $userId;//密码和手机号正确，返回用户id
		}else{
			return 2;//密码错误
		}
	}
	
	//密码加密
	private function HashPassword($password){
		$interSalt =md5(uniqid(rand(),true));
		$salt = substr($interSalt,0,6);
		$this->salt = $salt;
		return hash('sha256',$password.$salt);
	}
}
$username = $_POST['userName'];
$password = $_POST['password'];
$Login = new Login($username,$password);
$return = $Login->verify();
$json = new Json();
switch($return){
	case 0:
		$json->response(false,'该用户名或者密码错误');
		break;
	case 2:
		$json->response(false,'该用户名或者密码错误');
		break;
	default:
		$json->response(true,'登录成功',array('userId'=>$return));
}