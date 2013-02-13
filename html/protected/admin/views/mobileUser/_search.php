<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
            ));
    ?>

    <div class="row-fluid">
        <div class="span4">
            <?php
            echo CHtml::label('ID', 'MobileUser__id');

            echo $form->textField($model, '_id', array('class' => 'span11'));
            ?>
        </div>

        <div class="span4">
            <?php
            echo $form->label($model, 'device_type');
            echo $form->dropDownList($model, 'device_type', array('' => 'Any', 'ios' => 'iOs', 'android' => 'Android'), array('class' => 'span11'));
            ?>
        </div>


        <div class="span4">
            <?php
            echo CHtml::label('Tag', 'MobileUser_tags');
            echo $form->textField($model, 'tags', array('size' => 60, 'maxlength' => 256, 'class' => 'span11'));
            ?>
        </div>

    </div>
    <div class="row-fluid buttons">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'search   ')); ?>

    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->