<?php
namespace ECweb\admin;
session_start();

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use ECweb\Bootstrap;
use ECweb\lib\PDODatabase;
use ECweb\lib\Admin;

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,[
  'cache' => Bootstrap::CACHE_DIR
]);
$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$admin = new Admin($db);

$newsArr = $admin->getNews();

$neswArr = [
  'news_no' => '',
  'release_date' => '',
  'news' => '',
  'regist_date' => ''
];


$template = 'admin_news.html.twig';

if($_POST !== []){
  unset($_POST['complete']);
  $newsArr = $_POST;
  $newsArr['regist_date'] = date('Y-m-d H:i:s');
  $res = $db->insert('news', $newsArr);
  if($res === true){
    $template ='admin_news_comp.html.twig';
  }
}

  $context = [];
  $context['newsArr'] = $newsArr;
  $context['session'] = $_SESSION;
  $template = $twig->loadTemplate($template);
  $template->display($context);