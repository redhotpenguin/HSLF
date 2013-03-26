<div id="new_tag_form" class="form">

    <?php
    $model = new Tag();
    ?>

    <div class="row">
        <?php
        echo Chtml::label('name', 'name');
        echo Chtml::textField('name', '', array('size' => 60, 'maxlength' => 255));
        ?>
    </div>

    <div class="row">
        <?php
        echo Chtml::label('display_name', 'display_name');
        echo Chtml::textField('display_name', '', array('size' => 60,));
        ?>
    </div>

    <div class="row">
        <?php
        echo Chtml::label('type', 'type');
        echo Chtml::textField('type', '', array('size' => 60, 'maxlength' => 255));
        ?>
    </div>

    <div class="row">
        <?php
        if (count($tagTypes) > 1) {
            //   echo $form->labelEx($model, 'type');
            //  echo $form->dropDownList($model, 'type', $model->getTagTypes());
            //   echo $form->error($model, 'type');
        } else {
            $model->type = $tagTypes[0];
            //   echo $form->hiddenField($model, 'type');
        }
        ?>
    </div>

    <div class="row buttons">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save', 'htmlOptions' => array('id' => 'new_tag_btn')));
        ?> 


    </div>


</div><!-- form -->