<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use App\Services\LogsService;
use App\Models\User;
use App\Models\UsersRates;
use App\Models\UsersLiquidation;
use App\Services\CoachLiqService;

class Salary extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'Salary:createMonthly';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Create monthly salaries';

    
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
      $this->sLog = new LogsService('schedule','Salary');
      $year = date('Y');
      $month = date('m');
      
      if (date('d') == 1) return null;
      
      $sCoachLiqService = new CoachLiqService();
      $e_noSalary = $i_loaders = $i_already = [];
      $customers = User::whereBy_role()->select('id')
              ->where('status', 1)->pluck('id');
      
      foreach ($customers as $uID) {
        $taxCoach = UsersRates::where('customer_id', $uID)->first();
        if ($taxCoach) {
            $oLiq = UsersLiquidation::where('user_id', $uID)
              ->whereYear('date_liquidation', '=', $year)
              ->whereMonth('date_liquidation', '=', $month)
              ->first();
            if (!$oLiq){
              $oLiq = new UsersLiquidation();
              $oLiq->date_liquidation = "$year-$month-01";
              $oLiq->user_id = $uID;
              $oLiq->salary = $taxCoach->salary;
              $commision = $sCoachLiqService->liqMensualBasic($uID, $year, $month);
              $oLiq->commision = array_sum($commision['totalClase']);
              $oLiq->save();
              $i_loaders[] = $uID.'=>'.$taxCoach->salary;
            } else {
              $save = false;
              if (!$oLiq->commision){
                $commision = $sCoachLiqService->liqMensualBasic($uID, $year, $month);
                $oLiq->commision = array_sum($commision['totalClase']);
                $save = true;
              }
              if ($oLiq->salary<1){
                $oLiq->salary = $taxCoach->salary;
                $save = true;
                $i_loaders[] = $uID.'=>'.$taxCoach->salary;
              } else {
                $i_already[] = $uID.'=>'.$oLiq->salary;
              }
              if ($save)  $oLiq->save();
            }
          } else {
            $e_noSalary[] = $uID;
          }
    
      }
      
      if (count($i_loaders)>0)  $this->sLog->info('Salario cargados ',$i_loaders);
      if (count($i_already)>0)  $this->sLog->info('Salario ya cargados ',$i_already);
      if (count($e_noSalary)>0)  $this->sLog->info('Salario no cargado ',$e_noSalary);
    } catch (\Exception $e) {
      dd($e->getMessage());
      $this->sLog->error('Exception: '.$e->getMessage());
    }
  }
}
