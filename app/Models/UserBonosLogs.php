<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //línea necesaria

class UserBonosLogs extends Model {

  protected $table = 'users_bonos_logs';

  static function getTotal($uBonoID) {
    $last = UserBonosLogs::where('user_bonos_id', $uBonoID)
                    ->orderBy('id', 'desc')->first();
    if ($last) {
      return $last->total;
    }
    return 0;
  }
  static function getLst($uBonoID) {
    return UserBonosLogs::where('user_bonos_id', $uBonoID)->get();
  }

}
