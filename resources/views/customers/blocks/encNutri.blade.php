<?php
$count = 1;
?>
<div class="fromEncNutri">
 <?php $nro = 1; ?>
  @foreach($data['qstion1'] as $i=>$q)
  <div class="field">
    <label>{{$nro.'. '.$q}}</label>
    <?php
    switch ($i) {
      case 'nutri_q22':
        ?>
        <table class="table">
          <tr>
            <td></td>
            <th class="text-center">Entre semana</th>
            <th class="text-center">Fines de semana</th>
          </tr>
          <tr>
            <th>Desayuno</th>
            <td><input type="text" id="nutri_q22_1_1" name="nutri_q22_1_1" class="form-control" required=""></td>
            <td><input type="text" id="nutri_q22_2_1" name="nutri_q22_2_1" class="form-control" required=""></td>
          </tr>
          <tr>
            <th>Comida</th>
            <td><input type="text" id="nutri_q22_1_2" name="nutri_q22_1_2" class="form-control" required=""></td>
            <td><input type="text" id="nutri_q22_2_2" name="nutri_q22_2_2" class="form-control" required=""></td>
          </tr>
          <tr>
            <th>Cena</th>
            <td><input type="text" id="nutri_q22_1_3" name="nutri_q22_1_3" class="form-control" required=""></td>
            <td><input type="text" id="nutri_q22_2_3" name="nutri_q22_2_3" class="form-control" required=""></td>
          </tr>
          <tr>
            <th>Snacks / Entrehoras</th>
            <td><input type="text" id="nutri_q22_1_4" name="nutri_q22_1_4" class="form-control" required=""></td>
            <td><input type="text" id="nutri_q22_2_4" name="nutri_q22_2_4" class="form-control" required=""></td>
          </tr>
        </table>
        <?php
        break;
      case 'nutri_q2':
        ?>
    
    <input  size="10" maxlength="10" onKeyUp = "this.value = formateafecha(this.value);" placeholder="DD-MM-YYYY" id="{{$i}}" name="{{$i}}" class="form-control" required="">
        <?php
        break;
      default :
        ?>
        @if(isset($data['options'][$i]))
        @foreach($data['options'][$i] as $i2=>$q2)
        <div class="radio">
          <input type="radio" value='{{$q2}}' name="{{$i}}"  id="{{$i}}" required=""><span>{{$q2}}</span>
        </div>
        @endforeach
        @else
        <input type="text" id="{{$i}}" name="{{$i}}" value="" class="form-control" required="">
        @endif
        <?php
        break;
    }
     $nro++;
    ?>

  </div>
  @endforeach


</div>







<style>
  .fromEncNutri img{
    max-width: 100%;
  }

  .fromEncNutri .radio input[type="radio"] {
    margin-right: 10px;
  }
  .fromEncNutri .radio span {
    margin-top: 0;
  }

  .fromEncNutri .bold{
    text-align: center;
    font-weight: bold;
    color: #000;
    margin-bottom: 2em;
  }

  .fromEncNutri .field {
    margin: 1px 0px 10px !important;
    padding: 3px 8px;
    font-size: 13px;
    min-height: 2.1em;
  }
  .fromEncNutri label {
    font-size: 14px;
  }
  .fromEncNutri .tqstion2 th{
    width: 25%;
    padding: 18px 5px !important;
    font-size: 13px !important;
  }

</style>