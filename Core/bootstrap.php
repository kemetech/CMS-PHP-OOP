<?php
// Enable strict typing
declare(strict_types=1);
// Enable strict error reporting
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', '1');

// Check if the server name is localhost
if ($_SERVER['SERVER_NAME'] == 'localhost') {
    define('ROOT', $_SERVER['DOCUMENT_ROOT'] . '/CMS-PHP/'); 
} else {
    define('ROOT', $_SERVER['DOCUMENT_ROOT'] . '/');  
}

/**
 * Generates a URL based on the provided path.
 *
 * @param string $path The path to append to the base URL.
 * @return string The generated URL.
 */
function url($path) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $baseURL = $protocol . $host . dirname(dirname($scriptName));
    return rtrim($baseURL, '/') . '/' . ltrim($path, '/');
}

define('APP_DIR', ROOT . 'App/');
define('ADMIN_DIR', ROOT . 'admin/');
define('CORE_DIR', ROOT . 'core/');
define('PUBLIC_DIR', ROOT . 'public/');

define('DS', DIRECTORY_SEPARATOR);
define('APP', ROOT . 'App' . DS);
define('VIEWS', ROOT  . 'Views' . DS);
define('INCLUDES', VIEWS  . 'includes' . DS);
define('TEMPLATE_PARTS', VIEWS  . 'template_parts' . DS);
define('LAYOUTS', VIEWS  . 'layouts' . DS);
define('UPLOAD_DIR' , PUBLIC_DIR . 'uploads/');

require_once ROOT . 'vendor/autoload.php';
$configPath = ROOT . 'config.ini';

use Core\Config\SessionConfig;
use Core\Config\AppConfig;
use Admin\Auth\Session;
use Core\Config\MysqlConfig;
use Core\Database\MySqlConnection;
use Core\Config\UploadConfig;

$dbConfig = new MysqlConfig($configPath, 'database');
$sessionConfig = new SessionConfig($configPath, 'session');
new AppConfig($configPath, 'App');
new UploadConfig($configPath, 'image upload');

Session::setCookieParams($sessionConfig);

$db = new MySqlConnection($dbConfig);
MySqlConnection::connect();
