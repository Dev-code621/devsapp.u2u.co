<?php $__env->startSection('content'); ?>
    <style>

    </style>
    <?php if($instruction): ?>
        <div class="news-section-container">
            <?= $instruction->contents ?>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.template',['menu'=>"instruction"], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\New folder\devsapp.u2u.co.2021-09-11_03_00_21\devsapp.u2u.co\resources\views/frontend/instruction.blade.php ENDPATH**/ ?>