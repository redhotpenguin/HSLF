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







    <?php
    /*
      $data = array(
      array("id" => 1, "name" => "John",
      "parents" => array(
      array("id" => 10, "name" => "Mary",
      "parents" => array(
      array("id" => 100, "name" => "Jane",
      "parents" => array(
      array("id" => 1000, "name" => "Helene"),
      array("id" => 1001, "name" => "Peter")
      )
      ),
      array("id" => 101, "name" => "Richard",
      "parents" => array(
      array("id" => 1010, "name" => "Lisa"),
      array("id" => 1011, "name" => "William")
      )
      ),
      ),
      ),
      array("id" => 11, "name" => "Derek",
      "parents" => array(
      array("id" => 110, "name" => "Julia"),
      array("id" => 111, "name" => "Christian",
      "parents" => array(
      array("id" => 1110, "name" => "Deborah"),
      array("id" => 1111, "name" => "Marc"),
      ),
      ),
      ),
      ),
      ),
      ),
      );


      echo 'find';

      $this->widget('system.web.widgets.CTreeView', array(
      'data' => $data,
      'id'=>'areas',
      'animated' => 'normal',
      'collapsed' => true,
      'htmlOptions' => array('class' => 'treeview-gray')));
     */
    ?>


</div>