<div class="table-responsive">
    <table class="table table-striped js-dataTable-citas table-header-bg dataTable no-footer">
        <thead>
            <tr>
                <th class="text-left">Nombre</th>
                <th>Teléfono</th>
                @foreach($aMonths as $k=>$v)
                <th>{{$v}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($oCustomers as $c)
            <tr>
                <td class="text-left showInform" data-id="{{$c->id}}">{{$c->name}}</td>
                <td>{{$c->phone}}</td>
                <?php 
                foreach($aMonths as $k=>$v){
                    echo '<td>';
                    if (!isset($aLst[$c->id])){
                        echo '--</td>';
                        continue;
                    }
                    if (!isset($aLst[$c->id][$k])){
                        echo '--</td>';
                        continue;
                    }
                    foreach ($aLst[$c->id][$k] as $item){
                        echo $item.'</br>';
                    }
                    echo '</td>';
                }
                ?>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>