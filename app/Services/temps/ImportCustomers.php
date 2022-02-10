<?php

namespace App\Services\temps;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Charges;
/*
 * UPDATE `0_temp_users2`  SET user_id = null;
UPDATE `0_temp_users2`  SET user_id = (SELECT users.id FROM users WHERE users.name = `0_temp_users2`.`name`)
 * 
 * /admin/import/updChargesDate
 */
class ImportCustomers {

  public function __construct() {
    
  }
  
  public function import($tipe) {
    switch ($tipe){
//      case 'clientes':
//        $this->importCustomer();
//        break;
//      case 'rates':
//        $this->loadRates();
//      case 'updRates':
//        $this->updRates();
//        break;
//      case 'updChargesDate':
//        $this->updChargesDate();
//        break;
//      case 'urates':
//        $this->updCustomersRates();
//        break;
    }
  }
  public function importCustomer() {
    
    
//    $cutomers = DB::table('0_temp_users2')->get();
//    foreach ($cutomers as $c){
//      
//      
//      $email = str_replace(' ','_', $c->name).'@Araknet.tech';
//      $email = str_replace('__','_',$email);
//      
//      DB::table('0_temp_users2')->where('id',$c->id)->update(['email'=>$email]);
//    }

    $cutomers = DB::table('0_temp_users2')->whereNull('user_id')->get();
    $lst = [];
    foreach ($cutomers as $c){
      if(!isset($lst[$c->email])){
        $lst[$c->email] = $c;
      }
    }
    foreach ($lst as $c){
      $exist = User::where('email',$c->email)->first();
      if ($exist){
        DB::table('0_temp_users2')->where('email',$c->email)->update(['user_id'=>$exist->id]);
      } else {
        $obj = new User();
        $obj->name = $c->name;
        $obj->email = $c->email;
        $obj->password = str_random(60);
        $obj->remember_token = str_random(60);
        $obj->role = 'user';
        $obj->phone = '';
        $obj->save();
        DB::table('0_temp_users2')->where('email',$c->email)->update(['user_id'=>$obj->id]);
      }
      
    }
    
  }
  public function loadRates() {
    $cutomers = DB::table('0_temp_users2')->get();
    $tRates = \App\Models\Rates::pluck('type','id')->toArray();
    foreach ($cutomers as $c){
      $oCobro = new \App\Models\Charges();
      $oCobro->customer_id = $c->user_id;
      $oCobro->date_payment = $c->date;
      $oCobro->rate_id = $c->rate_id;
      $oCobro->type_payment = strtolower($c->tpay);
      $oCobro->type = 1;
      $oCobro->import = $c->price;
      $oCobro->discount = 0;
      $oCobro->type_rate = $tRates[$c->rate_id];
      $oCobro->save();

      /**************************************************** */

      $newRate = new \App\Models\CustomersRates();
      $newRate->customer_id = $c->user_id;
      $newRate->rate_id = $c->rate_id;
      $newRate->rate_year = date('Y', strtotime($c->date));
      $newRate->rate_month = date('n', strtotime($c->date));
      $newRate->charge_id = $oCobro->id;
      $newRate->coach = $c->coach;
      $newRate->save();
      
    }
  }
    
    function updRates(){
      $cutomers = DB::table('0_temp_users2')->get();
      $tRates = \App\Models\Rates::pluck('type','id')->toArray();
      foreach ($cutomers as $c){
        $month = date('n', strtotime($c->date));
        if ($month != 4)          continue;
        $year = date('Y', strtotime($c->date));
        
        $oRate = \App\Models\CustomersRates::where('customer_id',$c->user_id)
                ->where('rate_id',$c->rate_id)
                ->where('rate_month',$month)
                ->where('rate_year',$year)->first();
        if ($oRate){
          $oRate->price = $c->price;
          $oRate->save();
        }
        
      }
    }
  
    function updChargesDate(){
      $allDates = \App\Models\Dates::all();
      foreach ($allDates as $i){
        if (!$i->customers_rate_id){
          $uRate = \App\Models\CustomersRates::where('id_appointment',$i->id)->first();
          if ($uRate){
            $uRate->charge_id = $i->charge_id;
            $uRate->save();
            
          } else {
            $timeCita = strtotime($i->date);
            $uRate = new \App\Models\CustomersRates();
            $uRate->customer_id = $i->customer_id;
            $uRate->rate_id = $i->rate_id;
            $uRate->rate_year = date('Y', $timeCita);
            $uRate->rate_month = date('n', $timeCita);
            $uRate->price = $i->price;
            $uRate->charge_id = $i->charge_id;
            $uRate->save();
          }
          
          $i->customers_rate_id = $uRate->id;
          $i->save();
        }
      }
      
      return ;
      $charges = Charges::where('date_payment','>=','2021-05-05')
              ->where('date_payment','<=','2021-05-07')->get();
      
      $cIDS = [];
      $aCh = [];
      foreach ($charges as $c){
        $cIDS[] = $c->id;
        $aCh[$c->id] = $c;
        
      }
     
      $uRates = \App\Models\CustomersRates::whereIn('charge_id',$cIDS)
              ->where('rate_month','<',5)->get();
      foreach ($uRates as $ur){
        $oCharge = $aCh[$ur->charge_id];
        $day = date('d', strtotime($oCharge->date_payment));
        
        $oCharge->date_payment = $ur->rate_year.'-'.$ur->rate_month.'-'.$day;
        $oCharge->update();
      }
    }
  
    /**
     * http://evol.virtual/admin/import/urates
     */
    function updCustomersRates(){
      $suscr = \App\Models\UsersSuscriptions::whereNotNull('user_id')->get();
      foreach ($suscr as $i){
      
        $affected = DB::table('users_rates')
              ->where('customer_id',$i->customer_id)
              ->where('rate_id',$i->rate_id)
              ->update(['coach_id' => $i->user_id]);
        
      }
    }
}
