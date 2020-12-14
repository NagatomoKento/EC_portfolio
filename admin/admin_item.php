<?php
namespace ECweb\admin;
session_start();

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use ECweb\Bootstrap;
use ECweb\lib\PDODatabase;
use ECweb\lib\Item;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$item = new Item($db);

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);


$cateArr = $item->getCategoryList();
$itemData = $item->getItemDetailData($item_id = '');
$max = count($itemData);

$context = [];
$context['cateArr'] = $cateArr;
$context['itemData'] = $itemData;
$context['session'] = $_SESSION;

$template = $twig->loadTemplate('admin_item.html.twig');
$template->display($context);