<?php
namespace ECweb\lib;

class PageNation{
  private $loginflg = false;
  
  public function __construct($db)
  {
    $this->db = $db;
  }


  public function pageNation($unit, $num, $records){

    if(!empty($records)){
      $max_record = count($records);
      $max_page = ceil($max_record / $unit);
      
      if($num < 2){
        $start_record_num = 1;
      } else {
        $start_record_num = ($unit * ($num - 1)) + 1;
      }
      
      $pagenation = array();
      $pagenation['pagenum'] = $num;
      $pagenation['maxpage'] = (int)$max_page;
      $pagenation['maxrecord'] = $max_record ;
      $pagenation['record'] = array();
      
      for($i = $start_record_num; $i < $start_record_num + $unit; $i++){
        if(!empty($records[$i])){
          $pagenation['record'][$i] = $records[$i];
        }
      }
      return $pagenation; 
    }
  }

}