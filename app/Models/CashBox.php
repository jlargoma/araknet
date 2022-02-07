<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashBox extends Model
{
    protected $table = 'cashbox';
    

    public function getArrayCashBoxResume($order_type='ASC')
    {
    	$last_arqueo = \App\Models\CashBox::where("typePayment", "ARQUEO")->orderBy('created_at', 'desc')->first();
        $previous_import = $last_arqueo->import ?? 0;

        if (!is_object($last_arqueo)) {
            $total_gastos = \App\Models\CashBox::whereRaw('import != 0 and type in ("GASTO")')->orderBy('date',$order_type)->get()->toArray();
            $total_charges = \App\Models\Charges::whereRaw('import != 0 and type_payment = "cash"')->orderBy('date_payment',$order_type)->get()->toArray();
            $total_income = \App\Models\Incomes::whereRaw('import != 0 and typePayment = "cash"')->orderBy('date',$order_type)->get()->toArray();
        } else {
            $total_gastos = \App\Models\CashBox::whereRaw('type in ("GASTO") and date >= ?', [$last_arqueo->date])
                            ->orderBy('date',$order_type)->orderBy('id', "DESC")->get()->toArray();
            $total_charges = \App\Models\Charges::whereRaw('import != 0 and type_payment = "cash" and date_payment >= ?', [$last_arqueo->date])
                            ->orderBy('date_payment',$order_type)->get()->toArray();
            $total_income = \App\Models\Incomes::whereRaw('import != 0 and typePayment = "cash" and date >= ?', [$last_arqueo->date])
                            ->orderBy('date',$order_type)->get()->toArray();
        }

        $all_movements = array_merge($total_gastos, $total_charges, $total_income);
        $cash_box = $this->buildResumeMovements($all_movements);

        $last_movement_date = null;
        //exit($previous_import);

        //sort by DESC date
//        ksort($cash_box['movements']);
//
//        $cash_box = $this->calculateMovementBalance($cash_box, $previous_import);
//
//        if ($order_type == "DESC") 
//        	ksort($cash_box['movements']);
//
//
//        // move the internal pointer to the end of the array        
//        end($cash_box['movements']);         
//
//        //get the current key of the internal pointer
//        $last_movement_date = key($cash_box['movements']);
//
//        //move the internal pointer to the first array element
//        reset($cash_box['movements']);

        return [ 
            'cashBox' => $cash_box['movements'] ?? [],
            'previous_import' => $previous_import,
            'last_arqueo' => $last_arqueo,
            'total_balance' => $cash_box["total_balance"] ?? 0,
            'last_movement_date' => $last_movement_date,
        ];
    }

    private function calculateMovementBalance(&$cash_box, $previous_import)
    {
    	$total_balance = $previous_import;
    	$all_movements = [];
    	foreach ($cash_box['movements'] as $date => $movements) {
    		foreach ($movements as $index => $movement) {

    			if ($movement["type_payment"] != "ARQUEO")
    				$total_balance += $movement["import"];
	    			
	    		$movement["balance"] = $total_balance;

    			$all_movements[$date][] = $movement;
    		}
    	}

    	$cash_box["total_balance"] = $total_balance;

    	return [
    		"movements" => $all_movements,
    		"total_balance" => $total_balance
    	];
    }

    private function buildResumeMovements($all_movements)
    {
        $movements = [];
//        $date = "";
//        foreach ($all_movements as $movement) {
//            $date = $movement['date'] ?? $movement['date_payment'];
//            $type = !is_numeric($movement["type"]) ? $movement["type"] : "INGRESO";
//
//            $type_payment = isset($movement["type_rate"]) ? \App\Models\TypesRate::find($movement["type_rate"])->name : $movement["typePayment"];
//            $rate = isset($movement["id_rate"]) ? \App\Models\Rates::find($movement["id_rate"]) : null;
//            $concept = $comment = '';
//            if ($rate){
//              $concept = $rate->name;
//              $comment = $rate->planStripe;
//            }
//            else{
//              $concept = isset ($movement["concept"]) ? $movement["concept"] : '';
//              $comment = isset ($movement["comment"]) ? $movement["comment"] : '';
//            }
//            
//            
//            $import = $type == "GASTO" && $type_payment != "ARQUEO" ? $movement["import"] * -1 : $movement["import"];
//	    $user   = isset($movement["id_user"]) ? 'USER-name' : $movement["type"];
////	    $user   = isset($movement["id_user"]) ? \App\Models\User::find($movement["id_user"])->name : $movement["type"];
//
//            $data_tranformed = [
//                "type" => $type,
//                "type_payment" => $type_payment,
//                "concept" => $concept,
//                "date" => $date,
//                "import" => (float)$import,
//                "comment" => $comment,
//                "balance" => 0,
//                "user" => $user,
//            ];
//            $movements["movements"][$date][] = $data_tranformed;
//            $data_tranformed = null;
//        }

        return $movements;
    }
}
