<?php
$className = get_class($model);

$contactList = CHtml::listData(Contact::model()->findAll(array('order' => 'first_name ASC')), 'id', function($contact) {
                    return $contact->first_name . " " . $contact->last_name;
                });


$jsonContactList = CJSON::encode($contactList);

$dropDownName = $className.'[contacts][]';
        

$ns = "var contactSelector_ns  = {contactList: $jsonContactList, dropDownName:'$dropDownName'};";

Yii::app()->clientScript->registerScript('contactSelector_ns', $ns, CClientScript::POS_HEAD);

// Helpers
function contactDropdown($modelName, $contactList, $id = 'contactDropDown', $class = 'contact', $selected = null) {




    $options = array(
        'tabindex' => '1',
        'class' => 'span11 ' . $class,
        'name' => 'contacts[]',
        'style' => 'float:left;'
    );

    $html = '<div class="row-fluid" id=' . $id . '>' . CHtml::dropDownList($modelName . '[contacts][]', $selected, $contactList, $options);

    $html.='<div class="span3 deleteBtn btn btn-warning">delete</div></div>';

    return $html;
}
?>


<div class="clearfix"></div>

<div class="row-fluid">
    <div class="span11" id="contacts">
        <?php
        if (!empty($contacts))
            foreach ($contacts as $contact) {
                echo contactDropdown($className, $contactList, 'contactDropDown', 'contact span6', $contact->id);
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
        'id' => 'addContactButton'
    ),
));
?>
</div>