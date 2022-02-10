<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use \Carbon\Carbon;

class MailController extends Controller
{
	public static function sendEmailPaymentRate($data)
	{

		$customer        = \App\User::find($data['customer_id']);
		$date        = Carbon::createFromFormat('Y-m-d', $data['fecha_pago']);
		$rate        = \App\Rates::find($data['id_tax']);
		$typePayment = $data['type_payment'];
		$importe     = $data['importe'];
		$email       = $data['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return $email.' no es un mail válido';
        try{
          $sended = Mail::send('emails._payment_rate', [
              'user'        => $customer,
              'date'        => $date,
              'rate'        => $rate,
              'importe'     => $importe,
              'typePayment' => $typePayment
          ], function ($message) use ($email) {
              $message->subject('Comprobante de pago Araknet');
              $message->from(config('mail.from.address'), config('mail.from.name'));
              $message->to($email);
          });
        } catch (\Exception $ex) {
          return ($ex->getMessage());
        }
		 
        return 'OK';
	}
        
     
        
    public static function sendEmailPayRateByStripe($data,$oUser,$oRate,$pStripe)
	{

		$date        = Carbon::createFromFormat('Y-m-d', $data['fecha_pago']);
		$typePayment = $data['type_payment'];
		$importe     = $data['importe'];
		$email       = $oUser->email;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return $email.' no es un mail válido';
        
        $rType = \App\Models\TypesRate::find($oRate->type);
        $type = 'Nuevo Servicio';
        if ($rType->type == 'fisio' || $rType->type == 'nutri'){
          $type = 'Nueva Cita';
        }
        try{
          $sended = Mail::send('emails._payment_rateStripe', [
              'user'        => $oUser,
              'date'        => $date,
              'rate'        => $oRate,
              'type'        => $type,
              'importe'     => $importe,
              'pStripe'     => $pStripe
          ], function ($message) use ($email) {
              $message->subject('Solicitud de pago Araknet');
              $message->from(config('mail.from.address'), config('mail.from.name'));
              $message->to($email);
          });
        } catch (\Exception $ex) {
          return ($ex->getMessage());
        }
         
        return 'OK';
		return (!$sended) ? true : false;
	}
        
     public static function sendEmailPuncharseBonoByStripe($data,$oUser,$oBono,$pStripe)
	{

		$typePayment = $data['type_payment'];
		$importe     = $data['importe'];
		$email       = $oUser->email;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return $email.' no es un mail válido';
        try{
          $sended = Mail::send('emails._payment_BonoStripe', [
              'type'        => 'Compra de Bono',
              'user'        => $oUser,
              'bono_name'   => $oBono->name,
              'importe'     => $importe,
              'pStripe'     => $pStripe
          ], function ($message) use ($email) {
              $message->subject('Solicitud de compra de Bonos Araknet');
              $message->from(config('mail.from.address'), config('mail.from.name'));
              $message->to($email);
          });
        } catch (\Exception $ex) {
          return ($ex->getMessage());
        }
         
        return 'OK';
		return (!$sended) ? true : false;
	}
        
    public static function sendEmailPayDateByStripe($oDate, $oCustomer, $oRate,$oCoach,$importe,$subj=null,$calFile=null)
	{
            $email    = $oCustomer->email;
            $dateTime = strtotime($oDate->date);
            $day = date('d',$dateTime).' de '.getMonthSpanish(date('n',$dateTime),false);
            $hour = $oDate->getHour();
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return $email.' no es un mail válido';
            try{
              if (!$subj) $subj = 'Solicitud de pago Araknet';
              $sended = Mail::send('emails.cita_upd', [
                      'customer'=> $oCustomer,
                      'obj'     => $oDate,
                      'rate'    => $oRate,
                      'importe' => $importe,
                      'oCoach'  => $oCoach,
                      'hour'    => $hour,
                      'day'     => $day,
                      'urlEntr' => null,
              ], function ($message) use ($email,$subj,$calFile) {
                      $message->subject($subj);
                      $message->from(config('mail.from.address'), config('mail.from.name'));
                      $message->to($email);
                      if ($calFile){
                        $message->attach($calFile, array(
                            'as' => 'Evento Calendario '.time()));
                      }
              });
              
            } catch (\Exception $ex) {
              return ($ex->getMessage());
            }
            return 'OK';
	}
    public static function sendEmailCita($oDate, $oCustomer, $oRate,$oCoach,$importe,$subj=null,$calFile=null)
	{
            $email    = $oCustomer->email;
            $dateTime = strtotime($oDate->date);
            $day = date('d',$dateTime).' de '.getMonthSpanish(date('n',$dateTime),false);
            $hour = $oDate->getHour();
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return $email.' no es un mail válido';
            try{
              
              if (!$subj)  $subj = 'Recordatorio de su Cita de Araknet';
              
              $sended = Mail::send('emails._remember_citaStripe', [
                      'customer'    => $oCustomer,
                      'obj'     => $oDate,
                      'rate'    => $oRate,
                      'importe' => $importe,
                      'oCoach'  => $oCoach,
                      'hour'    => $hour,
                      'day'     => $day,
              ], function ($message) use ($email,$subj,$calFile) {
                      $message->subject($subj);
                      $message->from(config('mail.from.address'), config('mail.from.name'));
                      $message->to($email);
                      if ($calFile){
                        $message->attach($calFile, array(
                            'as' => 'Evento Calendario '.time()));
                      }
              });
            } catch (\Exception $ex) {
              return ($ex->getMessage());
            }
            return 'OK';
              
	}

}
