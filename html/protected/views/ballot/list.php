<h1>List of ballots</h1>

<h3><?php echo $state_abbr; ?>
 <?php echo $district_type; ?>
 <?php echo $district; ?></h3>


<?php

foreach($ballots as $ballot){
    
    echo $ballot->item;
    echo '<br>';
}


?>