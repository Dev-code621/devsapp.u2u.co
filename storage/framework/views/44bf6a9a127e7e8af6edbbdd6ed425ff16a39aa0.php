<?php $__env->startSection('content'); ?>
    <style>
        #login-form {
            max-width: 450px;
            margin: 50px auto;
            border: 1px solid #3e8ef7;
        }
        #login-title {
            text-align: center;
            background: #3e8ef7;
            padding: 10px;
            font-size: 20px;
            color: #fff;
            font-weight: bold;
        }
        #login-body {
            padding: 15px;
        }
        #login-btn {
            width: 150px;
            font-size: 20px;
        }
    </style>
    <div class="news-section-container">
        <?php if(session()->has('message')): ?>
            <div class="alert alert-success">
                 <?php echo session()->get('message');?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo e(url('/device/login')); ?>">
            <?php echo csrf_field(); ?>
            <div id="login-form">
                <div id="login-title">Device Login</div>
                <div id="login-body">
                    <div class="form-group">
                        <label>Device Mac Address</label>
                        <input class="form-control mac_address" value="<?php echo e(old('mac_address')); ?>" name="mac_address" required>
                    </div>
                    <div class="form-group">
                        <label>Device Key</label>
                        <input class="form-control" type="password" name="device_key"  autocomplete="new-password" required>
                    </div>
                    <?php if(session()->has('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo session()->get('error');?>
                        </div>
                    <?php endif; ?>
                    <div id="login-btn-container" class="text-center">
                        <button class="btn btn-primary" id="login-btn">Login</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo e(env('GOOGLE_RECAPTCHA_KEY')); ?>"></script>
    <script>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.template',['menu'=>"mylist"], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\New folder\devsapp.u2u.co.2021-09-11_03_00_21\devsapp.u2u.co\resources\views/frontend/device_login.blade.php ENDPATH**/ ?>