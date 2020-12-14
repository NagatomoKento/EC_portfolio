<?php
// http://localhost/DT/ECweb/shopping/shop_complete.php
namespace ECweb\shopping;
session_start();

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use ECweb\Bootstrap;
use ECweb\lib\PDODatabase;
use ECweb\lib\Cart;
use ECweb\lib\Purchase;
use ECweb\lib\Login;
use ECweb\lib\Email;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$cart = new Cart($db);
$purchase = new Purchase($db);
$login = new Login($db);
$email = new Email($db);
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

$login->check_Login_session();
$customer_no = $_SESSION['customer_no'];

// ログイン済ユーザかどうかのチェック
$template = ($_SESSION['login_flg'] === "1")?
  'shop_complete.html.twig' : 'request_login.html.twig';

$dataArr = $cart->getCartData($customer_no);
$itemTypeCount = count($dataArr);

$purchase_unit = 'A' . strtotime("now");
  for($i=0; $i<$itemTypeCount; $i++){
    $ins = $purchase->insPurchase($dataArr[$i],$purchase_unit);
  }
$delcart = $cart->clearCart($customer_no,$cart_id ="*");
$purchase_unit_no = $cart->getPurchase_unit($customer_no);

// $email->preparaEmail($dataArr,$customerArr,$purchase_unit_no);
// $email->sendEmail($customer_no);

$context = [];
$context['purchase_unit_no'] = $purchase_unit_no[0];
$context['dataArr'] = $dataArr;
$context['session'] = $_SESSION;

$template = $twig->loadTemplate($template);
$template->display($context);