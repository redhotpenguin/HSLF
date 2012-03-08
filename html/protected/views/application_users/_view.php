<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('device_token')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->device_token), array('view', 'id'=>$data->device_token)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::encode($data->id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('latitude')); ?>:</b>
	<?php echo CHtml::encode($data->latitude); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('longitude')); ?>:</b>
	<?php echo CHtml::encode($data->longitude); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('state_abbr')); ?>:</b>
	<?php echo CHtml::encode($data->state_abbr); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('district_number')); ?>:</b>
	<?php echo CHtml::encode($data->district_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('registration')); ?>:</b>
	<?php echo CHtml::encode($data->registration); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('user_agent')); ?>:</b>
	<?php echo CHtml::encode($data->user_agent); ?>
	<br />

	*/ ?>

</div>