<div class="hero-unit">
    <h1>Thank you</h1>
    <p>"<?php echo $pushMessage->alert; ?>" was successfully saved.</p>
</div>

<?php
echo "<p><em>Push Message ID: #{$pushMessage->id}</em><br/>";
if ($pushMessage->payload->type != 'other') {
    echo "<em>Payload ID: #{$pushMessage->payload->id}</em>";
}

echo '</p><p>' . CHtml::link('Go back to push messages', array('pushMessage/index'));
echo '<br/>' . CHtml::link('Send another message', array('pushMessage/composer')) . '</p>';

?>