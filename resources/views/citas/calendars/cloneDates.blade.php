<?php 
function printEvents($lst,$h){
  echo '<td class="time not">';
  $first = true;
  foreach ($lst as $item){
    
    if ($item['charged'] == 2){
      echo '<span '
      . 'data-id="'.$item['id'].'" '
      . 'data-name="--" '
      . 'class="eventType_'.$item['user'].' events blocked">'
      .$h. '</span>';
    } else {
      $payment = ($item['charged'] != 1) ? '<span class="no-pay"></span>' : '';
      echo '<span '
      . 'data-id="'.$item['id'].'" '
      . 'data-name="'. strtolower($item['name']).'" '
      . 'class="eventType_'.$item['user'].' events">';
      echo ($first) ? $h. '</span>' : ' *</span>';
    }
    $first = false;
  }
   echo '</td>';
}
                        
?>


@extends('layouts.popup')
@section('content')
<link rel="stylesheet" href="{{ assetV('css/custom.css') }}">
<link rel="stylesheet" href="{{ assetV('css/calendars.css') }}">
<a class="back" href="#">X</a>
<div class="col-xs-12">
  <h2 class="text-center">Duplicar Cita</h2>
    <form action="" method="post" id="formClone">
      <input type="hidden" name="idDate" value="{{$obj->id}}">
      <input type="hidden" name="datelst"  id="datelst" value="">
      <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <div class="row">
      <div class="col-md-8">
      <h3>Repetir la cita de {{$cRate->customer->name}}</h3>
      <p>{{$cRate->customer->name}}, el día {{dateMin($obj->date)}} a las {{$obj->getHour()}}</p>
      </div>
      <div class="col-md-4">
        <button type="button" class="btn btn-success" id="cloneDates">Crear</button>
      </div>
      </div>
      </form>
  <div class="row">
      
       
<?php foreach($calendar as $days):
if (empty($days)) continue ?>
<div class="table-responsive col-md-4 selDays">
    <table class="table table-calendar ">
        <thead>
            <tr>
                @foreach($days as $d)
                <th>{{$d['day']}}<br/>{{$d['date']}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @for($i=8; $i<22; $i++)
            <tr>
                @foreach($days as $k=>$d)
                <?php $dk= $k+1; //corrige -> no hay domingos ?>
                @if(isset($times[$dk]) && isset($times[$dk][$i]) && $times[$dk][$i] == 0)
                <td class="time not">{{$i}}</td>
                @else
                <?php 
                if (isset($aLst[$d['time']]) && isset($aLst[$d['time']][$i])){
                        printEvents($aLst[$d['time']][$i],$i);
                } else {
                  echo '<td class="time selectDate" data-date="'.$d['time'].'" data-time="'.$i.'">'.$i.'</td>';
                }
                ?>
                @endif
                @endforeach
            </tr>
            @endfor
        </tbody>
    </table>
</div>

<?php endforeach; ?>
     
    
  </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
  $('.selectDate').click(function(event){
    var obj = $(this);
    if (obj.hasClass('active')){
      obj.removeClass('active')
    } else {
      obj.addClass('active')
    }
  });
  $('.back').click(function(event){
    history.back();
  });
  $('#cloneDates').on('click',function(event){
    event.preventDefault();
    var dates = '';
    var count = 0;
    $('.selectDate.active').each(function(i,j){
      dates += $(this).data('date')+'-'+$(this).data('time')+';';
      count++;
    });
    
    $('#datelst').val(dates);
    if (confirm('Crear las '+count+' citas para el cliente?')){
      $('#formClone').submit();
    } else {
      setTimeout(function(){$('.loading').hide();},150);
    }
    return null;
  });
  
  @if($detail)
    var details = {!!$detail!!};
  @endif
</script>
<script src="{{assetv('/admin-css/assets/js/toltip.js')}}"></script>
<style>
  .selDays{
    padding: 10px;
}
    .selDays .table{
    box-shadow: 1px 1px #000;
  }
  .selDays tr td{
    margin: 0;
    padding: 1px !important;
    text-align: center;
  }
  
toltip {
    color: #eae9e9;
    background: #0c71ff;
}
.table-calendar tbody td.time {
  cursor: pointer;
}
.table-calendar tbody td.time:hover,
.table-calendar tbody td.time.active {
    background-color: #1fab5a;
    color: #FFF;
}
.table-calendar tbody td.time.not {
  background-color: #c1c1c1;
  cursor: not-allowed;
}
 a.back {
    display: block;
    font-weight: bold;
    float: right;
    width: 31px;
    background-color: #6e6e6e;
    color: #FFF;
    text-align: center;
    font-size: 22px;
    border-radius: 6px;
}
</style>
@endsection