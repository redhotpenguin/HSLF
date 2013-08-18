<div class="hero-unit">
    <h1>Dashboard</h1>
</div>
<div class="tenantGroup action_group">
    <?php

    // UI Helper
    function getBlockClassSize($number) {
        $classBlock = 'whole';

        if ($number == 4) {
            $classBlock = 'fourth';
        } elseif ($number == 3) {
            $classBlock = 'third';
        } elseif ($number == 2) {
            $classBlock = 'half';
        }

        return $classBlock;
    }

    usort($tenants, function($a, $b) {
                return strcmp($a->display_name, $b->display_name);
            });

    $tenantNumber = count($tenants);
    ?>

    <div class="section-divider">
        <h3>My Project<?php echo $tenantNumber > 1 ? 's' : ''; ?></h3>
    </div>

    <?php

    $tenantGroups = array_chunk($tenants, 4);

    foreach ($tenantGroups as $tenants) {
        
        $blockClass = getBlockClassSize(count($tenants));

        foreach ($tenants as $tenant) {
             echo CHtml::link($tenant->display_name, "/client/" . $tenant->name, array('class' => "action_block $blockClass"));
        }
    }
    ?>
</div>