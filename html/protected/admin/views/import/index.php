<?php
if ($result == 'success')
    echo "<div class='btn btn-success'>Success</div>";

if ($result == 'failure') {
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

    <div class="clearfix"></div>
    <p>
        <b>CSV structure:</b> id, abbr, name
        (headers must be included)
    </p>
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

    <div class="clearfix"></div>
    <p>
        <b>CSV structure:</b>id, state_abbr, number, type, display_name
        (headers must be included)
    </p>

</div>
<?php
echo Chtml::endForm();
?>
<br/>

<div class="form">
    <?php
    echo CHtml::form(CHtml::normalizeUrl(array('import/index', 'action' => 'importScorecard')), 'POST', array('enctype' => 'multipart/form-data'));
    ?>
    <h1>Import Ballot items:</h1>


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

<br/>

<div class="form">
    <?php
    echo CHtml::form(CHtml::normalizeUrl(array('import/index', 'action' => 'importScorecard')), 'POST', array('enctype' => 'multipart/form-data'));
    ?>
    <h1>Import Score cards:</h1>


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

    <div class="clearfix"></div>
    <p>
        <b>CSV structure:</b>id, ballot_item_id, scorecard_item_id, vote_id
        (headers must be included)
    </p>
</div>



<br/>

<strong>Notes:</strong>
<ul>
    <li> An ID column is <b>always</b> required</li>
    <li> To update an existing record, an ID value must be provided in the first column.</li>
    <li> Excel might replace empty rows with ',,,,' which will cause the import to fail.</li>
    <li>If an ID cell is left empty, the row will be appended. In some case, this might causes a unique-constraint violation error if the row already exists.</li>
    <li>If an error happens, the import will be rolled back and won't leave any traces.</li>
</ul>



<?php
echo Chtml::endForm();

