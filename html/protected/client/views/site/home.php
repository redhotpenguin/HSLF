<?php $this->pageTitle = Yii::app()->name; 

// UI Helper
function getBlockClassSize($number) {
        $classBlock = 'whole';

        if ($number == 3) {
            $classBlock = 'third';
        } elseif ($number == 2) {
            $classBlock = 'half';
        }
        
        return $classBlock;
    }
?>

<div id="homepage">
    <div class="hero-unit">
        <h1><?php echo $tenantDisplayName; ?></h1>
        <p>Welcome to your mobile app dashboard. Here you can manage communication with your app users, access and evaluate usage data and manage the content of your app. Use the drop-down navigation above or click one of the buttons below to manage your app.</p>
    </div>

    <?php
    $canManagePushMessages = Yii::app()->user->hasPermission('managePushMessages');
    $canManagePayloads = Yii::app()->user->hasPermission('managePayloads');
    $canManageAlertTypes = Yii::app()->user->hasPermission('manageAlertTypes');

    // communicate section dynamic block size
    $communicateBlockNumber = 0;
    $communicateBlockNumber += $canManagePushMessages ? 1 : 0;
    $communicateBlockNumber += $canManagePayloads ? 1 : 0;
    $communicateBlockNumber += $canManageAlertTypes ? 1 : 0;
    $classBlockSize = getBlockClassSize($communicateBlockNumber);

    if ($communicateBlockNumber > 0):
        ?>
        <div class="section-divider clearfix">
            <h3>Communicate</h3>
        </div>


        <div class = "action_group clearfix">
            <?php
            if ($canManagePushMessages) {
                echo CHtml::link('Push Notifications', array('pushMessage/index'), array('class' => "action_block $classBlockSize"));
            }
            if ($canManagePayloads) {
                echo CHtml::link('Payloads', array('payload/index'), array('class' => "action_block $classBlockSize"));
            }
            if ($canManageAlertTypes) {
                echo CHtml::link('Alert Types', array('pushMessage/index'), array('class' => "action_block $classBlockSize"));
            }
            ?>
        </div>

        <?php
    endif;

    if (Yii::app()->user->hasPermission('manageMobileUsers')) :
        ?>

        <div class="section-divider clearfix">
            <h3>Evaluate</h3>
        </div>
        <div class="action_group clearfix">
            <?php
            echo CHtml::link('App Usage', array('report/index'), array('class' => 'action_block half'));
            echo CHtml::link('Mobile Users', array('mobileUser/index'), array('class' => 'action_block half'));
            ?>
        </div>
        <?php
    endif;

    $canManageOrganizations = Yii::app()->user->hasPermission('manageOrganizations');
    $canManageContacts = Yii::app()->user->hasPermission('manageContacts');
    $canManageTags = Yii::app()->user->hasPermission('manageTags');

    // manage section dynamic block size
    $manageBlockNumber = 0;
    $manageBlockNumber += $canManageOrganizations ? 1 : 0;
    $manageBlockNumber += $canManageContacts ? 1 : 0;
    $manageBlockNumber += $canManageTags ? 1 : 0;
    $classBlock = getBlockClassSize($manageBlockNumber);
    
    

    if ($manageBlockNumber > 0):
        ?>

        <div class="action_group clearfix">

            <div class="section-divider">
                <h3>Manage</h3>
            </div>
            <?php
            if ($canManageOrganizations) {
                echo CHtml::link('Organizations', array('organization/index'), array('class' => "action_block $classBlock"));
            }
            if ($canManageContacts) {
                echo CHtml::link('Contacts', array('contact/index'), array('class' => "action_block $classBlock"));
            }
            if ($canManageTags) {
                echo CHtml::link('Tags', array('tag/index'), array('class' => "action_block $classBlock"));
            }
            ?>
        </div>
        <?php
    endif;

    if (Yii::app()->user->hasPermission('manageMobileUsers')):
        ?>

        <div class = "section-divider">
            <h3>Did you know?</h3>
        </div>

        <div class = "heroUserCount">
            <h2><?php echo CHtml::link(number_format($userCount), array('mobileUser/index')); ?> users have registered with your app.</h2>
            <?php echo CHtml::link('more stats', array('report/index'), array('class' => 'action_block')); ?>
        </div>

        <?php
    endif;
    ?>

</div>
