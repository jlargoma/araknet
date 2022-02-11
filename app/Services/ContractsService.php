<?php

namespace App\Services;

class ContractsService {
  

  private $contrats = [
      'cesion-antena'=>['code'=>1001,'title'=>'CONTRATO DE CESION ANTENA','signed'=>false]
      ];
  
  function getContracts(){
    return $this->contrats;
  }
  /**
   * 
   */
  function getContract($type){
    if (isset($this->contrats[$type])){
      return $this->contrats[$type];
    }
    return null;
  }
  function getContractBy_code($code){
    foreach ($this->contrats as $k=>$v){
      if ($v['code'] == $code){
        return $k;
      }
    }
    return null;
  }
  /**
   * 
   * @param type $uID
   * @param type $type
   * @return boolean
   */
  function getLinkContracts($cID,$type){
    $link = '' ;
    $code = 0;
    if (isset($this->contrats[$type])){
      $code = $this->contrats[$type]['code'];
      $link = \URL::to('/firmar/').'/';
      $link .= LinksService::getLink([$cID,$code,time()]);
      return $link;
    }
    return null;
  }
  
  function getContractData($code, $control){
    $data = \App\Services\LinksService::getLinkData($code, $control);
    $dView = [
        'name' => '',
        'tit' => '',
        'key' => '',
        'msg' => '',
        'oCustomer' => null,
        'url' => "/firmar/$code/$control",
    ];
    if (!$data) {
      abort(404);
      exit();
    }

    $oCustomer = \App\Models\Customers::find($data[0]);
    if (!$oCustomer) {
      abort(404);
      exit();
    }
    $dView['oCustomer'] = $oCustomer;

    $dView['key'] = $this->getContractBy_code($data[1]);
    if (!$dView['key']) {
      abort(404);
      exit();
    }

    $aContract = $this->getContract($dView['key']);
    $dView['tit'] = $aContract['title'];
    
    return $dView;
  }
  
}
