<div class="row-fluid">

    <div class="span6">
        <?php
        echo $form->labelEx($model, 'primary_contact_id');
        //  echo $form->textField($model, 'primary_contact_id', array('size' => 60, 'class' => 'span11', 'maxlength' => 512));
        echo $form->error($model, 'primary_contact_id');


        $list = CHtml::listData(Contact::model()->findAll(array('order' => 'first_name ASC')), 'id', function($contact) {
                            return $contact->first_name . " " . $contact->last_name;
                        });
                        
        echo $form->dropDownList($model, 'primary_contact_id', $list, array('class' => 'span11'));
        ?>
    </div>

</div>
<br/>
<div class="row-fluid">
    <?php
    echo $form->labelEx($model, 'contacts');

    $this->widget('backend.extensions.ContactSelector.ContactSelector', array(
        'model' => $model,
        'attribute' => '',
        'options' => array(
            'model_name' => 'Organization',
        ),
    ));
    ?>
</div>