<?php use \Carbon\Carbon; ?>
<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
@extends('layouts.users-master')
@section('title') Mi contrato- Evolutio @endsection

@section('externalScripts')
<style type="text/css">
	/**
	 * The CSS shown here will not be introduced in the Quickstart guide, but shows
	 * how you can use CSS to style your Element's container.
	 */
	.StripeElement {
	  background-color: white;
	  height: 40px;
	  padding: 10px 12px;
	  border-radius: 4px;
	  border: 1px solid transparent;
	  box-shadow: 0 1px 3px 0 #e6ebf1;
	  -webkit-transition: box-shadow 150ms ease;
	  transition: box-shadow 150ms ease;
	}

	.StripeElement--focus {
	  box-shadow: 0 1px 3px 0 #cfd7df;
	}

	.StripeElement--invalid {
	  border-color: #fa755a;
	}

	.StripeElement--webkit-autofill {
	  background-color: #fefde5 !important;
	}
</style>

@endsection

@section('body')
	<div class="row" style="margin: 30px 0;">
		<div class="col-md-2 col-xs-6 text-left">
			<a href="{{ url('/clientes') }}">Atrás</a>
		</div>
	</div>
	<div class="heading-block center">
		<h2>Contrato</h2>
		<span>Aquí puedes revisar el contrato que tienes con nosotros.</span>
	</div>
	<div class="row">
		<div class="col-xs-12">
			@include('users._partials._contrato', ['summary' => 0])
		</div>
	</div>
@endsection


@section('scripts')

<script type="text/javascript">
	// Create a Stripe client
	var stripe = Stripe('<?php echo $stripe['publishable_key'] ?>');

	// Create an instance of Elements
	var elements = stripe.elements();

	// Custom styling can be passed to options when creating an Element.
	// (Note that this demo uses a wider set of styles than the guide below.)
	var style = {
	  base: {
	    color: '#32325d',
	    lineHeight: '18px',
	    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
	    fontSmoothing: 'antialiased',
	    fontSize: '16px',
	    '::placeholder': {
	      color: '#aab7c4'
	    }
	  },
	  invalid: {
	    color: '#fa755a',
	    iconColor: '#fa755a'
	  }
	};

	// Create an instance of the card Element
	var card = elements.create('card', {style: style});

	// Add an instance of the card Element into the `card-element` <div>
	card.mount('#card-element');

	// Handle real-time validation errors from the card Element.
	card.addEventListener('change', function(event) {
	  var displayError = document.getElementById('card-errors');
	  if (event.error) {
	    displayError.textContent = event.error.message;
	  } else {
	    displayError.textContent = '';
	  }
	});

	// Handle form submission
	var form = document.getElementById('payment-form');
	form.addEventListener('submit', function(event) {
	  event.preventDefault();

	  stripe.createToken(card).then(function(result) {
	    if (result.error) {
	      // Inform the user if there was an error
	      var errorElement = document.getElementById('card-errors');
	      errorElement.textContent = result.error.message;
	    } else {
	      // Send the token to your server
	      stripeTokenHandler(result.token);
	    }
	  });
	});
</script>
@endsection