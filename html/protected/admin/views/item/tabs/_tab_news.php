    <?php
    if ($model->id):
        $new_item_news_url = CHtml::normalizeUrl(array('itemNews/add', 'item_id' => $model->id));

        echo CHtml::link('Add a news update', $new_item_news_url, array('target' => '_blank'));
        ?>
        <br/>
        <br/>

        <?php
        if ($model->itemNews):

            foreach ($model->itemNews as $itemNew) {
                ?>

                <div class="news_item">
                    <span class="btn floatright">


                        <?php
                        $edit_item_news_url = CHtml::normalizeUrl(array('itemNews/update', 'id' => $itemNew->id));
                        echo CHtml::link('Edit', $edit_item_news_url);
                        ?>

                    </span>

                    <b> <?php echo $itemNew->title; ?>:</b>
                    <br/>
                    <p><?php echo $itemNew->getExcerpt() ?></p>

                    <?php ?> 
                </div>
                <?php
            }

        else:
            echo 'No news updates';
        endif;

    else:
        echo 'You must save an item before you can add a news.';
    endif; // end test $model->itemNews
    ?>