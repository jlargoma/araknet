@extends('layouts.pdf')

@section('title') Contratos @endsection

@section('styles')
<style type="text/css">

  .contratoBox{
    max-width: 860px;
    margin: 50px auto;
    font-size: 16px;
    padding: 30px 10px 45px 50px;
    background-color: #FFF;
  }
  .rateCalendar .item {
    width: 200px;
  }
  .sing-box {
    border: 1px solid;
    width: 325px;
    padding: 5px;
    margin: 15px auto;
  }
  .signProp,
  .signAdmin{
    width: 48%;
    margin: 2%;
    text-align: center;
    display: inline-block;
  }
  table.signs{
    width: 100%;
  }
  table.signs td{
    width: 49%;
      text-align: center;
  }
  table.signs td img {
    max-width: 230px;
}
.contratoBox h1 {
    font-size: 20px;
    font-weight: bold;
    text-align: center;
    margin-left: -1em;
}
.contratoBox h3 {
  margin-bottom: 14px;
  font-size: 12px;

}
.contratoBox .body,
.contratoBox .body p{
  font-size: 13px;
  /*text-indent: 5px;*/
  text-align: justify;
}
.sessionType{
  display: none;
}
.month {
    height: 20px;
    font-weight: bold;
}
</style>
@endsection
@section('content')

<div class="contratoBox">
  <h1>{{$tit}}</h1>
  <div class="body">
    <?php echo $text; ?>
  </div>
  @for($i=0; $i<15;$i++)
  <br/>
  @endfor
  <table class="signs">
    <tr>
      <td >
        <img src="data:image/png;base64,{{$signAdmin}}" >
      </td>
      <td>
        <img src="data:image/png;base64,{{$signFile}}" >
      </td>
    </tr>
    <tr>
      <td >ARAKNET GLOBAL GROUP SL</td>
      <td >{{$customerName}}</td>
    </tr>
  </table>
</div>
@endsection