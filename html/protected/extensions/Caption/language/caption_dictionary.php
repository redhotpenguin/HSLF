<?php

global $caption_dictonary;

$caption_dictonary = array(
    //candidate page
    'candidate' => array(
        'name'=>'Candidate',
        'description' => 'Manage candidates',
        'action' => array(
            'create' => 'Use this page to create a new candidate view',
            'update' => 'Update an existing candidate'
        ),
    ),
    // application user page
    'name'=>'Application user',
    'application_users' => array(
        'description' => 'Manage mobile users'
    ),
    //alertType
    'alertType' => array(
        'name'=>'Alert Type',
        'description' => 'Manage alert types users can choose from the application setting.',
        'action' => array(
            'create' => 'An alert type requires a <a target="_blank" href="/tag/create/">tag</a>.'
        ),
    ),
);
?>
