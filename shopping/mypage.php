<?php
namespace ECweb\shopping;
session_start();

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use ECweb\Bootstrap;
use ECweb\lib\PDODatabase;
use ECweb\lib\Cart;
use ECweb\lib\Login;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$cart = new Cart($db);
$login = new Login($db);
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

$login->check_Login_session();

// ログイン済ユーザかどうかのチェック
$template = ($_SESSION['login_flg'] === "1")?
  'mypage.html.twig' : 'request_login.html.twig';

$context = [];
$context['session'] = $_SESSION;

$template = $twig->loadTemplate($template);
$template->display($context);
