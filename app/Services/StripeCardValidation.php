<?php

namespace App\Services;

class StripeCardValidation {

    static function validate($req){
        $validator = \Illuminate\Support\Facades\Validator::make($req->all(), [
                    'cc_number' => 'required|min:14|max:20',
                    'cc_expide_mm' => 'required|numeric|min:1|max:13',
                    'cc_expide_yy' => 'required|numeric|min:20|max:' . (date('Y') + 10),
                    'cc_cvc' => 'required|numeric|min:99|max:9999',
                        ], [
                    'cc_number.required' => 'Debe ingresar el número de tarjeta',
                    'cc_number.min' => 'Debe ingresar el número de tarjeta',
                    'cc_number.max' => 'Debe ingresar el número de tarjeta',
                    'cc_expide_mm.required' => 'Mes de vencimiento no válido',
                    'cc_expide_mm.max' => 'Mes de vencimiento no válido',
                    'cc_expide_yy.required' => 'Debe ingresar el año de vencimiento',
                    'cc_expide_yy.min' => 'Año de vencimiento no válido',
                    'cc_expide_yy.max' => 'Año de vencimiento no válido',
                    'cc_cvc.required' => 'Debe ingresar el CVC / CVV',
                    'cc_cvc.min' => 'Debe ingresar el CVC / CVV',
                    'cc_cvc.max' => 'Debe ingresar el CVC / CVV',
                        ]
        );
        if ($validator->fails()) {
            return $validator;
        }
        
        return 'OK';
    }
}