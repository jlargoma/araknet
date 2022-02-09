<h2 class="text-center"><?php echo ($id > 0) ? 'Editar Cita' : 'Nueva Cita' ?></h2>
<form action="{{ url('/admin/citas/create') }}" method="post" id="formEdit">
  @if($id>0)            			
  <input type="hidden" name="idDate" value="{{$id}}">
  @endif
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <input type="hidden" name="date_type" value="{{$date_type}}">

  <div class="row">
    <div class="col-xs-12 col-md-4 push-20">
      @if($id<1) 
      <label for="customer_id" id="tit_user">
        <i class="fa fa-plus" id="newUser"></i>Cliente
        <span><input type="checkbox" id="is_group" name="is_group">Es un Grupo</span>
      </label>
      <div id="div_user">
        <select class="js-select2 form-control" id="customer_id" name="customer_id" style="width: 100%; cursor: pointer" data-placeholder="Seleccione usuario.."  >
          <option></option>
          <?php foreach ($customers as $key => $customer): ?>

            <option value="<?php echo $customer->id; ?>" <?php if (isset($customer_id) && $customer_id == $customer->id) echo 'selected' ?>>
              <?php echo $customer->name; ?>
            </option>
          <?php endforeach ?>
        </select>
      </div>
      <input class="form-control" type="text" id="u_name" name="u_name" placeholder="Nombre del usuario" style="display:none"/>
      @else
      <input type="hidden" name="customer_id" id="customer_id" value="{{$oUser->id}}">
      <label for="customer_id" id="tit_user">Cliente</label>
      <input class="form-control" value="{{$oUser->name}}" disabled=""/>
      @endif


    </div>
    <div class="col-xs-12 col-md-4 push-20">
      <label for="id_email">Email</label>
      <input class="form-control" type="email" id="NC_email" name="email" placeholder="email" value="{{$email}}"/>
    </div>
    <div class="col-xs-12 col-md-4 push-20">
      <label for="id_email">Teléfono</label>
      <input class="form-control" type="text" id="NC_phone" name="phone" placeholder="Teléfono" value="{{$phone}}"/>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-4 col-md-2  push-20">
      <label for="date">Fecha</label>
      <input class="js-datepicker form-control" value="{{$date}}" type="text" id="date" name="date" placeholder="Fecha y hora..." style="cursor: pointer;" data-date-format="dd-mm-yyyy"/>
    </div>
    <div class="col-xs-3 col-md-1 not-padding  push-20">
      <label for="customer_id">hora</label>
      <select class="form-control" id="hour" name="hour" style="width: 100%;" data-placeholder="hora" required >
        <?php for ($i = 8; $i <= 22; $i++) : ?>
          <?php
          if ($i < 10) {
            $hour = "0" . $i;
          } else {
            $hour = $i;
          }
          ?>
          <option value="<?php echo $hour ?>" <?php if ($time == $i) echo 'selected'; ?>>
            <?php echo $hour ?>: 00
          </option>
        <?php endfor; ?>

      </select>
    </div>
    <div class="col-xs-4 col-md-1  push-20">
      <label for="date">Hora Exacta</label>
      <input class="form-control" type="time" value="{{$customTime}}" type="text" id="customTime" name="customTime">
    </div>
    <div class="col-xs-6 col-md-2 push-20">
      <label for="user_id">{{$date_type_u}}</label>
      <select class="js-select2 form-control" id="user_id" name="user_id" style="width: 100%; cursor: pointer" data-placeholder="Seleccione coach.." >
        <option></option>
        <?php foreach ($coachs as $key => $coach): ?>
          <option value="<?php echo $coach->id; ?>" <?php if (isset($user_id) && $user_id == $coach->id) echo 'selected' ?>>
            <?php echo $coach->name; ?>
          </option>
        <?php endforeach ?>
      </select>

    </div>
    <div class="col-xs-6 col-md-4 push-20">
      <label for="id_type_rate">Servicio</label>
      <select class="js-select2 form-control" id="rate_id" name="rate_id" style="width: 100%;" data-placeholder="Seleccione un servicio" required >
        <option></option>
        <?php foreach ($services as $key => $service): ?>
          <option value="<?php echo $service->id; ?>" data-price="<?php echo $service->price ?>" <?php if (isset($id_serv) && $id_serv == $service->id) echo 'selected' ?>>
            <?php echo $service->name; ?>
          </option>
        <?php endforeach ?>
      </select>
    </div>
    <div class="col-xs-6 col-md-2 push-20">
      <label for="importeFinal">Precio</label>
      <input id="importeFinal" type="number" step="0.01" name="importe" class="form-control"  value="{{$price}}">
    </div>

  </div>

</form>

<div class=" row">
  <div class="col-xs-12 text-center">
    @if($id>0)   
    <button class="btn btn-lg btn-user" type="button" data-idUser="{{$customer_id}}">
      Ficha Usuario
    </button>
    @endif
    <button class="btn btn-lg btn-success sendForm" data-id="formEdit"  type="button" >
      Guardar
    </button>
    @if($id>0)   
    <button class="btn btn-lg btn-danger btnDeleteCita" type="button">
      Eliminar
    </button>
    <a href="/admin/citas/duplicar/{{$id}}" class="btn btn-lg btn-secondary">
      Duplicar
    </a>
    
    @if($id>0 && $date_type == 'fisio')
    <div class="block-icons">
      <div class="ecografo <?php echo (isset($ecogr) && $ecogr == 1) ? "active" : ''; ?>" data-id="{{$id}}" title="ecógrafo" >
        <img src="/img/ecog-gris.png" class="grey"  alt="Sin ecógrafo">
        <img src="/img/ecog.png" class="blue"  alt="ecógrafo">
      </div>
      <div class="indiba <?php echo (isset($indiba) && $indiba == 1) ? "active" : ''; ?>" data-id="{{$id}}"  title="indiba">
        <img src="/img/indiba-gris.png" class="grey" alt="Sin indiba">
        <img src="/img/indiba.png" class="blue"  alt="indiba">
      </div>
    </div>
    @endif
    @endif
    
    </div>  
    @if($id>0 && $date_type == 'nutri')
    <div class="col-xs-12 text-center">
      @if(isset($encNutr))
      <a href="/admin/ver-encuesta/{{$btnEncuesta}}" class="btn btn-lg btn-info" target="_black">
          Ver encuesta
        </a>
        <button class="btn btn-lg btn-success clearEncuesta" data-id="{{$customer_id}}"  type="button" >
          Vaciar encuesta
        </button>
      @else
        <button class="btn btn-lg btn-success sendEncuesta" data-id="{{$customer_id}}"  type="button" >
          Reenviar encuesta
        </button>
      @endif
      </div>
    @endif
  
</div>

@if(!$charge && $id>0)
<div class="row">
  @include('calendars.cobrar')
</div>
@endif
  @if($id>0 && $charge)
  <div class="tpayData ">
    <table class="table">
      <tr>
        <td colspan="2" class="success">Cobrado</td>
      </tr>
      <tr>
        <th>Fecha:</th>
        <td>{{dateMin($charge->date_payment)}}</td>
      </tr>
      <tr>
        <th>Desc.:</th>
        <td>{{$charge->discount}}%</td>
      </tr>
      <tr>
        <th>Precio:</th>
        <td>{{moneda($charge->import)}}</td>
      </tr>
      <tr>
        <th>Metodo:</th>
        <td>{{payMethod($charge->type_payment)}}</td>
      </tr>
    </table>
  </div>
  @endif
