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