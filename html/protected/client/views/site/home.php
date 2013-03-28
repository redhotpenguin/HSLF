<?php $this->pageTitle = Yii::app()->name; ?>

<div id="homepage">
    <div class="hero-unit">
        <h1><?php echo $tenantDisplayName;?></h1>
        <p>Welcome to your mobile advocacy platform administration dashboard.<br/>
            Use the drop-down navigation above or click one of the buttons below to manage your mobile application.</p>
    </div>

    <div class="action_group">
        <span class="action_title">Content</span>

        <div class="action_block_group">
            <?php
            if (Yii::app()->user->hasPermission('manageBallotItems'))
                echo CHtml::link('Ballot Items', array('ballotItem/index'), array('class' => 'action_block'));

            if (Yii::app()->user->hasPermission('manageOrganizations'))
                echo CHtml::link('Organizations', array('organization/index'), array('class' => 'action_block'));

            if (Yii::app()->user->hasPermission('manageContacts'))
                echo CHtml::link('Contacts', array('contact/index'), array('class' => 'action_block'));

            if (Yii::app()->user->hasPermission('manageTags'))
                echo CHtml::link('Tags', array('tag/index'), array('class' => 'action_block'));
            ?>
        </div>

    </div>

    <div class="action_group">
        <span class="action_title">Communication</span>

        <div class="action_block_group">
            <?php
            if (Yii::app()->user->hasPermission('manageMobileUsers'))
                echo CHtml::link('Mobile Users', array('mobileUser/index'), array('class' => 'action_block'));

            if (Yii::app()->user->hasPermission('managePushMessages'))
                echo CHtml::link('Push Messages', array('pushMessage/index'), array('class' => 'action_block'));

            if (Yii::app()->user->hasPermission('manageAlertTypes'))
                echo CHtml::link('Alert Types', array('alertType/index'), array('class' => 'action_block'));

            if (Yii::app()->user->hasPermission('managePayloads'))
                echo CHtml::link('Payloads', array('payload/index'), array('class' => 'action_block'));
            ?>
        </div>

    </div>


</div>
