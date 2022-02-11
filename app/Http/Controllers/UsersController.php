<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\Rates;
use App\Models\UsersRates;
use \App\Traits\Usuarios\HorariosTraits;
use Carbon\Carbon;
class UsersController extends Controller {

  use HorariosTraits;

  public function index($type = null) {

    $year = getYearActive();
    $UsersLiqService = new \App\Services\UsersLiqService();
    $data = $UsersLiqService->liqByMonths($year, $type);
    $data['type'] = $type;
    $data['date'] = Carbon::now();
    $auxMonths = [0=>0];
    for($i=1;$i<13;$i++) $auxMonths[$i] = 0;
    //---------------------------------------------------------------//
    // get expenses asociated
    $oExpenses = \App\Models\Expenses::where('to_user', '>', 0)
            ->whereYear('date', '=', $year)
            ->orderBy('date')
            ->get();
    $lstExpType = \App\Models\Expenses::getTypes();
    if ($oExpenses) {
      foreach ($oExpenses as $item) {
        $auxM = intval(substr($item->date, 5, 2));
        if (!isset($data['aLiq'][$item->to_user])){
          $data['aLiq'][$item->to_user] = $auxMonths;
          $data['aLiqTotal'][$item->to_user] = 0;
        }
        
        $data['aLiq'][$item->to_user][$auxM] += $item->import;
        $data['aLiqTotal'][$item->to_user] += $item->import;
      }
    }
    $oUsr = new User();
    $data['uRoles'] = $oUsr->roles;
    
    return view('/admin/usuarios/index', $data);
  
  }
  public function upd($id) {

    $oUser = User::find($id);
    //---------------------------------------------------------------//
    $month = date('Y-m');
    $lstMonts = lstMonthsSpanish();
    $aMonths = [];
    $year = getYearActive();
    foreach ($lstMonts as $k => $v) {
      if ($k > 0)
        $aMonths[$year . '-' . str_pad($k, 2, "0", STR_PAD_LEFT)] = $v;
    }
    //---------------------------------------------------------------//
    $uRates = \App\Models\UsersRates::where('user_id', $oUser->id)->first();
    $salario_base = $ppc = $comm = $pppt = $ppcg = 0;
    if ($uRates) {
      $salario_base = $uRates->salary;
      $ppc = $uRates->ppc;
      $pppt = $uRates->pppt;
      $ppcg = $uRates->ppcg;
      $comm = $uRates->comm;
    }

    //---------------------------------------------------------------//
    return view('/admin/usuarios/_form', [
        'rates' => Rates::all(),
        'oUser' => $oUser,
        'aMonths' => $aMonths,
        'year' => $year,
        'month' => $month,
        'salario_base' => $salario_base,
        'ppc' => $ppc,
        'pppt' => $pppt,
        'ppcg' => $ppcg,
        'comm' => $comm,
    ]);
  }

  public function saveUser(Request $request) {
    
    $rates = $request->input('rate_ids');

    $id = $request->input('id');
    $oUser = User::find($id);
    $oUser->name = $request->input('name');
    $oUser->email = $request->input('email');
    $oUser->role = $request->input('role', '');
    $oUser->dni = $request->input('dni');
    $oUser->address = $request->input('address');
      
    if ($request->input('password'))
      $oUser->password = bcrypt($request->input('password'));

    $oUser->phone = $request->input('phone');
    $oUser->iban = $request->input('iban');
    $oUser->ss = $request->input('ss');
    $oUser->save();
    $uRates = UsersRates::where('user_id', $oUser->id)->first();

    if (!$uRates) {
      $uRates = new UsersRates();
      $uRates->user_id = $oUser->id;
    }
    $uRates->salary = intval($request->input('salario_base'));
    $uRates->ppc = $request->input('ppc');
    $uRates->pppt = $request->input('pppt');
    $uRates->ppcg = $request->input('ppcg');
    $uRates->comm = $request->input('comm');
    $uRates->save();
    return redirect()->back()->with('success', 'Registro actualizado');
  }

  public function disable($id) {
    $usuario = User::find($id);
    $usuario->status = 0;
    $usuario->save();
    return back()->with(['success'=>'Usuario desactivado']);
  }

  public function activate($id) {
    $usuario = User::find($id);
    $usuario->status = 1;
    $usuario->save();
    return back()->with(['success'=>'Usuario activado']);
  }
  
    public function sendEmailUsuarios($id) {

    # _info_trainers_email
    $oUser = User::find($id);
    $email = $oUser->email;
    $sended = \Illuminate\Support\Facades\Mail::send('emails._info_user_email', ['user' => $oUser], function ($message) use ($email) {
              $message->subject('Registro de Usuario');
              $message->from(config('mail.from.address'), config('mail.from.name'));
              $message->to($email);
            });

    return "Correo enviado a " . $email;
//         return "No se pudo enviar el correo a ".$emailTrainer;
  }
  
    public function newItem() {
    $oUser = new User();
    return view('/admin/usuarios/new', [
        'roles' => $oUser->roles,
    ]);
    
  }
  
   public function create(Request $request) {
    $issetUser = User::where('email', $request->input('email'))->get();
    if (count($issetUser) > 0) {
      return "email duplicado";
    } else {
      $oUser = new User();
      $oUser->name = $request->input('name');
      $oUser->email = $request->input('email');
      $oUser->password = bcrypt($request->input('password'));
      $oUser->remember_token = str_random(60);
      $oUser->role = $request->input('role', '');
      $oUser->phone = $request->input('phone');
      $oUser->password = bcrypt($request->input('password'));
      $oUser->iban = $request->input('iban');
      $oUser->ss = $request->input('ss');
      $oUser->save();
      
      $uRates = UsersRates::where('user_id', $oUser->id)->first();

      if (!$uRates) {
        $uRates = new UsersRates();
        $uRates->user_id = $oUser->id;
      }
      $uRates->salary = intval($request->input('salario_base'));
      $uRates->ppc = $request->input('ppc');
      $uRates->pppt = $request->input('pppt');
      $uRates->ppcg = $request->input('ppcg');
      $uRates->comm = $request->input('comm');
      $uRates->save();
    
    
      return back();
    }
  }
}
