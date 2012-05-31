<?php $this->pageTitle = Yii::app()->name; ?>

<h1>WM Mobile: HSLF </h1>

<div>




    <?php
    if (Yii::app()->user->id):
        ?>

        <p> <b><?php echo $total_app_users; ?></b> <a href="/admin/application_users">Application users</a></p>
        <p> <b><?php echo $total_candidate_page; ?></b> <a href="/admin/candidate">Endorsement pages</a></p>

        <?php
    endif; //end test is user logged in
    ?>

</div>
