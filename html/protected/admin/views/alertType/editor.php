<?php
$navBarItems = array();

if (!$model->isNewRecord) {
    array_push($navBarItems, '', array('label' => 'Create', 'url' => array('create'),
            ), '', array('label' => 'Delete', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this alert type?')), '');
}

$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => 'Alert Types',
    'brandUrl' => array('alertType/index'),
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
        'id' => 'alert-type-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'class' => 'form-vertical'),
            ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="">
        <?php echo $form->labelEx($model, 'display_name'); ?>
        <?php echo $form->textField($model, 'display_name', array('size' => 60, 'maxlength' => 1024)); ?>
        <?php echo $form->error($model, 'display_name'); ?>
    </div>

    <div class="">
        <?php echo $form->labelEx($model, 'category'); ?>
        <?php echo $form->textField($model, 'category', array('size' => 60, 'maxlength' => 512, 'placeholder' => 'Ex: organization')); ?>
        <?php echo $form->error($model, 'category'); ?>
    </div>

    <div class="">
        <?php
        echo $form->labelEx($model, 'tag_id');
        $tagList = CHtml::listData(Tag::model()->findAllByAttributes(array('type' => 'alert')), 'id', 'name');
        echo $form->dropDownList($model, 'tag_id', $tagList);
        echo $form->error($model, 'tag_id');
        ?>
    </div>

    <div class="buttons">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->