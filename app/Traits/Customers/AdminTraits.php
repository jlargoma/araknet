<?php

namespace App\Traits\Customers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Customers;
use App\Http\Controllers\StripeController;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Rates;
use App\Models\TypesRate;
use App\Models\Dates;
use App\Models\CustomersNotes;
use App\Models\CustomersRates;
use App\Models\Charges;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

trait AdminTraits {

  public function newCustomer(Request $request) {
    return view('admin.clientes.forms.new');
  }

  public function saveCustomer(Request $request) {
    $issetUser = Customers::where('email', $request->input('email'))->get();
    if (count($issetUser) > 0) {
      return redirect('/admin/cliente/nuevo')->withErrors(["email duplicado"])->withInput();
    } else {
      $oObj = new Customers();
      $oObj->name = $request->input('name');
      $oObj->email = $request->input('email');
      $oObj->password = str_random(60); //bcrypt($request->input('password'));
      $oObj->remember_token = str_random(60);
      $oObj->phone = $request->input('phone');
      $oObj->dni = $request->input('dni');
      $oObj->address = $request->input('address');
      $oObj->population = $request->input('population');
      $oObj->province = $request->input('province');
      $oObj->iban = $request->input('iban');
      if ($oObj->save()) {
        $email = $oObj->email;
        $sended = \Illuminate\Support\Facades\Mail::send('emails._create_customer_email', ['customer' => $oObj], function ($message) use ($email) {
                  $message->subject('Inscripción en Araknet');
                  $message->from('info@Araknet.tech', 'Inscripción Araknet');
                  $message->to($email);
                });
        return redirect('/admin/cliente/informe/' . $oObj->id);
      }
    }
    return redirect('/admin/cliente/nuevo')->withErrors(["Error al crear el usuario"])->withInput();
  }

  /**
   * /clientes/update
   * @param Request $request
   * @return type
   */
  public function update(Request $request) {

    $id = $request->input('id');
    $oObj = Customers::find($id);
    $oObj->name = $request->input('name');
    $oObj->email = $request->input('email');
    $oObj->dni = $request->input('dni');
    $oObj->status = $request->input('status');

    $oObj->iban = $request->input('iban');
    $oObj->address = $request->input('address');
    $oObj->population = $request->input('population');
    $oObj->province = $request->input('province');
    $oObj->hotspot_imac = $request->input('hotspot_imac');
    $oObj->hotspot_date = $request->input('hotspot_date');
    $oObj->user_id = $request->input('user_id');
    if ($request->input('password'))
      $oObj->password = bcrypt($request->input('password'));

    $oObj->phone = $request->input('phone');
    $oObj->save();

    $oObj->setMetaContent('costComercial', $request->input('costComercial'));
    $oObj->setMetaContent('costAlquiler', $request->input('costAlquiler'));
    $oObj->setMetaContent('costTotal', $request->input('costTotal'));

    return redirect()->back()->with('success', 'Cliente actualizado');
  }

  public function showResult(Request $request) {
    return view('customers.message');
  }

  public function rmContracts(Request $request) {
    $uID = $request->input('customer_id');

    $oUser = User::find($uID);
    if (!$oUser) {
      return response()->json(['error', 'cliente no encontrado']);
    }

    $uPlan = $oUser->getPlan();
    // Already Signed  -------------------------------------------
    if ($uPlan !== null) {
      $fileName = $oUser->setMetaContent('contrato_FIDELITY_' . $uPlan, null);
      return response()->json(['OK', 'Contrato removido']);
    }

    return response()->json(['error', 'Contrato no encontrad']);
  }

  public function getMail($id) {
    $oObjet = Customers::find($id);
    if ($oObjet) {
      return [$oObjet->email, $oObjet->phone];
    }
    return ['', ''];
  }

  
  public function disable($id) {
    $oObjt = Customers::find($id);
    $oObjt->status = 0;
    if ($oObjt->save()) die('OK');
    else die('ERROR');
  }

  public function activate($id) {
    $oObjt = Customers::find($id);
    $oObjt->status = 1;
    if ($oObjt->save()) die('OK');
    else die('ERROR');
  }
  
  
}
