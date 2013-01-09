<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/themes/dashboard/js/form/mobile_user.js');


$this->breadcrumbs = array(
    'Mobile users',
);

$data = array("tags" => "Tag");
?>

<h1>Mobile Users</h1>

<h3> <span id="mobile_user_count"><?php echo $mobile_user_count; ?></span> mobile users</h3>



<div class="form">

    <?php
    echo CHtml::beginForm('mobileUser/push', "POST", array("id" => "mobile_user_form"));
    ?>


    <div class="row">
        <?php
        echo CHtml::label("Device type", "device_type");
        echo CHtml::dropDownList("device_type", "device_type", array("" => "", "ios" => "IOS", "android" => "Android"));
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

