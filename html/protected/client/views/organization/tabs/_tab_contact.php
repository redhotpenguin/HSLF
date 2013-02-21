<div class="row-fluid">

    <div class="span6">
        <?php
        echo $form->labelEx($model, 'primary_contact_id');
        //  echo $form->textField($model, 'primary_contact_id', array('size' => 60, 'class' => 'span11', 'maxlength' => 512));
        echo $form->error($model, 'primary_contact_id');

        echo $form->hiddenField($model, 'primary_contact_id');

// ext is a shortcut for application.extensions
        $this->widget('backend.extensions.ExtendedAutoComplete.ExtendedAutoComplete', array(
            'name' => 'primary_contact_field',
            'source' => $this->createUrl('contact/findContact'),
// attribute_value is a custom property that returns the 
/// name of our related object -ie return $model->related_model->name
            'value' => $model->isNewRecord ? null : "{$model->primary_contact->first_name} {$model->primary_contact->last_name}",
            'options' => array(
                'minLength' => 1,
                'autoFill' => false,
                'focus' => 'js:function( event, ui ) {
            $( "#primary_contact_field" ).val( ui.item.first_name + " "+ ui.item.last_name );
            return false;
        }',
                'select' => 'js:function( event, ui ) {
            $("#' . CHtml::activeId($model, 'primary_contact_id') . '")
            .val(ui.item.id);
            return false;
        }'
            ),
            'htmlOptions' => array('class' => 'input-1 span6', 'autocomplete' => 'off', 'placeholder' => 'Primary contact name',
            ),
            'methodChain' => '.data( "autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>" + item.first_name + " "+ item.last_name + "</a>" )
            .appendTo( ul );
    };'
        ));
        ?>
    </div>

</div>

<div class="row-fluid">
    <?php
    $this->widget('backend.extensions.ContactSelector.ContactSelector', array(
        'model' => $model,
        'attribute' => '',
        'options' => array(
            'model_name' => 'Organization',
        ),
    ));
    ?>
</div>