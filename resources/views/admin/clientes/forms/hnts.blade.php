<h3 class="text-left">HISTORIAL HNTs</h3>





<div class="row">
  <div class="col-sm-9">
    
    <canvas id="hnt_customer" style="width: 100%; height: 250px;"></canvas>
    <p class="text-center">Activado: {{convertDateToShow_text($customer->hotspot_date,true)}}</p>
  </div>
  <div class="col-sm-3">
    <div class="table-responsive">
      <table class="table table-h20">
        <thead>
          <tr>
            <th>Día</th>
            <th>Hnts</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($hnts):
            foreach ($hnts[1] as $k => $v):
              ?>
              <tr>
                <td>{{dateMin($k)}}</td>
                <td>{{$v}}</td>
              </tr>
              <?php
            endforeach;
          endif;
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>