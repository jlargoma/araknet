@foreach($calendar as $days)
<?php if (empty($days)) continue ?>
<div class="table-responsive">
    <table class="table table-calendar">
        <thead>
            <tr>
                @foreach($days as $d)
                <th colspan="2">{{$d['day'].' '.$d['date']}}</th>
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
                    <td class="time not editDate">
                    @else
                    <td class="time addDate" data-date="{{$d['time']}}" data-time="{{$i}}">{{$i}}</td>
                    <td class="editDate">
                    @endif
                        <?php
                        if (isset($aLst[$d['time']]) && isset($aLst[$d['time']][$i])){
                            foreach ($aLst[$d['time']][$i] as $item){
                                $payment = ($item['charged'] != 1) ? '<span class="no-pay"></span>' : '';
                                echo '<div '
                                . 'data-id="'.$item['id'].'" '
                                . 'data-name="'. strtolower($item['name']).'" '
                                . 'class="eventType_'.$item['coach'].' events">'
                                    .$payment.str_limit($item['name'],10)
                                    .'<toltip/>'
                                . '</div>';
                            }
                        }
                        ?>
                    </td>
                
                @endforeach
            </tr>
            @endfor
        </tbody>
    </table>
</div>
@endforeach