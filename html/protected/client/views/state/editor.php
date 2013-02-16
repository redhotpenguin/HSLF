<?php
$navBarItems = array();

if (!$model->isNewRecord) {
    array_push($navBarItems, '', array('label' => 'Create', 'url' => array('create'),
            ), '', array('label' => 'Delete', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this state? This will remove districts and ballot items linked to it.')), '');
}

$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => 'States',
    'brandUrl' => array('state/index'),
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
        'id' => 'state-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'form-vertical'),
            ));
    ?>



    <?php echo $form->errorSummary($model); ?>

    <div class="">
        <?php echo $form->labelEx($model, 'abbr'); ?>
        <?php echo $form->textField($model, 'abbr', array('size' => 3, 'maxlength' => 3)); ?>
        <?php echo $form->error($model, 'abbr'); ?>
    </div>

    <div class="">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 128)); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="buttons">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save')); ?>
    </div>

    <?php
    $this->endWidget();

    ?>

</div><!-- form -->