
<h1>Your Projects</h1>
<br/>

<?php foreach ($userTenants as $userTenant) { ?>

    <div class="hero-unit">
        <h3> <a href="?tenantID=<?php echo $userTenant->tenant_id ?>"><?php echo $userTenant->tenant->display_name; ?></a></h3>
    </div>


    <?php
}

