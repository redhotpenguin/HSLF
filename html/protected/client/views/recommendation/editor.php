<?php
$navBarItems = array();

if (!$model->isNewRecord) {
    array_push($navBarItems, '', array('label' => 'Create', 'url' => array('create'),
            ), '', array('label' => 'Delete', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this recommendation?')), '');
}


$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Recommendations';
$this->secondaryNav['url'] =array('recommendation/index');
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'recommendation-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'form-vertical'),
            ));
    ?>



        <?php echo $form->errorSummary($model); ?>

    <div class="">
        <?php echo $form->labelEx($model, 'value'); ?>
<?php echo $form->textField($model, 'value', array('size' => 60, 'maxlength' => 64)); ?>
<?php echo $form->error($model, 'value'); ?>
    </div>

    <div class="">
        <?php
        echo $form->labelEx($model, 'type');
        echo $form->dropDownList($model, 'type', $model->getTypeOptions());
        echo $form->error($model, 'type');
        ?>
    </div>

    <div class="buttons">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save')); ?>
    </div>

    <?php
    $this->endWidget();


    ?>

</div><!-- form -->