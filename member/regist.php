<?php
// http://localhost/DT/ECweb/member/regist.php

namespace ECweb\member;

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use ECweb\member\master\initMaster;
use ECweb\Bootstrap;

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,[
  'cache' => Bootstrap::CACHE_DIR
]);

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
  'tel3' => '',
  'agree' => ''
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

$template = $twig->loadTemplate('regist.html.twig');
$template->display($context);