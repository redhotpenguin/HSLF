<h1>Reports</h1>

<h2>Overview for <?php echo date('F Y') ?></h2>

<p>Total Push Sends: <?php echo $totalPushSent; ?> </p>

<hr/>
<div class="action_group">
    <?php echo CHtml::link("Google Analytics", $tenantSettings->analytics_link, array('class' => 'action_block')); ?>
    <?php echo CHtml::link("App Store", $tenantSettings->ios_link, array('class' => 'action_block')); ?>

</div>

<div class="action_group">
    <?php echo CHtml::link("Google Play", $tenantSettings->android_link, array('class' => 'action_block')); ?>
    <?php echo CHtml::link("Push Messages", array('pushMessage/index'), array('class' => 'action_block')); ?>
</div>

<div class="clearfix"></div>

<hr/>

<div class="heroUserCount">
    <h2><?php echo CHtml::link($userCount, array('mobileUser/index')); ?> people have installed your application.</h2>
</div>

<div class="section-divider">
    <h3>Customers</h3>
</div>