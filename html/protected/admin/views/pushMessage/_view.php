<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tenant_id')); ?>:</b>
	<?php echo CHtml::encode($data->tenant_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('payload_id')); ?>:</b>
	<?php echo CHtml::encode($data->payload_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('creation_date')); ?>:</b>
	<?php echo CHtml::encode($data->creation_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('alert')); ?>:</b>
	<?php echo CHtml::encode($data->alert); ?>
	<br />


</div>