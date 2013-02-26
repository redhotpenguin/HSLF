<?php

require_once('jobs/MobileUserExportJob.php');


$csvHeaders = array(
    '_id' => 'Identifier',
    'tenant_id' => 'Tenant ID',
    'device_type' => 'Device Type',
    'ua_identifier' => 'Urban Airship Identifier',
    'districts' => 'Districts',
    'action_taken' => 'Action Taken',
    'tags' => 'Tags',
    'name' => 'Name',
    'email' => 'Email',
    'home_address' => 'Home Address',
    'phone_number' => 'Phone Number',
    'registration_date' => 'Registration Date',
    'last_connection_date' => 'Last Connection date',
);

$_parameters = array(
    'tenant_id' => 2,
    'tenant_name' => 'Our Oregon',
    'email' => 'jonas.palmero@gmail.com',
    'mongodb_host' => 'mongodb://localhost:27017',
    'mongodb_name' => 'mobile_advocacy_platform',
    'mongodb_username' => 'admin',
    'mongodb_password' => 'admin',
    'mongodb_time_out' => 5000,
    'mongodb_collection_name' => 'mobile_user',
    'csvHeaders' => $csvHeaders
);


$filterAttributes['device_type'] = 'android';



/* dotcloud queue sandbox credentials */
$parameters = array(
    'tenant_id' => 1,
    'tenant_name' => 'Our Oregon',
    'email' => 'jonas.palmero@gmail.com',
    'mongodb_host' => 'mongodb://root:a5a7j4HsY027U6wGZ8kg@mapqueue-winningmark-data-0.azva.dotcloud.net:51138',
    'mongodb_name' => 'mobile_advocacy_platform',
    'mongodb_username' => 'map_user',
    'mongodb_password' => 'jeMEwRArEKwBg7Q',
    'mongodb_time_out' => 5000,
    'mongodb_collection_name' => 'mobile_user',
    'csvHeaders' => $csvHeaders,
    'filterAttributes' => $filterAttributes,
);


$job = new MobileUserExportJob();
$job->args = $parameters;

$job->perform();