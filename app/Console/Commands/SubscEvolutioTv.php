<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserRates;
use App\Models\Rates;
use App\Services\MailsService;
use Log;
use App\Services\LogsService;
include_once app_path().'/Functions.php';

class SubscAraknetTv extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'SubscAraknetTv:send';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Send suscription to AraknetTv';

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
  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    try {
      $this->sLog = new LogsService('schedule','Create AraknetTv');
      
      $token = md5((date('Y')*date('j')/date('d'))*17954);
      $year = date('Y');
      $month = date('m');
      $suscripEnd = date('Y-m-', strtotime('+1 months')).'01';
      
      $oRatesSubsc = Rates::select('rates.id', 'types_rate.type')
                    ->join('types_rate', 'rates.type', '=', 'types_rate.id')
                    ->whereIn('types_rate.type', ['gral', 'pt'])->pluck('id');
      
      $uRates = UserRates::where('rate_year',$year)
              ->where('rate_month',$month)
              ->whereIn('id_rate',$oRatesSubsc)
              ->whereNotNull('id_charges')->get();
      if ($uRates){
        $MailsService = new MailsService();
        foreach ($uRates as $uR){
          if (!$uR->rate)
            $this->sLog->error('Rate no exist',$uR->id);
          
          
          $uRdata = [$uR->id,$uR->rate->name];
          
          
          if (!$uR->user)
            $this->sLog->error('user no exist',$uRdata);
          
          
          $uRdata[] = $uR->user->name;
          $uRdata[] = $uR->user->email;
        }
      } else {
        $this->sLog->info('No hay items para '.date('Y-m'));
      }
    } catch (\Exception $e) {
      $this->sLog->error('Exception: '.$e->getMessage());
    }
  }
  
}
