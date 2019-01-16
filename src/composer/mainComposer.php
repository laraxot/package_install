<?php


//https://odino.org/install-composer-dependencies-with-the-symfony2-cli/
//https://stackoverflow.com/questions/25893664/how-to-use-composer-composer-php-classes-to-update-individual-packages

\define('ROOT_DIR', \realpath('../../laravel'));
\define('EXTRACT_DIRECTORY', ROOT_DIR.'/composer'); // /storage/composer
\define('HOME_DIRECTORY', ROOT_DIR.'/composer/home');
\define('COMPOSER_INSTALLED', \file_exists(ROOT_DIR.'/vendor'));
\set_time_limit(10000);
\ini_set('memory_limit', -1);  //could be forbidden on server
\putenv('COMPOSER_HOME='.HOME_DIRECTORY);

include 'password.php';
if (!isset($_POST['function'])) {
    die('You must specify a function');
}
if (!\function_exists($_POST['function'])) {
    die('Function not found');
} else {
    \call_user_func($_POST['function']);
}

function getStatus()
{
    $output = [
        'composer' => \file_exists(ROOT_DIR.'/composer.phar'),
        'composer_extracted' => \file_exists(EXTRACT_DIRECTORY),
        'installer' => \file_exists('installer.php'),
    ];
    \header('Content-Type: text/json; charset=utf-8');
    echo \json_encode($output);
}

function downloadComposer()
{
    $installerURL = 'https://getcomposer.org/installer';
    $installerFile = 'installer.php';
    if (!\file_exists($installerFile)) {
        echo 'Downloading '.$installerURL.PHP_EOL;
        \flush();
        $ch = \curl_init($installerURL);
        \curl_setopt($ch, CURLOPT_CAINFO, __DIR__.'/cacert.pem');
        \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        \curl_setopt($ch, CURLOPT_FILE, \fopen($installerFile, 'w+'));
        if (\curl_exec($ch)) {
            echo 'Success downloading '.$installerURL.PHP_EOL;
        } else {
            echo 'Error downloading '.$installerURL.PHP_EOL;
            die();
        }
        \flush();
    }
    echo 'Installer found : '.$installerFile.PHP_EOL;
    echo 'Starting installation...'.PHP_EOL;
    \flush();
    $argv = [];
    include $installerFile;
    \flush();
}

function extractComposer()
{
    if (\file_exists(ROOT_DIR.'/composer.phar')) {
        echo 'Extracting composer.phar ...'.PHP_EOL;
        \flush();
        $composer = new Phar(ROOT_DIR.'/composer.phar');
        $composer->extractTo(EXTRACT_DIRECTORY);
        echo 'Extraction complete.'.PHP_EOL;
    } else {
        echo ROOT_DIR.'/composer.phar does not exist';
    }
}

function command()
{
    //command_string();
    command_array();
}

function command_string()
{
    //$_POST['command']='require laravel/scout';
    \set_time_limit(-1);
    \putenv('COMPOSER_HOME='.HOME_DIRECTORY);
    $path = ROOT_DIR;
    $path = \str_replace('\\', '\\\\', $path);
    if (!\file_exists($path)) {
        echo 'Invalid Path';
        die();
    }
    if (\file_exists(EXTRACT_DIRECTORY)) {
        require_once EXTRACT_DIRECTORY.'/vendor/autoload.php';
        $input = new Symfony\Component\Console\Input\StringInput($_POST['command'].' -vvv -d '.$path);
        $output = new Symfony\Component\Console\Output\StreamOutput(\fopen('php://output', 'w'));
        $app = new Composer\Console\Application();
        $app->run($input, $output);
    } else {
        echo 'Composer not extracted.';
        extractComposer();
    }
}

function command_array()
{
    $args = [];
    /*
    $args['command'] = 'require';
    $args['packages']=['laravel/scout'];
    */
    $args['command'] = $_POST['command'];
    if (isset($_POST['package']) && \mb_strlen(\trim($_POST['package'])) > 3) {
        //$args['command'] = 'require';
        $args['packages'] = [$_POST['package']];
    }
    if (\in_array($args['command'], ['require', 'remove'], true) && !isset($args['packages'])) {
        echo '<h3>Package is obligatory when command is require or remove </h3>';
        exit;
    }

    \chdir(ROOT_DIR);
    require_once EXTRACT_DIRECTORY.'/vendor/autoload.php';

    $input = new Symfony\Component\Console\Input\ArrayInput($args);
    $output = new Symfony\Component\Console\Output\StreamOutput(\tmpfile());

    //Create the application and run it with the commands
    $application = new Composer\Console\Application();
    $application->setAutoExit(false);
    $application->setCatchExceptions(false);
    try {
        //Running commdand php.ini allow_url_fopen=1 && proc_open() function available
        $application->run($input, $output);
        echo 'Success';
    } catch (\Exception $e) {
        echo 'Error: '.$e->getMessage()."\n";
    }

    \rewind($output->getStream());
    $content = \stream_get_contents($output->getStream());
    \fclose($output->getStream());
    echo '<pre>['.\chr(13);
    \print_r($content);
    echo \chr(13).']</pre>';
}

/*
use Composer\Console\Application;
use Composer\Command\UpdateCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\HttpFoundation\Response;

$stream = fopen('php://temp', 'w+');
*/
