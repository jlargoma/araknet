<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
  public function handle($request, Closure $next, $role)
  {
    if (!Auth::guest()) {
      $uRole = $request->user()->role;
     
      
      $roles = explode('|', $role);
      if (!in_array($uRole,$roles)) {
        if($request->ajax()){
          ?>
          <p><h2>Ups!! No tienes autorización para ver el contenido solicitado.</h2></p>
          <?php
          die();
                return response()->json(['status'=>'Error','msg'=>'Ocurrió un error']);
        }
//        abort(403, "No tienes autorización para ingresar.");
        return redirect('no-allowed');
      }
    } else {
      return redirect()->guest('login');
    }
    
    return $next($request);
  }
}
