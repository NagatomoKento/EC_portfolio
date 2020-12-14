<?php
namespace ECweb\member;
session_start();

use ECweb\lib\PDODatabase;
use ECweb\lib\Login;
use ECweb\Bootstrap;

require_once dirname(__FILE__) . './../Bootstrap.class.php';

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);
$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$login = new Login($db);
$login->check_Login_session();

$template = 'complete.html.twig';

// $login_flg = $login->setLoginflg($ses->customer_no);

$context['session'] = $_SESSION;
$template = $twig->loadTemplate($template);
$template->display($context);