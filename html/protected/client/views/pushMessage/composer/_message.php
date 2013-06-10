<?php
echo $form->errorSummary($pushMessage);

echo $form->textArea($pushMessage, 'alert', array('class' => 'span12', 'rows' => 3));
?>

<p>You have <span id="previewChars" class="badge"></span> characters left.</p>
