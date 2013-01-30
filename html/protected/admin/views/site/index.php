<?php

if (empty($tenants)):
    echo '<h1>No Projects</h1>';



else:





    foreach ($tenants as $tenant) {
       echo CHtml::link($tenant->display_name, "admin/".$tenant->name);
        echo '<hr/>';
    }

endif;