<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserRates;
use App\Models\Dates;
use Illuminate\Support\Facades\Mail;
use Log;
use App\Services\LogsService;
include_once app_path().'/Functions.php';
/**
 * /checkcrom/Remember/appointment
 */
class RememberAppointment extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'Remember:appointment';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Remember Appointment by email';

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
      $this->sLog = new LogsService('schedule','Remember Cita');
      $tomorrow = new \DateTime('tomorrow');
      $date = $tomorrow->format('Y-m-d');
       
      $day = $tomorrow->format('d').' de '.getMonthSpanish($tomorrow->format('n'),false);
      $lst = Dates::whereNull('blocked')
              ->whereDate('date',$date)->get();
      if (count($lst)>0){
        foreach ($lst as $item){
          $uRate = $item->uRates;
          if ($uRate){
            $price = ($item->date_type == 'pt') ? null : $uRate->price;
            $sent = $this->sendEmail($item, $uRate->user, $uRate->rate,$item->coach,$price,$day);
            if ($sent == 'OK')
              $this->sLog->info('Enviado ',$item->id);
            else
              $this->sLog->error('Enviar: '.$sent,$item->id);
            
          }
        }
      } else {
        $this->sLog->info('No hay citas para '.$date);
      }
    } catch (\Exception $e) {
      $this->sLog->error('Exception: '.$e->getMessage());
    }
  }
  
  function sendEmail($oDate, $oUser, $oRate,$oCoach,$importe,$day){
    
            $email= $oUser->email;
            $hour = $oDate->getHour();
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return $email.' no es un mail válido';
            try{
              $subj = 'Recordatorio de su Cita de Evolutio';
              $sended = Mail::send('emails._remember_citaStripe', [
                      'user'    => $oUser,
                      'obj'     => $oDate,
                      'rate'    => $oRate,
                      'importe' => $importe,
                      'oCoach'  => $oCoach,
                      'hour'    => $hour,
                      'day'     => $day,
              ], function ($message) use ($email,$subj) {
                      $message->subject($subj);
                      $message->from(config('mail.from.address'), config('mail.from.name'));
                      $message->to($email);
                      $message->attach(public_path('/img/protocolo.jpeg'), array(
                            'as' => 'Protocolo Covid', 
                            'mime' => 'image/jpeg'));
              });
            } catch (\Exception $ex) {
              return ($ex->getMessage());
            }
            return 'OK';
	}
}
