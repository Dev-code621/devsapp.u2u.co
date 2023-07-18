<?php
$menu=isset($menu) ? $menu : "";
?>
<div class="header">
    <div class="header-items-container">
        <div class="header-item-wrapper <?php echo e($menu=='news' ? 'active' : ''); ?>">
            <a href="<?php echo e(url('/news')); ?>">News</a>
        </div>
        <div class="header-item-wrapper <?php echo e($menu=='faq' ? 'active' : ''); ?>">
            <a href="<?php echo e(url('/faq')); ?>">Support</a>
        </div>
        <div class="header-item-wrapper <?php echo e($menu=='instruction' ? 'active' : ''); ?>">
            <a href="<?php echo e(url('/instruction')); ?>">Instruction</a>
        </div>
        <div class="header-item-wrapper <?php echo e($menu=='mylist' ? 'active' : ''); ?>">
            <a href="<?php echo e(url('/device/playlists')); ?>">Manage PlayLists</a>
        </div>
        <div class="header-item-wrapper <?php echo e($menu=='reseller' ? 'active' : ''); ?>">
            <a href="<?php echo e(url('/become-a-reseller')); ?>">Become a Reseller</a>
        </div>

    </div>
</div>
<?php /**PATH D:\New folder\devsapp.u2u.co.2021-09-11_03_00_21\devsapp.u2u.co\resources\views/frontend/layouts/header.blade.php ENDPATH**/ ?>