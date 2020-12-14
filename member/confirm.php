<?php
// http://localhost/DT/ECweb/member/confirm.php
namespace ECweb\member;
session_start();

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use ECweb\member\master\initMaster;
use ECweb\lib\PDODatabase;
use ECweb\lib\Common;
use ECweb\Bootstrap;
use ECweb\lib\Login;

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,[
  'cache' => Bootstrap::CACHE_DIR
]);

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$login = new Login($db);
$common = new Common();

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
    header('Location:' . Bootstrap::ENTRY_URL_MEMBER . 'regist.php');
    exit();
  break;

  case 'confirm':
    unset($_POST['confirm']);
    $dataArr = $_POST;

    if(isset($_POST['sex']) === false){
      $dataArr['sex'] = "";
    }
    if(isset($_POST['agree']) === false){
      $dataArr['agree'] = '';
    }

    $errArr = $common->errorCheck($dataArr);
    $err_check = $common->getErrorFlg();

    // $err_check = true ⇨エラーなし
    $template = ($err_check === true)?
    'confirm.html.twig' : 'regist.html.twig';
  break;
  
    case 'back':
      $dataArr = $_POST;
      unset($dataArr['back']);
      foreach($dataArr as $key =>$value){
        $errArr[$key] = '';
         }
      $template = 'regist.html.twig';
    break;


    case 'complete':
    $dataArr = $_POST;
    unset($dataArr['complete']);
    unset($dataArr['passwordConfirm']);
    $email = $db->select('member', $column = 'email', $where = "email=?", [$dataArr['email']]);

    if(empty($email) == false){
    $template = $twig->loadTemplate('complete_duplicate.html.twig');
    $template->display([]);
    exit();
    }
     
    $dataArr['password'] = password_hash($dataArr['password'],PASSWORD_DEFAULT);
    $dataArr['regist_date'] = date('Y-m-d H:i:s');

    $res = $db->insert('member', $dataArr);

    if($res === true){
    $login->check_Login_session();

    $get_mem_no = $login->getmem_no($dataArr);
    $mem_no = $get_mem_no[0]['mem_no'];
    $get_mem_name = $login->selectname($mem_no);
    $mem_family_name = $get_mem_name[0]['family_name'];
    $mem_first_name = $get_mem_name[0]['first_name'];
    $_SESSION['customer_no'] = $mem_no;
    $_SESSION['login_flg'] = "1";
    $_SESSION['family_name'] = $mem_family_name;
    $_SESSION['first_name'] = $mem_first_name;

    header('Location:' . Bootstrap::ENTRY_URL_MEMBER . 'complete.php');
    exit();
  } else {
    $template = 'regist.html.twig';
    foreach($dataArr as $key => $value){
      $errArr[$key] = '';
    }
  }
  break;
}


  $sexArr = initMaster::getSex();
  $agreeArr = initMaster::getPresonalInfo();
  $context['sexArr'] = $sexArr;
  $context['agreeArr'] = $agreeArr;

  list($yearArr, $monthArr, $dayArr)= initMaster::getDate();

  $context['yearArr'] = $yearArr;
  $context['monthArr'] = $monthArr;
  $context['dayArr'] = $dayArr;
  $context['dataArr'] = $dataArr;
  $context['errArr'] = $errArr;

  $template = $twig->loadTemplate($template);
  $template->display($context);