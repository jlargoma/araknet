<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
  
  protected $table = 'expenses';
  
  static function getTypes(){
    return [
        'alquiler_nave_y_comunidad' => 'ALQUILER NAVE Y COMUNIDAD',
        'comision_intermed' => 'COMISION INTERMED',
        'decoracion' => 'DECORACION',
        'gastos_representacion' => 'GASTOS REPRESENTACION',
        'gestorÍa' => 'GESTORÍA',
        'gto_bancarios' => 'GTO BANCARIOS',
        'gto_mat_deportivo' => 'GTO MAT DEPORTIVO',
        'gto_mat._fisio' => 'GTO MAT. FISIO',
        'gto_tarjeta_visa' => 'GTO TARJETA VISA',
        'impuestos' => 'IMPUESTOS',
        'limpieza' => 'LIMPIEZA',
        'marketing_y_publicidad' => 'MARKETING Y PUBLICIDAD',
        'mensajeria' => 'MENSAJERIA',
        'otros' => 'OTROS',
        'renting_fisioterapia' => 'RENTING FISIOTERAPIA',
        'seguros' => 'SEGUROS',
        'seguros_soc' => 'SEGUROS SOCIALES',
        'servicios_prof' => 'SERVICIOS PROF INDEPENDIENTES',
        'software' => 'SOFTWARE',
        'suministros' => 'SUMINISTROS',
        'varios' => 'VARIOS',
    ];
  }        
  static function getTypesGroup(){
    return [
            'names'=> [
              'alquileres' => 'ALQUILERES',
              'impuestos' => 'IMPUESTOS',
              'renting' => 'RENTING',
              'sueldos_y_salarios' => 'SUELDOS Y SALARIOS',
              'software' => 'SOFTWARE',
              'suministros' => 'SUMINISTROS',
              'gasto_material' => 'GASTO MATERIAL',
              'marketing_y_publicidad' => 'MARKETING Y PUBLICIDAD',
              'otros' => 'RESTO DE GASTOS',
            ],
            'groups' => [
                'alquiler_nave_y_comunidad' => 'alquileres',
                'comision_intermed' => 'otros',
                'decoracion' => 'otros',
                'gastos_representacion' => 'otros',
                'gestorÍa' => 'otros',
                'gto_bancarios' => 'otros',
                'gto_mat_deportivo' => 'gasto_material',
                'gto_mat._fisio' => 'gasto_material',
                'gto_tarjeta_visa' => 'otros',
                'marketing_y_publicidad' => 'marketing_y_publicidad',
                'mensajeria' => 'otros',
                'otros' => 'otros',
                'renting_fisioterapia' => 'renting',
                'seguros' => 'otros',
                'software' => 'software',
                'seguros_soc' => 'software',
                'servicios_prof' => 'software',
                'suministros' => 'suministros',
                'limpieza' => 'suministros',
                'impuestos' => 'impuestos',
                'varios' => 'otros',
            ]];
        
  }
  
  //Para poner nombre al tipo de cobro//
  static function getTypeCobro($typePayment=NULL) {
    $array = [
        0 => "Tarjeta visa",//"Metalico Jorge",
        2 => "CASH",// "Metalico Jaime",
        3 => "Banco",//"Banco Jorge",
    ];

    if (!is_null($typePayment)) return $typePayment = $array[$typePayment];
    
    return $array;
  }
    
    
  static function getTypesOrderned(){
    $types =  [
        'ALQUILER NAVE Y COMUNIDAD','RENTING FISIOTERAPIA','SUMINISTROS','GTO MAT. FISIO',
        'GTO MAT DEPORTIVO','MARKETING Y PUBLICIDAD','COMISION INTERMED','GTO BANCARIOS',
        'GTO TARJETA VISA','DECORACION','SOFTWARE','GESTORÍA','SEGUROS','GASTOS REPRESENTACION',
        'MENSAJERIA','OTROS'
    ];
    sort($types);
//    $types =  [
//        'ALQUILERES','RENTING','SUELDOS Y SALARIOS','SUMINISTROS',
//        'GASTO MATERIAL','MARKETING Y PUBLICIDAD','RESTO DE GASTOS'
//        ];

    
    foreach ($types as $v){
      echo "'". strtolower(str_replace(' ','_', $v))."' => '"."',<br/>";
    }
//    foreach ($types as $v){
//      echo "'". strtolower(str_replace(' ','_', $v))."' => '".strtolower(str_replace(' ','_', $v))."',<br/>";
//    }
    die;
  }
  
  
  
  static function getByTypes(){
    $types = self::getTypes();
    $groups = self::getTypesGroup();
    
    $n = $groups['names'];
    $g = $groups['groups'];
//    sort($n);
    foreach ($n as $k=>$v){
      echo "<h1>$v:</h1>";
      foreach ($g as $k1=>$v1){
        if ($v1 == $k){
          foreach ($types as $k2=>$v2){
            if ($k1 == $k2){
              echo '&nbsp&nbsp   - '.ucfirst(strtolower($v2)).'<br>';
            }
          }
        }
      }
    }
    die;
  }
    
}
