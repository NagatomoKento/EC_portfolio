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

// ログイン済ユーザかどうか分岐
if($_SESSION['login_flg'] === "1"){
  $template = 'cart.html.twig';
} else {
  $template = 'request_login.html.twig';
  $context = [];
  $context['session'] = $_SESSION;
  $template = $twig->loadTemplate($template);
  $template->display($context);
  exit();
}


$item_id = (isset($_GET['item_id']) === true && preg_match('/^\d+$/', $_GET['item_id']) === 1) ? $_GET['item_id'] : '';
$crt_id = (isset($_GET['crt_id']) === true && preg_match('/^\d+$/', $_GET['crt_id']) === 1) ? $_GET['crt_id'] : '';

$customer_no = $_SESSION['customer_no'];

if($item_id !== ''){
  $cartCheck = $cart->CheckCartData($customer_no, $item_id);
    if(isset($cartCheck[0]) === true){
    $cart_id = $cartCheck[0]['crt_id'];
    $num = $cartCheck[0]['num'] + 1;
    if($num > 10){ $num =10; }
    $num_change = $cart->ChangeItemNum($cart_id, $num);
    } else {
      $res = $cart->insCartData($customer_no, $item_id, $num=1);
      if($res === false){
      echo "商品カート登録に失敗しました。";
      exit();
      }
    }
  }

if(isset($_GET['reduce']) == true){
  $item_id = $_GET['reduce'];
  $cartCheck = $cart->CheckCartData($customer_no, $item_id);
  if(isset($cartCheck[0]) === true){
  $cart_id = $cartCheck[0]['crt_id'];
  $num = $cartCheck[0]['num'] -1 ;
   if($num == 0){
     $del = $cart->clearCart($customer_no, $cart_id);
   }
  $res = $cart->ChangeItemNum($cart_id, $num);
}
}


$dataArr = $cart->getCartData($customer_no);
$sumNum = $cart->getTotalnum($customer_no);


$context = [];
$context['sumNum'] = $sumNum;
$context['dataArr'] = $dataArr;
$context['session'] = $_SESSION;

$template = $twig->loadTemplate($template);
$template->display($context);