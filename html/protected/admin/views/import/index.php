<?php
if ($result == 'success')
    echo "<div class='btn btn-success'>Success</div>";

if ($result == 'failure'){
        echo "<div class='btn btn-danger'>Error</div>";
        print_r($error_msg);
        echo '<br/>';
}
 
?>


<div class="form">
    <?php
    echo CHtml::form(CHtml::normalizeUrl(array('import/index', 'action' => 'importState')), 'POST', array('enctype' => 'multipart/form-data'));
    ?>
    <h1>Import States:</h1>


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
</div>
<?php
echo Chtml::endForm();
?>


<br/>

<div class="form">
    <?php
    echo CHtml::form(CHtml::normalizeUrl(array('import/index', 'action' => 'importDistrict')), 'POST', array('enctype' => 'multipart/form-data'));
    ?>
    <h1>Import Districts:</h1>


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
    
</div>
<?php
echo Chtml::endForm();
?>


<br/>

<div class="form">
    <?php
    echo CHtml::form(CHtml::normalizeUrl(array('import/index', 'action' => 'importBallot')), 'POST', array('enctype' => 'multipart/form-data'));
    ?>
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
</div>
<?php
echo Chtml::endForm();
?>

