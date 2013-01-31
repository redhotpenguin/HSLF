<h3>Tenants:</h3>
<table  class="table table-striped table-bordered">
    <thead>
    <th>ID</th>
    <th>Name</th>
    <th>Display Name</th>
    <th>Address</th>
</thead>
<tbody>
    <?php
    foreach ($model->tenants as $tenant) {
        echo '<tr>';
        echo '<td>' . $tenant->id . '</td>';
        echo '<td>' . $tenant->name . '</td>';
        echo '<td>' . $tenant->display_name . '</td>';
        echo '<td>' . $tenant->email . '</td>';
        echo '</tr>';
    }
    ?>
</tbody>
</table>
<div class="row-fluid">
    <?php
    echo CHtml::label('Add to tenant account', 'add_to_project');
    echo CHtml::textField('add_to_project', '', array('placeholder' => 'Ex: ouroregon'));
    ?>
</div>
<br/>
<div class="row-fluid">
    <?php
    echo CHtml::label('Remove from tenant account', 'remove_from_project');
    echo CHtml::textField('remove_from_project', '', array('placeholder' => 'Ex: ouroregon'));
    ?>
</div>