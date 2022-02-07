<?php 
$yearActive = getYearActive();
$y = date('Y');
$years = [$y+1]; 
for($i=$y; $i>($y-5); $i--){
  $years[] = $i;
}
?>
<select id="years" class="form-control minimal sidebar-mini-hide">
    @foreach($years as $year)
        <option value="{{ $year }}" @if ($year == $yearActive) selected @endif >
            {{ $year }}
        </option>
    @endforeach
</select>
