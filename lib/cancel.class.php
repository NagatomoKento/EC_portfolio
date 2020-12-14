<?php
namespace ECweb\lib;

class Cancel
{
  private $db = null;
  
  public function __construct($db)
  {
    $this->db = $db;
  }
  
  public function setDeleteflg($customer_no)
  {
    $table = ' member ';
    $insData = [ 'delete_flg' => 1 ,
                 'delete_date' => date('Y-m-d H:i:s')];
    $where = 'mem_no=? ';
    $arrWhereVal = [$customer_no];
    return $this->db->update($table, $insData, $where, $arrWhereVal);
  }
  
  public function unsetDeleteflg($customer_no)
  {
    $table = ' member ';
    $insData = [ 'delete_flg' => 0 ,
                 'delete_date' => null];
    $where = 'mem_no=? ';
    $arrWhereVal = [$customer_no];
    return $this->db->update($table, $insData, $where, $arrWhereVal);
  }

  public function checkDeleteflg($customer_no)
  {
  $table = ' member ';
  $column = ' delete_flg ';
  $where = 'customer_no=? ';
  $arrWhereVal = [$customer_no];
  return $this->db->select($table, $column, $where, $arrWhereVal);
  }
}