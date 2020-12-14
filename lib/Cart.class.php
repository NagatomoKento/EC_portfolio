<?php
namespace ECweb\lib;
use ECweb\Bootstrap;

class Cart
{
  private $db = null;

  public function __construct($db = null)
  { $this->db = $db; }
  
  public function insCartData($customer_no, $item_id, $num)
  {
    $table = ' cart ';
    $insData = [
      'customer_no' => $customer_no,
      'item_id' => $item_id,
      'num' => $num,
      'regist_date' => date("Y-m-d H:i:s")
    ];
    return $this->db->insert($table, $insData);
  }
  
  public function CheckCartData($customer_no, $item_id)
  {
    $table = ' cart ';
    $column = ' crt_id, num ';
    $where = ' customer_no=? AND item_id=? AND delete_flg=?';
    $arrWhereVal = [$customer_no, $item_id, 0];
    return $res =  $this->db->select($table, $column, $where, $arrWhereVal);
  }
  
  public function ChangeItemNum($cart_id, $num)
  {
    $table = ' cart ';
    $insData = [
      'crt_id' => $cart_id,
      'num' => $num,
      'update_date' => date("Y-m-d H:i:s")
    ];
    $where = "crt_id=?";
    $arrWhereVal = [$cart_id];
    return $resb = $this->db->update($table, $insData, $where, $arrWhereVal);
  }

  public function getCartData($customer_no)
  {
    $table = ' cart c LEFT JOIN item i ON c.item_id = i.item_id ';
    $column = 'c.crt_id, c.customer_no, i.item_id, i.item_name, i.price, i.image, SUM(c.num) AS NUM ';
    $where = ' c.customer_no = ? AND c.delete_flg = ? GROUP BY i.item_id';
    $arrVal = [$customer_no, 0];
    return $this->db->select($table, $column, $where, $arrVal);
  }

  // public function reduceCartData($crt_id)
  // {
  //   $table = ' cart ';
  //   $insData = ['delete_flg' => 1];
  //   $where = ' crt_id = ? ';
  //   $arrWhereVal = [$crt_id];
  //   return $this->db->update($table, $insData, $where, $arrWhereVal);
  // }
  
  public function clearCart($customer_no,$cart_id)
  {
    $table = ' cart ';
    $insData = ['delete_flg' => 1];
    $where = ' customer_no = ? AND $crt_id = ?';
    $arrWhereVal = [$customer_no, $cart_id];
    return $this->db->update($table, $insData, $where, $arrWhereVal);
  }
  
  public function getPurchase_unit($customer_no)
  {
    $table = ' purchase ';
    $column = ' purchase_unit ';
    $where = ' customer_no=? order by purchased_id desc limit 1';
    $arrWhereVal = [$customer_no];
    return $this->db->select($table, $column, $where, $arrWhereVal);
  }

  public function getTotalnum($customer_no)
  {

    $table = ' cart c';
    $column = 'SUM( num ) AS num';
    $where = ' c.customer_no= ? AND c.delete_flg= ?';
    $arrWhereVal = [$customer_no, 0];
    $res = $this->db->select($table, $column, $where, $arrWhereVal);
    return $num = ($res !== false && count($res) !== 0) ? $res[0]['num'] : 0;
  }
}
