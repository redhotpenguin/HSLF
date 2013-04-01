<?php
if ($displayNextButton)
    $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button', 'type' => 'primary', 'size' => 'large', 'label' => 'Next', 'htmlOptions' => array('style' => 'float:right;', 'id' => 'composerNextBtn')));
?>


<fieldset id="action">
    <h1>Message</h1>
</fieldset>

<div class="form">
    <div class="row-fluid">

        <div class="span12">
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'push_composer',
                    ));

            echo $form->errorSummary($pushMessageModel);

            echo $form->textArea($pushMessageModel, 'alert', array('class' => 'span12', 'rows' => 10));

            $this->endWidget();
            ?>
        </div>

    </div>
</div>