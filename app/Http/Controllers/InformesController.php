<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use DB;
use \App\Models\User;
use App\Models\Charges;

class InformesController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * 
     * @param type $year
     * @param type $month
     * @param type $day
     * @param type $search
     * @param type $type_payment
     * @return type
     */
    private function getCharges($year,$month,$day,$search=null,$type_payment=null,$rate=null,$f_coach=null) {
        $sql_charges = Charges::where('import', '!=', 0);
        $sql_CashBox = \App\Models\CashBox::where('import', '!=', 0)
                ->where('type', 'INGRESO');
        
        if ($search){
            $search = trim($search);
            $cliIDs = User::where('name', 'LIKE', "%" . $search . "%")->pluck('id');
            $sql_charges->whereIn('id_user',$cliIDs);
            $sql_CashBox->where('concept', 'LIKE', "%" . $search . "%");
        }
        
        if ($day == "all") {
            $starDate = "$year-$month-01";
            $endDate = date("Y-m-t", strtotime($starDate));
            $sql_charges->where('date_payment', '>=', $starDate)->where('date_payment', '<=', $endDate);
            $sql_CashBox->where('date', '>=', $starDate)->where('date', '<=', $endDate);
        } else {
            $starDate = "$year-$month-$day";
            $sql_charges->where('date_payment', '=', $starDate);
            $sql_CashBox->where('date', '=', $starDate);
        }

        if ($type_payment && $type_payment != 'all'){
          $sql_charges->where('type_payment', $type_payment);
        }
        
        if ($rate){
          if ($rate != 'all'){
            $filerRate = explode('-', $rate);
            if (count($filerRate) == 2){
              $sql_charges->where('id_rate', $filerRate[1]);
            }
            else{
              $sql_charges->where('type_rate', $filerRate[0]);
            }
          }
        }
        //------------------------------------------------------------//
        if($f_coach){
          $uR_IDs = \App\Models\UserRates::where('coach_id',$f_coach)->pluck('id_charges');
          $sql_charges->whereIn('id', $uR_IDs);
        }
        //------------------------------------------------------------//
        
        
        $charges = $sql_charges->orderBy('date_payment')->get();
        $extrasCharges = $sql_CashBox->orderBy('date')->get();
        //------------------------------------------------------------//
        $CoachsService = new \App\Services\CoachsService();
        $aCargesCoachs = $CoachsService->getCoachsCharge($sql_charges->pluck('id'));
        //------------------------------------------------------------//

        $bank = 0;
        $cash = 0;
        $card = 0;
        $clients = [];
        $rates = $bonos = [];
        foreach ($charges as $charge) {
            $clients[] = $charge->id_user;
            if ($charge->id_rate>0)  $rates[] = $charge->id_rate;
            if ($charge->bono_id>0)  $bonos[] = $charge->bono_id;
            switch ($charge->type_payment){
              case 'banco':
                $bank += $charge->import;
                break;
              case 'cash':
                $cash += $charge->import;
                break;
              case 'card':
                $card += $charge->import;
                break;
            }
        }

        /* EXTRAS OF CASH (VENDING... CURSOS... ) */

        if ($extrasCharges) {
            foreach ($extrasCharges as $index => $charge) {
                $cash += $charge->import;
            }
        }
        $endDay =  date("t", strtotime($starDate));
        $aUsers =  User::whereIn('id', $clients)->get()
                        ->pluck('name', 'id')->toArray();
        $aRates =  \App\Models\Rates::whereIn('id', $rates)->get()
                        ->pluck('name', 'id')->toArray();
        
        return [
            'charges' => $charges,
            'extrasCharges' => $extrasCharges,
            'cash' => $cash,
            'aCargesCoachs' => $aCargesCoachs,
            'card' => $card,
            'bank' => $bank,
            'clients' => $clients,
            'rates' => $rates,
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'endDay' => $endDay,
            'aUsers' => $aUsers,
            'aRates' => $aRates
                ];
                
                
    }
    
    public function getChargesRates($year,$month,$day,$search=null) {
      
      $sqlURates = \App\Models\UserRates::where('id_charges', '>', 0)
              ->where('rate_month',$month)->where('rate_year',$year);
      if ($search){
          $search = trim($search);
          $cliIDs = User::where('name', 'LIKE', "%" . $search . "%")->pluck('id');
          $sqlURates->whereIn('id_user',$cliIDs);
      }
      $uRates = $sqlURates->orderBy('created_at')->get();
      $bank = 0;
      $cash = 0;
      $card = 0;
      $clients = [];
      $rates = [];
      $charges = [];
      foreach ($uRates as $item) {
          $clients[] = $item->id_user;
          $rates[] = $item->id_rate;
          
          $charge = $item->charges;
          if ($charge){
            $charges[] = $charge;
            switch ($charge->type_payment){
              case 'banco':
                $bank += $charge->import;
                break;
              case 'cash':
                $cash += $charge->import;
                break;
              case 'card':
                $card += $charge->import;
                break;
            }
          }
            
      }

      $extrasCharges = [];
        $endDay =  date("t", strtotime("$year-$month-01"));
        $aUsers =  User::whereIn('id', $clients)->get()
                        ->pluck('name', 'id')->toArray();
        $aRates =  \App\Models\Rates::whereIn('id', $rates)->get()
                        ->pluck('name', 'id')->toArray();
        
        return [
            'charges' => $charges,
            'extrasCharges' => $extrasCharges,
            'cash' => $cash,
            'bank' => $bank,
            'card' => $card,
            'clients' => $clients,
            'rates' => $rates,
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'endDay' => $endDay,
            'aUsers' => $aUsers,
            'aRates' => $aRates,
                ];
                
                
    }
    
    
    public function informeClienteMes(Request $request,$month=null,$day=null,$f_rate=null,$f_method=null,$f_coach=null) {

        $year = getYearActive();
        if (!$month)
            $month = date('m');
        if (!$day)
            $day = 'all';

        $data = $this->getCharges($year,$month,$day,null,$f_method,$f_rate,$f_coach);
        $lstMonthsSpanish = lstMonthsSpanish();
        unset($lstMonthsSpanish[0]);
        $data['months'] =  $lstMonthsSpanish;
        
        $chargesIDs = [];
        foreach ($data['charges'] as $c){
          $chargesIDs[] = $c->id;
        }
        $data['aURates']= \App\Models\UserRates::whereIn('id_charges', $chargesIDs)
              ->pluck('rate_month','id_charges')->toArray();
        
        /*****************************************************************/
        $rateFilter = [];
        $oTypes = \App\Models\TypesRate::all();
        foreach ($oTypes as $item){
          $aux  = \App\Models\Rates::where('type',$item->id)->get();
          $aux2 = [];
          foreach ($aux as $a){
            $aux2[$a->id] = $a->name;
          }
          $rateFilter[$item->id] = ['n' => $item->name,'l'=>$aux2];
        }
        $data['rateFilter']= $rateFilter;
//        dd($rateFilter);
        $data['filt_rate']= $f_rate;
        $data['filt_method']= $f_method;
        /*****************************************************************/
        $data['f_coach']= $f_coach;
        $data['aTRates']= \App\Models\Rates::getRatesTypeRates();
        $data['aCoachs']= User::getCoachs()->pluck('name','id');
        return view('admin.informes.informeClientesMes',$data);
    }
    
    public function informeCuotaMes(Request $request, $month = null, $day = null) {

        $year = getYearActive();
        if (!$month)
            $month = date('m');
        if (!$day)
            $day = 'all';

        $data = $this->getChargesRates($year,$month,$day);
        $lstMonthsSpanish = lstMonthsSpanish();
        unset($lstMonthsSpanish[0]);
        $data['months'] =  $lstMonthsSpanish;
        
        $byRate = [];
        $byRateT = [];
        $aRType = \App\Models\TypesRate::all()->pluck('name','id')->toArray();
        //rate types
        $aRrt = \App\Models\Rates::all()->pluck('type','id')->toArray();
        foreach ($aRrt as $k=>$v){
          $byRateT[$v] = [
              't'=>0,
              'banco'=>0,
              'cash'=>0,
              'card'=>0,
              'bono'=>0,
          ];
        }
        
        foreach ($data['charges'] as $c){
          if ($c->id_rate>0){
            
            if (!isset($byRate[$c->id_rate]))
                $byRate[$c->id_rate] = 0;
            
            $byRate[$c->id_rate] += $c->import;
            if (isset($aRrt[$c->id_rate])){
              $byRateT[$aRrt[$c->id_rate]]['t'] += $c->import;
              $byRateT[$aRrt[$c->id_rate]][$c->type_payment] += $c->import;
            }
          }
        }
        
        $data['byRate'] =  $byRate;
        $data['byTypeRate'] =  $byRateT;
        $data['aRType'] =  $aRType;
        return view('admin.informes.informeCuotaMes',$data);
    }
    
    public function informeCobrosMes(Request $request, $month = null, $day = null) {

        $year = getYearActive();
        if (!$month)
            $month = date('m');
        
        
        $aRType = \App\Models\TypesRate::all()->pluck('name','id')->toArray();
        //rate types
        $aRrt = \App\Models\Rates::all()->pluck('type','id')->toArray();
        $aRname = \App\Models\Rates::all()->pluck('name','id')->toArray();
        
        
        $uResult = [];
        $tCoachs = [];
        $uRates = \App\Models\UserRates::select(
                'users_rates.*','charges.type_payment',
                'charges.import','charges.discount')
                ->where('rate_year',$year)
                ->where('rate_month',$month)
                ->join('charges','id_charges','=','charges.id')->get();
        
        if ($uRates){
          foreach ($uRates as $uR){
            if (!isset($uResult[$uR->id_user])) $uResult[$uR->id_user] = [];

            $uResult[$uR->id_user][] = [
                $uR->id_rate,
                $uR->coach_id,
                $uR->type_payment,
                $uR->import,
                $uR->discount,
                isset($aRrt[$uR->id_rate]) ? $aRrt[$uR->id_rate] : null
            ];

            if (!isset($tCoachs[$uR->coach_id])) $tCoachs[$uR->coach_id] = 0;
            
            $tCoachs[$uR->coach_id] += $uR->import;
          }
        }
        
        
        

        $aCustomers = User::whereIn('id',array_keys($uResult))
                ->pluck('name','id')->toArray();
        $aCoachs = User::whereIn('id', array_keys($tCoachs))
                ->pluck('name','id')->toArray();
       
                
        $lstMonthsSpanish = lstMonthsSpanish();
        unset($lstMonthsSpanish[0]);
        $data['months'] =  $lstMonthsSpanish;
        
        $data['year']    =  $year;
        $data['month']   =  $month;
        $data['aRname']  =  $aRname;
        $data['aRType']  =  $aRType;
        $data['uResult'] =  $uResult;
        $data['aCust']   =  $aCustomers;
        $data['aCoachs'] =  $aCoachs;
        $data['tCoachs'] =  $tCoachs;
        return view('admin.informes.informeCobrosMes',$data);
    }
    
    
    public function searchClientInform(Request $request,  $month = null) {
        $year = getYearActive();
        if (!$month)  $month = date('m');
        
        $search = trim($request->input('search',''));
        $data = $this->getChargesRates($year,$month,'all',$search);
        return view('admin/informes/_table_informes', $data);
    }

    public function informeCierreDiario(Request $request,$month = null, $day = null) {
        
        
        $year = getYearActive();
        if (!$month)
            $month = date('m');
        if (!$day)
            $day = 'all';

        $data = $this->getCharges($year,$month,$day);
        $lstMonthsSpanish = lstMonthsSpanish();
        unset($lstMonthsSpanish[0]);
        $months =  $lstMonthsSpanish;
        
        $totalBank = $totalCash = 0;
        $arrayDays=[];
        if ($day == 'all'){
            for($i=1;$i<=$data['endDay'];$i++)
                $arrayDays[$i]=['cash'=>0,'bank'=>0];
        } else $arrayDays[$day]=['cash'=>0,'bank'=>0];
        
        foreach ($data['charges'] as $i){
            $j = date('j', strtotime($i->date_payment));
            if ($i->type_payment == 'cash'){
                $arrayDays[$j]['cash'] += $i->import;
                $totalCash += $i->import;
            } else {
                $arrayDays[$j]['bank'] += $i->import;
                $totalBank += $i->import;
            }
        }
        foreach ($data['extrasCharges'] as $i){
            $j = date('j', strtotime($i->date));
            $arrayDays[$j]['cash'] += $i->import;
            $totalCash += $i->import;
        }
        
        $auxTime = $year.'-'.str_pad($month, 2, "0", STR_PAD_LEFT).'-';
        if ($day == 'all'){
            $auxTime .= '01';
            $yesterday = date('n/j',strtotime($auxTime .' -1 days'));
            $tomorrow = date('n/j',strtotime($auxTime));
        } else {
            $auxTime .= str_pad($day, 2, "0", STR_PAD_LEFT);
            $yesterday = date('n/j',strtotime($auxTime .' -1 days'));
            $tomorrow = date('n/j',strtotime($auxTime .' +1 days'));
        }
            
        return view('.admin.informes.cierresDiarios', [
            'arrayDays' => $arrayDays,
            'totalBank' => $totalBank,
            'totalCash' => $totalCash,
            'month' => $month,
            'months' => $months,
            'year' => $year,
            'day' => $day,
            'clients' => $data['clients'],
            'endDay' => $data['endDay'],
            'total' => $totalBank+$totalCash,
            'yesterday'=>$yesterday,
            'tomorrow'=>$tomorrow,
        ]);
    }

    public function informeCaja(Request $request) {
        
        $date = date('Y-m-d');
        $charges = Charges::where('import', '!=', 0)
                ->where('date_payment', '=', $date)
                ->where('type_payment', 'cash')
                ->sum('import');
        $oCashBox = \App\Models\CashBox::where('import', '!=', 0)
                ->where('date', '=', $date)->get();
        $total = $charges;
        foreach ($oCashBox as $item) {
            if ($item->typePayment == "INGRESO") {
                $total += $item->import;
            } else{
                $total -= $item->import;
            }
        }

      
        return view('.admin.informes.caja', [
            'totalCash' => $total,
            'date' => $date
        ]);
    }

    public function informeCajaMes(Request $request, $month = null, $day = null) {

        $year = getYearActive();
        if (!$month)
            $month = date('m');
        if (!$day)
            $day = 'all';

        $data = $this->getCharges($year,$month,$day,null,'cash');
        $lstMonthsSpanish = lstMonthsSpanish();
        unset($lstMonthsSpanish[0]);
        $data['months'] =  $lstMonthsSpanish;
        
        $chargesIDs = [];
        foreach ($data['charges'] as $c){
          $chargesIDs[] = $c->id;
        }
        $data['aURates']= \App\Models\UserRates::whereIn('id_charges', $chargesIDs)
              ->pluck('rate_month','id_charges')->toArray();

        /***************************************************************/
        $date = date('Y-m-d');
        $data['totalCash'] = Charges::where('import', '!=', 0)
                ->where('date_payment', '=', $date)
                ->where('type_payment', 'cash')
                ->sum('import');
        $data['date'] = $date;
        /***************************************************************/
        return view('admin.informes.informeCajaMes',$data);
    }
  
    

}
