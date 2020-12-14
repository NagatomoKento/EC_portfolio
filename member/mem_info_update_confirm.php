<?php
namespace ECweb\member;
session_start();

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use ECweb\member\master\initMaster;
use ECweb\lib\PDODatabase;
use ECweb\lib\Common;
use ECweb\lib\Login;
use ECweb\Bootstrap;

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,[
  'cache' => Bootstrap::CACHE_DIR
]);

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$common = new Common();
$login = new Login($db);
$login->check_Login_session();


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
    header('Location:' . Bootstrap::ENTRY_URL_MEMBER . 'mem_info_update.php');
    exit();
  break;


  case 'confirm':
    unset($_POST['confirm']);
    $dataArr = $_POST;

    if(isset($_POST['sex']) === false){
      $dataArr['sex'] = "";
    }
    $errArr = $common->errorCheck($dataArr);
    $err_check = $common->getErrorFlg();
    // $err_check === true ⇨エラーなし
    $template = ($err_check === true)?
    'mem_info_update_confirm.html.twig' : 'mem_info_update.html.twig';
  break;
  

    case 'back':
      $dataArr = $_POST;
      unset($dataArr['back']);
      foreach($dataArr as $key =>$value){
        $errArr[$key] = '';
         }
      $template = 'mem_info_update.html.twig';
    break;


    case 'complete':
    $dataArr = $_POST;
    $dataArr['password'] = password_hash($dataArr['password'],PASSWORD_DEFAULT);
    unset($dataArr['complete']);
    unset($dataArr['passwordConfirm']);
    
    $dataArr['update_date'] = date('Y-m-d H:i:s');
    $res = $db->update($table='member', $dataArr, $where ='mem_no=?',$arrWhereVAl = [$_SESSION['customer_no']]);

  if($res === true){
    header('Location:' . Bootstrap::ENTRY_URL_MEMBER . 'complete.php');
    exit();
  } else {
    $template = 'mem_info_update.html.twig';
    foreach($dataArr as $key => $value){
      $errArr[$key] = '';
    }
  }
  break;
}

  $sexArr = initMaster::getSex();
  $agreeArr = initMaster::getPresonalInfo();
  list($yearArr, $monthArr, $dayArr)= initMaster::getDate();

  $context['sexArr'] = $sexArr;
  $context['agreeArr'] = $agreeArr;
  $context['yearArr'] = $yearArr;
  $context['monthArr'] = $monthArr;
  $context['dayArr'] = $dayArr;
  $context['dataArr'] = $dataArr;
  $context['errArr'] = $errArr;
  $context['session'] = $_SESSION;

  $template = $twig->loadTemplate($template);
  $template->display($context);