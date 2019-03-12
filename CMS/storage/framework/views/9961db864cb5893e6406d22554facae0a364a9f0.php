<?php $__env->startSection('template_title'); ?>
  Showing Styles
<?php $__env->stopSection(); ?>

<?php $__env->startSection('template_linked_css'); ?>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <style type="text/css" media="screen">
        .users-table {
            border: 0;
        }
        .users-table tr td:first-child {
            padding-left: 15px;
        }
        .users-table tr td:last-child {
            padding-right: 15px;
        }
        .users-table.table-responsive,
        .users-table.table-responsive table {
            margin-bottom: 0;
        }

    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            Showing All Styles
                            <a href="/styles/create" class="btn btn-default btn-sm pull-right">
                                <i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>
                                Create New Style
                            </a>
                        </div>
                    </div>

                    <div class="panel-body">

                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th class="hidden-sm hidden-xs hidden-md">Created</th>
                                        <th>Actions</th>
                                        <th></th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php  $num = 0  ?>
                                    <?php $__currentLoopData = $styles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $style): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php  $num++  ?>
                                        <tr>
                                            <td><?php echo e($num); ?></td>
                                            <td><?php echo e($style->name); ?></td>
                                            <td class="hidden-sm hidden-xs hidden-md"><?php echo e($style->created_at); ?></td>
                                            <td>
                                                <?php echo Form::open(array('url' => 'styles/' . $style->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')); ?>

                                                    <?php echo Form::hidden('_method', 'DELETE'); ?>

                                                    <?php echo Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">Delete</span><span class="hidden-xs hidden-sm hidden-md"> Style</span>', array('class' => 'btn btn-danger btn-sm','type' => 'button', 'style' =>'width: 100%;' ,'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Delete Style', 'data-message' => 'Are you sure you want to delete this style ?')); ?>

                                                <?php echo Form::close(); ?>

                                            </td>
                                            
                                                
                                                    
                                                
                                            
                                            <td>
                                                <a class="btn btn-sm btn-info btn-block" href="<?php echo e(URL::to('styles/' . $style->id . '/edit')); ?>" data-toggle="tooltip" title="Edit">
                                                    <i class="fa fa-pencil fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">Edit</span><span class="hidden-xs hidden-sm hidden-md"> Style</span>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('modals.modal-delete', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>

    <?php if(count($styles) > 10): ?>
        <?php echo $__env->make('scripts.datatables', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endif; ?>
    <?php echo $__env->make('scripts.delete-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.save-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>