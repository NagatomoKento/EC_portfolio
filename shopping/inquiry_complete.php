<?php

namespace ECweb\shopping;
require_once dirname(__FILE__) . './../Bootstrap.class.php';

use ECweb\lib\PDODatabase;
use ECweb\lib\Common_inquiry;
use ECweb\Bootstrap;


$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,[
  'cache' => Bootstrap::CACHE_DIR
]);

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);

$common_inquiry = new Common_inquiry();


// 直接URL打ち込みされた場合
if(empty($_POST) === true){
  $mode = 'direct';
}

// From 入力画面
if(isset($_POST['complete']) === true){
  $mode = 'complete';
}


switch($mode){
  case 'direct':
    header('Location:' . Bootstrap::ENTRY_URL_SHOP . 'inquiry_form.php');
    exit();
  break;


  case 'complete':
    unset($_POST['complete']);
    $inquiryArr = $_POST;

    if(isset($_POST['name_kana']) === false){
      $inquiryArr['name_kana'] = "";
    }
    if(isset($_POST['corporate']) === false){
      $inquiryArr['corporate'] = '';
    }
    
    $errArr = $common_inquiry->inquiry_errorCheck($inquiryArr);
    $err_check = $common_inquiry->inquiry_getErrorFlg();
    // $err_check = true ⇨エラーなし
    if($err_check === true){
      $inquiryArr['regist_date'] = date('Y-m-d H:i:s');
      $res = $db->insert('inquiry', $inquiryArr);
      if($res === true){
        $template ='inquiry_complete.html.twig';
      }
    } else {
        $template = 'inquiry_form.html.twig';
        }
  break;
}

$context = [];
$context['inquiryArr'] = $inquiryArr;
$context['errArr'] = $errArr;

$template = $twig->loadTemplate($template);
$template->display($context);