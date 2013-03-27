<fieldset id="message">


<div class="row-fluid">

    <div class="span6">
        <?php
        echo $form->labelEx($pushMessageModel, 'alert');
        echo $form->textArea($pushMessageModel, 'alert', array('cols' => 60, 'rows' => 3, 'class' => 'span12'));
        echo $form->error($pushMessageModel, 'alert');
        ?>
    </div>

</div>


</fieldset>