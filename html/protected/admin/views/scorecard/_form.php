<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'candidate-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <div class="row">
        <?php
        echo $form->labelEx($model, 'type');
        echo $form->dropDownList($model, 'type', $model->getTypeOptions());
        echo $form->error($model, 'type');
        ?>
    </div>

     <div class="row">
        <?php
        echo $form->labelEx($model, 'name');
        echo $form->dropDownList($model, 'name', $model->getNameOptions());
        echo $form->error($model, 'name');
        ?>
    </div>


    <div class="row">
        <?php echo $form->labelEx($model, 'vote'); ?>
        <?php echo $form->dropDownList($model, 'vote', $model->getVoteOptions()); ?>
        <?php echo $form->error($model, 'vote'); ?>
    </div>




    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->