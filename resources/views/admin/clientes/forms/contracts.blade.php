<?php

function btn_seeContrato($uID, $sign, $type) {
  if ($sign):
    ?>
    <a href="/admin/see-contrato/{{$uID}}/{{$type}}" title="Ver documento" class="btn btn-info" target="_black">
      <i class="fa fa-eye"></i>
    </a>
  <?php else: ?>
    <button type="button" title="Ver documento" class="btn btn-info" disabled>
      <i class="fa fa-eye"></i>
    </button>
  <?php
  endif;
}
?>
<h3 class="text-left">CONTRATOS</h3>
<div class="table-responsive">
  <table class="table">
    @if($lstContracts)
    @foreach($lstContracts as $kCont => $contract)
    <tr data-id="{{$kCont}}">
      <th>{{$contract['title']}}</th>
       <td class="btnCel">
         
      <button type="button" title="Firmar" class="btn btn-default goContracts">
        <i class="fa fa-pencil-square"></i> Firmar
      </button>
    </td>
      <td class="btnCel">
        @if($contract['signed'])
        <button type="button" title="Firmado" class="btn btn-success">
          <i class="fa fa-check"></i> Firmado
        </button>
        @else
        <button type="button" title="Firmado" class="btn btn-danger">
          <i class="fa fa-close"></i> No firmado
        </button>
        @endif
      </td>
      <td class="btnCel">
        <button type="button" title="Enviar / Re-enviar mail de Contrato" class="btn btn-info sendContract" data-k="{{$kCont}}">
          <i class="fa fa-envelope"></i> Enviar
        </button>
      </td>
      <td class="btnCel"  colspan="2"><?php echo btn_seeContrato($customer->id, $contract['signed'],$kCont); ?></td>
    </tr>
    @endforeach
    @endif


  </table>
</div>