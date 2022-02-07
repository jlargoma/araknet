<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


trait EncuestaNutriTraits {

  public function get_encNutriFields() {
  
    $f = []; 
    for($i=1;$i<23;$i++) $f[] = 'nutri_q'.$i;
    for($i=1;$i<5;$i++) $f[] = 'nutri_q22_1_'.$i;
    for($i=1;$i<5;$i++) $f[] = 'nutri_q22_2_'.$i;
   
    return $f;
    
  }
  public function get_nutriQuestions() {
  
      $qstion1 = [
          'nutri_q1' => 'Nombre completo',
          'nutri_q2' => 'Fecha de nacimiento',
          'nutri_q3' => 'Sexo',
          'nutri_q4' => 'Intervenciones quirúrgicas',
          'nutri_q5' => 'Patologías? (Hipertensión, diabetes, colesterol alto, tiroides…)',
          'nutri_q6' => 'Toma alguna medicación?',
          'nutri_q7' => 'Alergias o intolerancias alimentarias?',
          'nutri_q8' => 'Alimentos que rechaza',
          'nutri_q9' => 'Agua / infusiones?',
          'nutri_q10' => 'Digestiones',
          'nutri_q11' => 'Deposiciones',
          'nutri_q12' => 'Cuantas veces orina al día?',
          'nutri_q13' => 'Ha seguido alguna dieta anteriormente?',
          'nutri_q14' => 'Suele comer fuera de casa? (catering de la empresa, menú de bar, etc)',
          'nutri_q15' => 'Con cuantas personas convive?',
          'nutri_q16' => 'Quien cocina habitualmente?',
          'nutri_q17' => 'Tipo de alimentación?',
          'nutri_q18' => 'Cuantas comidas hace al día?',
          'nutri_q19' => 'Hace algún tipo de deporte o acude al gym? (mínimo 1 hora)',
          'nutri_q20' => 'En su actividad laboral, pasa gran parte de la jornada sentado?',
          'nutri_q21' => 'Cómo acude a su puesto de trabajo?',
          'nutri_q22' => 'Diario de alimentación',
      ];
      //Entres semana / Fines de semana

      $qstion2 = [
          'nutri_q22_1_1' => 'Desayuno',
          'nutri_q22_1_2' => 'Comida',
          'nutri_q22_1_3' => 'Cena',
          'nutri_q22_1_4' => 'Snacks / Entrehoras',
          'nutri_q22_2_1' => 'Desayuno',
          'nutri_q22_2_2' => 'Comida',
          'nutri_q22_2_3' => 'Cena',
          'nutri_q22_2_4' => 'Snacks / Entrehoras',
      ];
      $options = [
          'nutri_q3' => ['Masculino','Femenino'],
          'nutri_q9' => ['Menos de 500 ml','Entre 500 ml y 1,5 l','Entre 1,5 l y 2,5 l','Más de 2,5 l'],
          'nutri_q10' => ['Normales','Pesadas y lentas'],
          'nutri_q11' => ['Varias veces al día ','Una vez al día','Cada 2-3 días','Estreñimiento crónico'],
          'nutri_q12' => ['Menos de 4 veces','Entre 4 y 10 veces','Más de 10 veces'],
          'nutri_q13' => ['Si','No'],
          'nutri_q14' => ['Si','No','Algún día'],
          'nutri_q17' => ['Onmívora','Vegana','Vegetariana','Ovolacteovegetariana','Otra'],
          'nutri_q18' => ['2','3','5'],
          'nutri_q19' => ['Menos de 3 veces a la semana','Entre 3 y 5 días a la semana','No hago ningún tipo de deporte'],
          'nutri_q20' => ['Si','No'],
          'nutri_q21' => ['Coche / moto','Transporte público','Bicicleta / patinete','Caminando'],
      ];
      

    return [
        'qstion1' => $qstion1,
        'qstion2' => $qstion2,
        'options' => $options,
        ];
  }
  public function get_encNutri($user) {
  
    $fields = $this->get_encNutriFields();
    $data = $user->getMetaContentGroups($fields);
    foreach ($fields as $f)
      if (!isset($data[$f])) $data[$f] = null;
    
      
    $code = encriptID($user->id).'-'.encriptID(time()*rand());
    $keys = $code.'/'.getKeyControl($code);
    $data['url'] = '/encNutri/'.$keys;
    $data['url_dwnl'] = '/descargar-encNutri/'.$keys;
      
      
      
    return array_merge($data,$this->get_nutriQuestions());
  }
  public function show_encNutri($user) {
  
    $fields = $this->get_encNutriFields();
    $data = $user->getMetaContentGroups($fields);
    foreach ($fields as $f)
      if (!isset($data[$f])) $data[$f] = null;
    
    return array_merge($data,$this->get_nutriQuestions());
  }
  
  public function setEncNutri(Request $request) {
   
    $fields = $this->get_encNutriFields();
    $code = $request->input('_code','');
    $aCode = explode('-', $code);
    if (count($aCode)!=2) return 'error1';
    if ($request->input('_control')!=getKeyControl($code)) return 'error2';
    
    $uid = desencriptID($aCode[0]);
    $oUser = User::find($uid);
    if (!$oUser){
      abort(404);
      exit();
    }
    
    $data = $oUser->getMetaContentGroups($fields);
    $req = $request->all();
    $metaDataADD = $metaDataUPD = [];
//    dd($fields);
    foreach ($fields as $f){
      if (isset($req[$f])){
        if (isset($data[$f])) $metaDataUPD[$f] = $req[$f];
        else  $metaDataADD[$f] = $req[$f];
      }
    }
  
    $oUser->setMetaContentGroups($metaDataUPD,$metaDataADD);
    
    return redirect()->back()->with('success','Encuesta enviada.');

  }

 
  function seeEncuestaNutri($code,$control) {
    $aCode = explode('-', $code);
    if (count($aCode)!=2) return 'error1';
    if ($control!=getKeyControl($code)) return 'error2';
    
    $uid = desencriptID($aCode[0]);
    $oUser = User::find($uid);
    if (!$oUser){
      abort(404);
      exit();
    }
    
    $encNutri = $this->get_encNutri($oUser);
    //dd($encNutri);
    return view('customers.printEncuestaNutri', [
        'data' => $encNutri
    ]);
    
  }
  function formEncuestaNutri($code,$control) {
    
    
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
    
    $nutri_q1 = $oUser->getMetaContent('nutri_q1');
    if ($nutri_q1){
      return view('customers.encuestaNutri',['already' =>true]);
    }
    
    $encNutri = $this->get_encNutri($oUser);
    return view('customers.encuestaNutri', [
        'data' => $encNutri,
        'user' => $oUser,
        'code' => $code,
        'control' => $control,
        'url_dwnl' => '/descargar-encNutri/'.$code.'/'.$control,
    ]);
  }

  public function sendEncNutri(Request $request) {
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
    $link = \Illuminate\Support\Facades\URL::to('/encNutri/').'/'.$keys;
    
    
    $sended = \Illuminate\Support\Facades\Mail::send('emails._sign-consent', ['user' => $oUser,'tit'=>$tit,'link'=>$link], function ($message) use ($email) {
          $message->subject('FIRMMA DE VALORACIÓN DE SALUD Y PREPARACIÓN AL ENTRENAMIENTO');
          $message->from(config('mail.from.address'), config('mail.from.name'));
          $message->to($email);
        });
    return response()->json(['OK','Email enviado']);
  }
  
  
  public function clearEncuestaNutri(Request $request) {
    $uID = $request->input('uID',null);
    
    $lstKeys = $this->get_nutriQuestions();
    $lstKeys = array_merge($lstKeys['qstion1'],$lstKeys['qstion2']);
            
    DB::table('user_meta')
            ->where('user_id',$uID)->whereIn('meta_key', array_keys($lstKeys))
            ->delete();
    
    return 'OK';
  }
  public function sendEncuestaNutri(Request $request) {
    $uID = $request->input('uID',null);
    $oUser = User::find($uID);
    if (!$oUser) return 'Usuario no encontrado';
    $already = $oUser->getMetaContent('nutri_q1');
    if ($already) return 'La encuesta ya se encuentra completada';

    $code = encriptID($oUser->id).'-'.encriptID(time()*rand());
    $keys = $code.'/'.getKeyControl($code);
    $urlEncuesta = \URL::to('/encuesta-nutricion').'/'.$keys; 
    
    $email    = $oUser->email;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return $email.' no es un mail válido';
    try{
      $subj = 'Encuesta de Nutrición - evolutio';
      $sended = \Illuminate\Support\Facades\Mail::send('emails._encuestaNutri', [
              'user'    => $oUser,
              'urlEntr' => $urlEncuesta,
      ], function ($message) use ($email,$subj) {
              $message->subject($subj);
              $message->from(config('mail.from.address'), config('mail.from.name'));
              $message->to($email);
              $message->attach(public_path('/img/protocolo.jpeg'), array(
                    'as' => 'Protocolo Covid', 
                    'mime' => 'image/jpeg'));
      });
    } catch (\Exception $ex) {
      return ($ex->getMessage());
    }
    return 'OK';
            

  }
  
  
  

}
