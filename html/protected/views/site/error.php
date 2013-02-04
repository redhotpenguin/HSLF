<?php
$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<p>Error <?php echo $code; ?></p>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>