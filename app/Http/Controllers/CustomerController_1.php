<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use Mail;
use App\Models\User;
use App\Models\UserRates;

class CustomerController extends Controller {

  public function index() {
    die();
  }

  public function pagoSimple($type, $token = null, $control = null) {
    $amount = 15000;
    $name = '';
    $items = null;
    $sStripe = new \App\Services\StripeService();
    $data = $sStripe->getPaymentLinkData($type, $token, $control);
    if (!$data) die('error');
      
    $disc = null;
    if (count($data) == 2) {

      $typeKey = $data[0];
      $data = $data[1];
      switch ($typeKey) {
        case 'rate': //$year,$month,$id_user,$importe*100,$rate
          $oRate = \App\Models\Rates::find($data[4]);
          /** @Todo Controlar si ya está pagado */
          $name = 'Pago de la cuota de '
                  . $oRate->name
                  . ' del mes de ' . getMonthSpanish($data[1], false);
          $name .= ' del ' . $data[0];
          $amount = round($data[3]);
          $oUser = User::find($data[2]);
          $disc = $data[5];
          break;
        case 'nutri': //$dID,$oUser->id,$importe*100,$oRate->id;
        case 'fisio': //$dID,$oUser->id,$importe*100,$oRate->id;
          $oDate = \App\Models\Dates::find($data[0]);
          $uRates = $oDate->uRates;
          if (!$uRates){
            $oDate->delete();
            die('Usuario eliminado');
          }
          $oUser = $uRates->user;
          if (!$oUser){
            $uRates->delete();
            $oDate->delete();
            die('Usuario eliminado');
          }
          $dateTime = strtotime($oDate->date);
          $day = date('d', $dateTime) . ' de ' . getMonthSpanish(date('n', $dateTime),false);
          $hour = $oDate->getHour();
          $oRate = $uRates->rate;
          $oCoach = $oDate->coach;
          /** @Todo Controlar si ya está pagado */
          $name = 'Pago de su cita de ';
          $items = [];
          if ($oDate->date_type == 'nutri') {
            $name .= ' Nutrición ';
            $items[] = '<b>Nutricionista:</b> ' . $oCoach->name;
          }
          if ($oDate->date_type == 'fisio') {
            $name .= ' Fisioterapia ';
            $items[] = '<b>Fisioterapeuta:</b> ' . $oCoach->name;
          }
          $items[] = '<b>Servicio:</b> ' . $oRate->name;
          $items[] = '<b>Fecha:</b> ' . $day;
          $items[] = '<b>Hora:</b> ' . $hour;

          $amount = round($data[2]);
          break;
      }
    }
    return view('customers.stripe_payment', [
        'keyStripe' => config('cashier.key'),
        'amount' => $amount,
        'name' => $name,
        'type' => $type,
        'token' => $token,
        'control' => $control,
        'items' => $items,
        'disc'=>$disc,
        'email' => $oUser->email,
    ]);
  }

  /*
   * 
   */
  public function pagar(Request $request) {

    $type = $request->input('data_1');
    $token = $request->input('data_2');
    $control = $request->input('data_3');
    $sStripe = new \App\Services\StripeService();
    $data = $sStripe->getPaymentLinkData($type, $token, $control);
    $disc = 0;
    if (!$data)
      return back()->withErrors(['Error al efectuar el pago (1)']);

    if (count($data) != 2)
      return back()->withErrors(['Error al efectuar el pago (2)']);

    $type = $data[0];
    $data = $data[1];
    //-------------------------------------------------------------------//
    switch ($type) {
      case 'rate': //$year,$month,$id_user,$importe*100,$rate,$disc,$id_coach
        $oRate = \App\Models\Rates::find($data[4]);
        if (!$oRate)  return back()->withErrors(['Item no encontrado']);
        $amount = round($data[3]);
        $time = strtotime($data[0] . '-' . $data[1] . '-01');
        $uID  = $data[2];
        $oUser = User::find($uID);
        $disc  = isset($data[5]) ? $data[5] : 0;
        $id_coach = isset($data[6]) ? $data[6] : null;
        break;
      case 'nutri': //$dID,$oUser->id,$importe*100,$oRate->id;
      case 'fisio':
        $oDate = \App\Models\Dates::find($data[0]);
        if (!$oDate)   return back()->withErrors(['Item no encontrado']);
        $oUser = $uRates->user;
        $oRate = $oDate->uRates;
        if (!$uRates) die('Usuario eliminado');
        $amount = round($data[2]);
        $uID   = $oUser->user_id;
        break;
    }
    
    if (!$oUser) die('Usuario eliminado');
    
    $stripeResp = $sStripe->pagoSimple($amount, $request->all());
    dd($stripeResp);
    //-------------------------------------------------------------------//
    //--- TARJETA ---------------------------------------//
    $CardService = new \App\Services\CardService();
    dd($request->all());
    $resp = $CardService->processCard($oUser, $request);
    if ($resp !== 'OK')  return back()->withErrors($resp)->withInput();
    //--- COBRAR POR STRIPE ---------------------------------------//
    $sStripe = new \App\Services\StripeService();
    $resp = $sStripe->automaticCharge($oUser,round($value*100));
    if ( $resp[0] !== 'OK'){
      if ( $resp[0] == '3DS'){
        //-------------------------------------------------------------/
        switch ($type) {
          case 'rate':
            \App\Models\Stripe3DS::addNew($uID,$resp[1],$resp[2],'generatePayment',
                    [
                      'time'=>$time,'rID'=>$data[4], 
                      'value'=>$amount,'disc'=>$disc,'id_coach'=>$id_coach
                    ]
            );
            break;
          case 'nutri':
          case 'fisio':
            \App\Models\Stripe3DS::addNew($uID,$resp[1],$resp[2],'cita',['dID'=>$oDate->id]);
            break;
        }
        return route(
             'cashier.payment',
             [$resp[1],'redirect'=>'resultado']
        );
        //-------------------------------------------------------------/
      } else {
          return back()->withErrors([$resp[1]]);
          }
    }
    $idStripe = $resp[1];
    $cStripe = $resp[2];
    //----------------------------------------------------------------/
    //----------------------------------------------------------------/
    $amount = round($amount / 100, 2);
    switch ($type) {
      case 'rate': //$year,$month,$id_user,$importe*100,$rate
        $ChargesService = new \App\Services\ChargesService();
        $response = $ChargesService->generatePayment(
                $time, $uID, $oRate->id, 'card', $amount, $disc, $idStripe, $cStripe,$id_coach
                );
        break;
      case 'nutri': //$dID,$oUser->id,$importe*100,$oRate->id;
      case 'fisio':
        $oDate = \App\Models\Dates::find($data[0]);
        $ChargesService = new \App\Services\ChargesDateService();
        $response = $ChargesService->generatePayment($oDate,'card',$amount,$idStripe, $cStripe);
        break;
    }

    if ($response[0] != 'OK') return back()->withErrors([$response[1]]);
    //----------------------------------------------------------------/
    //----------------------------------------------------------------/

    return back()->with(['success','Pagado.!']);
  }

  public function showResult() {
    return view('customers.message');
  }
  
  public function signConsentSave(Request $request,$code,$control) {
    $data = \App\Services\LinksService::getLinkData($code,$control);
    if (!$data){
      abort(404);
      exit();
    }
    $oUser = User::find($data[0]);
    if (!$oUser){
      abort(404);
      exit();
    }
    $uID  = $oUser->id;
    $sign = $request->input('sign');
    $encoded_image = explode(",", $sign)[1];
    $decoded_image = base64_decode($encoded_image);
    switch ($data[1]){
      case 1001:
        $type = 'sign_fisioIndiba';
        break;
      case 2002:
        $type = 'sign_sueloPelvico';
        break;
      default:
        $type = 'sign_gral';
        break;
    }
    
    $fileName = 'signs/' .$type.'-'. $uID .'-'.time().'.png';
    $path = storage_path('/app/' . $fileName);
    
    $oUser->setMetaContent($type,$fileName);

    $storage = \Illuminate\Support\Facades\Storage::disk('local');
    $storage->put($fileName, $decoded_image);
    
    return redirect('/resultado')->with(['success' => 'Firma Guardada']);
  }

  function signConsent($code,$control) {
    
    $data = \App\Services\LinksService::getLinkData($code,$control);
    $dView = [
      'name'=>'',  
      'file'=>'',  
      'tit' =>'',  
      'tmsg'=>'',  
      'msg' =>'',  
      'url' =>"/firmar-consentimiento/$code/$control",  
    ];
    if (!$data){
      abort(404);
      exit();
    }
    
    $oUser = User::find($data[0]);
    if (!$oUser){
      abort(404);
      exit();
    }
    switch ($data[1]){
      case 1001:
        $tit = 'CONSENTIMIENTO FISIOTERAPIA CON INDIBA';
        $doc = 'CONSENTIMIENTO-FISIOTERAPIA-CON-INDIBA';
        break;
      case 2002:
        $tit = 'CONSENTIMIENTO SUELO PELVICO';
        $doc = 'CONSENTIMIENTO-SUELO-PELVICO';
        break;
      default:
        $tit = '';
        $doc = '';
        break;
    }
    

    return view('customers.consentimiento', [
      'name'=>$oUser->name,  
      'file'=>$doc,  
      'tit' =>$tit,  
      'url' =>"/firmar-consentimiento/$code/$control", 
    ]);


    $path = storage_path('/app/signs/' . $uid . '.png');
    if (!File::exists($path)) {
      abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = \Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
  }


}
