<?php

namespace XRA\Install\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
//use XRA\Install\Helpers\EnvironmentManager;
//use XRA\Install\Events\EnvironmentSaved;
use Validator;
//--- services
use XRA\Extend\Services\ThemeService;

//--------------------------------------------
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
    $composerPhar = new \Phar("../laravel/composer.phar");
    //php.ini set phar.readonly=0
    $composerPhar->extractTo(EXTRACT_DIRECTORY);
}

// change directory to root
chdir(ROOT_DIR);

//This requires the phar to have been extracted successfully.
require_once(EXTRACT_DIRECTORY.'/vendor/autoload.php');
//Use the Composer classes
use Composer\Console\Application;
use Composer\Command\UpdateCommand;
use Symfony\Component\Console\Input\ArrayInput;

class ComposerController extends Controller
{
    /**
    * Display the installer welcome page.
    *
    * @return \Illuminate\Http\Response
    */
    public function composer()
    {
        //$view=ThemeService::getView();
        $this->install();
        $view='install::composer';
        return view($view);
    }


    public function install()
    {
        ddd('install');
        //Create the commands
        $args = array('command' => 'update');
        if (!COMPOSER_INITED) {
            echo "This is first composer run: --no-scripts option is applies\n";
            $args['--no-scripts']=true;
        }
        $input = new ArrayInput($args);

        //Create the application and run it with the commands
        $application = new Application();
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);
        try {
            //Running commdand php.ini allow_url_fopen=1 && proc_open() function available
            $application->run($input);
            echo 'Success';
        } catch (\Exception $e) {
            echo 'Error: '.$e->getMessage()."\n";
        }
    }
}
