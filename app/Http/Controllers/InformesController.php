<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use DB;
use \App\Models\User;
use App\Models\Customers;
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
    private function getCharges($year,$month,$day,$search=null,$type_payment=null,$rate=null,$f_user=null) {
        $sql_charges = Charges::where('import', '!=', 0);
        if ($search){
            $search = trim($search);
            $cliIDs = User::where('name', 'LIKE', "%" . $search . "%")->pluck('id');
            $sql_charges->whereIn('customer_id',$cliIDs);
        }
        
        if ($day == "all") {
            $starDate = "$year-$month-01";
            $endDate = date("Y-m-t", strtotime($starDate));
            $sql_charges->where('date_payment', '>=', $starDate)->where('date_payment', '<=', $endDate);
        } else {
            $starDate = "$year-$month-$day";
            $sql_charges->where('date_payment', '=', $starDate);
        }

        if ($type_payment && $type_payment != 'all'){
          $sql_charges->where('type_payment', $type_payment);
        }
        
        if ($rate){
          if ($rate != 'all'){
            $filerRate = explode('-', $rate);
            if (count($filerRate) == 2){
              $sql_charges->where('rate_id', $filerRate[1]);
            }
            else{
              $sql_charges->where('type_rate', $filerRate[0]);
            }
          }
        }
        //------------------------------------------------------------//
        if($f_user){
          $uR_IDs = \App\Models\CustomersRates::where('user_id',$f_user)->pluck('charge_id');
          $sql_charges->whereIn('id', $uR_IDs);
        }
        //------------------------------------------------------------//
        $charges = $sql_charges->orderBy('date_payment')->get();
        $usersService = new \App\Services\UsersService();
        $aCargesusers = $usersService->getusersCharge($sql_charges->pluck('id'));
        //------------------------------------------------------------//

        $bank = 0;
        $cash = 0;
        $card = 0;
        $clients = [];
        $rates = $bonos = [];
        foreach ($charges as $charge) {
            $clients[] = $charge->customer_id;
            if ($charge->rate_id>0)  $rates[] = $charge->rate_id;
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

        $endDay =  date("t", strtotime($starDate));
        $aUsers =  User::whereIn('id', $clients)->get()
                        ->pluck('name', 'id')->toArray();
        $aRates =  \App\Models\Rates::whereIn('id', $rates)->get()
                        ->pluck('name', 'id')->toArray();
        
        return [
            'charges' => $charges,
            'cash' => $cash,
            'aCargesusers' => $aCargesusers,
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
      
      $sqlURates = \App\Models\CustomersRates::where('charge_id', '>', 0)
              ->where('rate_month',$month)->where('rate_year',$year);
      if ($search){
          $search = trim($search);
          $cliIDs = User::where('name', 'LIKE', "%" . $search . "%")->pluck('id');
          $sqlURates->whereIn('customer_id',$cliIDs);
      }
      $uRates = $sqlURates->orderBy('created_at')->get();
      $bank = 0;
      $cash = 0;
      $card = 0;
      $clients = [];
      $rates = [];
      $charges = [];
      foreach ($uRates as $item) {
          $clients[] = $item->customer_id;
          $rates[] = $item->rate_id;
          
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
    
    
    public function informeClienteMes(Request $request,$month=null,$day=null,$f_rate=null,$f_method=null,$f_user=null) {

        $year = getYearActive();
        if (!$month)
            $month = date('m');
        if (!$day)
            $day = 'all';

        $data = $this->getCharges($year,$month,$day,null,$f_method,$f_rate,$f_user);
        $lstMonthsSpanish = lstMonthsSpanish();
        unset($lstMonthsSpanish[0]);
        $data['months'] =  $lstMonthsSpanish;
        
        $chargesIDs = [];
        foreach ($data['charges'] as $c){
          $chargesIDs[] = $c->id;
        }
        $data['aURates']= \App\Models\CustomersRates::whereIn('charge_id', $chargesIDs)
              ->pluck('rate_month','charge_id')->toArray();
        
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
        $data['f_user']= $f_user;
        $data['aTRates']= \App\Models\Rates::getRatesTypeRates();
        $data['ausers']= User::getusers()->pluck('name','id');
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
          if ($c->rate_id>0){
            
            if (!isset($byRate[$c->rate_id]))
                $byRate[$c->rate_id] = 0;
            
            $byRate[$c->rate_id] += $c->import;
            if (isset($aRrt[$c->rate_id])){
              $byRateT[$aRrt[$c->rate_id]]['t'] += $c->import;
              $byRateT[$aRrt[$c->rate_id]][$c->type_payment] += $c->import;
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
        $tusers = [];
        $uRates = \App\Models\CustomersRates::select(
                'customers_rates.*','charges.type_payment',
                'charges.import','charges.discount')
                ->where('rate_year',$year)
                ->where('rate_month',$month)
                ->join('charges','charge_id','=','charges.id')->get();
        
        if ($uRates){
          foreach ($uRates as $uR){
            if (!isset($uResult[$uR->customer_id])) $uResult[$uR->customer_id] = [];

            $uResult[$uR->customer_id][] = [
                $uR->rate_id,
                $uR->user_id,
                $uR->type_payment,
                $uR->import,
                $uR->discount,
                isset($aRrt[$uR->rate_id]) ? $aRrt[$uR->rate_id] : null
            ];

            if (!isset($tusers[$uR->user_id])) $tusers[$uR->user_id] = 0;
            
            $tusers[$uR->user_id] += $uR->import;
          }
        }
        
        
        

        $aCustomers = Customers::whereIn('id',array_keys($uResult))
                ->pluck('name','id')->toArray();
        $ausers = User::whereIn('id', array_keys($tusers))
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
        $data['ausers'] =  $ausers;
        $data['tusers'] =  $tusers;
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
        $data['aURates']= \App\Models\CustomersRates::whereIn('charge_id', $chargesIDs)
              ->pluck('rate_month','charge_id')->toArray();

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
