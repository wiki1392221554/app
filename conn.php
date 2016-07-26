<?php
/*这个类负责连接和实例化数据库
 *
 */
require('apiJson.php');
//单例模式下连接数据库
class Db {
	static private $_instance;//保存实例化的类
	static private $_connectSource;//保存数据库连接资源

	//数据库配置
	private $_dbConfig = array(
		'host' => '121.42.156.244',//数据库地址
		'user' => 'htj',//数据库用户名
		'password' => '2010collect',//数据库密码
		'database' => 'image',//数据库名称
	);

	//定义为私有，防止被外部实例化
	private function __construct() {

	}

	/*实例化类
	 *@return object
	 */
	static public function getInstance() {
		if(!(self::$_instance instanceof self)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/*连接数据库
	 *return object
	 */
	public function connect() {
		if(!self::$_connectSource) {
			self::$_connectSource = new mysqli($this->_dbConfig['host'], $this->_dbConfig['user'], $this->_dbConfig['password'],$this->_dbConfig['database']);

			//只能用函数来判断是否连接成功
			if(mysqli_connect_errno())
			{
				throw new Exception('database connect error ' . mysqli_connect_error());
			}
			
			//mysqli_select_db($this->_dbConfig['database'], self::$_connectSource);
			self::$_connectSource->set_charset("UTF8");
		}
		return self::$_connectSource;
	}
}

//引入该文件后，直接实例化
try{
    $mysqli = Db::getInstance()->connect();
}catch(Exception $e){
	Json::response(false,"数据库连接失败");
	exit();
}
