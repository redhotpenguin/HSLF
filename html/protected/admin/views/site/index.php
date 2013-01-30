<?php

if (empty($tenants)):
    echo '<h1>No Projects</h1>';



else:





    foreach ($tenants as $tenant) {
        print_r($tenant->display_name);
        echo '<hr/>';
    }

endif;