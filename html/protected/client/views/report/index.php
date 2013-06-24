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

$currentMonth = date('F');
?>


<div class=" section-divider">
    <h3>Resources</h3>
</div>


<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'label' => 'Help',
    'type' => 'button',
    'htmlOptions' => array(
        'data-toggle' => 'modal',
        'data-target' => '#modalResourcesHelp',
        'id' => 'resourcesHelpButton',
        'class' => 'helpBtn'
    ),
));
?>
<div class="clearfix"></div>


<div class="action_group">
    <?php echo CHtml::link("Google Analytics", $tenantSettings->analytics_link, array('class' => 'action_block', 'target' => '_blank')); ?>
    <?php echo CHtml::link("App Store", $tenantSettings->ios_link, array('class' => 'action_block', 'target' => '_blank')); ?>

</div>

<div class="action_group">
    <?php echo CHtml::link("Google Play", $tenantSettings->android_link, array('class' => 'action_block', 'target' => '_blank')); ?>
    <?php echo CHtml::link("Push Messages", array('pushMessage/index'), array('class' => 'action_block', 'target' => '_blank')); ?>
</div>

<div class="clearfix"></div>


<div class="section-divider">
    <h3>Reports</h3>
</div>


<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'label' => 'Help',
    'type' => 'button',
    'htmlOptions' => array(
        'data-toggle' => 'modal',
        'data-target' => '#modalReportsHelp',
        'id' => 'reportsHelpButton',
        'class' => 'helpBtn'
    ),
));
?>

<h3>Overview for the month of  <?php echo $currentMonth ?>:</h3>

<b>User registrations <span id="totalMonthlyUserCount"></span>:</b>
<div id="monthlyUserRegistrationChart" class="chart" ></div>

<b>Pushes Sent:</b>
<div id="monthlyPushChart"  class="chart" ></div>

<b>Push Responses:</b>
<div id="monthlyUserResponseChart" class="chart" ></div>


<b>Total Installs: <?php echo CHtml::link($userCount, array('mobileUser/index')); ?></b>


<?php
$this->beginWidget(
        'bootstrap.widgets.TbModal', array(
    'id' => 'modalResourcesHelp',
    'autoOpen' => false,
    'htmlOptions' => array(
        'style' => 'width:50%;left:40%',
    ),
));
?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Resources</h4>
</div>
<?php
echo $this->renderPartial('help/_resources');
?>

<div class="modal-footer">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Close',
        'url' => '#',
        'htmlOptions' => array('data-dismiss' => 'modal'),
    ));
    $this->endWidget();
    ?>
</div>
<?php
$this->beginWidget(
        'bootstrap.widgets.TbModal', array(
    'id' => 'modalReportsHelp',
    'autoOpen' => false,
    'htmlOptions' => array(
        'style' => 'width:50%;left:40%'
    ),
));
?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Reports</h4>
</div>
<?php
echo $this->renderPartial('help/_reports');
?>

<div class="modal-footer">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Close',
        'url' => '#',
        'htmlOptions' => array('data-dismiss' => 'modal'),
    ));
    $this->endWidget();
    ?>
</div>