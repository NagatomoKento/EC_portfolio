<?php
// http://localhost/DT/ECweb/login/login.php
namespace ECweb\login;
session_start();

require_once dirname(__FILE__) . './../Bootstrap.class.php';
use ECweb\lib\PDODatabase;
use ECweb\lib\Login;
use ECweb\Bootstrap;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,[
  'cache' => Bootstrap::CACHE_DIR
]);

$logout = new Login($db);
$logout->check_Login_session();

if(isset($_POST['logout']) === true){
  $logout->unset_session($SESSION);
  header('Location:' . Bootstrap::ENTRY_URL_SHOP . 'item_list.php');
  exit();
}

$context = [];
$context['session'] = $_SESSION;

$template = $twig->loadTemplate('logout.html.twig');
$template->display($context);
