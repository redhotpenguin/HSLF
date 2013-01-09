<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/themes/dashboard/js/form/mobile_user.js');


$this->breadcrumbs = array(
    'Mobile users',
);

$data = array("tags"=>"Tag");
?>

<h1>Mobile Users</h1>

<div id="mobile_user_count"><?php echo $mobile_user_count; ?> mobile users</div>



<div class="form">

    <?php
    echo CHtml::beginForm('mobileUser/push', "POST");
    ?>
    <div class="row">
        <?php
        echo CHtml::dropDownList("filters[]", "tag", $data);
        echo ' is ';
        echo CHtml::textField("filterValues[]")
        ?>
    </div>


    <div class="row">
        <?php
        echo CHtml::submitButton("Send a message");
        ?>
    </div>

    <?php
    echo CHtml::endForm();
    ?>    
</div>

