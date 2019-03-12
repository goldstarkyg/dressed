<?php $__env->startSection('template_title'); ?>
    <?php echo e(Auth::user()->name); ?>'s' Homepage
<?php $__env->stopSection(); ?>

<?php $__env->startSection('template_fastload_css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <?php
                Auth::logout();
                Session::flush();
                ?>
                <script>location.href = '/';</script>

            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.firstapp', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>