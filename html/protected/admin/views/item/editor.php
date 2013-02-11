<br/><br/><br/>
<?php
$navBarItems = array(
    array('label' => 'Manage ballot items', 'url' => array('admin')),
);

if (!$model->isNewRecord) {
    array_push($navBarItems, '', array('label' => 'Delete this ballot item', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')), '', array('label' => 'Create another ballot item', 'url' => array('create'),
    ));
}

$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => '',
    'brandUrl' => '#',
    'htmlOptions' => array('class' => 'subnav'),
    'collapse' => true, // requires bootstrap-responsive.css
    'items' => array(
        array(
            'class' => 'bootstrap.widgets.TbMenu',
            'items' => $navBarItems
        ),
    ),
));

?>


<?php
echo $this->renderPartial('_form', array(
    'model' => $model,
    'organization_list' => $organization_list,
        )
);


