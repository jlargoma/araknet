<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CustomersRates;
use App\Models\Rates;
use App\Services\MailsService;
use Log;
use App\Services\LogsService;
include_once app_path().'/Functions.php';
/**
 * /checkcrom/Remember/appointment
 */
class RememberPaymentAgain extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'Remember:paymentAgain';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Remember Monthly Payment by email - seccond notif';

  private $sLog;
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
      $this->sLog = new LogsService('schedule','Seccond Remember Payment');
      
      $year = date('Y');
      $month = date('m');
      
      $oRatesSubsc = Rates::select('rates.id', 'types_rate.type')
                    ->join('types_rate', 'rates.type', '=', 'types_rate.id')
                    ->whereIn('types_rate.type', ['gral', 'pt'])->pluck('id');
      
      $uRates = CustomersRates::where('rate_year',$year)
              ->where('rate_month',$month)
              ->whereIn('rate_id',$oRatesSubsc)
              ->whereNull('charge_id')->get();
        
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
          
          
          $resp = $MailsService->sendEmail_Payment($uR,'Recodatorio: Abono de cuota mes en curso','recordatorio_pago');
          if ($resp){
            $this->sLog->info('Enviado ',$uRdata);
          } else {
            $this->sLog->info('No Enviado ',$uRdata);
          }
          
        }
      } else {
        $this->sLog->info('No hay citas para '.date('Y-m'));
      }
    } catch (\Exception $e) {
      $this->sLog->error('Exception: '.$e->getMessage());
    }
  }
  
}
