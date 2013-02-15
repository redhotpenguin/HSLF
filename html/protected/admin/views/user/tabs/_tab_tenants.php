<h3>Tenants:</h3>
<table  class="table table-striped table-bordered">
    <thead>
    <th>ID</th>
    <th>Name</th>
    <th>Display Name</th>
    <th>Address</th>
    <th>Permissions</th>
</thead>
<tbody>
    <?php
    $roleOptions = $model->getRoleOptions();

    foreach ($model->tenants as $tenant) {
        $role = $model->getRoleByTenant($tenant->id);

        $permissionEditorLink = $this->createUrl('user/permission', array('tenantId' => $tenant->id, 'userId' => $model->id));

        echo '<tr>';
        echo '<td>' . $tenant->id . '</td>';
        echo '<td>' . $tenant->name . '</td>';
        echo '<td>' . $tenant->display_name . '</td>';
        echo '<td>' . $tenant->email . '</td>';
        echo '<td>' . CHtml::link("permissions", $permissionEditorLink, array("target" => '_blank')) . '</td>';
        echo '</tr>';
    }
    ?>
</tbody>
</table>
<div class="row-fluid">
    <?php
    echo CHtml::label('Add to tenant account', 'add_to_tenant');
    echo CHtml::textField('add_to_tenant', '', array('placeholder' => 'Ex: ouroregon'));
    ?>
</div>
<br/>
<div class="row-fluid">
    <?php
    echo CHtml::label('Remove from tenant account', 'remove_from_tenant');
    echo CHtml::textField('remove_from_tenant', '', array('placeholder' => 'Ex: ouroregon'));
    ?>
</div>