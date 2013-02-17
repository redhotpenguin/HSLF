<?php
$this->pageTitle = Yii::app()->name . ' - Error';
?>
<h2>OMG</h2>
<h3>Something's gone horribly wrong</h3>

<pre>
<?php

if (isset($code)) {
    echo $code.':';
    echo $message;
}
?>
</pre>