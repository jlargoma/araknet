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
    <tr >
      <th>CONSENTIMIENTO FISIOTERAPIA CON INDIBA</th>
      <td class="btnCel">
        @if($fisioIndiba)
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
        <button type="button" title="Enviar / Re-enviar mail de consentimiento" class="btn btn-info sendConsent">
          <i class="fa fa-envelope"></i> Enviar
        </button>
      </td>
      <td class="btnCel"  colspan="2"><?php echo btn_seeContrato($customer->id, $fisioIndiba, 'fisioIndiba'); ?></td>
    </tr>

  </table>
</div>