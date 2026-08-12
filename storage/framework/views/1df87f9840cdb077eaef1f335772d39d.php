<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Sign in · Shishi Footsteps ERP</title>
    <link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>" sizes="any">
    <link rel="apple-touch-icon" href="<?php echo e(asset('images/brand/apple-touch-icon.png')); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Great+Vibes&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="login-body">
    <main class="login-shell">
        <section class="login-visual">
            <div class="login-visual-backdrop"></div>
            <div class="login-brand"><img src="<?php echo e(asset('images/brand/shishi-footsteps-white.png')); ?>" alt="Shishi Footsteps"><small>Safari ERP</small></div>
            <div class="login-story">
                <span>One connected travel operation</span>
                <h1>Every journey,<br><em>beautifully managed.</em></h1>
                <p>From the first website request to the final safari evaluation, your team plans, prices, reserves and delivers from one secure workspace.</p>
                <div class="login-proof"><div><strong>360°</strong><small>Trip visibility</small></div><div><strong>24/7</strong><small>Operations control</small></div><div><strong>1</strong><small>Source of truth</small></div></div>
            </div>
            <small class="login-visual-footer">Shishi Footsteps · East Africa journey management</small>
        </section>
        <section class="login-panel">
            <div class="login-card">
                <div class="login-mobile-brand"><img src="<?php echo e(asset('images/brand/shishi-footsteps-green.png')); ?>" alt="Shishi Footsteps"></div>
                <p class="eyebrow">Secure team access</p>
                <h2>Welcome back</h2>
                <p class="login-intro">Sign in to manage clients, itineraries, quotations, reservations and operations.</p>

                <?php if(session('status')): ?><div class="login-message"><i data-lucide="check-circle-2"></i><?php echo e(session('status')); ?></div><?php endif; ?>
                <?php if($errors->any()): ?><div class="login-error"><i data-lucide="circle-alert"></i><?php echo e($errors->first()); ?></div><?php endif; ?>

                <form method="POST" action="<?php echo e(route('login.store')); ?>" class="login-form">
                    <?php echo csrf_field(); ?>
                    <label>Email address<div><i data-lucide="mail"></i><input type="email" name="email" value="<?php echo e(old('email')); ?>" placeholder="name@company.com" autocomplete="email" autofocus required></div></label>
                    <label>Password<div><i data-lucide="lock-keyhole"></i><input id="login-password" type="password" name="password" placeholder="Enter your password" autocomplete="current-password" required><button type="button" data-password-toggle aria-label="Show password"><i data-lucide="eye"></i></button></div></label>
                    <div class="login-options"><label><input type="checkbox" name="remember" value="1"> Keep me signed in</label><span>Protected access</span></div>
                    <button class="login-submit">Sign in to Safari ERP<i data-lucide="arrow-right"></i></button>
                </form>
                <div class="login-security"><i data-lucide="shield-check"></i><span><strong>Secure workspace</strong><small>Your session and account activity are protected.</small></span></div>
            </div>
        </section>
    </main>
    <script src="https://unpkg.com/lucide@0.468.0/dist/umd/lucide.min.js"></script>
</body>
</html>
<?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\auth\login.blade.php ENDPATH**/ ?>