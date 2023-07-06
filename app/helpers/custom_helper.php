<?php
   function decimalallow($val,$zero = 4){
      return number_format((float)$val, $zero, '.', '');
   }
   function amountformate($v, $d = 4, $c = "", $cl = 'l'){
      $sendvalue = number_format($v,$d);
      if($cl == "l" && $c != ""){
         $sendvalue = $c." ".$sendvalue;
      }
      else if($cl == "r" && $c != ""){
         $sendvalue = $sendvalue." ".$c;
      }
      return $sendvalue;
   }
   function dateformate($date,$formate = "Y-m-d"){
      return date($formate, strtotime($date));
   }
   function get_traking_status($traking){
      $status = "Pending";
      $rows = array();
      if($traking != ""){
         $rows = json_decode(file_get_contents('https://cod.callcourier.com.pk/api/CallCourier/GetTackingHistory?cn='.$traking));
      }
      foreach($rows as $row){
         $status = $row->ProcessDescForPortal;
      }
      return $status;
   }
   function get_traking_data($traking){
      $senddata['status'] = "Pending";
      $status = "Pending";
      $rows = array();
      if($traking != ""){
         $rows = json_decode(file_get_contents('https://cod.callcourier.com.pk/api/CallCourier/GetTackingHistory?cn='.$traking));
      }
      foreach($rows as $row){
         $senddata['status'] = $row->ProcessDescForPortal;
         $senddata['cod_amount'] = $row->codAmount;
      }
      return $senddata;
   }
   function get_traking($traking){
      $rows = array();
      if($traking != ""){
         $rows = file_get_contents('https://cod.callcourier.com.pk/api/CallCourier/GetTackingHistory?cn='.$traking);
      }
      return $rows;
   }
