<?php
$navBarItems = array();

if (!$model->isNewRecord) {
    array_push($navBarItems, '', array('label' => 'Create', 'url' => array('create'),
            ), '', array('label' => 'Delete', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this option?')), '');
}

$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => 'Options',
    'brandUrl' => array('option/index'),
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
        'id' => 'option-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'class' => 'form-vertical'),
            ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row-fluid">
        <?php
        echo $form->labelEx($model, 'name');
        $nameOptions = array('size' => 60, 'class' => 'span12');
        if (!$model->isNewRecord)
            $nameOptions['readonly'] = 'readonly';

        echo $form->textField($model, 'name', $nameOptions);
        echo $form->error($model, 'name');
        ?>
    </div>

    <div class="row-fluid">
        <?php echo $form->labelEx($model, 'value'); ?>
        <?php echo $form->textArea($model, 'value', array('rows' => 6, 'class' => 'span12')); ?>
        <?php echo $form->error($model, 'value'); ?>
    </div>

    <div class="buttons">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save')); ?>
    </div>

    <?php
    $this->endWidget();

    if (getParam('updated') == '1') {
        echo '<div class="update_box btn-success">Option successfully updated</div>';
    } elseif (getParam('created') == '1') {
        echo '<div class="update_box btn-success">Option successfully saved</div>';
    }
    ?>

</div><!-- form -->