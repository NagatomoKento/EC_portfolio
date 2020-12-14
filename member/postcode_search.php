<?php

namespace ECweb\member;

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use ECweb\lib\PDODatabase;
use ECweb\Bootstrap;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);

if(isset($_GET['zip1']) === true && isset($_GET['zip2']) === true){
  $zip1 = $_GET['zip1'];
  $zip2 = $_GET['zip2'];

  $query = " SELECT "
            . " pref, "
            . " city, "
            . " town "
            . " FROM "
            . " postcode "
        . " WHERE "
            . " zip= " . $db->str_quote($zip1.$zip2)
            . " LIMIT 1 ";
  // LIMIT 1 ：同じ数字・文字列があっても、初めの一つ目のみを取得する。

  $res = $db->select($query);
  echo($res !== "" && count($res) !== 0)? $res[0]['pref'] . $res[0]['city'] . $res[0]['town']: '';
  // count(変数に含まれるすべての要素、 あるいはオブジェクトに含まれる何かの数を数える。)：戻り値は、正しい時1、間違い時は0
  // AJAX で呼び出している場合のechoは
  // ⭐️呼び出し元のcommon.jsのdataに代入される。
  // ⇨echoすると、common.jsのfunction(data)のdataに入る
} else {
  echo "no";
}