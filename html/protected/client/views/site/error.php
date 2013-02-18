<?php
$this->pageTitle = Yii::app()->name . ' - Error';

?>
<h2>OMG</h2>



<?php

if (isset($error['errorCode']) && $error['errorCode'] == 23502)
    $message = 'This resource is used by something else and can not be deleted';

elseif (isset($error['errorCode']) && $error['errorCode'] == 23505)
    $message = 'Another resource already has the same name.';

else
    $message = $error['message'];
?>



<h3>Something's gone horribly wrong:</h3>


<?php
echo '<pre>' . $message . '</pre>';

if (Yii::app()->request->urlReferrer)
    echo '<br/>' . CHtml::link('Go back', Yii::app()->request->urlReferrer, array('class' => 'btn'));
