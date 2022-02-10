<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use Carbon\Carbon;
use App\Models\Dates;
use App\Models\User;
use App\Models\Rates;
use App\Models\TypesRate;
use App\Services\CitasService;

class NutriController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($month = null, $coach = 0, $serv = 0) {
        if (!$month){
          $yearActive = getYearActive();
          $month = $yearActive.date('-m');
        }
        $date = $month . '-01';

        $oCalendar = new \App\Services\CalendarService($date);
        $calendar = $oCalendar->getCalendarWeeks();
        
        $start = $calendar['firstDay'];
        $finish = $calendar['lastDay'];
        $rslt = CitasService::get_calendars($start,$finish,$serv,$coach,'nutri',$calendar['days']);
        $rslt['calendar'] = $calendar['days'];
        $rslt['month'] = $month;
        /*******************************************/
        return view('nutricion.index', $rslt);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexWeek($week = null, $coach = 0, $serv = 0) {
          
        $yearActive = getYearActive();
        if (!$week) $week = date('W');
        if ($week<10) $week = '0'.intVal($week);

        $time = strtotime($yearActive.'W'.$week);
        $date = date('Y-m-d',$time);

        $oCalendar = new \App\Services\CalendarService($date);
        $calendar = $oCalendar->getCalendarOneWeek();
        
        $start = $calendar['firstDay'];
        $finish = $calendar['lastDay'];
        $rslt = CitasService::get_calendars($start,$finish,$serv,$coach,'nutri',$calendar['days']);
        $rslt['calendar'] = $calendar['days'];
        $rslt['week'] = $week;
        $rslt['time'] = $time;
        /*******************************************/
        return view('nutricion.indexWeek', $rslt);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($date = null, $time = null) {
      $data = CitasService::get_create($date,$time,'nutri');
      return view('nutricion.form', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
      
      $data = CitasService::get_edit($id);
      if ($data){
        /**************************************************** */
        //encuesta
        $data['encNutr'] = $data['oUser']->getMetaContent('nutri_q1');
        $code = encriptID($data['oUser']->id).'-'.encriptID(time()*rand());
        $data['btnEncuesta'] = $code.'/'.getKeyControl($code);
        /**************************************************** */
        return view('nutricion.form',$data);
      } else {
        return $this->create();
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    public function listado($coach = 0, $type = 0) {

        $year = getYearActive();
        $month = null;
        /**************************************************** */
        $servic = Rates::getByTypeRate('nutri')->pluck('name', 'id');
        /**************************************************** */
        $aLst = [];
        $aUser = $uIDs = [];
        $sql = Dates::where('date_type', 'nutri')
                ->whereYear('date', '=', $year);
        if ($type && $type != 0)
            $sql->where('rate_id', $type);
        if ($coach && $coach > 0)
            $sql->where('user_id', $coach);

        $oLst = $sql->orderBy('date')->get();

        if ($oLst) {
            foreach ($oLst as $item) {
                $time = strtotime($item->date);
                $month = date('n', $time);
                $date = date('d / H', $time);
                $uID = $item->customer_id;
                $uIDs[] = $uID;
                if (!isset($aLst[$uID]))
                    $aLst[$uID] = [];
                if (!isset($aLst[$uID][$month]))
                    $aLst[$uID][$month] = [];

                $aLst[$uID][$month][] = $date . ':00';
            }
        }
        /**************************************************** */
        $oUsers = User::whereIn('id', $uIDs)->get();
        /**************************************************** */
        $lstMonts = lstMonthsSpanish();

        /**************************************************** */
        $users = User::whereBy_role('nutri')->where('status', 1)->get();
        $tColors = [];
        if ($users) {
            $auxColors = colors();
            $i = 0;
            foreach ($users as $item) {
                if (!isset($auxColors[$i]))
                    $i = 0;
                $tColors[$item->id] = $auxColors[$i];
                $i++;
            }
        }



        $rslt = [
            'type' => $type,
            'aLst' => $aLst,
            'aMonths' => $lstMonts,
            'year' => $year,
            'month' => $month,
            'type' => $type,
            'types' => $servic,
            'tColors' => $tColors,
            'users' => $users,
            'coach' => $coach,
            'oUsers' => $oUsers,
        ];

        return view('nutricion.listado', $rslt);
    }

    public function informe($uID) {
        $year = getYearActive();
        $customer = User::find($uID);
        $servic = TypesRate::where('type', 'nutri')->pluck('name', 'id');
        $users = User::whereBy_role('nutri')->pluck('name', 'id');
        $lstMonts = lstMonthsSpanish();
        /**************************************************** */
        $aLst = [];
        $oLst = Dates::where('date_type', 'nutri')
                ->where('customer_id', $uID)
                ->whereYear('date', '=', $year)
                ->orderBy('date')->get();

        if ($oLst) {
            foreach ($oLst as $i) {
                $time = strtotime($i->date);
                $month = date('n', $time);
                $date = date('d', $time);
                $hour = date('H', $time);
                $uID = $i->customer_id;
                $tm  = isset($lstMonts[$month]) ? $lstMonts[$month] : '';
                $aLst[] = [
                    'id'   => $i->id,
                    'hour' => $hour . ':00',
                    'date' => $date .' '.$tm,
                    'rate' => isset($servic[$i->id_type_rate]) ? $servic[$i->id_type_rate] : '',
                    'coach'=> isset($users[$i->user_id]) ? $users[$i->user_id] : '',
                    'charged' => $i->charged,
                ];
                
            }
        }
        
        $lstRates = [];
        $CustomersRates = CustomersRates::where('customer_id', $customer->id)
                        ->whereYear('created_at', "=", $year)->get();
        if($CustomersRates){
            foreach ($CustomersRates as $i){
                $lstRates[] = $i->rate->name;
            }
            $lstRates = array_unique($lstRates);
            sort($lstRates);
        }
        /**************************************************** */
        return view('nutricion.informe', [
            'user' => $customer,
            'aLst' => $aLst,
            'lstRates' => $lstRates,
            'id' => $uID,
            'year' => $year,
        ]);
    }

    public function uploadFile(Request $request) {

        $directory = storage_path() . "/Nutricion/" . $request->nombre;

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        $directory = storage_path() . "/Nutricion/" . $request->nombre . "/";
        // echo $storage_path . basename( $_FILES['uploadedfile']['name']);
        $directory = $directory . basename($_FILES['uploadedfile']['name']);
        if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $directory)) {
            return redirect()->action('NutriController@index');
        } else {
            return redirect()->action('NutriController@index');
        }
    }

}
