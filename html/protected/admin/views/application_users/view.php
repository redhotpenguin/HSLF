<?php
$this->breadcrumbs = array(
    'Application Users' => array('index'),
    $model->id,
);

$this->menu = array(
    array('label' => 'List application users', 'url' => array('index')),
    array('label' => 'Update application users', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete application users', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage application users', 'url' => array('admin')),
);
?>

<h1>View application user #<?php echo $model->id; ?></h1>

<?php
$state_name = $model->district->stateAbbr->name;
$district_type = $model->district->type;
$district_number = $model->district->number;


$district = $state_name . ' ' . $district_type . ' - ' . $district_number;

$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        array(
            'name' => 'district_id',
            'value' => $district
        ),
        'device_token',
        'latitude',
        'longitude',
        'registration',
        'type',
        'user_agent',
        'uap_user_id',
    ),
));
?>

<br/>
<h1>Extra user data:</h1>


<?php
$user_metas = $model->getAllMeta();

if (!empty($user_metas)) {
    echo '<table>';
    foreach ($user_metas as $meta) {
        echo '<tr>';
        echo '<td>' . $meta['meta_key'] . '</td>';
        echo '<td>' . $meta['meta_value'] . '</td>';
        echo '</tr>';
    }

    echo '</table>';
}
?>


<h1>User Tags:</h1>
<?php
$user_tags = $model->tags;

if (!empty($user_tags)) {
    foreach ($user_tags as $tag) {
        echo $tag->name . ',';
    }
}
?>