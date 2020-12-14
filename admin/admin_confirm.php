<?php
namespace ECweb\admin;
session_start();

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use ECweb\member\master\initMaster;
use ECweb\lib\PDODatabase;
use ECweb\Bootstrap;
use ECweb\lib\Admin;

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,[
  'cache' => Bootstrap::CACHE_DIR
]);

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$admin = new Admin($db);


// 直接URL打ち込みされた場合
if(empty($_POST) === true){
  $mode = 'direct';
}

// From 登録画面
if(isset($_POST['confirm']) === true){
  $mode = 'confirm';
}

// From 戻る
if(isset($_POST['back']) === true){
  $mode = 'back';
}

// From 登録完了
if(isset($_POST['complete']) === true){
  $mode = 'complete';
}

switch($mode){
  case 'direct':
    header('Location:' . Bootstrap::ENTRY_URL_ADMIN . 'admin_top.php');
    exit();
  break;


  case 'confirm':
    unset($_POST['confirm']);
    $dataArr = $_POST;
    $dataArr['image'] = $_FILES['image']['name'];
    $template ='admin_confirm.html.twig';
  break;
  

    case 'back':
      $dataArr = $_POST;
      unset($dataArr['back']);
      $template = 'admin_regist.html.twig';
    break;


    case 'complete':
      $dataArr = $_POST;
      unset($dataArr['complete']);
      $dataArr['regist_date'] = date('Y-m-d H:i:s');
      $res = $db->insert($table='item', $dataArr);

    if($res === true){
      header('Location:' . Bootstrap::ENTRY_URL_ADMIN . 'admin_item_complete.php');
      exit();
    } else {
      $template = 'mem_info_update.html.twig';
      foreach($dataArr as $key => $value){
        $errArr[$key] = '';
      }
    }
  break;
}

  $context['dataArr'] = $dataArr;
  $context['session'] = $_SESSION;

  $template = $twig->loadTemplate($template);
  $template->display($context);