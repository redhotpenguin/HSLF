<?php
if (empty($tenants)):
    echo '<h1>No Projects</h1>';



else:
    ?>


    <div class="hero-unit">
        <h1>My dashboard</h1>
        
        <br/>
            <?php
            foreach ($tenants as $tenant) {
                echo '<h2>';
                echo CHtml::link($tenant->display_name, "/admin/" . $tenant->name, array('class' => ''));
                echo '</h2>';
            }
            ?>

    <?php
    endif;
    ?>

</div>