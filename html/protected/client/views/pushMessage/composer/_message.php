<?php
echo $form->errorSummary($pushMessage);

echo $form->textArea($pushMessage, 'alert', array('class' => 'span11', 'rows' => 3));
?>
<a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="The text of the push notification the user will receive and the title of the message in the Alert Inbox."></a>


<p>You have <span id="previewChars" class="badge"></span> characters left.</p>
<em>Recommended: 120<br/>Maximum: 140</em>
