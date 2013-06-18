

<h3> Overview</h3>

<p>Total Push Sends: <b><?php echo CHtml::link($totalPushSent, array('pushMessage/index')); ?> </b> (<?php echo date('F Y') ?>) </p>
<p>Total Installs  : <b><?php echo CHtml::link($userCount, array('mobileUser/index')); ?></b> </p>

<div class="section-divider">
    <h3>Reports</h3>
</div>


<div class="action_group">
    <?php echo CHtml::link("Google Analytics", $tenantSettings->analytics_link, array('class' => 'action_block')); ?>
    <?php echo CHtml::link("App Store", $tenantSettings->ios_link, array('class' => 'action_block')); ?>

</div>

<div class="action_group">
    <?php echo CHtml::link("Google Play", $tenantSettings->android_link, array('class' => 'action_block')); ?>
    <?php echo CHtml::link("Push Messages", array('pushMessage/index'), array('class' => 'action_block')); ?>
</div>
