<div class="not_found">
    <p class="code">404</p>
    <div class="clearfix"></div>
    <p class="reason">
        <?php
        if (isset($error['message']))
            echo $error['message'];
        else
            echo 'This is not the page you are looking for.';
        ?>
    </p>



</div>
<?php
if (Yii::app()->request->urlReferrer)
    echo '<br/>' . CHtml::link('Go back', Yii::app()->request->urlReferrer, array('class' => 'btn'));

