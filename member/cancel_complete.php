<?php
namespace ECweb\member;
session_start();

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use ECweb\Bootstrap;
use ECweb\lib\PDODatabase;
use ECweb\lib\Login;


$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$logout = new Login($db);
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);
$logout->check_Login_session();
$logout->unset_session($_SESSION);

// ログイン済ユーザかどうかのチェック
$template = 'cancel_complete.html.twig';


$context = [];
$template = $twig->loadTemplate($template);
$template->display($context);
