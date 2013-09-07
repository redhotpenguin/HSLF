<?php

$navBarItems = array(
    '',
    array('label' => 'Create', 'url' => array('create')),
    '',
);

if ($isAdmin) {
    array_push($navBarItems, array('label' => 'Export', 'url' => array('exportCSV')), ''
    );
}

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Tags';
$this->secondaryNav['url'] = array('tag/index');


$this->header = "Tags";
$this->introText = "Below is a list of all the Tags used in your app. To filter the list by Display Name or Name, type in the search bar and press enter. To filter by tag type, select from the drop down menu. You can also sort the tags by clicking the words “Display Name,” “Name” or “Type.”</p><p class='helpText'>Click the “Update” icon to edit an existing Tag or click the “Delete” icon to remove the Tag from your app. To add a new Tag, click the “Create” button above. To export a full list of Tags and their details, click the “Export” button above.</p>";


$this->widget('backend.extensions.ExtendedWidgets.GridView', array(
    'id' => 'tag-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'type' => 'striped',
    'columns' => array(
        array(
            'name' => 'display_name',
            'placeholder' => 'Search by Display Name',
        ),
        array(
            'name' => 'name',
            'placeholder' => 'Search by Name',
        ),
        array(
            'name' => 'type',
            'filter' => CHtml::dropDownList('Tag[type]', $model->type, array("" => 'Any') + Tag::model()->getTagTypes()),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}{delete}'
        ),
    ),
));
