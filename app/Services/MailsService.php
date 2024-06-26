<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\Settings;

class MailsService {

  public static function sendEmailPayBono($oUser, $oBono, $tPay) {
    $email = $oUser->email;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
      return $email . ' no es un mail válido';
    try {
      $sended = Mail::send('emails._payment_bono', [
                  'user' => $oUser,
                  'bono' => $oBono,
                  'typePayment' => $tPay
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

  public static function sendEmailPayRate($data, $oCustomer, $oRate) {


    $date = self::convertDateToShow_text($data['fecha_pago']);
    $typePayment = $data['type_payment'];
    $importe = $data['importe'];
    $email = $oCustomer->email;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
      return $email . ' no es un mail válido';
    try {
      $sended = Mail::send('emails._payment_rate', [
                  'customer' => $oCustomer,
                  'date' => $date,
                  'rate' => $oRate,
                  'importe' => $importe,
                  'tit' => 'Nuevo cobro',
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

  public static function convertDateToShow_text($date, $year = false) {
    $date = trim($date);
    if ($date) {
      $MonthSpanish = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

      $aux = explode('-', $date);
      if (is_array($aux) && count($aux) == 3) {
        $aux2 = explode(' ', $aux[2]);
        return $aux2[0] . ' ' . $MonthSpanish[intval($aux[1])] . ', ' . $aux[0];
      }
    }
    return $date;
  }

  /*   * ******************************************************************** */

  /**
   * Clear all not loaded vars
   * @param type $text
   * @return type
   */
  public function clearVars($text) {

    return preg_replace('/\{(\w+)\}/i', '', $text);
  }

  /**
   *
   * @param uRate $data
   * @param String  $key
   * @return String HTML
   */
  public function getMailData($data, $keyTemp) {

    $mailClientContent = Settings::getContent($keyTemp);

    $dataContent = array(
        'customer_name' => $data->user->name,
        'customer_email' => $data->user->email,
        'customer_phone' => $data->user->phone,
        'service_name' => $data->rate->name,
    );

    /** process the mail content */
    foreach ($dataContent as $k => $v) {
      $mailClientContent = str_replace('{' . $k . '}', $v, $mailClientContent);
    }
    return $mailClientContent;
  }

  /**
   *
   * @param type $book
   * @param type $subject
   */
  public function sendEmail_Payment($uRate, $subject, $template) {
    if (!$uRate->user->email || trim($uRate->user->email) == '')
      return;

    $mailClientContent = $this->getMailData($uRate, $template);
    setlocale(LC_TIME, "ES");
    setlocale(LC_TIME, "es_ES");

    $data = [$uRate->rate_year, $uRate->rate_month, $uRate->customer_id, $uRate->price * 100, $uRate->rate_id, 0];
    $sStripe = new \App\Services\StripeService();
    $pStripe = url($sStripe->getPaymentLink('rate', $data));
    $mailClientContent = str_replace('{pago_enlace}', $pStripe, $mailClientContent);
    $mailClientContent = str_replace('{pago_monto}', number_format($uRate->price, 2, ',', '.'), $mailClientContent);

    $mailClientContent = $this->clearVars($mailClientContent);

    $to = $uRate->user->email;
    $sended = Mail::send('emails.base', [
                'mailContent' => $mailClientContent,
                'tit' => $subject
                    ], function ($message) use ($to, $subject) {
                      $message->from(config('mail.from.address'), config('mail.from.name'));
                      $message->to($to);
                      $message->subject($subject);
                      $message->replyTo(config('mail.from.address'));
                    });
    return $sended;
  }

  function getText($template, $oCustomer = null, $Rate = null, $oUser = null) {
    $txtContent = Settings::getContent($template);
    $dataContent = array();
    if ($Rate) {
      $dataContent = [
          'cliente_nombre' => $data->customer->name,
          'cliente_correo' => $data->customer->email,
          'cliente_tel' => $data->customer->phone,
      ];
    }

    if ($oCustomer) {
      $dataContent['cliente_nombre'] = $oCustomer->name;
      $dataContent['cliente_correo'] = $oCustomer->email;
      $dataContent['cliente_dni'] = $oCustomer->dni;
      $dataContent['cliente_tel'] = $oCustomer->phone;
      $dataContent['cliente_domicilio'] = $oCustomer->address . ' (' . $oCustomer->province . ')';
    }
    $dataContent['mes_actual'] = getMonthSpanish(date('m'), false);

    /** process the mail content */
    foreach ($dataContent as $k => $v) {
      $txtContent = str_replace('{' . $k . '}', $v, $txtContent);
    }

    return $this->clearVars($txtContent);
  }
  
  
  function sendMailBasic($template,$subject,$to, $dataContent) {
    $txtContent = Settings::getContent($template);
    /** process the mail content */
    foreach ($dataContent as $k => $v) {
      $txtContent = str_replace('{' . $k . '}', $v, $txtContent);
    }

    $txtContent = $this->clearVars($txtContent);
    $sended = Mail::send('emails.base', [
                'mailContent' => $txtContent,
                'tit' => $subject
                    ], function ($message) use ($to, $subject) {
                      $message->from(config('mail.from.address'), config('mail.from.name'));
                      $message->to($to);
                      $message->subject($subject);
                      $message->replyTo(config('mail.from.address'));
                    });
    return $sended;
    
  }
  
  
  

}
