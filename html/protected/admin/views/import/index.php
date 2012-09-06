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
        <b>CSV structure:</b><br/> id, abbr, name
        (headers must be included)
            <br/>
        Ex: [blank or existing], CA, California
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
        <b>CSV structure:</b><br/>id, state_abbr, number, type, display_name, locality
        (headers must be included)<br/>

    </p>

</div>
<?php
echo Chtml::endForm();
?>
<br/>

<div class="form">
    <?php
    echo CHtml::form(CHtml::normalizeUrl(array('import/index', 'action' => 'importBallotItem')), 'POST', array('enctype' => 'multipart/form-data'));
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

    <div class="clearfix"></div>
    <p>
        <b>CSV structure:</b> id, district_id, item, item_type, recommendation_id, next_election_date, priority, detail, date_published, published, party_id, image_url, election_result_id, url, personal_url, score, office_id, facebook_url, twitter_handle, hold_office, facebook_share, twitter_share, measure_number
        <br/>
        (headers must be included)
    </p>
    <?php
    echo Chtml::endForm();
    ?>
</div>

<br/>

<div class="form">
    <?php
    echo CHtml::form(CHtml::normalizeUrl(array('import/index', 'action' => 'importScorecard')), 'POST', array('enctype' => 'multipart/form-data'));
    ?>
    <h1>Import Scorecards:</h1>


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
<?php
echo Chtml::endForm();
?>

<br/>

<div class="form">
    <?php
    echo CHtml::form(CHtml::normalizeUrl(array('import/index', 'action' => 'importVote')), 'POST', array('enctype' => 'multipart/form-data'));
    ?>
    <h1>Import Votes:</h1>


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
        <b>CSV structure:</b>id, vote, icon
        (headers must be included)
    </p>
    <?php
    echo Chtml::endForm();
    ?>
</div>

<br/>

<div class="form">
    <?php
    echo CHtml::form(CHtml::normalizeUrl(array('import/index', 'action' => 'importRecommendation')), 'POST', array('enctype' => 'multipart/form-data'));
    ?>
    <h1>Import Recommendations:</h1>


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
        <b>CSV structure:</b>id, value, type
        (headers must be included)
    </p>
    <?php
    echo Chtml::endForm();
    ?>
</div>


<br/>

<div class="form">
    <?php
    echo CHtml::form(CHtml::normalizeUrl(array('import/index', 'action' => 'importOffice')), 'POST', array('enctype' => 'multipart/form-data'));
    ?>
    <h1>Import Offices:</h1>


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
        <b>CSV structure:</b>id, name
        (headers must be included)
    </p>
    <?php
    echo Chtml::endForm();
    ?>
</div>

<br/>

<div class="form">
    <?php
    echo CHtml::form(CHtml::normalizeUrl(array('import/index', 'action' => 'importParty')), 'POST', array('enctype' => 'multipart/form-data'));
    ?>
    <h1>Import Parties:</h1>


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
        <b>CSV structure:</b>id, name, abbreviation, initial
        (headers must be included)
    </p>
    <?php
    echo Chtml::endForm();
    ?>
</div>

<br/>

<div class="form">
    <?php
    echo CHtml::form(CHtml::normalizeUrl(array('import/index', 'action' => 'importScorecardItem')), 'POST', array('enctype' => 'multipart/form-data'));
    ?>
    <h1>Import Scorecard Items:</h1>


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
        <b>CSV structure:</b>id, name, description, office_id
        (headers must be included)
    </p>
    <?php
    echo Chtml::endForm();
    ?>
</div>

<br/>

<div class="form">
    <?php
    echo CHtml::form(CHtml::normalizeUrl(array('import/index', 'action' => 'importTag')), 'POST', array('enctype' => 'multipart/form-data'));
    ?>
    <h1>Import Tags:</h1>


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
        <b>CSV structure:</b>id, name, type
        (headers must be included)
    </p>
    <?php
    echo Chtml::endForm();
    ?>
</div>

<div class="form">
    <?php
    echo CHtml::form(CHtml::normalizeUrl(array('import/index', 'action' => 'importEndorser')), 'POST', array('enctype' => 'multipart/form-data'));
    ?>
    <h1>Import Endorsers:</h1>


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
        <b>CSV structure:</b>id, name, description, website, image_url
        (headers must be included)
    </p>
    <?php
    echo Chtml::endForm();
    ?>
</div>
<br/>
<div class="form">
    <?php
    echo CHtml::form(CHtml::normalizeUrl(array('import/index', 'action' => 'importEndorserBallotItem')), 'POST', array('enctype' => 'multipart/form-data'));
    ?>
    <h1>Import Endorser-Ballot Item:</h1>


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
        <b>CSV structure:</b>id, endorser_id, ballot_item_id
        (headers must be included)
    </p>
    <?php
    echo Chtml::endForm();
    ?>
</div>

<br/>

<br/>


<strong>Notes:</strong>
<ul>
    <li> An ID column is <b>always</b> required and should always be first.</li>
    <li> To update an existing record, an ID value must be provided in the ID column.</li>
    <li> Excel might replace empty rows with ',,,,' which will cause the import to fail.</li>
    <li>If an ID cell is left empty, the row will be appended. In some cases, it will causes a unique-constraint violation error if the row already exists.</li>
    <li>If an error happens, the import will be rolled back and won't leave any traces.</li>
</ul>



<?php
echo Chtml::endForm();

