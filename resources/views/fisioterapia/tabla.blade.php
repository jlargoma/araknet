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
            @foreach($oUsers as $u)
            <tr>
                <td class="text-left showInform" data-id="{{$u->id}}">{{$u->name}}</td>
                <td>{{$u->telefono}}</td>
                <?php 
                foreach($aMonths as $k=>$v){
                    echo '<td>';
                    if (!isset($aLst[$u->id])){
                        echo '--</td>';
                        continue;
                    }
                    if (!isset($aLst[$u->id][$k])){
                        echo '--</td>';
                        continue;
                    }
                    foreach ($aLst[$u->id][$k] as $item){
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