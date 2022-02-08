<div class="row ">
    <div class="col-xs-12">
        <div class="col-xs-12 col-md-12 text-left">
            <h1 class="text-center">
                <?php echo $user->name; ?>
            </h1>
        </div>
    </div>
</div>
<div class="row mx-1em">
    <div class="col-md-4" style="margin-right: 1px solid #e8e8e8;">
        <div><b>Cliente:</b><br/>{{$user->name}}</div>
        <div><b>Email: </b><br/>{{$user->email}}</div>
        <div><b>TEL.: </b><br/>{{$user->phone}}</div>
    </div>
    <div class="col-md-8" style="margin-left: 1px solid #e8e8e8;">
        <h4 class="text-left">SERVICIOS ASOCIADOS:</h4>
        <ul>
        <?php foreach ($lstRates as $rate): ?>
        <li>{{$rate}}</li>
        <?php endforeach ?>
        </ul>
    </div>
</div>
<div class="table-responsive">
<table class="table">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Fisioterapeuta</th>
            <th>Servicio</th>
            <th>Pagado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($aLst as $i)
        <tr class="editDate" data-id="{{$i['id']}}">
            <td>{{$i['date']}}</td>
            <td>{{$i['hour']}}</td>
            <td>{{$i['coach']}}</td>
            <td>{{$i['rate']}}</td>
            <td><?php echo ($i['charged']) ? 'SI' : 'NO' ?></td>
        </tr>
        @endforeach
    </tbody>
</table>
    </div>
<style>
    .editDate{
        cursor: pointer;
    }
    .editDate:hover{
        background-color: #deecff;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $('.editDate').on('click',function(event){
            event.preventDefault();
            var id = $(this).data('id');
            $('#content-add-date').load('/admin/citas-fisioterapia/edit/'+id);
            $('#modal-add-date').modal();
            $('#infoCita').modal('hide');
        });
    });
</script>