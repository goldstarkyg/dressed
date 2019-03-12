<!DOCTYPE html>
<html lang="<?php echo e(config('app.locale')); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow, noydir, noodp, nosnippet, noimageindex">
    
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php if(trim($__env->yieldContent('template_title'))): ?><?php echo $__env->yieldContent('template_title'); ?> | <?php endif; ?> <?php echo trans('titles.app'); ?></title>
    <meta name="description" content="">
    <meta name="author" content="Dressd">
    <link rel="shortcut icon" href="/favicon.ico">

    
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    
    <?php echo $__env->yieldContent('template_linked_fonts'); ?>

    

    <?php echo $__env->yieldContent('template_linked_css'); ?>

    <style type="text/css">
        <?php echo $__env->yieldContent('template_fastload_css'); ?>

            <?php if(Auth::User() && (Auth::User()->profile) && (Auth::User()->profile->avatar_status == 0)): ?>
                .user-avatar-nav {
            background: url(<?php echo e(Gravatar::get(Auth::user()->email)); ?>) 50% 50% no-repeat;
            background-size: auto 100%;
        }
        <?php endif; ?>

    </style>

    
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>;
    </script>

    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="/css/animate.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/mainadmin.css" rel="stylesheet">

    <!-- Mainly scripts -->
    <script src="/js/jquery-2.1.1.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="/js/inspinia.js"></script>
    <script src="/js/plugins/pace/pace.min.js"></script>

    <?php echo $__env->yieldContent('head'); ?>

</head>
<body class="top-navigation">

<div id="wrapper">
    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom white-bg">
            <?php echo $__env->make('partials.nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
        <div class="wrapper wrapper-content">
            <?php echo $__env->make('partials.form-status', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php echo $__env->yieldContent('content'); ?>
        </div>
        <div class="footer">
            <div class="pull-right">

            </div>
            <div>
                <strong>Copyright</strong> Dressd &copy; <?php echo e(date('Y')); ?>

            </div>
        </div>
    </div>
</div>


<?php echo $__env->yieldContent('footer_scripts'); ?>

</body>
</html>