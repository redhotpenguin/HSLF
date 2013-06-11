<?php
if (!$pushMessage->push_identifier) {
    $header = 'Thank You';
    $note = "{$pushMessage->alert} was successfully saved.";
} else {
    $header = 'Push Message delivered';
    $note = CHtml::link('Details', Chtml::normalizeUrl(array('pushMessage/view', 'id' => $pushMessage->id)));
}
?>

<div class="hero-unit">


    <h1><?php echo $header; ?></h1>
    <p><?php echo $note; ?></p>

</div>
<?php
echo "<p><em>Push Message ID: #{$pushMessage->id}</em><br/>";
if ($pushMessage->payload->type != 'other') {
    echo "<em>Payload ID: #{$pushMessage->payload->id}</em>";
}

echo '</p><p>' . CHtml::link('Go back to push messages', array('pushMessage/index'));
echo '<br/>' . CHtml::link('Send another message', array('pushMessage/composer')) . '</p>';
?>