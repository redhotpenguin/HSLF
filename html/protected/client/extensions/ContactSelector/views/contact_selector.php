<?php
$className = get_class($model);
$tenant = Yii::app()->user->getLoggedInUserTenant();
$siteUrl = Yii::app()->params['site_url'] . '/client/' . $tenant->name;


$contactList = array('Select Contact') + CHtml::listData(Contact::model()->findAll(array('order' => 'first_name ASC')), 'id', function($contact) {
                    return $contact->first_name . " " . $contact->last_name;
                });


$ns = "var contactSelector_ns  = {site_url: '$siteUrl', className: '$className'};";

Yii::app()->clientScript->registerScript('contactSelector_ns', $ns, CClientScript::POS_HEAD);
?>


<div class="clearfix"></div>


<table id="contacts" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th colspan="2">
                Contacts
                <?php
                if ($helpText) {
                    echo '<a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="' . $helpText . '"></a>';
                }
                ?>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($contacts))
            foreach ($contacts as $contact) {
                ?>
                <tr class="contactRow">
                    <td class='contactName'> 
                        <?php echo $contact->first_name . " " . $contact->last_name; ?>
                    </td>
                    <td>
                        <span  name="deleteContactBtn" class="btn btn-warning" >Remove</span>
                        <input type='hidden' name='<?php echo $className; ?>[contacts][]' value='<?php echo $contact->id; ?>'/> 
                    </td>
                </tr>
                <?php
            }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">  
                <?php
                $options = array(
                    'tabindex' => '1',
                    'class' => 'span11',
                    'name' => 'contacts[]',
                    'style' => 'float:left;',
                    'id' => 'contactDropdown',
                );

                echo CHtml::dropDownList('contactDropdown', '', $contactList, $options);
                ?>
                <div class="clearfix"></div>
                <?php
                if ($dropDownText) {
                    echo "<em>$dropDownText</em>";
                }
                ?>

            </td>    
        </tr>

    </tfoot>
</table>


<?php
$this->beginWidget(
        'bootstrap.widgets.TbModal', array(
    'id' => 'createContactModal',
    'autoOpen' => false,
));
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Create New Contact</h4>
</div>

<div class="modal-body">
<?php
$this->render('contact_creator', array('contact' => new Contact()));
?>
</div>

<div class="modal-footer">
<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'type' => 'primary',
    'label' => 'Save',
    'url' => '#',
    'htmlOptions' => array(
        'id' => 'save_contact_btn'),
));
?>
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Close',
        'url' => '#',
        'htmlOptions' => array('data-dismiss' => 'modal', 'id' => 'close_btn'),
    ));
    ?>
</div>

    <?php $this->endWidget(); ?><?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Create New Contact',
        'type' => 'info',
        'htmlOptions' => array(
            'data-toggle' => 'modal',
            'data-target' => '#createContactModal',
        ),
    ));