<?php $this->pageTitle = Yii::app()->name; ?>

<div>

    <?php
    /*
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


    $t = Yii::app()->queue->enqueue('mobile_platform', 'MobileUserExportJob', $parameters);
     * 
     */
    
    if (Yii::app()->user->id):
        ?>
        <div class="hero-unit">
            <h1>Dashboard</h1>
            <p>Welcome to the administration dashboard.</p>


            <?php
            if ($itemCount != null) {
                echo "<p><b>$itemCount</b> " . CHtml::link("Ballot Items", array("item/index")) . '</p>';
            }


            if ($mobileUserCount != null) {
                echo "<p><b>$mobileUserCount</b> " . CHtml::link("Mobile Users", array("mobileUser/Index")) . '</p>';
            }
            ?>


        </div>


    </div>

    <?php


endif; //end test is user logged in