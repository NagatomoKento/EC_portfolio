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
$customer_no = $_SESSION['customer_no'];

// ログイン済ユーザかどうかのチェック
$template = ($_SESSION['login_flg'] === "1")?
  'shop_confirm.html.twig' : 'request_login.html.twig';

$dataArr = $cart->getCartData($customer_no);

if(empty($dataArr) === true){
  header('Location:' . Bootstrap::ENTRY_URL_SHOP . 'item_list.php');
} 

$sumNum = $cart->getTotalnum($customer_no);

$table = ' member ';
$colum =  " mem_no, "
          ." family_name, "
          ." first_name, "
          ." email, "
          ." zip1, "
          ." zip2, "
          ." address ";
$where = "mem_no = ? ";
$arrVall = [$customer_no];
$mem_dataArr = $db->select($table,$colum,$where,$arrVall);

$context = [];
$context['sumNum'] = $sumNum;
$context['dataArr'] = $dataArr;
$context['mem_dataArr'] = $mem_dataArr[0];
$context['session'] = $_SESSION;

$template = $twig->loadTemplate($template);
$template->display($context);