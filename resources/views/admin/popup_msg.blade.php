@extends('layouts.popup')
@section('content')
@if(isset($msg))
<p class="alert alert-info tex-center">{{$msg}}</p>
@endif
@endsection