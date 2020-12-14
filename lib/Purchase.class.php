<?php
namespace ECweb\lib;

class Purchase
{
  private $db = null;

  public function __construct($db)
  { $this->db = $db; }
  
  public function insPurchase($dataArr,$purchase_unit)
  {
    $table = ' purchase ';
    $insData = [
      'purchase_unit' => $purchase_unit,
      'customer_no' => $dataArr['customer_no'],
      'item_id' => $dataArr['item_id'],
      'item_name' => $dataArr['item_name'],
      'price' => $dataArr['price'],
      'image' => $dataArr['image'],
      'quantity' => $dataArr['NUM'],
      'regist_date' => date("Y-m-d H:i:s")
    ];
    return $this->db->insert($table, $insData);
  }

    public function purchase_history($customer_no)
    {
    $table = ' purchase ';
    $column = 'purchase_unit, item_id, item_name, price, image, quantity, regist_date';
    $where = ' customer_no=? ';
    $arrVal = [$customer_no];
    return $this->db->select($table, $column, $where, $arrVal);
  }
  
  public function sales_view($timeArr)
  {
    $table = 'purchase';
    
    $colum =  " purchased_id, "
              ." purchase_unit, "
              ." customer_no, "
              ." item_id, "
              ." item_name, "
              ." price, "
              ." image, "
              ." quantity, "
              ." regist_date ";
    $where = "regist_date between ? and ?";
    $arrVall = [$timeArr['from'], $timeArr['to']];
    
    return $dataArr = $this->db->select($table,$colum,$where,$arrVall);
  }

  public function csv_output($headingArr,$salesArr,$fileName)
  {
    $dirPath = "{{constant('ECweb\\Bootstrap::ENTRY_URL')}}CSV"; 
    // ディレクトリの作成
    if(!file_exists($dirPath)){
      mkdir($dirPath,0700);
    }
    // ファイルの作成
    $createCsvFilePath = $dirPath."/".$fileName.".csv";
    if(!file_exists($createCsvFilePath)){
      touch($createCsvFilePath);
    }
    $createCsvFile = fopen($createCsvFilePath, "w");
    if($createCsvFile){
      foreach($headingArr as $line){
        fputcsv($createCsvFile, $line);
      }
    }
    $createCsvFile = fopen($createCsvFilePath, "a");
    if($createCsvFile){
      foreach($salesArr as $line){
        fputcsv($createCsvFile, $line);
      }
    }
    fclose($createCsvFile);
  } 
}