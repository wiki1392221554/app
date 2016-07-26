<?php
include ('conn.php');//数据库连接
class Reg{
	//初始化用户名和密码
	public function __construct($mysqli,$username,$password,$phone){
		$this->username = $username;
		$this->password = $password;
		$this->phone = $phone;
		$this->mysqli = $mysqli;
	}
	//添加用户
	public function add(){
		//获取信息
		$username = $this->username;
		$password = $this->password;
		$phone = $this->phone;
		$password = self::HashPassword($password);
		$salt= $this->salt;//盐值
		$time = time();
		$sql_select = "select * from `user_baseinfo` where user_name = '$username' ";
		$result = $this->mysqli->query($sql_select);
		$count = $result->num_rows;
		if($count>0){
			return 0;//用户名，已存在返回0
		}
		
		$sql_select = "select * from `user_baseinfo` where phone_call = $phone";
		$result = $this->mysqli->query($sql_select);
		$count = $result->num_rows;
		if($count>0){
			return 3;//手机号，已存在返回0
		}

		$sql_insert = "insert into `user_baseinfo` (user_name,password,salt,phone_call,reg_time) values ('$username','$password','$salt','$phone',$time)";
		$this->mysqli->query($sql_insert);
		$user_id = $this->mysqli->insert_id;
		if($user_id){
			return $user_id;//注册成功返回用户id
		}else{
			return 2;//注册失败返回2
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
//主过程
$username = addslashes($_POST['username']);//用户名
$password = addslashes($_POST['password']);//密码
$phone = addslashes($_POST['phone']);//手机号
$Reg = new Reg($mysqli,$username,$password,$phone);
$return = $Reg->add();
$json = new Json();
switch($return){
	case 0:
		$json->response(false,'该用户名已被注册');
		break;
	case 2:
		$json->response(false,'用户注册失败');
		break;
	case 3:
		$json->response(false,'该手机号已存在');
		break;
	default:
		$json->response(true,'用户注册成功',array('userId'=>$return));//注册成功
}