<?php $__env->startSection('content'); ?>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo e($client_id); ?>&currency=EUR"></script>
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

    </style>

    <div class="news-section-container">
        <?php if($activation_content): ?>
            <?= $activation_content->contents ?>
        <?php endif; ?>
        <?php if(session()->has('message')): ?>
            <div class="alert alert-success">
                <?php echo session()->get('message');?>
            </div>
        <?php endif; ?>
        <?php if(session()->has('error')): ?>
            <div class="alert alert-danger">
                <?php echo session()->get('error');?>
            </div>
        <?php endif; ?>
        <div class="alert alert-success hide" id="success-message"></div>
        <div class="alert alert-danger hide" id="error-message"></div>
        <form id="form" method="post" action="<?php echo e(url('/activation/saveActivation')); ?>">
            <?php echo csrf_field(); ?>
            <div class="row mt-20" style="justify-content: center">
                <div class="col-12 col-md-6 form-group">
                    <div class="form-group">
                        <label>Select Package</label>
                        <select class="form-control" id="price_package">
                            <?php $__currentLoopData = $price_packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($item->id); ?>"><?php echo e($item->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <p id="price-text">
                        <span>Price:</span>
                        <span class="price-value" id="price-value">&euro;</span>
                    </p>
                    <div class="position-relative">
                        <div id="paypal-element"></div>
                        <div id="disabled-container"></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/jquery.validate.js"></script>
    

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
            })

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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.device_template',['menu'=>"activation"], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\New folder\devsapp.u2u.co.2021-09-11_03_00_21\devsapp.u2u.co\resources\views/frontend/activation.blade.php ENDPATH**/ ?>