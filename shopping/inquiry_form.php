<?php
// http://localhost/DT/ECweb/shopping/inquiry_form.php

namespace ECweb\shopping;
session_start();

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use ECweb\member\master\initMaster;
use ECweb\lib\PDODatabase;
use ECweb\Bootstrap;


$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

$inquiryArr = [
  'name' => '',
  'name_kana' => '',
  'corporate' => '',
  'email' => '',
  'subject' => '',
  'contents' => ''
];

$errArr = [];
foreach($inquiryArr as $key => $value){
  $errArr[$key] = '';
}

$agreeArr = initMaster::getPresonalInfo();

$context = [];
$context['inquiryArr'] = $inquiryArr;
$context['agreeArr'] = $agreeArr;

$template = $twig->loadTemplate('inquiry_form.html.twig');
$template->display($context);