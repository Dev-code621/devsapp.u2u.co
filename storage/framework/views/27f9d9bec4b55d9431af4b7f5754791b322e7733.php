<!DOCTYPE html>

<html>
    <?php echo $__env->make('frontend.layouts.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <body>
        <div class="container">
            <?php echo $__env->make('frontend.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="content-container">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
            <?php echo $__env->make('frontend.layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->yieldContent('script'); ?>
        </div>

    </body>
</html>
<?php /**PATH D:\New folder\devsapp.u2u.co.2021-09-11_03_00_21\devsapp.u2u.co\resources\views/frontend/layouts/template.blade.php ENDPATH**/ ?>