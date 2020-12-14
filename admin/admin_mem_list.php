<?php
// http://localhost/DT/ECweb/admin/mem_list.php
namespace ECweb\admin;
session_start();

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use ECweb\Bootstrap;
use ECweb\member\master\initMaster;
use ECweb\lib\PDODatabase;
use ECweb\lib\Common;

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);

$table = 'member';
$colum =  " mem_no, "
          ." family_name, "
          ." first_name, "
          ." family_name_kana, "
          ." first_name_kana, "
          ." sex, "
          ." email, "
          ." agree, "
          ." regist_date ";
$where = "mem_no";
$arrVall = [];

  $dataArr = $db->select($table,$colum,$where,$arrVall);
  
  $context = [];
  $context['dataArr'] = $dataArr;
  $context['session'] = $_SESSION;
  $template = $twig->loadTemplate('admin_mem_list.html.twig');
  $template->display($context);
