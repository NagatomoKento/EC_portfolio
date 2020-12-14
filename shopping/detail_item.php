<?php

namespace ECweb\shopping;
session_start();

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use ECweb\Bootstrap;
use ECweb\lib\PDODatabase;
use ECweb\lib\Item;
use ECweb\lib\Login;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$item = new Item($db);
$login = new Login($db);

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

$login->check_Login_session();

// ログイン済ユーザかどうかのチェック
// $template = ($_SESSION['login_flg']  === "1")?
//   'detail_item_guest.html.twig':'detail_item.html.twig';

$item_id = (isset($_GET['item_id']) === true && preg_match('/^\d+$/', $_GET['item_id']) === 1) ? $_GET['item_id'] : '';

if($item_id === ''){
  header('Location:' . Bootstrap::ENTRY_URL_SHOP. 'item_list.php');
}

$cateArr = $item->getCategoryList();
$itemData = $item->getItemDetailData($item_id);

$context = [];
$context['cateArr'] = $cateArr;
$context['itemData'] = $itemData[0];
$context['session'] = $_SESSION;

$template = $twig->loadTemplate('detail_item.html.twig');
$template->display($context);