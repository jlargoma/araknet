<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customers;
use Log;
use App\Services\LogsService;
use App\Services\HeliumService;
use App\Models\CustomersHnts;
use App\Models\HntsDays;

class DailyHNT extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'DailyHNT:getVals';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Get daily HNT by Hotspots';
  private $sLog;
  private $sHelium;
  private $cLst;
  private $aErrors;
  private $aSuccess;

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    parent::__construct();
    $this->sHelium = new HeliumService();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    try {
      $this->sLog = new LogsService('schedule', 'DailyHNT');
      $this->cLst = Customers::whereNotNull('hotspot_imac')
                      ->where('status', 1)->get();

      $this->aErrors = [];
      $this->aSuccess = [];
      $today = date('Y-m-d');
      if ($this->cLst) {
//        $this->getByRang(date('Y-m-d',strtotime('-7 days')),date('Y-m-d'));
        $this->getByDay($today);
      }

      $this->saveHNT_Account($today);
    

      if (count($this->aSuccess) > 0)
        $this->sLog->info('Success ', $this->aSuccess);
      if (count($this->aErrors) > 0)
        $this->sLog->error('Errores ', $this->aErrors);
    } catch (\Exception $e) {
      $this->sLog->error('Exception: ' . $e->getMessage());
    }
  }

  function getByDay($day) {
    $startDate = $day; //'2022-02-17';
    $endDate = date('Y-m-d', strtotime($startDate . ' +1 day'));
    $this->saveHNT($startDate, $endDate);
  }

  function getByRang($d1, $d2) {
    $startDate = null;
    $endDate = null;
    $arrayDays = arrayDays($d1, $d2, 'Y-m-d');
    foreach ($arrayDays as $d => $v) {
      if (!$startDate) {
        $startDate = $d;
        continue;
      }
      $endDate = $d;
      $this->saveHNT($startDate, $endDate);
      $startDate = $d;
    }
  }

  function saveHNT($startDate, $endDate) {
    $errors = $success = [];
    foreach ($this->cLst as $c) {
      $cID = $c->id;
      $hotspots = $c->hotspot_imac;

      $resp = $this->sHelium->getHNT_hotspots($hotspots, $startDate, $endDate);
      if ($resp) {
        //echo $c->name.' '.$c->dni."\n";
        if ($c->hotspot_date > $startDate)
          continue;

        if (isset($this->sHelium->response->data)) {
          $value = $this->sHelium->response->data->total;

          $cHNT = CustomersHnts::where('customer_id', $cID)
                          ->where('date', $startDate)->first();
          if (!$cHNT) {
            $cHNT = new CustomersHnts();
            $cHNT->customer_id = $cID;
            $cHNT->date = $startDate;
          }
          $cHNT->hnt = $value;
          $cHNT->save();

          $this->aSuccess[] = $c->name . ': ' . $value;
        }
      } else {

        $this->aErrors[] = $c->name . ': ' . $this->sHelium->response;
      }
    }
  }

  function saveHNT_Account($startDate, $endDate=null) {
    $errors = $success = [];
    
    if (!$endDate)   $endDate = date('Y-m-d', strtotime($startDate . ' +1 day'));
    
    $resp = $this->sHelium->getHNT_accounts($startDate, $endDate);
    if ($resp) {
      if (isset($this->sHelium->response->data)) {
        $value = $this->sHelium->response->data->total;

        $cHNT = HntsDays::where('date', $startDate)->first();
        if (!$cHNT) {
          $cHNT = new HntsDays();
          $cHNT->date = $startDate;
        }
        $cHNT->hnt = $value;
        $cHNT->save();

        $this->aSuccess[] = 'Account: ' . $value;
      }
    } else {

      $this->aErrors[] = 'Account: ' . $this->sHelium->response;
    }
  }

}
