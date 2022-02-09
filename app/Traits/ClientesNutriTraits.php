<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;

trait ClientesNutriTraits {

  
    // Seccion Nutricion

    public function nutricion(Request $request, $month = "") {
        if (empty($month)) {
            $date = Carbon::now();
        } else {
            echo $month;
            $date = Carbon::createFromDate(date('Y'), $month, 1);
        }

        $arrayPaymentMonthByUser = array();
        $month = $date->copy()->startOfYear();
        $visitas = \App\Dates::where('id_type_rate', 5)->groupBy('customer_id')->get();

        for ($i = 0; $i < 6; $i++) {
            $arrayPaymentMonthByUser[$i] = $this->getPendingPaymentByMonth($month->copy()->format('Y-m-d'));
            $month->addMonth();
        }

        return view('/admin/usuarios/nutricion', [
            'user' => new User(),
            'date' => $date,
            'visitas' => $visitas,
            'payments' => $arrayPaymentMonthByUser,
        ]);
    }

    public function informeNutricion($year = '', $id) {
        if ($year == '') {
            $year = date('Y');
        }

        $months = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];
        $customer = User::find($id);

        $directory = storage_path() . "/Nutricion/" . $customer->name;
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        $directorio = dir($directory);
        $resumenes = \App\FisicCheck::where('customer_id', $id)->orderBy('id_date', 'DSEC')->first();

        if (count($resumenes) > 0) {
            $age = $resumenes->age;
            $height = $resumenes->height;
            $objetive = $resumenes->objetive;
            $weight = $resumenes->weight;
        } else {
            $age = "";
            $height = "";
            $objetive = "";
            $weight = "";
        }

        $resumen2 = \App\FisicCheck::where('customer_id', $id)->orderBy('id_date', 'DSEC')->first();
        if (count($resumen2) > 0) {
            $actualWeight = $resumen2['weight'];
        } else {
            $actualWeight = 0;
        }


        return view('/admin/usuarios/informe_nutricion', [
            'services' => \App\TypesRate::all(),
            'months' => $months,
            'dates' => \App\Dates::where('customer_id', $id)->get(),
            'age' => $age,
            'height' => $height,
            'objetive' => $objetive,
            'weight' => $weight,
            'chequeos' => \App\FisicCheck::where('customer_id', $id)->get(),
            'year' => $year,
            'user' => $customer,
            'directory' => $directory,
            'download' => $directorio,
            'actualWeight' => $actualWeight,
            'id' => $id,
        ]);
    }

    public function canvasInforme($id) {

        $resumenes = \App\FisicCheck::where('customer_id', $id)->orderBy('id', 'ASC')->first();

        if (count($resumenes) > 0) {
            $age = $resumenes->age;
            $height = $resumenes->height;
            $objetive = $resumenes->objetive;
            $weight = $resumenes->weight;
        } else {
            $age = "";
            $height = "";
            $objetive = "";
            $weight = "";
        }
        $resumen2 = \App\FisicCheck::where('customer_id', $id)->orderBy('id', 'DSEC')->first();
        if (count($resumen2) > 0) {
            $actualWeight = $resumen2['weight'];
        } else {
            $actualWeight = 0;
        }

        $chequeos = \App\FisicCheck::where('customer_id', $id)->get();
        $fechas = array();
        if (count($chequeos) > 0) {
            foreach ($chequeos as $key => $chequeo) {
                $fecha = Carbon::createFromFormat('Y-m-d H:i:s', $chequeo->cita->date);
                $fechas[$key] = "'" . $fecha->format('d-m-Y') . "',";
            }
        }


        return view('/admin/usuarios/_informe-canvas', [
            'objetive' => $objetive,
            'weight' => $weight,
            'actualWeight' => $actualWeight,
            'fechas' => \App\FisicCheck::where('customer_id', $id)->get(),
            'chequeos' => \App\FisicCheck::where('customer_id', $id)->get(),
        ]);
    }

    public function newInforme(Request $request) {
        $informes = \App\FisicCheck::where('id_date', $request->id)->first();

        // echo "id ".$request->id."!!";
        // echo "coach ".$request->coach."!!";
        // echo "user ".$request->user."!!";
        // echo "comentario ".$request->comentario."!!";
        // echo "edad ".$request->age."!!";
        // echo "weight ".$request->weight."!!";
        // echo "height ".$request->height."!!";
        // echo "objetive ".$request->objetive."!!";
        // echo "basal ".$request->basal."!!";

        if (count($informes) == 0 && $request->id != Null && $request->age != Null && $request->weight != Null && $request->height != Null && $request->objetive != Null && $request->basal != Null) {
            $informe = new \App\FisicCheck();

            $informe->id_date = $request->id;
            $informe->user_id = $request->coach;
            $informe->customer_id = $request->user;
            $informe->comment = $request->comentario;
            $informe->age = $request->age;
            $informe->weight = $request->weight;
            $informe->height = $request->height;
            $informe->objetive = $request->objetive;
            $informe->basal = $request->basal;

            $forma = new \App\PlanFit();

            $forma->customer_id = $request->user;
            $forma->weight = $request->weight;
            $forma->week = 0;
            $forma->save();

            $informe->save();
            echo "Informe creado";
        } else if (count($informes) > 0) {
            $informes->id_date = $request->id;
            $informes->comment = $request->comentario;
            $informes->age = $request->age;
            $informes->weight = $request->weight;
            $informes->height = $request->height;
            $informes->objetive = $request->objetive;
            $informes->basal = $request->basal;

            $informes->save();
            echo "Informe guardado";
        } else {
            echo "Te falta algun dato";
        }
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
            return redirect()->action('UsersController@nutricion');
        } else {
            return redirect()->action('UsersController@nutricion');
        }
    }

    public function getDownload($customer, $namefile) {
        //PDF file is stored under project/public/download/info.pdf

        $file = storage_path() . "/Nutricion/" . $customer . "/" . $namefile;

        $headers = array(
            'Content-Type: application/pdf',
        );

        return Response()->download($file);
    }

    public function exportClients() {
        $array_excel = [];
        $array_excel[] = [
            'Nombre',
            'Email',
            'phone',
            'Estado'
        ];

        Excel::create('clientes', function ($excel) use ($array_excel) {

            $excel->sheet('clientes_activos_inactivos', function ($sheet) use ($array_excel) {

                $customers = User::where('role', 'user')->get();

                foreach ($customers as $customer) {
                    $array_excel[] = [
                        $customer->name,
                        $customer->email,
                        $customer->phone,
                        $customer->status ? 'ACTIVO' : 'NO ACTIVO'
                    ];
                }

                $sheet->fromArray($array_excel);
            });
        })->export('xls');
    }

}
