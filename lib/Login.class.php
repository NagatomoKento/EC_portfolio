<?php

namespace ECweb\lib;

class Login
{
  private $email = '';
  private $password = '';
  private $db = null;
  private $loginflg = false;
  
  public function __construct($db)
  {
    $this->db = $db;
  }
  
  public function checklogin($dataArr)
  {
  $resemail = $this->db->select('member','email','email=?',[$dataArr['email']]);
  $respassword = $this->db->select('member','password','email=?',[$dataArr['email']]);

  if(empty($resemail) !== true && password_verify($dataArr['password'],$respassword[0]['password']) === true){
    $this->email =$resemail;
    $this->password =$respassword;
    $this->loginflg = true;
  } 
  return $this->loginflg;
  }

  public function getmem_no($dataArr)
  {
    return $get_mem_no = $this->db->select('member','mem_no','email=?',[$dataArr['email']]);
  } 

  public function selectname($mem_no)
  {
    $table = ' member ';
    $col = ' family_name, first_name ';
    $where = ' mem_no = ? ';
    $arrVal = [$mem_no];
    return $res = $this->db->select($table, $col, $where, $arrVal);
  }

  public function check_Login_session()
  {
    if(isset($_SESSION['customer_no']) === false){
      $_SESSION['customer_no'] = '';
    }
    if (isset($_SESSION['login_flg']) === false){
      $_SESSION['login_flg'] = '';
    }
    if (isset($_SESSION['family_name']) === false){
      $_SESSION['family_name'] = '';
    }
    if (isset($_SESSION['first_name']) === false){
      $_SESSION['first_name'] = '';
    }
  }

  public function unset_session($SESSION)
  {
      $_SESSION['customer_no'] = '';
      $_SESSION['login_flg'] = '';
      $_SESSION['family_name'] = '';
      $_SESSION['first_name'] = '';
      return $_SESSION;
  }


  // public function setLoginflg()
  // {
  //   $table = ' session ';
  //   $insData = [ 'login_flg' => 1 ];
  //   $where = 'customer_no=? ';
  //   $arrWhereVal = [$this->mem_no];
  //   $this->db->update($table, $insData, $where, $arrWhereVal);
  //   return $this->login_flg = 1;
  // }
  
  // public function unsetLoginflg($customer_no)
  // {
  // $table = ' session ';
  // $insData = [ 'login_flg' => 0 ];
  // $where = 'customer_no=? ';
  // $arrWhereVal = [$this->mem_no];
  // $this->db->update($table, $insData, $where, $arrWhereVal);
  // return $this->login_flg = 0;
  // }


}