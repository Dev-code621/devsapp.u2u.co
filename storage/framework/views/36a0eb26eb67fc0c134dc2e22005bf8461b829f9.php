<head>
    <?php
        $title=isset($title) ? $title : "FLIX IPTV";
        $meta_keyword=isset($keyword) ? $keyword : "";
        $meta_description=isset($description) ? $description : "";
    ?>
    <title><?php echo e($title); ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="<?php echo e($meta_keyword); ?>">
    <meta name="description" content="<?php echo e($meta_description); ?>">

    <link rel="stylesheet" href="<?php echo e(asset('/admin/template/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('/admin/template/css/bootstrap-extend.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('/frontend/css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('/admin/template/fonts/font-awesome/font-awesome.css')); ?>">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo e(asset('/images/apple-icon-57x57.png')); ?>">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo e(asset('/images/apple-icon-60x60.png')); ?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo e(asset('/images/apple-icon-72x72.png')); ?>">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo e(asset('/images/apple-icon-76x76.png')); ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo e(asset('/images/apple-icon-114x114.png')); ?>">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo e(asset('/images/apple-icon-120x120.png')); ?>">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo e(asset('/images/apple-icon-144x144.png')); ?>">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo e(asset('/images/apple-icon-152x152.png')); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('/images/apple-icon-180x180.png')); ?>">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo e(asset('/images/android-icon-192x192.png')); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('/images/favicon-32x32.png')); ?>">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo e(asset('/images/favicon-96x96.png')); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('/images/favicon-16x16.png')); ?>">

    <link rel="shortcut icon" href="<?php echo e(asset('/images/favicon.ico')); ?>" type="image/x-icon">
    <link rel="icon" href="<?php echo e(asset('/images/favicon.ico')); ?>" type="image/x-icon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<?php /**PATH D:\New folder\devsapp.u2u.co.2021-09-11_03_00_21\devsapp.u2u.co\resources\views/frontend/layouts/head.blade.php ENDPATH**/ ?>