<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Shishi Footsteps ERP'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="login-body">
    <?php echo $__env->yieldContent('content'); ?>
</body>
</html>
<?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\layouts\guest.blade.php ENDPATH**/ ?>