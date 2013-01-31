<?php $this->pageTitle = Yii::app()->name; ?>

<div>

    <?php
    if (Yii::app()->user->id):
        ?>
        <div class="hero-unit">
            <h1>Dashboard</h1>
            <p>Welcome to the administration dashboard.</p>
            
          
              
            
            <p> <b><?php echo $total_item_number; ?></b>  <?php  echo CHtml::link("Ballot Items", array("item/Admin")); ?> </p>
            <p> <b><?php echo $total_user_number; ?></b>  <?php  echo CHtml::link("Mobile Users", array("mobileUser/Index")); ?> </p>

        </div>

        <div class="row">
            <div class="span3">
                <h2>Ballot Items</h2>
                <p>Add, edit, delete, search ballot items. </p>
                <p><?php  echo CHtml::link('More', array('item/Admin'), array('class'=>'btn')); ?></a></p>
            </div>
            <div class="span3">
                <h2>Image Uploader</h2>
                <p>Upload images. </p>
                <p><?php  echo CHtml::link('More', array('upload/index'), array('class'=>'btn')); ?></p>
            </div>
            <div class="span3">
                <h2>Push Notifications</h2>
                <p>Send Rich Push Notifications to mobile users.</p>
                <p><a class="btn" href="<?php echo $tenant->ua_dashboard_link; ?>">More »</a></p>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="span3">
                <h2>Organizations</h2>
                <p>Manage organizations.</p>
                <p><?php  echo CHtml::link('More', array('organization/admin'), array('class'=>'btn')); ?></p>
            </div>

            <div class="span3">
                <h2>Options</h2>
                <p>Remotely update your mobile application.</p>
                <p><?php  echo CHtml::link('More', array('option/index'), array('class'=>'btn')); ?></p>
            </div>

            <div class="span3">
                <h2>Mobile Users</h2>
                <p>Mobile user list</p>
                <p><?php  echo CHtml::link('More', array('mobileUser/index'), array('class'=>'btn')); ?></p>
            </div>
        </div>

    </div>

    <?php

endif; //end test is user logged in