
<div class="row-fluid">  
    <?php echo $form->textAreaRow($model, 'alert', array('class' => 'span12', 'rows' => '4')); ?>
</div>

<div class="row-fluid">
    <?php
    echo $form->labelEx($model, 'payload_id');

    echo $form->hiddenField($model, 'payload_id');

// ext is a shortcut for application.extensions
    $this->widget('admin.extensions.ExtendedAutoComplete.ExtendedAutoComplete', array(
        'name' => 'payload_field',
        'source' => $this->createUrl('payload/findPayload'),
// attribute_value is a custom property that returns the 
/// name of our related object -ie return $model->related_model->name
        'value' => $model->isNewRecord ? null : $model->payload->title,
        'options' => array(
            'minLength' => 1,
            'autoFill' => false,
            'focus' => 'js:function( event, ui ) {
            $( "#payload_field" ).val( ui.item.title );
            return false;
        }',
            'select' => 'js:function( event, ui ) {
            $("#' . CHtml::activeId($model, 'payload_id') . '")
            .val(ui.item.id);
            return false;
        }'
        ),
        'htmlOptions' => array('class' => 'input-1 span6', 'autocomplete' => 'off', 'placeholder' => 'Payload title',
        ),
        'methodChain' => '.data( "autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>" + item.title +  "</a>" )
            .appendTo( ul );
    };'
    ));

    echo $form->error($model, 'payload_id');
    ?>
</div>





<div>
    <?php echo $form->hiddenField($model, 'creation_date'); ?>
</div>