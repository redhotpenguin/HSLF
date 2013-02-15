<?php
$navBarItems = array();

if (!$model->isNewRecord) {
    array_push($navBarItems, '', array('label' => 'Create', 'url' => array('create'),
            ), '', array('label' => 'Delete', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this district? This will remove ballot items linked to it.')), '');
}

$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => 'Districts',
    'brandUrl' => array('district/index'),
    'htmlOptions' => array('class' => 'subnav'),
    'collapse' => true, // requires bootstrap-responsive.css
    'items' => array(
        array(
            'class' => 'bootstrap.widgets.TbMenu',
            'items' => $navBarItems
        ),
    ),
));
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'district-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'form-vertical'),
            ));
    ?>



    <?php echo $form->errorSummary($model); ?>

    <div class="">
        <?php echo $form->labelEx($model, 'state_id'); ?>
        <?php
        $state_list = CHtml::listData(State::model()->findAll(), 'id', 'name');
        $options = array(
            'tabindex' => '0',
            'empty' => '(not set)',
        );
        echo $form->dropDownList($model, 'state_id', $state_list, $options);
        ?>
        <?php echo $form->error($model, 'state_id'); ?>
    </div>

    <div class="">
        <?php
        echo $form->labelEx($model, 'type');
        echo $form->dropDownList($model, 'type', $model->getTypeOptions(), $options);
        echo $form->error($model, 'type');
        ?>
    </div>

    <div class="">
        <?php echo $form->labelEx($model, 'number'); ?>
        <?php echo $form->textField($model, 'number'); ?>
        <?php echo $form->error($model, 'number'); ?>
    </div>


    <div class="">
        <?php echo $form->labelEx($model, 'display_name'); ?>
        <?php echo $form->textField($model, 'display_name'); ?>
        <?php echo $form->error($model, 'display_name'); ?>
    </div>

    <div class="">
        <?php echo $form->labelEx($model, 'locality'); ?>
        <?php echo $form->textField($model, 'locality'); ?>
        <?php echo $form->error($model, 'locality'); ?>
    </div>


    <div class=" buttons">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save')); ?>
    </div>

    <?php
    $this->endWidget();
    
    if (getParam('updated') == '1') {
        echo '<div class="update_box btn-success">District successfully updated</div>';
    } elseif (getParam('created') == '1') {
        echo '<div class="update_box btn-success">District successfully saved</div>';
    }
    ?>

</div><!-- form -->