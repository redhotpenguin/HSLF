<?php
$navBarItems = array();

if (!$model->isNewRecord) {
    array_push($navBarItems, '', array('label' => 'Create', 'url' => array('create'),
            ), '');

    $this->headerButton = Chtml::linkButton('Delete', array(
                'class' => 'btn btn-danger',
                'submit' => array('delete', 'id' => $model->id),
                'confirm' => 'Are you sure you want to delete this tag?'
            ));
}


$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Tags';
$this->secondaryNav['url'] = array('tag/index');
?>


<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'tag-form'
            ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php
        echo $form->labelEx($model, 'name');
        echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 255));
        ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Enter the name of the tag that will be stored in the database. This must begin with the prefix “org,” “alert” or “district” depending on the tag type. Use underscores instead of spaces and all letters must be lowercase. For example: org_city_of_portland"></a>
        <?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="row">
        <?php
        echo $form->labelEx($model, 'display_name');
        echo $form->textField($model, 'display_name', array('size' => 60));
        ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Enter the name you will use to find the tag in the admin dashboard. For example: City of Portland"></a>

        <?php echo $form->error($model, 'display_name'); ?>
    </div>

    <div class="row">
        <?php
        echo $form->labelEx($model, 'type');
        echo $form->dropDownList($model, 'type', $model->getTagTypes());
        ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Select Organization if the tag will be associated with Organizations, Alert if the tag will be associated with interest switches in the app or District if the tag will be associated with a location."></a>

        <?php
        echo $form->error($model, 'type');
        ?>
    </div>

    <div class="row buttons">

        <?php
        $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save'));
        ?> 


    </div>

    <div class="hidden update_box" id="targetdiv">
    </div>

<?php
$this->endWidget();
?>

</div><!-- form -->