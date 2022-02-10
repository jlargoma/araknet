<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rates extends Model
{
  const noShow = [];
    public function users()
    {
        return $this->hasMany('\App\Models\CustomersRates','id', 'rate_id');
    }

    public function typeRate()
    {
        return $this->hasOne('\App\Models\TypesRate','id', 'type');
    }
        
    static function getByTypeRate($type){
        return self::select('rates.*')
                ->join('types_rate','rates.type','=','types_rate.id')
                ->where('status',1)
                ->whereNotIn('rates.id',self::noShow)
                ->where('types_rate.type',$type)->get();
    }
    static function getByTypeRateID($id){
        return self::select('rates.*')
                ->join('types_rate','rates.type','=','types_rate.id')
                ->where('types_rate.id',$id)->get();
    }
    static function getRatesTypeRates(){
        return self::join('types_rate','rates.type','=','types_rate.id')
                ->pluck('types_rate.name','rates.id')->toArray();
    }
    
    /**
     * Get Rates group by Type
     * 
     * @param type $name => just name and ID
     * @return type
     */
    static function getTypeRatesGroups($name=true){
      
      $rateFilter = [];
      $oTypes = \App\Models\TypesRate::orderBy('name', 'asc')->get();
      foreach ($oTypes as $item){
        $aux  = Rates::where('type',$item->id)
                ->whereNotIn('id',self::noShow)
                ->orderBy('name', 'asc')->get();
        if ($name){
          $aux2 = [];
          foreach ($aux as $a){
            $aux2[$a->id] = $a->name;
          }
          $rateFilter[$item->id] = ['n' => $item->name,'l'=>$aux2];
        } else {
          $rateFilter[$item->id] = ['n' => $item->name,'l'=>$aux];
        }
      }
      return $rateFilter;
    }
}
