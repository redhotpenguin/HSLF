<?php

echo '<pre>';





foreach($tenants as $tenant){
    print_r($tenant->display_name);
}