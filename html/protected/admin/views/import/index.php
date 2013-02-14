<?php
if (isset($result)) {
    if ($result == 'success')
        echo "<div class='btn btn-success'>Success</div>";
    elseif ($result == 'failure') {
        echo "<div class='btn btn-danger'>Error</div>";
        print_r($errorMsg);
        echo '<br/>';
    }
}

foreach ($models as $model):
    ?>

    <div class="form">
        <?php
        echo CHtml::form(CHtml::normalizeUrl(array('import/index', 'modelName' => $model['name'])), 'POST', array('enctype' => 'multipart/form-data'));
        ?>
        <h1>Import <?php echo $model['friendlyName'] ?>:</h1>


        <div class="left_col">
            <?php
            echo CHtml::fileField("import");
            ?>
        </div>

        <div class = "right_col">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Import'));
            ?>
        </div>

        <div class="clearfix"></div>
        <?php
        $this->renderPartial("_structure", array('csvStructure' => $model['attributes']));
        echo Chtml::endForm();
        ?>
    </div>

    <br/>
    <hr/>
    <br/>
    <?php
    echo Chtml::endForm();
endforeach;
?>

<br/>

<strong>Notes:</strong>
<ul>
    <li> An ID column is <b>always</b> required and should always be first.</li>
    <li> To update an existing record, an ID value must be provided in the ID column.</li>
    <li> Excel might replace empty rows with ',,,,' which will cause the import to fail.</li>
    <li>If an ID cell is left empty, the row will be appended. In some cases, it will causes a unique-constraint violation error if the row already exists.</li>
    <li>If an error happens, the import will be rolled back and won't leave any traces.</li>
</ul>



