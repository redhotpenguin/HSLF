<?php
$this->pageTitle = Yii::app()->name . ' - Error';
$this->breadcrumbs = array(
    'Error',
);
?>
<h2>OMG</h2>
<h3>Something's gone horribly wrong</h3>


<h6>Please send this to the guy in charge:</h6>
<pre>
<?php

if (isset($code)) {
    echo $code.':';
    echo $message;

}
?>
</pre>