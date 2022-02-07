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
    <form action="/pago" method="POST">
        <input type="hidden" name="data_1" value="{{$type}}">
        <input type="hidden" name="data_2" value="{{$token}}">
        <input type="hidden" name="data_3" value="{{$control}}">
        {{ csrf_field() }}
        <script
            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
            data-key="{{ $keyStripe }}"
            data-amount="{{$amount}}"
            data-name="Pago"
            data-description="{{$name}}"
            data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
            data-locale="auto">
        </script>
    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



<div class="content">

</div>