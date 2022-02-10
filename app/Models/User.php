<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Subscription;

class User extends Authenticatable {

  use HasFactory,
      Notifiable;
  use Billable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'name',
      'email',
      'password',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
      'password',
      'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
      'email_verified_at' => 'datetime',
  ];

  /**
   * Roles List
   * @var type
   */
  var $roles = [
      'admin' => 'SuperAdmin',
      'subadmin' => 'Admin',
      'partners' => 'Socio / Dueño',
      'commercial' => 'Comercial',
      'installer' => 'Instalador',
      'invertor' => 'Inversor'
  ];

  public function rates() {
    return $this->hasMany('\App\Models\CustomersRates', 'customer_id', 'id');
  }

  public function charges() {
    return $this->hasMany('\App\Models\Charges', 'customer_id', 'id');
  }

  public function rateCoach() {
    return $this->hasOne('\App\Models\CoachRates', 'customer_id', 'id');
  }

  public function userCoach() {
    return $this->hasOne('\App\Models\CoachUsers', 'customer_id', 'id');
  }

  public function suscriptions() {
    return $this->hasMany('\App\Models\UsersSuscriptions', 'customer_id', 'id');
  }

  static function whereBy_role($type = null, $includeAdmin = false) {

    if (is_null($type)){
      $roles = ['partners', 'commercial', 'installer', 'invertor'];
    } else {
      $roles = (is_array($type)) ? $type : [$type];
    }

    if ($includeAdmin){
      $roles[] = 'admin';
      $roles[] = 'subadmin';
    }
    
    foreach($roles as $k=>$v){
      if ($v == 'comercial') $roles[$k]='commercial';
      if ($v == 'instal') $roles[$k]='installer';
    }

    return self::whereIn('role', $roles);
  }

  static function getCoachs($type = null, $includeAdmin = false) {
    return User::whereBy_role($type, $includeAdmin)
                    ->where('status', 1)->orderBy('status', 'DESC')->get();
  }
  
  static function getUsersWithRoles($type = null) {
    $lst =  User::whereBy_role($type, false)
                    ->where('status', 1)->orderBy('name')->get();
    $allUsers = [];
    if ($lst){
      foreach ($lst as $i){
        $allUsers[$i->id] = (object)[
          'n'=>$i->name,
          'r'=>$i->role,
          'rn'=>isset($i->roles[$i->role]) ? $i->roles[$i->role] : ''
        ];
      }
    }
    return $allUsers;
  }

  /*   * ******************************************************************* */

  /////////  user_meta //////////////
  public function setMetaContent($key, $content) {

    $already = DB::table('user_meta')
                    ->where('user_id', $this->id)->where('meta_key', $key)->first();
    if ($already) {
      DB::table('user_meta')->where('user_id', $this->id)
              ->where('meta_key', $key)
              ->update(['meta_value' => $content]);
    } else {
      DB::table('user_meta')->insert(
              ['user_id' => $this->id, 'meta_key' => $key, 'meta_value' => $content]
      );
    }
    return null;
  }

  public function getMetaContent($key) {

    $oMeta = DB::table('user_meta')
                    ->where('user_id', $this->id)->where('meta_key', $key)->first();

    if ($oMeta) {
      return $oMeta->meta_value;
    }
    return null;
  }

  public function setMetaContentGroups($metaDataUPD, $metaDataADD) {
    if (count($metaDataUPD)) {
      $d = [];
      foreach ($metaDataUPD as $k => $v) {
        $updated = DB::table('user_meta')->where('user_id', $this->id)
                ->where('meta_key', $k)
                ->update(['meta_value' => $v]);
        if (!$updated) {
          $metaDataADD[$k] = $v;
        }
      }
    }

    if (count($metaDataADD)) {
      $d = [];
      foreach ($metaDataADD as $k => $v)
        $d[] = ['user_id' => $this->id, 'meta_key' => $k, 'meta_value' => $v];
      DB::table('user_meta')->insert($d);
    }
  }

  public function getMetaContentGroups($keys) {

    return DB::table('user_meta')
                    ->where('user_id', $this->id)->whereIn('meta_key', $keys)
                    ->pluck('meta_value', 'meta_key')->toArray();
  }

  public function getMetaUserID_byKey($keys, $val = null) {

    $sql = DB::table('user_meta')
            ->where('meta_key', $keys);
    if ($val)
      $sql->where('meta_value', $val);

    return $sql->pluck('user_id')->toArray();
  }

  function getPayCard() {

    $paymentMethod = null;
    try {
      return $this->paymentMethods()->first();
    } catch (\Exception $ex) {
      return null;
    }
  }

  public function getPlan() {
    return $this->getMetaContent('plan');
  }

}
