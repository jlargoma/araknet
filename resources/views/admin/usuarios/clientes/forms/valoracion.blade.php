<?php
$count = 1;
?>

<h3 class="text-left">VALORACIÓN DE SALUD Y PREPARACIÓN AL ENTRENAMIENTO
</h3>
<form class="row formValora" action="{{ url('/admin/clientes/setValora') }}" method="post">
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <input type="hidden" name="id" value="{{ $customer->id }}">
  <div class="col-md-6 ">
    <div class="form-material mt-3">
      <input class="form-control autosaveValora" type="text" id="name" name="name" required value="<?php echo $customer->name ?>">
      <label for="name">NOMBRE</label>
    </div>
    <div class="form-material mt-3">
      <input class="form-control autosaveValora" type="number" id="valora_years" name="valora_years"  maxlength="9" value="{{$valora['valora_years']}}">
      <label for="phone">EDAD</label>
    </div>
    <div class="form-material mt-3">
      <input class="form-control autosaveValora" type="date" id="valora_date" name="valora_date" value="{{$valora['valora_date']}}">
      <label for="phone">FECHA DE VALORACIÓN</label>
    </div>
  </div>
  <div class="col-md-6 ">
    <div class="form-material mt-3">
      <input type="text" id="lastname" class="form-control autosaveValora" name="valora_lastname" value="{{$valora['valora_lastname']}}">
      <label>APELLIDOS</label>
    </div>
    <div class="form-material mt-3">
      <input class="form-control autosaveValora" type="text" id="valora_tutor" name="valora_tutor" value="{{$valora['valora_tutor']}}">
      <label for="name">*FIRMA DEL TUTOR (en menores de edad)</label>
    </div>
  </div>

  <div class="col-md-12 mt-1">
    <h4 class="mt-1">ANTECEDENTES MÉDICOS Y DE ACTIVIDAD </h4>
    <u>Por favor, conteste a estas preguntas con sinceridad y de forma detallada cuando proceda:</u>
    @foreach($valora['qstion1'] as $i=>$q)
    <div class="form-material mt-3">
      <input type="text" id="{{$i}}" class="form-control autosaveValora" name="{{$i}}" value="{{$valora[$i]}}">
      <label>{{$count.'. '.$q}}</label>
      <?php $count++; ?>
    </div>
    @endforeach
  </div>
  <div class="col-md-12 mt-1">
    <h4 class="mt-1">ECÓGRAFO: VALORACIÓN ABDOMINAL Y ACTIVACIÓN DEL TRANSVERSO</h4>
    @foreach($valora['qstion2'] as $i=>$q)
    <div class="form-material mt-3">
      <textarea id="{{$i}}" class="form-control autosaveValora" name="{{$i}}">{{$valora[$i]}}</textarea>
      <label>{{$q}}</label>
    </div>
    @endforeach
  </div>
  <div class="col-md-12 mt-1">
    <h4 class="mt-1">COMPOSICIÓN CORPORAL Y MEDICIONES</h4>
    <div class="col-md-6 mt-1">
      <table class="table">
        <tr><th colspan="2">BIOIMPEDANCIA</th></tr>
        @foreach($valora['qstion3'] as $i=>$q)
        <tr>
          <td>{{$q}}</td>
          <td><input type="text" id="{{$i}}" class="form-control autosaveValora" name="{{$i}}" value="{{$valora[$i]}}"></td>
        </tr>
        @endforeach
      </table>
    </div>
    <div class="col-md-6 mt-1">
      <table class="table">
        <tr><th colspan="2">PERIMETRO (CENTÍMETROS)</th></tr>
        @foreach($valora['qstion4'] as $i=>$q)
        <tr>
          <td>{{$q}}</td>
          <td><input type="text" id="{{$i}}" class="form-control autosaveValora" name="{{$i}}" value="{{$valora[$i]}}"></td>
        </tr>
        @endforeach
      </table>
    </div>
    <div class="col-md-12 mt-1">
      <table class="table">
        <tr>
          @foreach($valora['qstion5'] as $i=>$q)
          <th>{{$q}}</th>
          @endforeach
        </tr>
        <tr>
          @foreach($valora['qstion5'] as $i=>$q)
          <td><input type="text" id="{{$i}}" class="form-control autosaveValora" name="{{$i}}" value="{{$valora[$i]}}"></td>
          @endforeach
        </tr>
      </table>
    </div>
  </div>
  <div class="col-md-12 mt-1 movilidad">
    <h4 class="mt-1">MOVILIDAD</h4>
    @foreach($valora['qstion6'] as $i=>$q)
    <div class="form-material mt-3">
      <h5>{{$q}}</h5>
      <p>{{$valora['qstion6_stext'][$i]}}</p>
      <textarea id="{{$i}}" class="form-control autosaveValora" name="{{$i}}">{{$valora[$i]}}</textarea>
      <?php $count++; ?>
    </div>
    @endforeach
  </div>
  <div class="col-md-12 mt-1">
    <h4 class="mt-1">FUERZA DE TREN SUPERIOR</h4>
    <h5>TEST: PUSHUP </h5>
    <p>El objetivo de la prueba es valorar la fuerza y resistencia del tren superior.</p>
    <p>Se debe realizar el mayor número posible de flexiones en una única serie.</p>
    <table class="table">
      @foreach($valora['qstion7'] as $i=>$q)
      <tr>
        <td>{{$q}}</td>
        <td><input type="text" id="{{$i}}" class="form-control autosaveValora" name="{{$i}}" value="{{$valora[$i]}}"></td>
      </tr>
      @endforeach
    </table>
    <h5>ESTRATIFICACIÓN DE LA POBLACIÓN EN BASE AL RESULTADO</h5>
    <img src="/img/valoracion/trenSupH.png">
    <img src="/img/valoracion/trenSupM.png">
  </div>
  <div class="col-md-12  mt-1">
    <h4 class="mt-1">FUERZA DE TREN INFERIOR</h4>
    <h5>TEST: SIT TO STAND </h5>
    <p>El objetivo de esta prueba es valorar la fuerza y capacidad funcional del tren inferior.</p>
    <p>Hay que levantarse y sentarse en una silla el mayor número de veces por minuto.</p>
    <table class="table">
      @foreach($valora['qstion8'] as $i=>$q)
      <tr>
        <td>{{$q}}</td>
        <td><input type="text" id="{{$i}}" class="form-control autosaveValora" name="{{$i}}" value="{{$valora[$i]}}"></td>
      </tr>
      @endforeach
    </table>
    <h5>ESTRATIFICACIÓN DE LA POBLACIÓN EN BASE AL RESULTADO</h5>
    <table class="table">
      <tr><th>HOMBRES</th><th>MUJERES</th></tr>
      <tr>
        <td><img src="/img/valoracion/trenInfH.png"></td>
        <td><img src="/img/valoracion/trenInfM.png"></td>
      </tr>
    </table>
    <img>
  </div>

  <div class="col-md-12  mt-1">
    <h4 class="mt-1">RESISTENCIA CARDIOVASCULAR</h4>
    <h5>TEST: SKI </h5>
    <p>Valora la capacidad cardiorrespiratoria utilizando la máquina de Ski ERG.</p>
    <p>Se parte con una potencia de 30 W y se van incrementando 30 W cada 20 segundos. La prueba finaliza pasados 2 minutos o si usted necesita detenerse antes.</p>
    <h5>RESULTADO</h5>
    <table class="table">
      @foreach($valora['qstion9'] as $i=>$q)
      <tr>
        <td>{{$q}}</td>
        <td><input type="text" id="{{$i}}" class="form-control autosaveValora" name="{{$i}}" value="{{$valora[$i]}}"></td>
      </tr>
      @endforeach
    </table>
    <div class="bold"> 
      * CALIFICACIÓN DEL RESULTADO<br>
      - Por debajo de 1 minuto: POCA CAPACIDAD<br>
      - 1 minuto: RESISTENCIA MODERADA<br>
      - Entre 1´30¨ y 1´59: RESISTENCIA ACEPTABLE<br>
      - 2 minutos: BUENA CONDICIÓN FÍSICA.<br>
    </div>
  </div>
  <div class="col-md-12  mt-1">
    <h4 class="mt-1">CONCLUSIONES Y RECOMENDACIONES GENERALES</h4>
    <textarea class="form-control autosaveValora" name="valora_concl">{{$valora['valora_concl']}}</textarea>
  </div>
  <div class="col-md-12  mt-1">
    <h4 class="mt-1">FIRMA Y DNI DE CLIENTE<br/>(o padre/madre/tutor legal en caso de menores de edad)</h4>
    <div class="boxSign">
      @if($valora['valora_sign'])
      <img src="/admin/cliente/sign/{{$valora['valora_sign']}}" >
      <input type="checkbox" name="delSign">Borrar firma
      @else
      <p>Documento no Firmado</p>
      @endif
      
    </div>
    <div class="form-inline text-center mt-1">
    <label>DNI</label>
    <input type="text" class="form-control autosaveValora" name="valora_dni" value="{{$valora['valora_dni']}}" placeholder="DNI">
    </div>
  </div>
  <div class="col-md-12  mt-1">
    <button class="btn btn-success" type="submit">
      <i class="fa fa-floppy-o" aria-hidden="true"></i> Actualizar
    </button>
    <button type="button" title="Enviar / Re-enviar mail de valoración" class="btn btn-info sendValora">
        <i class="fa fa-envelope"></i> Enviar
      </button>
    <a class="btn btn-default" type="button" href="{{$valora['url']}}" target="_blank">
      <i class="fa fa-link" aria-hidden="true"></i> Abrir firma
    </a>
    <a class="btn btn-success" href="{{$valora['url_dwnl']}}" target="_blank" >
    <i class="fa fa-file" aria-hidden="true"></i> Imprimir / Descargar
  </a>
  </div>
</form>
<style>
  .formValora img{
    max-width: 100%;
  }

  .formValora h4.mt-1 {
    font-weight: bold;
    color: #f7f7f7;
    background-color: #46c37b;
    border-color: #34a263;
    padding: 6px;
    text-align: center;
    margin-bottom: 1em;
  }
  .formValora th {
    font-size: 1.32em !important;
    background-color: #46c37b;
    border-color: #34a263;
    padding: 6px !important;
    text-align: center;
    margin-bottom: 1em;
  }
  .formValora u{
    color: #000;
    font-weight: bold;
    text-align: center;
    font-size: 1.1em;
    display: block;
  }
  .formValora h5{
    color: #000;
    font-weight: bold;
    text-align: center;
    font-size: 1.1em;
    display: block;
    background-color: #46c37b;
    padding: 6px;
  }
  .formValora p{
    text-align: center;
    background-color: #92d050;
    color: #000;
    padding: 6px;
    margin: 0px;

  }
  .formValora .bold{
    text-align: center;
    font-weight: bold;
    color: #000;
  }
.formValora .boxSign {
    min-height: 10em;
    text-align: center;
    border: 1px solid #c3c3c3;
}
  .formValora textarea.form-control autosaveValora{
    min-height: 8em;
  }

</style>