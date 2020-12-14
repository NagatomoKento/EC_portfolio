<?php
namespace ECweb\admin;
session_start();

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use ECweb\Bootstrap;
use ECweb\lib\PDODatabase;
use ECweb\lib\Purchase;

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);

$purchase_search = new Purchase($db);

  $timeArr = [];
  $salesArr = [];
  // $headingArr =[
  //   '購入ID',
  //   '購入ユニット',
  //   '顧客No.',
  //   '商品ID',
  //   '商品名',
  //   '価格',
  //   '画像',
  //   '数量',
  //   '登録日'];

  if($_POST !== []){
    $timeArr['from'] = $_POST['time_from'];
    $timeArr['to'] = $_POST['time_to'];
    $timeArr['mark'] = ' 〜 ';
    $salesArr = $purchase_search->sales_view($timeArr);
  }

  // if(isset($_POST['csv_output']) === true){
  //   $fileName = "salesdata";
  //   $mkcsv = $purchase_search->csv_output($headingArr,$salesArr,$fileName);
  //   }

  $context = [];
  $context['timeArr'] = $timeArr;
  // $context['headingArr'] = $headingArr;
  $context['salesArr'] = $salesArr;
  $context['session'] = $_SESSION;
  $template = $twig->loadTemplate('admin_sales_list.html.twig');
  $template->display($context);
