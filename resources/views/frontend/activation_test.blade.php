@extends('frontend.layouts.device_template',['menu'=>"activation"])
@section('content')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://www.paypal.com/sdk/js?client-id={{$client_id}}&currency=EUR"></script>
    <style>
        label{
            color:#111;
            font-size:17px;
            margin-top:5px;
        }
        #submit-btn{
            width: 270px;
            border-radius: 10px;
            font-size: 25px;
        }
        #price-text {
            margin-top:20px;
            font-size: 17px;
            color: #333;
            font-weight: bold;
        }
        .payment-method-container {
            /*max-width: 400px;*/
            margin: 0 auto;
        }
        .payment-title {
            font-size: 20px;
            font-weight: normal;
            color: #222;
            margin-bottom: 5px;
        }
        .payment-method-item-container {
            /*font-size: 20px;*/
            /*background: #fff;*/
            /*padding: 5px 10px;*/
            /*border-radius: 5px;*/
            /*color: #444;*/
            position: absolute;
            font-size: 20px;
            background: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            color: #444;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: fit-content;
        }
        .payment-method-item-container[data-payment_type="card"] {
            border-bottom: none;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
            cursor: pointer;
            transition: all 0.5s;
        }
        .payment-method-item-container[data-payment_type="paypal"] {
            border-radius: 0;
        }
        .payment-method-item-container[data-payment_type="sofort"] {
            border-radius: 0;
            border-top:0;
            border-bottom: none;
        }
        .payment-method-item-container[data-payment_type="crypto"] {
            border-top-left-radius:0;
            border-top-right-radius: 0;
        }
        .payment-method-item-container:hover,.payment-method-item-container.active {
            background: #ddd;
            color: #000;
        }
        .payment-method-icon {
            margin-right: 20px;
            /*width: 40px;*/
            text-align: center;
        }
        .payment-item-detail-container{
            display: none;
            overflow: hidden;
            transition: height 1.5s;
            border: 1px solid #666;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            margin-bottom:10px;
        }
        .payment-item-detail-container.active {
            display: block;
        }

        .card-payment-method-body{
            padding:10px 10px;
        }
        .card-error{
            color: #ee0404;
        }
        .error {
            color: #cc0000;
            margin-top: 0;
        }

        .border-bottom-rad-0{
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }
        .border-bottom-none{
            border-bottom: none;
        }
        #coin-select {
            border-top:none;
            padding-top:5px;
            padding-bottom: 10px;
            padding-left:20px;
            padding-right: 5px;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
        }

        .hide{
            display: none;
        }

        .or-container{
            height: 80px;
        }
        .or-border{
            margin-top: 0;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 100%;
            overflow: hidden;
            border-bottom: 2px solid #333;
            z-index: 0;
        }
        .or{
            width: fit-content;
            background: #fff;
            z-index: 100;
            padding: 0 10px;
            font-size: 20px;
            color: #111;
            letter-spacing: 2px;
            overflow: hidden;
            position: absolute;
            left:50%;
            top: 50%;
            transform: translate(-50%,-50%);
        }

        #disabled-container{
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: #fff;
            opacity: 0.5;
            z-index: 100;
            display: none;
        }

        .payment-button{
            width: 100%;
            font-size: 20px;
            font-weight: bold;
        }

    </style>

    <div class="news-section-container">
        @if($activation_content)
            <?= $activation_content->contents ?>
        @endif
        @if(session()->has('message'))
            <div class="alert alert-success">
                <?php echo session()->get('message');?>
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger">
                <?php echo session()->get('error');?>
            </div>
        @endif
        <div class="alert alert-success hide" id="success-message"></div>
        <div class="alert alert-danger hide" id="error-message"></div>
        <form id="form" method="post" action="{{url('/activation/saveActivationTest')}}">
            @csrf
            <div class="row mt-20" style="justify-content: center">
                <div class="col-12 col-md-6 form-group">
                    <div class="form-group">
                        <label>Select Package</label>
                        <select class="form-control" id="plan_id">
                            @foreach($price_packages as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <p id="price-text">
                        <span>Price:</span>
                        <span class="price-value" id="price-value">&euro;</span>
                    </p>
                    @if($show_paypal==1)
                        <div class="position-relative">
                            <div id="paypal-element"></div>
                            <div id="disabled-container"></div>
                        </div>
                        @if($show_stripe==1 || $show_coin==1)
                            <div id="" class="position-relative or-container">
                                <div class="or-border"></div>
                                <div class="or text-center">Or</div>
                            </div>
                        @endif
                    @endif
                    @if($show_stripe==1)
                        <div>
                            <button class="btn btn-primary payment-button" id="stripe-payment-btn">
                                Pay with Stripe
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/jquery.validate.js"></script>
    {{--    <script src="https://www.google.com/recaptcha/api.js?render={{env('GOOGLE_RECAPTCHA_KEY')}}"></script>--}}

    <div>
        <script>
            var site_url=`<?php echo(url('')); ?>`, mac_address, timer, price_packages=<?=json_encode($price_packages) ?> ;
            $(document).ready(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                setTimeout(()=>{
                    $('.alert.alert-success').slideUp(100);
                },10000);
                $('#price-value').html('&euro;'+price_packages[0].price)
            })

            $('#price_package').change(function () {
                var plan_id=$(this).val();
                var current_price_package;
                for(var i=0;i<price_packages.length;i++){
                    if(price_packages[i].id==plan_id){
                        current_price_package=price_packages[i];
                        break;
                    }
                }
                $('#price-value').html('&euro;'+current_price_package.price)
            })

            paypal.Buttons({
                createOrder: function(data, actions) {
                    var plan_id=$('#price_package').val();
                    return fetch(`${site_url}/paypal/order/create?plan_id=${plan_id}`, {
                        method: 'post',
                        dataType:'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).then(function(res) {
                        console.log(res);
                        if(res.ok){
                            return res.json();
                        }else{
                            $('#error-message').text(res.msg).slideDown();
                        }
                    }).then(
                        function(orderData) {
                            return orderData.id;
                        },
                        function (error) {
                            console.log(error);
                        }
                    );
                },
                // Finalize the transaction
                onApprove: function(data, actions) {
                    var plan_id=$('#price_package').val();
                    return fetch(`${site_url}/paypal/order/capture?order_id=${data.orderID}&plan_id=${plan_id}`, {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).then(function(res) {
                        console.log(res);
                        return res.json();
                    }).then(function(orderData) {
                        console.log(orderData);
                        var errorDetail = Array.isArray(orderData.details) && orderData.details[0];
                        if (errorDetail && errorDetail.issue === 'INSTRUMENT_DECLINED') {
                            return actions.restart();
                        }
                        if (errorDetail) {
                            var msg = 'Sorry, your transaction could not be processed.';
                            if (errorDetail.description) msg += '\n\n' + errorDetail.description;
                            if (orderData.debug_id) msg += ' (' + orderData.debug_id + ')';
                            // Show a failure message
                            return alert(msg);
                        }
                        // Show a success message to the buyer
                        $('#success-message').text('Thanks for your payment, your mac address activated now.').slideDown();
                        setTimeout(function () {
                            $('#success-message').slideUp();
                            // window.location.reload();
                        },5000)
                        // alert('Transaction completed by ' + orderData.payer.name.given_name);
                    });
                }
            }).render('#paypal-element');

            $(document).on('keyup', '.mac_address', function () {
                makeMacAddressFormat(this)
            })
            $(document).on('change','.mac_address',function () {
                makeMacAddressFormat(this)
            });

            var payment_type;
            var stripe = Stripe('<?= $stripe_public_key ?>');
            $('#stripe-payment-btn').click(function (e) {
                e.preventDefault();
                payment_type='stripe';
                submitForm(e);
            })




            function submitForm(e) {
                var isValid = $($(e.target).parents('form')).validate({
                    rules: {
                        "mac-address": "required",
                    },
                    messages: {
                        name: "Mac address is needed",
                    }
                });
                if(!isValid)
                    return;
                else
                    checkout();
            }

            function checkout() {
                if(payment_type=='stripe'){
                    var form_data=new FormData($('#form')[0]);
                    form_data.append('payment_type','stripe');
                    var plan_id=$('#plan_id').val();
                    form_data.append('plan_id',plan_id);

                    $('#submit-btn').attr('disabled',true)
                    $.ajax(
                        {
                            method:'post',
                            url:`<?= url('/device/saveActivation')?>`,
                            data:form_data,
                            dataType: 'json',
                            processData:false,
                            contentType: false,
                            success:data=>{
                                if(data.status==='success'){
                                    stripe
                                        .redirectToCheckout({
                                            sessionId: data.sessionId,
                                        })
                                        .then(result=>{
                                            if(result.error)
                                                alert(result.error.message)
                                        });
                                }else{
                                    showErrorNotify(data.msg);
                                }
                            },
                            error:error=>{
                                console.log(error);
                                $('body').html(error.responseText);
                            }
                        }
                    )

                }else{
                    $("<input />").attr("type", "hidden")
                        .attr("name", "payment_type")
                        .attr("value", payment_type)
                        .appendTo("#form");
                    $("<input />").attr("type", "hidden")
                        .attr("name", "coin_type")
                        .attr("value", $('#coin_type').val())
                        .appendTo("#form");
                    $("#form").submit();
                }
            }

            function makeMacAddressFormat(targetElement) {
                var origin_value=$(targetElement).val();
                var max_count=origin_value.length>=16 ? 16 : origin_value.length;
                for(var i=2;i<max_count;i+=3) {
                    if (origin_value[i] !== ':')
                        origin_value = [origin_value.slice(0,i),':',origin_value.slice(i)].join('');
                }
                $(targetElement).val(origin_value);
            }

        </script>

    </div>

@endsection
