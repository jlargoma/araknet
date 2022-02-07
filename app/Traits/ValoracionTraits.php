<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


trait ValoracionTraits {

  public function get_valoracionFields() {
  
    $f = ['valora_date','valora_years','valora_lastname',
        'valora_concl','valora_dni','valora_sign','valora_tutor']; 
    for($i=1;$i<10;$i++) $f[] = 'valora_q'.$i;
    for($i=1;$i<5;$i++) $f[] = 'valora_q2_'.$i;
    for($i=1;$i<7;$i++) $f[] = 'valora_q3_'.$i;
    for($i=1;$i<7;$i++) $f[] = 'valora_q4_'.$i;
    for($i=1;$i<7;$i++) $f[] = 'valora_q5_'.$i;
    for($i=1;$i<7;$i++) $f[] = 'valora_q6_'.$i;
    for($i=1;$i<3;$i++) $f[] = 'valora_q7_'.$i;
    for($i=1;$i<3;$i++) $f[] = 'valora_q8_'.$i;
    for($i=1;$i<3;$i++) $f[] = 'valora_q9_'.$i;
    return $f;
    
  }
  public function get_valoracionQuestions() {
  
      $qstion1 = [
          'valora_q1' => '¿PADECE/HA PADECIDO ALGUNA ENFERMEDAD CARDIOVASCULAR O AFECCIÓN IMPORTANTE? (Infarto, arritmia, diabetes, colesterol alto, problemas de tensión o tiroides...)',
          'valora_q2' => 'RESPECTO A LO ANTERIOR ¿Y ENTRE SUS FAMILIARES DE 1er GRADO? (PADRES/HERMANOS/HIJOS)',
          'valora_q3' => '¿SE HA SOMETIDO A INTERVENCIONES QUIRÚRGICAS?',
          'valora_q4' => '¿HA TENIDO LESIONES TRAUMATOLÓGICAS? (Fractura, luxación, rotura fibrilar, esguince...)',
          'valora_q5' => '¿TOMA ALGÚN MEDICAMENTO DE MANERA HABITUAL? ¿TIENE ALERGIAS?',
          'valora_q6' => '¿HA PRACTICADO ANTERIORMENTE DEPORTE O REALIZADO EJERCICIOS DE FUERZA?',
          'valora_q7' => '¿TIENE AUTORIZACIÓN Y CONSENTIMIENTO MÉDICO PARA HACER DEPORTE?',
          'valora_q8' => '¿FUMA? En caso afirmativo, ¿Qué cantidad diaria?',
          'valora_q9' => '¿SE CONSIDERA UNA PERSONA SEDENTARIA O ACTIVA?',
      ];
      $qstion2 = [
          'valora_q2_1' => 'ECOGRAFÍA: DIÁSTASIS ABDOMINAL',
          'valora_q2_2' => 'ACTIVACIÓN DEL TRANSVERSO',
          'valora_q2_3' => 'VALORACIÓN DE CICATRICES',
          'valora_q2_4' => 'VALORACIÓN ESTRUCTURAL (CAMILLA Y SUELO)'
      ];
      $qstion3 = [
          'valora_q3_1' => 'Altura (Cm)',
          'valora_q3_2' => 'Peso',
          'valora_q3_3' => '% Grasa corporal',
          'valora_q3_4' => '% Masa muscular',
          'valora_q3_5' => 'Grasa visceral',
          'valora_q3_6' => 'Metabolismo basal (Kcal)',
      ];
      $qstion4 = [
          'valora_q4_1' => 'Pecho',
          'valora_q4_2' => 'Cintura',
          'valora_q4_3' => 'Cadera',
          'valora_q4_4' => 'Bicipital',
          'valora_q4_5' => 'Pierna',
          'valora_q4_6' => 'Muñeca',
      ];

      $qstion5 = [
          'valora_q5_1' => 'Presión sistólica',
          'valora_q5_2' => 'Presión diastólica',
          'valora_q5_3' => 'FC (reposo)',
          'valora_q5_4' => 'FC (máx)',
      ];

      $qstion6 = [
          'valora_q6_1' => 'CADERA',
          'valora_q6_2' => 'TOBILLO',
          'valora_q6_3' => 'HOMBRO Y CINTURA ESCAPULAR',
          'valora_q6_4' => 'MOVILIDAD TORÁCICA Y LUMBO-PÉLVICA',
          'valora_q6_5' => 'OTROS (CUELLO, CODO, MUÑECA, RODILLA…)',
      ];
      $qstion6_stext = [
          'valora_q6_1' => 'Se valora el rango de movimiento en los distintos movimientos de la articulación',
          'valora_q6_2' => 'Se valora el rango de movimiento en los distintos movimientos de la articulación',
          'valora_q6_3' => 'Se valora el rango de movimiento de todo el complejo del hombro',
          'valora_q6_4' => 'Se valora el rango de movimiento de la columna',
          'valora_q6_5' => 'Se valora el rango de movimiento en otros puntos de interés',
      ];

      $qstion7 = [
          'valora_q7_1' => 'Nº de repeticiones:',
          'valora_q7_2' => 'Con rodillas apoyadas (versión simplificada):'
      ];
      $qstion8 = [
          'valora_q8_1' => 'Nº de repeticiones:',
          'valora_q8_2' => 'Con rodillas apoyadas (versión simplificada):'
      ];
      $qstion9 = [
          'valora_q9_1' => 'Tiempo alcanzado (máximo 2 min)',
          'valora_q9_2' => 'Frecuencia cardíaca final:'
      ];

    return [
        'qstion1' => $qstion1,
        'qstion2' => $qstion2,
        'qstion3' => $qstion3,
        'qstion4' => $qstion4,
        'qstion5' => $qstion5,
        'qstion6' => $qstion6,
        'qstion7' => $qstion7,
        'qstion8' => $qstion8,
        'qstion9' => $qstion9,
        'qstion6_stext' => $qstion6_stext,
        ];
    
  }
  public function get_valoracion($user) {
  
    $fields = $this->get_valoracionFields();
    $data = $user->getMetaContentGroups($fields);
    foreach ($fields as $f)
      if (!isset($data[$f])) $data[$f] = null;
    
      
    $code = encriptID($user->id).'-'.encriptID(time()*rand());
    $keys = $code.'/'.getKeyControl($code);
    $data['url'] = '/valoracion/'.$keys;
    $data['url_dwnl'] = '/descargar-valoracion/'.$keys;
      
      
      
    return array_merge($data,$this->get_valoracionQuestions());
  }
  public function show_valoracion($user) {
  
    $fields = $this->get_valoracionFields();
    $data = $user->getMetaContentGroups($fields);
    foreach ($fields as $f)
      if (!isset($data[$f])) $data[$f] = null;
    
    return array_merge($data,$this->get_valoracionQuestions());
  }
  
  public function setValora(Request $request) {
   
    $fields = $this->get_valoracionFields();
    $uID = $request->input('id');
    $oUser = User::find($uID);
    
    
    $data = $oUser->getMetaContentGroups($fields);
    $req = $request->all();
    $metaDataADD = $metaDataUPD = [];
    foreach ($fields as $f){
      if (isset($req[$f])){
        if (isset($data[$f])) $metaDataUPD[$f] = $req[$f];
        else  $metaDataADD[$f] = $req[$f];
      }
    }
    if ($request->input('delSign')){
      $metaDataUPD['valora_sign'] = null;
    }
    $oUser->setMetaContentGroups($metaDataUPD,$metaDataADD);
    
    return redirect('/admin/usuarios/informe/' . $uID . '/valoracion')->with('success','Registro actualizado.');

  }

  function downlValoracion($code,$control) {
    
    $aCode = explode('-', $code);
    if (count($aCode)!=2) return 'error1';
    if ($control!=getKeyControl($code)) return 'error2';
    
    $uid = desencriptID($aCode[0]);
    $oUser = User::find($uid);
    if (!$oUser){
      abort(404);
      exit();
    }
    $u_name = $oUser->name;
    
    
    $valoracion = $this->get_valoracion($oUser);
    
    $fileName = $valoracion['valora_sign'];
    $sign = false;
    if ($fileName){
      $path = storage_path('/app/' . $fileName);
      $sign = File::exists($path);
      if ($sign){
        $fileName = str_replace('signs/','', $fileName);
      }
    }
    
    return view('pdfs.valoracion', [
        'valora' => $valoracion,
        'user' => $oUser,
        'sign_donwl' => $sign,
        'fileName' => $fileName,
    ]);
    $pdf = \Barryvdh\DomPDF\Facade::loadHTML($view);
    return $pdf->download('valoracion.pdf');
        
        
  }
  function seeValoracion($code,$control) {
    
    
    $aCode = explode('-', $code);
    if (count($aCode)!=2) return 'error1';
    if ($control!=getKeyControl($code)) return 'error2';
    
    $uid = desencriptID($aCode[0]);
    $oUser = User::find($uid);
    if (!$oUser){
      abort(404);
      exit();
    }
    $u_name = $oUser->name;
    
    
    $valoracion = $this->get_valoracion($oUser);
    
    $fileName = $valoracion['valora_sign'];
    $sign = false;
    if ($fileName){
      $path = storage_path('/app/' . $fileName);
      $sign = File::exists($path);
      if ($sign){
        $fileName = str_replace('signs/','', $fileName);
      }
    }
//    if (!$sign){
//      die('firma');
//    }
    return view('customers.valoracion', [
        'valora' => $valoracion,
        'user' => $oUser,
        'sign' => $sign,
        'fileName' => $fileName,
        'url' => '/firmar-valoracion/'.$code.'/'.$control,
        'url_dwnl' => '/descargar-valoracion/'.$code.'/'.$control,
    ]);
  }

  public function sendValoracion(Request $request) {
    $uID = $request->input('id_user',null);
    if (!$uID){
      return response()->json(['error','usuario no encontrado']);
    }
    $oUser = User::find($uID);
    if (!$oUser){
      return response()->json(['error','usuario no encontrado']);
    }
    
    $email = $oUser->email;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
      return response()->json(['error',$email.' no es un mail válido']);
    }
    
    $tit  = 'VALORACIÓN DE SALUD Y PREPARACIÓN AL ENTRENAMIENTO';
    $code = encriptID($oUser->id).'-'.encriptID(time()*rand());
    $keys = $code.'/'.getKeyControl($code);
    $link = \Illuminate\Support\Facades\URL::to('/valoracion/').'/'.$keys;
    
    
    $sended = \Illuminate\Support\Facades\Mail::send('emails._sign-consent', ['user' => $oUser,'tit'=>$tit,'link'=>$link], function ($message) use ($email) {
          $message->subject('FIRMMA DE VALORACIÓN DE SALUD Y PREPARACIÓN AL ENTRENAMIENTO');
          $message->from(config('mail.from.address'), config('mail.from.name'));
          $message->to($email);
        });
    return response()->json(['OK','Email enviado']);
  }
  
  public function signValoracion(Request $request,$code,$control) {
    $data = \App\Services\LinksService::getLinkData($code,$control);
    if (!$data){
      abort(404);
      exit();
    }
    $oUser = User::find($data[0]);
    if (!$oUser){
      abort(404);
      exit();
    }
    $uID  = $oUser->id;
    $sign = $request->input('sign');
    $dni = $request->input('dni');
    if (trim($dni) == '') {
      return back()->with(['error' => 'El DNI es obligatorio']);
    }
    $oUser->setMetaContent('valora_dni',$dni);
    
    
    $encoded_image = explode(",", $sign)[1];
    $decoded_image = base64_decode($encoded_image);
    $type = 'valora_sign';
    $fileName = $type.'-'. $uID .'-'.time().'.png';
    $oUser->setMetaContent($type,$fileName);
    
    $fileName = 'signs/'.$fileName;
    $path = storage_path('/app/' . $fileName);
    

    $storage = \Illuminate\Support\Facades\Storage::disk('local');
    $storage->put($fileName, $decoded_image);
    
    return back()->with(['success' => 'Firma Guardada']);
  
  }


  public function autosaveValora(Request $req) {
    $uID    = $req->input('id');
    $field = $req->input('field');
    $val   = $req->input('val');
    $oUser = User::find($uID);
    if ($oUser){
      $oUser->setMetaContent($field,$val);
      die('ok');
    }
    die('error');
  }
}
