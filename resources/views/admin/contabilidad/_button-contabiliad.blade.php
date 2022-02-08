 
<?php
$url = substr(Request::path(), 6);
$posicion = strpos($url, '/');
if ($posicion > 0) {
  $url = substr($url, 0, $posicion);
} else {
  $url;
};

$items = [
    'ingresos'=>'Ingresos',
    'gastos'=>'Gastos',
    'perdidas-ganancias'=>'CTA P&G'
];
?>
<div class="col-md-12 col-xs-12 push-20">
@foreach($items as $k=>$v)
  <div class="col-md-1 col-xs-4 push-10" style="padding: 5px;">
    <?php if ($url == $k): ?>
      <button class="btn btn-md" style="width: 100%; background-color: #6600ff;pointer-events: none" disabled>
        <a class="text-white" >{{$v}}</a>
      </button>
    <?php else: ?>
      <a class="text-white" href="{{url('/admin/'.$k)}}">
        <button class="btn btn-md btn-primary" style="width: 100%;">
          {{$v}}
        </button>
      </a>
    <?php endif ?>	
  </div>
@endforeach
</div>
