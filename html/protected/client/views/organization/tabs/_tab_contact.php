<div class="row-fluid">

    <div class="span6">
        <?php
        
        
        
        echo $form->labelEx($model, 'primary_contact_id');
        echo $form->textField($model, 'primary_contact_id', array('size' => 60, 'class' => 'span11', 'maxlength' => 512));
        echo $form->error($model, 'primary_contact_id');
        ?>
    </div>

</div>