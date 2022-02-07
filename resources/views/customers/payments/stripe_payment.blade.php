@extends('layouts.app')

@section('content')
<div class="">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{$name}}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                <h3>{{moneda(($amount/100),true,2)}}</h3>
                @if($disc)
                <b>Descuento del {{$disc}}%</b>
                @endif
                @if($items)
                <ul>
                   @foreach($items as $i)
                   <li>{!! $i !!}</li>
                   @endforeach
                </ul>
                @endif
                @if($payment)
                <p class="alert alert-success">Pagado</p>
                @else
                <p class="text-center">
                 {{ $checkout->button('Pagar') }}
                 </p>
                @endif
                  </div>
              </div>
        </div>
  </div>
</div>
@endsection



@section('scripts')
<script src="https://js.stripe.com/v3/"></script>
@endsection
