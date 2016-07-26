<?php
include ('conn.php');//数据库连接
$user_id = $_POST['user_id'];
$json = new Json();
class User{
	public function __construct($mysqli,$user_id){
		$this->user_id = $user_id;
		$this->mysqli = $mysqli;
	}
	//获取基本信息
	public function get_baseinfo(){
		$user_id = $this->user_id;
		$sql_select = "select phone_call,user_name  from  `user_baseinfo` where userId = '$user_id'";
		$result = $this->mysqli->query($sql_select);
		$count = $result->row_nums($result);
		if(!count){
			return 0;//未注册，返回0
		}
		$arr = $result->fetch_array(MYSQLI_ASSOC);
		$data['username'] = $arr['user_name'];
		$data['phone'] = $arr['phone_call'];
		return $data;
	}
	//获取体质测试信息
	public function get_test_result(){

		$user_id = $this->user_id;
		//查询是否注册
		$sql_select = "select phone_call  from  `user_baseinfo` where userId = '$user_id'";
		$result = $this->mysqli->query($sql_select);
		$count = $result->row_nums;
		if(!$count){
			return 0;//未注册，返回0
		}
		$sql_select = "select test_result  from  `body_quality_test` where userId = '$user_id'";
		$result = $this->mysqli->query($sql_select);
		$count = $result->row_nums;
		if(!$count){
			return 1;//未测试
		}
		$arr = $result->fetch_array(MYSQLI_ASSOC);
		$data['test_result'] = $arr['test_result'];
		return $data;
	}
}

$user = new User($mysqli,$user_id);
$get_type = $_POST['get_type'];
//确定要获取的数据
if($get_type == 'base_info'){
	$data = $user->get_baseinfo();
}elseif($get_type == 'test_result'){
	$data = $user->get_test_result();
}else{
	$data = 0;
}

switch($data){
	case 0:
		$json->response(false,'该用户尚未注册');
		break;
	case 1:
		$json->response(false,'该用户尚未进行体质测试');
		break;
	default:
		$json->response(true,'用户信息获取成功',$data);
}