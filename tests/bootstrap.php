<?php
declare(strict_types=1);

use Cake\Core\Configure;

require dirname(__DIR__) . '/vendor/autoload.php';

define('ROOT', dirname(__DIR__));
define('APP', ROOT . '/tests/test_app/src/');
define('CONFIG', ROOT . '/tests/test_app/config/');
define('WWW_ROOT', ROOT . '/webroot/');
define('TMP', sys_get_temp_dir() . '/cakephp-daisyui/');
define('CACHE', TMP . 'cache/');
define('LOGS', TMP . 'logs/');
define('CAKE_CORE_INCLUDE_PATH', ROOT . '/vendor/cakephp/cakephp');
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . '/');
define('CAKE', CORE_PATH . 'src/');

require CORE_PATH . 'config/bootstrap.php';

// ClassMapTest briefly writes an app-side daisyui.php to test shadowing; if a
// crashed run left it behind it would silently replace the plugin map everywhere.
if (is_file(CONFIG . 'class_maps/daisyui.php')) {
    unlink(CONFIG . 'class_maps/daisyui.php');
}

Configure::write('debug', true);
Configure::write('App', [
    'namespace' => 'App',
    'encoding' => 'UTF-8',
    'defaultLocale' => 'en_US',
    'base' => false,
    'baseUrl' => false,
    'dir' => 'src',
    'webroot' => 'webroot',
    'wwwRoot' => WWW_ROOT,
    'fullBaseUrl' => 'http://localhost',
    'imageBaseUrl' => 'img/',
    'jsBaseUrl' => 'js/',
    'cssBaseUrl' => 'css/',
    'paths' => ['templates' => [ROOT . '/tests/test_app/templates/']],
]);
