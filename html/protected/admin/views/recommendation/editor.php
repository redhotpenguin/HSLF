<?php
$navBarItems = array();

if (!$model->isNewRecord) {
    array_push($navBarItems, '', array('label' => 'Create', 'url' => array('create'),
            ), '', array('label' => 'Delete', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this recommendation?')), '');
}

$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => 'Recommendations',
    'brandUrl' => array('recommendation/index'),
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

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'recommendation-form',
	'enableAjaxValidation'=>false,
            'htmlOptions' => array('class' => 'form-vertical'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="">
		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textField($model,'value',array('size'=>60,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'value'); ?>
	</div>

	<div class="">
		<?php echo $form->labelEx($model,'type');
                 echo $form->dropDownList($model, 'type', $model->getTypeOptions());
		 echo $form->error($model,'type'); ?>
	</div>

	<div class="buttons">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->