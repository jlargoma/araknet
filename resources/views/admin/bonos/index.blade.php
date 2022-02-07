@extends('layouts.admin-master')

@section('title') Bonos - Evolutio HTS @endsection

@section('headerButtoms')
<li class="text-center">
  <button class="btn btn-sm btn-success new-bono" data-toggle="modal" data-target="#modal-bono">
    <i class="fa fa-plus"></i> Bonos
  </button>
</li>
@endsection

@section('content')
<div class="content content-full bg-white">
      <h3 class="text-center">
        Listado de Bonos
      </h3>
      <div class="table-responsive">
        <?php if (count($objs) > 0): ?>
          <table class="table table-bordered table-striped js-dataTable-full table-header-bg">
            <thead>
              <tr>
                <th class="text-center hidden-xs hidden-sm" style="background-color: #46c37b; width: 60px;">id</th>
                <th class="text-center" style="background-color: #46c37b; min-width: 280px;">Bono</th>
                <th class="text-center" style="background-color: #46c37b;">Servicios</th>
                <th class="text-center" style="background-color: #46c37b;">Tipo Plan</th>
                <th class="text-center" style="background-color: #46c37b;">Precio</th>
                <th class="text-center" style="background-color: #46c37b">Cantidad</th>
                <th class="text-center" style="background-color: #46c37b;min-width: 10%;">Acciones</th>
              </tr>
            </thead>
            <tbody>	
              <?php foreach ($objs as $obj): ?>
                <tr>
                  <td class="text-center hidden-xs hidden-sm"><?php echo $obj->id ?></td>
                  <td class="text-center">
                    <input type="text" class="form-control editables name-bono-<?php echo $obj->id ?>"  data-id="<?php echo $obj->id; ?>" value="<?php echo $obj->name; ?>" />
                  </td>
                  <td class="text-center">
                    <select class="form-control editables rate-bono-<?php echo $obj->id ?>" data-id="<?php echo $obj->id; ?>">
                        <option value="all">Todos</option>
                        <?php 
                        foreach ($rateFilter as $k=>$v):
                          $s = ($k == $obj->rate_type)? 'selected' : '';
                          echo '<option value="'.$k.'" '.$s.' class="b">'.$v['n'].'</option>';
                          foreach ($v['l'] as $k2=>$v2):
                            $aux = "$k-$k2";
                            $s = ($k2 == $obj->rate_subf)? 'selected' : '';
                            echo '<option value="'.$aux.'" '.$s.'>&nbsp; - '.$v2.'</option>';
                          endforeach;
                        endforeach; 
                        ?>
                    </select>
                  </td>
                  <td class="text-center">
                    <span style="font-size: 23px;" class="rate_fidelity" data-k="<?php echo $obj->id ?>" data-v="<?php echo $obj->tarifa ?>">
                      <i class="fa fa-heart text-success fidelity" <?php echo ($obj->tarifa != 'fidelity') ? 'style="display:none;"' : ''; ?>></i>
                      <i class="fa fa-heart-o no_fidelity" <?php echo ($obj->tarifa == 'fidelity') ? 'style="display:none;"' : ''; ?>></i>
                    </span>
                  </td>
                  <td class="text-center">
                    <input type="text"  class="form-control editables price-bono-<?php echo $obj->id ?>" data-id="<?php echo $obj->id; ?>" value="<?php echo $obj->price; ?>" />
                  </td>
                  <td class="text-center ">
                    <input type="text" class="form-control editables qty-bono-<?php echo $obj->id ?>" data-id="<?php echo $obj->id; ?>" value="<?php echo $obj->qty; ?>" />
                  </td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="{{ url('/admin/bonos/delete/')}}/<?php echo $obj->id ?>" class="btn btn-md btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar Tarifa" onclick="return confirm('Are you sure you want to delete this item?');">
                        <i class="fa fa-times"></i>
                      </a>
                    </div>
                  </td>
                </tr>
  <?php endforeach ?>
            </tbody>
          </table>
<?php else: ?>
          <div class="col-xs-12">
            <h2 class="text-muted font-w200">
              No hay <span class="font-w600">Bonos</span> creada <span class="font-w600"></span>, por favor cree una nueva aquí
            </h2>
          </div>
      <?php endif ?>
      </div>
    </div>
      <br/><br/><hr><br/><br/>
  <div class="row mt-1em">
      <h3 class="text-center">Listado de Bonos Antiguos</h3>
      <div class="block-content oldRatesContent">
<?php if (count($old) > 0): ?>
          <table class="table table-bordered table-striped js-dataTable-full table-header-bg">
            <thead>
              <tr>
                <th class="text-center" >Bono</th>
                <th class="text-center">Precio</th>
                <th class="text-center">Valor del bono</th>
                <th class="text-center">Cantidad</th>
              </tr>
            </thead>
            <tbody>	
            <?php foreach ($old as $rate): ?>
                <tr>
                  <td class="text-left ">{{$rate->name}}</td>
                  <td class="text-center ">{{$rate->price}}</td>
                  <td class="text-center ">{{$rate->value}}</td>
                  <td class="text-center ">{{$rate->qty}}</td>
                </tr>		
            <?php endforeach ?>	             
            </tbody>
          </table>
<?php endif ?>
      </div>
    </div>
      
      
      
  </div> 
<div class="modal fade" id="modal-bono" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="block block-themed block-transparent remove-margin-b">
        <div class="block-header bg-primary-dark">
          <ul class="block-options">
            <li>
              <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
            </li>
          </ul>
        </div>
        <div class="row block-content" id="content-bono">
          @include('admin.bonos.new')
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


@section('scripts')
<script type="text/javascript">
  $(document).ready(function () {


    $('.editables').change(function (event) {
            
        var id = $(this).attr('data-id');
        var data= {
            id: id,
            name: $('.name-bono-' + id).val(),
            price: $('.price-bono-' + id).val(),
            rate: $('.rate-bono-' + id).val(),
            qty: $('.qty-bono-' + id).val(),
        };
                   console.log(data);   
      $.get('/admin/bonos/update/', data, function(resp){
        if (resp == 'OK'){
          window.show_notif('success', 'Bono actualizado');
        } else {
          window.show_notif('error', 'Bono no actualizado');
        }
      });
    });
    
    $('.rate_fidelity').on('click',function(){
        var that = $(this);
        var val = that.data('v');
        if (val == '') val = 'no_fidelity';
        var newVal = 'fidelity';
        if (val == 'fidelity') newVal = 'no_fidelity';

        var data= {
              id: that.data('k'),
              val: newVal,
              _token: '{{csrf_token()}}'
          };

        $.post('/admin/bonos/upd_fidelity', data).done(
          function (resp) {
            if (resp == 'OK'){
               window.show_notif('success', 'tipo de plan actualizado');
               that.find('.'+newVal).show();
               that.find('.'+val).hide();
               that.data('v',newVal);
            } else {
               window.show_notif('error', 'tipo de plan NO actualizado');
            }
          });

      });
   
  });
</script>
@endsection