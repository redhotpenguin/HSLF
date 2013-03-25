<?php $this->pageTitle = Yii::app()->name; ?>

<div>

    <?php

    if (Yii::app()->user->id):
        ?>
        <div class="hero-unit">
            <h1>Dashboard</h1>
            <p>Welcome to the administration dashboard.</p>


            <?php
            if ($itemCount != null) {
                echo "<p><b>$itemCount</b> " . CHtml::link("Ballot Items", array("ballotItem/index")) . '</p>';
            }


            if ($mobileUserCount != null) {
                echo "<p><b>$mobileUserCount</b> " . CHtml::link("Mobile Users", array("mobileUser/Index")) . '</p>';
            }
            ?>


        </div>


    </div>

    <?php


endif; //end test is user logged in