<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/static/libs/jqplot/js/jquery.jqplot.min.js');
$cs->registerScriptFile($baseUrl . '/static/libs/jqplot/plugins/jqplot.barRenderer.min.js');
$cs->registerScriptFile($baseUrl . '/static/libs/jqplot/plugins/jqplot.categoryAxisRenderer.min.js');
$cs->registerScriptFile($baseUrl . '/static/libs/jqplot/plugins/jqplot.highlighter.js');



$cs->registerCssFile($baseUrl . '/static/libs/jqplot/css/jquery.jqplot.min.css');
$cs->registerScriptFile($baseUrl . '/static/report/js/report.js', CClientScript::POS_END);
$cs->registerCssFile($baseUrl . '/static/report/css/report.css');
$tenant = Yii::app()->user->getLoggedInUserTenant();
$controller_url = Yii::app()->params['site_url'] . '/client/' . $tenant->name;
$ns = "var report_ns  = {controller_url: '" . $controller_url . "'};";

Yii::app()->clientScript->registerScript('settings-script', $ns, CClientScript::POS_HEAD);
?>



<div class="section-divider">
    <h3>Reports</h3>
</div>


<b>Pushes sent for the month of <?php echo date('F'); ?>:</b>
<div id="monthlyPushChart" ></div>

<b>Total Installs: <?php echo CHtml::link($userCount, array('mobileUser/index')); ?></b>



<div class="section-divider">
    <h3>Resources</h3>
</div>


<div class="action_group">
    <?php echo CHtml::link("Google Analytics", $tenantSettings->analytics_link, array('class' => 'action_block')); ?>
    <?php echo CHtml::link("App Store", $tenantSettings->ios_link, array('class' => 'action_block')); ?>

</div>

<div class="action_group">
    <?php echo CHtml::link("Google Play", $tenantSettings->android_link, array('class' => 'action_block')); ?>
    <?php echo CHtml::link("Push Messages", array('pushMessage/index'), array('class' => 'action_block')); ?>
</div>




