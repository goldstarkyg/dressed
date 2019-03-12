<?php $__env->startSection('template_title'); ?>
    Welcome <?php echo e(Auth::user()->name); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('head'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <?php
                $usercount = DB::table('users')->where('activated', 1)->where('id', '!=', 1)->count();
                $lastmonth = date('Y-m-01');
                $lastusercount = DB::table('users')->where('activated', 1)->where('id', '!=', 1)->where('created_at', '<=', $lastmonth)->count();
                if($lastusercount != 0){
                    $userpercent = round(($usercount - $lastusercount) / $lastusercount * 100);
                }else{
                    $userpercent = 0;
                }
                $postcount = DB::table('posts')->where('status', 1)->count();
                $lastpostcount = DB::table('posts')->where('status', 1)->where('created_at', '<=', $lastmonth)->count();
                if($lastpostcount != 0){
                    $postpercent = round(($postcount - $lastpostcount) / $lastpostcount * 100);
                }else{
                    $postpercent = 0;
                }
                $commentcount = DB::table('comments')->where('status', 1)->count();
                $lastcommentcount = DB::table('comments')->where('status', 1)->where('created_at', '<=', $lastmonth)->count();
                if($lastcommentcount != 0){
                    $commentpercent = round(($commentcount - $lastcommentcount) / $lastcommentcount * 100);
                }else{
                    $commentpercent = 0;
                }
                $usermonths = array();
                    $year = date('Y');
                for($i = 1; $i< 13; $i++){
                    $k = $i;
                    if($i < 10) $k = '0'.$i;
                    $enddate = $year.'-'.$k.'-31 23:59:59';
                    $startdate = $year.'-'.$k.'-01 00:00:00';
                    $monthcount = DB::table('users')->where('activated', 1)->where('id', '!=', 1)->where('created_at', '>=', $startdate)->where('created_at', '<=', $enddate)->count();
                    $usermonths[] = array('month'=>$i, 'count'=>$monthcount);
                }
                $postmonths = array();
                $year = date('Y');
                for($i = 1; $i< 13; $i++){
                    $k = $i;
                    if($i < 10) $k = '0'.$i;
                    $enddate = $year.'-'.$k.'-31 23:59:59';
                    $startdate = $year.'-'.$k.'-01 00:00:00';
                    $monthcount = DB::table('posts')->where('status', 1)->where('created_at', '>=', $startdate)->where('created_at', '<=', $enddate)->count();
                    $postmonths[] = array('month'=>$i, 'count'=>$monthcount);
                }

                ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>Users</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?php echo e(number_format($usercount)); ?></h1>
                                <div class="stat-percent font-bold text-info">
                                    <?php if($userpercent >= 0): ?>
                                    <?php echo e($userpercent); ?>% <i class="fa fa-level-up"></i>
                                    <?php else: ?>
                                        <?php echo e($userpercent); ?>% <i class="fa fa-level-down"></i>
                                    <?php endif; ?>
                                </div>
                                <small>New users</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>Posts</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?php echo e(number_format($postcount)); ?></h1>
                                <div class="stat-percent font-bold text-info">
                                    <?php if($postpercent >= 0): ?>
                                        <?php echo e($postpercent); ?>% <i class="fa fa-level-up"></i>
                                    <?php else: ?>
                                        <?php echo e($postpercent); ?>% <i class="fa fa-level-down"></i>
                                    <?php endif; ?>
                                </div>
                                <small>New posts</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>Comments</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?php echo e(number_format($commentcount)); ?></h1>
                                <div class="stat-percent font-bold text-info">
                                    <?php if($commentpercent >= 0): ?>
                                        <?php echo e($commentpercent); ?>% <i class="fa fa-level-up"></i>
                                    <?php else: ?>
                                        <?php echo e($commentpercent); ?>% <i class="fa fa-level-down"></i>
                                    <?php endif; ?>
                                </div>
                                <small>New posts</small>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5>Users</h5>
                                </div>
                                <div class="ibox-content">
                                    <div class="flot-chart">
                                        <div class="flot-chart-content" id="flot-bar-chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5>Posts</h5>
                                </div>
                                <div class="ibox-content">

                                    <div class="flot-chart">
                                        <div class="flot-chart-content" id="flot-line-chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    </div>
                    <?php
                    $posts = DB::table('posts as p')
                        ->leftJoin('users as u', 'u.name', '=', 'p.user_id')
                        ->leftJoin('styles as s', 's.id', '=', 'p.style_id')
                        ->select('p.*', 'u.first_name', 'u.last_name', 'u.avatar', 's.name as stylename')
                        ->orderby('p.created_at', 'desc')->limit(10)->get();
                    ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5>Recent posts </h5>
                                </div>
                                <div class="ibox-content">
                                    <table class="table table-striped table-bordered table-hover " id="editable" >
                                        <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Post Photo</th>
                                            <th>Post Caption</th>
                                            <th>Style</th>
                                            <th>Posted time</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="gradeX">
                                            <td>
                                                <img src="<?php echo e($post->avatar); ?>" style="height:50px;max-width:50px;">
                                                &nbsp;<?php echo e($post->first_name); ?>&nbsp;<?php echo e($post->last_name); ?>

                                            </td>
                                            <td>
                                                <img src="/img/posts/<?php echo e($post->photo); ?>" style="width:100px;">
                                                <?php if(!empty($post->photo2)): ?>
                                                    <img src="/img/posts/<?php echo e($post->photo2); ?>" style="width:100px;">
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo base64_decode($post->subject, 'utf-8');
                                                ?>
                                            </td>
                                            <td class="center">
                                                <?php echo e($post->stylename); ?>

                                            </td>
                                            <td class="center">
                                                <?php echo e($post->created_at); ?>

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
    </div>

    <!-- Data Tables -->
    <link href="/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
    <link href="/css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">
    <link href="/css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">
    <!-- Data Tables -->
    <script src="/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="/js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="/js/plugins/dataTables/dataTables.responsive.js"></script>
    <script src="/js/plugins/dataTables/dataTables.tableTools.min.js"></script>
    <!-- Flot -->
    <script src="js/plugins/flot/jquery.flot.js"></script>
    <script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="js/plugins/flot/jquery.flot.time.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>

    <script>
        $(function() {
            var barOptions = {
                series: {
                    bars: {
                        show: true,
                        barWidth: 0.6,
                        fill: true,
                        fillColor: {
                            colors: [{
                                opacity: 0.8
                            }, {
                                opacity: 0.8
                            }]
                        }
                    }
                },
                xaxis: {
                    tickDecimals: 0
                },
                colors: ["#1ab394"],
                grid: {
                    color: "#999999",
                    hoverable: true,
                    clickable: true,
                    tickColor: "#D4D4D4",
                    borderWidth:0
                },
                legend: {
                    show: false
                },
                tooltip: true,
                tooltipOpts: {
                    content: "%x month: %y users"
                }
            };
            var barData = {
                label: "bar",
                data: [
                    <?php $__currentLoopData = $usermonths; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usermonth): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    [<?php echo e($usermonth['month']); ?>, <?php echo e($usermonth['count']); ?>],
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ]
            };
            $.plot($("#flot-bar-chart"), [barData], barOptions);

        });
        $(function() {
            var barOptions = {
                series: {
                    lines: {
                        show: true,
                        lineWidth: 2,
                        fill: true,
                        fillColor: {
                            colors: [{
                                opacity: 0.0
                            }, {
                                opacity: 0.0
                            }]
                        }
                    }
                },
                xaxis: {
                    tickDecimals: 0
                },
                colors: ["#1ab394"],
                grid: {
                    color: "#999999",
                    hoverable: true,
                    clickable: true,
                    tickColor: "#D4D4D4",
                    borderWidth:0
                },
                legend: {
                    show: false
                },
                tooltip: true,
                tooltipOpts: {
                    content: "%x month: %y posts"
                }
            };
            var barData = {
                label: "bar",
                data: [
                   <?php $__currentLoopData = $postmonths; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usermonth): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        [<?php echo e($usermonth['month']); ?>, <?php echo e($usermonth['count']); ?>],
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ]
            };
            $.plot($("#flot-line-chart"), [barData], barOptions);

        });

        /* Init DataTables */
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>