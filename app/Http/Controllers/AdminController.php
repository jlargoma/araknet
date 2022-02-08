<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Carbon\Carbon;
use Auth;
use Mail;
use App\Classes\Mobile;
use DB;
class AdminController extends Controller
{
    public function index(Request $request)
    {
    	return redirect()->action('UsersController@clientes');

    }

    public function contabilidad()
    {
        return view('admin.dashboard');
    }

    public function unauthorized()
    {
    	return view('admin.unauthorized', [ 'user' => Auth::user() ]);
    }
    
    public function changeActiveYear(Request $request)
    {
      $year = $request->input('year');
      if (is_numeric($year)){
        $current = date('Y')+3;
        if ($year<=$current && $year>($current-6)){
           setcookie('ActiveYear', $year, time() + (86400 * 30), "/"); // 86400 = 1 day
           return 'cambiado';
        }
      }
      return '';
    
    }

}
