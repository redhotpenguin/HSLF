<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/themes/dashboard/js/form/mobile_user.js');


$this->breadcrumbs = array(
    'Mobile users',
);

$this->menu = array(
    array('label' => 'Browse', 'url' => array('browse')),
);
?>

<div class="hero-unit">
    <h1> <span id="mobile_user_count"><?php echo $mobile_user_count; ?></span> mobile users</h1>
</div>


<div class="form">

    <?php
    echo CHtml::beginForm('mobileUser/push', "POST", array("id" => "mobile_user_form"));
    ?>


    <div class="row">
        <?php
        echo CHtml::label("Device type", "device_type");
        echo CHtml::dropDownList("device_type", "device_type", array("" => "", "ios" => "iOS", "android" => "Android"));
        ?>
    </div>

    <hr/>

    <div class="row">
        <?php
        echo CHtml::label("Tag", "tags[]");

        echo CHtml::textField("tags[]")
        ?>
    </div>

    <div class="row">
        <?php
        echo CHtml::label("Tag", "tags[]");

        echo CHtml::textField("tags[]")
        ?>
    </div>


    <div class="row">
        <?php
        // echo CHtml::button("Send a notification", array('id'=>'push_btn'));
        ?>
    </div>

    <?php
    echo CHtml::endForm();
    ?>    
</div>

