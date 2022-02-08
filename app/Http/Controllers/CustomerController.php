<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use Mail;
use App\Models\User;
use App\Models\UserRates;
use \App\Traits\ValoracionTraits;
use \App\Traits\EncuestaNutriTraits;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller {

  use ValoracionTraits,EncuestaNutriTraits;
  
  public function index() {
    die();
  }
  public function showResult(Request $request) {
    return view('customers.message');
  }
  
  public function signConsentSave(Request $request,$code,$control) {
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
    $encoded_image = explode(",", $sign)[1];
    $decoded_image = base64_decode($encoded_image);
    switch ($data[1]){
      case 1001:
        $type = 'sign_fisioIndiba';
        break;
      case 2002:
        $type = 'sign_sueloPelvico';
        break;
      default:
        $type = 'sign_gral';
        break;
    }
    
    $fileName = 'signs/' .$type.'-'. $uID .'-'.time().'.png';
    $path = storage_path('/app/' . $fileName);
    
    $oUser->setMetaContent($type,$fileName);

    $storage = \Illuminate\Support\Facades\Storage::disk('local');
    $storage->put($fileName, $decoded_image);
    
    return redirect('/resultado')->with(['success' => 'Firma Guardada']);
  }

  function signConsent($code,$control) {
    
    $data = \App\Services\LinksService::getLinkData($code,$control);
    $dView = [
      'name'=>'',  
      'file'=>'',  
      'tit' =>'',  
      'tmsg'=>'',  
      'msg' =>'',  
      'url' =>"/firmar-consentimiento/$code/$control",  
    ];
    if (!$data){
      abort(404);
      exit();
    }
    
    $oUser = User::find($data[0]);
    if (!$oUser){
      abort(404);
      exit();
    }
    switch ($data[1]){
      case 1001:
        $tit = 'CONSENTIMIENTO FISIOTERAPIA CON INDIBA';
        $doc = 'CONSENTIMIENTO-FISIOTERAPIA-CON-INDIBA';
        break;
      case 2002:
        $tit = 'CONSENTIMIENTO SUELO PELVICO';
        $doc = 'CONSENTIMIENTO-SUELO-PELVICO';
        break;
      default:
        $tit = '';
        $doc = '';
        break;
    }
    

    return view('customers.consentimiento', [
      'name'=>$oUser->name,  
      'file'=>$doc,  
      'tit' =>$tit,  
      'url' =>"/firmar-consentimiento/$code/$control", 
    ]);


    $path = storage_path('/app/signs/' . $uid . '.png');
    if (!File::exists($path)) {
      abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = \Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
  }


  function getSign($file) {

    $path = storage_path('/app/signs/' .$file);
    if (!File::exists($path)) {
      abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = \Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
  }
  
  
  
  /*
 * -----------------------------------------------------------
 *  CONTRATOS
 * -----------------------------------------------------------
 */

  
  function seeContracts($uID){
    $oUser = User::find($uID);
    if (!$oUser){
      return redirect('404')->withErrors(['Cliente no encontrado']);
    }
    
    // Already Signed  -------------------------------------------
    $sing_contrato = false;
      $fileName = $oUser->getMetaContent('contrato_FIDELITY_');
      $path = storage_path('app/'.$fileName);
      if ($fileName && File::exists($path)){
        return response()->file($path, [
        'Content-Disposition' => str_replace('%name', 'Contrato ', "inline; filename=\"%name\"; filename*=utf-8''%name"),
        'Content-Type'        => 'application/pdf'
        ]);
      }
    
   

    return redirect('404')->withErrors(['Contrato no encontrado']);
  }
  
  
  

  function downlContract($code,$control) {
    $data = $this->getContracts($code,$control);
    if ($data['sign']){
      return response()->download($data['path'], 'contrato-EVOLUTIO.pdf', [], 'inline');
    } 
    
    return back()->withErrors(['contrato no encontrado']);
        
  }
  

  
  function signContrato($code,$control) {
    return view('customers.contrato', $this->getContracts($code,$control));
  }
  
  
  public function rmContracts(Request $request) {
    $uID = $request->input('id_user');
    
    $oUser = User::find($uID);
    if (!$oUser){
      return response()->json(['error','cliente no encontrado']);
    }
    
    $uPlan =$oUser->getPlan();
    // Already Signed  -------------------------------------------
    if ($uPlan !== null){
      $fileName = $oUser->setMetaContent('contrato_FIDELITY_'.$uPlan, null);
      return response()->json(['OK','Contrato removido']);
    }
      
    return response()->json(['error','Contrato no encontrad']);
    
  }
  public function signContratoSave(Request $request,$code,$control) {
    $data = $this->getContracts($code,$control);
    
    $dni = $request->input('dni');
    $sign = $request->input('sign');
    $encoded_image = explode(",", $sign)[1];
    $decoded_image = base64_decode($encoded_image);
    
    $data = $this->getContracts($code,$control);
    if (isset($data['error'])){
      return redirect('404')->withErrors([$data['error']]);
    }
    $text = $data['text'];
    $tit = $data['tit'];
    $oUser = $data['user'];
    
    //Signs -------------------------------------------
    $data['signFile'] = $encoded_image;
    $data['dni'] = $dni;
    
    //PDF -------------------------------------------
    $pdf = \App::make('dompdf.wrapper');
    $pdf->getDomPDF()->set_option("enable_php", true)->setHttpContext(
        stream_context_create([
            'ssl' => [
                'allow_self_signed'=> TRUE,
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
            ]
        ])
    );
    
    $pdf->loadView('customers.contratosDownl',$data);
//    return view('customers.contratosDownl',$data);
    $output = $pdf->output();
//        return $pdf->download('invoice.pdf');
    //return $pdf->stream();
        
    //save document
    $uPlan = $oUser->getPlan();
    
    $fileName = 'contracts/Contrato-'. $oUser->id .'-'.time().'.pdf';
    $path = storage_path('/app/' . $fileName);
        
    $oUser->setMetaContent('contrato_FIDELITY_'.$uPlan,$fileName);
    $storage = \Illuminate\Support\Facades\Storage::disk('local');
    $storage->put($fileName, $output);
    
    //---------------------------------------------------
    // Send Mail
    $subject = "Contrato $tit";
    $mailContent = 'Hola '.$oUser->name.', <br/><br/>';
    $mailContent .= '<p>Gracias por firmar su contrato del <b>'.$tit.'</b> con nuestro centro de entranamientos <b>EVOLUTIO.FIT</b>';
    $mailContent .= '<p>Le adjuntamos el documento firmado</p>';
    $mailContent .= '<br/><br/><br/><p>Muchas Gracias.!</p>';
    $email = $oUser->email;
    try{
      
      Mail::send('emails.base', [
            'mailContent' => $mailContent,
            'title'       => $subject,
            'tit'       => $subject
        ], function ($message) use ($subject,$email,$path,$fileName) {
            $message->from(config('mail.from.address'), config('mail.from.name'));
            $message->subject($subject);
            $message->to($email);
            $message->attach( $path, array(
                            'as' => $fileName.'.pdf', 
                            'mime' => 'application/pdf'));
        });
        
      return redirect('/resultado')->with(['success' => 'Firma Guardada']);
    } catch (\Exception $e){
      return $e->getMessage();
    }
    //---------------------------------------------------
  }
  
  
  
    /*
   * If the contract is signed, show the PDF
   */
  function getContracts($code,$control){
    
    $data = \App\Services\LinksService::getLinkData($code,$control);
    if (!$data){
      return ['error'=>'Contrato no encontrado'];
    }
    
    $oUser = User::find($data[0]);
    if (!$oUser || $data[0] != $oUser->id){
      return ['error'=>'Contrato no encontrado'];
    }
    
    $text = '';
    
    
    $uF_tCreated = time();
   $uF_start = date('d-m-Y',$uF_tCreated); 
   $uF_end = date('d-m-Y', strtotime('+1 year', $uF_tCreated) ); 
    
      
    $oClientesContratos = new \App\Helps\ClientesContratos();
        $tit = 'PLAN FIDELITY';
        $text = 's';
    
    
     // Already Signed  -------------------------------------------
    if ($uPlan !== null){
      $fileName = $oUser->getMetaContent('contrato_FIDELITY_');
      $path = storage_path('app/'.$fileName);
      if ($fileName && File::exists($path)){
        return [
          'path' => $path,
          'sign' => true,
          'text' => null,
          'error' => null,
          'user' => null,
          'tit' =>$tit, 
          'url' =>"/descargrar-contrato/$code/$control", 
        ];
      }
    }
    //END: Already Signed  -------------------------------------------
    

    return[
      'user'=>$oUser,  
      'name'=>$oUser->name,  
      'uF_start'=>$uF_start,  
      'uF_end'=>$uF_end,  
      'text'=>$text,  
      'tit' =>$tit,  
      'url' =>"/firmar-contrato/$code/$control", 
      'error' => null,
      'sign' => false
    ];
  }

}
