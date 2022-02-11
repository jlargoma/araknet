<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Carbon\Carbon;
use App\Models\UsersLiquidation;
use App\Models\User;
use App\Models\UsersRates;
use App\Models\Dates;
use Barryvdh\DomPDF\Facade as PDF;

class UsersLiquidationController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    
    public function liquidation(Request $request)
    {
      $liquidation = null;
      if ( !empty($request->id_liquidation) ) {
        $liquidation = UsersLiquidation::find($request->id_liquidation);
      }
      if (!$liquidation){
        $liquidation = new UsersLiquidation();
        $liquidation->user_id = $request->user_id;
        $liquidation->total = $request->importe;
        $liquidation->date_liquidation = Carbon::createFromFormat('Y-m-d', $request->date_liquidation)->copy()->format('Y-m-d');
      }
      
      if ($request->importe == "") {
        $liquidation->total = 0;
      } else {
        $liquidation->total = $request->importe;
      }

      if ($liquidation->save())	return 'OK';
    	else return "Error al guardar, intentelo de nuevo más tarde!";
    }
    
    
    public function store(Request $request)
    {
        $user_id = $request->user_id;
        $importe  = $request->importe;
        $type     = $request->type;
        $date     = $request->date.'-01';
        
        $oLiq = UsersLiquidation::where('user_id',$user_id)
                ->where('date_liquidation',$date)->first();
        if (!$oLiq){
            $oLiq = new UsersLiquidation();
            $oLiq->user_id = $user_id;
            $oLiq->date_liquidation = $date;
        }
        if ($type == 'liq') $oLiq->salary = intval($importe);
        if ($type == 'comm') $oLiq->commision = intval($importe);
        if ($oLiq->save()) return 'OK';
        
        return "Error al guardar, intentelo de nuevo más tarde!";
    }
    
      
    
    public function userLiquidacion($id, $date = null){
      
      if (!$date) $date = date('Y-m');
       $aux = explode('-', $date);
       if (count($aux) == 2){
           $year  = $aux[0];
           $month = $aux[1];
       } else {
           $year = getYearActive();
           $month = date('m');
       }
      $sUserLiq = new \App\Services\UsersLiqService();
      $aLiq = $sUserLiq->liquMensual($id,$year,$month);
      return view('/admin/usuarios/liquidacion',[ 
              'pagosClase' => $aLiq['pagosClase'],
              'totalClase' => $aLiq['totalClase'],
              'nExtr' => $aLiq['nExtr'],
              'totalExtr' => $aLiq['totalExtr'],
              'salary' => $aLiq['salary'],
              'classLst' => $aLiq['classLst'],
              ]);
       
            
    }
    public function payments($id){
        
        $year = getYearActive();
        $month = date('m');
        /**********************************************************/
        $lstMonts = lstMonthsSpanish();
        $aMonths  = [];
        $emptyMonth = [];
        foreach ($lstMonts as $k=>$v){
            if ($k>0){
              $key = $year.'-'.str_pad($k, 2, "0", STR_PAD_LEFT);
              $aMonths[$key] = $v;
              $emptyMonth[$key] = 0;
            }
        }
        
        /**********************************************************/
        
        $liqLst =  [];
        $CommLst = [];
        $CommLstCalc = $emptyMonth;
        $oLiq = UsersLiquidation::where('user_id',$id)
                    ->whereYear('date_liquidation' ,'=', $year)
                    ->get();
        if ($oLiq){
            foreach ($oLiq as $item){
              $aux = substr($item->date_liquidation,0,7);
              $liqLst[$aux]=$item->salary;
              $CommLst[$aux]=$item->commision;
            }
        }
         $liqLst[0] = array_sum($liqLst);
         $CommLst[0] = array_sum($CommLst);
        /**********************************************************/
        $payMonth = [0=>0];
        $oExpenses = \App\Models\Expenses::where('to_user',$id)
                ->whereYear('date','=', $year)
                ->orderBy('date')
                ->get();
        $lstExpType = \App\Models\Expenses::getTypes();
        if ($oExpenses){
            foreach ($oExpenses as $item) {
              $aux = substr($item->date,0,7);
              if (!isset($payMonth[$aux])) $payMonth[$aux] = 0;
              $payMonth[$aux]+= $item->import;
              $payMonth[0]+= $item->import;
            }
        }
        //-----------------------------------------------------//
        //---- BEGIN liquidación mensual    -------------------//
        $aLiq = null;
        $liqByM = [];
        $sUserLiq = new \App\Services\UsersLiqService();
        $now = date('m');
        foreach ($aMonths as $k=>$v){
            $am = substr($k, 5,2);
            if ($am>$now){
              $liqByM[$k] = 0;
            } else {
              $aux = $sUserLiq->liquMensual($id,$year,$am);
              $CommLstCalc[$k] = array_sum($aux['totalClase']);
            }
        }
        //---- END liquidación mensual    ---------------------//
        //-----------------------------------------------------//
        

        return view('/admin/usuarios/payments',[ 
                                                'user' => User::find($id),
                                                'payMonth' => $payMonth,
                                                'aMonths'=>$aMonths,
                                                'liqLst' => $liqLst,
                                                'CommLst' => $CommLst,
                                                'CommLstCalc' => $CommLstCalc,
            'year'=>$year
                                                ]);


    }
    
     public function enviarEmailLiquidacion($id, $date = null){
        
                 
        if (!$date) $date = date('Y-m');
        $aux = explode('-', $date);
        if (count($aux) == 2){
            $year  = $aux[0];
            $month = $aux[1];
        } else {
            $year = getYearActive();
            $month = date('m');
        }
        $sUserLiq = new \App\Services\UsersLiqService();
        $aData = $sUserLiq->liquMensual($id,$year,$month);
        $oUser = User::find($id);
        $aData['user'] = $oUser;
        $aData['mes'] = getMonthSpanish($month,false).' '.$year;
//        $view =  \View::make('pdfs.liquidacion', $aData)->render();
//        echo $view;die;

        $fileName = str_replace(' ','-','liquidacion '.$aData['mes'].' '. strtoupper($oUser->name));
        $routePdf = storage_path('/app/liquidaciones/'. urlencode($fileName).'.pdf');
        $pdf = PDF::loadView('pdfs.liquidacion', $aData);
        $pdf->save($routePdf);
        
//        return $pdf->download('invoice.pdf');

//
//        return view('emails._liquidacion_coach',['user' => $oUser,'mes'=>$aData['mes']]);
        $emailing = $oUser->email;
        try{
        \Mail::send(['html' => 'emails._user_liquidacion'],['user' => $oUser,'mes'=>$aData['mes']], function ($message) use ($emailing, $fileName,$routePdf)  {
                setlocale(LC_TIME, "ES");
                setlocale(LC_TIME, "es_ES");
                $message->subject($fileName);
                $message->from(config('mail.from.address'), config('mail.from.name'));
                $message->to($emailing);
                $message->attach($routePdf);
            });
        } catch (\Exception $ex) {
          dd($ex);
        }
            
        return 'OK';

            
    }
}
