<?php
namespace ECweb\member;
session_start();

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use ECweb\lib\PDODatabase;
use ECweb\member\master\initMaster;
use ECweb\Bootstrap;
use ECweb\lib\Login;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,[
  'cache' => Bootstrap::CACHE_DIR
]);
$login = new Login($db);
$login->check_Login_session();

$template = ($_SESSION['login_flg'] === "1")?
  'mem_info_update.html.twig' : 'request_login.html.twig';;

$dataArr = [
  'password' => '',
  'passwordConfirm' => '',
  'family_name' => '',
  'first_name' => '',
  'family_name_kana' => '',
  'first_name_kana' => '',
  'sex' => '',
  'year' => '',
  'month' => '',
  'day' => '',
  'zip1' => '',
  'zip2' => '',
  'address' => '',
  'email' => '',
  'tel1' => '',
  'tel2' => '',
  'tel3' => ''
];

$errArr = [];
foreach($dataArr as $key => $value){
  $errArr[$key] = '';
}

list($yearArr, $monthArr, $dayArr) = initMaster::getDate();
$sexArr = initMaster::getSex();
$agreeArr = initMaster::getPresonalInfo();

$context = [];
$context['yearArr'] = $yearArr ;
$context['monthArr'] = $monthArr ;
$context['dayArr'] = $dayArr ;
$context['sexArr'] = $sexArr ;
$context['agreeArr'] = $agreeArr ;
$context['dataArr'] = $dataArr ;
$context['errArr'] = $errArr ;
$context['session'] = $_SESSION;

$template = $twig->loadTemplate($template);
$template->display($context);