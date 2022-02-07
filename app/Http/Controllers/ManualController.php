<?php

namespace App\Http\Controllers;

use App\Http\Requests;
class ManualController extends Controller
{
      
  public function bonos() {
    return view('manuals.bonos');
  }
  public function citas() {
    return view('manuals.citas');
  }
}

