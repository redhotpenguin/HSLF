<?php $this->pageTitle = Yii::app()->name; ?>

<div>

    <?php
    if (Yii::app()->user->id):
        
        ?>
        <div class="hero-unit">
            <h1>Dashboard</h1>
            <p>Welcome to the administration dashboard.</p>
            <p> <b><?php echo $total_ballot_page; ?></b> <a href="/admin/ballotItem/admin/">Ballot items</a></p>
        </div>

        <div class="row">
            <div class="span3">
                <h2>Ballot Items</h2>
                <p>Add, edit, delete, search ballot items. </p>
                <p><a class="btn" href="/admin/ballotItem/admin/">More »</a></p>
            </div>
            <div class="span3">
                <h2>Image Uploader</h2>
                <p>Upload images. </p>
                <p><a class="btn" href="/admin/upload/">More »</a></p>
            </div>
            <div class="span3">
                <h2>Push Notifications</h2>
                <p>Send Rich Push Notifications to mobile users.</p>
                <p><a class="btn" href="<?php echo $tenant->ua_dashboard_link;   ?>">More »</a></p>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="span3">
                <h2>Endorsers</h2>
                <p>Manage endorsers.</p>
                <p><a class="btn" href="/admin/endorser/admin/">More »</a></p>
            </div>

            <div class="span3">
                <h2>Options</h2>
                <p>Remotely update your mobile application.</p>
                <p><a class="btn" href="/admin/option/">More »</a></p>
            </div>

            <div class="span3">
                <h2>Dashboard users</h2>
                <p>Manage who can access this dashboard.</p>
                <p><a class="btn" href="/admin/user/">More »</a></p>
            </div>
        </div>

    </div>

    <?php

endif; //end test is user logged in
