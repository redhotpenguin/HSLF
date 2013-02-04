<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/static/mobileuser/mobile_user.js');

 $tenant = Yii::app()->user->getCurrentTenant();
 
 $action_url = getSetting('site_url').'/admin/'.$tenant->name;
 

$ns = "var ns  = {action_url: '" . $action_url . "'};";


Yii::app()->clientScript->registerScript('settings-script', $ns, CClientScript::POS_HEAD);


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
        <span class="delete_tag" style="display:none;" id="delete_district_original">X</span>


        <?php
        echo CHtml::beginForm('mobileUser/sendAlert', "POST", array("id" => "mobile_user_form"));
        ?>

        <div id="tag_list">
            <h4>Tags: </h4>
            <em>Users who have at least one of the following tags</em>

            <div class="row tagBox" id="original_tag">
                <?php
                echo CHtml::textField("tags[]", "", array('class' => 'tagInput'));
                ?>
            </div>
        </div>

        <div class="row">
            <?php
            echo CHtml::button("Add a tag", array('id' => 'add_tag_btn'));
            ?>
        </div>

        <hr/>

        <div id="district_list">
            <h4>Districts:</h4>
            <em>Users who are least in one of the following districts</em>

            <div class="row districtBox" id="original_district">
                <?php
                echo CHtml::textField("districts[]", "", array('class' => 'districtInput'));
                ?>
            </div>
        </div>

        <div class="row">
            <?php
            echo CHtml::button("Add a district", array('id' => 'add_district_btn'));
            ?>
        </div>

        <hr/>

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
            echo CHtml::button("Compose an alert", array('id' => 'compose_alert_btn', 'class' => 'btn-large btn-primary'));
            ?>

            <?php
            echo CHtml::button("Export current selection", array('id' => 'export_btn', 'style' => 'float:right;', 'class' => 'btn-large btn-info'));
            ?>
        </div>

    </div>



    <div id="composer" style="display:none;
         ">
        <div class="row-fluid">
            <h4>Alert:</h4>
            <?php
            echo CHtml::textArea("alert", null, array('id' => 'composer_input', 'placeholder' => 'Message goes here', 'class' => 'span12', 'rows' => 3));
            ?>

        </div>
        <hr/>
        <div id="key_value_list">
            <h4>Options:</h4>


            <div class="row keyValueBox" id="original_key_value">
                <?php
                echo ' <b>key:</b> ' . CHtml::textField("keys[]", "", array('class' => 'keyInput'));

                echo ' <b> value:</b> ' . CHtml::textField("values[]", "", array('class' => 'valueInput'));
                ?>

            </div>
        </div>

        <div class="row">
            <?php
            echo CHtml::button("Add an option", array('id' => 'add_option_btn'));
            ?>
        </div>

        <hr/>

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

