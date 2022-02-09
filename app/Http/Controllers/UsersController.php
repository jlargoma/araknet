<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Response;
use App\Http\Requests;
use \Carbon\Carbon;
use Mail;
use URL;
use Cache;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Models\CustomersRates;
use \App\Traits\EntrenadoresTraits;
use \App\Traits\ClientesTraits;
use \App\Traits\ValoracionTraits;

class UsersController extends Controller {

  use EntrenadoresTraits,ValoracionTraits,
      ClientesTraits;

  public function index() {
dd();
    return view('/admin/usuarios/index', [
        'users' => User::whereIn('role', [
            'admin',
            'administrativo'
        ])->get(),
        'date' => Carbon::now(),
    ]);
  }

  public function newCustomer(Request $request) {
    return view('admin.usuarios.clientes.forms.new');
  }

  public function saveCustomer(Request $request) {
    $issetUser = User::where('email', $request->input('email'))->get();
    if (count($issetUser) > 0) {
      return redirect('/admin/usuarios/nuevo')->withErrors(["email duplicado"])->withInput();
    } else {
      $newUser = new User();
      $newUser->name = $request->input('name');
      $newUser->email = $request->input('email');
      $newUser->password = str_random(60); //bcrypt($request->input('password'));
      $newUser->remember_token = str_random(60);
      $newUser->role = 'user';
      $newUser->phone = $request->input('phone');
      $newUser->dni = $request->input('dni');
      $newUser->address = $request->input('address');
      $newUser->population = $request->input('population');
      $newUser->province = $request->input('province');
      $newUser->iban = $request->input('iban');
      if ($newUser->save()) {
        $email = $newUser->email;
        $sended = Mail::send('emails._create_user_email', ['user' => $newUser], function ($message) use ($email) {
                  $message->subject('Inscripción en Araknet');
                  $message->from('info@Araknet.tech', 'Inscripción Araknet');
                  $message->to($email);
                });
        return redirect('/admin/usuarios/informe/' . $newUser->id);
      }
    }
    return redirect('/admin/usuarios/nuevo')->withErrors(["Error al crear el usuario"])->withInput();
  }

  public function nueva($role = null) {
    $url = URL::previous();
    if (!$role) {
      if (preg_match('/usuarios/i', $url)) {
        $role = 'admin';
      } elseif (preg_match('/clientes/i', $url)) {
        $role = 'user';
      } elseif (preg_match('/entrenadores/i', $url)) {
        $role = 'teach';
      } elseif (preg_match('/nutricion/i', $url)) {
        $role = 'nutri';
      }
    }

    $aCoachs = [];
    $uCoach = null;
    if ($role == 'user') {
      $aCoachs = User::whereCoachs('teach')->orderBy('name')->pluck('name', 'id')->toArray();
    }
    return view('/admin/usuarios/nueva', [
        'rates' => \App\Models\Rates::orderBy('status', 'desc')->orderBy('name', 'asc')->get(),
        'role' => $role,
        'aCoachs' => $aCoachs,
        'uCoach' => $uCoach,
    ]);
  }

  public function create(Request $request) {
    $issetUser = User::where('email', $request->input('email'))->get();
    if (count($issetUser) > 0) {
      return "email duplicado";
    } else {
      $newUser = new User();
      $newUser->name = $request->input('name');
      $newUser->email = $request->input('email');
      $newUser->password = bcrypt($request->input('password'));
      $newUser->remember_token = str_random(60);
      $newUser->role = $request->input('role', 'user');
      $newUser->phone = $request->input('phone');
      $newUser->password = bcrypt($request->input('password'));

      if ($newUser->save()) {
        /*         * ************************************ */
        $rateID = $request->input('rate_id');
        if ($rateID > 0) {
          $oRate = \App\Models\Rates::find($request->input('rate_id'));
          if ($oRate) {
            $rateUser = new CustomersRates();
            $rateUser->customer_id = $newUser->id;
            $rateUser->rate_id = $oRate->id;
            $rateUser->rate_year = date('Y');
            $rateUser->rate_month = date('m');
            $rateUser->save();
          }
        }
        /*         * ************************************ */
        if ($newUser->role == 'user') {
          $uCoach = new \App\Models\CoachUsers();
          $uCoach->customer_id = $newUser->id;
          $uCoach->user_id = $request->input('u_coach', 0);
          $uCoach->save();
        }
        /*         * ************************************ */

        $email = $newUser->email;
        $role = $newUser->role;
        switch ($role) {
          case 'admin';
          case 'administrativo';
            return redirect('/admin/usuarios');
            break;
          case 'teach';
          case 'nutri';
          case 'fisio';
          case 'teach_nutri';
          case 'teach_fisio';
            return redirect('/admin/entrenadores');
            break;
          default :
            $sended = Mail::send('emails._create_user_email', ['user' => $newUser], function ($message) use ($email) {
                      $message->subject('Registro de Usuario');
                      $message->subject('Inscripción en Araknet');
                      $message->from('info@Araknet.tech', 'Inscripción Araknet');
                      $message->to($email);
                    });
            if ($request->ajax())
              return 'OK';
            return redirect('/admin/clientes');
            break;
        }
      }
    }
    if ($request->ajax())
      return 'error';
  }

  public function actualizar($id) {
    return view('/admin/usuarios/update', [
        'rates' => \App\Models\Rates::all(),
        'user' => User::find($id)
    ]);
  }

  public function getMail($id) {
    $oUser = User::find($id);
    if ($oUser) {
      return [$oUser->email, $oUser->phone];
    }
    return ['', ''];
//        return ($oUser) ? $oUser->email : '';
  }
  public function getRates($uID) {
    $aLst = CustomersRates::where('customer_id',$uID)
            ->where('active',1)->pluck('rate_id')->toArray();
    \App\Services\ValoracionService::RateLstID($uID,$aLst);
    return response()->json(array_unique($aLst));
  }

  public function getList() {
    return \App\User::where('role', 'user')
                    ->where('status', 1)
                    ->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
  }

  public function delete($id) {
    User::find($id)->delete();
    return redirect('/admin/usuarios');
  }

  public function update(Request $request) {
    $rates = $request->input('rate_ids');

    $id = $request->input('id');
    $customerToUpdate = User::find($id);
    $customerToUpdate->name = $request->input('name');
    $customerToUpdate->email = $request->input('email');
    $customerToUpdate->role = $request->input('role', 'user');
    $customerToUpdate->dni = $request->input('dni');
    $customerToUpdate->address = $request->input('address');
      
    if ($request->input('password'))
      $customerToUpdate->password = bcrypt($request->input('password'));

    $customerToUpdate->phone = $request->input('phone');

   

    $customerToUpdate->iban = $request->input('iban');
    $customerToUpdate->ss = $request->input('ss');
    $CoachRates = \App\Models\CoachRates::where('customer_id', $customerToUpdate->id)->first();

    if (!$CoachRates) {
      $CoachRates = new \App\Models\CoachRates();
      $CoachRates->customer_id = $customerToUpdate->id;
    }
    $CoachRates->salary = intval($request->input('salario_base'));
    $CoachRates->ppc = $request->input('ppc');
    $CoachRates->pppt = $request->input('pppt');
    $CoachRates->ppcg = $request->input('ppcg');
    $CoachRates->comm = $request->input('comm');
    $CoachRates->save();
    return redirect()->back()->with('success', 'Registro actualizado');
  }

  public function disable($id) {
    $usuario = User::find($id);
    $usuario->status = 0;
    if ($usuario->save()) die('OK');
    else die('ERROR');
    if ($usuario->save()) {
      if ($usuario->role == 'admin') {
        return redirect('/admin/usuarios');
      } elseif ($usuario->role == 'teach' || $usuario->role == 'teacher') {
        return redirect('/admin/entrenadores');
      } else {
        return redirect('/admin/clientes');
      }
    }
  }

  public function activate($id) {
    $usuario = User::find($id);
    $usuario->status = 1;
    if ($usuario->save()) die('OK');
    else die('ERROR');
  }

  public function actualizarUsuario($id) {

    return view('/admin/usuarios/_form', [
        'rates' => \App\Models\Rates::all(),
        'user' => User::find($id)
    ]);
  }

  public function sendEmailEntrenadores($id) {

    # _info_trainers_email
    $trainer = User::find($id);
    $emailTrainer = $trainer->email;
    $sended = Mail::send('emails._info_trainers_email', ['user' => $trainer], function ($message) use ($emailTrainer) {
              $message->subject('Registro de Usuario');
              $message->from(config('mail.from.address'), config('mail.from.name'));
              $message->to($emailTrainer);
            });

    return "Correo enviado a " . $emailTrainer;
//         return "No se pudo enviar el correo a ".$emailTrainer;
  }

  public function duplicateRatesUser($date = "") {
    $date = Carbon::now();
    $customers = User::where('role', 'user')->get();

    foreach ($customers as $customer) {
      $oldRatesUser = CustomersRates::where('customer_id', $customer->id)
              ->whereMonth('created_at', '=', $date->copy()->format('m'))
              ->whereYear('created_at', '=', $date->copy()->format('Y'))
              ->get();
      echo "Cliente: " . $customer->name . "<br>";
      $total_tarifas_cliente = count($oldRatesUser);
      echo "Tarifas encontradas para este cliente (" . $total_tarifas_cliente . ") en el mes de " . $date->copy()
              ->format('m') . " : <br>";
      if ($total_tarifas_cliente > 0) {
        foreach ($oldRatesUser as $oldRateUser) {
          echo $oldRateUser->rate->name . " ";
          if ($oldRateUser->rate->type != 4 || !preg_match('/BONO/i', $oldRateUser->rate->name)) {
            if ($oldRateUser->rate->mode <= 1) {
              $actualDate = $date->copy()->addMonth();
              $isRateExistNow = CustomersRates::where('customer_id', $customer->id)
                      ->where('rate_id', $oldRateUser->rate_id)
                      ->whereMonth('created_at', '=', $actualDate->copy()
                              ->format('m'))
                      ->whereYear('created_at', '=', $actualDate->copy()
                              ->format('Y'))
                      ->get();

              if (count($isRateExistNow) == 0) {
                $newRateUser = new CustomersRates();
                $newRateUser->customer_id = $oldRateUser->customer_id;
                $newRateUser->rate_id = $oldRateUser->rate_id;
                $newRateUser->created_at = $actualDate->copy()->startOfMonth();
                $newRateUser->updated_at = $actualDate->copy()->startOfMonth();
                $newRateUser->save(['timestamps' => false]);

                echo "Duplicada para el mes de " . $actualDate->copy()->format('m') . "!!<br>";
              } else {
                echo "Ya existe la tarifa<br>";
              }
            } else {
              echo "ya pago, la tarifa No es mensual";
            }
          } else {
            echo "La tarifa no se cobra de forma mensual";
          }

          echo "<br>";
        }
      }

      echo "<br><br><br>";
    }
  }

  public function informRate(Request $request) {
    //_informRateUser
    $rate = \App\Models\Rates::find($request->idRate);
    $CustomersRates = CustomersRates::where('customer_id', $request->idUser)->where('rate_id', $request->idRate)
                    ->orderBy('created_at', 'desc')->get();
    if ($rate->type == 4) {

      if (count($CustomersRates) > 0) {
        $customerRateCreated = $CustomersRates[0]->created_at;
      }

      $dateCreatedUserRate = Carbon::createFromFormat('Y-m-d H:i:s', $customerRateCreated);
      $classes = \App\Assistance::where('customer_id', $request->idUser)
              ->where('date_assistance', ">", $dateCreatedUserRate->copy()
                      ->format('Y-m-d H:i:s'))
              ->get();
    } else {
      $classes = \App\Assistance::where('customer_id', $request->idUser)
              ->whereYear('date_assistance', "=", date('Y'))
              ->whereMonth('date_assistance', "=", date('m'))
              ->get();
    }

    return view('/admin/usuarios/_informRateUser', [
        'classes' => $classes,
        'rate' => $rate,
        'CustomersRates' => $CustomersRates,
    ]);
  }

  public static function getPendingPaymentByMonth($date) {
    $pendiente = 0;
    $customers = User::where('role', 'user')->where('status', 1)->get();
    $month = date('m', strtotime($date));
    $year = date('Y', strtotime($date));

    $ratesLst = \App\Models\Rates::all()->pluck('price', 'id')->toArray();
    $customers = User::where('role', 'user')->where('status', 1)
            ->join('users_rates', 'users.id', '=', 'users_rates.customer_id')
            ->whereYear('users_rates.created_at', '=', $year)
            ->whereMonth('users_rates.created_at', '=', $month)
            ->get();

    foreach ($customers as $customer) {
      if (isset($ratesLst[$customer->rate_id])) {
        $cobro = \App\Charges::where('customer_id', $customer->id)
                ->where('rate_id', $customer->rate_id)
                ->whereYear('date_payment', '=', $year)
                ->whereMonth('date_payment', '=', $month)
                ->count();

        if ($cobro == 0) {
          $pendiente += $ratesLst[$customer->rate_id];
        }
      }
    }

    return $pendiente . "€";
  }

  function sendConsent(Request $request){
    $uID = $request->input('customer_id',null);
    $type = $request->input('type',null);
    if (!$uID){
      return response()->json(['error','usuario no encontrado']);
    }
    $oUser = User::find($uID);
    if (!$oUser){
      return response()->json(['error','usuario no encontrado']);
    }
    
    $email = $oUser->email;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
      return response()->json(['error',$email.' no es un mail válido']);
    }
    
    $doc = $tit = $subject = $link = '' ;
    $code = 0;
    switch ($type){
      case 'fisioIndiba':
        $tit = 'CONSENTIMIENTO FISIOTERAPIA CON INDIBA';
        $doc = 'CONSENTIMIENTO-FISIOTERAPIA-CON-INDIBA.pdf';
        $code = 1001;
        $link = URL::to('/firmar-consentimiento/').'/';
        $subject = 'Firma de consentimiento';
        break;
      case 'sueloPelvico':
        $tit = 'CONSENTIMIENTO SUELO PELVICO';
        $doc = 'CONSENTIMIENTO-SUELO-PELVICO.pdf';
        $code = 2002;
        $link = URL::to('/firmar-consentimiento/').'/';
        $subject = 'Firma de consentimiento';
        break;
      case 'contrato':
        
        $uPlan = $oUser->getPlan();
        if ($uPlan){
          if ($uPlan == 'fidelity'){
              $tit = 'CONTRATO PLAN FIDELITY';
              $subject = 'Firma de contrato: PLAN FIDELITY';
          }
          if ($uPlan == 'basic'){
              $tit = 'CONTRATO PLAN BÁSICO';
              $subject = 'Firma de contrato: PLAN NORMAL';
          }
        }
        $code = 3003;
        $link = URL::to('/firmar-contrato/').'/';
        
        break;
      default:
        $tit = '';
        $doc = '';
        break;
    }
    
   
    $link .= \App\Services\LinksService::getLink([$uID,$code,time()]);
    
    
    $sended = Mail::send('emails._sign-consent', ['user' => $oUser,'tit'=>$tit,'link'=>$link], function ($message) use ($subject,$email) {
          $message->subject($subject);
          $message->from(config('mail.from.address'), config('mail.from.name'));
          $message->to($email);
        });
    return response()->json(['OK','Email enviado']);
  }
}
