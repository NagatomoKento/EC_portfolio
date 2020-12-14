<?php
// http://localhost/DT/ECweb/admin/admin_item_add.php
namespace ECweb\admin;
session_start();

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use ECweb\Bootstrap;

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,[
  'cache' => Bootstrap::CACHE_DIR
]);

$itemArr = [
  'item_name' => '',
  'detail' => '',
  'price' => '',
  'image' => '',
  'image2' => '',
  'image3' => '',
  'ctg_id' => ''
];


$context = [];
$context['itemArr'] = $itemArr ;
$context['session'] = $_SESSION;

$template = $twig->loadTemplate('admin_regist.html.twig');
$template->display($context);