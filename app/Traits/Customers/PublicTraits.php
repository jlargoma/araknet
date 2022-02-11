<?php

namespace App\Traits\Customers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Customers;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Services\ContractsService;

trait PublicTraits {

  function getSign($file) {

    $path = storage_path('/app/signs/' . $file);
    if (!File::exists($path)) {
      abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = \Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
  }

  function signContract($code, $control) {
    
    $sContract = new ContractsService();
    $dView = $sContract->getContractData($code, $control);
    $oCustomer = $dView['oCustomer'];
    $key = $dView['key'];
    
    // Already Signed  -------------------------------------------
    $fileName = $oCustomer->getMetaContent('file_' . $key);
    $path = storage_path('app/' . $fileName);
    if ($fileName && File::exists($path)) {
      return view('customer.contract', [
          'sign' => true,
          'tit' => $dView['tit'],
          'signAdmin' => '',
          'url' => "/ver-contrato/$code/$control",
      ]);
    }
    // Already Signed  -------------------------------------------



    $sMails = new \App\Services\MailsService();
    $text = $sMails->getText('contracts_' . $key, $oCustomer);
    $pathAdmin = storage_path('/app/signsAdmin.png');
    $signAdmin = File::get($pathAdmin);

    return view('customer.contract', [
        'name' => $oCustomer->name,
        'sign' => false,
        'tit' => $dView['tit'],
        'text' => $text,
        'signAdmin' => $signAdmin,
        'url' => "/firmar/$code/$control",
    ]);
  }

  function seeContract($code, $control) {
    $sContract = new ContractsService();
    $data = $sContract->getContractData($code, $control);
    $oCustomer = $data['oCustomer'];
    

    // Already Signed  -------------------------------------------
    $fileName = $oCustomer->getMetaContent('file_' . $data['key']);
    $path = storage_path('app/' . $fileName);
    if ($fileName && File::exists($path)) {
       return response()->file($path, [
                  'Content-Disposition' => str_replace('%name', $data['tit'], "inline; filename=\"%name\"; filename*=utf-8''%name"),
                  'Content-Type' => 'application/pdf'
      ]);
    }
    
    abort(404);
    exit();
  }

  function signContractSave(Request $req, $code, $control) {

    $sContract = new ContractsService();
    $dView = $sContract->getContractData($code, $control);
    $oCustomer = $dView['oCustomer'];
    $key = $dView['key'];
    $tit = $dView['tit'];
    $dView['customerName'] = $oCustomer->name;
    
    $sMails = new \App\Services\MailsService();
    $dView['text'] = $sMails->getText('contracts_' . $key, $oCustomer);
    
    /* ------------------------ */
    $sign = $req->input('sign');
    $encoded_image = explode(",", $sign)[1];
    $decoded_image = base64_decode($encoded_image);

    //Signs -------------------------------------------
    $dView['signFile'] = $encoded_image;
    $path = storage_path('/app/signsAdmin.png');
    $file = File::get($path);
    $dView['signAdmin'] = base64_encode($file);

    //PDF -------------------------------------------
    $pdf = \App::make('dompdf.wrapper');
    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->loadView('customer.contractDownl', $dView);
    $output = $pdf->output();
//        return $pdf->download('invoice.pdf');
//    return $pdf->stream();
    //save document
    $fileName = 'contracts/' . $key . '-' . $oCustomer->id . '-' . time() . '.pdf';
    $path = storage_path('/app/' . $fileName);

    $oCustomer->setMetaContent('file_' . $key, $fileName);

    $storage = \Illuminate\Support\Facades\Storage::disk('local');
    $storage->put($fileName, $output);

    //---------------------------------------------------
    // Send Mail

    $subject = $tit;
    $mailContent = 'Hola ' . $oCustomer->name . ', <br/><br/>';
    $mailContent .= '<p>Gracias por firmar su <b>' . $tit . '</b>.</b></p>';
    $mailContent .= '<p>Le adjuntamos el documento firmado.</p>';
    $mailContent .= '<br/><br/><br/><p>Muchas Gracias.!</p>';
    $email = $oCustomer->email;
    try {
      $fileName = $subject;
      \Illuminate\Support\Facades\Mail::send('emails.base', [
          'mailContent' => $mailContent,
          'tit' => $subject
              ], function ($message) use ($subject, $email, $path, $fileName) {
                $message->from(config('mail.from.address'), config('mail.from.name'));
                $message->subject($subject);
                $message->to($email);
                $message->bcc(config('mail.from.address'));
                $message->attach($path, array(
                    'as' => $fileName . '.pdf',
                    'mime' => 'application/pdf'));
              });

      return back()->with(['success' => 'Firma Guardada']);
    } catch (\Exception $e) {
      return $e->getMessage();
    }
    //---------------------------------------------------
  }

}
