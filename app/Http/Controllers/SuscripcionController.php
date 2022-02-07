<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;

class SuscripcionController extends Controller
{
public function pago(Request $request)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
        $customer = Customer::create(array(
                'email' => $request->stripeEmail,
                'source' => $request->stripeToken
            ));
        $charge = Charge::create(array(
                'customer' => $customer->id,
                'amount' => 1990,
                'currency' => 'usd'
            ));
        return 'Cargo exitoso!';
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}
