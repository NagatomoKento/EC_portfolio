<?php
// http://localhost/DT/ECweb/admin/admin.php
namespace ECweb\admin;
session_start();

require_once dirname(__FILE__) . './../Bootstrap.class.php';
use ECweb\lib\PDODatabase;
use ECweb\lib\Admin;
use ECweb\Bootstrap;

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,[
  'cache' => Bootstrap::CACHE_DIR
]);
$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);

$admin_login = new Admin($db);

if(empty($_POST) !== true){
  $dataArr = [
    'staff_id' => $_POST['staff_id'],
    'staff_pass' => $_POST['staff_pass'],
    'errmsg' => 'スタッフIDもしくはパスワードが異なります。'
  ];
} else {
    $dataArr = [
      'staff_id' => '',
      'staff_pass' => '',
      'errmsg' => ''
    ];
}

$dataArr['loginflg'] = false;
$dataArr['loginflg'] = $admin_login->checklogin($dataArr);


if($dataArr['loginflg'] === true){
  $get_staff_no = $admin_login->getstaff_no($dataArr);
  $staff_no = $get_staff_no[0]['staff_no'];
  $get_staff_name = $admin_login->selectname($staff_no);
  $staff_name = $get_staff_name[0]['staff_name'];
  $_SESSION['staff_no'] = $staff_no;
  $_SESSION['login_flg'] = "1";
  $_SESSION['staff_name'] = $staff_name;
  header('Location:' . Bootstrap::ENTRY_URL_ADMIN . 'admin_top.php');
} else {
  $template = 'admin.html.twig';
  $context = [];
  $context['dataArr'] = $dataArr;
  $template = $twig->loadTemplate($template);
  $template->display($context);
}