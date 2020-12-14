<?php
namespace ECweb\member;
session_start();

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use ECweb\Bootstrap;
use ECweb\lib\PDODatabase;
use ECweb\lib\Login;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);
$logout = new Login($db);
$logout->check_Login_session();

$customer_no = $_SESSION['customer_no'];
// ログイン済ユーザかどうかのチェック
$template = ($_SESSION['login_flg'] === "1")?
'cancel_mem.html.twig' : 'request_login.html.twig';


$context = [];
$context['session'] = $_SESSION;

$template = $twig->loadTemplate($template);
$template->display($context);