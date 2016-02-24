<?php
$fs_prefix = "/path/to/htdocs/";

// HTTP
define('HTTP_SERVER',      'http://' . $_SERVER['SERVER_NAME'] . '/');

// HTTPS
define('HTTPS_SERVER',     'https://' . $_SERVER['SERVER_NAME'] . '/');

// DIR
define('DIR_APPLICATION',  $fs_prefix . 'catalog/');
define('DIR_SYSTEM',       $fs_prefix . 'system/');
define('DIR_LANGUAGE',     $fs_prefix . 'catalog/language/');
define('DIR_TEMPLATE',     $fs_prefix . 'catalog/view/theme/');
define('DIR_CONFIG',       $fs_prefix . 'system/config/');
define('DIR_IMAGE',        $fs_prefix . 'image/');
define('DIR_CACHE',        $fs_prefix . 'system/cache/');
define('DIR_DOWNLOAD',     $fs_prefix . 'system/download/');
define('DIR_UPLOAD',       $fs_prefix . 'system/upload/');
define('DIR_MODIFICATION', $fs_prefix . 'system/modification/');
define('DIR_LOGS',         $fs_prefix . 'system/logs/');

// DB
define('DB_DRIVER',        'mysqli');
define('DB_PORT',          '3306');
define('DB_DATABASE',      'opencart');
define('DB_PREFIX',        'oc_');
define('DB_HOSTNAME',      'localhost');
define('DB_USERNAME',      'root');
define('DB_PASSWORD',      '');
