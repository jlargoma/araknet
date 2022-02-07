<?php 
function printEvents($lst){
  $aux = $aux2 = [];
  foreach ($lst as $item){
    $aux[] = $item['h'];
    if (!isset($aux2[$item['h']])) $aux2[$item['h']] = [];
    $aux2[$item['h']][] = $item;
  }
  $aux = array_unique($aux);
  asort($aux);
  foreach ($aux as $h){
    foreach ($aux2[$h] as $item){
    $ecogrf = '';
    $indiba = '';
    if (isset($item['ecogr']) && $item['ecogr']){
      $ecogrf = '<img src="/img/ecog.png" class="ecogrf"  title="ecógrafo" alt="ecógrafo">';
    }
    if (isset($item['indiba']) && $item['indiba']){
      $indiba = '<img src="/img/indiba.png" class="ecogrf" title="indiba" alt="indiba">';
    }
    
    switch ($item['charged']){
      case 2:
        echo '<div '
          . 'data-id="'.$item['id'].'"  data-name=""  '
          . 'class="eventType_'.$item['coach'].' events blocked">'
          .$item['h'].'<span></span><toltip/>'
          . '</div>';
        break;
      case 3:
        echo '<div '
          . 'data-id="'.$item['id'].'" data-name="" '
          . 'class="eventType_'.$item['coach'].' events group">'
          .$item['h'].'<cust>  GRUPO '.$ecogrf.$indiba.'</cust><toltip/>'
          . '</div>';
        break;
      default :
        $payment = ($item['charged'] != 1) ? '<span class="no-pay"></span>' : '';
        $hour = ($item['charged'] != 1) ? '<nopay>'.$item['h'].'</nopay>' : $item['h'];
        echo '<div '
        . 'data-id="'.$item['id'].'" '
        . 'data-name="'. strtolower($item['name']).'" '
        . 'class="eventType_'.$item['coach'].' events">'
          .$hour.'<cust>'.str_limit($item['name'],10).$ecogrf.$indiba.'</cust>'
            .'<toltip/>'
        . '</div>';
       break;
    }
    }
  }
}
              
$thisWeek = date('W');
?>
@foreach($calendar as $days)
<?php if (empty($days)) continue;
$cWeek = (date('W',$days[0]['time']) == $thisWeek) ? 'id="cweek"' : '';
?>
<div class="table-responsive" <?php echo $cWeek; ?>>
    <table class="table table-calendar">
        <thead>
            <tr>
                @foreach($days as $d)
                <th colspan="2">{{$d['day'].' '.$d['date']}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @for($i=8; $i<23; $i++)
            <tr>
                @foreach($days as $k=>$d)
                <?php 
                $dk= $k+1; //corrige -> no hay domingos 
                $avail = '';
                if (isset($avails[$d['time']]) && isset($avails[$d['time']][$i]) && count($avails[$d['time']][$i])){
                  $avail = '';
                  foreach ($avails[$d['time']][$i] as $cID)
                    $avail .='<span class="dateAvail coach_'.$cID.'"></span>';
                }
                  
                ?>
                    @if(isset($times[$dk]) && isset($times[$dk][$i]) && $times[$dk][$i] == 0)
                    <td class="time not">{{$i}}</td>
                    <td class="not editDate">
                      <div class="lst_events">
                        <div class="availDate"><?= $avail?></div>
                      <?php
                      if (isset($aLst[$d['time']]) && isset($aLst[$d['time']][$i])){
                        printEvents($aLst[$d['time']][$i]);
                      }
                      ?>
                      </div>
                    </td>
                    @else
                    <td class="time addDate" data-date="{{$d['time']}}" data-time="{{$i}}">{{$i}}</td>
                    <td class="editDate">
                      <div class="lst_events">
                      <div class="availDate"><?= $avail?></div>
                      <?php
                      if (isset($aLst[$d['time']]) && isset($aLst[$d['time']][$i])){
                        printEvents($aLst[$d['time']][$i]);
                      }
                      ?>
                      </div>
                    </td>
                @endif
                @endforeach
            </tr>
            @endfor
        </tbody>
    </table>
</div>
@endforeach