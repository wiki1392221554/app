<?php
/**
 * 这个类负责输出json数据的封装
 * @param boolean $bool
 * @param string  $message
 * @param  array  $data
 * @return null
 */
Class Json{
	//响应
	static function response($bool,$message,$data=null){
		if($data){
			$response = array('success'=>$bool,'message'=>$message,'data'=>$data);//有数据
		}else{
			$response = array('success'=>$bool,'message'=>$message);//无数据
		}	
		$return = json_encode($response,JSON_UNESCAPED_UNICODE);
		echo $return;
	}
}