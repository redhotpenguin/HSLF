<div class="not_found">
    <p class="code">404</p>
    <div class="clearfix"></div>
    <p class="reason">This is not the page you are looking for. <br/>
These <i>may</i> be the droids you are looking for.
    </p>

    <iframe width="620" height="340" src="https://www.youtube.com/embed/g5VR4wdGeRg?feature=player_detailpage" frameborder="0" allowfullscreen></iframe>




</div>
<?php
if (Yii::app()->request->urlReferrer)
    echo '<br/>' . CHtml::link('Go back', Yii::app()->request->urlReferrer, array('class' => 'btn'));

