<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CustomersRates;
use App\Models\Rates;
use Illuminate\Support\Facades\DB;
include_once app_path().'/Functions.php';

class Tasks extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'Tasks:start';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Realizar tareas por consola';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
      
//      $oLst = DB::table('user_meta')->where('meta_key', 'FIDELITY')->get();
//  
//      
//      if ($oLst){
//        foreach ($oLst as $item){
//          $exists = DB::table('user_meta')
//                  ->where('meta_key', 'plan')
//                  ->where('user_id',$item->user_id)->first();
//          
//          $plan =  ($item->meta_value == 1) ? 'fidelity' : 'basic';
//          
////              dd($exists,$plan);
//          if (!$exists){
//            DB::table('user_meta')->insert([
//                'meta_key' => 'plan',
//                'user_id'=>$item->user_id,
//                'meta_value'=>$plan
//            ]);
//          }
//        }
//      }
  }
  
}
