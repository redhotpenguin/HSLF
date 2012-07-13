<?php

	@session_start();
	// ugliest hack ever
	$connected = false;
	foreach($_SESSION as $t){
		if ( is_array($t) ){
			if($t['role'] == 1)
				$connected = true;
		}	
	}
	
	if ($connected == false){
		echo 'Incident reported: ' .$_SERVER['REMOTE_ADDR']. ' tried to access a secured area without proper privileges.';
		error_log($_SERVER['REMOTE_ADDR'] ." tried to access the image manager without proper authorization.");
                exit;       
        }
	