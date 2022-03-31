<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use DB;
use \App\Models\User;
use App\Models\Customers;
use App\Models\CustomersHnts;

class InformesController extends Controller {

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    //
  }

  public function informeClienteMes(Request $request, $month = null, $f_user = null) {

    $year = getYearActive();
    if (!$month)
      $month = date('m');

    $firsDay = $year . '-' . str_pad($month, 2, "0", STR_PAD_LEFT) . '-01';
    $lastDay = date("Y-m-t", strtotime($firsDay));
    if ($lastDay > date('Y-m-d'))
      $lastDay = date('Y-m-d',strtotime('-1 day'));
    $arrayDays = arrayDays($firsDay, $lastDay, 'Y-m-d', 0);
    $days = [];
    foreach ($arrayDays as $d => $v) {
      $days[] = substr($d, 8, 2);
    }
    $sql = CustomersHnts::select('customers_hnts.*')->whereYear('date', '=', $year)
            ->whereMonth('date', '=', $month);

    if ($f_user) {
      $sql->join('customers', function ($join) use($f_user) {
        $join->on('customers.id', '=', 'customer_id');
        $join->on('customers.user_id', '=', DB::raw($f_user));
      });
    }

    $cLstHNTs = $sql->get();
    $byCustomer = [];
    if (count($cLstHNTs) > 0) {
      foreach ($cLstHNTs as $cHnt) {
        if (!isset($byCustomer[$cHnt->customer_id]))
          $byCustomer[$cHnt->customer_id] = $arrayDays;
        $byCustomer[$cHnt->customer_id][$cHnt->date] = $cHnt->hnt;
      }
    }

    $lstCustomers = Customers::whereIn('id', array_keys($byCustomer))->get();
    $aLstCust = [];
    foreach ($lstCustomers as $c) {
      $aLstCust[$c->id] = $c;
    }

    $data = [
        'daysText' => '"' . implode('","', $days) . '"',
        'month' => $month,
        'arrayDays' => $arrayDays,
        'byCustomer' => $byCustomer,
        'aLstCust' => $aLstCust,
    ];

    $lstMonthsSpanish = lstMonthsSpanish();
    unset($lstMonthsSpanish[0]);
    $data['months'] = $lstMonthsSpanish;
    $data['cMonth'] = getMonthSpanish($month,false);

    /*     * ************************************************************** */
    $data['f_user'] = $f_user;
    $data['lastDay'] = $lastDay;
    $data['ausers'] = User::getusers()->pluck('name', 'id');
    return view('admin.informes.informeClientesMes', $data);
  }
  
    public function conexiones() {

      $byCustomer = [];
    $lst = \App\Models\HotspotStatus::all();
    if ($lst){
      $byCustomer = \App\Models\HotspotStatus::pluck('hotspot_imac')->toArray();
    }
    $lstCustomers = Customers::whereIn('hotspot_imac', $byCustomer)->get();
    $aLstCust = [];
    foreach ($lstCustomers as $c) {
      $aLstCust[$c->hotspot_imac] = $c;
    }

    return view('admin.informes.informeConexiones', [
        'data'=>$lst,
        'aLstCust' =>$aLstCust
    ]);
  }
  

}
