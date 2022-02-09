<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UsersSuscriptions;
use App\Models\CustomersRates;
use Log;

class SubscPayment extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'SubscPayment:charge';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Charge monthly rates';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    try {
      $lst = UsersSuscriptions::all();
      $year = date('Y');
      $month = date('m');
      foreach ($lst as $s){
        $uID = $s->customer_id;
        $rID = $s->rate_id;
        $uRate = CustomersRates::where('customer_id',$uID)
                ->where('rate_year',$year)->where('rate_month',$month)
                ->where('rate_id', $rID)->first();
        if (!$uRate){
          $uRate = new CustomersRates();
          $uRate->customer_id = $uID;
          $uRate->rate_id = $rID;
          $uRate->price   = $s->price;
          $uRate->rate_year = $year;
          $uRate->rate_month = $month;
          $uRate->save();

        }
      }
    } catch (\Exception $e) {
    Log::error("Error creando suscripciones");
    }
  }
}
