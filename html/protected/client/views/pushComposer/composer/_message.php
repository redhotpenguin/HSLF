<fieldset id="message">


    <h1>Message</h1>

<div class="row-fluid">

    <div class="span12">
        <?php
        echo CHtml::textArea('message', $message, array('class'=>'span12','rows'=>10));
        ?>
    </div>

</div>


</fieldset>