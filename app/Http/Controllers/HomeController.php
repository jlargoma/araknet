<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Auth;
use App\User;
use Mail;
use App\Classes\Mobile;
use URL;
use Stripe;
class HomeController extends Controller
{

    public function index(Request $request)
    {
        $user   = Auth::user();
        $rate   = $user->getLastRate();
        $stripe = self::$stripe;
        return view('users.index', ['user' => $user, 'rate' => $rate, 'stripe' => $stripe]);
    }
    
    public function contrato(Request $request)
    {
        $user = Auth::user();
        $rate = $user->getLastRate();
        $stripe = self::$stripe;

        return view('users.mi-contrato', ['user' => $user, 'rate' => $rate, 'stripe' => $stripe]);
    }

    /* METODOS DE PAGO */


    public function pagos(Request $request)
    {
        $stripe = new Stripe;
        $stripe = Stripe::make('sk_test_eCEVq9hsIeu0SSCph7Om1XIM00h4bO1Hx3');

        $user = Auth::user();
        $rate = $user->getLastRate();

        /* Stripe data customer */
        $cards = $stripe->cards()->all($user->id_stripe);

        return view('users.mis-pagos', ['user' => $user,  'rate' => $rate, 'stripe' => self::$stripe, 'cards' => $cards ]);
    }

    public function addCard(Request $request)
    {
        $stripe = new Stripe;
        $stripe = Stripe::make(self::$stripe['key']);

        $user = User::find($request->input('id_user'));

        $card = $stripe->cards()->create($user->id_stripe, $request->input('stripeToken'));

        return redirect()->action('HomeController@pagos');
    }

    public function deleteCard(Request $request, $cardId)
    {
        $cardId = base64_decode($cardId);

        $stripe = new Stripe;
        $stripe = Stripe::make(self::$stripe['key']);

        $user = Auth::user();

        $card = $stripe->cards()->delete($user->id_stripe, $cardId);

        return redirect()->action('HomeController@pagos');
    }


    /* FIN METODOS DE PAGO */

    /* MIS SERVICIOS */

    public function servicios(Request $request)
    {
        $user = Auth::user();

        $stripe = new Stripe;
        $stripe = Stripe::make(self::$stripe['key']);

        $userSuscription = $stripe->subscriptions()->all($user->id_stripe);

        return view('users.mis-servicios', ['user' => $user, 'userSuscription' => $userSuscription ,]);
    }

    public function cancelSuscription(Request $request, $suscriptionId)
    {
        $suscriptionId = base64_decode($suscriptionId);

        $stripe = new Stripe;
        $stripe = Stripe::make(self::$stripe['key']);

        $user = Auth::user();

        $subscription = $stripe->subscriptions()->cancel($user->id_stripe, $suscriptionId, true);
        // $card = $stripe->cards()->delete($user->id_stripe, $cardId);
        
        return redirect()->action('HomeController@pagos');
    }



    /* FIN MIS SERVICIOS */

    /* MI CUENTA */

    public function cuenta(Request $request)
    {
        $user = Auth::user();
        return view('users.mi-cuenta', ['user' => $user]);
    }

    public function updateClient(Request $request)
    {
        $id = $request->input('id');
        $userToUpdate = User::find($id);
        $userToUpdate->name     = $request->input('name');
        $userToUpdate->password = bcrypt($request->input('password'));
        $userToUpdate->telefono = $request->input('telefono');
        $userToUpdate->address = $request->input('address');
        if ($userToUpdate->save()) {
            return redirect()->action('HomeController@cuenta');
            
        }
    }

    

    public function generarSuscripcion(Request $request)
    {
        $stripe = new Stripe;
        $stripe = Stripe::make('sk_test_eCEVq9hsIeu0SSCph7Om1XIM00h4bO1Hx3');

        $stripeToken = $request->input('stripeToken');
        $stripeToken = 'tok_visa';
        $rate = \App\Rates::find($request->input('id_rate'));
        $user = User::find($request->input('id_user'));
        
        /* Creamos el cliente */
        $customer = $stripe->customers()->create([
            'email' => $user->email,
        ]);
        $card = $stripe->cards()->create($customer['id'], $stripeToken);


        /* Creamos la suscripcion del cliente */
        $subscription = $stripe->subscriptions()->create($customer['id'], [
            'plan' => $rate->planStripe,
        ]);

        $user->id_stripe = $customer['id'];
//        $user->contractAccepted = 1;
        $user->save();

        $userSuscriptions = new \App\UsersSuscriptions();
        $userSuscriptions->id_user = $user->id;
        $userSuscriptions->id_suscription = $subscription['id'];
        $userSuscriptions->save();

        return redirect()->action('HomeController@index');
        
    }

    /* FIN MI CUENTA */


    public function changeActiveYear(Request $request)
    {
      $year = $request->input('year');
      if (is_numeric($year)){
        $current = date('Y')+3;
        if ($year<=$current && $year>($current-6)){
           setcookie('ActiveYear', $year, time() + (86400 * 30), "/"); // 86400 = 1 day
           return 'cambiado';
        }
      }
      return '';
    
    }
   
}