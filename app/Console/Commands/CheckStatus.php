<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customers;
use Log;
use App\Services\LogsService;
use App\Services\HeliumService;
use App\Models\HotspotStatus;
use Illuminate\Support\Facades\Mail;

class CheckStatus extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'Hotspots:checkStatus';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Get daily status of Hotspots';
  private $sLog;
  private $sHelium;
  private $aAlerts;
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
    $this->HStatus = new HotspotStatus();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    try {
      $this->sLog = new LogsService('schedule', 'DailyStatus');

      $this->aErrors = [];
      $this->aSuccess = [];
      $this->aAlerts = [];

      $resp = $this->sHelium->get_hotspots();
      if ($resp) {
        if (isset($this->sHelium->response->data)) {
          $lst = $this->sHelium->response->data;
          $this->getData($lst);
        }
      }

      if (count($this->aSuccess) > 0)
        $this->sLog->info('Success ', $this->aSuccess);
      if (count($this->aAlerts) > 0)
        $this->sendAlerts();
      if (count($this->aErrors) > 0)
        $this->sLog->error('Errores ', $this->aErrors);
    } catch (\Exception $e) {
      $this->sLog->error('Exception: ' . $e->getMessage());
    }
  }

  function getData($lst) {
    $errors = $success = [];
    foreach ($lst as $hp) {
      $status = $hp->status->online;
      $geocode = $hp->geocode->short_street;
      if ($hp->geocode->short_city) $geocode .= "( {$hp->geocode->short_city} )";
      
      //busco el registro por address
      $obj = $this->HStatus->getBy_imac($hp->address);

      //Busco si tiene un cliente asociado
      $idCust = -1;
      $oCust = Customers::where('hotspot_imac', $hp->address)->first();
      if ($oCust)
        $idCust = $oCust->id;

      //si estaba activo y ahora no, genero la alerta
//      if (true) {
      if ($obj->status == 'online' && $obj->status != $status) {
        $this->aAlerts[] = [
            'status' => $status,
            'geocode' => $geocode,
            'address' => $hp->address,
            'name' => $hp->name,
            'cname' => ($oCust) ? $oCust->name : null,
        ];
      }

      //guardo/actualizo el registro
      $obj->customer_id = $idCust;
      $obj->name = $hp->name;
      $obj->street = $geocode;
      $obj->status = $status;
      $obj->save();
      $this->aSuccess[] = 'HpS_' . $obj->id . ': ' . $status;
    }
  }

  function sendAlerts() {
    if (count($this->aAlerts) == 0)
      return '';


    $subject = 'Atención: Hotspot(s) offline';

    $text = '<h3>Los siguientes Hotspot deben controlarse:</h3>';

    foreach ($this->aAlerts as $v) {
      $aux = [];
      if ($v['cname'])
        $aux[] = 'Cliente: ' . $v['cname'];
      
      $aux[] = 'Nombre Hotspot: ' . $v['name'];
      
      if (trim($v['geocode']) != '')
        $aux[] = 'Calle: ' . $v['geocode'];
      
      $aux[] = 'Hotspot: ' . $v['address'];
      $aux[] = 'Status: <b>' . $v['status'].'</b>';
      $text .= '<p><b>-----------</b></p>';
      $text .= implode('<br/>', $aux);
    }



    Mail::send('emails.base', [
        'mailContent' => $text,
        'tit' => $subject
            ], function ($message) use ($subject) {
              $message->from(config('mail.from.address'));
              $message->to('jlargo@mksport.es');
              $message->cc('pingodevweb@gmail.com');
              $message->subject($subject);
            });
  }

}
