<?php
// http://localhost/DT/ECweb/admin/admin_top.php
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

$template = 'admin_top.html.twig';
$context = [];
$context['session'] = $_SESSION;
$template = $twig->loadTemplate($template);
$template->display($context);