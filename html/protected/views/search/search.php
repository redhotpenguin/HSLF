

<h3>Search Results for: "<?php echo CHtml::encode($term); ?>"</h3>
<?php if (!empty($results)): ?>
    <?php foreach ($results as $result):
        ?>                  
        <p><?php echo CHtml::link($result->title, CHtml::encode($result->link)); ?></p>
        <p><?php
        echo CHtml::encode($result->content);
        // echo $query->highlightMatches(CHtml::encode($result->content)); 
        ?>

        </p>
        <hr/>
    <?php endforeach; ?>

<?php else: ?>
    <p class="error">No results matched your search terms.</p>
<?php endif; ?>