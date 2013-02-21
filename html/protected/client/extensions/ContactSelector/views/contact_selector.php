<?php

// Helpers
function contactDropdown($modelName, $id = 'contactDropDown', $class = 'contact', $selected = null) {


    $contactList = CHtml::listData(Contact::model()->findAll(array('order' => 'first_name ASC')), 'id', function($contact) {
                        return $contact->first_name . " " . $contact->last_name;
                    });
    $options = array(
        'tabindex' => '1',
        'class' => 'span6 '.$class,
        'name' => 'contacts[]',
        'style'=>'float:left;'
    );

    $html =  '<div class="row-fluid" id=' . $id . '>' . CHtml::dropDownList($modelName.'[contacts][]', $selected, $contactList, $options);
    
    $html.='<div class="span3 deleteBtn btn btn-warning">delete</div></div>';
    
    return $html;
}
?>


<div class="clearfix"></div>

<div class="row-fluid">
    <div class="span6" id="contacts">
        <?php
        $className = get_class($model);
        
        if (empty($contacts))
            echo contactDropdown($className);

        else
            foreach ($contacts as $contact) {
                echo contactDropdown($className, 'contactDropDown', 'contact span6', $contact->id);
            }
        ?>
    </div>
</div>

<div class="row-fluid">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Add a contact',
        'type' => 'info',
        'htmlOptions' => array(
            'id'=>'addContactButton'
        ),
    ));
    ?>
</div>