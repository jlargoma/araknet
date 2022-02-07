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
    <form action="/pago" method="POST">
        <input type="hidden" name="data_1" value="{{$type}}">
        <input type="hidden" name="data_2" value="{{$token}}">
        <input type="hidden" name="data_3" value="{{$control}}">
        {{ csrf_field() }}
        <!-- Stripe Elements Placeholder -->

        <div id="cardElement"></div>
        <button type="button" id="payCard">Pagar</button>
    </form>
                </div>
            </div>
        </div>
    </div>
</div>







@endsection

@section('scripts')


<script src="https://js.stripe.com/v3/"></script>

<script>
    const stripe = Stripe('pk_test_Egfkl4ujueS2yCu8OHtG0G68009jocjWMA');

    const elements = stripe.elements();
    const cardElement = elements.create('card');

    cardElement.mount('#cardElement');
</script>

<script type="text/javascript">

    $(document).ready(function () {
      $('#payCard').on('click',function (){
        console.log(cardElement,$('#cardElement'));
      });
    });
</script>
    

@endsection