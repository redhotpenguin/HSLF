<?php $this->pageTitle = Yii::app()->name; ?>

<div id="homepage">
    <div class="hero-unit">
        <h1><?php echo $tenantDisplayName; ?></h1>
        <p>Welcome to your mobile app dashboard.<br/>
            Use the drop-down navigation above or click one of the buttons below to manage your mobile app.</p>
    </div>

    <div class="section-divider">
        <h3>Manage</h3>
    </div>


    <div class="action_group">
        <?php
        if (Yii::app()->user->hasPermission('manageMobileUsers')) {
            echo CHtml::link('Mobile Users', array('mobileUser/index'), array('class' => 'action_block'));
            echo CHtml::link('App Usage', array('report/index'), array('class' => 'action_block'));
        }
        if (Yii::app()->user->hasPermission('managePushMessages')) {
            echo CHtml::link('Push Notifications', array('pushMessage/index'), array('class' => 'action_block'));
        }
        ?>
    </div>

    <div class="clearfix"></div>

    <?php if (Yii::app()->user->hasPermission('manageMobileUsers')): ?>

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
