<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model {

  protected $fillable = [
      'key',
      'value'
  ];

  static function getKeyValue($key) {
    $obj = Settings::select('value')->where('key', $key)->first();
    if ($obj) {
      return $obj->value;
    } else {
      return null;
    }
  }

  static function getContent($key) {

    $Object = null;
    if (!$Object) {
      $Object = Settings::select('content')->where('key', $key)->first();
    }

    if ($Object) {
      return $Object->content;
    }

    return '';
  }

  static function getKeysTxtMails($lng = 'es') {
    $lst = [
        'pago_cuota_mes' => 'Abono de cuota mes en curso',
        'recordatorio_pago' => 'Recordatorio de pago el dia 5 a las 00:01',
        'contracts_mails' => 'Firmar contratos',
        'contracts_cesion-antena' => 'CONTRATO DE CESION ANTENA',
    ];
    return $lst;
  }

  // Put this in any model and use
  // Modelname::findOrCreate($id);
  public static function findOrCreate($key) {
    $obj = static::where('key', $key)->first();
    if ($obj)
      return $obj;

    $obj = new static;
    $obj->key = $key;
    $obj->save();
    return $obj;
  }

  static function getKeysWSP() {
      return [
           'send_encuesta_subject',
      ];
    }
}
