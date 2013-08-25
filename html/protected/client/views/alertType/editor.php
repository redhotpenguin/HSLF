<?php
$navBarItems = array();
if (!$model->isNewRecord) {
    array_push($navBarItems, '', array('label' => 'Create', 'url' => array('create'), ''));


    $this->headerButton = Chtml::linkButton('Delete', array(
                'class' => 'btn btn-danger',
                'submit' => array('delete', 'id' => $model->id),
                'confirm' => 'Are you sure you want to delete this alert type?'
            ));
}

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Alert Types';
$this->secondaryNav['url'] = array('alertType/index');
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
        <?php
        echo $form->labelEx($model, 'display_name');
        echo $form->textField($model, 'display_name', array('size' => 60, 'maxlength' => 21));
        ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Displayed next to the interest switch in the app. To display properly, there is a 21 character limit. For example: “Political Action”"></a>
        <?php
        echo $form->error($model, 'display_name');
        ?>
    </div>

    <div class="">
        <?php
        echo $form->labelEx($model, 'tag_id');
        $tagList = CHtml::listData(Tag::model()->findAllByAttributes(array('type' => 'alert')), 'id', 'name');
        echo $form->dropDownList($model, 'tag_id', $tagList);
        ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Choose the tag that will be assigned to the user when the interest switch is turned on."></a>
        <?php
        echo $form->error($model, 'tag_id');
        ?>
    </div>

    <div class="buttons">
<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save')); ?>
    </div>

    <?php
    $this->endWidget();
    ?>

</div><!-- form -->