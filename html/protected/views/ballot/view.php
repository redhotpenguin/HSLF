<h1>Single Ballot view</h1>
<?php


if($ballot):
echo $ballot->item;
echo '<br>';
echo $ballot->district->stateAbbr->name;
echo '<br>';
echo $ballot->recommendation->value;
else:
   echo 'no ballot found';
endif;



?>