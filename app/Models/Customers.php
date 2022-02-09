<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Subscription;

class Customers extends Authenticatable {

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

 

  public function rates() {
    return $this->hasMany('\App\Models\CustomersRates', 'customer_id', 'id');
  }

  public function charges() {
    return $this->hasMany('\App\Models\Charges', 'customer_id', 'id');
  }

 

  public function suscriptions() {
    return $this->hasMany('\App\Models\CustomersSuscriptions', 'customer_id', 'id');
  }

  
  /*   * ******************************************************************* */

  /////////  customers_meta //////////////
  public function setMetaContent($key, $content) {

    $already = DB::table('customers_meta')
                    ->where('customer_id', $this->id)->where('meta_key', $key)->first();
    if ($already) {
      DB::table('customers_meta')->where('customer_id', $this->id)
              ->where('meta_key', $key)
              ->update(['meta_value' => $content]);
    } else {
      DB::table('customers_meta')->insert(
              ['customer_id' => $this->id, 'meta_key' => $key, 'meta_value' => $content]
      );
    }
    return null;
  }

  public function getMetaContent($key) {

    $oMeta = DB::table('customers_meta')
                    ->where('customer_id', $this->id)->where('meta_key', $key)->first();

    if ($oMeta) {
      return $oMeta->meta_value;
    }
    return null;
  }

  public function setMetaContentGroups($metaDataUPD, $metaDataADD) {
    if (count($metaDataUPD)) {
      $d = [];
      foreach ($metaDataUPD as $k => $v) {
        $updated = DB::table('customers_meta')->where('customer_id', $this->id)
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
        $d[] = ['customer_id' => $this->id, 'meta_key' => $k, 'meta_value' => $v];
      DB::table('customers_meta')->insert($d);
    }
  }

  public function getMetaContentGroups($keys) {

    return DB::table('customers_meta')
                    ->where('customer_id', $this->id)->whereIn('meta_key', $keys)
                    ->pluck('meta_value', 'meta_key')->toArray();
  }

  public function getMetaUserID_byKey($keys, $val = null) {

    $sql = DB::table('customers_meta')
            ->where('meta_key', $keys);
    if ($val)
      $sql->where('meta_value', $val);

    return $sql->pluck('customer_id')->toArray();
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
