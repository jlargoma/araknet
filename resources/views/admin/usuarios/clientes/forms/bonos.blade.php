<h3 class="text-left">Bonos del Usuario
  <button class="btn btn-default add_bono" data-idUser="<?php echo $user->id; ?>">
    <i class="fa fa-plus-circle" aria-hidden="true"></i> Comprar
  </button>
</h3>
<div class="row">
  <div class="col-md-6">
<?php
$oBonoLst = $user->bonos;
$rate_subf = \App\Models\TypesRate::subfamily();
if (count($oBonoLst) > 0) { ?>
  <?php
  foreach ($oBonoLst as $b) {
    $name = 'Bono sin servicio';
    if ($b->rate_subf && isset($rate_subf[$b->rate_subf]))
      $name = $rate_subf[$b->rate_subf];
    if ($b->rate_type && isset($atypeRates[$b->rate_type]))
      $name = $atypeRates[$b->rate_type].' (General)';
                  ?>
      <div class="lstBono" data-id="{{$b->id}}" >
        <label>{!!$name!!}</label>
        <span>{{$b->qty}}</span>
      </div>
    <?php
  }
} else {
  echo '<p class="alert alert-warning">No posee bonos</p>';
}
?>
  </div>
  <div class="col-md-6">
    <h4>Logs:</h4>
    <div id="bonoLog">
      <p class="alert alert-warning">Seleccione un bono para continuar</p>
    </div>
  </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
      $('.lstBono').on('click',function(){
        $('.lstBono').removeClass('selected');
        $(this).addClass('selected');
        $('#bonoLog').load('/admin/bonologs/'+$(this).data('id'));
      })
      
      
//      $('#bonoLog').on('submit','#updBonoBalance',function (event) {
//        event.preventDefault();
//        var form = $(this);
//        var ID = form.find('#updBonoBalanceID').val();
//        $.post(form.attr('action'), form.serialize()).done(function(resp) {
//        if (resp == 'OK'){
//          alert('Bono actualizado');
//          $('#bonoLog').load('/admin/bonologs/'+ID);
//        } else {
//          alert(resp);
//        }
//      });
//   
//      });
      
      
    });
</script>