<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SettingsController extends Controller {

  
  /**
   * Get messages page
   */
  public function messages($key = 'pago_cuota_mes') {
    //get all emial's options
    $lstKeys = Settings::getKeysTxtMails();

    //get from DB all messages
    $data = ['es' => null, 'en' => null, 'es_ota' => null, 'en_ota' => null];
    $content = Settings::getContent($key);
    include_once app_path('Helps/VariablesTxts.php');
    $kWSP = Settings::getKeysWSP();
    $ckeditor = true;
    if (in_array($key, $kWSP))
      $ckeditor = false;
    
    return view('settings.txt-email', [
        'lstKeys' => $lstKeys,
        'content' => $content,
        'key' => $key,
        'varsTxt' => $varsTxt,
        'ckeditor' => $ckeditor,
        'kWSP' => $kWSP,
    ]);
  }

  /**
   * Save the email template setting
   *
   * @param Request $request
   * @return type
   */
  public function messages_upd(Request $request, $lng = 'es') {

    $key = $request->input('key');
    $sNames = Settings::getKeysTxtMails();
    $n = isset($sNames[$key]) ? $sNames[$key] : '';

    $text = $request->input($key, null);
    if ($text)   $this->saveTextMails($key,$n, $text);

    return back()->with('status', 'Setting updated!');
  }

  private function saveTextMails($key, $name, $text) {
    $Object = Settings::where('key', $key)->first();
    if ($Object) {
      $Object->content = $text;
      $Object->save();
    } else {

      $Object = new Settings();
      $Object->key = $key;
      $Object->name = $name;
      $Object->value = 0;
      $Object->content = $text;
      $Object->save();
    }
  }

  /**
   * /test-text/es/text_payment_link
   * @param type $lng
   */
  function testText($key=null){
    
    $lstKeys = Settings::getKeysTxtMails();
    $name = isset($lstKeys[$key]) ? $lstKeys[$key] : $key;
   
    $all = null;
    //---------------------------------------------------------// 
    if ($key){
      $all[$key] = Settings::getContent($key);
    } else {
      foreach ($lstKeys as $k1=>$v1)
      $keysValue = Settings::whereIn('key', array_keys($lstKeys))->get();
      foreach ($keysValue as $item) {
        $all[$item->key] = $item->content;
      }
    }
    //---------------------------------------------------------// 
    include_once app_path('Helps/VariablesTxts.php');
    return view('settings.test-txt-email', [
        'all' => $all,
        'key' => $key,
        'name' => $name,
        'varsTxt'=>$varsTxt,
        'lstKeys' => $lstKeys,
    ]);
  }
}
