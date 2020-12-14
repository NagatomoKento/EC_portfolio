<?php
namespace ECweb\admin;
session_start();

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use ECweb\lib\PDODatabase;
use ECweb\lib\Admin;
use ECweb\Bootstrap;

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,[
  'cache' => Bootstrap::CACHE_DIR
]);
$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);

$admin_login = new Admin($db);

if(isset($_POST['logout']) === true){
  $_SESSION['customer_no'] = '';
  $_SESSION['login_flg'] = '';
  $_SESSION['family_name'] = '';
  $_SESSION['first_name'] = '';
  $_SESSION['staff_no'] = '';
  $_SESSION['staff_name'] = '';
  header('Location:' . Bootstrap::ENTRY_URL_ADMIN . 'admin.php');
  exit();
}

$context = [];
$context['session'] = $_SESSION;

$template = $twig->loadTemplate('admin_logout.html.twig');
$template->display($context);