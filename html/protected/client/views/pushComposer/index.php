<?php

$this->secondaryNav['name'] = 'Push Composer';
$this->secondaryNav['url'] = array('pushComposer/index');

?>
<h3>push composer</h3>

<?php
    echo CHtml::link("Send a push", array('composer') );
?>