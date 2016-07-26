<?php
header("Content-type:text/html;charset=utf-8");
require ('conn.php');//数据库连接

$user_id = $_POST['userId'];//用户id
$test_result = $_POST['bodyTest'];//体侧结果
$question_id = $_POST['questionId'];
$test_detail = $_POST['testDetail'];
//是否注册
$sql_select = "select * from `user_baseinfo` where userId = '$user_id' ";
$result = $mysqli->query($sql_select);
$count = $result->row_nums;
$json = new Json();
if(!$count){
	$json->response(false,'该用户名未注册');;//该用户未注册
	exit();
}
//是否测试过
$sql_select = "select *  from `body_quality_test` where userId = '$user_id' and  questionId = $question_id";
$result = $mysqli->query($sql_select );
$count = $result->row_nums;
if($count){
	$arr = $result->fetch_assoc();
	$test_id = $arr['test_id'];
	$sql_update = "update `body_quality_test` set testResult = '$test_result' where userId = '$user_id' AND  and  questionId = $question_id ";
	$result = $mysqli->query($sql_update);
	if($result){
		$data['test_id'] = $test_id;
		$json->response(true,'体质测试更新成功',$data);;//测试成功
		exit();
	}else{
		$json->response(flase,'体质测试失败');;//测试失败
		exit();
	}
}

//插入测试数据
$test_time = time();
$sql_insert = "insert into `body_quality_test` (userId,questionId,testResult,testDetail,testTime) values ('$user_id',$question_id,'$test_result','$test_detail','$test_time')";
$result = $mysqli->query($sql_insert);
if($result){
	$data['test_id'] = $mysqli->insert_id;
	$json->response(true,'体质测试成功',$data);;//测试成功
}else{
	$json->response(false,'体质测试失败');;//测试失败
}