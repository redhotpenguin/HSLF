<?php

if (!empty($checkBoxList)):
    ?>
    <table class="table table-bordered table-striped">

        <?php
        foreach ($checkBoxList as $tagId => $item):
            ?>
            <tr>
                <td> <?php echo $item['name']; ?> </td>
                <td> <?php echo CHtml::checkBox($modelName . '[tags][]', $item['checked'], array('value' => $tagId)); ?> </td>
            </tr>

            <?php
        endforeach;
        ?>
    </table><?php
else:
    echo 'No tags avalaible';
endif;

$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'new_tag_modal',
    'options' => array(
        'title' => 'Create a new tag',
        'autoOpen' => true,
    ),
));

$this->render('tag_creator', array('tagTypes'=>$tagTypes));

$this->endWidget('zii.widgets.jui.CJuiDialog');

// the link that may open the dialog
echo CHtml::link('Create new tag', '#', array(
    'onclick' => '$("#new_tag_modal").dialog("open"); return false;',
    'class' => 'btn btn-info',
));
    ?>

