<?php

echo '<pre>';


require 'vendor/autoload.php';

Resque::setBackend("127.0.0.1:6379", null);

$connected = Resque::redis()->auth('foobared');

Resque::redis()->select(2);


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

$parameters = array(
    'tenant_id' => 2,
    'tenant_name'=>'Our Oregon',
    'email' => 'jonas.palmero@gmail.com',
    'mongodb_host' => 'mongodb://localhost:27017',
    'mongodb_name' => 'mobile_advocacy_platform',
    'mongodb_username' => 'admin',
    'mongodb_password' => 'admin',
    'mongodb_time_out' => 5000,
    'mongodb_collection_name' => 'mobile_user',
    'csvHeaders' => $csvHeaders
);

Resque::enqueue('mobile_platform', 'MobileUserExportJob', $parameters);