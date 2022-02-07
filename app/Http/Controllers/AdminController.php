<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Carbon\Carbon;
use Auth;
use Mail;
use App\Classes\Mobile;
use DB;
class AdminController extends Controller
{
    public function index(Request $request)
    {
    	return redirect()->action('UsersController@clientes');

    }

    public function contabilidad()
    {
        return view('admin.dashboard');
    }

    public function unauthorized()
    {
    	return view('admin.unauthorized', [ 'user' => Auth::user() ]);
    }

    public function gestionAsistencia($id = 0, $date = "")
    {
        if ($date == "") {
            $fech = Carbon::now();
        }else{
            $fech = Carbon::createFromFormat('Y-m',$date);
        }    

        if ($id != 0) {
            $selectedCoach = \App\User::find($id);
            $idCoach   = $selectedCoach->id;
            $nameCoach = $selectedCoach->name;
        }else{
            $selectedCoach = 0;
            $idCoach = 0;
            $nameCoach = '';
        }
        
        return view('admin.gestion_asistencia', [ 
                                                    'date'          => $fech,
                                                    'coachs'        => \App\User::where('role', 'teach')->get(),
                                                    'selectedCoach' => $selectedCoach,
                                                    'idCoach'       => $idCoach,
                                                    'nameCoach'     => $nameCoach,
                                                ]);
    }

    public function getDataClientsByDay($date, $dia, Request $request)
    {
        $date = Carbon::createFromFormat('Y-m-d', $date);
        
        if ($request->idCoach == 0) {

            $horarios = \App\Schedules::where('day', $dia)->get();

        }else{
            $clasesCoach = \App\CoachClasses::where('date', '=', $date->format('Y-m-d'))->get();
            $arrayIds = array();
            foreach ($clasesCoach as $claseCoach) {
                $arrayIds[] = $claseCoach->id;
            }
            $horarios = \App\Schedules::where('day', $request->day)->whereIn('id_class', $arrayIds)->get();
        }
        

        return view('admin._gestion_asistencia_tabla_semanal', [ 
                                                                    'horarios' => $horarios,
                                                                    'date'     => $date,
                                                                ]);


    }

    public function perdidasGanacias($year = "")
    {
        if ( $year == "" ) {
            $year = Carbon::now()->copy()->format('Y');
            $date = Carbon::now()->copy();
        }else{
            $date = Carbon::createFromFormat('Y', $year);
        }
        /* INGRESOS */
        $rates = IncomesController::getSummaryYear($year, 1);
	    unset($rates[$year]['clients']);
        /* INGRESOS EXTRAS */
        $ratesExtras =  IncomesController::getTypeIncomes($year);
        $ingresos = \App\Incomes::all();
        if (count($ingresos) > 0) {
            foreach ($ingresos as $key => $ingreso) {
                $x = Carbon::createFromFormat('Y-m-d', $ingreso->date);
                $ratesExtras[$date->copy()->format('Y')][$ingreso->type][$x->copy()->format('n')] += $ingreso->import;
            }
        }
        /* GASTOS */
        $gastos = ExpensesController::getSummaryYear($year);

        /* DATOS ESTADISTICAS */
        /* clientes por mes */
        $yearAux = $date->copy()->startOfYear();
        for($i = 1 ; $i <= 12; $i++):
            $clientes[$i] =  \App\User::getUserActiveByMonth($yearAux->copy()->format('Y-m-d'));
            $yearAux->addMonths(1);
        endfor;
        /* ingresos */



        return view('admin.contabilidad.perdidas-ganancias', [
                                                'year'        => $year,
                                                'date'        => $date,
                                                'rates'       => $rates,
                                                'ratesExtras' => $ratesExtras,
                                                'gastos'      => $gastos,
                                                'clientes'    => $clientes,
                                            ]);
    }



    public function translateData()
    {
        return view('admin.translate');

    }

    public function bulkData(Request $request)
    {
        $file = $request->file('csv');
        $routeFile = storage_path('/csv/');
        $name = 'csv_'.date('Y_m_d_H_i_s').".csv";
        $file->move($routeFile, $name);
        echo "<pre>";
        $handle = fopen($routeFile."/".$name, "r");
        if ($handle) {
            $index = 0;
            while (($line = fgets($handle)) !== false) {
                if ($index > 0) {
                    $data = explode(';', $line);
                    if (count($data) > 0) {
                        // print_r($data);

                        $this->insertBulkData($data);



                    }
                    
                }
                $index++;
            }
            fclose($handle);
        }
    }

    public function insertBulkData($data){
        $date = Carbon::createFromFormat('Y-m-d','2016-06-01');
        $user = \App\User::where('name', 'LIKE', '%'.utf8_encode($data[1]).'%' )->get();
        if ( count($user) > 0) {
            $user = $user[0];
            echo $user->name."<br>";
            $rate = \App\Rates::where('name', 'LIKE', '%'.utf8_encode($data[2]).'%' )->get();
            if ( count($rate) > 0){
                $rate = $rate[0];

                for ($i=3; $i < 14 ; $i++) {
                    if ( $data[$i] != "") {
                        /* ASIGNAMOS */
                        $userRate = new \App\UserRates();
                        $userRate->id_user = $user->id;
                        $userRate->id_rate = $rate->id;
                        $userRate->created_at = $date->copy()->format('Y-m-d');
                        $userRate->updated_at = $date->copy()->format('Y-m-d');
                        $userRate->save();

                        /* COBRAMOS */
                        $cobro               = new \App\Charges();
                        $cobro->id_user      = $user->id; 
                        $cobro->date_payment = $date->copy()->format('Y-m-d'); 
                        $cobro->id_rate      = $rate->id;
                        $cobro->type_payment = 'cash'; 
                        $cobro->type         = 1;
                        $cobro->import       = $data[$i]; 
                        $cobro->discount     = 0; 
                        $cobro->type_rate    = $rate->type; 
                        $cobro->save();
                    }


                    $date->addMonth();
                }



            }else{
                echo "Usuario no tiene tarifas/ datos ".$user->name."<br>";
            }




        }
        
    }

    public function resumen()
    {
        return view('admin.resumenExcel', ['clientes' => \App\User::where('role', 'user')->get() ]);
    }

}
