<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('content')); ?>:</b>
	<?php echo CHtml::encode($data->content); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('state_abbr')); ?>:</b>
	<?php echo CHtml::encode($data->state_abbr); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('district_number')); ?>:</b>
	<?php echo CHtml::encode($data->district_number); ?>
	<br />


</div>