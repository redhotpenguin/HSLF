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

    <div id="filters"">

        <span class="delete_tag" style="display:none;" id="delete_tag_original">X</span>

        <?php
        echo CHtml::beginForm('mobileUser/sendAlert', "POST", array("id" => "mobile_user_form"));
        ?>


        <div class="row">
            <?php
            echo CHtml::label("Device type", "device_type");
            echo CHtml::dropDownList("device_type", "device_type", array("" => "", "ios" => "iOS", "android" => "Android"));
            ?>
        </div>

        <hr/>

        <div id="tag_list">
            <div class="row tagBox" id="original_tag">
                <?php
                echo CHtml::label("Tag", "tags[]");

                echo CHtml::textField("tags[]", "", array('class' => 'tagInput'))
                ?>
            </div>
        </div>

        <div class="row">
            <?php
            echo CHtml::button("Add a tag", array('id' => 'add_tag_btn'));
            ?>
        </div>


        <div class="row">
            <?php
            echo CHtml::button("Compose an alert", array('id' => 'compose_alert_btn', 'class' => 'btn-large btn-primary'));
            ?>

            <?php
            echo CHtml::button("Export current selection", array('id' => 'export_btn', 'class' => 'btn-large btn-info'));
            ?>
        </div>

    </div>


    <div id="composer" style="display:none;">
        <div class="row-fluid">

            <?php
            echo CHtml::label("Alert", "alert");
            echo CHtml::textArea("alert", null, array('id'=>'composer_input','placeholder' => 'Message goes here', 'class' => 'span12', 'rows' => 3));
            ?>

        </div>
        <div class="row-fluid">

            <?php
            echo CHtml::button("Go!", array('id' => 'send_alert_btn', 'class' => 'btn-large btn-primary'));
            ?>

            <?php
            echo CHtml::button("Cancel", array('id' => 'cancel_alert_btn', 'class' => 'btn-large btn-warning'));
            ?>
        </div>
    </div>
    
    <div id="push_result"></div>
    
    <?php
    echo CHtml::endForm();
    ?>

</div>

