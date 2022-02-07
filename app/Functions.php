<?php

//die('asdf');
function formatToImport($data) {
    return utf8_decode($data);
}

function lstMonths($min = true) {
  
    $lstMonths = lstMonthsSpanish($min);
    unset($lstMonths[0]);
    return $lstMonths;
}

function lstMonthsSpanish($min = true) {
    if ($min) {
        return['', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sept', 'Oct', 'Nov', 'Dic'];
    } else {
        return ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    }
}

function getMonthSpanish($m, $min = true) {
    $arrayMonth = lstMonthsSpanish($min);
    $m = intval($m);
    return isset($arrayMonth[$m]) ? $arrayMonth[$m] : '';
}

function convertDateToDB($date){
  $date= trim($date);
  if ($date){
    $aux = explode('/',$date);
    if (is_array($aux) && count($aux)==3){
      $month = ($aux[1]<10) ? '0'.intval($aux[1]) : $aux[1];
      $day = ($aux[0]<10) ? '0'.intval($aux[0]) : $aux[0];
      return $aux[2].'-'.$month.'-'.$day;
    }
  }
  return null;
}
  
function convertSpanishDate($date) {
    $aux = explode(',', $date);
    if (isset($aux[1])) {
        $date = explode(' de ', $aux[1]);
        if (count($date) == 3) {
            $day = intval($date[0]);
            if ($day < 10)
                $day = '0' . $day;
            $months = lstMonthsSpanish(FALSE);
            $month = array_search(trim($date[1]), $months);
            if ($month < 10)
                $month = '0' . $month;

            return intval($date[2]) . '-' . $month . '-' . $day;
        }
    }
    return '';
}

function convertDateToShow($date, $yearsComplete = false) {
    $date = trim($date);
    if ($date) {

        $aux = explode('-', $date);
        if (is_array($aux) && count($aux) == 3) {
            if ($yearsComplete)
                return $aux[2] . '/' . $aux[1] . '/' . $aux[0];
            return $aux[2] . '/' . $aux[1] . '/' . ($aux[0] - 2000);
        }
    }
    return null;
}

function convertDateToShow_text($date, $year = false) {
    $date = trim($date);
    if ($date) {

        $aux = explode('-', $date);
        if (is_array($aux) && count($aux) == 3) {
          $aux2 = explode(' ', $aux[2]);
          if ($year)
              return $aux2[0] . ' ' . getMonthSpanish($aux[1]) . ', ' . ($aux[0] - 2000);
          return $aux2[0] . ' ' . getMonthSpanish($aux[1]);
        }
    }
    return null;
}

function dateMin($date) {
    $date = trim($date);
    if ($date) {
        $aux = explode('-', $date);
        if (is_array($aux) && count($aux) == 3) {
          $aux2 = explode(' ', $aux[2]);
          return $aux2[0] . ' ' . getMonthSpanish(intval($aux[1]));
        }
    }
    return null;
}

function rates_codes() {
    return [
        'TARIFA_CL1',
        'TARIFA_CL2',
        'TARIFA_PT1',
        'TARIFA_PT2',
        'TARIFA_FI',
        'FARIFA_NU',
        'TARIFA_SALGE',
        'TARIFA_SALIM'
    ];
}

function assetV($uri) {
    $uri_asset = asset($uri);
    $v = config('app.version', 'v1.0.6');
    return $uri_asset . '?' . $v;
}

function getYearActive() {

    if (isset($_COOKIE['ActiveYear'])) {
        return $_COOKIE['ActiveYear'];
    }
    setcookie('ActiveYear', date('Y'), time() + (86400 * 30), "/"); // 86400 = 1 day
    return date('Y');
}

function colors() {
    return ['#9b59ff', '#295d9b', '#10cfbd', '#ffa116', '#871282', '#066572', '#06931d', '#1fa7c0', '#b2d33d', '#3aaa49'];
}
function printColor($id){
  $lst = colors();
  $count = count($lst);
  if ($id<$count) return $lst[$id];
  
  $id = $id/$count;
  return $lst[$id];
}

function show_isset($index, $array,$get=false) {
    if (isset($array[$index])) {
      if ($get) return $array[$index];
        echo $array[$index];
    }
}

function listDaysSpanish($min = false) {
    if ($min) {
        $array = [
            1 => 'Lun',
            2 => 'Mar',
            3 => 'Mié',
            4 => 'Jue',
            5 => 'Vie',
            6 => 'Sáb',
            0 => 'Dom',
        ];
    } else {
        $array = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            0 => 'Domingo',
        ];
    }
    return $array;
}

function getNameDay($dw, $min = false) {
    $wds = listDaysSpanish($min);
    return isset($wds[$dw]) ? $wds[$dw] : '';
}

function moneda($mount,$cero=true,$decimals=0){
  if ($cero)  return number_format($mount, $decimals, ',', '.' ).' €';
  
  if ($mount != 0) return number_format($mount, $decimals, ',', '.' ).' €';
  return '--';
  
}

function mformat($mount,$decimals=0){
  if ($mount != 0) return number_format($mount, $decimals, ',', '.' );
  return '--';
}

function str_limit($txt,$limit,$end='...'){
return \Illuminate\Support\Str::limit($txt,$limit,$end);
}
function str_random($limit=40){
    return Str::random($limit);
}
function slugify($text)
{
  // replace non letter or digits by -
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, '-');

  // remove duplicate -
  $text = preg_replace('~-+~', '-', $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}

function desencriptID($text){

    $text = trim($text);
    $char_list = "GHIJKLMNOPQRSTUVWXYZ";
    $char_salt = "ABCDEFabcdef";
    $text_len = strlen($text);
    $result = "";

    for($i = 0; $i < $text_len; $i++)
    {
      if (strpos($char_salt, $text[$i]) !== FALSE){
        $result = $text[$i].$result;
      } else {
        $aux = strpos($char_list, $text[$i]);
        if ($aux > 9){
          $result = ($aux-10).$result;
        } else {
          $result = $aux.$result;
        }
      }
    }
    $id = hexdec($result);
    $cantControl = strlen($result);
    if (substr($text,-1) == $cantControl) return $id/217;
    if (substr($text,-2) == $cantControl) return $id/217;
    return 'null';
}

function encriptID($data){
    $text = strtoupper(dechex($data*217));
    $char_list = "GHIJKLMNOPQRSTUVWXYZ";
    $char_salt = "ABCDEFabcdef";
    $text_len = strlen($text);
    $result = "";

    for($i = 0; $i < $text_len; $i++)
    {
      if (strpos($char_salt, $text[$i]) !== FALSE){
        $result = $text[$i].$result;
      } else {
        if (($i%2) == 0){
          $result = $char_list[$text[$i]+10].$result;
        } else {
          $result = $char_list[$text[$i]].$result;
        }
      }
    }
    
    $length = strlen($result);
    $newVal = '';
    for ($i=0; $i<$length; $i++) {
      $newVal .= (rand(0, 117)). $result[$i];
    }
    return ($newVal).$length;
}

function getKeyControl($id){
  $aux = md5($id);
  return strtoupper(preg_replace('/[0-9]/','', $aux)).intval(preg_replace('/[a-z]/','', $aux));
}
function payMethod($i = null){
    $lst = [
        'cash'=>'Metálico',
        'card'=>'Tarjeta',
        'banco'=>'Banco',
        'bono'=>'Bono',
        ];
    
    if ($i){
        return isset($lst[$i]) ? $lst[$i] : 'Otro';
    }
    return $lst;
}
function convertBold($detail){
  $detail = trim(nl2br($detail));
  if (!$detail || trim($detail) == '') return '';
  $start = false;
  $aDetail = explode('*', $detail);
  $result = '';
  if ($detail[0] == '*'){
    $result = '<b>';
    $start =  true;
  }
  if (count($aDetail)>0){
    foreach ($aDetail as $v){
      if ($v == "") continue;
      $result .= $v;
      if ($start)  $result .= '</b>';
      else  $result .= '<b>';
      
      $start = !$start;
    }
  }
  
  if ($start)  $result .= '</b>';
  return $result;
    
}
function removeIVA($price,$iva){
  if (!$price || !$iva) return 0;
  return round($price / (1 + $iva/100),2);
}

function arrayDays($start,$end,$format,$val=0,$includeLast = true){
  $allDay = [];
  $inicio = new DateTime($start);
  $intervalo = new DateInterval('P1D');
  $fin = new DateTime($end);
  $periodo = new DatePeriod($inicio, $intervalo, $fin);
  
  foreach ($periodo as $fecha) {
    if ($val == 'w')
      $allDay[$fecha->format($format)] = $fecha->format('w');
    else 
      $allDay[$fecha->format($format)] = $val;
  }
  
  if ($includeLast){
    if ($val == 'w')
      $allDay[$fin->format($format)] = $fin->format('w');
    else 
      $allDay[$fin->format($format)] = $val;
  }
  
  return $allDay;
}

function priceNoFidelity($val){
  return $val*1.1;
}