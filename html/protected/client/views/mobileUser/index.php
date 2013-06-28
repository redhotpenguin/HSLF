<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/static/mobileuser/mobile_user.js');


$tenant = Yii::app()->user->getLoggedInUserTenant();

$action_url = Yii::app()->params['site_url'] . '/client/' . $tenant->name;


$ns = "var ns  = {action_url: '" . $action_url . "'};";


Yii::app()->clientScript->registerScript('settings-script', $ns, CClientScript::POS_HEAD);


if ($isAdmin)
    $navBarItems = array(
        '',
        array('label' => 'Browse', 'url' => array('browse')),
        '',
    );

else
    $navBarItems = array();



$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Mobile Users';
$this->secondaryNav['url'] = array('mobileUser/index');
?>

<div class="hero-unit">
    <h1> <span id="mobile_user_count"><?php echo number_format($mobile_user_count); ?></span> mobile users</h1>
</div>


<div class="form">

    <div id="filters">

        <?php
        echo CHtml::beginForm('/', "POST", array("id" => "mobile_user_form"));
        ?>

        <hr/>

        <div class="row">
            <h4>Tags</h4>
            <?php
            $this->widget('backend.extensions.TagSelector.TagSelector', array(
                'model' => new MobileUser,
                'tag_types' => array('organization', 'alert'),
                'display_tag_creator' => false,
            ));
            ?>
        </div>

        <div class="row">
            <h4>Device Type:</h4>
            <?php
            echo CHtml::dropDownList("device_type", "device_type", array("" => "Any", "ios" => "iOS", "android" => "Android"));
            ?>
        </div>

        <hr/>

        <div class="row">
            <h4>Push only:</h4>
            <em>Users who have push enabled</em>

            <?php
            echo CHtml::checkBox("push_only", false, array("id" => "push_only_checkbox"));
            ?>

        </div>

        <hr/>


        <div style="margin-top:20px" class="row">
            <?php
            if ($isAdmin) {
                $this->widget('bootstrap.widgets.TbButton', array(
                    'label' => 'Export current selection',
                    'type' => 'info',
                    'size' => 'large',
                    'htmlOptions' => array('id' => 'export_btn', 'style' => 'float:right;')
                ));
            }
            ?>
        </div>

    </div>

    <?php
    echo CHtml::endForm();
    ?>

</div>

