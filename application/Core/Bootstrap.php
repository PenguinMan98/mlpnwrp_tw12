<?php
// start server session
session_start();

//ini_set( 'error_reporting' , E_ALL & ~E_DEPRECATED);
//ini_set( 'display_errors' , 'On');

date_default_timezone_set('MST');

$loggedIn = !empty($_SESSION['u_info']['login']);
$userName = ($loggedIn) ? $_SESSION['u_info']['login'] : 'Anonymous';

if($loggedIn && !isset($_SESSION['u_info']['id'])){
	$tw_root_path = '/var/www/public/';
	
	//echo getcwd()." =? (".($tw_root_path == getcwd()).")<br>";
	$myDir = getcwd();
	chdir($tw_root_path);
	include($tw_root_path . 'tiki-setup.php');
	chdir($myDir);
}

$userId = ($loggedIn) ? $_SESSION['u_info']['id'] : false;

/*echo "checking paths.<br>";
echo getcwd() . "<br>";
$d = dir("/var/www/");
echo "Handle: " . $d->handle . "<br>";
echo "Path: " . $d->path . "<br>";
while (false !== ($entry = $d->read())) {
	echo $entry."<br>";
}
$d->close();*/

class Bootstrap{
	private $user = null;
	/**
	 * Call this method to get singleton
	 *
	 * @return UserFactory
	 */
	public static function getInstance()
	{
		static $inst = null;
		if ($inst === null) {
			$inst = new Bootstrap();
		}
		return $inst;
	}
	
	/**
	 * Private ctor so nobody else can instance it
	 *
	 */
	private function __construct()
	{
		$this->setPathing();
		$this->startAutoloader();
		$this->loadDatabase();
		
		GLOBAL $userId;
		
		// set up permissions
		if(!$userId){
			unset($_SESSION['mlpnwrp']['is_guide']);
		}elseif(!isset($_SESSION['mlpnwrp']['is_guide'])){
			$uugProvider = new Model_Data_UsersUsergroupsProvider(); // my own class for checking tiki permissions
			$_SESSION['mlpnwrp']['is_guide'] = $uugProvider->isGuide($userId);
		}
	}
	
	public function setPathing(){
		define('SERVER_ROOT' , '/var/www');
		define('APP_ROOT' , SERVER_ROOT . '/application/');
		define('CORE_ROOT' , APP_ROOT . 'Core/');
		define('MODEL_ROOT' , APP_ROOT . 'Model/');
		define('VIEW_ROOT' , APP_ROOT . 'View/');
		define('CONTROLLER_ROOT' , APP_ROOT . 'Controller/');
		define('LIBRARY_ROOT' , APP_ROOT . 'Library/');
		
		define('PUBLIC_ROOT' , SERVER_ROOT . '/public'); // use this for PHP includes
		define('SITE_ROOT' , '');
	}
	
	public function startAutoloader(){
		// start the autoloader
		spl_autoload_register(function ($className){
			$className = str_replace("_", "/", $className);
			if(strpos($className, "Controller") !== false){
				if(file_exists(CONTROLLER_ROOT . $className . ".php")){ // check the controllers
					include CONTROLLER_ROOT . $className . ".php";
				}
			}elseif(file_exists(MODEL_ROOT . $className . ".php")){ // check the models
				include MODEL_ROOT . $className . ".php";
			}elseif(file_exists(LIBRARY_ROOT . $className . ".php")){ // check the library
				include LIBRARY_ROOT . $className . ".php";
			}elseif(file_exists(CORE_ROOT . $className . ".php")){ // check the library
				include CORE_ROOT . $className . ".php";
			}
		});
	}
	
	public function loadDatabase(){
		// include the databasing stuff
		require_once(CORE_ROOT . "DAO.php");
		include(CORE_ROOT . "Config.php");
		//include(CORE_ROOT . "DbCn.php");
		DbCn::getInstance(array(
			'dsn'=>'mysql:dbname=' . Core_Config::$DB_DATABASE . ';host=' . Core_Config::$DB_SERVER,
			'user' => Core_Config::$DB_USERNAME,
			'password' => Core_Config::$DB_PASSWORD,
			'driver_options' => array(
			PDO::MYSQL_ATTR_INIT_COMMAND =>  "SET NAMES utf8"
				)));
		
	}
}

// useful functions 

function getImage($type, $characterId){
	GLOBAL $character;
	$path = PUBLIC_ROOT . "/img/".$characterId."/";
	$extArr = array(".jpg",".gif",".png");
	foreach($extArr as $ext){
		$img = $path . $type . $ext;
		//echo "looking for $img (".(file_exists($img)).")<br>";
		if(file_exists($img)){
			return $type . $ext;
		}
	}
	return false;
}

