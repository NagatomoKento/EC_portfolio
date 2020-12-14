<?php
namespace ECweb\lib;

class Common_inquiry
{
  private $dataArr = [];
  private $errArr  = [];

  public function __construct()
  {
  }

  public function inquiry_errorCheck($dataArr)
  {

    $this->dataArr = $dataArr;
    $this->createErrorMessage();
    $this->nameCheck();
    $this->mailCheck();
    $this->subjectCheck();
    $this->contentsCheck();
    return $this->errArr;
  }

  private function createErrorMessage()
  {
    foreach($this->dataArr as $key => $val){
      $this->errArr[$key] = '';
    }
  }

  private function nameCheck()
  {
    if($this->dataArr['name'] === ''){
      $this->errArr['name'] = 'お名前を入力してください';
    }
  }

  private function mailCheck()
  {
    if(preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+[a-zA-Z0-9\._-]+$/',$this->dataArr['email']) === 0){
      $this->errArr['email'] = 'メールアドレスを正しい形式で入力してください';
    }
  }


  private function subjectCheck()
  {
    if($this->dataArr['subject'] === ''){
      $this->errArr['subject'] = '件名を入力してください';
    }
  }


  private function contentsCheck()
  {
    if($this->dataArr['contents'] === ''){
      $this->errArr['contents'] = 'お問い合わせ内容を入力してください';
    }
  }


  // 上記、チェックの結果、
  // エラーなし ⇨ $err_check = true
  // エラーあり ⇨ $err_check = false

  public function inquiry_getErrorFlg()
  {
    $err_check = true;
    foreach($this->errArr as $key => $value){
      if($value !== ''){
        $err_check = false;
      }
    }
    return $err_check;
  }
}