@if($payment == 'cod') 
                                <input type="hidden" name="method" value="Cash On Delivery">


@endif
@if($payment == 'paypal') 
                                <input type="hidden" name="method" value="Paypal">
                                <input type="hidden" name="cmd" value="_xclick">
                                <input type="hidden" name="no_note" value="1">
                                <input type="hidden" name="lc" value="UK">
                                <input type="hidden" name="currency_code" value="{{$curr->name}}">
                                <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest">

@endif

@if($payment == 'stripe') 
                                	<input type="hidden" name="method" value="Stripe">
                                  <div class="row" >
                                    <div class="col-lg-6">
                                      <input class="form-control card-elements" name="cardNumber" type="text" placeholder="{{ $langg->lang163 }}" autocomplete="off" required autofocus oninput="validateCard(this.value);" />
                                      <span id="errCard"></span>
                                    </div>
                                    <div class="col-lg-6">
                                      <input class="form-control card-elements" name="cardCVC" type="text" placeholder="{{ $langg->lang164 }}" autocomplete="off" required oninput="validateCVC(this.value);" />
                                      <span id="errCVC"></span>
                                    </div>
                                    <div class="col-lg-6">
                                      <input class="form-control card-elements" name="month" type="text" placeholder="{{ $langg->lang165 }}" required />
                                    </div>
                                    <div class="col-lg-6">
                                      <input class="form-control card-elements" name="year" type="text" placeholder="{{ $langg->lang166 }}" required />
                                    </div>
                                </div>


                                <script type="text/javascript" src="{{ asset('assets/front/js/payvalid.js') }}"></script>
                                <script type="text/javascript" src="{{ asset('assets/front/js/paymin.js') }}"></script>
                                <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
                                <script type="text/javascript" src="{{ asset('assets/front/js/payform.js') }}"></script>


                                <script type="text/javascript">
                                  var cnstatus = false;
                                  var dateStatus = false;
                                  var cvcStatus = false;
                              
                                  function validateCard(cn) {
                                    cnstatus = Stripe.card.validateCardNumber(cn);
                                    if (!cnstatus) {
                                      $("#errCard").html('{{ $langg->lang781 }}');
                                    } else {
                                      $("#errCard").html('');
                                    }
                                    btnStatusChange();
                              
                              
                                  }
                              
                                  function validateCVC(cvc) {
                                    cvcStatus = Stripe.card.validateCVC(cvc);
                                    if (!cvcStatus) {
                                      $("#errCVC").html('{{ $langg->lang782 }}');
                                    } else {
                                      $("#errCVC").html('');
                                    }
                                    btnStatusChange();
                                  }
                              
                                </script>


@endif


@if($payment == 'instamojo') 
                                	<input type="hidden" name="method" value="Instamojo">

@endif


@if($payment == 'paystack') 

<script src="https://js.paystack.co/v1/inline.js"></script>
        <input type="hidden" name="ref_id" id="ref_id" value="">
        <input type="hidden" name="sub" id="sub" value="0">
		<input type="hidden" name="method" value="Paystack">

<script type="text/javascript">
    
        $('#step1-form').on('submit',function(){
        	$('#preloader').hide();
            var val = $('#sub').val();
            var total = $('#grandtotal').val();
                if(val == 0)
                {
                var handler = PaystackPop.setup({
                  key: '{{$gs->paystack_key}}',
                  email: '{{$gs->paystack_email}}',
                  amount: total * 100,
                  currency: "{{$curr->name}}",
                  ref: ''+Math.floor((Math.random() * 1000000000) + 1),
                  callback: function(response){
                    $('#ref_id').val(response.reference);
                    $('#sub').val('1');
                    $('#final-btn').click();
                  },
                  onClose: function(){
                  }
                });
                handler.openIframe();
                    return false;                    
                }
                else {
                	$('#preloader').show();
                    return true;   
                }

        });


</script>



@endif

@if($payment == 'other') 

                                <input type="hidden" name="method" value="{{ $gateway->title }}">

                                  <div class="row" >

<div class="col-lg-12 pb-2">
	
	{!! $gateway->details !!}

</div>


<div class="col-lg-6">
	<label>{{ $langg->lang167 }} *</label>
	<input class="form-control" name="txn_id4" type="text" placeholder="{{ $langg->lang167 }}" required />
</div>


  </div>
@endif