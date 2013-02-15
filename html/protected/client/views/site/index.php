<?php
if (empty($tenants)):
    echo '<h1>No Projects</h1>';
else:
    ?>


    <div class="hero-unit">
        <h1>Dashboard</h1>
        
        <br/>
            <?php
            foreach ($tenants as $tenant) {
                echo '<h3>';
                echo CHtml::link($tenant->display_name, "/client/" . $tenant->name, array('class' => ''));
                echo '</h3>';
            }
            ?>

    <?php
    endif;
    ?>

</div>