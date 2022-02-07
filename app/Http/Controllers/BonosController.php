<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Carbon\Carbon;
use Stripe;
use App\Models\Bonos;
use App\Models\UserBonos;

class BonosController extends Controller {

  public function index() {
    return view('/admin/bonos/index', [
        'objs' => Bonos::where('status', 1)->orderBy('name', 'asc')->get(),
        'old' => Bonos::where('status', 0)->orderBy('name', 'asc')->get(),
        'rateFilter'=> \App\Models\TypesRate::getWithsubfamily()
    ]);
  }
  
  public function create(Request $request) {
    $oObj = new Bonos();

    $oObj->name = $request->input('name');
    $oObj->qty = $request->input('qty');
    $oObj->price = $request->input('price');
//    $oObj->value = $request->input('value');
    $oObj->status = 1;
    $rate = $request->input('rate');
    if ($rate){
      $oObj->rate_id = null;
      $oObj->rate_type = null;
      if ($rate != 'all'){
        $filerRate = explode('-', $rate);
        if (count($filerRate) == 2) $oObj->rate_subf = $filerRate[1];
        else $oObj->rate_type = $filerRate[0];
      }
    }
    $oObj->save();
    return redirect()->back()->with(['success'=>'Bono agregado']);
  }

  public function update(Request $request) {

    $id = $request->input('id');
    $oObj = Bonos::find($id);
    $oObj->name = $request->input('name');
    $oObj->qty = $request->input('qty');
//    $oObj->value = $request->input('value');
    $oObj->price = $request->input('price');
    $rate = $request->input('rate');
    
    if ($rate){
      $oObj->rate_id = null;
      $oObj->rate_type = null;
      if ($rate != 'all'){
        $filerRate = explode('-', $rate);
        if (count($filerRate) == 2){
          $oObj->rate_subf = $filerRate[1];
          $oObj->rate_type = null;
        }
        else{
          $oObj->rate_subf = null;
          $oObj->rate_type = $filerRate[0];
        }
      }
    }
    
    if ($oObj->save()) {
      die('OK');
    }
    die('error');
  }

  public function upd_fidelity(Request $request) {
    $id = $request->input('id');
    $oObj = Bonos::find($id);
    $oObj->tarifa = $request->input('val');
    if ($oObj->save()) return 'OK';
    return 'ERROR';
  }
  
  public function delete($id) {
    $oObj = Bonos::find($id);
    $oObj->status = 0;
    if ($oObj->save()) {
        return redirect()->back()->with(['success'=>'Bono eliminado']);
    }
  }
  
  ///////////////////////////////////////////////////////////////////////////
  
  public function purcharse(Request $req) {
    $uID = $req->input('id_user', 0);
    $bID = $req->input('id_bono', 0);
    $price = $req->input("price_$bID", 0);
   
    $oUser = \App\Models\User::find($uID);
    if (!$oUser || $oUser->id != $uID)
      return redirect()->back()->withErrors(['Usuario no encontrado']);
    
    $oBono = Bonos::find($bID);
    if (!$oBono || $oBono->id != $bID)
      return redirect()->back()->withErrors(['Bono no encontrado']);
    
     
    $tpay = $req->input('type_payment','cash');
//    $value = $oBono->price;
    $idStripe=null;$cStripe=null;
    if ($tpay == 'card'){
      $oUser = \App\Models\User::find($uID);
      $sStripe = new \App\Services\StripeService();
      //--- NUEVA TARJETA ---------------------------------------//
      if ($req->input('cardLoaded') == 0){
        $CardService = new \App\Services\CardService();
        $resp = $CardService->processCard($oUser, $req);
        if ($resp !== 'OK')  return back()->withErrors($resp)->withInput();
      }
      /***********************************/
      /** COBRAR POR STRIPE **/
      /***********************************/
      $resp = $sStripe->automaticCharge($oUser,round($price*100));
      if ( $resp[0] != 'OK'){
         if ( $resp[0] == '3DS'){
           \App\Models\Stripe3DS::addNew($oUser->id,$resp[1],$resp[2],'asignBono',['bono'=>$oBono->id,'tpay'=>$tpay]);
          
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
    }
    
    $oServ = new \App\Services\BonoService();
    $resp = $oServ->asignBono($oUser,$oBono,$tpay,$idStripe,$cStripe,$price);

    if ($resp[0] == 'error') {
        return redirect()->back()->withErrors([$resp[1]]);
    }
    
    $type = $req->input('type', 0);
    $id_back = $req->input('id_back', 0);
    if ($id_back>0){
        switch ($type){
            case 'nutri':
               return redirect('/admin/citas/edit/'.$id_back)->with('success', $resp[1]);
            case 'fisio':
               return redirect('/admin/citas-fisioterapia/edit/'.$id_back)->with('success', $resp[1]);
//            case 'ficha':
//               return redirect('/admin/usuarios/informe/'.$id_back.'#bono')->with('success', $resp[1]);
            case 'pt':
                die('error');
        }
    }
    return redirect()->back()->with('success', $resp[1]);
  }
  
  /**
   * 
   * /admin/bonos/comprar/3000
   * @param type $uId
   * @return type
   */
  public function show_purcharse($uId,$t,$id) {
    
    $oUser = \App\Models\User::find($uId);
    if ($oUser && $oUser->id == $uId){
      $uPlan = $oUser->getPlan();
      //--------------------------------//
      //--  BEGIN: STRIPE    ----------//
      $pStripe = null;
      $card = null;
      $paymentMethod = $oUser->getPayCard();
      if ($paymentMethod) {
        $aux = $paymentMethod->toArray();
        $card['brand'] = $aux['card']['brand'];
        $card['exp_month'] = $aux['card']['exp_month'];
        $card['exp_year'] = $aux['card']['exp_year'];
        $card['last4'] = $aux['card']['last4'];
      }
      //--  END: STRIPE       ----------//
      //--------------------------------//
    
      return view('admin.usuarios.clientes.bono', [
        'user'=>$oUser,
        'card'=>$card,
        'oBonos' => Bonos::where('status', 1)->orderBy('name', 'asc')->get(),
        'type'=>$t,
        'id_back'=>$id,
        'typesRate'=> \App\Models\TypesRate::pluck('name','id')->toArray(),
        'rate_subf'=> \App\Models\TypesRate::subfamily(),
        'uPlan'=>$uPlan
    ]);
      
    }
    die('usuario no encontrado');
  }
  
  function printBonologs($id){
    $logs = \App\Models\UserBonosLogs::getLst($id);
    if ($logs){
      include_once app_path().'/Blocks/bonosLogs.php';
    }
  }
  
  function updBonologs(Request $req) {
    
    $motiv = $req->input('motiv', null);
    $qty   = $req->input('qty', null);
    $id    = $req->input('id', null);
    
    if (trim($motiv) == '')
      return back()->withErrors(['El motivo es requerido']);
    
    $qty = intval($qty);
    $oUsrBono = UserBonos::find($id);
    if ($oUsrBono){
      $oUsrBono->qty = $qty;
      $oUsrBono->save();
      
      $obj = new \App\Models\UserBonosLogs();
      $obj->user_bonos_id = $id;
      $obj->charge_id = null;
      $obj->decr = null;
      $obj->total = $qty;
      $obj->text = $motiv;
      $obj->save();
      return back()->with(['success'=>'Bonos actualizados']);
    }
    
    return back()->withErrors(['Bono no encontrado']);
  }
  
  //------------------------------------------------------------------------//
  //----  INFORMES                  ---------------------------------------//
  //----------------------------------------------------------------------//
  function getByUsers(Request $req) {
    $filter = $req->input('f', null);
    if ($filter){
      if (is_numeric($filter)) $oUsrBonos = UserBonos::where('rate_id',$filter)->get();
      else    $oUsrBonos = UserBonos::where('rate_subf',$filter)->get();
      
    } else $oUsrBonos = UserBonos::all();
    
    $rate_subf = \App\Models\TypesRate::subfamily();
    $aUB = [];
    $totals = ['i'=>0,'d'=>0,'t'=>0,'p'=>0];
    foreach ($oUsrBonos as $ub){
      if (!isset($aUB[$ub->user_id]))
        $aUB[$ub->user_id] = [];
      
      $t = $ub->rate_id ? $ub->rate_id : $ub->rate_subf;
      $data = ['type'=>$t,'i'=>0,'d'=>0,'t'=>$ub->qty,'p'=>0];
      $lst = $ub->logs()->orderBy('created_at')->get();
      foreach ($lst as $l){
        if ($l->incr) $data['i'] += $l->incr;
        if ($l->decr) $data['d'] += $l->decr;
        $data['p'] += $l->price;
      }
      
      $totals['i'] += $data['i'];
      $totals['d'] += $data['d'];
      $totals['t'] += $data['t'];
      $totals['p'] += $data['p'];
      
      $aUB[$ub->user_id][$ub->id] = $data;
    }
    
    
    $aUsers = \App\Models\User::whereIN('id',array_keys($aUB))->pluck('name','id')->toArray();
    $aRates = \App\Models\Rates::all()->pluck('name','id')->toArray();
    $rateFilter = \App\Models\TypesRate::getWithsubfamily();
    return view('admin.contabilidad.bonos.by_customer', compact('aUB','aUsers','aRates','rate_subf','rateFilter','filter','totals'));
  }
  
  function sharedBono_save(Request $req) {
    
    $oUserTo = \App\Models\User::find($req->input('uID'));
    $oUserFrom = \App\Models\User::find($req->input('id_userBono'));
    $oUserBonos = \App\Models\UserBonos::find($req->input('id_bono'));
    
    $errors = [];
    if (!$oUserFrom) $errors[] = 'Usuario no encontrado';
    if (!$oUserTo) $errors[] = 'Usuario no encontrado';
    if (!$oUserBonos) $errors[] = 'Bono no encontrado';
    
    if (count($errors)>0)
    return redirect()->back()->withErrors($errors);
    
    
    
    
    $sBono = new \App\Services\BonoService();
    $resp = $sBono->bonoCompartido($oUserFrom, $oUserTo, $oUserBonos,1);
    if ($resp == 'OK') return redirect()->back()->with(['success'=>'Bono compartido.']);
    
    return redirect()->back()->withErrors ([$resp]);
  }

  
  function sharedBono($uID,$serv){

    $users = \App\Models\User::where('status', 1)->where('role', 'user')
            ->orderBy('name')->get();
    
    return view('admin.bonos.sharedBono', compact('uID','serv','users'));
    $oBonos = UserBonos::where('user_id',$uID)->get();
    dd($oBonos);
  }
  
  function sharedBono_getlst($uID,$serv){

    $oUser = \App\Models\User::find($uID);
    $oBonoLst = $oUser->bonosServ($serv);
    $tBonos = $oBonoLst[0];
    $oBonoLst = $oBonoLst[1];
    
     if (count($oBonoLst)>0){
        foreach ($oBonoLst as $b){
          ?>
            <div class="checkBono" >
              <input type="radio" name="id_bono" value="<?php echo $b[0]; ?>" class="form-control"/>
              <label><?php echo $b[1]; ?> (<?php echo $b[2]; ?>)</label>
            </div>
          <?php
        }
      } else {
        ?>
        <p class="alert alert-warning">No tiene bonos asignados</p>
        <?php
      }
          
  
  }
}
