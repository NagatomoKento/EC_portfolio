<?php
namespace ECweb\lib;

class Item
{
  public $cateArr = [];
  public $db = null;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function getCategoryList()
  {
    $table = ' category ';
    $col = ' ctg_id, category_name ';
    $res = $this->db->select($table, $col);
    return $res;
  }

  public function getItemList($ctg_id)
  {
    $table = ' item ';
    $col = ' item_id, item_name, price, image, ctg_id ';
    $where = ($ctg_id !== '') ? '  ctg_id = ? ': '';
    $arrVal = ($ctg_id !== '')? [$ctg_id] : [];
    $res = $this->db->select($table,$col,$where,$arrVal);
    return ($res !== false && count($res) !== 0)? $res : false;
  }

  // public function getSearchItemList($searchWord)
  // {
  //   $table = ' item ';
  //   $col = ' item_id, item_name, price, image ';
  //   $where = ($searchWord !== []) ? ' item_name like ? ': '';
  //   $arrVal = ($searchWord !== [])? ['%'.$searchWord['item_name'].'%'] : [];
  //   $res = $this->db->select($table,$col,$where,$arrVal);
  //   return ($res !== false && count($res) !== 0)? $res : false;
  // }

  public function getSearchItemList($searchWord)
  {
    $table = ' item ';
    $col = ' item_id, item_name, price, image ';
    $where = ($searchWord !== []) ? ' item_name like ? OR detail like ? ': '';
    $arrVal = ($searchWord !== [])? ['%'.$searchWord['item_name'].'%' , '%'.$searchWord['item_name'].'%'] : [];

    $res = $this->db->select($table,$col,$where,$arrVal);
    return ($res !== false && count($res) !== 0)? $res : false;
  }

  //商品の詳細情報を取得
  public function getItemDetailData($item_id)
  {
    $table = ' item ';
    $col = ' item_id, item_name, detail, price, image, ctg_id, regist_date ';
    $where = ($item_id !== '') ? ' item_id = ?' : '';
    $arrVal = ($item_id !== '') ? [$item_id] : []; 
    
    $res = $this->db->select($table, $col, $where, $arrVal);
    return($res !== false && count($res) !== 0) ? $res : false;
  }

  public function getNews()
  {
    $table = ' news ';
    $col = ' release_date, news';
    $where = 'release_date limit 5';
    $arrVal = []; 
    
    $res = $this->db->select($table, $col, $where, $arrVal);
    foreach($res as $key => $value){
      $modified[$key] = $value['release_date'];
    }
    array_multisort($modified, SORT_DESC, $res);
    return($res !== false && count($res) !== 0) ? $res : false;
  }

}