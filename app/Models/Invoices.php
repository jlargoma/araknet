<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoices extends Model
{
  
  use SoftDeletes; //Implementamos 
      
  protected $dates = ['deleted_at']; //Registramos la nueva columna
   
  public function getNumAttribute(){
    $numb = '';
    if ( $this->code && trim($this->code) != '')
      $numb = '#'.$this->code.' / ';
    
    return $numb.str_pad($this->number, 5, "0", STR_PAD_LEFT);
    
  }
  
  public function emisor($emisor = null) {
    $emisores = [
      'ISDE' => [
          'name' => 'GET FIT VILLAVICIOSA SL',
          'nif' => 'B87457206',
          'address' => 'avda Quitapesares n20 28670 ',
          'phone' => -1,
          'zipcode' => 'Villaviciosa de Odon (Madrid)',
      ],
    ];
    
    if ($emisor){
      return isset($emisores[$emisor]) ? $emisores[$emisor] : null;
    }
    
    return $emisores;
  }
  
  
  /**********************************************************************/
  /////////  invoice_meta //////////////
  public function setMetaContent($key,$content) {
    
    
    $updated =  DB::table('invoices_meta')->where('invoice_id',$this->id)
              ->where('meta_key',$key)
              ->update(['meta_value' => $content]);

    if (!$updated) {
      DB::table('invoices_meta')->insert(
            ['invoice_id' => $this->id, 'meta_key' => $key,'meta_value' => $content]
        );
    }
  }
  public function getMetaContent($key) {
    
    $book_meta = DB::table('invoices_meta')
            ->where('invoice_id',$this->id)->where('meta_key',$key)->first();
    
    if ($book_meta) {
      return $book_meta->meta_value;
    }
    return null;
  }
  public function getMetaObj($key) {
    return DB::table('invoices_meta')
            ->where('invoice_id',$this->id)->where('meta_key',$key)->get();
    
  }
   public function deleteMetaContent($key) {
    DB::table('invoices_meta')
            ->where('invoice_id',$this->id)->where('meta_key',$key)->delete();
  }
}
