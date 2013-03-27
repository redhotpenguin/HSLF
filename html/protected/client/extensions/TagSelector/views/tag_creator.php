<div id="new_tag_form" class="form">

    <?php
    $model = new Tag();
    ?>

    <div class="row">
        <?php
        echo Chtml::label('Display Name', 'display_name');
        echo Chtml::textField('display_name', '', array('size' => 60,));
        ?>
    </div>

    <div class="row">
        <?php
        echo Chtml::label('Name', 'name');
        echo Chtml::textField('name', '', array('size' => 60, 'maxlength' => 255));
        ?>
    </div>



    <div class="row">
        <?php
        if (count($tagTypes) > 1) {
            echo Chtml::label('Type', 'type');
            echo CHtml::dropDownList('type', null, $model->getTagTypes());
        } else {
            echo CHtml::hiddenField('type', $tagTypes[0]);
        }
        ?>
    </div>

</div><!-- form -->