<?php
namespace ECweb;

date_default_timezone_set('Asia/Tokyo');
require_once dirname(__FILE__) . './../vendor/autoload.php';

class Bootstrap
{
  const DB_HOST = 'localhost';
  const DB_NAME = 'ECweb_db';
  const DB_USER = 'EC_user';
  const DB_PASS = 'EC_pass';
  const DB_TYPE = 'mysql';
  const APP_DIR = '/Applications/XAMPP/xamppfiles/htdocs/DT/';
  const TEMPLATE_DIR = self::APP_DIR . 'ECweb/templates/';
  const CACHE_DIR = self::APP_DIR . 'ECweb/templates_c/';
  const APP_URL = 'http://localhost/DT/';
  const ENTRY_URL = self::APP_URL . 'ECweb/';
  const ENTRY_URL_SHOP = self::APP_URL . 'ECweb/shopping/';
  const ENTRY_URL_MEMBER = self::APP_URL . 'ECweb/member/';
  const ENTRY_URL_LOGIN = self::APP_URL . 'ECweb/login/';
  const ENTRY_URL_ADMIN = self::APP_URL . 'ECweb/admin/';


  public static function loadClass($class)
  {
    $path = str_replace('\\', '/', self::APP_DIR . $class . '.class.php');
    require_once $path;
  }
}

spl_autoload_register([
  'ECweb\Bootstrap',
  'loadClass'
]);