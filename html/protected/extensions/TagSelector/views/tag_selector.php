<table class="table table-bordered table-striped">
    <?php
    foreach ($associatedTags as $tag):
        ?>
        <tr>
            <td> <?php echo $tag->display_name; ?> </td>
            <td> <?php echo CHtml::checkBox($modelName.'[tags][]', true, array('value'=>$tag->id)); ?> </td>
        </tr>

        <?php
    endforeach;
    foreach ($unAssociatedTags as $tag):
        ?>
        <tr>
            <td> <?php echo $tag->display_name; ?> </td>
            <td> <?php echo CHtml::checkBox($modelName.'[tags][]', false, array('value'=>$tag->id)); ?> </td>
        </tr>
        <?php
    endforeach;
    ?>

</table>