<?php
/* @var $this TenantController */
/* @var $model Tenant */

$this->breadcrumbs=array(
	'Tenants'=>array('index'),
	$model->name,
);

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Tenants';
$this->secondaryNav['url'] =array('tenant/index');
?>

<h1>View Tenant #<?php echo $model->display_name; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'display_name',
		'creation_date',
		'web_app_url',
		'email',
		'api_key',
		'api_secret',
		'ua_dashboard_link',
		'cicero_user',
		'cicero_password',
		'ua_api_key',
		'ua_api_secret',
	),
)); ?>
