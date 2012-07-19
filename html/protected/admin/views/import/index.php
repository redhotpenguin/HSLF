
<?php
echo CHtml::form(CHtml::normalizeUrl(array('import/index', 'action' => 'importBallot')), 'POST', array('enctype' => 'multipart/form-data'));
?>
<div class="form">
    <h1>Import ballot items:</h1>


    <div class="left_col">
        <?php
        echo CHtml::fileField("import");
        ?>

    </div>

    <div class = "right_col">
        <?php
        echo Chtml::submitButton("Import");
        ?>
    </div>

    <div class="clearfix">
        <?php
        echo Chtml::endForm();
        print_r($result);
        ?>
    </div>

</div>

