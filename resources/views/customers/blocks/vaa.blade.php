<?php
$count = 1;
?>
<table class="formValora">
  <tr>
    <td colspan="2"><label for="name">NOMBRE</label>
      <div class="field">{{$user->name}}</div></td>
    <td colspan="2"> <label>APELLIDOS</label>
      <div class="field">{{$valora['valora_lastname']}}</div></td>
  </tr>
  <tr>
    <td colspan="2">  <label for="telefono">EDAD</label>
      <div class="field">{{$valora['valora_years']}}</div></td>
    <td colspan="2"><label for="name">*FIRMA DEL TUTOR (en menores de edad)</label>
      <div class="field">{{$user->dni}}</div></td>
  </tr>
  <tr>
    <td colspan="2"> <label for="telefono">FECHA DE VALORACIÓN</label>
      <div class="field">{{convertDateToShow($valora['valora_date'],true)}}</div></td>
    <td colspan="2"></td>
  </tr>
  <tr>
    <td colspan="4">
      <h4 class="mt-1">ANTECEDENTES MÉDICOS Y DE ACTIVIDAD </h4>
      <u>Por favor, conteste a estas preguntas con sinceridad y de forma detallada cuando proceda:</u>
    </td>
  </tr>
  @foreach($valora['qstion1'] as $i=>$q)
  <tr>
    <td colspan="4">
      <label>{{$count.'. '.$q}}</label>
      <div class="field">{{$valora[$i]}}</div>
      <?php $count++; ?>
      </div>
    </td>
  </tr>
  @endforeach
  <tr>
    <td colspan="4"></td>
  </tr>
  <tr>
    <td colspan="4"><h4 class="mt-1">ECÓGRAFO: VALORACIÓN ABDOMINAL Y ACTIVACIÓN DEL TRANSVERSO</h4></td>
  </tr>
  @foreach($valora['qstion2'] as $i=>$q)
  <tr>
    <th>{{$q}}</th>
    <td colspan="3"><?php echo nl2br($valora[$i]); ?></td>
  </tr>
  @endforeach
  <tr>
    <td colspan="4"><h4 class="mt-1">COMPOSICIÓN CORPORAL Y MEDICIONES</h4></td>
  </tr>

  <tr>
    <td colspan="2">
      <table class="table">
        <tr><th colspan="2">BIOIMPEDANCIA</th></tr>
        @foreach($valora['qstion3'] as $i=>$q)
        <tr>
          <td>{{$q}}</td>
          <td><div class="field"><?php echo nl2br($valora[$i]); ?></div></td>
        </tr>
        @endforeach
      </table>
    </td>
    <td colspan="2">
      <table class="table">
        <tr><th colspan="2">PERIMETRO (CENTÍMETROS)</th></tr>
        @foreach($valora['qstion4'] as $i=>$q)
        <tr>
          <td>{{$q}}</td>
          <td><div class="field"><?php echo nl2br($valora[$i]); ?></div></td>
        </tr>
        @endforeach
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="4"></td>
  </tr>
  <tr>
    @foreach($valora['qstion5'] as $i=>$q)
    <th>{{$q}}</th>
    @endforeach
  </tr>
  <tr>
    @foreach($valora['qstion5'] as $i=>$q)
    <td><div class="field"><?php echo nl2br($valora[$i]); ?></div></td>
    @endforeach
  </tr>
  <tr class="movilidad">
    <td colspan="4"><h4 class="mt-1">MOVILIDAD</h4></td>
  </tr>
  
  @foreach($valora['qstion6'] as $i=>$q)
  <tr class="movilidad">
    <td colspan="4">
    <h5>{{$q}}</h5>
    <p>{{$valora['qstion6_stext'][$i]}}</p>
    <div class="field txt"><?php echo nl2br($valora[$i]); ?></div>
   </td>
  </tr>
  @endforeach
  
  
      
   
  
  <tr>
    <td colspan="4"></td>
  </tr>
  
  <tr>
    <td colspan="4"></td>
  </tr>
  
  <tr>
    <td colspan="4"></td>
  </tr>
  
  
</table>


<div class="col-md-12 mt-1 movilidad">
  
  @foreach($valora['qstion6'] as $i=>$q)
  <div class="form-material mt-2">
    <h5>{{$q}}</h5>
    <p>{{$valora['qstion6_stext'][$i]}}</p>
    <div class="field txt"><?php echo nl2br($valora[$i]); ?></div>
  </div>
  <br/><br/>
  @endforeach
</div>
<div class="col-md-12 mt-1 printBreak">

  <br/><br/>
</div>
<div class="block-logo">
  <img src="/assets/logo/logo.svg" alt="Araknet" class="logo"/>
</div>
<div class="col-md-12 mt-1">
  <h4 class="mt-1">FUERZA DE TREN SUPERIOR</h4>
  <h5>TEST: PUSHUP </h5>
  <p>El objetivo de la prueba es valorar la fuerza y resistencia del tren superior.<br/>
    Se debe realizar el mayor número posible de flexiones en una única serie.</p>
  <table class="table ">
    @foreach($valora['qstion7'] as $i=>$q)
    <tr>
      <td class="td_1">{{$q}}</td>
      <td><div class="field"><?php echo nl2br($valora[$i]); ?></div></td>
    </tr>
    @endforeach
  </table>
  <h5>ESTRATIFICACIÓN DE LA POBLACIÓN EN BASE AL RESULTADO</h5>
  <img src="/img/valoracion/trenSupH.png">
  <img src="/img/valoracion/trenSupM.png">
</div>
<div class="col-md-12 mt-1 printBreak">

  <br/><br/>
</div>
<div class="block-logo">
  <img src="/assets/logo/logo.svg" alt="Araknet" class="logo"/>
</div>
<div class="col-md-12  mt-1">
  <h4 class="mt-1">FUERZA DE TREN INFERIOR</h4>
  <h5>TEST: PUSHUP </h5>
  <p>El objetivo de la prueba es valorar la fuerza y resistencia del tren inferior.<br/>
    Se debe realizar el mayor número posible de flexiones en una única serie.</p>
  <table class="table">
    @foreach($valora['qstion8'] as $i=>$q)
    <tr>
      <td class="td_1">{{$q}}</td>
      <td><div class="field"><?php echo nl2br($valora[$i]); ?></div></td>
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
<div class="col-md-12 mt-1 printBreak">

  <br/><br/>
</div>
<div class="block-logo">
  <img src="/assets/logo/logo.svg" alt="Araknet" class="logo"/>
</div>
<div class="col-md-12  mt-1">
  <h4 class="mt-1">RESISTENCIA CARDIOVASCULAR</h4>
  <h5>TEST: SKI </h5>
  <p>
    Valora la capacidad cardiorrespiratoria utilizando la máquina de Ski ERG.
    <br/>
    Se parte con una potencia de 30 W y se van incrementando 30 W cada 20 segundos. La prueba finaliza pasados 2 minutos o si usted necesita detenerse antes.
  </p>
  <h5>RESULTADO</h5>
  <table class="table">
    @foreach($valora['qstion9'] as $i=>$q)
    <tr>
      <td class="td_1">{{$q}}</td>
      <td><div class="field"><?php echo nl2br($valora[$i]); ?></div></td>
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
<div class="col-md-12 mt-1 printBreak">

  <br/><br/>
</div>
<div class="block-logo">
  <img src="/assets/logo/logo.svg" alt="Araknet" class="logo"/>
</div>
<div class="col-md-12  mt-1">
  <h4 class="mt-1">CONCLUSIONES Y RECOMENDACIONES GENERALES</h4>
  <div class="field txt">{{$valora['valora_concl']}}</div>
</div>
<div class="col-md-12  mt-1" style="text-align: center;">
  <h4 class="mt-1">FIRMA Y DNI DE CLIENTE<br/>(o padre/madre/tutor legal en caso de menores de edad)</h4>
  @if(isset($sign_donwl))
  <img src="/admin/usuarios/sign/{{$valora['valora_sign']}}" class="boxSign">
  @endif
  <div class="fieldSing">DNI:{{$valora['valora_dni']}}</div>
</div>
</div>
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
    font-size: 1em;
    display: block;
    margin-top: -12px;
  }
  .formValora h5{
    color: #000;
    font-weight: bold;
    text-align: center;
    font-size: 1.1em;
    display: block;
    background-color: #46c37b;
    padding: 6px;
    margin-bottom: 0;
  }
  .formValora p{
    text-align: center;
    background-color: #92d050;
    color: #000;
    padding: 6px;
    margin: 0px 0px 1em;

  }
  .formValora .bold{
    text-align: center;
    font-weight: bold;
    color: #000;
    margin-bottom: 2em;
  }
  .formValora .boxSign{
    height: 10em;
    border: 1px solid #c3c3c3;
  }
  .formValora .field {
    border: 1px solid #c3c3c3;
    border-radius: 4px;
    margin: 1px 0px 10px !important;
    padding: 3px 8px;
    color: #464646;
    font-size: 13px;
    min-height: 2.1em;
  }
  .formValora label {
    font-size: 14px;
  }
  .formValora .txt {
    min-height: 7em;
  }

  .formValora .table tr {
    border: 1px solid #464646;
  }
  .formValora .table .td_1{
    width: 25%;
  }
  .formValora .tqstion2 th{
    width: 25%;
    padding: 18px 5px !important;
    font-size: 13px !important;
  }
  .formValora td {
    width: 25%;
  }

</style>