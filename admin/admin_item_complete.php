<?php
namespace ECweb\admin;
session_start();

use ECweb\lib\PDODatabase;
use ECweb\Bootstrap;
use ECweb\lib\Admin;

require_once dirname(__FILE__) . './../Bootstrap.class.php';

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);
$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);

$template = 'admin_item_complete.html.twig';



$context['session'] = $_SESSION;
$template = $twig->loadTemplate($template);
$template->display($context);