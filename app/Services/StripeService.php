<?php

namespace App\Services;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;

class StripeService {
    
    private  $sKey;
    private  $sPRivKey;
    private   $aTypes = [
      '','rate','sucrip','nutri','fisio','bono'
    ];
    public function __construct()
    {
         $this->sKey = config('cashier.key');
         $this->sPRivKey = config('cashier.secret');
     }
     
    function getPaymentLink($type,$data){
        foreach ($data as $k=>$v) $data[$k] = encriptID ($v);
        $code = implode('-', $data);
        
        if ($type == 'bono')
          return '/copra-de-bonos/'.$code.'/'.getKeyControl($code);
        
        $tId = array_search($type, $this->aTypes);
        return '/pago-simple/'.$tId.'/'.$code.'/'.getKeyControl($code);
    }
    
    function getPaymentLinkData($t,$code,$control){
        
                
        if (!isset($this->aTypes[$t])) return false;
        if (getKeyControl($code) !== $control)return false;
        
        $data = explode('-', $code);
        foreach ($data as $k=>$v) $data[$k] = desencriptID($v);
        return [$this->aTypes[$t],$data];

    }
    
    function pagoSimple($amount,$data){
        if (!isset($data['stripeToken']) || trim($data['stripeToken']) == ''){
          return 'Tarjeta no ingresada';
        }
         try {
            Stripe::setApiKey($this->sPRivKey);
            $customer = Customer::create(array(
                    'email' => $data['stripeEmail'],
                    'source' => $data['stripeToken']
                ));
            return Charge::create(array(
                    'customer' => $customer->id,
                    'amount' => $amount,
                    'currency' => 'eur'
            ));
        } catch (\Exception $ex) {
          if ($ex->getCode() == 0 && isset($ex->payment)){
            $payment = $ex->payment;
            return ['3DS',$payment->id,$payment->customer];
          }
          
          return $this->codesErrors($ex->getStripeCode());
        }
    }
  
      
  /**
   * 
   * @param Request $req
   * @return type
   */
  public function newCheckout($oUser,$amount,$name) {
    try {

      Stripe::setApiKey($this->sPRivKey);
      $this->checkUser($oUser);

      return $oUser->checkoutCharge($amount,$name, 1,
            [
            'success_url' => route('customer.pay.success'),
            'cancel_url' => route('customer.pay.cancel'),
            ]);
    } catch (\Exception $ex) {
      return $this->codesErrors($ex->getStripeCode());
    }
  }
    
    /**
   * 
   * @param Request $req
   * @return type
   */
  public function automaticCharge($oUser,$amount) {
    try {

      Stripe::setApiKey($this->sPRivKey);
      $this->checkUser($oUser);

      $paymentMethod = $oUser->getPayCard();
      if (!$paymentMethod) return 'No tiene una tarjeta asociada';
      
      $stripeCharge = $oUser->charge(
            $amount, $paymentMethod->id
        );
      
      if ($stripeCharge){
        if ($stripeCharge->status == 'succeeded'){
            return ['OK',$stripeCharge->id,$stripeCharge->customer];
        }
      }
      return ['error','Ocurrió un error al procesar su Tarjeta. Por favor, intentelo nuevamente.'];
    } catch (\Exception $ex) {

      if ($ex->getCode() == 0 && isset($ex->payment)){
        $payment = $ex->payment;
        return ['3DS',$payment->id,$payment->customer];
      }
      
      return $this->codesErrors($ex->getStripeCode());
    }
  }
    /**
   * 
   * @param Request $req
   * @return type
   */
  public function checkUser($oUser) {
//      Stripe::setApiKey($this->sPRivKey);
      if (!$oUser->hasStripeId()){
        $oUser->createAsStripeCustomer();
        return null;
      }
      try {
        $customer = \Stripe\Customer::retrieve($oUser->stripe_id,[]);
      } catch (\Exception $ex) {
        $oUser->stripe_id = null;
        $oUser->save();
        $oUser->createAsStripeCustomer();
      }
  }
    
 /**
   * 
   * @param Request $req
   * @return type
   */
  public function subscription_changeCard($oUser,$cc_number,$cc_expide_mm,$cc_expide_yy,$cc_cvc) {


    try {

      Stripe::setApiKey($this->sPRivKey);
        if (!$cc_number || !$cc_expide_mm || !$cc_expide_yy || !$cc_cvc){
            return 'Por favor, complete los campos requeridos.';
        }
        $this->checkUser($oUser);
        /** si no tiene el metodo de pago, se genera uno nuevo* */
        $paymentMethod = \Stripe\PaymentMethod::create([
                    'type' => 'card',
                    'card' => [
                        'number' => $cc_number,
                        'exp_month' => $cc_expide_mm,
                        'exp_year' => $cc_expide_yy,
                        'cvc' => $cc_cvc,
                    ],
                    'billing_details' => [
                        'name' => $oUser->name,
                        'email' => $oUser->email,
                    ]
        ]);
        $oUser->updateDefaultPaymentMethod($paymentMethod);
        $oUser->updateDefaultPaymentMethodFromStripe();
        return 'updated';//'Tarjeta de crédito actualizada';
    } catch (\Exception $ex) {
      return $this->codesErrors($ex->getStripeCode(),$ex->getMessage());
    }
  }
  
  private function codesErrors($stripeCode,$msg=null) {
//      dd($stripeCode);
      switch ($stripeCode) {
            case 'token_already_used':
                $error = 'No puedes usar un token de Stripe más de una vez';
            case "parameter_invalid_integer":
                $error = "Monto a cobrar inválido";
                break;
            case "incorrect_number":
                $error = "Número de tarjeta inválido";
                break;
            case "invalid_number":
                $error = "Número de tarjeta inválido";
                break;
            case "invalid_expiry_month":
                $error = "Mes de vencimiento inválido";
                break;
            case "invalid_expiry_year":
                $error = "Año de vencimiento inválido";
                break;
            case "expired_card":
                $error = "Tarjeta expirada";
                break;
            case "card_declined":
                $error = "Tarjeta rechazada";
                break;
            case "invalid_cvc":
            case "incorrect_cvc":
                $error = "Código de seguridad inválido";
                break;
            case "resource_missing":
                $error = $msg;
                break;
            default:
                $error = 'Ocurrió un error al procesar su Tarjeta. Por favor, intentelo nuevamente.';
//                $error = 'Error al procesar su pago';
        }
        return $error;
  }
}