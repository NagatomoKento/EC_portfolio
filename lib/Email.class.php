<?php
namespace ECweb\lib;

class Email{
  private $db = null;
  private $auto_reply_subject = null;
  private $auto_reply_text = null;
  private $customerArr = null;
  private $purchase_unit_no = null;

  public function __construct($db)
  {
    $this->db = $db;
  }
  
  public function preparaEmail($dataArr,$customerArr,$purchase_unit_no)
  {
    $this->customerArr = $customerArr;
    $this->purchase_unit_no = $purchase_unit_no;
    // タイトル
    $this->auto_reply_subject = '【南国農園】ご購入ありがとうございます。';
    // 本文
    $auto_reply_text = $this->customerArr['family_name'].$this->customerArr['first_name'].'様';
    $auto_reply_text .= "この度は、ご購入頂き誠にありがとうございます。下記の内容でご購入を受け付けました。\n";
    $auto_reply_text .= "購入ユニットNo.". $this->purchase_unit_no . "\n\n";

    $count = count($dataArr);
    for($i=0; $i<$count; $i++){
      $price_arr[$i] = $dataArr[$i]['price'] * $dataArr[$i]['NUM'];
      $auto_reply_text .= "商品名：" . $dataArr[$i]['item_name'] . "  ";
      $auto_reply_text .= "個数：" .$dataArr[$i]['NUM'] ."\n";
      $auto_reply_text .= "単価：" .$dataArr[$i]['price'].'円' ;
      $auto_reply_text .= "小計：" . $price_arr[$i]. "円（税込み）" ."\n\n\n\n";
      $price_total[] = $price_arr[$i]++;
    }
    $auto_reply_text .= "合計金額：". array_sum($price_total) ."円（税込み）". "\n\n";
    $auto_reply_text .= "お振込は下記口座へお願い致します。". "\n";
    $auto_reply_text .= "*********************************************". "\n";
    $auto_reply_text .= "お振込口座". "\n";
    $auto_reply_text .= "南国銀行  南国支店(759)". "\n";
    $auto_reply_text .= "普通  759759". "\n";
    $auto_reply_text .= "ナンゴクノウエン（カ ". "\n";
    $auto_reply_text .= "*********************************************" . "\n\n\n";
    $auto_reply_text .= "またのご利用を心よりお待ちしてます。" . "\n";
    $auto_reply_text .= "南国農園 事務局";
    $this->auto_reply_text = $auto_reply_text;
  }

  public function sendEmail($customer_no)
  {
    $to = $this->db->select('member','email','mem_no=?',[$customer_no]);
    return$meil = mb_send_mail($to[0]['email'], $this->auto_reply_subject, $this->auto_reply_text);
  }
}