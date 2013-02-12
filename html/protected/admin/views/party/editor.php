<?php
$navBarItems = array();

if (!$model->isNewRecord) {
    array_push($navBarItems, '', array('label' => 'Create', 'url' => array('create'),
            ), '', array('label' => 'Delete', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this party?')), '');
}

$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => 'Parties',
    'brandUrl' => array('party/index'),
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
        'id' => 'party-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

        <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
<?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 2048)); ?>
<?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'abbr'); ?>
<?php echo $form->textField($model, 'abbr', array('size' => 60, 'maxlength' => 128)); ?>
        <?php echo $form->error($model, 'abbr'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'initial'); ?>
<?php echo $form->textField($model, 'initial', array('size' => 60, 'maxlength' => 16)); ?>
        <?php echo $form->error($model, 'initial'); ?>
    </div>
    
    <div class="row buttons">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save')); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->