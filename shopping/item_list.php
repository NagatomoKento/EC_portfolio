<?php
// http://localhost/DT/ECweb/shopping/item_list.php

namespace ECweb\shopping;
session_start();

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use ECweb\Bootstrap;
use ECweb\lib\PDODatabase;
use ECweb\lib\Item;
use ECweb\lib\Login;
use ECweb\lib\PageNation;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$item = new Item($db);
$login = new Login($db);
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, ['cache' => Bootstrap::CACHE_DIR]);
$page = new PageNation($db);

$login->check_Login_session();
$ctg_id = (isset($_GET['ctg_id']) === true && preg_match('/^[0-9]+$/', $_GET['ctg_id']) === 1) ? $_GET['ctg_id'] : '';

$cateArr = $item->getCategoryList(); 
$dataArr = $item->getItemList($ctg_id);


$searchWord = [];
if(isset($_GET) === true && empty($_GET['item_name']) !== true){
  $searchWord = $_GET;
  $dataArr = $item->getSearchItemList($searchWord);
}
$newsArr = $item->getNews();


$context = [];
$context['cateArr'] = $cateArr;
$context['dataArr'] = $dataArr;
$context['newsArr'] = $newsArr;
$context['session'] = $_SESSION;
$context['searchWord'] = $searchWord;

$template = $twig->loadTemplate('item_list.html.twig');
$template->display($context);