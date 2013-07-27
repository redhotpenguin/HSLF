<div id="new_tag_form" class="form">

    <?php
    $model = new Tag();
    ?>

    <div class="row">
        <?php
        echo Chtml::label('Display Name', 'display_name');
        echo Chtml::textField('display_name', '', array('class' => 'span7', 'size' => 60,'style'=>'width: 310px;', 'placeholder' => 'City of Portland'));
        ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Enter the name you will be using to find the tag in the admin dashboard. For example: City of Portland"></a>

    </div>

    <div class="row">
        <?php
        echo Chtml::label('Tag Name', 'name');
        echo Chtml::textField('name', '', array('class' => 'span7', 'size' => 60, 'maxlength' => 255, 'style'=>'width: 310px;','placeholder' => 'org_city_portland'));
        if (in_array('organization', $tagTypes)):
            ?>
            <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Enter the name of the tag that will be stored in the database. This must begin with “org_”, use underscores instead of spaces and all letters must be lowercase. For example: org_city_portland."></a>
            <?php
        endif;
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