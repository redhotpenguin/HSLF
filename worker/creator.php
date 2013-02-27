<?php

echo '<pre>';


require 'vendor/autoload.php';

require_once( 'config.php');


Resque::setBackend(REDIS_HOST . ':' . REDIS_PORT, null);

Resque::redis()->auth(REDIS_PASSWORD);

Resque::redis()->select(REDIS_DB);

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

$filterAttributes['device_type'] = 'android';
$filterAttributes = array();


/* dotcloud queue sandbox credentials */
$parameters = array(
    'tenant_id' => 2,
    'tenant_name' => 'Our Oregon',
    'requested_by' => 'jonas',
    'email' => 'jonas.palmero@gmail.com',
    'mongodb_host' => 'mongodb://localhost:27017',
    'mongodb_name' => 'mobile_advocacy_platform',
    'mongodb_username' => 'admin',
    'mongodb_password' => 'admin',
    'mongodb_time_out' => 5000,
    'mongodb_collection_name' => 'mobile_user',
    'csvHeaders' => $csvHeaders,
    'filterAttributes' => $filterAttributes,
);


Resque::enqueue('mobile_platform', 'MobileUserExportJob', $parameters);