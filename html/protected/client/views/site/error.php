<?php
$this->pageTitle = Yii::app()->name . ' - Error';

?>
<h2>OMG</h2>


<h3>Something's gone horribly wrong:</h3>

<?php
echo "<pre>{$code}: {$message}</pre>";

if (Yii::app()->request->urlReferrer)
    echo '<br/>' . CHtml::link('Go back', Yii::app()->request->urlReferrer, array('class' => 'btn'));
