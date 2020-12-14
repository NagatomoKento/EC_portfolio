<?php
// http://localhost/DT/ECweb/login/login.php
namespace ECweb\login;
session_start();

require_once dirname(__FILE__) . './../Bootstrap.class.php';
use ECweb\lib\PDODatabase;
use ECweb\lib\Login;
use ECweb\Bootstrap;

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,[
  'cache' => Bootstrap::CACHE_DIR
]);

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);

$login = new Login($db);


if(empty($_POST) !== true){
  $dataArr = [
    'email' => $_POST['email'],
    'password' => $_POST['password'],
    'errmsg' => 'メールアドレスもしくはパスワードが異なります。'
  ];
} else {
    $dataArr = [
      'email' => '',
      'password' => '',
      'errmsg' => ''
    ];
}

if(isset($_POST['guest_login'])===true){
  $_SESSION['customer_no'] = 1;
  $_SESSION['login_flg'] = "1";
  $_SESSION['family_name'] = "永友";
  $_SESSION['first_name'] = "健斗";
  header('Location:' . Bootstrap::ENTRY_URL_SHOP . 'item_list.php');
  exit();
}

$res = $login->checklogin($dataArr);

if($res === true){
  $get_mem_no = $login->getmem_no($dataArr);
  $mem_no = $get_mem_no[0]['mem_no'];
  $get_mem_name = $login->selectname($mem_no);
  $mem_family_name = $get_mem_name[0]['family_name'];
  $mem_first_name = $get_mem_name[0]['first_name'];
  $_SESSION['customer_no'] = $mem_no;
  $_SESSION['login_flg'] = "1";
  $_SESSION['family_name'] = $mem_family_name;
  $_SESSION['first_name'] = $mem_first_name;
  header('Location:' . Bootstrap::ENTRY_URL_SHOP . 'item_list.php');
  } else {
  $context = [];
  $context['dataArr'] = $dataArr ;
  $template = $twig->loadTemplate('login.html.twig');
  $template->display($context);
}
