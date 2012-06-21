<div id="ballot_info">
    <h1 class="ballot_item_name"> <?php echo $ballot->item; ?> </h1>

    <div id="ballot_location">
        <?php
        echo '<br>';
        echo $ballot->district->stateAbbr->name;
        echo ' ';
        echo $ballot->district->type;
        echo ' ';
        echo $ballot->district->number;
        ?>
    </div>    

</div>

<?php
if ($ballot->image_url)
    echo "<div id='ballot_image'> <img src='$ballot->image_url'/> </div>";
?>




<div id="ballot_detail">
    <?php
    echo '<br>';

    echo $ballot->detail;
    ?>

</div>

<br><br>

<?php
if ($ballot->BallotItemNews):
    ?>
    <div id="ballot_news">
        <h1>News:</h1>

        <?php
        foreach ($ballot->BallotItemNews as $ballotItemNew):
            ?>
            <div class="ballot_news_item">

                <div class="ballot_news_title"><?php echo $ballotItemNew->title; ?></div>
                <div class="ballot_news_content"><?php echo $ballotItemNew->content; ?></div>

                <div class="ballot_news_date_published"><?php echo $ballotItemNew->date_published; ?></div>


            </div>
            <?php
        endforeach; // end looping through the ballot item news
        ?>

    </div>
    <?php
endif; // end if ballot_news
?>
