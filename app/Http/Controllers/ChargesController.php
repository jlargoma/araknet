<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Carbon\Carbon;
use App\Models\User;
use App\Models\Rates;
use App\Models\UserRates;
use App\Models\UserBonos;
use App\Models\Charges;
use App\Services\ChargesService;

class ChargesController extends Controller {

    public function updateCobro(Request $request, $id) {
        $charge = Charges::find($id);

        if (!$charge)
            return view('admin.popup_msg');
        $uRate = UserRates::where('id_charges', $charge->id)->first();
        $coach_id = null;
        if ($uRate) {
            $date = getMonthSpanish($uRate->rate_month, false) . ' ' . $uRate->rate_year;
            $coach_id = $uRate->coach_id;
        } else {
            $time = strtotime($charge->date_payment);
            $date = getMonthSpanish(date('n', $time), false) . ' ' . date('Y', $time);
        }
        
        $oBono = null;
        if ($charge->bono_id>0){
          $oBono = \App\Models\Bonos::find($charge->bono_id);
          if (!$oBono){
            $oBono = 'not_found';
          }
        }
        
        $oUser = $charge->user;
        $uPlan = 'none';
        if ($oUser){
          $uPlan = $oUser->getPlan();
        }

        return view('admin.charges.cobro_update', [
            'taxes' => Rates::all(),
            'rate' => Rates::find($charge->id_rate),
            'date' => $date,
            'user' => $oUser,
            'importe' => $charge->import,
            'charge' => $charge,
            'coach_id' => $coach_id,
            'oBono' => $oBono,
            'coachs' => User::getCoachs(),
            'uPlan'=>$uPlan
        ]);
    }

    public function updateCharge(Request $request, $id) {
        $charge = Charges::find($id);
        $id_coach = $request->input('id_coach', null);
        if (!$charge) {
            return back()->withErrors(['cobro no encontrado']);
        }
        if ($request->input('deleted')) {
          $uRate = UserRates::where('id_charges',$id)->first();
          $sBonos = new \App\Services\BonoService();
          if ($uRate){
            $uRate->id_charges = null;
            $uRate->save();
            $charge->delete();
            //devuelvo el bono
            $sBonos->restoreBonoCharge($id);
            return redirect('/admin/clientes/generar-cobro/'.$uRate->id)->with('success', 'cobro Eliminado');
          }     
          //devuelvo el bono
          $sBonos->restoreBonoCharge($id);
          $charge->delete();
          return back()->with('success', 'cobro Eliminado');
        } else {
          $charge->import = $request->input('importe');
          if ($request->input('type_payment')) $charge->type_payment = $request->input('type_payment');
          if ($request->input('discount'))  $charge->discount = $request->input('discount');
          $charge->save();
          
          if (!$charge->bono_id || $charge->bono_id<1){
            UserRates::where('id_charges',$id)->update(
                  ['price' => $charge->import,'coach_id'=>$id_coach]
                  );
          }
          return back()->with('success', 'cobro actualizado');
        }
    }

    public function cobrar(Request $req) {

      $id_uRate = $req->input('id_uRate', null);
      $uRate = UserRates::find($id_uRate);
      
      if (!$uRate) {
        return back()->withErrors(['Tarifa no encontrada']);
      }
      
      $time = strtotime($uRate->rate_year.'/'.$uRate->rate_month.'/01');
      $uID = $uRate->id_user;
      $rID = $uRate->id_rate;
      $tpay = $req->input('type_payment','cash');
      $value = $req->input('importe', 0);
      $disc = $req->input('discount', '0');
      $id_coach = $req->input('id_coach', null);
      $idStripe=null;$cStripe=null;
      if ($tpay == 'card'){
         $oUser = User::find($uID);
        //--- NUEVA TARJETA ---------------------------------------//
        if ($req->input('cardLoaded') == 0){
          $CardService = new \App\Services\CardService();
          $resp = $CardService->processCard($oUser, $req);
          if ($resp !== 'OK')  return back()->withErrors($resp)->withInput();
        }
        //--- COBRAR POR STRIPE ---------------------------------------//
        $sStripe = new \App\Services\StripeService();
        $resp = $sStripe->automaticCharge($oUser,round($value*100));
        if ( $resp[0] !== 'OK'){
          if ( $resp[0] == '3DS'){
            \App\Models\Stripe3DS::addNew($oUser->id,$resp[1],$resp[2],'generatePayment',
                    [
                      'time'=>$time,
                      'rID'=>$rID, 
                      'value'=>$value, 
                      'disc'=>$disc,
                      'id_coach'=>$id_coach  
                    ]);
            return redirect()->route(
                     'cashier.payment',
                     [$resp[1],'redirect'=>'resultado']
               );

          } else {
           return redirect()->back()
                   ->withErrors([$resp[1]])
                   ->withInput();
          }
        }
        $idStripe = $resp[1];
        $cStripe = $resp[2];
      } // END CARD
      
      if ($tpay == 'bono'){
        $oDates = \App\Models\Dates::where('id_user_rates',$id_uRate)->first();
        if (!$oDates) 
          return back()->withErrors(['Los Bonos sólo aplican a Citas'])->withInput();

        $bonoID = $req->input('id_bono', 0);
        $UserBonos = UserBonos::find($bonoID);
        if (!$UserBonos) return back()->withErrors(['Bono no encontrado'])->withInput();

        $resp = $UserBonos->check($uID);
        if ($resp != 'OK') 
            return back()->withErrors([$resp])->withInput();

        $value = 0;
      }
            
      $ChargesService = new ChargesService();
      $resp = $ChargesService->generatePayment(
              $time, $uID, $rID, $tpay, $value, 
              $disc,$idStripe,$cStripe,$id_coach);
       
      if ($resp[0] == 'error') {
          return back()->withErrors([$resp[1]]);
      }
      if ($tpay == 'bono'){

        $UserBonos->usar($resp[2],$oDates->date_type,$oDates->date);
      }

      return redirect('/admin/update/cobro/'.$resp[2])->with('success', $resp[1]);
    }

    public function chargeUser(Request $req) {
        $month = $req->input('date_payment', null);
        $operation = $req->input('type', 'all');
        $id_coach = $req->input('id_coach', null);
        if ($month)
            $time = strtotime($month);
        else
            $time = time();
        $uID = $req->input('id_user', null);
        $rID = $req->input('id_rate', null);
        $tpay = $req->input('type_payment','cash');
        $value = $req->input('importe', 0);
        $disc = $req->input('discount', 0);
        $oUser = User::find($uID);
        /************************************************************/
        $resp = ['error','Error al procesar su cobro'];
        if ($operation == 'all' || !$operation){
            $idStripe=null;$cStripe=null;
            if ($tpay == 'card'){
              //--- NUEVA TARJETA ---------------------------------------//
              if ($req->input('cardLoaded') == 0){
                $CardService = new \App\Services\CardService();
                $resp = $CardService->processCard($oUser, $req);
                if ($resp !== 'OK')  return back()->withErrors($resp)->withInput();
              }
              //--- COBRAR POR STRIPE ---------------------------------------//
              $sStripe = new \App\Services\StripeService();
              $resp = $sStripe->automaticCharge($oUser,round($value*100));
              if ( $resp[0] !== 'OK'){
                if ( $resp[0] == '3DS'){
                    \App\Models\Stripe3DS::addNew($oUser->id,$resp[1],$resp[2],'generatePayment',
                            [
                              'time'=>$time,
                              'rID'=>$rID, 
                              'value'=>$value, 
                              'disc'=>$disc,
                              'id_coach'=>$id_coach  
                            ]);
                    return redirect()->route(
                             'cashier.payment',
                             [$resp[1],'redirect'=>'resultado']
                       );

                  } else {
                   return redirect()->back()
                           ->withErrors([$resp[1]])
                           ->withInput();
                  }
              }
              $idStripe = $resp[1];
              $cStripe = $resp[2];
            } // END CARD
            
            $ChargesService = new ChargesService();
            $resp = $ChargesService->generatePayment($time, $uID, $rID, $tpay, $value, $disc,$idStripe,$cStripe,$id_coach);
            
        } else {
          $u_email = $req->input('u_email',null);
          if ($u_email && $oUser->email != $u_email){
            $oUser->email = $u_email;
            $oUser->save();
          }
          $u_phone = $req->input('u_phone',null);
          if ($u_phone && $oUser->telefono != $u_phone){
            $oUser->telefono = $u_phone;
            $oUser->save();
          }
          return $this->generateStripeLink($time, $uID, $rID, $tpay, $value, $disc,$operation,$id_coach);
        }

        if ($resp[0] == 'error') {
            return back()->withErrors([$resp[1]]);
        }
        return back()->with('success', $resp[1]);
    }

    
    public static function savePayment($date, $uID, $rID, $tpay, $value, $disc,$idStripe,$cStripe){
        $oUser = User::find($uID);
        if (!$oUser)
          return ['error', 'Usuario no encontrado'];
        $oRate = Rates::find($rID);
        if (!$oRate)
            return ['error', 'Tarifa no encontrada'];
        $dataMail = [
            'fecha_pago' => $date,
            'type_payment' => $tpay,
            'importe' => $value,
        ];
        if(!$disc) $disc = 0;
        //BEGIN PAYMENTS
            $oCobro = new Charges();
            $oCobro->id_user = $oUser->id;
            $oCobro->date_payment = date('Y-m-d');
            $oCobro->id_rate = $oRate->id;
            $oCobro->type_payment = $tpay;
            $oCobro->type = 1;
            $oCobro->import = $value;
            $oCobro->discount = $disc;
            $oCobro->type_rate = $oRate->type;
            $oCobro->id_stripe = $idStripe;
            $oCobro->customer_stripe = $cStripe;
            $oCobro->save();
        //END PAYMENTS
        $statusPayment = 'Pago realizado correctamente, por ' . payMethod($tpay);
        /*************************************************************/
        \App\Services\MailsService::sendEmailPayRate($dataMail, $oUser, $oRate);
        return ['OK', $statusPayment,$oCobro->id];
    }
            


    function generateStripeLink($time, $uID, $rID, $tpay, $importe, $disc=0,$operation,$id_coach) {

        $month = date('Y-m-d', $time);
        $oUser = User::find($uID);
        if (!$oUser)
            return ['error', 'Usuario no encontrado'];


        $oRate = Rates::find($rID);
        if (!$oRate)
            return ['error', 'Tarifa no encontrada'];
     
        if(!$disc) $disc = 0;
        //BEGIN PAYMENTS MONTH
        $auxTime = $time;
        for ($i = 0; $i < $oRate->mode; $i++) {
                //si no tenia asignada la tarifa del mes
                $newRate = new UserRates();
                $newRate->id_user = $oUser->id;
                $newRate->id_rate = $oRate->id;
                $newRate->rate_year = date('Y', $auxTime);
                $newRate->rate_month = date('n', $auxTime);
                $newRate->id_charges = null;
                $newRate->coach_id = $id_coach;
                $newRate->price = $oRate->price;
                
                $newRate->save();
            /************************************************** */
            //Next month
            $auxTime = strtotime($month . ' +1 month');
            $month = date('Y-m-d', $auxTime);
            $value = 0; //solo se factura el primer mes
            $disc = 0; //solo se factura el primer mes
        }
        //END PAYMENTS MONTH
        /************************************************************** */
        
        $data = [date('Y', $time),date('m', $time),$uID,$importe*100,$rID,$disc,$id_coach];
        $sStripe = new \App\Services\StripeService();
        $pStripe = url($sStripe->getPaymentLink('rate',$data));
        switch ($operation){
          case 'mail':
            $dataMail = [
              'fecha_pago' => $month,
              'type_payment' => $tpay,
              'importe' => $importe,
            ];
            $sent = MailController::sendEmailPayRateByStripe($dataMail, $oUser, $oRate,$pStripe);
            if ($sent == 'OK') return ['OK', 'Se ha enviado un email con el link de pago'];
            return ['error', $sent];
            break;
               case 'wsp':
                $msg = 'Te adjuntamos el enlace para el pago de **'.$oRate->name.'** en Evolutio '.$pStripe;
                return response()->json(['OK',$msg]);
                break;
            case 'copy':
                $msg = 'Te adjuntamos el enlace para el pago de '.$oRate->name.' en Evolutio '.$pStripe;
                return response()->json(['OK',$msg]);
                break;
        }
        return response()->json(['error','error']);
    }

    
    public function getPriceTax(Request $request) {
        $tax = Rates::find($request->idTax);
        return $tax->price;
    }
    
    public function sendCobroMail(Request $request) {
        $uRate = UserRates::find($request->input('u_rate'));
        if (!$uRate){
          return ['error', 'Tarifa no encontrada'];
        }
        
        $time = strtotime($uRate->rate_year.'/'.$uRate->rate_month.'/01');
        $id_coach = ($request->input('id_coach'));
        $importe = ($request->input('importe'));
        $u_email = ($request->input('u_email'));
        $u_phone = ($request->input('u_phone'));
        $type = ($request->input('type'));
        $disc = $request->input('discount', 0);
        $oUser = $uRate->user;
        if (!$oUser)
            return ['error', 'Usuario no encontrado'];

        $oRate = $uRate->rate;
        if (!$oRate)
            return ['error', 'Tarifa no encontrada'];
        
        if ($oUser->email != $u_email){
            $oUser->email = $u_email;
            $oUser->save();
        }
        if (!empty($u_phone) && $oUser->telefono != $u_phone){
            $oUser->telefono = $u_phone;
            $oUser->save();
        }
        
        $uRate->coach_id = $id_coach;
        $uRate->save();
        
        $data = [date('Y', $time),date('m', $time),$oUser->id,$importe*100,$oRate->id,$disc];
        $sStripe = new \App\Services\StripeService();
        $pStripe = url($sStripe->getPaymentLink('rate',$data));
        
        switch ($type){
            case 'mail':
                $dataMail = [
                    'fecha_pago' => date('Y-m-d', $time),
                    'type_payment' => 'card',
                    'importe' => $importe,
                ];
                
                $sentErr = MailController::sendEmailPayRateByStripe($dataMail, $oUser, $oRate,$pStripe);
                if ($sentErr == 'OK')  return response()->json(['OK', 'Se ha enviado un email con el link de pago']);
                  return response()->json(['error', $sentErr]);
                break;
            case 'wsp':
                $msg = 'Te adjuntamos el enlace para el pago de **'.$oRate->name.'** en Evolutio '.$pStripe;
                return response()->json(['OK',$msg]);
                break;
            case 'copy':
                $msg = 'Te adjuntamos el enlace para el pago de '.$oRate->name.' en Evolutio '.$pStripe;
                return response()->json(['OK',$msg]);
                break;
        }
            
        return response()->json(['error','error']);

    }
    
    
    
    public function sendCobroGral(Request $request) {
        $dID = $request->input('idDate');
        
        $oDate = \App\Models\Dates::find($dID);
        if (!$oDate || $oDate->id != $dID) {
            return response()->json(['error','Cita No encontrada']);
        }
        
        $coach = $oDate->coach;
        
        $u_email = ($request->input('u_email'));
        $u_phone = ($request->input('u_phone'));
        $type = ($request->input('type'));
        
        $uRates = $oDate->uRates;
        if (!$uRates){
          return ['error', 'Servicio no encontrado'];
        }
        $oUser = $uRates->user;
        if (!$oUser){
          return ['error', 'Usuario no encontrado'];
        }
        $oRate = $uRates->rate;
        $importe = $uRates->price;
        if (!empty($u_email) && $oUser->email != $u_email){
            $oUser->email = $u_email;
            $oUser->save();
        }
        if (!empty($u_phone) && $oUser->telefono != $u_phone){
            $oUser->telefono = $u_phone;
            $oUser->save();
        }
        
        $data = [$dID,$oUser->id,$importe*100,$oRate->id];
        $sStripe = new \App\Services\StripeService();
        $rType = \App\Models\TypesRate::find($oRate->type);
        $pStripe = url($sStripe->getPaymentLink($rType->type,$data));
        
        switch ($type){
            case 'mail':
                $resp = MailController::sendEmailPayDateByStripe($oDate, $oUser, $oRate,$coach,$pStripe,$importe);
                if ($resp == 'OK')  return response()->json(['OK', 'Se ha enviado un email con el link de pago']);
                  return response()->json(['error', $resp]);
                break;
            case 'wsp':
                $msg = 'Te adjuntamos el enlace para el pago de **'.$oRate->name.'** en Evolutio '.$pStripe;
                return response()->json(['OK',$msg]);
                break;
            case 'copy':
                $msg = 'Te adjuntamos el enlace para el pago de '.$oRate->name.' en Evolutio '.$pStripe;
                return response()->json(['OK',$msg]);
                break;
        }
            
        return response()->json(['error','error']);

    }
    
    public function sendCobroBono(Request $request) {
        $oBono = \App\Models\Bonos::find($request->input('u_bono'));
        if (!$oBono){
          return ['error', 'Bono no encontrado'];
        }
        $disc = 0;
        $importe = $oBono->price;
        $u_email = ($request->input('u_email'));
        $u_phone = ($request->input('u_phone'));
        $u_ID    = ($request->input('u_ID'));
        $type    = ($request->input('type'));
        $oUser = User::find($u_ID);
        if (!$oUser)
            return ['error', 'Usuario no encontrado'];
        
        if ($oUser->email != $u_email){
            $oUser->email = $u_email;
            $oUser->save();
        }
        if (!empty($u_phone) && $oUser->telefono != $u_phone){
            $oUser->telefono = $u_phone;
            $oUser->save();
        }
        
        
        if ($oBono->tarifa == 'fidelity'){
          if ($oUser->getPlan() == 0) $importe = priceNoFidelity($importe);
        }
              
        
        $data = [$oUser->id,$importe*100,$oBono->id,$disc];
        $sStripe = new \App\Services\StripeService();
        $pStripe = url($sStripe->getPaymentLink('bono',$data));
        
        switch ($type){
            case 'mail':
                $dataMail = [
                    'fecha_pago' => date('Y-m-d'),
                    'type_payment' => 'card',
                    'importe' => $importe,
                ];
                
                $sentErr = MailController::sendEmailPuncharseBonoByStripe($dataMail, $oUser, $oBono,$pStripe);
                if ($sentErr == 'OK')  return response()->json(['OK', 'Se ha enviado un email con el link de pago']);
                  return response()->json(['error', $sentErr]);
                break;
            case 'wsp':
                $msg = 'Te adjuntamos el enlace para el pago de **'.$oBono->name.'** en Evolutio '.$pStripe;
                return response()->json(['OK',$msg]);
                break;
            case 'copy':
                $msg = 'Te adjuntamos el enlace para el pago de '.$oBono->name.' en Evolutio '.$pStripe;
                return response()->json(['OK',$msg]);
                break;
        }
            
        return response()->json(['error','error']);

    }

}
