<?php $this->pageTitle = Yii::app()->name; ?>

<h1>Welcome to the MVG Administration site</h1>

<div style="height:600px;">
<?php
if(Yii::app()->user->id):
?>
    <div class="last_entry candidate">
        <h3>Last Candidate:</h3>
         <h6><?php echo $last_candidate->full_name; ?></h6>
        <?php
        
        echo $last_candidate->endorsement;
        echo  '<br/><br/>Published: '.$last_candidate->date_published;
        echo  '<br/>Scorecard: '.$last_candidate->scorecard;
        echo  '<br/>State: '.$last_candidate->state_abbr;
        echo '<br/>Party '.$last_candidate->party;
        ?>
    </div>

    <div class="last_entry push">
        <h3>Last Notification:</h3>
        <?php
        echo $last_push->message;
        echo '<br/><br/>Published: '.$last_push->create_time;
        ?>
    </div>


    <div class="last_entry alert">
        <h3>Last Alert:</h3>
        <h6><?php   echo $last_alert->title; ?></h6>
        <div><?php echo $last_alert->content; ?> </div>
        <div>Published <?php echo $last_alert->create_time; ?> </div>
        <div>State: <?php echo $last_alert->state_abbr; ?> </div>
    </div>
<?php
endif;

?>




</div>