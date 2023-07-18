<!DOCTYPE html>
<html>
    <?php echo $__env->make('frontend.layouts.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php
        $playlist_id=session('playlist_id');
        $playlist=App\Model\PlayList::find($playlist_id);
        $playlist->urls=$playlist->PlayListUrls;
    ?>
    <body>
        <div class="container">
            <div id="device-page-container">
                <div id="menus-container">
                    <div class="device-page-menu <?php echo e($menu=='playlists' ? 'active' : ''); ?>">
                        <a href="<?php echo e(url('device/playlists')); ?>">
                            Manage Playlists
                        </a>
                    </div>
                    <div class="device-page-menu <?php echo e($menu=='activation' ? 'active' : ''); ?>">
                        <a href="<?php echo e(url('device/activation')); ?>">
                            Activate Device
                        </a>
                    </div>
                    <div id="device-page-logout-menu">
                        <a href="<?php echo e(url('device/logout')); ?>">
                            <i class="fa fa-sign-out">Log out</i>
                        </a>
                    </div>
                </div>
                <div class="content-container" id="device-page-content-container">
                    <div class="device-info-container">
                        <div class="device-info-item-wrapper">
                            <div class="device-info-label">Mac Address:</div>
                            <div class="device-info-value"><?php echo e($playlist->mac_address); ?></div>
                        </div>
                        <div class="device-info-item-wrapper">
                            <div class="device-info-label">Status:</div>
                            <div class="device-info-value">Active</div>
                        </div>
                        <div class="device-info-item-wrapper">
                            <div class="device-info-label">Expiration:</div>
                            <div class="device-info-value"><?php echo e($playlist->expire_date); ?></div>
                        </div>
                    </div>
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
            <?php echo $__env->make('frontend.layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->yieldContent('script'); ?>
        </div>
        <script src="<?php echo e(asset('/admin/js/notify.js')); ?>"></script>
        <script>
            function showSuccessNotify(message) {
                $.notify(message,
                    {
                        className:'success',
                        position:'right 100px',
                    }
                );
            }
            function showErrorNotify(message) {
                $.notify(message,
                    {
                        className:'error',
                        position:'right 100px',
                    }
                );
            }
            $(document).ready(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                'use strict';
            })
        </script>
        <script src="<?php echo e(asset('frontend/playlist.js')); ?>"></script>


    </body>
</html>
<?php /**PATH D:\New folder\devsapp.u2u.co.2021-09-11_03_00_21\devsapp.u2u.co\resources\views/frontend/layouts/device_template.blade.php ENDPATH**/ ?>