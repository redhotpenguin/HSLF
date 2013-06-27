<div class="hero-unit">
    <h1>Dashboard</h1>
</div>
<div class="action_group">
    <?php
    usort($tenants, function($a, $b) {
                return strcmp($a->display_name, $b->display_name);
            });
    ?>

    <div class="section-divider">
        <h3>My Project<?php echo count($tenants) > 1 ? 's' : ''; ?></h3>
    </div>


    <?php
    foreach ($tenants as $tenant) {
        echo CHtml::link($tenant->display_name, "/client/" . $tenant->name, array('class' => "action_block fourth"));
    }
    ?>
</div>