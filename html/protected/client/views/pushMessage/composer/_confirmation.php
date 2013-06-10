<fieldset id="thankyou">


    <div class="hero-unit">
        <h1>Thank you</h1>
        <p>"<?php echo $pushMessage->alert; ?>" was successfully saved.</p>
    </div>

    <?php
    echo "<em>Push Message ID: #{$pushMessage->id}</em><br/>";
    if ($pushMessage->payload->type != 'other') {
        echo "<em>Payload ID: #{$pushMessage->payload->id}</em>";
    }
    ?>

</fieldset>