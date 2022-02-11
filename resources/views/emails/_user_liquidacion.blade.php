<?php
$tit = 'Liquidación ' . $mes;
?>
@include('emails.head')

<p>Hola {{$user->name}},</p>
<p>Te adjuntamos tu liquidación para el mes de {{$mes}} que has solicitado.</p>
@include('emails.footer')
