<?php
$this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button', 'type' => 'primary', 'size' => 'large', 'label' => 'Back', 'htmlOptions' => array('style' => 'float:left;', 'id' => 'composerBackBtn')));
?>
<fieldset id="review">
    <h1>Confirmation</h1>

    <p><?php
//print_r($pushMessage);

echo '<hr>';
//print_r($payload);
?> </p>

</fieldset>


<?php
$this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button', 'type' => 'primary', 'size' => 'large', 'label' => 'Send Push Notification', 'htmlOptions' => array('id' => 'composerNextBtn')));
?>