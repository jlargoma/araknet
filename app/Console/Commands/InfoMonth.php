<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UsersSuscriptions;
use App\Models\Charges;
use Illuminate\Support\Facades\DB;
use Log;

class InfoMonth extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'InfoMonth:weekStatus';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Create weekle status';

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
  public function handle() {
    try {
      $inform = $this->getInformMonth();
      $pyg = $this->getPyG();
      $tit = 'Informe semanal';
      
      $mailContent = $pyg.'<hr/>'.$inform;
      
      $email = 'test@test.cadfd';
      
      $emails = [
        'carlos.biosca24@gmail.com',
        'jlargo@mksport.es',
        'pingodevweb@gmail.com'
      ];
      $sended = \Illuminate\Support\Facades\Mail::send('emails.base', [
              'mailContent'=> $mailContent,
              'tit'=> $tit,
          ], function ($message) use ($emails,$tit) {
              $message->subject($tit);
              $message->from(config('mail.from.address'), config('mail.from.name'));
              $message->to($emails);
          });
          
    } catch (\Exception $e) {
      dd($e);
      Log::error("Error creando suscripciones");
    }
  }

  public function getInformMonth() {
    $year = date('Y');
    $informes = new \App\Http\Controllers\InformesController();
    $data = $informes->getChargesRates($year, date('m'), null);
    $byRate = [];
    $byRateT = [];
    $aRType = \App\Models\TypesRate::all()->pluck('name', 'id')->toArray();
    $aR_RateType = \App\Models\Rates::all()->pluck('type', 'id')->toArray();
    foreach ($aR_RateType as $k => $v) {
      $byRateT[$v] = 0;
    }

    foreach ($data['charges'] as $charges) {
      if (!isset($byRate[$charges->rate_id]))
        $byRate[$charges->rate_id] = 0;

      $byRate[$charges->rate_id] += $charges->import;
      if (isset($aR_RateType[$charges->rate_id])) {
        $byRateT[$aR_RateType[$charges->rate_id]] += $charges->import;
      }
    }

    $data['aRType'] = $aRType;
    $aRates = $data['aRates'];
    $html = '<h2 class="text-center">INFORME DE CUOTAS PAGADAS AL MES</h2>';
    $html .= '<h3>POR TARIFAS</h3>';

    $html .= '<table class="t-content"><thead><tr><th>Tarifa</th><th>Total</th></tr></thead><tbody>';
    $total = 0;
    foreach ($byRate as $rate => $import) {
      $total += $import;
      $html .= '<tr><td>' . ((isset($aRates[$rate])) ? $aRates[$rate] : ' - ');
      $html .= '</td><td>' . $this->moneda($import) . '</td></tr>';
    }


    $html .= '</tbody><tfoot><tr><th>Total</th><th>' . $this->moneda($total) . '</th></tr></tfoot></table>';

    $html .= '<h3>POR FAMILIA DE TARIFAS</h3>';
    $html .= '<table class="t-content"><thead><tr><th>Tarifa</th><th>Total</th></tr></thead><tbody>';
    $total = 0;
    foreach ($byRateT as $rt => $import) {
      $total += $import;
      $html .= '<tr><td>' . ((isset($aRType[$rt])) ? $aRType[$rt] : ' - ');
      $html .= '</td><td>' . $this->moneda($import) . '</td></tr>';
    }


    $html .= '</tbody><tfoot><tr><th>Total</th><th>' . $this->moneda($total) . '</th></tr></tfoot></table>';

    return $html;
  }

  public function getPyG() {
    $year = date('Y');
    $expenses = \App\Models\Expenses::whereYear('date', '=', $year)->sum('import');
    $aIncomes = Charges::select(DB::raw('type_payment,sum(import) as cant'))
                    ->whereYear('date_payment', '=', $year)
                    ->groupBy('type_payment')->pluck('cant', 'type_payment')->toArray();
    $incomes = array_sum($aIncomes);

    $html = '<h2 class="text-center">RESUMEN PyG</h2>';
    $html .= '<table class="t-content">';
    $html .= '<tr><th>Ingresos</th><td>' . $this->moneda($incomes) . '</td></tr>';
    $html .= '<tr><th>Gastos</th><td>' . $this->moneda($expenses) . '</td></tr>';
    $html .= '<tr><th>Resultado</th><td>' . $this->moneda($incomes - $expenses) . '</td></tr>';
    $html .= '<tr><th colspan="2"><hr/></th></tr>';

    $aux = isset($aIncomes['cash']) ? $aIncomes['cash'] : 0;
    $html .= '<tr><th>Metálico</th><td>' . $this->moneda($aux) . '</td></tr>';
    $aux = isset($aIncomes['card']) ? $aIncomes['card'] : 0;
    $html .= '<tr><th>Tarjeta</th><td>' . $this->moneda($aux) . '</td></tr>';
    $aux = isset($aIncomes['banco']) ? $aIncomes['banco'] : 0;
    $html .= '<tr><th>Banco</th><td>' . $this->moneda($aux) . '</td></tr>';

    $html .= '<tr><th colspan="2"><hr/></th></tr>';

    $subsc = UsersSuscriptions::count();
    $html .= '<tr><th>Suscripciones activas</th><td>' . $subsc . '</td></tr>';
    $customers = \App\Models\User::where('status', 1)->count();
    $html .= '<tr><th>Clientes activos</th><td>' . $customers . '</td></tr>';

    $html .= '</table>';
    return $html;
  }

  function moneda($mount,$cero=true,$decimals=0){
    if ($cero)  return number_format($mount, $decimals, ',', '.' ).' €';

    if ($mount != 0) return number_format($mount, $decimals, ',', '.' ).' €';
    return '--';

  }
}
