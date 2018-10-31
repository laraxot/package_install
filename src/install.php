<?php

//Use the Composer classes

use Composer\Console\Application;
use Composer\Command\UpdateCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\HttpFoundation\Response;


new zeroInstall();

class zeroInstall{
	public $step=0;
	public function zeroInstall(){
		if (isset($_GET['step'])) {
			$this->step=$_GET['step'];
		}
		$act='step'.$this->step;
		echo $this->nextlink();
		$this->$act();
		//echo $this->nextlink();
	}//end construct
	public function url(){
		return '?step='.($this->step+1);
	}
	public function nextlink()
	{
		return '<a href="'.$this->url().'">Step '.($this->step+1).'</a>';
	}

	public function moveFilesAndFolder($source, $destination){
		$files = scandir($source);
		foreach ($files as $file) {
			if (in_array($file, array(".",".."))) {
				continue;
			}
			// If we copied this successfully, mark it for deletion
			if (is_dir($source.'/'.$file)) {
				if(!is_dir($destination.'/'.$file)){
					mkdir($destination.'/'.$file);
				}
				$this->moveFilesAndFolder($source.'/'.$file, $destination.'/'.$file);
			} else {
				if (copy($source.'/'.$file, $destination.'/'.$file)) {
					$delete[] = $source.$file;
				}
			}
		}
		echo '<h3>+Done</h3>';
	}


	public function bower($args){
		echo '<pre>
		  ____                         
		 |  _ \                        
		 | |_) | _____      _____ _ __ 
		 |  _ < / _ \ \ /\ / / _ \  __|
		 | |_) | (_) \ V  V /  __/ |   
		 |____/ \___/ \_/\_/ \___|_| update   
		</pre>';

		define('ROOT_DIR', realpath('../laravel'));
		//echo '<br> ROOT_DIR :'.ROOT_DIR; //die('['.__LINE__.']['.__FILE__.']');
		define('EXTRACT_DIRECTORY', ROOT_DIR. '/bower');
		define('HOME_DIRECTORY', ROOT_DIR. '/bower/home');
		define('COMPOSER_INITED', file_exists(ROOT_DIR.'/vendor'));
		error_reporting(E_ALL);
		ini_set('display_errors',1);
		set_time_limit(10000);
		ini_set('memory_limit', -1);

		//if (!getenv('HOME') && !getenv('COMPOSER_HOME')) {
		    putenv("COMPOSER_HOME=".HOME_DIRECTORY);
		    putenv("HOME=".HOME_DIRECTORY);
		//}

		/*
		//--------Extracting composer library
		echo '<br/>AUTOLOAD : '.EXTRACT_DIRECTORY.'/vendor/autoload.php'; //die('<hr/>['.__LINE__.']['.__FILE__.']');
		if (file_exists(EXTRACT_DIRECTORY.'/vendor/autoload.php') == true) {
		    echo "<hr/>Extracted autoload already exists. Skipping phar extraction as presumably it's already extracted.\n";
		}
		else{
		    $composerPhar = new Phar(ROOT_DIR."/bowerphp.phar");
		    //php.ini set phar.readonly=0
		    $composerPhar->extractTo(EXTRACT_DIRECTORY);
		}
		*/
		//--------------running Composer Command
		// change directory to root
		chdir(ROOT_DIR);

		//This requires the phar to have been extracted successfully.
		require_once(ROOT_DIR.'/vendor/autoload.php');

		//Use the Composer classes
		//use Bowerphp\Console\Application;
		//use Bowerphp\Command\UpdateCommand;
		//use Symfony\Component\Console\Input\ArrayInput;
		//use Symfony\Component\Console\Output\StreamOutput;
		//use Symfony\Component\Console\Output\ConsoleOutput;
		//use Symfony\Component\HttpFoundation\Response;
		$stream = fopen('php://temp', 'w+');
		$fp = tmpfile();
		//$output = new StreamOutput($stream);
		$output = new StreamOutput($fp);

		//$output = new ConsoleOutput();

		//Create the commands
		
		//$cmd=$_GET['cmd'];
		//$args=['command'=>$cmd];
		/*
		$cmd=$args['cmd'];
		switch ($cmd) {
		    case 'install':
		        $args['--save']=true;
		        //$args['package']=$_GET['pack'];
		    break;
		}
		$args['command']=$args['cmd'];
		unset($args['cmd']);
		*/
		$args['--save']=true;
		//$args = array('command' => 'update');
		//$args= array('command' => 'install','package'=> 'BlackrockDigital/startbootstrap-sb-admin-2','--save'=>true);
		//dd($args);
		//$args= array('command' => 'list');
		if (!COMPOSER_INITED) {
		    echo "This is first composer run: --no-scripts option is applies\n";
		    $args['--no-scripts']=true;
		}
		
		$input = new ArrayInput($args);
		//echo '<hr>['.__LINE__.']<pre>';print_r($input);echo '</pre>';die();

		//Create the application and run it with the commands
		$application = new Bowerphp\Console\Application();
		$application->setAutoExit(false);
		$application->setCatchExceptions(false);
		try {
		    //Running commdand php.ini allow_url_fopen=1 && proc_open() function available
		    $application->run($input, $output);
		    echo '<br/>Success';
		} catch (\Exception $e) {
		    echo '<br/>['.__LINE__.']Error: '.$e->getMessage()."\n";
		}
		rewind($output->getStream());
		$content = stream_get_contents($output->getStream());
		fclose($output->getStream());
		echo '<pre>[';print_r($content);echo ']</pre>';

	}//end bower

	public function composer($args){
		$local_file='composer-setup.php';
		echo '<h3>move composer.phar, here because install has an exit</h3>';
		if (!file_exists('../laravel/composer.phar')) {
			rename('composer.phar', '../laravel/composer.phar');
		} else {
			echo '<h3>composer.phar gia\' spostato</h3>';
		}
		if (file_exists($local_file)) {
			unlink($local_file);
		}
		echo '<h3>copy .env.example to .env</h3>';
		$env_content=file_get_contents('../laravel/.env.example');
		$env_content=str_replace(
			'APP_KEY=',
			'APP_KEY=base64:Gfw24gJvvqeGNVLEqP5gLZlS9Z5pEUa5DvPasbkDD9g=',
			$env_content
		);

		file_put_contents('../laravel/.env', $env_content);
		echo '<pre>
		   ______
		  / ____/___  ____ ___  ____  ____  ________  _____
		 / /   / __ \/ __ `__ \/ __ \/ __ \/ ___/ _ \/ ___/
		/ /___/ /_/ / / / / / / /_/ / /_/ (__  )  __/ /
		\____/\____/_/ /_/ /_/ .___/\____/____/\___/_/  
							/_/

		</pre>';
		define('ROOT_DIR', realpath('../laravel'));
		echo '<br> ROOT_DIR :'.ROOT_DIR; //die('['.__LINE__.']['.__FILE__.']');
		define('EXTRACT_DIRECTORY', ROOT_DIR. '/composer');
		define('HOME_DIRECTORY', ROOT_DIR. '/composer/home');
		define('COMPOSER_INITED', file_exists(ROOT_DIR.'/vendor'));

		set_time_limit(10000);
		ini_set('memory_limit', -1);

		if (!getenv('HOME') && !getenv('COMPOSER_HOME')) {
			putenv("COMPOSER_HOME=".HOME_DIRECTORY);
		}


		//--------Extracting composer library
		echo '<br/>AUTOLOAD : '.EXTRACT_DIRECTORY.'/vendor/autoload.php'; //die('<hr/>['.__LINE__.']['.__FILE__.']');
		if (file_exists(EXTRACT_DIRECTORY.'/vendor/autoload.php') == true) {
			echo "<hr/>Extracted autoload already exists. Skipping phar extraction as presumably it's already extracted.\n";
		} else {
			$composerPhar = new Phar("../laravel/composer.phar");
			//php.ini set phar.readonly=0
			$composerPhar->extractTo(EXTRACT_DIRECTORY);
		}
		//--------------running Composer Command
		// change directory to root
		chdir(ROOT_DIR);
		//This requires the phar to have been extracted successfully.
		require_once(EXTRACT_DIRECTORY.'/vendor/autoload.php');
		//Create the commands
		//$args = array('command' => 'dumpautoload');
		if (!COMPOSER_INITED) {
			echo " This is first composer run: --no-scripts option is applies\n";
			$args['--no-scripts']=true;
		}
		$input = new ArrayInput($args);

		//Create the application and run it with the commands
		$application = new Application();
		$application->setAutoExit(false);
		$application->setCatchExceptions(false);
		$fp = tmpfile();
		$output = new StreamOutput($fp);
		try {
			//Running commdand php.ini allow_url_fopen=1 && proc_open() function available
			$application->run($input, $output);
			echo '<h3>Success</h3>';
		} catch (\Exception $e) {
			echo '<h3>Error: '.$e->getMessage()."\n</h3>";
		}

		rewind($output->getStream());
		$content = stream_get_contents($output->getStream());
		fclose($output->getStream());
		echo '<pre>[';print_r($content);echo ']</pre>';

	}

	public function step0(){
		echo '<h3>Benvenuti clicca su "step 1" per iniziare ..</h3>';
	}//end step0;

	public function step1(){
		if(basename(__DIR__)!='public_html'){
			echo '<h3> crea la cartella public_html sposta install.php dentro "public_html" e ricomincia dalla cartella public_html</h3>';
			die();
		}
		echo '<h3>download zip from github and extract it</h3>';
		$remote_file='https://github.com/laravel/laravel/archive/master.zip';
		$local_file=__DIR__.DIRECTORY_SEPARATOR.'laravel.zip';
		$zip_dir='../';
		if(is_dir('../laravel')){
			echo '<h3>folder "../laravel" just exists</h3>';
			exit;
		}

		$remote_content=file_get_contents($remote_file);

		file_put_contents($local_file, $remote_content);

		echo '<h3>+done</h3>';

		$zip = new ZipArchive;
		if ($zip->open($local_file) === true) {
			$zip->extractTo($zip_dir);
			$zip->close();
			echo 'ok';
		} else {
			echo 'failed';
		}
		rename('../laravel-master', '../laravel');
		unlink($local_file);
	}//end step1

	public function step2(){
		echo '<h3>copy from ../laravel/public to ./public_html (here)</h3>';
		$source="../laravel/public";
		$destination =__DIR__;
		$this->moveFilesAndFolder($source, $destination);
	}//end step2

	public function insertAfterStr($text,$str_find,$str_add){
		$pos=strrpos($text, $str_find);
		$text_before=substr($text, 0, $pos+strlen($str_find));
		$text_after=substr($text, $pos+strlen($str_find));
		$pos=strrpos($text, $str_add);
		if($pos===false){
			$text=$text_before.chr(13).chr(10).$str_add.$text_after;
		}
		return $text;
	}

	public function step3(){
		echo '<h3>sistemo index.php</h3>';
		$index_content=file_get_contents('index.php');
		$str="define('LARAVEL_START', microtime(true));";
		$add_content="define('LARAVEL_DIR', realpath(__DIR__.'/../laravel'));";
		$index_content=$this->insertAfterStr($index_content,$str,$add_content);
		$pos=strrpos($index_content, $str);
		$index_content=str_replace("__DIR__.'/../bootstrap", "LARAVEL_DIR.'/bootstrap", $index_content);
		$index_content=str_replace("__DIR__.'/../vendor", "LARAVEL_DIR.'/vendor", $index_content);

		$str='$app = require_once LARAVEL_DIR.\'/bootstrap/app.php\';';
		$add_content='// set the public path to this directory
$app->bind(\'path.public\', function() {
	return __DIR__;
});
';		
		$index_content=$this->insertAfterStr($index_content,$str,$add_content);
		
		//$new_content=str_replace('\'../bootstrap', 'LARAVEL_DIR.\'/bootstrap', $new_content);
		file_put_contents('index.php', $index_content);
		echo '<h3>+Done</h3>';
	}//end step3

	public function step4(){
		echo '<h3>create composer.phar</h3>';
		$remote_file='https://getcomposer.org/installer';
		$local_file='composer-setup.php';
		$remote_content=file_get_contents($remote_file);
		file_put_contents($local_file, $remote_content);
		$argv=[];
		include($local_file);
	}//end step

	public function step5(){
		$file='../laravel/composer.json';
		$content=file_get_contents($file);
		$json=json_decode($content);
		//echo '<pre>';print_r($json);echo '</pre>';
		//die();

		$psr4=new \stdClass();
		$array=["App\\"=>"app/",
		"XRA\\Extend\\"      =>"packages/XRA/Extend/src",
		"XRA\\Frontend\\"    =>"packages/XRA/Frontend/src",
		"XRA\\Backend\\"     =>"packages/XRA/Backend/src",
		"XRA\\Blog\\"        =>"packages/XRA/Blog/src",
		"XRA\\Install\\"     =>"packages/XRA/Install/src",
		"XRA\\LU\\"          =>"packages/XRA/LU/src",
		"XRA\\Settings\\"    =>"packages/XRA/Settings/src",
		"XRA\\Test\\"        =>"packages/XRA/Test/src",
		"XRA\\Geo\\"         =>"packages/XRA/Geo/src",
		"XRA\\Import\\"      =>"packages/XRA/Import/src",
		"XRA\\Food\\"        =>"packages/XRA/Food/src",
		"XRA\\Seo\\"         =>"packages/XRA/Seo/src",
		"XRA\\XRA\\"         =>"packages/XRA/XRA/src",
		];
		foreach($array as $k=>$v){
			$psr4->$k=$v;
		}

		$json->autoload->{'psr-4'}=$psr4;

		$json->require->{"laravel/scout"}="*"; //for search
        $json->require->{"teamtnt/laravel-scout-tntsearch-driver"}="*"; //for search without algolia
        $json->require->{"laravel/socialite"}="*"; // for login with social
        $json->require->{"facebook/graph-sdk"}="*"; //for fb integration
        $json->require->{"laravelcollective/html"}="*"; // for form
        $json->require->{"beelab/bowerphp"}="*"; // for bower
        $json->require->{"doctrine/dbal"}="*"; //  for query

		//echo '<pre>';print_r($json);echo '</pre>';
		//die();
		$content=json_encode($json,JSON_PRETTY_PRINT);
		$content=str_replace('\\/','/',$content);
		file_put_contents($file,$content);
		echo '<p>aggiornato file composer.json</p>';
	}

	

	public function step6(){
		$this->composer(['command' => 'update']);
	}//end step5

	public function step7(){
		$content=json_encode(["directory"=>"../public_html/bc"],JSON_PRETTY_PRINT);
		$content=str_replace('\\/','/',$content);
		file_put_contents('../laravel/.bowerrc', $content);
		echo '<p>creato file .bowerrc</p>';
	}//end step6

	

	public function step8(){
		/*
		$local_file=__DIR__.'./packages.zip';
		$zip_dir='../laravel';
		$zip = new ZipArchive;
		if ($zip->open($local_file) === true) {
			$zip->extractTo($zip_dir);
			$zip->close();
			echo '<h3>ok</h3>';
		} else {
			echo '<h3>failed</h3>';
		}
		//rename('../laravel-master', '../laravel');
		//unlink($local_file);
		echo '<p>estratti pacchetti base</p>';
		*/ 
		echo '<h3>+Done</h3>';
	}

	public function step9(){

		$file='../laravel/config/app.php';
		$content=file_get_contents($file);
		$str="App\Providers\RouteServiceProvider::class,";
		$add_content="
		//--- XRA 
		XRA\XRA\XRAServiceProvider::class,
		";
		$content=$this->insertAfterStr($content,$str,$add_content);

		$str="'View' => Illuminate\Support\Facades\View::class,";
		$add_content=PHP_EOL."
		//---XRA 
        'Form' => Collective\Html\FormFacade::class,
        'Html' => Collective\Html\HtmlFacade::class,
        'Theme' => XRA\Extend\Services\ThemeService::class,
        ";
        $content=$this->insertAfterStr($content,$str,$add_content);
        file_put_contents($file,$content);
		echo '<p>aggiornato ['.$file.']</p>';
	}

	public function step10(){
		$migrations=[
		'2014_10_12_000000_create_users_table.php',
		'2014_10_12_100000_create_password_resets_table.php'
		];
		$dir='../laravel/database/migrations';
		foreach($migrations as $v){
			$from=$dir.'/'.$v;
			$to=substr($from,0,-4).'.old';
			if(file_exists($from)){
				rename($from,$to);
			}
		}

		echo '<p>renamed unnecessary migrations</p>';
		//composer(['command' => 'dumpautoload']);
		//composer(['command' => 'install']);
	}

	public function step11(){
		//copy('../laravel/packages/bower.json', '../laravel/bower.json');
		//echo '<p>copiato file bower.json</p>';
		//$this->bower(['command'=>'install']);
		//$this->bower(['command'=>'init']);
	}

	public function step12(){
		/*Fatal error: Uncaught Error: Class 'PackageVersions\Versions' not found in C:\xampp\htdocs\lara\test03\laravel\vendor\beelab\bowerphp\src\Bowerphp\Console\Application.php:49 Stack trace: #0 C:\xampp\htdocs\lara\test03\public_html\install.php(143): Bowerphp\Console\Application->__construct() #1 C:\xampp\htdocs\lara\test03\public_html\install.php(462): zeroInstall->bower(Array) #2 C:\xampp\htdocs\lara\test03\public_html\install.php(23): zeroInstall->step12() #3 C:\xampp\htdocs\lara\test03\public_html\install.php(13): zeroInstall->zeroInstall() #4 {main} thrown in C:\xampp\htdocs\lara\test03\laravel\vendor\beelab\bowerphp\src\Bowerphp\Console\Application.php on line 49
		*/
		$this->bower(['command'=>'install','package'=>'BlackrockDigital/startbootstrap-sb-admin-2']);
	}

	public function step13(){

		$config_dir='../laravel/config/'.strtolower(str_replace('www.', '', $_SERVER['SERVER_NAME'])).'';
		if(!is_dir($config_dir)){
			 @mkdir($config_dir, 0777);
		}
		foreach (glob('../laravel/packages/config_sample/*.php') as $filename){
			$filename=str_replace('/',DIRECTORY_SEPARATOR,$filename);
			copy($filename,$config_dir.'/'.basename($filename));
			//require_once($filename);
		}
		echo '<p>creata cartella di configurazione dominio</p>';
	}

	function curlAuthDownload($url, $destination,$user,$pwd) {
	    try {
	        $fp = fopen($destination, "w");
	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, $url);
	        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	        curl_setopt($ch, CURLOPT_USERPWD, $user . ":" . $pwd);
	        curl_setopt($ch, CURLOPT_FILE, $fp);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	        $resp = curl_exec($ch);

	        // validate CURL status
	        if(curl_errno($ch))
	            throw new Exception(curl_error($ch), 500);

	        // validate HTTP status code (user/password credential issues)
	        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	        if ($status_code != 200)
	            throw new Exception("Response with Status Code [" . $status_code . "].", 500);
	    }
	    catch(Exception $ex) {
	        if ($ch != null) curl_close($ch);
	        if ($fp != null) fclose($fp);
	        throw new Exception('Unable to properly download file from url=[' + $url + '] to path [' + $destination + '].', 500, $ex);
	    }
	    if ($ch != null) curl_close($ch);
	    if ($fp != null) fclose($fp);
	}

	public function step14(){
		@mkdir('./tmp', 0777);
		$zip_file='./tmp/xra.zip';
		$zip_dir='../laravel/packages/XRA';
		@mkdir('./tmp', $zip_dir);
		$user='';
		$pwd='';
		//--------PACAKGE XRA --------------
		$this->curlAuthDownload('https://bitbucket.org/qweb_xot/package_xra/get/HEAD.zip',$zip_file,$user,$pwd);
		$zip = new ZipArchive;
		if ($zip->open($zip_file) === true) {
			$zip->extractTo($zip_dir);
			$zip->close();
			echo 'ok';
		} else {
			echo 'failed';
		}
		rename($zip_dir.'/qweb_xot-package_xra-2e836e498ad7',$zip_dir.'/XRA');

		//--------PACAKGE EXTEND --------------
		$this->curlAuthDownload('https://bitbucket.org/qweb_xot/package_extend/get/HEAD.zip',$zip_file,$user,$pwd);
		$zip = new ZipArchive;
		if ($zip->open($zip_file) === true) {
			$zip->extractTo($zip_dir);
			$zip->close();
			echo 'ok';
		} else {
			echo 'failed';
		}
		rename($zip_dir.'/qweb_xot-package_extend-b0bacb098e46',$zip_dir.'/Extend');


	}



}//end class
