<?php
$navBarItems = array();

if (!$model->isNewRecord) {
    array_push($navBarItems, '', array('label' => 'Create', 'url' => array('create'),
            ), '', array('label' => 'Delete', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this scorecard item?')), '');
}

$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => 'Scorecard Items',
    'brandUrl' => array('scorecardItem/index'),
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
        'id' => 'scorecard-item-form',
        'enableAjaxValidation' => false,
            ));
    ?>


    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('size' => 60, 'class' => 'span7', 'maxlength' => 4096)); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'description'); ?>
        <?php echo $form->textArea($model, 'description', array('rows' => 6, 'class' => 'span7', 'cols' => 10)); ?>
        <?php echo $form->error($model, 'description'); ?>
    </div>

    <div class="row">
        <?php
        $office_list = CHtml::listData(Office::model()->findAll(), 'id', 'name');

        echo $form->labelEx($model, 'office_id');
        echo $form->dropDownList($model, 'office_id', $office_list);
        echo $form->error($model, 'office_id');
        ?>
    </div>

    <div class="row buttons">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save')); ?>

    </div>

    <?php
    $this->endWidget();


    ?>

</div><!-- form -->