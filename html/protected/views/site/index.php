<?php $this->pageTitle = Yii::app()->name; ?>

<h1>Welcome to the MVG Administration site</h1>

<div>
    <?php
    if (Yii::app()->user->id):
        ?>
        <div class="last_entry candidate">
            <h3>Last Candidate:</h3>
            <h6><?php
    if ($last_candidate):
        echo $last_candidate->full_name;
            ?></h6>
                <?php
                echo $last_candidate->endorsement;
                echo '<br/><br/>Published: ' . $last_candidate->date_published;
                echo '<br/>Scorecard: ' . $last_candidate->scorecard;
                echo '<br/>State: ' . $last_candidate->state_abbr;
                echo '<br/>Party ' . $last_candidate->party;

            endif;
            ?>
        </div>

        <div class="last_entry push">
            <h3>Last Notification:</h3>
            <?php
            if ($last_push):
            echo $last_push->message;
            echo '<br/><br/>Published: ' . $last_push->create_time;
           endif;
            ?>
        </div>


        <div class="last_entry alert">
            <h3>Last Alert:</h3>
            <h6><?php 
            if($last_alert):
            echo $last_alert->title; ?></h6>
            <div><?php echo $last_alert->content; ?> </div>
            <div>Published <?php echo $last_alert->create_time; ?> </div>
            <div>State: <?php echo $last_alert->state_abbr; ?> </div>
        </div>
        <?php
        endif;
    endif; //end test is user logged in
    ?>

</div>
