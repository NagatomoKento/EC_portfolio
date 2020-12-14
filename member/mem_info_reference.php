<?php
namespace ECweb\member;
session_start();

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use ECweb\Bootstrap;
use ECweb\lib\PDODatabase;
use ECweb\lib\Login;

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,[
  'cache' => Bootstrap::CACHE_DIR
]);
$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$login = new Login($db);
$login->check_Login_session();

$customer_no = $_SESSION['customer_no'];

$template = ($_SESSION['login_flg'] === "1")?
  'mem_info_reference.html.twig' : 'request_login.html.twig';

$column = 'mem_no, '
        . 'family_name, '
        .'first_name, '
        .'family_name_kana, '
        .'first_name_kana, '
        .'sex, '
        .'year, '
        .'month, '
        .'day, '
        .'zip1, '
        .'zip2, '
        .'address, '
        .'email, '
        .'tel1, '
        .'tel2, '
        .'tel3 ';

$dataArr = $db->select($table='member',$column, $where='mem_no=?', $arrVal=[$customer_no]);

  $context = [];
  if($dataArr !== []){
    $context['dataArr'] = $dataArr[0];
  }
  $context['session'] = $_SESSION;
  $template = $twig->loadTemplate($template);
  $template->display($context);