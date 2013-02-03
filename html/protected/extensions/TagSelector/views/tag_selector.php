<table class="table table-bordered table-striped">
    <?php
        
    foreach ($checkBoxList as $tagId=>$item):
        ?>
        <tr>
            <td> <?php echo $item['name']; ?> </td>
            <td> <?php echo CHtml::checkBox($modelName.'[tags][]', $item['checked'], array('value'=>$tagId)); ?> </td>
        </tr>

        <?php
    endforeach;
    ?>

</table>