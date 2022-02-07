<?php 

function btn_seeContrato($uID,$sign,$type){
  if($sign): ?>
    <a href="/admin/see-contrato/{{$uID}}/{{$type}}" title="Ver documento" class="btn btn-info" target="_black">
      <i class="fa fa-eye"></i>
    </a>
  <?php else: ?>
  <button type="button" title="Ver documento" class="btn btn-info" disabled>
    <i class="fa fa-eye"></i>
  </button>
  <?php endif;
}

function btn_seeConsent($uID,$sign,$type){
  if($sign): ?>
    <a href="/admin/see-consent/{{$uID}}/{{$type}}" title="Ver documento" class="btn btn-info" target="_black">
      <i class="fa fa-eye"></i>
    </a>
  <?php else: ?>
  <button type="button" title="Ver documento" class="btn btn-info" disabled>
    <i class="fa fa-eye"></i>
  </button>
  <?php endif;
}
function btn_downlConsent($uID,$sign,$type){
  if($sign): ?>
    <a href="/admin/downl-consent/{{$uID}}/{{$type}}" title="Descargar documento" class="btn btn-info" target="_black">
      <i class="fa fa-download"></i>
    </a>
  <?php else: ?>
  <button type="button" title="Descargar documento" class="btn btn-info" disabled>
    <i class="fa fa-download"></i>
  </button>
  <?php endif;
}

?>
<h3 class="text-left">CONSENTIMIENTOS</h3>
<div class="table-responsive">
<table class="table">
  <tr data-id="fisioIndiba">
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
    <td class="btnCel"  colspan="2"><?php echo btn_seeConsent($user->id,$fisioIndiba,'fisioIndiba'); ?></td>
  </tr>
  <tr data-id="sueloPelvico">
    <th>CONSENTIMIENTO SUELO PELVICO</th>
    <td class="btnCel">
      @if($sueloPelvico)
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
    <td class="btnCel" colspan="2"><?php echo btn_seeConsent($user->id,$sueloPelvico,'sueloPelvico'); ?></td>
  </tr>
  @if($uPlan == 'basic' || $uPlan == 'fidelity')
  <tr data-id="contrato">
    <th>CONTRATOS PLAN <?php echo ($uPlan == 'fidelity') ? 'FIDELITY' : 'BÁSICO';?></th>
    <td class="btnCel">
      @if($sing_contrato)
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
      <button type="button" title="Enviar / Re-enviar mail de Contrato" class="btn btn-info sendConsent">
        <i class="fa fa-envelope"></i> Enviar
      </button>
    </td>
    <td class="btnCel"><?php echo btn_seeContrato($user->id,$sing_contrato,'contrato'); ?></td>
    <td class="btnCel">@if($sing_contrato)<button class="btn btn-danger rmContrato" title="Reiniciar contrato" ><i class="fa fa-close"></i></button>@endif</td>
  </tr>
  @endif
</table>
  </div>