<?php
namespace ECweb\lib;

class Admin
{
  private $db = null;
  private $loginflg = false;
  
  public function __construct($db)
  {
    $this->db = $db;
  }
  
  public function checklogin($dataArr)
  {
  $staff_id = $this->db->select('staff','staff_id','staff_id=?',[$dataArr['staff_id']]);
  $staff_pass = $this->db->select('staff','staff_pass','staff_id=?',[$dataArr['staff_id']]);

  if(empty($staff_id) !== true && password_verify($dataArr['staff_pass'],$staff_pass[0]['staff_pass']) === true){
    $this->loginflg = true;
  } 
  return $this->loginflg;
  }

  public function getstaff_no($dataArr)
  {
    return $get_staff_no = $this->db->select('staff','staff_no','staff_id=?',[$dataArr['staff_id']]);
  } 

  public function selectname($staff_no)
  {
    $table = 'staff';
    $col = ' staff_name';
    $where = ' staff_no = ? ';
    $arrVal = [$staff_no];
    return $res = $this->db->select($table, $col, $where, $arrVal);
  }

  public function setLoginflg($staff_id)
  {
    $table = ' staff ';
    $insData = [ 'login_flg' => 1 ];
    $where = 'staff_id=? ';
    $arrWhereVal = [$staff_id];
    $this->db->update($table, $insData, $where, $arrWhereVal);
    return $this->login_flg = 1;
  }
  
  public function unsetLoginflg($staff_id)
  {
    $table = ' staff ';
    $insData = [ 'login_flg' => 0 ];
    $where = 'staff_id=? ';
    $arrWhereVal = [$staff_id];
    $this->db->update($table, $insData, $where, $arrWhereVal);
    return $this->login_flg = 0;
  }

  public function checkLoginflg($staff_id)
  {
  $table = ' staff ';
  $column = ' login_flg ';
  $where = ' staff_id=? ';
  $arrWhereVal = [$staff_id];
  return $this->db->select($table, $column, $where, $arrWhereVal);
  }

  public function getNews()
  {
    $table = ' news ';
    $col = "*";
    $where = '';
    $arrVal = []; 
    
    $res = $this->db->select($table, $col, $where, $arrVal);
    return($res !== false && count($res) !== 0) ? $res : false;
  }

  public function errorCheck($dataArr)
  {
    return $get_staff_no = $this->db->select('staff','staff_no','staff_id=?',[$dataArr['staff_id']]);
  }

}