<table class="table table-bordered table-striped">
    <?php
    foreach ($associatedTags as $tag):
        ?>
        <tr>
            <td> <?php echo $tag->display_name; ?> </td>
            <td> <?php echo CHtml::checkBox('tags[]', true); ?> </td>
        </tr>

        <?php
    endforeach;
    foreach ($unAssociatedTags as $tag):
        ?>
        <tr>
            <td> <?php echo $tag->display_name; ?> </td>
            <td> <?php echo CHtml::checkBox('tags[]', false); ?> </td>
        </tr>
        <?php
    endforeach;
    ?>

</table>