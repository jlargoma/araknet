<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Carbon\Carbon;
use Stripe;
use App\Models\Rates;
use App\Models\TypesRate;
use App\Models\UserRates;

class RatesController extends Controller {

  public function index() {
      
    $types = TypesRate::orderBy('t_orden')->pluck('name','id');
    $serv=[];
    foreach ($types as $k=>$v){
        $serv[$k] = Rates::where('status', 1)->where('type',$k)->orderBy('name')->get();
    }
//      dd($serv,$types);
    return view('/admin/rates/index', [
        'types' => $types,
        'services' => $serv,
        'subfamily' => TypesRate::subfamily(),
        'oldRates' => Rates::where('status', 0)->orderBy('order', 'asc')->orderBy('name', 'asc')->get(),
    ]);
  }

  public function newRate() {
    return view('/admin/rates/new', [
        'taxes' => Rates::all(),
        'typesRate' => TypesRate::all(),
        'subtype' => TypesRate::subfamily(),
    ]);
  }

  public function create(Request $request) {
    $rates = new Rates();

    $rates->name = $request->input('name');
    $rates->max_pax = $request->input('max_pax');
    $rates->type = $request->input('type');
    $rates->price = $request->input('price');
    $rates->mode = $request->input('mode');
    $rates->status = 1;
    $rates->cost = 0; //$request->input('cost');
    $rates->tarifa = $request->input('tarifa');
    $rates->subfamily = $request->input('subfamily');
    $rates->order = 99;
    $rates->save();
    return redirect()->back()->with(['success'=>'Servicio agregado']);
  }

  public function actualizar($id) {

    return view('/admin/rates/update', [
        'rate' => Rates::find($id),
        'typesRate' => TypesRate::all(),
    ]);
  }

  public function upd_fidelity(Request $request) {
    $id = $request->input('id');
    $oRates = Rates::find($id);
    $oRates->tarifa = $request->input('val');
    if ($oRates->save()) return 'OK';
    return 'ERROR';
  }
  public function update(Request $request) {

    $id = $request->input('id');
    $oRates = Rates::find($id);
    $oRates->name = $request->input('name');
    $oRates->max_pax = $request->input('max_pax');
    $oRates->type = $request->input('type');
    $oRates->price = $request->input('price');
    $oRates->mode = $request->input('mode');
    $oRates->cost = $request->input('cost');
    $oRates->subfamily = $request->input('subfamily');
//    $oRates->planStripe = $request->input('plan');
    if ($oRates->save()) {
      echo "Cambiada!!";
    }
  }

  public function delete($id) {
    $rate = Rates::find($id);

//    $stripe = new Stripe;
//    $stripe = Stripe::make(HomeController::$stripe['key']);
//    $plan = $stripe->plans()->delete($rate->planStripe);
    $rate->status = 0;
    if ($rate->save()) {
        return redirect()->back()->with(['success'=>'Servicio eliminado']);
    }
  }

  public function unassignedRate($idUserRate) {
    $userRate = UserRates::find($idUserRate);
    if (!$userRate){
        return redirect()->back()->withErrors(['Tarifa no encontrada']);
    }
    if ($userRate->charges){
      return redirect()->back()->withErrors(['Tarifa cobrada.']);
    }
    $appointment = \App\Models\Dates::where('id_user_rates',$userRate->id)->first();
    if ($appointment){
      return redirect()->back()->withErrors(['Tarifa asociada a una cita.']);
    }
    $date = getMonthSpanish($userRate->rate_month).' '.$userRate->rate_year;
    if ($userRate->delete()) {
      return redirect()->back()->with('success','Servicio removido para el perdiodo '.$date);
    }
  }

}
