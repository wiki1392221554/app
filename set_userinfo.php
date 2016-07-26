<?php
include ('conn.php');//数据库连接

class User{
	public function __construct($mysqli,$userId){

		//检查是否该用户是否存在
		$sql_select = "select user_name from `user_baseinfo`  where userId = '$userId'";
		$result = $this->mysqli->query($sql_select);
		$count = $result->row_nums;
		if(!$count){
			return false;
		}
		$this->userId = $userId;
		$this->mysqli = $mysqli;
	}

	//设置手机号
	public function setPhone($phone){

		$userId = $this->userId;
		$sql_update = "update `user_baseinfo` set phone_call = $phone where userId = '$userId'";
		$result = $this->mysqli->query($sql_update);
		if($result){
			return 1;//更新成功
		}else{
			return 2;//更新失败
		}
	}

	//重新设置密码
	public function setPassword($password){
		$userId = $this->userId;
		$password = self::HashPassword($password);
		$salt= $this->salt;//盐值
		
		$sql_update = "update `user_baseinfo` set password = '$password',salt = '$salt' where userId = '$userId'";
		$result = $this->mysqli->query($sql_update);
		if($result){
			return 1;//密码更新成功
		}else{
			return 2;//更新失败
		}
	}

	//设置基本信息，如年龄，性别，体质情况
	public function setBaseInfo($age,$sex,$body_quality){
		$userId = $this->userId;
		$sql_update = "update `user_baseinfo` set age = $age,sex = '$sex',bodyQuality = '$body_quality' where userId = '$userId'";
		$result = $this->mysqli->query($sql_update);
		if($result){
			return 1;//更新成功
		}else{
			return 2;//更新失败
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
$userId = $_POST['userId'];
$phone = $_POST['phone'];
$password = $_POST['pwd'];
$sex = $_POST['sex'];
$age = $_POST['age'];
$body_quality = $_POST['bodyQuality'];
$json = new Json();

$user = new User($mysqli,$userId);
//分发请求
if($phone){
	$res = $user->setPhone($phone);
}elseif($password){
	$res = $user->setPassword($password);
}elseif($age && $sex){
	$res = $user->setBaseInfo($age,$sex,$body_quality);
}else{
	$res = 2;
}

switch($res){
		case 1:
		$json->response(true,'设置成功');//更新成功
		break;
	case 0:
		$json->response(false,'该用户尚未注册');//用户名未注册
		break;
	case 2:
		$json->response(false,'设置失败');//设置失败
		break;
	default:
		$json->response(false,'系统繁忙');
}