<?php

namespace App\Http\Controllers;

use \App\Traits\Customers\ValoraTraits;
use \App\Traits\Customers\AdminTraits;
use \App\Traits\Customers\PublicTraits;
use \App\Traits\Customers\InformesTraits;

class CustomerController extends Controller {

  use AdminTraits,PublicTraits,ValoraTraits,InformesTraits;
  
  
}
