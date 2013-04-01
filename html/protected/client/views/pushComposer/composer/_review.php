<?php
if ($displayNextButton)
    $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button', 'type' => 'primary', 'size' => 'large', 'label' => 'Send Push Notification', 'htmlOptions' => array('style' => 'float:right;', 'id' => 'composerNextBtn')));
?>


<fieldset id="review">
    <h1>Review</h1>

    <p><?php
echo $alert;

echo '<hr>';

print_r($payload);

?> </p>

</fieldset>